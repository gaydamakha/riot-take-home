<?php

declare(strict_types=1);

namespace Tests\Gaydamakha\RiotTakeHome\Domain\Signing;

use Gaydamakha\RiotTakeHome\Domain\Signing\SignPayloadRequest;
use Gaydamakha\RiotTakeHome\Domain\Signing\SignPayloadService;
use Gaydamakha\RiotTakeHome\Domain\Signing\StringSignerInterface;
use Gaydamakha\RiotTakeHome\Domain\Signing\VerifyPayloadRequest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SignPayloadService::class)]
class SignPayloadServiceTest extends TestCase
{
    private readonly StringSignerInterface $signer;
    private readonly SignPayloadService $service;

    public function setUp(): void
    {
        $this->service = new SignPayloadService(
            $this->signer = $this->createMock(StringSignerInterface::class),
        );
    }

    public function testSign(): void
    {
        $request = new SignPayloadRequest($data = '{"foo":"bar"}');
        $this->signer->expects(self::once())
            ->method('sign')
            ->with($data)
            ->willReturn('i am signature');

        $response = $this->service->sign($request);
        $this->assertSame('i am signature', $response->signature);
    }

    public function testVerifyIsValid(): void
    {
        $request = new VerifyPayloadRequest(
            'i am signature',
            $data = '{"foo":"bar"}'
        );
        $this->signer->expects(self::once())
            ->method('sign')
            ->with($data)
            ->willReturn('i am signature');
        $this->signer->expects(self::once())
            ->method('compareSignatures')
            ->with('i am signature', 'i am signature')
            ->willReturn(true);

        $response = $this->service->verify($request);
        $this->assertTrue($response->verified);
    }

    public function testVerifyIsNotValid(): void
    {
        $request = new VerifyPayloadRequest(
            'i am invalid signature',
            $data = '{"foo":"bar"}'
        );
        $this->signer->expects(self::once())
            ->method('sign')
            ->with($data)
            ->willReturn('i am signature');
        $this->signer->expects(self::once())
            ->method('compareSignatures')
            ->with('i am signature', 'i am invalid signature')
            ->willReturn(false);

        $response = $this->service->verify($request);
        $this->assertFalse($response->verified);
    }
}
