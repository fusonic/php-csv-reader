<?php

// Copyright (c) Fusonic GmbH. All rights reserved.
// Licensed under the MIT License. See LICENSE file in the project root for license information.

declare(strict_types=1);

namespace Fusonic\CsvReader\Conversion;

interface ValueConverterInterface
{
    public function convert(string $value, string $targetType): mixed;

    public function supports(string $targetType): bool;
}
