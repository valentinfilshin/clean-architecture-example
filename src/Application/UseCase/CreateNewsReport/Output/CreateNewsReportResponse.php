<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNewsReport\Output;

readonly class CreateNewsReportResponse
{
    public function __construct(public string $reportUrl)
    {
    }
}
