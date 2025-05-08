<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNewsReport\Output;

readonly class CreateNewsReportDTO
{
    public function __construct(
        public string $url,
        public string $title,
    ) {
    }
}
