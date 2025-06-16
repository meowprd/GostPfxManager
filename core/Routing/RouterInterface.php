<?php

namespace PurrPHP\Routing;

use League\Container\Container;
use PurrPHP\Http\Request;

interface RouterInterface {
  
  public function dispatch(Request $request, Container $container): array;
}