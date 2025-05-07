<?php

declare(strict_types=1);

namespace App\Domain\Service;

use App\Domain\ValueObject\Title;

interface UrlMetadataFetcherInterface
{
    public function fetchTitle(string $url): Title;
}
