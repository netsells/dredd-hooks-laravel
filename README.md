# Laravel Hooks for Dredd API Testing Framework
[![Packagist](https://img.shields.io/packagist/v/netsells/dredd-hooks-laravel.svg)](https://packagist.org/packages/netsells/dredd-hooks-laravel)
[![Packagist](https://img.shields.io/packagist/dt/netsells/dredd-hooks-laravel.svg)](https://packagist.org/packages/netsells/dredd-hooks-laravel)
[![license](https://img.shields.io/github/license/netsells/dredd-hooks-laravel.svg)]()

This package contains a PHP Dredd hook handler which provides a bridge between the [Dredd API Testing Framework](http://dredd.readthedocs.org/en/latest/)
 and PHP environment to ease implementation of testing hooks provided by [Dredd](http://dredd.readthedocs.org/en/latest/). Most of the heavy lifting is provided by the [ddelnano/dredd-hooks-php](https://github.com/ddelnano/dredd-hooks-php) package.

## Installation
### Composer

Laravel Hooks for Dredd should be installed via composer, we recommend you put this in your require-dev section.

```bash
composer require netsells/dredd-hooks-laravel --dev
```

### Dredd Setup
In order to inject environment variables and use the full power of Larvel Dredd Hooks, you need to add the following to your `dredd.yml` file (or put in your console arguments).

```yml
# This can be any single file which extends Netsells\Dredd\Kernel
hookfiles: 'tests/dredd/Kernel.php'

language: 'vendor/bin/dredd-hooks-laravel'
server: 'php -S 127.0.0.1:3000 ./vendor/netsells/dredd-hooks-laravel/server.php -t public/'
endpoint: 'http://127.0.0.1:3000'
```

## Usage

The package requires you to make a single file (named in the `hookfiles` part of dredd.yml above). This should have at least the `handle` method.

```php
<?php

namespace Tests\Dredd;

use Netsells\Dredd\Hook;
use Netsells\Dredd\Transaction;
use Illuminate\Support\Facades\Artisan;
use Netsells\Dredd\Kernel as DreddKernel;

class Kernel extends DreddKernel
{
    public function handle(Hook $hook)
    {
        $this->beforeEach(function (Transaction &$transaction) {
            Artisan::call('migrate:fresh');
            Artisan::call('passport:install');

            Artisan::call('db:seed');
        });

        $hook->group('Posts', Hooks\Posts::class);
    }
}
```
