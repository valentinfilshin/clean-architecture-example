<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNews;

use App\Application\UseCase\CreateNews\Input\CreateNewsRequest;
use App\Application\UseCase\CreateNews\Output\CreateNewsResponse;
use App\Domain\Repository\NewsRepositoryInterface;

class CreateNewsUseCase
{
    public function __construct(
        private readonly NewsRepositoryInterface $newsRepository
    ) {
    }

    public function __invoke(CreateNewsRequest $request): int
    {
        //$news = 1;
        //$news = $this->newsRepository->save($news);
        //return new CreateNewsResponse($news-getId());

        return 1;
    }
}