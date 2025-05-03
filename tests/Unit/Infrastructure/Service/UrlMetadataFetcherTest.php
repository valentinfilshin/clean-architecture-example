<?php

namespace App\Tests\Unit\Infrastructure\Service;

use App\Infrastructure\Service\UrlMetadataFetcher;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;

class UrlMetadataFetcherTest extends TestCase
{
    #[TestWith(['https://habr.com/ru/companies/otus/news/894246/', 'OTUS — 8 лет / Хабр'])]
    #[TestWith(['https://habr.com/ru/articles/906606/', '1С — архаика или рабочий инструмент? Разбор горячего анти-хайпа / Хабр'])]
    public function testFetchTitle(string $url, string $needle): void
    {
        // Arrange
        $urlMetadataFetcher = new UrlMetadataFetcher();

        // Act
        $result = $urlMetadataFetcher->fetchTitle($url);

        // Assert
        $this->assertEquals($needle, $result, $message = 'Title не совпадает с ожидаемым');
    }

    public function testFetchTitleEmptyString(): void
    {
        // Arrange
        $url = '';
        $urlMetadataFetcher = new UrlMetadataFetcher();

        // Act
        $result = $urlMetadataFetcher->fetchTitle($url);

        // Assert
        $this->assertEquals(null, $result, $message = 'Значение не пустое');
    }
    public function testFetchTitleWrongUrl(): void
    {
        // Arrange
        $url = 'А я не URL';
        $urlMetadataFetcher = new UrlMetadataFetcher();

        // Act
        $result = $urlMetadataFetcher->fetchTitle($url);

        // Assert
        $this->assertEquals(null, $result, $message = 'Значение не пустое');
    }
}
