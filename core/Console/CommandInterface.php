<?php

namespace PurrPHP\Console;

interface CommandInterface {

  public function execute(array $params = array()): int;
}