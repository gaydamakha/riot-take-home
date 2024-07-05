<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Application\Verify;

use Gaydamakha\RiotTakeHome\Application\InvalidPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Domain\Signing\SignPayloadService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/verify', description: 'Verify the payload against given signature', tags: ['Signing'],
)]
#[OA\RequestBody(
    required: true,
    content: new OA\JsonContent(properties: [
        new OA\Property('signature', type: 'string', minLength: 1),
        new OA\Property('data', minProperties: 1, example: [
            'foo' => 'bar'
        ]),
    ]),
)]
#[OA\Response(response: 400, description: 'No payload provided or payload is not a JSON, or the payload does not match the signature')]
#[OA\Response(response: 204, description: 'Payload is verified')]
readonly class VerifyPayloadController
{
    public function __construct(
        private VerifyPayloadRequestAdapter $requestAdapter,
        private SignPayloadService $service,
    ) {}

    public function __invoke(ServerRequestInterface $serverRequest, ResponseInterface $serverResponse): ResponseInterface
    {
        try {
            $request = $this->requestAdapter->fromServerRequestInterface($serverRequest);
        } catch (InvalidPayloadProvidedException $e) {
            throw new HttpBadRequestException($serverRequest, $e->getMessage());
        }
        $response = $this->service->verify($request);
        return $serverResponse->withStatus($response->verified ? 204 : 400);
    }
}
