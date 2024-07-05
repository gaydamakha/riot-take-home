<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption;

class ProcessPayloadRequest
{
    public function __construct(
        public readonly array $payload // Must be a JSON object
    ) {}
}
