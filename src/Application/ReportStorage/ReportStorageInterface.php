<?php

declare(strict_types=1);

namespace App\Application\ReportStorage;

use App\Application\ReportStorage\Output\ReportStorageResponse;

interface ReportStorageInterface
{
    public function save(array $news): ReportStorageResponse;
}
