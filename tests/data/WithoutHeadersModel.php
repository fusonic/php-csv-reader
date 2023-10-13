<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Tests\data;

use Fusonic\CsvReader\Attributes\IndexMapping;

class WithoutHeadersModel
{
    #[IndexMapping(0)]
    public int $field;

    public float $methodBackingField;

    #[IndexMapping(1)]
    public function method(float $value): void
    {
        $this->methodBackingField = $value;
    }
}
