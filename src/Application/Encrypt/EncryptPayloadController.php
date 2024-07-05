<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Application\Encrypt;

use Gaydamakha\RiotTakeHome\Application\InvalidPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Application\ProcessPayloadRequestAdapter;
use Gaydamakha\RiotTakeHome\Domain\Encryption\ProcessPayloadService;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/encrypt', description: 'Encrypt the payload', tags: ['Encryption'],
)]
#[OA\RequestBody(
    required: true,
    content: new OA\JsonContent(),
)]
#[OA\Response(response: 400, description: 'No payload provided or payload is not a JSON')]
#[OA\Response(response: 200, description: 'Encrypted payload', content: new OA\JsonContent())]
readonly class EncryptPayloadController
{
    public function __construct(
        private ProcessPayloadRequestAdapter $requestAdapter,
        private ProcessPayloadService $service,
    ) {}

    public function __invoke(ServerRequestInterface $serverRequest, ResponseInterface $serverResponse): ResponseInterface
    {
        try {
            $request = $this->requestAdapter->fromServerRequestInterface($serverRequest);
        } catch (InvalidPayloadProvidedException $e) {
            throw new HttpBadRequestException($serverRequest, $e->getMessage());
        }
        $response = $this->service->encrypt($request);
        return $serverResponse->withHeader('Content-type', 'application/json')
            ->withBody(Psr17FactoryDiscovery::findStreamFactory()
            ->createStream(json_encode($response->payload)));
    }
}
