<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNewsReport;

use App\Application\UseCase\CreateNewsReport\Output\CreateNewsReportDTO;
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
        $newsList = [];
        $news = $this->newsRepository->findAll();

        foreach ($news as $item) {
            $newsList[] = new CreateNewsReportDTO(
                $item->getUrl()->url,
                $item->getTitle()->title
            );
        }

        $this->reportStorage->save($newsList);
    }
}
