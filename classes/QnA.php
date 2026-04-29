<?php

declare(strict_types=1);

namespace App;

use PDOException;

final class QnA extends Database
{
    public function getAllItems(): array
    {
        $connection = $this->getConnection();

        if ($connection === null) {
            return [];
        }

        try {
            $statement = $connection->query('SELECT question, answer FROM qna');

            if ($statement === false) {
                return [];
            }

            $items = $statement->fetchAll();

            return is_array($items) ? $items : [];
        } catch (PDOException $exception) {
            return [];
        }
    }
}
