<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\News\CreateNewsReport;

use App\Application\UseCase\CreateNewsReport\CreateNewsReportUseCase;
use App\Application\UseCase\CreateNewsReport\Input\CreateNewsReportRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
readonly class Controller
{
    public function __construct(
        private CreateNewsReportUseCase $createNewsReportUseCase
    ) {
    }

    #[Route(path: '/news/report', name: 'news.report', methods: ['POST'])]
    public function __invoke(#[MapRequestPayload] CreateNewsReportRequest $request): JsonResponse
    {
        $reportData = ($this->createNewsReportUseCase)($request);
        return new JsonResponse($reportData);
    }
}
