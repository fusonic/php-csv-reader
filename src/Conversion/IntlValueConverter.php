<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Conversion;

use Fusonic\CsvReader\Exceptions\ValueConversionException;

class IntlValueConverter extends ValueConverter
{
    private \NumberFormatter $numberFormatter;

    public function __construct(string $locale)
    {
        $this->numberFormatter = new \NumberFormatter($locale, \NumberFormatter::TYPE_DEFAULT);
    }

    public function convert(string $value, string $targetType): mixed
    {
        if ($this->isNullValue($value, $targetType)) {
            return null;
        }

        $targetType = ltrim($targetType, '?');

        if ('int' === $targetType) {
            return $this->convertInt($value);
        }

        if ('float' === $targetType) {
            return $this->convertFloat($value);
        }

        return parent::convert($value, $targetType);
    }

    private function convertInt(string $value): int
    {
        try {
            return (int) $this->numberFormatter->parse($value, \NumberFormatter::TYPE_INT32);
        } catch (\Exception $ex) {
            throw ValueConversionException::fromValueAndTargetType($value, 'int', $ex);
        }
    }

    private function convertFloat(string $value): float
    {
        try {
            return (float) $this->numberFormatter->parse($value, \NumberFormatter::TYPE_DOUBLE);
        } catch (\Exception $ex) {
            throw ValueConversionException::fromValueAndTargetType($value, 'float', $ex);
        }
    }
}
