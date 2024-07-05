<?php

declare(strict_types=1);

namespace Gaydamakha\RiotTakeHome\Domain\Encryption;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(MixedToStringAdapter::class)]
class MixedToStringAdapterTest extends TestCase
{
    public static function fromProvider(): iterable
    {
        yield [true, 'true'];
        yield [false, 'false'];
        yield [123, '123'];
        yield [123.45, '123.45'];
        yield ['some-string', 'some-string'];
        yield [null, 'null'];
        yield [['foo' => 'bar'], '{"foo":"bar"}'];
        yield [(object)['foo' => 'bar'], '{"foo":"bar"}'];
    }

    #[DataProvider('fromProvider')]
    public function testFrom(mixed $value, string $expected): void
    {
        $result = MixedToStringAdapter::from($value);
        self::assertSame($expected, $result);
    }

    public static function toProvider(): iterable
    {
        yield ['true', true];
        yield ['false', false];
        yield ['123', 123];
        yield ['123.45', 123.45];
        yield ['some-string', 'some-string'];
        yield ['null', null];
        yield ['{"foo":"bar"}', ['foo' => 'bar']];
    }

    #[DataProvider('toProvider')]
    public function testTo(string $value, mixed $expected): void
    {
        $result = MixedToStringAdapter::to($value);
        self::assertSame($expected, $result);
    }
}
