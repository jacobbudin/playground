# Playground

Playground is a command line tool for evaluating PHP packages from [Packagist](https://packagist.org) (via [Composer](https://getcomposer.org)) in the [Boris PHP REPL](https://github.com/d11wtq/playground).

[![Travis status](https://travis-ci.org/jacobbudin/playground.png?branch=master)](https://travis-ci.org/jacobbudin/playground)

## Why?

Let's say you're looking for a better way to evaluate datetimes in your latest Web app, and you want to try, say, the [jasonlewis/expressive-date](https://packagist.org/packages/jasonlewis/expressive-date) package. You could add the package to your `composer.json` and fiddle with Expressive Date inside your MVC framework of choice, but that's a time-consuming task just to evalute a package. And there's a risk you accidentally commit the package to your Composer configuration, or, even worse, you accidentally leave a few package-dependent lines in your application logic.

Couldn't it be easier? Playground leverages the power of Composer package management with the easy-of-use of the Boris REPL. Playground lets you download packages, autoload the package's classes, and launch a REPL in one line. If you decides to use the package(s), Playground can even generate a starter `composer.json` file for you.

## Installation

Playground is available for download as a Phar archive. Simply [download `playground.phar`](https://github.com/jacobbudin/playground/releases/download/v1.0.0/playground.phar) and run it:

    $ curl -L -O https://github.com/jacobbudin/playground/releases/download/v1.0.0/playground.phar
    $ chmod +x playground.phar
    $ ./playground.phar

## Usage

From the command line, run like so:

	$ playground monolog/monolog

You can also specify specific versions of packages:

	$ playground monolog/monolog:1.6.*

And specify multiple packages:

	$ playground monolog/monolog jasonlewis/expressive-date
	
Inside every REPL is a local variable `$playground`, which is a `Playground` instance with various helper methods. For example:

	boris> $playground->saveComposer('composer.json'); // Saves composer.json file
	boris> $playground->getPackages(); // Returns associative array of packages loaded, with version numbers

## Source

You can use Playground as an independent package via Packagist.org. You can rebuild the Phar archive using [Box](http://box-project.org/):

    $ box build

## Thanks

[Chris Corbyn](https://github.com/d11wtq) (Boris), [Nils Adermann](http://www.naderman.de) & [Jordi Boggiano](http://seld.be) (Composer), and the PHP Team

## Author

[Jacob Budin](http://www.jacobbudin.com)

## License

BSD 3-Clause (Revised) License
