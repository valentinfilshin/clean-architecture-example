<?php

declare(strict_types=1);

namespace App\Application\UrlMetadataFetcher\Input;

readonly class UrlMetadataFetcherRequest
{
    public function __construct(public string $url)
    {
    }
}
