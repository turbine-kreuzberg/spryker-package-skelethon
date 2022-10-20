# :package_description

<!--delete-->
---
This repo can be used to scaffold a Laravel package. Follow these steps to get started:

1. Press the "Use this template" button at the top of this repo to create a new repo with the contents of this skeleton.
2. Run "php ./configure.php" to run a script that will replace all placeholders throughout all the files.
3. Have fun creating your package.
---
<!--/delete-->

## About the package
This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

### Features
- Enumerate the features of the package

## Installation
### Composer
You can install the package via composer:

```bash
composer require :vendor_slug/:package_slug
```

### Configuration
Add the `VendorName` namespace to the `CORE_NAMESPACES` in your `config/Shared/config_default.php` file:

```php
$config[KernelConstants::CORE_NAMESPACES] = [
    // ...
    'VendorName',
];
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
