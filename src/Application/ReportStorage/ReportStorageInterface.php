<?php

declare(strict_types=1);

namespace App\Application\ReportStorage;

interface ReportStorageInterface
{
    public function save(array $news): string;
}
