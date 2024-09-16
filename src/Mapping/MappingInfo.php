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
abstract class MappingInfo
{
    protected function __construct(
        private string $targetType,
        private int $sourceIndex,
    ) {
    }

    public function getType(): string
    {
        return $this->targetType;
    }

    public function getIndex(): int
    {
        return $this->sourceIndex;
    }

    abstract public function setValue(object $object, mixed $value): void;
}
