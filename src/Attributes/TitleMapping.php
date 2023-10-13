<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY)]
final class TitleMapping extends MappingAttribute
{
    public function __construct(private string $title)
    {
    }

    public function getTitle(): string
    {
        return $this->title;
    }
}
