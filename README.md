# Logbook

A quick and simple platform for recording research events.

[![Build Status](https://img.shields.io/scrutinizer/build/g/mysociety/logbook.svg)](https://scrutinizer-ci.com/g/mysociety/logbook/)
[![Code Quality](https://img.shields.io/scrutinizer/g/mysociety/logbook.svg)](https://scrutinizer-ci.com/g/mysociety/logbook/)

## How does it work?

Check the [API documentation](http://docs.logbook.apiary.io/).

## Developing

### Requirements

* PHP 5.4.
* A PostgreSQL database.

### Configuration

Copy the `conf/conf.env-example` file to `conf/conf.env` and adjust parameters accordingly.

### Libraries

Libraries are handled using [Composer](https://getcomposer.org/). Do a `php composer.phar install`.

### Migrations

Migrations are handled using [Phinx](https://phinx.org/). To run them, `vendor/bin/phinx migrate -c conf/phinx.php` (after you've installed the Composer requirements).
