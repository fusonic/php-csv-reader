<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Exceptions;

final class MappingException extends CsvReaderException
{
    public const MULTIPLE_MAPPING_ATTRIBUTES_FOUND = 1;
    public const COLUMN_NOT_FOUND = 2;
    public const MISSING_HEADER_ROW = 3;
    public const UNSUPPORTED_MAPPING_ATTRIBUTE = 4;

    public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
