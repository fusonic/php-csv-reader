<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

namespace Fusonic\CsvReader\Tests\data;

use Fusonic\CsvReader\Attributes\TitleMapping;

class WithHeadersModel
{
    #[TitleMapping('Integer')]
    public int $field;

    public float $methodBackingField;

    #[TitleMapping('Float')]
    public function method(float $value): void
    {
        $this->methodBackingField = $value;
    }
}
