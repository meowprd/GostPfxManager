<?php

namespace PurrPHP\Entity;

use Doctrine\DBAL\Connection;
use PurrPHP\Event\EventDispatcher;
use PurrPHP\Event\ServiceEvent;

class EntityService {

  public function __construct(
    private Connection $connection,
    private EventDispatcher $eventDispatcher
  ) {}

  public function connection(): Connection { return $this->connection; }

  public function save(AbstractEntity $entity): int {
    $id = $this->connection->lastInsertId();
    $entity->setId($id);
    $this->eventDispatcher->dispatch(new ServiceEvent($entity));
    return $id;
  }
}