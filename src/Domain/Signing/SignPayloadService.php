<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Signing;

use DI\Attribute\Inject;
use Gaydamakha\RiotTakeHome\Domain\Signing\Signers\HmacSigner;

class SignPayloadService
{
    public function __construct(
        #[Inject(HmacSigner::class)] private readonly StringSignerInterface $signer,
    ) {}

    public function sign(SignPayloadRequest $request): SignPayloadResponse
    {
        $signature = $this->signer->sign($request->data);
        return new SignPayloadResponse($signature);
    }

    public function verify(VerifyPayloadRequest $request): VerifyPayloadResponse
    {
        $newSignature = $this->signer->sign($request->data);
        $verified = $this->signer->compareSignatures($newSignature, $request->signature);
        return new VerifyPayloadResponse($verified);
    }
}
