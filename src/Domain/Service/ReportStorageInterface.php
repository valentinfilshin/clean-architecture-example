<?php

declare(strict_types=1);

namespace App\Domain\Service;

interface ReportStorageInterface
{
    public function save(array $news): void;
}
