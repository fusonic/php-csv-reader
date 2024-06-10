<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader;

use Fusonic\CsvReader\Exceptions\CsvReaderException;
use Fusonic\CsvReader\Mapping\MappingBuilder;
use Fusonic\CsvReader\Mapping\MappingInfo;

class CsvReader
{
    private CsvReaderOptions $options;

    public function __construct(private mixed $file, ?CsvReaderOptions $options = null)
    {
        $this->options = $options ?? new CsvReaderOptions();

        if (!\is_string($file) && !\is_resource($file)) {
            throw new \TypeError(
                'Fusonic\\CsvReader\\CsvReader::__construct(): Argument #1 ($file) must be of type string or resource.'
            );
        }
    }

    /**
     * Iterates data from the configured CSV file and maps each row to an object of type $className.
     *
     * @template T of object
     *
     * @param class-string<T> $className the class which data should be mapped to
     *
     * @return \Traversable<T>
     */
    public function readObjects(string $className): iterable
    {
        /** @var resource $resource */
        $resource = \is_resource($this->file) ? $this->file : fopen($this->file, 'r');
        /** @var int $resourcePosition */
        $resourcePosition = ftell($resource);

        // Skip the BOM
        if ($this->options->removeBOM) {
            $this->skipBOM($resource);
        }

        // Read header
        $header = null;
        if ($this->options->hasHeaderRow) {
            $header = fgetcsv(
                $resource,
                0,
                $this->options->delimiter,
                $this->options->enclosure,
                $this->options->escape
            );

            if (false === $header) {
                throw new CsvReaderException('Cannot get line from file pointer and parse it for CSV fields.');
            }
        }

        // Build mapping
        $mappingBuilder = new MappingBuilder();
        $mapping = $mappingBuilder->build($header, $className);

        while (($row = fgetcsv(
            $resource,
            0,
            $this->options->delimiter,
            $this->options->enclosure,
            $this->options->escape
        )) !== false) {
            yield $this->map($row, $className, $mapping);
        }

        // Rewind resource to its original position or close it if source was a path
        if (\is_resource($this->file)) {
            fseek($this->file, $resourcePosition);
        } else {
            fclose($resource);
        }
    }

    /**
     * @param resource $resource
     */
    private function skipBOM($resource): void
    {
        $position = ftell($resource);

        if (false === $position) {
            throw new CsvReaderException('Cannot read the current position of the file read/write pointer.');
        }

        if (fread($resource, 3) !== \chr(0xEF).\chr(0xBB).\chr(0xBF)) {
            fseek($resource, $position);
        }
    }

    /**
     * @template T of object
     *
     * @param array<mixed>    $row
     * @param class-string<T> $className
     * @param MappingInfo[]   $mappings
     *
     * @return T
     */
    private function map(array $row, string $className, array $mappings): object
    {
        $object = new $className();

        foreach ($mappings as $mapping) {
            /* @var MappingInfo $mapping */
            $mapping->setValue(
                $object,
                $this->options->valueConverter->convert($row[$mapping->getIndex()], $mapping->getType())
            );
        }

        return $object;
    }
}
