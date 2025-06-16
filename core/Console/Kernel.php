<?php

namespace PurrPHP\Console;
use League\Container\Container;

class Kernel {

  public function __construct(
    private Container $container,
    private Application $application
  ) {}

  public function handle(): int {
    $this->registerCommands();
    return $this->application->run();
  }

  private function registerCommands(): void {
    $files = new \DirectoryIterator(__DIR__ . '/Commands');
    foreach($files as $file) {
      if(!$file->isFile()) { continue; }

      $command = COMMANDS_NAMESPACE . $file->getBasename('.php');
      if(is_subclass_of($command, CommandInterface::class)) {
        $cmd = (new \ReflectionClass($command))->getProperty('command')->getDefaultValue();
        $this->container->add($cmd, $command);
      }
    }
  }
}