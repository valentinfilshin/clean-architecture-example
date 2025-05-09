<?php

namespace App\Infrastructure\Service;

use App\Application\ReportStorage\ReportStorageInterface;

class FileReportStorage implements ReportStorageInterface
{
    // TODO переделать
    public function save(array $news): void
    {
        $cacheDir = __DIR__ . '/../../../var/cache/url_metadata';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0777, true);
        }

        // TODO uiid, не завязывать на time и microtime, а то вдруг одновременно будет обращение
        $cacheFile = $cacheDir . '/' . md5((string)time()) . '.json';
        $jsonData = json_encode([
            'data' => $news,
            'cached_at' => time()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        file_put_contents($cacheFile, $jsonData);
    }
}
