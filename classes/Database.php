<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;

class Database
{
    private ?PDO $connection = null;

    public function __construct()
    {
        $this->connect();
    }

    protected function getConnection(): ?PDO
    {
        return $this->connection;
    }

    private function connect(): void
    {
        $config = require __DIR__ . '/../config/database.php';

        try {
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['dbname'],
                $config['charset']
            );

            $this->connection = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            $this->connection = null;
        }
    }
}
