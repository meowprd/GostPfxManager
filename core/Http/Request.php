<?php

namespace PurrPHP\Http;
use PurrPHP\Session\SessionInterface;

class Request {

  private SessionInterface $session;
  private mixed $routeHandler;
  private array $routeVars;

  public function __construct(
    private readonly array $get,
    private readonly array $post,
    private readonly array $cookie,
    private readonly array $files,
    private readonly array $server
  ) {}

  public static function createFromGlobals(): static {
    return new static($_GET, $_POST, $_COOKIE, $_FILES, $_SERVER);
  }

  public function method(): string {
    return $this->server['REQUEST_METHOD'];
  }

  public function uri(): string {
    return strtok($this->server['REQUEST_URI'], '?');
  }

  public function input(string $key, $default = null): mixed {
    return $this->get[$key] ?? $this->post[$key] ?? $default;
  }

  public function session() { return $this->session; }

  public function setSession($session) { $this->session = $session; return $this; }

  public function routeVars() { return $this->routeVars; }

  public function setRouteVars($routeVars) { $this->routeVars = $routeVars; return $this; }

  public function routeHandler() { return $this->routeHandler; }

  public function setRouteHandler($routeHandler) { $this->routeHandler = $routeHandler; return $this; }
}