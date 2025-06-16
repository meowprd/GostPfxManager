<?php

namespace PurrPHP\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;

use PurrPHP\Console\CommandInterface;
use PurrPHP\Console\ConsoleColors;

class DatabaseMigrateCommand implements CommandInterface {

  private string $command = 'db:migrate';
  
  private const MIGRATIONS_TABLE = 'migrations';

  public function __construct(
    private Connection $connection
  ) {}

  public function execute(array $params = array()): int {
    echo(ConsoleColors::LIGHT_CYAN . 'Running ' . $this->command . PHP_EOL);

    $this->createMigrationsTable();
    $appliedMigrations = $this->getMigrationsList();
    $migrationsFiles = $this->getMigrationFiles();
    $newMigrations = array_values(array_diff($migrationsFiles, $appliedMigrations));

    foreach($newMigrations as $migration) {
      $this->connection->beginTransaction();
      try {
        $schema = new Schema();
        echo(ConsoleColors::LIGHT_CYAN . "Preparing {$migration}..." . PHP_EOL);
        $migrationInstance = require MIGRATIONS_PATH . "/{$migration}";
        $migrationInstance->up($schema);
        echo(ConsoleColors::LIGHT_YELLOW . "Prepared {$migration}" . PHP_EOL);
        $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
        echo(ConsoleColors::LIGHT_CYAN . "Applying migrations {$migration}..." . PHP_EOL);
        $this->connection->executeQuery($sqlArray[0]);
        echo(ConsoleColors::LIGHT_GREEN . 'Done!' . PHP_EOL);
        $this->addMigrationToTable($migration);
      } catch(\Throwable $e) {
        echo(ConsoleColors::LIGHT_RED . 'Error while applying migration!' . PHP_EOL);
        $this->connection->rollBack();
        throw $e;
      }
    }
    return 0;
  }

  private function createMigrationsTable(): void {
    $manager = $this->connection->createSchemaManager();

    if(!$manager->tableExists(self::MIGRATIONS_TABLE)) {
      echo(ConsoleColors::LIGHT_CYAN . 'Creating ' . self::MIGRATIONS_TABLE . ' table...' . PHP_EOL);
      $schema = new Schema();

      $table = $schema->createTable(self::MIGRATIONS_TABLE);
      $table->addColumn('id', Types::INTEGER, array('unsigned' => true, 'autoincrement' => true));
      $table->setPrimaryKey(array('id'));
      $table->addColumn('migration', Types::STRING, array('length' => 255));
      $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, array('default' => 'CURRENT_TIMESTAMP'));

      $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());
      $this->connection->executeQuery($sqlArray[0]);
      echo(ConsoleColors::LIGHT_GREEN . 'Done!' . PHP_EOL);
    }
  }

  private function getMigrationsList(): array {
    $queryBuilder = $this->connection->createQueryBuilder();
    return $queryBuilder
      ->select('migration')
      ->from(self::MIGRATIONS_TABLE)
      ->executeQuery()
      ->fetchFirstColumn();
  }

  private function getMigrationFiles(): array {
    $files = scandir(MIGRATIONS_PATH);
    return array_values(array_filter($files, function($name) { return !in_array($name, array('.', '..')); }));
  }

  private function addMigrationToTable(string $migration): void {
    $queryBuilder = $this->connection->createQueryBuilder();
    $queryBuilder
      ->insert(self::MIGRATIONS_TABLE)
      ->values(array('migration' => ':migration'))
      ->setParameter('migration', $migration)
      ->executeQuery();
  }
}