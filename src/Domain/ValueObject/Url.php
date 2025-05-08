<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

use InvalidArgumentException as InvalidArgumentExceptionAlias;

readonly class Url
{
    public function __construct(
        public string $url
    ) {
        $this->validate($url);
    }

    private function validate(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentExceptionAlias("Недопустимый URL: {$url}");
        }
    }
}
