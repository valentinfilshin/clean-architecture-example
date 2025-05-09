<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNews;

use App\Application\UrlMetadataFetcher\UrlMetadataFetcherInterface;
use App\Application\UseCase\CreateNews\Input\CreateNewsRequest;
use App\Application\UseCase\CreateNews\Output\CreateNewsResponse;
use App\Domain\Factory\NewsFactoryInterface;
use App\Domain\Repository\NewsRepositoryInterface;

readonly class CreateNewsUseCase
{
    public function __construct(
        private NewsFactoryInterface $newsFactory,
        private NewsRepositoryInterface $newsRepository,
        private UrlMetadataFetcherInterface $urlMetadataFetcher
    ) {
    }

    public function __invoke(CreateNewsRequest $request): CreateNewsResponse
    {
        // Данную логику делать в фабрике или все таки тут правильнее?

        // URL to DTO
        // Fabric либо VO либо можно строки VO
        // TODO DTO для metaDataFetcherRequest и переделать данный слой
        // ...

        // TODO Request/response
        // Получить Title
        $title  = $this->urlMetadataFetcher->fetchTitle($request->url);

        // Создать новость
        $news = $this->newsFactory->create($request->url, $title);

        // Сохранить новость
        $this->newsRepository->save($news);

        return new CreateNewsResponse($news->getNewsId());
    }
}
