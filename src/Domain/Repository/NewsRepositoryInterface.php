<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\News;

interface NewsRepositoryInterface
{
    // TODO убрать метод делет, добавить метод FindById
    public function save(News $news): int;
    public function delete(int $newsId): void;
    public function findById(int $newsId): ?News;

    /**
     * @return News[]
     */
    public function findAll(): iterable;
}
