<?php

namespace App\Tests\Unit\Applicaion\UseCases;

use App\Application\ReportStorage\ReportStorageInterface;
use App\Application\UseCase\CreateNewsReport\CreateNewsReportUseCase;
use App\Application\UseCase\CreateNewsReport\Input\CreateNewsReportRequest;
use App\Application\UseCase\CreateNewsReport\Output\CreateNewsReportDTO;
use App\Application\UseCase\CreateNewsReport\Output\CreateNewsReportResponse;
use App\Domain\Entity\News;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;
use PHPUnit\Framework\TestCase;

class CreateNewsReportUseCaseTest extends TestCase
{
    private NewsRepositoryInterface $newsRepository;
    private ReportStorageInterface $reportStorage;
    private CreateNewsReportUseCase $useCase;

    protected function setUp(): void
    {
        // Создаем моки для зависимостей
        $this->newsRepository = $this->createMock(NewsRepositoryInterface::class);
        $this->reportStorage = $this->createMock(ReportStorageInterface::class);

        // Инициализируем тестируемый класс
        $this->useCase = new CreateNewsReportUseCase(
            $this->newsRepository,
            $this->reportStorage
        );
    }

    public function testInvokeCreatesAndSavesReportCorrectly(): void
    {
        // Подготовка тестовых данных
        $newsIds = [1, 2];
        $reportUrl = 'https://example.com/reports/123';

        // Создаем запрос
        $request = new CreateNewsReportRequest($newsIds);

        // Создаем Title и Url
        $title1 = new Title('Первая новость');
        $url1 = new Url('https://example.com/news/1');

        $title2 = new Title('Вторая новость');
        $url2 = new Url('https://example.com/news/2');

        // Создаем моки для объектов News
        $news1 = $this->createMock(News::class);
        $news1->method('getTitle')->willReturn($title1);
        $news1->method('getUrl')->willReturn($url1);

        $news2 = $this->createMock(News::class);
        $news2->method('getTitle')->willReturn($title2);
        $news2->method('getUrl')->willReturn($url2);

        // Настраиваем ожидания для newsRepository
        $this->newsRepository
            ->expects($this->once())
            ->method('findByIds')
            ->with($newsIds)
            ->willReturn([$news1, $news2]);

        // Ожидаемые DTO, которые должны быть переданы в reportStorage
        $expectedDTOs = [
            new CreateNewsReportDTO('https://example.com/news/1', 'Первая новость'),
            new CreateNewsReportDTO('https://example.com/news/2', 'Вторая новость')
        ];

        // Настраиваем ожидания для reportStorage
        $this->reportStorage
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($dtos) use ($expectedDTOs) {
                // Проверяем, что массив DTO имеет правильную структуру
                if (count($dtos) !== 2) {
                    return false;
                }

                // Проверяем содержимое каждого DTO
                return $dtos[0]->url === $expectedDTOs[0]->url
                    && $dtos[0]->title === $expectedDTOs[0]->title
                    && $dtos[1]->url === $expectedDTOs[1]->url
                    && $dtos[1]->title === $expectedDTOs[1]->title;
            }))
            ->willReturn($reportUrl);

        // Вызываем тестируемый метод
        $response = ($this->useCase)($request);

        // Проверяем тип и содержимое ответа
        $this->assertInstanceOf(CreateNewsReportResponse::class, $response);
        $this->assertEquals($reportUrl, $response->reportUrl);
    }

    public function testInvokeWithEmptyNewsIdsReturnsEmptyReport(): void
    {
        // Подготовка тестовых данных
        $newsIds = [];
        $reportUrl = 'https://example.com/reports/empty';

        // Создаем запрос
        $request = new CreateNewsReportRequest($newsIds);

        // Настраиваем ожидания для newsRepository
        $this->newsRepository
            ->expects($this->once())
            ->method('findByIds')
            ->with($newsIds)
            ->willReturn([]);

        // Настраиваем ожидания для reportStorage
        $this->reportStorage
            ->expects($this->once())
            ->method('save')
            ->with([])
            ->willReturn($reportUrl);

        // Вызываем тестируемый метод
        $response = ($this->useCase)($request);

        // Проверяем ответ
        $this->assertEquals($reportUrl, $response->reportUrl);
    }
}
