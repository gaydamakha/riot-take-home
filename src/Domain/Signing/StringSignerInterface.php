<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Signing;

interface StringSignerInterface
{
    public function sign(string $string): string;

    public function compareSignatures(string $signature, string $clientSignature): bool;
}
