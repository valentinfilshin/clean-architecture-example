<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\News;

interface NewsRepositoryInterface
{
    public function save(News $news): void;

    public function findById(int $newsId): ?News;

    public function findByIds(array $newsIds): iterable;

    /**
     * @return News[]
     */
    public function findAll(): iterable;
}
