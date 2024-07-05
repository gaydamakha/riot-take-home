<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption;

interface StringEncryptorInterface
{
    public function encrypt(string $string): string;

    public function decrypt(string $string): string;
}
