<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

use Fusonic\CsvReader\Conversion\ValueConverter;
use PHPUnit\Framework\TestCase;

class ValueConverterTest extends TestCase
{
    private ValueConverter $vc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vc = new ValueConverter();
    }

    public function intProvider(): array
    {
        return [
            [1337, '1337'],
            [1337, '1337.37'],
            [0, ''],
        ];
    }

    /**
     * @dataProvider intProvider
     */
    public function testConvertInt(int $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'int');

        self::assertTrue(is_int($result));
        self::assertEquals($expected, $result);
    }

    /**
     * @return array[]
     */
    public function nullableIntProvider(): array
    {
        return [
            [1337, '1337'],
            [1337, '1337.37'],
            [null, ''],
        ];
    }

    /**
     * @dataProvider nullableIntProvider
     */
    public function testConvertNullableInt(?int $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?int');

        self::assertEquals($expected, $result);
    }

    public function floatProvider(): array
    {
        return [
            [1337, '1337'],
            [1337.37, '1337.37'],
            [0, ''],
        ];
    }

    /**
     * @dataProvider floatProvider
     */
    public function testConvertFloat(float $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'float');

        self::assertTrue(is_float($result));
        self::assertEquals($expected, $result);
    }

    /**
     * @return array[]
     */
    public function nullableFloatProvider(): array
    {
        return [
            [1337, '1337'],
            [1337.37, '1337.37'],
            [null, ''],
        ];
    }

    /**
     * @dataProvider nullableFloatProvider
     */
    public function testConvertNullableFloat(?float $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?float');

        self::assertEquals($expected, $result);
    }

    /**
     * @return string[][]
     */
    public function stringProvider(): array
    {
        return [
            ['1337', '1337'],
            ['1337.37', '1337.37'],
            ['', ''],
            ['Special characters #´`+*~!"§$%&/()=?', 'Special characters #´`+*~!"§$%&/()=?'],
            ['Multi'."\n".'Line', 'Multi'."\n".'Line'],
        ];
    }

    /**
     * @dataProvider stringProvider
     */
    public function testConvertString(string $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'string');

        self::assertTrue(is_string($result));
        self::assertEquals($expected, $result);
    }

    public function nullableStringProvider(): array
    {
        return [
            ['1337', '1337'],
            ['1337.37', '1337.37'],
            [null, ''],
            ['Special characters #´`+*~!"§$%&/()=?', 'Special characters #´`+*~!"§$%&/()=?'],
            ['Multi'."\n".'Line', 'Multi'."\n".'Line'],
        ];
    }

    /**
     * @dataProvider nullableStringProvider
     */
    public function testConvertNullableString(?string $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?string');

        self::assertEquals($expected, $result);
    }

    /**
     * @return array[]
     */
    public function boolProvider(): array
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

    /**
     * @dataProvider boolProvider
     */
    public function testConvertBool(bool $expected, string $input): void
    {
        $result = $this->vc->convert($input, 'bool');

        self::assertTrue(is_bool($result));
        self::assertEquals($expected, $result);
    }

    /**
     * @return array[]
     */
    public function nullableBoolProvider(): array
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

    /**
     * @dataProvider nullableBoolProvider
     */
    public function testConvertNullableBool(?bool $expected, string $input): void
    {
        $result = $this->vc->convert($input, '?bool');

        self::assertEquals($expected, $result);
    }

    /**
     * @return string[][]
     */
    public function dateTimeProvider(): array
    {
        return [
            ['2020-02-04 00:00:00', '2020-02-04'],
            ['2020-02-04 13:37:37', '2020-02-04 13:37:37'],

            // German date formats
            ['2020-02-04 00:00:00', '04.02.2020'],
            ['2020-02-04 13:37:37', '04.02.2020 13:37:37'],
        ];
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertDateTime(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, DateTime::class);

        self::assertTrue($result instanceof DateTime);
        self::assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertNullableDateTime(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.DateTime::class);

        self::assertTrue($result instanceof DateTime);
        self::assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertDateTimeInterface(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, DateTimeInterface::class);

        self::assertTrue($result instanceof DateTimeInterface);
        self::assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertNullableDateTimeInterface(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.DateTimeInterface::class);

        self::assertTrue($result instanceof DateTimeInterface);
        self::assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertDateTimeImmutable(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, DateTimeImmutable::class);

        self::assertTrue($result instanceof DateTimeImmutable);
        self::assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertNullableDateTimeImmutable(string $expected, mixed $input): void
    {
        $result = $this->vc->convert($input, '?'.DateTimeImmutable::class);

        self::assertTrue($result instanceof DateTimeImmutable);
        self::assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @return string[][]
     */
    public function nullValuesProvider(): array
    {
        return [
            [''],
            ['null'],
            ['NULL'],
            ['Null'],
        ];
    }

    /**
     * @dataProvider nullValuesProvider
     */
    public function testConvertNullables(mixed $value): void
    {
        self::assertNull($this->vc->convert($value, '?int'));
        self::assertNull($this->vc->convert($value, '?float'));
        self::assertNull($this->vc->convert($value, '?string'));
        self::assertNull($this->vc->convert($value, '?'.DateTime::class));
        self::assertNull($this->vc->convert($value, '?'.DateTimeImmutable::class));
        self::assertNull($this->vc->convert($value, '?'.DateTimeInterface::class));
    }
}
