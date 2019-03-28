# ColorWcag2

[![Travis Build Status](https://img.shields.io/travis/diegonz/color-wcag2/master.svg?style=flat-square)](https://travis-ci.org/diegonz/color-wcag2)
[![StyleCI Status](https://github.styleci.io/repos/178073390/shield?branch=master)](https://github.styleci.io/repos/178073390)
[![Codecov Status](https://img.shields.io/codecov/c/github/diegonz/color-wcag2.svg?style=flat-square)](https://codecov.io/gh/diegonz/PHPWakeOnLan)
[![Latest Version on Packagist](https://img.shields.io/packagist/v/diegonz/color-wcag2.svg?style=flat-square)](https://packagist.org/packages/diegonz/color-wcag2)

Laravel simple color accessibility tool, following Web Content Accessibility Guidelines (WCAG) 2.0

## Installation

Require the package using [composer](https://getcomposer.org/):

```bash
composer require diegonz/color-wcag2
```

### Javascript asset [optional]

Run the artisan command to generate de javascript asset:

```bash
php artisan color-wcag2:generate
```

Then include the javascript asset in your template like this:
```html
<script src="{{ asset('js/color-wcag2.js') }}"></script>
``` 

## Usage

### PHP

```php
<?php

use \Diegonz\ColorWcag2\Facades\ColorWcag2;

ColorWcag2::contrast('#5b77a0', '#fffffff');

// bool(true)
```

### Javascript

```javascript
colorWcag2.contrast('#5b77a0', '#fffffff');

// "true"
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email diego@smartidea.es instead of using the issue tracker.

## Credits

- [Diego González](https://github.com/diegonz)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
