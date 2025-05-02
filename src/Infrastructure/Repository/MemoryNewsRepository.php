<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entity\News;
use App\Domain\Repository\NewsRepositoryInterface;

class MemoryNewsRepository implements NewsRepositoryInterface
{

    public function save(News $news): void
    {
        // TODO: Implement save() method.
    }

    public function delete(int $newsId): void
    {
        // TODO: Implement delete() method.
    }

    public function findById(int $newsId): ?News
    {
        // TODO: Implement findById() method.
    }

    public function findAll(): iterable
    {
        // TODO: Implement findAll() method.
    }
}