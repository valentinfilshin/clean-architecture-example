<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\News;

interface NewsRepositoryInterface
{
    // TODO void?
    public function save(News $news): int;

    public function findById(int $newsId): ?News;

    public function findByIds(array $newsIds): iterable;

    /**
     * @return News[]
     */
    public function findAll(): iterable;
}
