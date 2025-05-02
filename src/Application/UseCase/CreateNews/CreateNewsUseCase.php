<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNews;

use App\Application\UseCase\CreateNews\Input\CreateNewsRequest;
use App\Application\UseCase\CreateNews\Output\CreateNewsResponse;
use App\Domain\Entity\News;
use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\Service\UrlMetadataFetcherInterface;
use App\Domain\ValueObject\Title;
use App\Domain\ValueObject\Url;
use DateTimeImmutable;

readonly class CreateNewsUseCase
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private UrlMetadataFetcherInterface $urlMetadataFetcher
    )
    {
    }

    public function __invoke(CreateNewsRequest $request): CreateNewsResponse
    {
        $url = new Url($request->url);
        $title = new Title($this->urlMetadataFetcher->fetchTitle($url->url));
        $data = new DateTimeImmutable();

        $news = new News($title, $url, $data);
        $id = $this->newsRepository->save($news);
        return new CreateNewsResponse($id);
    }
}