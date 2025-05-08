<?php

declare(strict_types=1);

namespace App\Application\UseCase\GetNews\Output;

readonly class GetNewsResponse
{
    public function __construct(
        public int $newsId,
        public string $date,
        public string $url,
        public string $title
    ) {
    }
}
