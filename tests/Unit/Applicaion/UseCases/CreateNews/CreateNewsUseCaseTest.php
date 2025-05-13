<?php

namespace App\Tests\Unit\Applicaion\UseCases\CreateNews;

use App\Application\UrlMetadataFetcher\Input\UrlMetadataFetcherRequest;
use App\Application\UrlMetadataFetcher\Output\UrlMetadataFetcherDTO;
use App\Application\UseCase\CreateNews\CreateNewsUseCase;
use App\Application\UseCase\CreateNews\Input\CreateNewsRequest;
use App\Application\UseCase\CreateNews\Output\CreateNewsResponse;
use App\Domain\Entity\News;
use App\Domain\Factory\NewsFactoryInterface;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Application\UrlMetadataFetcher\UrlMetadataFetcherInterface;
use PHPUnit\Framework\TestCase;

class CreateNewsUseCaseTest extends TestCase
{
    private NewsFactoryInterface $newsFactory;
    private NewsRepositoryInterface $newsRepository;
    private UrlMetadataFetcherInterface $urlMetadataFetcher;
    private CreateNewsUseCase $useCase;

    protected function setUp(): void
    {
        // Создаем моки для зависимостей
        $this->newsFactory = $this->createMock(NewsFactoryInterface::class);
        $this->newsRepository = $this->createMock(NewsRepositoryInterface::class);
        $this->urlMetadataFetcher = $this->createMock(UrlMetadataFetcherInterface::class);

        // Инициализируем тестируемый класс
        $this->useCase = new CreateNewsUseCase(
            $this->newsFactory,
            $this->newsRepository,
            $this->urlMetadataFetcher
        );
    }

    public function testInvokeCreatesAndSavesNewsCorrectly(): void
    {
        // Подготовка тестовых данных
        $url = 'https://example.com/news/article';
        $titleText = 'Тестовая новость';
        $newsId = 42;

        // Создаем запрос
        $request = new CreateNewsRequest($url);

        // Создаем объект Title
        $title = new UrlMetadataFetcherDTO($titleText);

        // Создаем мок объекта News
        $news = $this->createMock(News::class);
        $news->method('getNewsId')->willReturn($newsId);

        // Настраиваем ожидания для urlMetadataFetcher
        $this->urlMetadataFetcher
            ->expects($this->once())
            ->method('fetchTitle')
            ->with(new UrlMetadataFetcherRequest($url))
            ->willReturn($title);

        // Настраиваем ожидания для newsFactory
        $this->newsFactory
            ->expects($this->once())
            ->method('create')
            ->with($url, $titleText)
            ->willReturn($news);

        // Настраиваем ожидания для newsRepository
        $this->newsRepository
            ->expects($this->once())
            ->method('save')
            ->with($news);

        // Вызываем тестируемый метод
        $response = ($this->useCase)($request);

        // Проверяем тип и содержимое ответа
        $this->assertInstanceOf(CreateNewsResponse::class, $response);
        $this->assertEquals($newsId, $response->newsId);
    }
}
