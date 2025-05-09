<?php

declare(strict_types=1);

namespace App\Application\UrlMetadataFetcher;

interface UrlMetadataFetcherInterface
{
    public function fetchTitle(string $url): ?string;
}
