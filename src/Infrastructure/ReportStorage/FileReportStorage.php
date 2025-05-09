<?php

declare(strict_types=1);

namespace App\Infrastructure\ReportStorage;

use App\Application\ReportStorage\ReportStorageInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

readonly class FileReportStorage implements ReportStorageInterface
{
    public function __construct(
        private Filesystem $filesystem,
        private string $saveDir
    ) {
    }

    public function save(array $news): string
    {
        // Создаем директорию, если она не существует
        if (!$this->filesystem->exists($this->saveDir)) {
            $this->filesystem->mkdir($this->saveDir, 0777);
        }

        // Используем статический метод Uuid::v4() для генерации UUID
        $fileName = Uuid::v4()->toRfc4122() . '.json';
        $cacheFile = $this->saveDir . '/' . $fileName;

        $jsonData = json_encode([
            'data' => $news,
            'cached_at' => time()
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Записываем данные в файл
        $this->filesystem->dumpFile($cacheFile, $jsonData);

        // Возвращаем путь к сохраненному файлу
        return $cacheFile;
    }
}
