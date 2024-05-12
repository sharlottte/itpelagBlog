<?php

declare(strict_types=1);

namespace Sharlottte\Itpelag\Common;

use Doctrine\DBAL\Connection;

class Migrator
{
    public function __construct(private Connection $connection)
    {
    }

    public function run(): void
    {
        $this->ensureMigrationsTableExists();

        $migrationFiles = glob(__DIR__.'/../../migrations/*.sql');
        $executedMigrations = $this->connection->fetchFirstColumn('SELECT `name` FROM `migrations`');
        foreach ($migrationFiles as $migrationFile) {
            $migrationName = mb_substr(basename($migrationFile), 0, -4);
            if (!\in_array($migrationName, $executedMigrations, true)) {
                $this->runMigration($migrationFile, $migrationName);
            }
        }
    }

    private function ensureMigrationsTableExists(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `migrations` (
            `name` VARCHAR(255) NOT NULL PRIMARY KEY,
            `executed_at` DATETIME NOT NULL
        )';

        $this->connection->executeStatement($sql);
    }

    private function runMigration(string $migrationFile, string $migrationName): void
    {
        $sql = file_get_contents($migrationFile);

        $this->connection->executeStatement($sql);
        $this->connection->insert('migrations', [
            'name' => $migrationName,
            'executed_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);
    }
}
