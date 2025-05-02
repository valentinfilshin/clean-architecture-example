<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\News\CreateNews;

use App\Application\UseCase\CreateNews\CreateNewsUseCase;
use App\Application\UseCase\CreateNews\Input\CreateNewsRequest;
use App\Application\UseCase\CreateNews\Output\CreateNewsResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
readonly class Controller
{
    public function __construct(
        private CreateNewsUseCase $createNewsUseCase
    )
    {

    }
    #[Route(path: '/news', name: 'news.create', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateNewsRequest $createNewsRequest): CreateNewsResponse
    {
        $id = $this->createNewsUseCase->__invoke($createNewsRequest);
        return new CreateNewsResponse(
            1
        );
    }
}
