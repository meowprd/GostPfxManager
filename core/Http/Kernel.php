<?php

namespace PurrPHP\Http;

use PurrPHP\Http\Response;

use PurrPHP\Event\EventDispatcher;
use PurrPHP\Event\ResponseEvent;

use League\Container\Container;

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

use PurrPHP\Middleware\RequestHandlerInterface;

use PurrPHP\Exceptions\RouteNotFoundException;
use PurrPHP\Exceptions\MethodNotAllowedException;

class Kernel {

  public function __construct(
    private readonly Container $container,
    private readonly RequestHandlerInterface $requestHandler,
    private readonly EventDispatcher $eventDispatcher
  ) { $this->registerWhoops(); }

  public function handle(Request $request): Response {
    try {
      $response = $this->requestHandler->handle($request);
    } catch(\Exception $e) {
      $response = $this->resolveException($e);
    }

    $this->eventDispatcher->dispatch(new ResponseEvent($request, $response));
    return $response;
  }

  public function terminate(Request $request, Response $response) {
    $request->session()?->removeFlash();
  }

  private function resolveException(\Exception $e): Response {
    if(DEBUG) { throw $e; }
    
    if($e instanceof RouteNotFoundException) {
      return new Response($e->getMessage(), 404);
    } elseif($e instanceof MethodNotAllowedException) {
      return new Response($e->getMessage(), 405);
    } else {
      return new Response($e->getMessage(), 500);
    }
  }

  private function registerWhoops() { 
    if(!DEBUG) { return; }
    $run = new Run(); // whoops run
    $handler = new PrettyPageHandler(); // whoops handler
    
    $handler->setPageTitle('PurrPHP - Exception!');
    $run->pushHandler($handler);
    $run->register();
  }
}