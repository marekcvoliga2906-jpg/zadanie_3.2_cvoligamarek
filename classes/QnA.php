<?php

declare(strict_types=1);

final class QnA
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getAllItems(): array
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
