<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\ReportStorageInterface;

class FileReportStorage implements ReportStorageInterface
{
    public function save(array $news): void
    {
        $cacheDir = __DIR__ . '/../../../var/cache/url_metadata';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        $cacheFile = $cacheDir . '/' . md5((string)time()) . '.json';
        $jsonData = json_encode([
            'data' => $news,
            'cached_at' => time()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents($cacheFile, $jsonData);
    }
}
