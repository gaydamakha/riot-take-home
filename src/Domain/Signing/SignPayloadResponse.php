<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Signing;

readonly class SignPayloadResponse
{
    public function __construct(
        public string $signature
    ) {}
}
