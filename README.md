PHP dotenv extension for [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) to load .env variables from include files. as well as group them into array-like underscore syntax. [![Build Status](https://travis-ci.org/etelford/phpdotenv-include-extension.svg?branch=master)](https://travis-ci.org/etelford/phpdotenv-include-extension)
=========

How &amp; Why?
----

This package works as an add-on to the great [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv). When `.env` files start to get large it can be convenient to separate them out into smaller pieces. This helps solve that problem.

NB: If you're new to the idea of a `.env` file, I recommend you start by checking out [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) first.

Installation
------------

composer.json:

    "require": {
        "etelford/phpdotenv-include-extension": "*"
    }

Example
-------

Create your `.env` file as usual, but where you want to separate out sections of it, use an include.

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

When you want to retrieve an env variable from an include file, create an instance of `Etelford\Dotenv` and then use dot syntax and whichever method you normally use to include environment variables:

```php
# Be sure to include your autoloader
$dotenv = new Etelford\Dotenv(__DIR__);
$dotenv->load();

echo getenv('DATABASE_NAME'); // mydb
echo $_ENV('DATABASE_USERNAME'); // root
echo $_SERVER('DATABASE_PASSWORD'); // s3cr3t
echo env('DATABASE_PORT'); // 3306
```

Conventions
-----------

1. The path of an include is always **relative to your root `.env` file**.
1. You declare that a variable points to an include file using the `.INCLUDE` keyword. If you wish to override this you can with the third argument when you create the `Dotenv` class: `$dotenv = new Etelford\Dotenv(__DIR__, '.env', 'PATH');`

Caveat
------

This package will **not** work as a [command line script](https://github.com/vlucas/phpdotenv#command-line-scripts).