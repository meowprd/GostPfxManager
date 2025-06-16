<?php

namespace PurrPHP\Middleware;

use PurrPHP\Http\Request;
use PurrPHP\Http\Response;

interface RequestHandlerInterface {

  public function handle(Request $request): Response;
  public function injectMiddleware(array $middleware): void;
}