<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNews\Output;

readonly class CreateNewsResponse
{
    public function __construct(public int $newsId)
    {
    }
}
