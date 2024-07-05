<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Signing\Signers;

use DI\Attribute\Inject;
use Gaydamakha\RiotTakeHome\Domain\Signing\StringSignerInterface;

class HmacSigner implements StringSignerInterface
{
    public function __construct(
        #[Inject('signing.key')]
        private readonly string $signingKey,
        #[Inject('signing.algorithm')]
        private readonly string $signingAlgorithm,
    ) {}

    public function sign(string $string): string
    {
        return hash_hmac($this->signingAlgorithm, $string, mb_convert_encoding($this->signingKey, 'UTF-8'));
    }

    public function compareSignatures(string $signature, string $clientSignature): bool
    {
        return hash_equals($signature, $clientSignature);
    }
}
