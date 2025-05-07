<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNewsReport;

use App\Domain\Repository\NewsRepositoryInterface;
use App\Domain\Service\ReportStorageInterface;

readonly class CreateNewsReportUseCase
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private ReportStorageInterface $reportStorage
    ) {
    }

    public function __invoke(): void
    {
        $news = $this->newsRepository->findAll();

        foreach ($news as $item) {
            $itemId = $item->getNewsId();
            $arNews[$itemId]['TITLE'] = $item->getTitle();
            $arNews[$itemId]['URL'] = $item->getUrl();
            $arNews[$itemId]['DATA'] = $item->getData();
        }

        $this->reportStorage->save($arNews);
    }
}
