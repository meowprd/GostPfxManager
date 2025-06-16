<?php

namespace PurrPHP\Event;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class Event implements StoppableEventInterface {
  
  private bool $stopped = false;

  public function isPropagationStopped(): bool { return $this->stopped; }
  public function stopPropagation(): void { $this->stopped = true; }
}