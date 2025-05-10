<?php

declare(strict_types=1);

namespace App\Application\UrlMetadataFetcher\Output;

readonly class UrlMetadataFetcherDTO
{
    public function __construct(public ?string $title)
    {
    }
}
