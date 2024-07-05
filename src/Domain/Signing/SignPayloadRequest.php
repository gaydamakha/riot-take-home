<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Signing;

readonly class SignPayloadRequest
{
    public function __construct(
        public string $data,
    ) {}
}
