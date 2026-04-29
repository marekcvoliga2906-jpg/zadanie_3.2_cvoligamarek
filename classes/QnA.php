<?php

declare(strict_types=1);

namespace App;

use PDOException;

final class QnA extends Database
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        parent::__construct();
        $this->filePath = $filePath;
    }

    public function getAllItems(): array
    {
        $connection = $this->getConnection();

        if ($connection === null) {
            return $this->loadFromFile();
        }

        try {
            $queries = [
                'SELECT question, answer FROM qna',
                'SELECT otazka AS question, odpoved AS answer FROM qna',
            ];

            foreach ($queries as $query) {
                try {
                    $statement = $connection->query($query);
                } catch (PDOException $exception) {
                    continue;
                }

                if ($statement === false) {
                    continue;
                }

                $items = $statement->fetchAll();

                if (
                    is_array($items) &&
                    count($items) > 0 &&
                    isset($items[0]['question']) &&
                    isset($items[0]['answer'])
                ) {
                    return $items;
                }
            }

            return $this->loadFromFile();
        } catch (PDOException $exception) {
            return $this->loadFromFile();
        }
    }

    private function loadFromFile(): array
    {
        if (!is_file($this->filePath)) {
            return [];
        }

        $json = file_get_contents($this->filePath);

        if ($json === false) {
            return [];
        }

        $items = json_decode($json, true);

        return is_array($items) ? $items : [];
    }
}
