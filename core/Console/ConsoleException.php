<?php

namespace PurrPHP\Console;

class ConsoleException extends \Exception {

  public function __construct($message = "", $code = 0, $previous = null) {
    echo(
      ConsoleColors::LIGHT_RED . 
      '[FATAL ERROR]: ' . $message . 
      PHP_EOL
    );
    exit(1);
  }
}