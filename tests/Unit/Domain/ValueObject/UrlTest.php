<?php

namespace App\Tests\Unit\Domain\ValueObject;

use App\Domain\ValueObject\Url;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    public function testCreateValidUrl(): void
    {
        $url = new Url('https://example.com');
        $this->assertEquals('https://example.com', $url->url);
    }

    public function testInvalidUrlThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Url('invalid-url');
    }

    public function testEmptyUrlThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Url('');
    }
}
