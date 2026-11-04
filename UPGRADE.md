# UPGRADE

## Upgrade from `0.6.1` to `1.0.0`

- `CsvReader::__construct()` no longer accepts `null` for the `$options` parameter (deprecated since `0.5.2`). Either
  omit the argument to use the default options or pass a `CsvReaderOptions` instance:

  ```php
  // Before
  $reader = new CsvReader($file, null);

  // After
  $reader = new CsvReader($file);
  // or
  $reader = new CsvReader($file, new CsvReaderOptions());
  ```

- `symfony/deprecation-contracts` is no longer a dependency of this package.
