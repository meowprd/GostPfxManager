<?php

namespace PurrPHP\Middleware\Handlers;

use Doctrine\DBAL\Connection;
use PurrPHP\Http\Request;
use PurrPHP\Http\Response;
use PurrPHP\Session\SessionInterface;
use PurrPHP\Middleware\RequestHandlerInterface;
use PurrPHP\Middleware\MiddlewareInterface;

class StartSession implements MiddlewareInterface {
  
  public function __construct(
    private SessionInterface $session
  ) {}

  public function process(Request $request, Connection $database, RequestHandlerInterface $handler): Response {
    $this->session->start();
    $request->setSession($this->session);
    return $handler->handle($request);
  }
}