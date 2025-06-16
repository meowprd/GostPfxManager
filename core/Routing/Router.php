<?php

namespace PurrPHP\Routing;

use League\Container\Container;
use PurrPHP\Http\Request;

use PurrPHP\Controller\AbstractController;

class Router implements RouterInterface {

  private array $routes = array();

  public function dispatch(Request $request, Container $container): array {
    $handler = $request->routeHandler();
    $vars = $request->routeVars();

    if(is_array($handler)) {
      [$controller, $method] = $handler;
      $controller = $container->get($controller);
      if(is_subclass_of($controller, AbstractController::class)) { $controller->setRequest($request); }
      return array(array($controller, $method), $vars);
    }
    return array($handler, $vars);
  }

}