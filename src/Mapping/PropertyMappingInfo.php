<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Mapping;

/**
 * @internal
 */
final class PropertyMappingInfo extends MappingInfo
{
    public function __construct(
        string $targetType,
        private string $propertyName,
        int $sourceIndex,
    ) {
        parent::__construct($targetType, $sourceIndex);
    }

    public function getPropertyName(): string
    {
        return $this->propertyName;
    }

    public function setValue(object $object, mixed $value): void
    {
        // @phpstan-ignore-next-line
        $object->{$this->propertyName} = $value;
    }
}
