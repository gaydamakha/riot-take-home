<?php

declare(strict_types=1);

namespace Tests\Gaydamakha\RiotTakeHome\Application;

use Gaydamakha\RiotTakeHome\Application\NoPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Application\ProcessPayloadRequestAdapter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

#[CoversClass(ProcessPayloadRequestAdapter::class)]
class ProcessPayloadRequestAdapterTest extends TestCase
{
    protected readonly ProcessPayloadRequestAdapter $requestAdapter;

    protected function setUp(): void
    {
        $this->requestAdapter = new ProcessPayloadRequestAdapter();
    }

    public function testFromThrowsOnInvalidPayload(): void
    {
        $serverRequest = $this->createConfiguredMock(ServerRequestInterface::class, [
            'getParsedBody' => null,
        ]);

        $this->expectException(NoPayloadProvidedException::class);

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
        self::assertSame($body, $request->payload);
    }
}
