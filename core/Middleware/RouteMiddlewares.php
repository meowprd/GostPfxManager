<?php

namespace PurrPHP\Middleware;

use Doctrine\DBAL\Connection;
use PurrPHP\Http\Request;
use PurrPHP\Middleware\RequestHandlerInterface;
use PurrPHP\Http\Response;

use FastRoute\RouteCollector;
use FastRoute\Dispatcher;
use function FastRoute\simpleDispatcher;

use PurrPHP\Exceptions\MethodNotAllowedException;
use PurrPHP\Exceptions\RouteNotFoundException;

use PurrPHP\Controller\AbstractController;


class RouteMiddlewares implements MiddlewareInterface {

  private array $routes = array();

  public function __construct(
    private string $routesPath,
  ) {}

  public function process(Request $request, Connection $database, RequestHandlerInterface $requestHandler): Response {
    $dispatcher = $this->registerRoutes();
    $routeInfo = $dispatcher->dispatch($request->method(), $request->uri());
    
    switch($routeInfo[0]) {
      case Dispatcher::FOUND: 
        [$status, $handler, $vars] = $routeInfo;
        $request->setRouteHandler($handler[0]);
        $request->setRouteVars($vars);
        $requestHandler->injectMiddleware($handler[1]);
        break;
      case Dispatcher::METHOD_NOT_ALLOWED: throw new MethodNotAllowedException("{$request->method()} method not allowed");
      default: throw new RouteNotFoundException("Route {$request->method()} {$request->uri()} not found");
    }
    return $requestHandler->handle($request);
  }

  private function registerRoutes(): Dispatcher {
    $this->routes = include($this->routesPath);
    return simpleDispatcher(function(RouteCollector $collector) {
      foreach($this->routes as $route) {
        $collector->addRoute(...$route);
      }
    });
  }
}