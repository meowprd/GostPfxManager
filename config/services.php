<?php

/* ------------------------------ Used services ----------------------------- */
use PurrPHP\Routing\RouterInterface;
use PurrPHP\Routing\Router;

// middlewares
use PurrPHP\Middleware\RequestHandlerInterface;
use PurrPHP\Middleware\RequestHandler;
use PurrPHP\Middleware\RouteMiddlewares;
use PurrPHP\Middleware\Handlers\RouterDispatch;

// events
use PurrPHP\Event\EventDispatcher;

// http kernel
use PurrPHP\Http\Kernel;

// sessions
use PurrPHP\Session\SessionInterface;
use PurrPHP\Session\Session;

// twig
use Twig\Environment;
use PurrPHP\Template\TwigFactory;

// validator
use Rakit\Validation\Validator;

// controllers
use PurrPHP\Controller\AbstractController;

// database
use PurrPHP\Database\DatabaseFactory;
use Doctrine\DBAL\Connection;

// CLI
use PurrPHP\Console\Kernel as ConsoleKernel;
use PurrPHP\Console\Application;
use PurrPHP\Console\Commands\DatabaseMigrateCommand;

/* ----------------------------- Init container ----------------------------- */
use League\Container\Container;
use League\Container\ReflectionContainer;
use League\Container\Argument\Literal\ArrayArgument;
use League\Container\Argument\Literal\StringArgument;
$container = new Container();
$container->delegate(new ReflectionContainer(true));

/* --------------------------- Get database config -------------------------- */
$databaseConfig = require(CONFIG_PATH . '/database.php');

/* ---------------------------- String translate ---------------------------- */
function __(string $key){
    $translations = require_once(CONFIG_PATH . "/translate.php");
    return $translations[$key] ?? $key;
}

/* ------------------------------ Init services ----------------------------- */
// Init Router
$container->add(RouterInterface::class, Router::class);
$container->extend(RouterInterface::class);

// Init Middlewares
$container->add(RequestHandlerInterface::class, RequestHandler::class)
  ->addArgument($container)
  ->addArgument(Connection::class);

$container->add(RouterDispatch::class)
  ->addArgument(RouterInterface::class)
  ->addArgument($container);

$container->add(RouteMiddlewares::class)
  ->addArgument(new StringArgument(ROUTES_PATH));

// Init Events
$container->addShared(EventDispatcher::class);

// Init Kernel
$container->add(Kernel::class)
  ->addArgument($container)
  ->addArgument(RequestHandlerInterface::class)
  ->addArgument(EventDispatcher::class);

// Init sessions
$container->addShared(SessionInterface::class, Session::class);

// Init twig
$container->add('twig-factory', TwigFactory::class)
  ->addArgument(SessionInterface::class);

$container->addShared('twig', function() use ($container): Environment {
  return $container->get('twig-factory')->create();
});

// Init validator
// docs: https://github.com/rakit/validation
$container->addShared(Validator::class)
  ->addArgument(new ArrayArgument(array())); // custom messages 


// Init abstract controller
$container->inflector(AbstractController::class)
  ->invokeMethod('setContainer', array($container))
  ->invokeMethod('setValidator', array($container->get(Validator::class)));

// Init database
$container->add(DatabaseFactory::class)
  ->addArgument(new ArrayArgument($databaseConfig));

$container->addShared(Connection::class, function() use ($container): Connection {
  return $container->get(DatabaseFactory::class)->create();
});

// Init console
$container->add(Application::class)
  ->addArgument($container);

$container->add(ConsoleKernel::class)
  ->addArgument($container)
  ->addArgument(Application::class);

// Init console commands
$container->add('db:migrate', DatabaseMigrateCommand::class)
  ->addArgument(Connection::class);

/* ---------------------------- Return container ---------------------------- */
return $container;