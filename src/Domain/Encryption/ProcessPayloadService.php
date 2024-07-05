<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption;

use DI\Attribute\Inject;
use Gaydamakha\RiotTakeHome\Domain\Encryption\Encryptors\Base64Encryptor;

class ProcessPayloadService
{
    public function __construct(
        #[Inject(Base64Encryptor::class)] private readonly StringEncryptorInterface $stringEncryptor,
    ) {}

    public function encrypt(ProcessPayloadRequest $request): ProcessPayloadResponse
    {
        $responsePayload = [];
        foreach ($request->payload as $key => $value) {
            $stringToEncrypt = MixedToStringAdapter::from($value);
            $responsePayload[$key] = $this->stringEncryptor->encrypt($stringToEncrypt);
        }
        return new ProcessPayloadResponse($responsePayload);
    }

    public function decrypt(ProcessPayloadRequest $request): ProcessPayloadResponse
    {
        $responsePayload = [];
        foreach ($request->payload as $key => $value) {
            $decryptedString = $this->stringEncryptor->decrypt($value);
            $responsePayload[$key] = MixedToStringAdapter::to($decryptedString);
        }
        return new ProcessPayloadResponse($responsePayload);
    }
}
