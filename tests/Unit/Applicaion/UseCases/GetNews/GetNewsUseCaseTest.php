<?php

namespace App\Tests\Unit\Applicaion\UseCases\GetNews;

use App\Application\UseCase\GetNews\GetNewsUseCase;
use App\Application\UseCase\GetNews\Output\GetNewsResponse;
use App\Domain\Entity\News;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class GetNewsUseCaseTest extends TestCase
{
    private NewsRepositoryInterface $newsRepository;
    private GetNewsUseCase $useCase;

    protected function setUp(): void
    {
        // Создаем мок для NewsRepositoryInterface
        $this->newsRepository = $this->createMock(NewsRepositoryInterface::class);

        // Инициализируем тестируемый класс
        $this->useCase = new GetNewsUseCase($this->newsRepository);
    }

    public function testInvokeReturnsCorrectResponseObjects(): void
    {
        // Подготавливаем тестовые данные
        $title1 = new Title('Первая новость');
        $url1 = new Url('https://example.com/news/1');
        $date1 = new DateTimeImmutable('2023-01-15');
        $news1 = $this->createNewsWithId(1, $title1, $url1, $date1);

        $title2 = new Title('Вторая новость');
        $url2 = new Url('https://example.com/news/2');
        $date2 = new DateTimeImmutable('2023-01-20');
        $news2 = $this->createNewsWithId(2, $title2, $url2, $date2);

        // Настраиваем ожидания для мока
        $this->newsRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([$news1, $news2]);

        // Вызываем тестируемый метод
        $result = ($this->useCase)();

        // Преобразуем результат в массив для облегчения проверки
        $resultArray = iterator_to_array($result);

        // Проверяем, что возвращается правильное количество элементов
        $this->assertCount(2, $resultArray);

        // Проверяем, что элементы имеют правильный тип
        $this->assertInstanceOf(GetNewsResponse::class, $resultArray[0]);
        $this->assertInstanceOf(GetNewsResponse::class, $resultArray[1]);

        // Проверяем содержимое первого элемента
        $this->assertEquals(1, $resultArray[0]->newsId);
        $this->assertEquals('2023-01-15', $resultArray[0]->date);
        $this->assertEquals('https://example.com/news/1', $resultArray[0]->url);
        $this->assertEquals('Первая новость', $resultArray[0]->title);

        // Проверяем содержимое второго элемента
        $this->assertEquals(2, $resultArray[1]->newsId);
        $this->assertEquals('2023-01-20', $resultArray[1]->date);
        $this->assertEquals('https://example.com/news/2', $resultArray[1]->url);
        $this->assertEquals('Вторая новость', $resultArray[1]->title);
    }

    public function testInvokeWithEmptyRepositoryReturnsEmptyArray(): void
    {
        // Настраиваем мок для возврата пустого массива
        $this->newsRepository
            ->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        // Вызываем тестируемый метод
        $result = ($this->useCase)();

        // Проверяем, что возвращается пустой массив
        $this->assertEmpty(iterator_to_array($result));
    }

    /**
     * Вспомогательный метод для создания объекта News с заданным ID
     */
    private function createNewsWithId(int $id, Title $title, Url $url, DateTimeImmutable $date): News
    {
        $news = $this->createPartialMock(News::class, ['getNewsId', 'getTitle', 'getUrl', 'getDate']);
        $news->method('getNewsId')->willReturn($id);

        // Настраиваем ожидания для других методов
        $news->method('getTitle')->willReturn($title);
        $news->method('getUrl')->willReturn($url);
        $news->method('getDate')->willReturn($date);

        return $news;
    }
}
