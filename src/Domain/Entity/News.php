<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;
use DateTimeImmutable;

class News
{
    private ?int $newsId = null;

    public function __construct(
        private readonly Title $title,
        private readonly Url $url,
        private readonly DateTimeImmutable $date
    ) {
    }

    public function getNewsId(): ?int
    {
        return $this->newsId;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getUrl(): Url
    {
        return $this->url;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }
}
