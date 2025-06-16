<?php

namespace PurrPHP\Console;
use League\Container\Container;

class Application {
  
  public function __construct(
    private Container $container
  ) {}

  public function run(): int {
    $argv = $_SERVER['argv'];
    $cmd = $argv[1] ?? null;
    if(is_null($cmd)) { throw new ConsoleException('Command is not specified'); }

    try {
      $command = $this->container->get($cmd);
      $command->execute($this->parseParams(array_slice($argv, 2)));
    } catch(\Exception $e) {
      throw new ConsoleException($e->getMessage());
    }
    echo(ConsoleColors::RESET);
    return 0;
  }

  private function parseParams(array $params): array {
    $args = array();
    foreach($params as $param) {
      if(str_starts_with($param, '--')) {
        $option = explode('=', substr($param, 2));
        $args[$option[0]] = $option[1];
      }
    }
    return $args;
  }
}