<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

namespace Fusonic\CsvReader;

use Fusonic\CsvReader\Mapping\MappingBuilder;
use Fusonic\CsvReader\Mapping\MappingInfo;

class CsvReader
{
    private CsvReaderOptions $options;

    public function __construct(
        private string $file,
        ?CsvReaderOptions $options = null
    ) {
        $this->options = $options ?? new CsvReaderOptions();
    }

    /**
     * Iterates data from the configured CSV file and maps each row to an object of type $className.
     *
     * @param string $className the class which data should be mapped to
     */
    public function readObjects(string $className): iterable
    {
        $resource = fopen($this->file, 'r');

        // Read header
        $header = null;
        if ($this->options->hasHeaderRow) {
            $header = fgetcsv($resource, 0, $this->options->delimiter, $this->options->enclosure, $this->options->escape);
        }

        // Build mapping
        $mappingBuilder = new MappingBuilder();
        $mapping = $mappingBuilder->build($header, $className);

        while (($row = fgetcsv($resource, 0, $this->options->delimiter, $this->options->enclosure, $this->options->escape)) !== false) {
            yield $this->map($row, $className, $mapping);
        }

        fclose($resource);
    }

    private function map(array $row, string $className, array $mappings): object
    {
        $object = new $className();

        foreach ($mappings as $mapping) {
            /* @var MappingInfo $mapping */
            $mapping->setValue($object, $this->options->valueConverter->convert($row[$mapping->getIndex()], $mapping->getType()));
        }

        return $object;
    }
}
