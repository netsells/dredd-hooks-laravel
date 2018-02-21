# Laravel Hooks for Dredd API Testing Framework
This package contains a PHP Dredd hook handler which provides a bridge between the [Dredd API Testing Framework](http://dredd.readthedocs.org/en/latest/)
 and PHP environment to ease implementation of testing hooks provided by [Dredd](http://dredd.readthedocs.org/en/latest/). Most of the heavy loading is provided by the [ddelnano/dredd-hooks-php](https://github.com/ddelnano/dredd-hooks-php) package.
 
##  Dredd Setup
In order to inject environment variables and use the full power of Larvel Dredd Hooks, you need to add the following to your `dredd.yml` file (or put in your console arguments).

```yml
# This can be any single file which extends Netsells\Dredd\Kernel
hookfiles: 'tests/dredd/Kernel.php'

language: 'vendor/bin/dredd-hooks-laravel'
server: 'php -S 127.0.0.1:3000 ./vendor/netsells/dredd-hooks-laravel/server.php -t public/'
endpoint: 'http://127.0.0.1:3000'
```