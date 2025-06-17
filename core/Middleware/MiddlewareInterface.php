<?php

namespace PurrPHP\Middleware;

use PurrPHP\Http\Request;
use PurrPHP\Http\Response;
use Doctrine\DBAL\Connection;

interface MiddlewareInterface {
  
  public function process(Request $request, Connection $database, RequestHandlerInterface $handler): Response;
}