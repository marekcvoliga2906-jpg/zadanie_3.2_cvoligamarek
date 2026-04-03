<?php
declare(strict_types=1);

function loadJsonData(string $filePath): array
{
    if (!is_file($filePath)) {
        return [];
    }

    $json = file_get_contents($filePath);

    if ($json === false) {
        return [];
    }

    $data = json_decode($json, true);

    return is_array($data) ? $data : [];
}

function escapeHtml(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function renderSlides(array $slides): void
{
    foreach ($slides as $slide) {
        $image = isset($slide['image']) ? (string) $slide['image'] : '';
        $title = isset($slide['title']) ? (string) $slide['title'] : '';
        $url = isset($slide['url']) ? (string) $slide['url'] : '';

        if ($image === '') {
            continue;
        }

        echo '<div class="slide fade">';

        if ($url !== '') {
            echo '<a class="slide-link" href="' . escapeHtml($url) . '">';
        }

        echo '<img src="' . escapeHtml($image) . '" alt="' . escapeHtml($title) . '">';
        echo '<div class="slide-text">' . escapeHtml($title) . '</div>';

        if ($url !== '') {
            echo '</a>';
        }

        echo '</div>';
    }

    if (count($slides) > 1) {
        echo '<a id="prev" class="prev">&#10094;</a>';
        echo '<a id="next" class="next">&#10095;</a>';
    }
}

function renderAccordionItems(array $items): void
{
    foreach ($items as $item) {
        $question = isset($item['question']) ? (string) $item['question'] : '';
        $answer = isset($item['answer']) ? (string) $item['answer'] : '';

        if ($question === '' && $answer === '') {
            continue;
        }

        echo '<div class="accordion">';
        echo '<div class="question">' . escapeHtml($question) . '</div>';
        echo '<div class="answer">' . escapeHtml($answer) . '</div>';
        echo '</div>';
    }
}
