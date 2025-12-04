<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Tests;

use Fusonic\CsvReader\Conversion\ValueConverter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class ValueConverterTest extends TestCase
{
    private ValueConverter $vc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vc = new ValueConverter();
    }

    /**
     * @return \Iterator<array<int|string>>
     */
    public static function intProvider(): \Iterator
    {
        yield [1337, '1337'];

        yield [1337, '1337.37'];

        yield [0, ''];
    }

    #[DataProvider('intProvider')]
    public function testConvertInt(int $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'int');

        self::assertTrue(\is_int($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<int|string|null>>
     */
    public static function nullableIntProvider(): \Iterator
    {
        yield [1337, '1337'];

        yield [1337, '1337.37'];

        yield [null, ''];
    }

    #[DataProvider('nullableIntProvider')]
    public function testConvertNullableInt(?int $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?int');

        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<float|int|string>>
     */
    public static function floatProvider(): \Iterator
    {
        yield [1337, '1337'];

        yield [1337.37, '1337.37'];

        yield [0, ''];
    }

    #[DataProvider('floatProvider')]
    public function testConvertFloat(float $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'float');

        self::assertTrue(\is_float($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<float|int|string|null>>
     */
    public static function nullableFloatProvider(): \Iterator
    {
        yield [1337, '1337'];

        yield [1337.37, '1337.37'];

        yield [null, ''];
    }

    #[DataProvider('nullableFloatProvider')]
    public function testConvertNullableFloat(?float $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?float');

        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function stringProvider(): \Iterator
    {
        yield ['1337', '1337'];

        yield ['1337.37', '1337.37'];

        yield ['', ''];

        yield ['Special characters #´`+*~!"§$%&/()=?', 'Special characters #´`+*~!"§$%&/()=?'];

        yield ['Multi'."\n".'Line', 'Multi'."\n".'Line'];
    }

    #[DataProvider('stringProvider')]
    public function testConvertString(string $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'string');

        self::assertTrue(\is_string($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<string|null>>
     */
    public static function nullableStringProvider(): \Iterator
    {
        yield ['1337', '1337'];

        yield ['1337.37', '1337.37'];

        yield [null, ''];

        yield ['Special characters #´`+*~!"§$%&/()=?', 'Special characters #´`+*~!"§$%&/()=?'];

        yield ['Multi'."\n".'Line', 'Multi'."\n".'Line'];
    }

    #[DataProvider('nullableStringProvider')]
    public function testConvertNullableString(?string $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?string');

        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<bool|string>>
     */
    public static function boolProvider(): \Iterator
    {
        yield [true, '1'];

        yield [true, 'true'];

        yield [true, 'True'];

        yield [true, 'on'];

        yield [true, 'On'];

        yield [false, '0'];

        yield [false, 'false'];

        yield [false, 'False'];

        yield [false, 'off'];

        yield [false, 'Off'];

        yield [false, ''];
    }

    #[DataProvider('boolProvider')]
    public function testConvertBool(bool $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'bool');

        self::assertTrue(\is_bool($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<bool|string|null>>
     */
    public static function nullableBoolProvider(): \Iterator
    {
        yield [true, '1'];

        yield [true, 'true'];

        yield [true, 'True'];

        yield [true, 'on'];

        yield [true, 'On'];

        yield [false, '0'];

        yield [false, 'false'];

        yield [false, 'False'];

        yield [false, 'off'];

        yield [false, 'Off'];

        yield [null, ''];
    }

    #[DataProvider('nullableBoolProvider')]
    public function testConvertNullableBool(?bool $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?bool');

        self::assertSame($expected, $result);
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function dateTimeProvider(): \Iterator
    {
        yield ['2020-02-04 00:00:00', '2020-02-04'];

        yield ['2020-02-04 13:37:37', '2020-02-04 13:37:37'];

        // German date formats
        yield ['2020-02-04 00:00:00', '04.02.2020'];

        yield ['2020-02-04 13:37:37', '04.02.2020 13:37:37'];
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertDateTime(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, \DateTime::class);

        self::assertInstanceOf(\DateTime::class, $result);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertNullableDateTime(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.\DateTime::class);

        self::assertInstanceOf(\DateTime::class, $result);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertDateTimeInterface(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, \DateTimeInterface::class);

        self::assertInstanceOf(\DateTimeInterface::class, $result);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertNullableDateTimeInterface(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.\DateTimeInterface::class);

        self::assertInstanceOf(\DateTimeInterface::class, $result);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertDateTimeImmutable(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, \DateTimeImmutable::class);

        self::assertInstanceOf(\DateTimeImmutable::class, $result);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertNullableDateTimeImmutable(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.\DateTimeImmutable::class);

        self::assertInstanceOf(\DateTimeImmutable::class, $result);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @return \Iterator<array<string>>
     */
    public static function nullValuesProvider(): \Iterator
    {
        yield [''];

        yield ['null'];

        yield ['NULL'];

        yield ['Null'];
    }

    #[DataProvider('nullValuesProvider')]
    public function testConvertNullables(mixed $value): void
    {
        self::assertNull($this->vc->convert($value, '?int'));
        self::assertNull($this->vc->convert($value, '?float'));
        self::assertNull($this->vc->convert($value, '?string'));
        self::assertNull($this->vc->convert($value, '?'.\DateTime::class));
        self::assertNull($this->vc->convert($value, '?'.\DateTimeImmutable::class));
        self::assertNull($this->vc->convert($value, '?'.\DateTimeInterface::class));
    }
}
