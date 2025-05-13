<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\UrlMetadataFetcher;

use App\Application\UrlMetadataFetcher\Input\UrlMetadataFetcherRequest;
use App\Application\UrlMetadataFetcher\Output\UrlMetadataFetcherDTO;
use App\Infrastructure\UrlMetadataFetcher\UrlMetadataFetcher;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class UrlMetadataFetcherTest extends TestCase
{
    private HttpClientInterface|MockObject $httpClient;
    private UrlMetadataFetcher $urlMetadataFetcher;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->urlMetadataFetcher = new UrlMetadataFetcher($this->httpClient);
    }

    public function testFetchTitleWhenTitleExists(): void
    {
        // Arrange
        $url = 'https://example.com';
        $urlMetadataFetcherDTO = new UrlMetadataFetcherRequest($url);
        $expectedTitle = 'Пример заголовка страницы';
        $htmlContent = '<html><head><title>' . $expectedTitle . '</title></head><body></body></html>';

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getContent')
            ->willReturn($htmlContent);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', $url, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                ],
                'max_redirects' => 5,
            ])
            ->willReturn($response);

        // Act
        $result = $this->urlMetadataFetcher->fetchTitle($urlMetadataFetcherDTO);

        // Assert
        $this->assertInstanceOf(UrlMetadataFetcherDTO::class, $result);
        $this->assertEquals($expectedTitle, $result->title);
    }

    public function testFetchTitleWhenTitleDoesNotExist(): void
    {
        // Arrange
        $url = 'https://example.com';
        $urlMetadataFetcherDTO = new UrlMetadataFetcherRequest($url);
        $htmlContent = '<html><head></head><body>Контент без заголовка</body></html>';

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getContent')
            ->willReturn($htmlContent);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', $url, $this->anything())
            ->willReturn($response);

        // Act
        $result = $this->urlMetadataFetcher->fetchTitle($urlMetadataFetcherDTO);

        // Assert
        $this->assertInstanceOf(UrlMetadataFetcherDTO::class, $result);
        $this->assertNull($result->title);
    }

    public function testFetchTitleWithUTF8Characters(): void
    {
        // Arrange
        $url = 'https://example.com';
        $urlMetadataFetcherDTO = new UrlMetadataFetcherRequest($url);

        $expectedTitle = 'Заголовок с кириллицей и символами типа €£¥®©';
        $htmlContent = '<html><head><title>' . $expectedTitle . '</title></head><body></body></html>';

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getContent')
            ->willReturn($htmlContent);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->with('GET', $url, $this->anything())
            ->willReturn($response);

        // Act
        $result = $this->urlMetadataFetcher->fetchTitle($urlMetadataFetcherDTO);

        // Assert
        $this->assertInstanceOf(UrlMetadataFetcherDTO::class, $result);
        $this->assertEquals($expectedTitle, $result->title);
    }
}