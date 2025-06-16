<?php

namespace PurrPHP\Event;
use PurrPHP\Http\Request;
use PurrPHP\Http\Response;

class ResponseEvent extends Event {

  public function __construct(
    private readonly Request $request,
    private readonly Response $response
  ) {}

  public function request() { return $this->request; }
  public function response() { return $this->response; }
}