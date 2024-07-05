<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Application\Sign;

use Gaydamakha\RiotTakeHome\Application\InvalidPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Domain\Signing\SignPayloadRequest;
use JsonSchema\Validator;
use Psr\Http\Message\ServerRequestInterface;

class SignPayloadRequestAdapter
{
    public function __construct(
        private readonly Validator $validator,
    ) {}

    /**
     * @throws InvalidPayloadProvidedException
     */
    public function fromServerRequestInterface(ServerRequestInterface $request): SignPayloadRequest
    {
        $body = $request->getParsedBody();
        $body = json_decode(json_encode($body)); //convert to stdClass for the validation

        $this->validator->validate($body, [
            'type' => 'object',
            'minProperties' => 1
        ]);
        if (!$this->validator->isValid()) {
            throw new InvalidPayloadProvidedException('The payload is not valid');
        }

        return new SignPayloadRequest(json_encode($body));
    }
}
