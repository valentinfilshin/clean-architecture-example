<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Application\UrlMetadataFetcher\UrlMetadataFetcherInterface;
use App\Domain\ValueObject\Title;

class UrlMetadataFetcher implements UrlMetadataFetcherInterface
{
    public function fetchTitle(string $url): ?string
    {
        // Используем curl_init() с проверкой на null
        $curl = curl_init($url) ?: throw new \RuntimeException('Failed to initialize cURL');

        // Используем curl_setopt_array для более компактной настройки
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) 
            Chrome/91.0.4472.124 Safari/537.36',
        ]);

        try {
            $html = curl_exec($curl);

            if ($html === false) {
                return null;
            }

            // Правильная обработка HTML с учетом кодировки UTF-8
            libxml_use_internal_errors(true);

            $doc = new \DOMDocument();

            // Устанавливаем метафлаг для корректной обработки UTF-8
            $html = '<?xml encoding="UTF-8">' . $html;

            // Загружаем HTML без использования устаревшего mb_convert_encoding
            $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

            libxml_clear_errors();

            $titleTags = $doc->getElementsByTagName('title');

            return $titleTags->count() > 0 ? new Title(trim($titleTags->item(0)->textContent)) : null;
        } finally {
            // Гарантируем закрытие соединения cURL
            curl_close($curl);
        }
    }
}
