<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ProcessPayloadService::class)]
class ProcessPayloadServiceTest extends TestCase
{
    private readonly StringEncryptorInterface $stringEncryptor;
    private readonly ProcessPayloadService $service;

    public function setUp(): void
    {
        $this->service = new ProcessPayloadService(
            $this->stringEncryptor = $this->createMock(StringEncryptorInterface::class),
        );
    }

    public function testEncrypt(): void
    {
        $payload = [
            'foo' => 'some_string',
            'bar' => [
                'buz' => 'koko',
            ],
        ];
        $this->stringEncryptor->expects(self::exactly(2))
            ->method('encrypt')
            ->willReturnMap([
                ['some_string', 'encoded_string_1'],
                ['{"buz":"koko"}', 'encoded_string_2'],
            ]);

        $response = $this->service->encrypt(new ProcessPayloadRequest($payload));
        self::assertSame('encoded_string_1', $response->payload['foo']);
        self::assertSame('encoded_string_2', $response->payload['bar']);
    }

    public function testDecrypt(): void
    {
        $payload = [
            'foo' => 'encoded_string_1',
            'bar' => 'encoded_string_2',
        ];
        $this->stringEncryptor->expects(self::exactly(2))
            ->method('decrypt')
            ->willReturnMap([
                ['encoded_string_1', 'some_string'],
                ['encoded_string_2', '{"buz":"koko"}'],
            ]);

        $response = $this->service->decrypt(new ProcessPayloadRequest($payload));
        self::assertSame('some_string', $response->payload['foo']);
        self::assertSame([
            'buz' => 'koko',
        ], $response->payload['bar']);
    }
}
