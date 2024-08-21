<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Tests;

use Fusonic\CsvReader\Attributes\TitleMapping;
use Fusonic\CsvReader\CsvReader;
use Fusonic\CsvReader\CsvReaderOptions;
use Fusonic\CsvReader\Exceptions\MappingException;
use Fusonic\CsvReader\Tests\data\WithHeadersModel;
use Fusonic\CsvReader\Tests\data\WithoutHeadersModel;
use PHPUnit\Framework\TestCase;

class CsvReaderTest extends TestCase
{
    public function testReadWithHeadersAndTitleMapping(): void
    {
        $reader = new CsvReader(__DIR__.'/data/with_headers.csv');

        $item = iterator_to_array($reader->readObjects(WithHeadersModel::class))[0];

        self::assertSame(1, $item->field);
        self::assertSame(1.11, $item->methodBackingField);
    }

    public function testReadWithHeadersAndTitleMappingAsResource(): void
    {
        $reader = new CsvReader(fopen(__DIR__.'/data/with_headers.csv', 'r'));

        $item = iterator_to_array($reader->readObjects(WithHeadersModel::class))[0];

        self::assertSame(1, $item->field);
        self::assertSame(1.11, $item->methodBackingField);
    }

    public function testResourceIsRewinded(): void
    {
        $reader = new CsvReader(fopen(__DIR__.'/data/with_headers.csv', 'r'));

        iterator_to_array($reader->readObjects(WithHeadersModel::class));

        // Read once again
        $item = iterator_to_array($reader->readObjects(WithHeadersModel::class))[0];

        self::assertSame(1, $item->field);
        self::assertSame(1.11, $item->methodBackingField);
    }

    public function testReadWithHeadersAndIndexMapping(): void
    {
        $reader = new CsvReader(__DIR__.'/data/with_headers.csv');

        $item = iterator_to_array($reader->readObjects(WithoutHeadersModel::class))[0];

        self::assertSame(1, $item->field);
        self::assertSame(1.11, $item->methodBackingField);
    }

    public function testReadWithoutHeadersAndIndexMapping(): void
    {
        $options = new CsvReaderOptions();
        $options->hasHeaderRow = false;

        $reader = new CsvReader(__DIR__.'/data/without_headers.csv', $options);

        $item = iterator_to_array($reader->readObjects(WithoutHeadersModel::class))[0];

        self::assertSame(1, $item->field);
        self::assertSame(1.11, $item->methodBackingField);
    }

    public function testCsvSettingsChangedDelimiterAndEnclosure(): void
    {
        $options = new CsvReaderOptions();
        $options->delimiter = ';';
        $options->enclosure = '#';

        $reader = new CsvReader(__DIR__.'/data/csv_settings.csv', $options);
        $class = new class {
            #[TitleMapping('field1')] public int $field1;
            #[TitleMapping('field2')] public string $field2;
        };

        foreach ($reader->readObjects($class::class) as $item) {
            self::assertSame(1, $item->field1);
            self::assertSame(';', $item->field2);
        }
    }

    public function testBomRemovalautoRemoveIfExistent(): void
    {
        $options = new CsvReaderOptions();
        $options->removeBOM = true;

        $reader = new CsvReader(__DIR__.'/data/with_headers_bom.csv', $options);
        $item = iterator_to_array($reader->readObjects(WithHeadersModel::class))[0];

        self::assertSame(1, $item->field);
    }

    public function testBomRemovaldontChangeIfNotExistent(): void
    {
        $options = new CsvReaderOptions();
        $options->removeBOM = true;

        $reader = new CsvReader(__DIR__.'/data/with_headers.csv', $options);
        $item = iterator_to_array($reader->readObjects(WithHeadersModel::class))[0];

        self::assertSame(1, $item->field);
    }

    public function testBomRemovalfailIfExistsAndNotAutoremoved(): void
    {
        // The field will not be found because it's title is prefixed with the BOM!
        $this->expectException(MappingException::class);

        $options = new CsvReaderOptions();
        $options->removeBOM = false;

        $reader = new CsvReader(__DIR__.'/data/with_headers_bom.csv', $options);
        $item = iterator_to_array($reader->readObjects(WithHeadersModel::class))[0];

        self::assertSame(1, $item->field);
    }

    public function testInvalidSourceType(): void
    {
        $this->expectException(\TypeError::class);

        new CsvReader(new \stdClass());
    }
}
