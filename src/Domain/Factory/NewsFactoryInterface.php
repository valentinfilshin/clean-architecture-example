<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\News;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;

interface NewsFactoryInterface
{
    public function create(Url $url, Title $title): News;
}
