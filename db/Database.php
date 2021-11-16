<?php

namespace azadkh\mvcframework\db;

use azadkh\mvcframework\Application;
use PDO;

class Database
{
    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn = $config['dsn'] ?? '';
        $user = $config['user'] ?? '';
        $password = $config['password'] ?? '';
        $this->pdo = new PDO($dsn, $user, $password);
    }

    public function applyMigartions()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $newMigrations = [];
        $files = scandir(Application::$root_dir . '/migrations');
        $toApplyMigrtaions = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigrtaions as $migration) {
            if ($migration === '.' || $migration === '..') {
                continue;
            }
            require_once Application::$root_dir . '/migrations/' . $migration;
            $classname = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $classname();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applyed migration $migration");
            $newMigrations[] = $migration;
        }
        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            $this->log("All migrations are applied");
        }
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=INNODB;");
    }

    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare('SELECT migration FROM migrations');
        $statement->execute();

        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function saveMigrations(array $migrations)
    {

        $str = implode(",", array_map(fn ($m) => "('$m')", $migrations));

        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $str");
        $statement->execute();
    }

    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    public function log(string $message)
    {
        echo '[' . date('Y-m-d H:i:s') . '] - ' . $message . PHP_EOL;
    }
}
