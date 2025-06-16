<?php

namespace PurrPHP\Middleware;

use PurrPHP\Http\Request;
use PurrPHP\Http\Response;

interface MiddlewareInterface {
  
  public function process(Request $request, RequestHandlerInterface $handler): Response;
}