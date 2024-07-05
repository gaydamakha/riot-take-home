<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Application\Verify;

use Gaydamakha\RiotTakeHome\Application\InvalidPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Domain\Signing\VerifyPayloadRequest;
use JsonSchema\Validator;
use Psr\Http\Message\ServerRequestInterface;

class VerifyPayloadRequestAdapter
{
    public function __construct(
        private readonly Validator $validator,
    ) {}

    /**
     * @throws InvalidPayloadProvidedException
     */
    public function fromServerRequestInterface(ServerRequestInterface $request): VerifyPayloadRequest
    {
        $body = $request->getParsedBody();
        $body = json_decode(json_encode($body)); //convert to stdClass for the validation
        $this->validator->validate($body, [
            'type' => 'object',
            'properties' => [
                'signature' => [
                    'type' => 'string',
                    'minLength' => 1,
                ],
                'data' => [
                    'type' => 'object',
                    'minProperties' => 1
                ],
            ],
            'required' => ['signature', 'data'],
        ]);
        if (!$this->validator->isValid()) {
            throw new InvalidPayloadProvidedException('The payload is not valid');
        }

        return new VerifyPayloadRequest(
            signature: $body->signature,
            data: json_encode($body->data),
        );
    }
}
