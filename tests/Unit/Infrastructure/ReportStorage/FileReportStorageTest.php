<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\ReportStorage;

use App\Infrastructure\ReportStorage\FileReportStorage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class FileReportStorageTest extends TestCase
{
    private Filesystem|MockObject $filesystem;
    private FileReportStorage $fileReportStorage;
    private string $baseDir;
    private string $saveDir;

    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->baseDir = '/base/dir';
        $this->saveDir = '/save/dir';
        $this->fileReportStorage = new FileReportStorage(
            $this->filesystem,
            $this->baseDir,
            $this->saveDir
        );
    }

    public function testSaveCreatesDirectoriesIfTheyDoNotExist(): void
    {
        // Arrange
        $news = [
            (object)['url' => 'http://example.com', 'title' => 'Пример новости']
        ];

        // Настраиваем поведение для проверки существования директорий
        $this->filesystem->expects($this->exactly(2))
            ->method('exists')
            ->willReturnMap([
                [$this->baseDir, false],
                [$this->baseDir . $this->saveDir, false]
            ]);

        // Проверка создания первой директории
        $mkdirCalls = [];
        $this->filesystem->method('mkdir')
            ->willReturnCallback(function ($path) use (&$mkdirCalls) {
                $mkdirCalls[] = $path;
                return null;
            });

        // Ожидаем вызов dumpFile с правильными параметрами
        $this->filesystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                $this->stringContains($this->baseDir . $this->saveDir),
                $this->stringContains('<li><a href="http://example.com">Пример новости</a></li>')
            );

        // Act
        $result = $this->fileReportStorage->save($news);

        // Assert
        $this->assertMatchesRegularExpression('#^' . preg_quote($this->saveDir . '/', '#') . '[0-9a-f\-]+\.html$#', $result->url);
    }

    public function testSaveDoesNotCreateDirectoriesIfTheyExist(): void
    {
        // Arrange
        $news = [
            (object)['url' => 'http://example.com', 'title' => 'Пример новости']
        ];

        // Настраиваем поведение для проверки существования директорий
        $this->filesystem->expects($this->exactly(2))
            ->method('exists')
            ->willReturnMap([
                [$this->baseDir, true],
                [$this->baseDir . $this->saveDir, true]
            ]);

        // Ожидаем, что директории не будут созданы
        $this->filesystem->expects($this->never())
            ->method('mkdir');

        // Ожидаем вызов dumpFile
        $this->filesystem->expects($this->once())
            ->method('dumpFile');

        // Act
        $result = $this->fileReportStorage->save($news);

        // Assert
        $this->assertMatchesRegularExpression('#^' . preg_quote($this->saveDir . '/', '#') . '[0-9a-f\-]+\.html$#', $result->url);
    }

    public function testSaveWithMultipleNewsItems(): void
    {
        // Arrange
        $news = [
            (object)['url' => 'http://example1.com', 'title' => 'Новость 1'],
            (object)['url' => 'http://example2.com', 'title' => 'Новость 2'],
            (object)['url' => 'http://example3.com', 'title' => 'Новость 3']
        ];

        // Настраиваем существование директорий
        $this->filesystem->method('exists')->willReturn(true);

        // Проверяем содержимое HTML-файла
        $this->filesystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                $this->anything(),
                $this->callback(function ($content) {
                    return
                        str_contains($content, '<li><a href="http://example1.com">Новость 1</a></li>') &&
                        str_contains($content, '<li><a href="http://example2.com">Новость 2</a></li>') &&
                        str_contains($content, '<li><a href="http://example3.com">Новость 3</a></li>');
                })
            );

        // Act
        $result = $this->fileReportStorage->save($news);

        // Assert
        $this->assertNotEmpty($result);
    }

    public function testSaveWithNullValues(): void
    {
        // Arrange
        $news = [
            (object)['url' => null, 'title' => null]
        ];

        // Настраиваем существование директорий
        $this->filesystem->method('exists')->willReturn(true);

        // Проверяем обработку null-значений
        $this->filesystem->expects($this->once())
            ->method('dumpFile')
            ->with(
                $this->anything(),
                $this->stringContains('<li><a href="#">Без названия</a></li>')
            );

        // Act
        $result = $this->fileReportStorage->save($news);

        // Assert
        $this->assertNotEmpty($result);
    }
}
