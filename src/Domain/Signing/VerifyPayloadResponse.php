<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Signing;

readonly class VerifyPayloadResponse
{
    public function __construct(
        public bool $verified,
    ) {}
}
