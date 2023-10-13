<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader;

use Fusonic\CsvReader\Conversion\ValueConverter;
use Fusonic\CsvReader\Conversion\ValueConverterInterface;

class CsvReaderOptions
{
    /**
     * Field enclosure passed to `fgetcsv()` (one character only).
     */
    public string $enclosure = '"';

    /**
     * Field delimiter passed to `fgetcsv()` (one character only).
     */
    public string $delimiter = ',';

    /**
     * Escape character passed to `fgetcsv()` (one character only).
     */
    public string $escape = '\\';

    /**
     * Enables reading headers from the first row and allows to use `TitleMapping` attribute.
     */
    public bool $hasHeaderRow = true;

    /**
     * Automatically removes the BOM (byte-order mark) from the beginning of the file if there is any.
     */
    public bool $removeBOM = true;

    /**
     * Instance of a `ValueConverterInterface` implementation responsible to convert values from the CSV to their
     * respective target types.
     */
    public ValueConverterInterface $valueConverter;

    public function __construct()
    {
        $this->valueConverter = new ValueConverter();
    }
}
