<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Title;
use PHPUnit\Framework\TestCase;

class TitleTest extends TestCase
{
    public function testCreateValidTitle(): void
    {
        $title = new Title('Заголовок новости');
        $this->assertEquals('Заголовок новости', $title->title);
    }

    public function testEmptyTitleThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Title('');
    }

    public function testTooLongTitleThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Title(str_repeat('a', 256)); // Предполагаем ограничение в 255 символов
    }
}
