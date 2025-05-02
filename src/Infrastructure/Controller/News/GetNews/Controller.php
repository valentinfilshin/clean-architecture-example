<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller\News\GetNews;

use App\Application\UseCase\CreateNews\CreateNewsUseCase;
use App\Application\UseCase\CreateNewsReport\CreateNewsReportClass;
use App\Application\UseCase\GetNews\GetNewsUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
readonly class Controller
{
    public function __construct(
        private CreateNewsUseCase $createNewsUseCase,
        private GetNewsUseCase $getNewsUseCase,
        private CreateNewsReportClass $createNewsReportClass,
    )
    {

    }
    #[Route(path: '/news', name: 'news.get', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        // TODO ...
        $this->createNewsUseCase->__invoke($createNewsRequest);
        $this->createNewsUseCase->__invoke($createNewsRequest);
        $this->createNewsUseCase->__invoke($createNewsRequest);
        $this->createNewsUseCase->__invoke($createNewsRequest);

        $news = $this->getNewsUseCase->__invoke();
        foreach ($news as $item) {
            $itemId = $item->getNewsId();
            $arNews[$itemId]['TITLE'] = $item->getTitle();
            $arNews[$itemId]['URL'] = $item->getUrl();
            $arNews[$itemId]['DATA'] = $item->getData();
        }

        $this->createNewsReportClass->__invoke();
        return new JsonResponse($arNews);
    }
}
