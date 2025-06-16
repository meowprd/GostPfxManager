<?php

namespace PurrPHP\Controller;

use League\Container\Container;
use PurrPHP\Http\Request;
use PurrPHP\Http\Response;
use Rakit\Validation\Validator;

abstract class AbstractController {

  protected ?Container $container = null;
  protected Request $request;
  protected Validator $validator;

  public function setContainer(Container $container): void {
    $this->container = $container;
  }

  public function render(string $view, array $data = array(), Response $response = null): Response {
    $response ??= new Response();
    return $response->setContent($this->container->get('twig')->render($view, $data));
  }

  public function setRequest($request): self {
    $this->request = $request;
    return $this;
  }

  public function setValidator(Validator $validator): self {
    $this->validator = $validator;
    return $this;
  }
}