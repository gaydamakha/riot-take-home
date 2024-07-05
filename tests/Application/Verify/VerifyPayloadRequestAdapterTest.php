<?php

declare(strict_types=1);

namespace Tests\Gaydamakha\RiotTakeHome\Application\Verify;

use Gaydamakha\RiotTakeHome\Application\InvalidPayloadProvidedException;
use Gaydamakha\RiotTakeHome\Application\Verify\VerifyPayloadRequestAdapter;
use JsonSchema\Validator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

#[CoversClass(VerifyPayloadRequestAdapter::class)]
class VerifyPayloadRequestAdapterTest extends TestCase
{
    protected readonly VerifyPayloadRequestAdapter $requestAdapter;

    protected function setUp(): void
    {
        $this->requestAdapter = new VerifyPayloadRequestAdapter(new Validator());
    }

    public static function invalidPayloadProvider(): iterable
    {
        yield [null];
        yield [['foo' => 'bar']];
        yield [
            [
                'signature' => '', // empty signature is not valid
                'data' => [
                    'foo' => 'bar',
                ],
            ],
        ];
        yield [
            [
                'signature' => '123456',
                'data' => null //null data is not valid
            ],
        ];
    }

    #[DataProvider('invalidPayloadProvider')]
    public function testFromThrowsOnInvalidPayload(?array $payload): void
    {
        $serverRequest = $this->createConfiguredMock(ServerRequestInterface::class, [
            'getParsedBody' => $payload,
        ]);

        $this->expectException(InvalidPayloadProvidedException::class);

        $this->requestAdapter->fromServerRequestInterface($serverRequest);
    }

    public function testFrom(): void
    {
        $serverRequest = $this->createConfiguredMock(ServerRequestInterface::class, [
            'getParsedBody' => [
                'signature' => '123456',
                'data' => $data = [
                    'foo' => 'bar',
                ],
            ],
        ]);

        $request = $this->requestAdapter->fromServerRequestInterface($serverRequest);
        self::assertSame(json_encode($data), $request->data);
        self::assertSame('123456', $request->signature);
    }
}
