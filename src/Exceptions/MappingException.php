<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Exceptions;

final class MappingException extends CsvReaderException
{
    public const int MULTIPLE_MAPPING_ATTRIBUTES_FOUND = 1;
    public const int COLUMN_NOT_FOUND = 2;
    public const int MISSING_HEADER_ROW = 3;
    public const int UNSUPPORTED_MAPPING_ATTRIBUTE = 4;
}
