<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

use Fusonic\CsvReader\Conversion\IntlValueConverter;
use PHPUnit\Framework\TestCase;

class IntlValueConverterTest extends TestCase
{
    private IntlValueConverter $vc;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vc = new IntlValueConverter('de-AT');
    }

    public function testConvertFloat()
    {
        $this->assertEquals(1337.37, $this->vc->convert('1337,37', 'float'));
    }
}
