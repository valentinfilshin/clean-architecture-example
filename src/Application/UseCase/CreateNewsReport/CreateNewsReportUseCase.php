<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNewsReport;

use App\Application\ReportStorage\ReportStorageInterface;
use App\Application\UseCase\CreateNewsReport\Output\CreateNewsReportDTO;
use App\Domain\Repository\NewsRepositoryInterface;

readonly class CreateNewsReportUseCase
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private ReportStorageInterface $reportStorage
    ) {
    }

    public function __invoke(): void
    {
        // TODO принять список ID
        $newsList = [];
        $news = $this->newsRepository->findAll();

        foreach ($news as $item) {
            $newsList[] = new CreateNewsReportDTO(
                $item->getUrl()->url,
                $item->getTitle()->title
            );
        }

        // TODO return DTO
        $this->reportStorage->save($newsList);
    }
}
