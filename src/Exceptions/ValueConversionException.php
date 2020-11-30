<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

namespace Fusonic\CsvReader\Exceptions;

use Exception;
use Throwable;

final class ValueConversionException extends Exception
{
    public const TYPE_NOT_SUPPORTED = 1;
    public const CONVERSION_FAILED = 2;

    public function __construct(string $message, int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function fromValueAndTargetType(string $value, string $targetType, ?Throwable $innerException = null): ValueConversionException
    {
        return new ValueConversionException(sprintf('Could not parse "%s" as "%s".', $value, $targetType), ValueConversionException::CONVERSION_FAILED, $innerException);
    }
}
