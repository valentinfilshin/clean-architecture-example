<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNews;

use App\Application\UseCase\CreateNews\Input\CreateNewsRequest;
use App\Application\UseCase\CreateNews\Output\CreateNewsResponse;
use App\Domain\Factory\NewsFactoryInterface;
use App\Domain\Factory\UrlFactoryInterface;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\Service\UrlMetadataFetcherInterface;

readonly class CreateNewsUseCase
{
    public function __construct(
        private NewsFactoryInterface $newsFactory,
        private UrlFactoryInterface $urlFactory,
        private NewsRepositoryInterface $newsRepository,
        private UrlMetadataFetcherInterface $urlMetadataFetcher
    )
    {
    }

    public function __invoke(CreateNewsRequest $request): CreateNewsResponse
    {
        // Данную логику делать в фабрике или все таки тут правильнее?

        // Создать URL
        $url = $this->urlFactory->create($request->url);

        // Получить Title
        $title  = $this->urlMetadataFetcher->fetchTitle($url->url);

        // Создать новость
        $news = $this->newsFactory->create($url, $title);

        // Сохранить новость
        $this->newsRepository->save($news);

        return new CreateNewsResponse($news->getNewsId());
    }
}