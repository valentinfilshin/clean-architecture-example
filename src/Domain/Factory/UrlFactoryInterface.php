<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\ValueObject\Url;

interface UrlFactoryInterface
{
    public function create(string $url): Url;
}
