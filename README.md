# Pluf

[![Build Status](https://travis-ci.com/pluf/core.svg?branch=master)](https://travis-ci.com/pluf/core)
[![codecov](https://codecov.io/gh/pluf/core/branch/master/graph/badge.svg)](https://codecov.io/gh/pluf/core)
[![Coverage Status](https://coveralls.io/repos/github/pluf/core/badge.svg)](https://coveralls.io/github/pluf/core)
[![Maintainability](https://api.codeclimate.com/v1/badges/9e1457dbf2f0bcc8b953/maintainability)](https://codeclimate.com/github/pluf/core/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/9e1457dbf2f0bcc8b953/test_coverage)](https://codeclimate.com/github/pluf/core/test_coverage)

Pluf is a light, reliable and small PHP application framework to develop REST-full Multi/Single-tenant applications. This is the core of the Pluf framework which contains core concepts of Pluf Framework.


## Installation

To use the Pluf library in your project, simply add a dependency on pluf/core
to your project's `composer.json` file. Here is a minimal example of a `composer.json`
file that just defines a dependency on UPDATE_NAME 1.x:

```json
{
    "require": {
        "pluf/core": "~6.0"
    }
}
```

## Development

If you would like to contribute to Pluf, please read the README and CONTRIBUTING documents.

The most important guidelines are described as follows:

>All code contributions - including those of people having commit access - must go through a pull request and approved by a core developer before being merged. This is to ensure proper review of all the code.

Fork the project, create a feature branch, and send us a pull request.

To ensure a consistent code base, you should make sure the code follows the PSR-2 Coding Standards.

### Running Composer

To pull in the project dependencies via Composer, run:

    composer install

### Running the CI checks

To run all CI checks, which includes PHPUnit tests, PHPCS style checks and coverage tag validation, run:

    ./vendor/bin/phpcs -p -s src
    
### Running the tests

To run just the PHPUnit tests run

    ./vendor/bin/phpunit

