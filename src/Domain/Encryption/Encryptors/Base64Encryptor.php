<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption\Encryptors;

use Gaydamakha\RiotTakeHome\Domain\Encryption\StringEncryptorInterface;

class Base64Encryptor implements StringEncryptorInterface
{
    public function encrypt(string $string): string
    {
        return base64_encode($string);
    }

    public function decrypt(string $string): string
    {
        return base64_decode($string);
    }
}
