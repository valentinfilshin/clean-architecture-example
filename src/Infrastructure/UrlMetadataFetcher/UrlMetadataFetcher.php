<?php

declare(strict_types=1);

namespace App\Infrastructure\UrlMetadataFetcher;

use App\Application\UrlMetadataFetcher\Output\UrlMetadataFetcherDTO;
use App\Application\UrlMetadataFetcher\UrlMetadataFetcherInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class UrlMetadataFetcher implements UrlMetadataFetcherInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    public function fetchTitle(string $url): UrlMetadataFetcherDTO
    {
        $response = $this->httpClient->request('GET', $url, [
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36' .
                ' (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ],
            'max_redirects' => 5,
        ]);

        $html = $response->getContent();

        // Правильная обработка HTML с учетом кодировки UTF-8
        libxml_use_internal_errors(true);

        $doc = new \DOMDocument();

        // Устанавливаем метафлаг для корректной обработки UTF-8
        $html = '<?xml encoding="UTF-8">' . $html;

        // Загружаем HTML
        $doc->loadHTML($html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        libxml_clear_errors();

        $titleTags = $doc->getElementsByTagName('title');

        return $titleTags->count() > 0 ? new UrlMetadataFetcherDTO($titleTags->item(0)->textContent)
            : new UrlMetadataFetcherDTO(null);
    }
}
