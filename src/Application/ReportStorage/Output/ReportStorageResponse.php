<?php

declare(strict_types=1);

namespace App\Application\ReportStorage\Output;

readonly class ReportStorageResponse
{
    public function __construct(public string $url)
    {
    }
}
