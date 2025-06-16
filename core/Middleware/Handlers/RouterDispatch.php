<?php

namespace PurrPHP\Middleware\Handlers;

use PurrPHP\Http\Request;
use PurrPHP\Http\Response;
use PurrPHP\Routing\RouterInterface;
use League\Container\Container;
use PurrPHP\Middleware\RequestHandlerInterface;
use PurrPHP\Middleware\MiddlewareInterface;

class RouterDispatch implements MiddlewareInterface {
  
  public function __construct(
    private RouterInterface $router,
    private Container $container
  ) {}

  public function process(Request $request, RequestHandlerInterface $handler): Response {
    [$handler, $vars] = $this->router->dispatch($request, $this->container);
    return call_user_func_array($handler, $vars);
  }
}