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

    public function intProvider()
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
    public function testConvertInt(int $expected, string $input)
    {
        $result = $this->vc->convert($input, 'int');

        $this->assertTrue(is_int($result));
        $this->assertEquals($expected, $result);
    }

    public function nullableIntProvider()
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
    public function testConvertNullableInt(?int $expected, string $input)
    {
        $result = $this->vc->convert($input, '?int');

        $this->assertEquals($expected, $result);
    }

    public function floatProvider()
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
    public function testConvertFloat(float $expected, string $input)
    {
        $result = $this->vc->convert($input, 'float');

        $this->assertTrue(is_float($result));
        $this->assertEquals($expected, $result);
    }

    public function nullableFloatProvider()
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
    public function testConvertNullableFloat(?float $expected, string $input)
    {
        $result = $this->vc->convert($input, '?float');

        $this->assertEquals($expected, $result);
    }

    public function stringProvider()
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
    public function testConvertString(string $expected, string $input)
    {
        $result = $this->vc->convert($input, 'string');

        $this->assertTrue(is_string($result));
        $this->assertEquals($expected, $result);
    }

    public function nullableStringProvider()
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
    public function testConvertNullableString(?string $expected, string $input)
    {
        $result = $this->vc->convert($input, '?string');

        $this->assertEquals($expected, $result);
    }

    public function boolProvider()
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
    public function testConvertBool(bool $expected, string $input)
    {
        $result = $this->vc->convert($input, 'bool');

        $this->assertTrue(is_bool($result));
        $this->assertEquals($expected, $result);
    }

    public function nullableBoolProvider()
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
    public function testConvertNullableBool(?bool $expected, string $input)
    {
        $result = $this->vc->convert($input, '?bool');

        $this->assertEquals($expected, $result);
    }

    public function dateTimeProvider()
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
    public function testConvertDateTime(string $expected, mixed $input)
    {
        $result = $this->vc->convert($input, DateTime::class);

        $this->assertTrue($result instanceof DateTime);
        $this->assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertNullableDateTime(string $expected, mixed $input)
    {
        $result = $this->vc->convert($input, '?'.DateTime::class);

        $this->assertTrue($result instanceof DateTime);
        $this->assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertDateTimeInterface(string $expected, mixed $input)
    {
        $result = $this->vc->convert($input, DateTimeInterface::class);

        $this->assertTrue($result instanceof DateTimeInterface);
        $this->assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertNullableDateTimeInterface(string $expected, mixed $input)
    {
        $result = $this->vc->convert($input, '?'.DateTimeInterface::class);

        $this->assertTrue($result instanceof DateTimeInterface);
        $this->assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertDateTimeImmutable(string $expected, mixed $input)
    {
        $result = $this->vc->convert($input, DateTimeImmutable::class);

        $this->assertTrue($result instanceof DateTimeImmutable);
        $this->assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

    /**
     * @dataProvider dateTimeProvider
     */
    public function testConvertNullableDateTimeImmutable(string $expected, mixed $input)
    {
        $result = $this->vc->convert($input, '?'.DateTimeImmutable::class);

        $this->assertTrue($result instanceof DateTimeImmutable);
        $this->assertEquals($expected, $result->format('Y-m-d H:i:s'));
    }

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
    public function testConvertNullables($value)
    {
        $this->assertNull($this->vc->convert($value, '?int'));
        $this->assertNull($this->vc->convert($value, '?float'));
        $this->assertNull($this->vc->convert($value, '?string'));
        $this->assertNull($this->vc->convert($value, '?'.DateTime::class));
        $this->assertNull($this->vc->convert($value, '?'.DateTimeImmutable::class));
        $this->assertNull($this->vc->convert($value, '?'.DateTimeInterface::class));
    }
}
