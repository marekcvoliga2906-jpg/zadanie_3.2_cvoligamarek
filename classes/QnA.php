<?php

declare(strict_types=1);

namespace App;

use PDO;
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
        $columns = $this->detectColumns();

        if ($connection === null || $columns === null) {
            return $this->loadFromFile();
        }

        try {
            $query = sprintf(
                'SELECT %s AS id, %s AS question, %s AS answer FROM qna ORDER BY %s DESC',
                $columns['id'],
                $columns['question'],
                $columns['answer'],
                $columns['id']
            );

            $statement = $connection->query($query);

            if ($statement !== false) {
                $items = $statement->fetchAll();

                if (is_array($items)) {
                    return $items;
                }
            }

            return $this->loadFromFile();
        } catch (PDOException $exception) {
            return $this->loadFromFile();
        }
    }

    public function createItem(string $question, string $answer): bool
    {
        $connection = $this->getConnection();
        $columns = $this->detectColumns();

        if ($connection === null || $columns === null) {
            return false;
        }

        try {
            $query = sprintf(
                'INSERT INTO qna (%s, %s) VALUES (:question, :answer)',
                $columns['question'],
                $columns['answer']
            );

            $statement = $connection->prepare($query);

            return $statement->execute([
                ':question' => $question,
                ':answer' => $answer,
            ]);
        } catch (PDOException $exception) {
            return false;
        }
    }

    public function updateItem(int $id, string $question, string $answer): bool
    {
        $connection = $this->getConnection();
        $columns = $this->detectColumns();

        if ($connection === null || $columns === null) {
            return false;
        }

        try {
            $query = sprintf(
                'UPDATE qna SET %s = :question, %s = :answer WHERE %s = :id',
                $columns['question'],
                $columns['answer'],
                $columns['id']
            );

            $statement = $connection->prepare($query);

            return $statement->execute([
                ':id' => $id,
                ':question' => $question,
                ':answer' => $answer,
            ]);
        } catch (PDOException $exception) {
            return false;
        }
    }

    public function deleteItem(int $id): bool
    {
        $connection = $this->getConnection();
        $columns = $this->detectColumns();

        if ($connection === null || $columns === null) {
            return false;
        }

        try {
            $query = sprintf('DELETE FROM qna WHERE %s = :id', $columns['id']);
            $statement = $connection->prepare($query);

            return $statement->execute([':id' => $id]);
        } catch (PDOException $exception) {
            return false;
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

    private function detectColumns(): ?array
    {
        $connection = $this->getConnection();

        if ($connection === null) {
            return null;
        }

        try {
            $statement = $connection->query('SHOW COLUMNS FROM qna');

            if ($statement === false) {
                return null;
            }

            $dbColumns = $statement->fetchAll(PDO::FETCH_COLUMN);

            if (!is_array($dbColumns) || count($dbColumns) === 0) {
                return null;
            }

            if (in_array('id', $dbColumns, true) && in_array('question', $dbColumns, true) && in_array('answer', $dbColumns, true)) {
                return [
                    'id' => 'id',
                    'question' => 'question',
                    'answer' => 'answer',
                ];
            }

            if (in_array('id', $dbColumns, true) && in_array('otazka', $dbColumns, true) && in_array('odpoved', $dbColumns, true)) {
                return [
                    'id' => 'id',
                    'question' => 'otazka',
                    'answer' => 'odpoved',
                ];
            }

            return null;
        } catch (PDOException $exception) {
            return null;
        }
    }
}
