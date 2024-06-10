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

class ValueConverterTest extends TestCase
{
    private ValueConverter $vc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vc = new ValueConverter();
    }

    /**
     * @return array<array<int|string>>
     */
    public static function intProvider(): array
    {
        return [
            [1337, '1337'],
            [1337, '1337.37'],
            [0, ''],
        ];
    }

    #[DataProvider('intProvider')]
    public function testConvertInt(int $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'int');

        self::assertTrue(\is_int($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return array<array<int|string|null>>
     */
    public static function nullableIntProvider(): array
    {
        return [
            [1337, '1337'],
            [1337, '1337.37'],
            [null, ''],
        ];
    }

    #[DataProvider('nullableIntProvider')]
    public function testConvertNullableInt(?int $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?int');

        self::assertSame($expected, $result);
    }

    /**
     * @return array<array<int|float|string>>
     */
    public static function floatProvider(): array
    {
        return [
            [1337, '1337'],
            [1337.37, '1337.37'],
            [0, ''],
        ];
    }

    #[DataProvider('floatProvider')]
    public function testConvertFloat(float $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'float');

        self::assertTrue(\is_float($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return array<array<int|float|string|null>>
     */
    public static function nullableFloatProvider(): array
    {
        return [
            [1337, '1337'],
            [1337.37, '1337.37'],
            [null, ''],
        ];
    }

    #[DataProvider('nullableFloatProvider')]
    public function testConvertNullableFloat(?float $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?float');

        self::assertSame($expected, $result);
    }

    /**
     * @return array<string[]>
     */
    public static function stringProvider(): array
    {
        return [
            ['1337', '1337'],
            ['1337.37', '1337.37'],
            ['', ''],
            ['Special characters #´`+*~!"§$%&/()=?', 'Special characters #´`+*~!"§$%&/()=?'],
            ['Multi'."\n".'Line', 'Multi'."\n".'Line'],
        ];
    }

    #[DataProvider('stringProvider')]
    public function testConvertString(string $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'string');

        self::assertTrue(\is_string($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return array<array<string|null>>
     */
    public static function nullableStringProvider(): array
    {
        return [
            ['1337', '1337'],
            ['1337.37', '1337.37'],
            [null, ''],
            ['Special characters #´`+*~!"§$%&/()=?', 'Special characters #´`+*~!"§$%&/()=?'],
            ['Multi'."\n".'Line', 'Multi'."\n".'Line'],
        ];
    }

    #[DataProvider('nullableStringProvider')]
    public function testConvertNullableString(?string $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?string');

        self::assertSame($expected, $result);
    }

    /**
     * @return array<array<bool|string>>
     */
    public static function boolProvider(): array
    {
        return [
            [true, '1'],
            [true, 'true'],
            [true, 'True'],
            [true, 'on'],
            [true, 'On'],
            [false, '0'],
            [false, 'false'],
            [false, 'False'],
            [false, 'off'],
            [false, 'Off'],
            [false, ''],
        ];
    }

    #[DataProvider('boolProvider')]
    public function testConvertBool(bool $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'bool');

        self::assertTrue(\is_bool($result));
        self::assertSame($expected, $result);
    }

    /**
     * @return array<array<bool|string|null>>
     */
    public static function nullableBoolProvider(): array
    {
        return [
            [true, '1'],
            [true, 'true'],
            [true, 'True'],
            [true, 'on'],
            [true, 'On'],
            [false, '0'],
            [false, 'false'],
            [false, 'False'],
            [false, 'off'],
            [false, 'Off'],
            [null, ''],
        ];
    }

    #[DataProvider('nullableBoolProvider')]
    public function testConvertNullableBool(?bool $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?bool');

        self::assertSame($expected, $result);
    }

    /**
     * @return array<string[]>
     */
    public static function dateTimeProvider(): array
    {
        return [
            ['2020-02-04 00:00:00', '2020-02-04'],
            ['2020-02-04 13:37:37', '2020-02-04 13:37:37'],

            // German date formats
            ['2020-02-04 00:00:00', '04.02.2020'],
            ['2020-02-04 13:37:37', '04.02.2020 13:37:37'],
        ];
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertDateTime(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, \DateTime::class);

        self::assertTrue($result instanceof \DateTime);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertNullableDateTime(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.\DateTime::class);

        self::assertTrue($result instanceof \DateTime);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertDateTimeInterface(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, \DateTimeInterface::class);

        self::assertTrue($result instanceof \DateTimeInterface);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertNullableDateTimeInterface(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.\DateTimeInterface::class);

        self::assertTrue($result instanceof \DateTimeInterface);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertDateTimeImmutable(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, \DateTimeImmutable::class);

        self::assertTrue($result instanceof \DateTimeImmutable);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    #[DataProvider('dateTimeProvider')]
    public function testConvertNullableDateTimeImmutable(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.\DateTimeImmutable::class);

        self::assertTrue($result instanceof \DateTimeImmutable);
        self::assertSame($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @return array<string[]>
     */
    public static function nullValuesProvider(): array
    {
        return [
            [''],
            ['null'],
            ['NULL'],
            ['Null'],
        ];
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
