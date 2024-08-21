# CSV Reader

[![License](https://img.shields.io/packagist/l/fusonic/csv-reader?color=blue)](https://github.com/fusonic/php-csv-reader/blob/master/LICENSE)
[![Latest Version](https://img.shields.io/github/tag/fusonic/php-csv-reader.svg?color=blue)](https://github.com/fusonic/php-csv-reader/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/fusonic/csv-reader.svg?color=blue)](https://packagist.org/packages/fusonic/csv-reader)
![php 8.2+](https://img.shields.io/badge/php-min%208.0-blue.svg)

CSV Reader is a powerful library for PHP 8 that allows you to map data from CSV files to a strongly typed data model. It does this by utilizing PHP 8's typed properties / arguments and automatically converts string values to the types defined for your class fields.

## Install

Use composer to install the lib from Packagist.

```bash
composer require fusonic/csv-reader
```

Requirements:

- PHP 8.2+

## Usage

Just define a class that represents your CSV's data structure and use the [`IndexMapping`](src/Attributes/IndexMapping.php) or [`TitleMapping`](src/Attributes/TitleMapping.php) attributes to define the mapping. You don't have to map all the columns, just the ones you need on your model.

```php
class Foo
{
    #[TitleMapping('Price')]
    public float $price;

    #[TitleMapping('Price')]
    public function setPrice(float $value): void
    {
        // Not implemented
    }
}
```

Now use the `CsvReader` class to convert CSV data to your object model:

```php
$reader = new CsvReader('myfile.csv');
foreach ($reader->readObjects(Foo::class) as $item) {
    var_dump($item);
}
```

**Hint:** You can also pass a `resource` instead of a file's path to `CsvReader`. This way you can also access files from remote filesystems, for example if you are using [`league/flysystem`](https://github.com/thephpleague/flysystem). 

## Supported types

- `int`
- `float`
- `string`
- `bool` (uses `filter_var()` with `FILTER_VALIDATE_BOOLEAN` and supports values like `on`, `true`, `1`)
- `DateTime`
- `DateTimeInterface`
- `DateTimeImmutable`

If you choose to use a **nullable type like `?string``** it will be mapped as `null` if the CSV value is an empty string or 'null' (case insensitive).

## Options

Refer to the [`CsvReaderOptions`](src/CsvReaderOptions.php) class to learn about options.

### `IntlValueConverter`

If you need to unserialize floating point numbers from a specific culture, you can use the `IntlValueConverter` like this:

```php
$options = new CsvReaderOptions();
$options->valueConverter = new IntlValueConverter('de-AT');

$reader = new CsvReader($options);
```

**Note:** parsing German date format is done implicitely by the default `ValueConverter` implementation since PHP supports passing German date formats to `DateTimeInterface` derivates.

## Contributing

This is a subtree split of [fusonic/php-extensions](https://github.com/fusonic/php-extensions) repository. Please create your pull requests there. 
