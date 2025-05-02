<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\News;
use App\Domain\Repository\NewsRepositoryInterface;

class MemoryNewsRepository implements NewsRepositoryInterface
{

    private array $news = [];
    private int $newsId = 1;

    public function save(News $news): int
    {
        $news->setNewsId($this->newsId);
        $this->news[] = $news;
        return $this->newsId++;
    }

    public function delete(int $newsId): void
    {
        unset($this->news[$newsId]);
    }

    public function findById(int $newsId): ?News
    {
        return $this->news[$newsId] ?? null;
    }

    public function findAll(): iterable
    {
        return $this->news;
    }
}