<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption;

class ProcessPayloadResponse
{
    public function __construct(
        public readonly array $payload,
    ) {}
}
