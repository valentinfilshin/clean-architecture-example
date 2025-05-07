<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Factory\UrlFactoryInterface;
use App\Domain\ValueObject\Url;

class UrlFactory implements UrlFactoryInterface
{
    public function create(string $url): Url
    {
        return new Url($url);
    }
}
