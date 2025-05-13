<?php

declare(strict_types=1);

namespace App\Application\UrlMetadataFetcher;

use App\Application\UrlMetadataFetcher\Input\UrlMetadataFetcherRequest;
use App\Application\UrlMetadataFetcher\Output\UrlMetadataFetcherDTO;

interface UrlMetadataFetcherInterface
{
    public function fetchTitle(UrlMetadataFetcherRequest $request): UrlMetadataFetcherDTO;
}
