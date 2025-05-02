<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface UrlMetadataFetcherInterface
{
    public function fetchTitle(string $url): ?string;
}
