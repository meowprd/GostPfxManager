<?php

namespace PurrPHP\Routing;

class Route {
  
  public static function get(string $uri, array|callable $handler, array $middleware = array()): array {
    return array('GET', $uri, array($handler, $middleware));
  }

  public static function post(string $uri, array|callable $handler, array $middleware = array()): array {
    return array('POST', $uri, array($handler, $middleware));
  }

  public static function put(string $uri, array|callable $handler, array $middleware = array()): array {
    return array('PUT', $uri, array($handler, $middleware));
  }

  public static function delete(string $uri, array|callable $handler, array $middleware = array()): array {
    return array('DELETE', $uri, array($handler, $middleware));
  }
}