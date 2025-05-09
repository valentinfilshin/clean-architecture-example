<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use App\Domain\Entity\News;
use App\Domain\Factory\NewsFactoryInterface;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;
use DateTimeImmutable;

class NewsFactory implements NewsFactoryInterface
{
    public function create(string $url, string $title): News
    {
        return new News(
            new Title($title),
            new Url($url),
            new DateTimeImmutable()
        );
    }
}
