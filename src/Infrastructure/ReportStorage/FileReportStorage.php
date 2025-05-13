<?php

declare(strict_types=1);

namespace App\Infrastructure\ReportStorage;

use App\Application\ReportStorage\Output\ReportStorageResponse;
use App\Application\ReportStorage\ReportStorageInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Uid\Uuid;

readonly class FileReportStorage implements ReportStorageInterface
{
    public function __construct(
        private Filesystem $filesystem,
        private string $baseDir,
        private string $saveDir,
    ) {
    }

    public function save(array $news): ReportStorageResponse
    {
        // Создаем директорию, если она не существует
        if (!$this->filesystem->exists($this->baseDir)) {
            $this->filesystem->mkdir($this->baseDir, 0777);
        }

        if (!$this->filesystem->exists($this->baseDir . $this->saveDir)) {
            $this->filesystem->mkdir($this->baseDir . $this->saveDir, 0777);
        }

        // Используем статический метод Uuid::v4() для генерации UUID
        $fileName = Uuid::v4()->toRfc4122() . '.html';
        $fullFilePath = $this->baseDir . $this->saveDir . '/' . $fileName;
        $publicFilePath = $this->saveDir . '/' . $fileName;

        // Формируем HTML-код
        $htmlContent = "<ul>\n";
        foreach ($news as $item) {
            $htmlContent .= sprintf(
                (string)"<li><a href=\"%s\">%s</a></li>",
                $item->url ?? '#',
                $item->title ?? 'Без названия'
            );
        }
        $htmlContent .= "</ul>";

        // Записываем данные в файл
        $this->filesystem->dumpFile($fullFilePath, $htmlContent);

        // Возвращаем путь к сохраненному файлу
        return new ReportStorageResponse($publicFilePath);
    }
}
