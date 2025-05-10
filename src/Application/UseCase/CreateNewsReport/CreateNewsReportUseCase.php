<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNewsReport;

use App\Application\ReportStorage\ReportStorageInterface;
use App\Application\UseCase\CreateNewsReport\Input\CreateNewsReportRequest;
use App\Application\UseCase\CreateNewsReport\Output\CreateNewsReportDTO;
use App\Application\UseCase\CreateNewsReport\Output\CreateNewsReportResponse;
use App\Domain\Repository\NewsRepositoryInterface;

readonly class CreateNewsReportUseCase
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository,
        private ReportStorageInterface $reportStorage
    ) {
    }

    public function __invoke(CreateNewsReportRequest $request): CreateNewsReportResponse
    {
        $newsList = [];
        $news = $this->newsRepository->findByIds($request->newsIds);

        foreach ($news as $item) {
            $newsList[] = new CreateNewsReportDTO(
                $item->getUrl()->url,
                $item->getTitle()->title
            );
        }

        $url = $this->reportStorage->save($newsList);

        return new CreateNewsReportResponse(
            $url
        );
    }
}
