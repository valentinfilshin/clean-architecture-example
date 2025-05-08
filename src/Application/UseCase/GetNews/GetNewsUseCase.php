<?php

declare(strict_types=1);

namespace App\Application\UseCase\GetNews;

use App\Application\UseCase\GetNews\Output\GetNewsResponse;
use App\Domain\Repository\NewsRepositoryInterface;

readonly class GetNewsUseCase
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository
    ) {
    }

    public function __invoke(): iterable
    {
        $newsList = [];
        $news = $this->newsRepository->findAll();

        foreach ($news as $item) {
            $newsList[] = new GetNewsResponse(
                $item->getNewsId(),
                $item->getDate()->format('Y-m-d'),
                $item->getUrl()->url,
                $item->getTitle()->title
            );
        }

        return $newsList;
    }
}
