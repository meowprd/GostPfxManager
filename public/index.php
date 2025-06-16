<?php
require_once(dirname(__DIR__) . '/config/app.php');
require_once(APP_PATH . '/vendor/autoload.php');

use PurrPHP\Http\Kernel;
use PurrPHP\Http\Request;
use PurrPHP\Event\EventDispatcher;

/** @var \League\Container\Container $container */
$container = require(CONFIG_PATH . '/services.php');

// register event listeners
$listenersList = require(CONFIG_PATH . '/eventListeners.php');
foreach($listenersList as $event => $listeners) {
  foreach(array_unique($listeners) as $listener) {
    $container->get(EventDispatcher::class)->addListener($event, new $listener);
  }
}

$kernel = $container->get(Kernel::class);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);