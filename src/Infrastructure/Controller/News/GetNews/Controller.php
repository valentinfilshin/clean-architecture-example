<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\News\GetNews;

use App\Application\UseCase\GetNews\GetNewsUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
readonly class Controller
{
    public function __construct(
        private GetNewsUseCase $getNewsUseCase
    ) {
    }

    #[Route(path: '/news', name: 'news.get', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $news = ($this->getNewsUseCase)();
        return new JsonResponse($news);
    }
}
