<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Conversion;

use Fusonic\CsvReader\Exceptions\ValueConversionException;

class ValueConverter implements ValueConverterInterface
{
    public function convert(string $value, string $targetType): mixed
    {
        if ($this->isNullValue($value, $targetType)) {
            return null;
        }

        $targetType = ltrim($targetType, '?');

        if ('int' === $targetType) {
            return (int) $value;
        }

        if ('float' === $targetType) {
            return (float) $value;
        }

        if ('string' === $targetType) {
            return $value;
        }

        if ('bool' === $targetType) {
            return filter_var($value, \FILTER_VALIDATE_BOOL);
        }

        if (\DateTimeInterface::class === $targetType || \DateTimeImmutable::class === $targetType) {
            try {
                return new \DateTimeImmutable($value);
            } catch (\Throwable $ex) {
                throw ValueConversionException::fromValueAndTargetType($value, $targetType, $ex);
            }
        }

        if (\DateTime::class === $targetType) {
            try {
                return new \DateTime($value);
            } catch (\Throwable $ex) {
                throw ValueConversionException::fromValueAndTargetType($value, $targetType, $ex);
            }
        }

        throw new ValueConversionException(\sprintf('"%s" is not a supported type.', $targetType), ValueConversionException::TYPE_NOT_SUPPORTED);
    }

    protected function isNullValue(string $value, string $targetType): bool
    {
        return str_starts_with($targetType, '?') && ('' === $value || 'null' === strtolower(trim($value)));
    }

    public function supports(string $targetType): bool
    {
        return \in_array($targetType, [
            'int',
            'float',
            'string',
            'bool',
            \DateTime::class,
            \DateTimeImmutable::class,
            \DateTimeInterface::class,
        ], true);
    }
}
