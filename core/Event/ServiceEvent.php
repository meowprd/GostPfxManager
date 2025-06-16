<?php

namespace PurrPHP\Event;

use PurrPHP\Entity\AbstractEntity;

class ServiceEvent extends Event {

  public function __construct(
    private AbstractEntity $service
  ) {}

  public function service(): AbstractEntity { return $this->service; }
}