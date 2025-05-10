<?php

namespace App\Application\UseCase\CreateNewsReport\Input;

readonly class CreateNewsReportRequest
{
    public function __construct(
        public array $newsIds
    ) {
    }
}