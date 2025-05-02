<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNewsReport;

use App\Domain\Repository\NewsRepositoryInterface;

class CreateNewsReportClass
{
    public function __construct(
        private NewsRepositoryInterface $newsRepository
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

        $this->saveMetadataToCache($arNews);
    }

    private function saveMetadataToCache(array $data): void
    {
        $cacheDir = __DIR__ . '/../../../var/cache/url_metadata';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $cacheFile = $cacheDir . '/' . md5((string)time()) . '.json';
        $jsonData = json_encode([
            'data' => $data,
            'cached_at' => time()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents($cacheFile, $jsonData);
    }
}