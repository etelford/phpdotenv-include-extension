PHP dotenv extension for [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv)
to load .env variables from include files. [![Build Status](https://travis-ci.org/etelford/phpdotenv-include-extension.svg?branch=master)](https://travis-ci.org/etelford/phpdotenv-include-extension)
=========

How &amp; Why?
----

This package works as an add-on to the great [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv).
When `.env` files start to get large it could be convenient to separate
variables out into smaller pieces. This helps solve that problem.

NB: If you're new to the idea of a `.env` file, I recommend you start by
checking out [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) first.

Installation
------------

composer.json:

    "require": {
        "etelford/phpdotenv-include-extension": "0.1.*"
    }

Example
-------

Create your `.env` file as usual, but where you want to separate out sections
of it, use an include.

```shell
# in your root .env
APP_NAME="My app"
DATABASE_INCLUDE=".database.env"

# .database.env
NAME=mydb
USERNAME=root
PASSWORD=s3cr3t
PORT=3306
```

When you want to retrieve an `env` variable from an include file, create an
instance of `Etelford\Dotenv` and then retrieve the variables using whichever
method you normally use to include environment variables:

```php
# Be sure to include your autoloader
$dotenv = new Etelford\Dotenv(__DIR__);
$dotenv->load();

echo getenv('DATABASE_NAME'); // mydb
echo $_ENV('DATABASE_USERNAME'); // root
echo $_SERVER('DATABASE_PASSWORD'); // s3cr3t
echo env('DATABASE_PORT'); // 3306
```

As you can see, the variables in an include file become namespaced. In the
example above, `.database.env` is included by using a namespace (`DATABASE`)
followed by and underscore (`_`) and the word `INCLUDE`.

Using With Laravel 5.x
----------------------

If you'd like to use this with the [Laravel](https://laravel.com) framework,
open your `bootstrap/app.php` and add the following after the application is
initially created:

```php
// This creates the application
$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

// Add this to enable .env includes
$dotenv = new Etelford\Dotenv($app->environmentPath());
$dotenv->load();
```

That's it! Now you can use included `.env` files as much as you like.

Conventions
-----------

1. The path of an include is always **relative to your root `.env` file**.
1. You declare that a variable points to an include file using the `_INCLUDE`
keyword. If you wish to override this you can with the third argument when you
create the `Dotenv` class: `$dotenv = new Etelford\Dotenv(__DIR__, '.env', 'PATH');`
Note that the `_` should not be provided if you use this customization.

Caveat
------

This package will **not** work as a [command line script](https://github.com/vlucas/phpdotenv#command-line-scripts).
