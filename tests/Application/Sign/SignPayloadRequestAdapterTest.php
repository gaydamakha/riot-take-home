<?php

declare(strict_types=1);

namespace Tests\Gaydamakha\RiotTakeHome\Application\Sign;

use Gaydamakha\RiotTakeHome\Application\InvalidPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Application\Sign\SignPayloadRequestAdapter;
use JsonSchema\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

#[CoversClass(SignPayloadRequestAdapter::class)]
class SignPayloadRequestAdapterTest extends TestCase
{
    protected readonly SignPayloadRequestAdapter $requestAdapter;

    protected function setUp(): void
    {
        $this->requestAdapter = new SignPayloadRequestAdapter(new Validator());
    }

    public function testFromThrowsOnInvalidPayload(): void
    {
        $serverRequest = $this->createConfiguredMock(ServerRequestInterface::class, [
            'getParsedBody' => null,
        ]);

        $this->expectException(InvalidPayloadProvidedException::class);

        $this->requestAdapter->fromServerRequestInterface($serverRequest);
    }

    public function testFrom(): void
    {
        $serverRequest = $this->createConfiguredMock(ServerRequestInterface::class, [
            'getParsedBody' => $body = [
                'foo' => 'bar'
            ],
        ]);

        $request = $this->requestAdapter->fromServerRequestInterface($serverRequest);
        self::assertSame(json_encode($body), $request->data);
    }
}
