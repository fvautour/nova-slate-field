# Laravel Nova Slate field

[![StyleCI](https://styleci.io/repos/340378574/shield)](https://styleci.io/repos/340378574)
[![Quality Score](https://img.shields.io/scrutinizer/g/bbs-lab/nova-slate-field.svg?style=flat-square)](https://scrutinizer-ci.com/g/bbs-lab/nova-slate-field)

## Contents

- [Installation](#installation)
- [Usage](#usage)
- [Changelog](#changelog)
- [Security](#security)
- [Contributing](#contributing)
- [Credits](#credits)
- [License](#license)

## Installation

You can install the nova slate field in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:


``` bash
composer require bbs-lab/nova-slate-field
```

The service provider will automatically get registered. Or you may manually add the service provider in your `config/app.php` file:

```php
'providers' => [
    // ...
    BBSLab\NovaSlateField\FieldServiceProvider::class,
],
```

## Usage

You can use the `BBSLab\CloudinaryField\Cloudinary` field in your Nova resource:

```php
<?php

namespace App\Nova;

use BBSLab\NovaSlateField\Slate;

class BlogPost extends Resource
{
    // ...
    
    public function fields(Request $request)
    {
        return [
            // ...

            Slate::make('Text'),

            // ...
        ];
    }
    
}
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Security

If you discover any security related issues, please email paris@big-boss-studio.com instead of using the issue tracker.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Mikaël Popowicz](https://github.com/mikaelpopowicz)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
