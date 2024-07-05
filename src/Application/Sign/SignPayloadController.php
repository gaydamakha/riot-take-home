<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Application\Sign;

use Gaydamakha\RiotTakeHome\Application\InvalidPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Domain\Signing\SignPayloadService;
use Http\Discovery\Psr17FactoryDiscovery;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/sign', description: 'Sign the payload', tags: ['Signing'],
)]
#[OA\RequestBody(
    required: true,
    content: new OA\JsonContent(minProperties: 1),
)]
#[OA\Response(response: 400, description: 'No payload provided or payload is not a JSON')]
#[OA\Response(response: 200, description: 'The signature of the payload', content: new OA\JsonContent(properties: [
    new OA\Property('signature')
]))]
readonly class SignPayloadController
{
    public function __construct(
        private SignPayloadRequestAdapter $requestAdapter,
        private SignPayloadService $service,
    ) {}

    public function __invoke(ServerRequestInterface $serverRequest, ResponseInterface $serverResponse): ResponseInterface
    {
        try {
            $request = $this->requestAdapter->fromServerRequestInterface($serverRequest);
        } catch (InvalidPayloadProvidedException $e) {
            throw new HttpBadRequestException($serverRequest, $e->getMessage());
        }
        $response = $this->service->sign($request);
        return $serverResponse->withHeader('Content-type', 'application/json')
            ->withBody(Psr17FactoryDiscovery::findStreamFactory()
            ->createStream(json_encode([
                'signature' => $response->signature,
            ])));
    }
}
