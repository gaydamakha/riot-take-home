<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Application;

use Gaydamakha\RiotTakeHome\Domain\Encryption\ProcessPayloadRequest;
use Psr\Http\Message\ServerRequestInterface;

class ProcessPayloadRequestAdapter
{
    /**
     * @throws InvalidPayloadProvidedException
     */
    public function fromServerRequestInterface(ServerRequestInterface $request): ProcessPayloadRequest
    {
        $body = $request->getParsedBody();
        if ($body === null) {
            throw new InvalidPayloadProvidedException('No payload provided or payload is not a JSON');
        }
        return new ProcessPayloadRequest($request->getParsedBody());
    }
}
