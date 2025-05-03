<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\News;
use App\Domain\Repository\NewsRepositoryInterface;

class FileNewsRepository implements NewsRepositoryInterface
{
    private string $storageDirectory;
    private string $counterFile;
    
    public function __construct(string $storageDirectory = null)
    {
        $this->storageDirectory = $storageDirectory ?? sys_get_temp_dir() . '/news_storage';
        $this->counterFile = $this->storageDirectory . '/counter.txt';
        
        if (!is_dir($this->storageDirectory)) {
            mkdir($this->storageDirectory, 0777, true);
        }
        
        if (!file_exists($this->counterFile)) {
            file_put_contents($this->counterFile, '1');
        }
    }
    
    public function save(News $news): int
    {
        $newsId = $news->getNewsId() ?? $this->getNextId();
        $news->setNewsId($newsId);
        
        $filePath = $this->getFilePath($newsId);
        file_put_contents($filePath, serialize($news));
        
        return $newsId;
    }

    public function findByIds(array $newsIds): iterable
    {
        $result = [];

        foreach ($newsIds as $newsId) {
            $news = $this->findById($newsId);
            if ($news !== null) {
                $result[] = $news;
            }
        }

        return $result;
    }

    public function findById(int $newsId): ?News
    {
        $filePath = $this->getFilePath($newsId);
        if (!file_exists($filePath)) {
            return null;
        }
        
        return unserialize(file_get_contents($filePath));
    }
    
    /**
     * @return News[]
     */
    public function findAll(): iterable
    {
        $result = [];
        $files = glob($this->storageDirectory . '/news_*.dat');
        
        foreach ($files as $file) {
            $result[] = unserialize(file_get_contents($file));
        }
        
        return $result;
    }
    
    private function getNextId(): int
    {
        $currentId = (int) file_get_contents($this->counterFile);
        $nextId = $currentId + 1;
        file_put_contents($this->counterFile, (string) $nextId);
        
        return $currentId;
    }
    
    private function getFilePath(int $newsId): string
    {
        return $this->storageDirectory . '/news_' . $newsId . '.dat';
    }
}
