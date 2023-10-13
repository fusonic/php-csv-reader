<?php

/*
 * Copyright (c) Fusonic GmbH. All rights reserved.
 * Licensed under the MIT License. See LICENSE file in the project root for license information.
 */

declare(strict_types=1);

use Fusonic\CsvReader\Attributes\IndexMapping;
use Fusonic\CsvReader\Attributes\TitleMapping;
use Fusonic\CsvReader\CsvReader;

require __DIR__.'/../vendor/autoload.php';

$reader = new CsvReader(__DIR__.'/example.csv');
foreach ($reader->readObjects(Foo::class) as $item) {
    var_dump($item);
}

echo 'Done.';

class Foo
{
    #[IndexMapping(0)]
    public int $indexProperty;

    #[TitleMapping('field2')]
    public string $titleProperty;

    #[TitleMapping('field3')]
    public ?float $nullableProperty;

    #[IndexMapping(0)]
    public function indexMethod(int $value): void
    {
        // Not implemented
    }

    #[TitleMapping('field2')]
    public function titleMethod(string $value): void
    {
        // Not implemented
    }

    #[TitleMapping('field3')]
    public function nullableMethod(?string $value): void
    {
        // Not implemented
    }
}
