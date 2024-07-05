<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Signing;

readonly class VerifyPayloadRequest
{
    public function __construct(
        public string $signature,
        public string $data,
    ) {}
}
