<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\News;

interface NewsFactoryInterface
{
    // Можно ли в фабрику передавать VO?
    public function create(string $url, string $title): News;
}
