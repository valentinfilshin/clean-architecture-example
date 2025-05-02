<?php

declare(strict_types=1);

namespace App\Application\UseCase\CreateNews\Input;

readonly class CreateNewsRequest
{
    public function __construct(public string $url)
    {
    }
}
