<?php

namespace App\Tests\Unit\Domain\Entity;

use App\Domain\Entity\News;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class NewsTest extends TestCase
{
    private Title $title;
    private Url $url;
    private DateTimeImmutable $date;

    protected function setUp(): void
    {
        $this->title = new Title('Тестовый заголовок');
        $this->url = new Url('https://example.com');
        $this->date = new DateTimeImmutable();
    }

    public function testNewsCreation(): void
    {
        $news = new News($this->title, $this->url, $this->date);

        $this->assertInstanceOf(News::class, $news);
        $this->assertNull($news->getNewsId());
    }

    public function testGetNewsId(): void
    {
        $news = new News($this->title, $this->url, $this->date);

        // По умолчанию ID равен null
        $this->assertNull($news->getNewsId());

        // Можно добавить рефлексию, чтобы установить ID для тестирования
        $reflection = new \ReflectionClass($news);
        $property = $reflection->getProperty('newsId');
        $property->setValue($news, 123);

        $this->assertEquals(123, $news->getNewsId());
    }

    public function testGetTitle(): void
    {
        $news = new News($this->title, $this->url, $this->date);

        $this->assertSame($this->title, $news->getTitle());
    }

    public function testGetUrl(): void
    {
        $news = new News($this->title, $this->url, $this->date);

        $this->assertSame($this->url, $news->getUrl());
    }

    public function testGetDate(): void
    {
        $news = new News($this->title, $this->url, $this->date);

        $this->assertSame($this->date, $news->getDate());
    }
}
