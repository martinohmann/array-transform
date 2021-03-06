array-transform
===============
[![Latest Stable Version](https://poser.pugx.org/mohmann/array-transform/v/stable)](https://packagist.org/packages/mohmann/array-transform)
[![Build Status](https://travis-ci.org/martinohmann/array-transform.svg?branch=master)](https://travis-ci.org/martinohmann/array-transform)
[![Coverage Status](https://coveralls.io/repos/github/martinohmann/array-transform/badge.svg)](https://coveralls.io/github/martinohmann/array-transform)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](https://opensource.org/licenses/MIT)
[![PHP 7.1+](https://img.shields.io/badge/php-7.1%2B-blue.svg)](https://packagist.org/packages/mohmann/array-transform)

Transforms raw arrays from a given source mapping to a target mapping and back again.

This package requires **PHP 7.1 or higher**.

Quick example. Given the following YAML mapping:
```
---
mapping:
  foo[int]:
    inverse: bar.baz[float]
    formula:
      direct: bar.baz / 1000
      inverse: foo * 1000
```

A php array that looks like this (1):
```
[
    'bar' [
        'baz' => 1000.0
    ],
];
```

... transforms to this (2) (`transform`):
```
[
    'foo' => 1;
];
```

... and can be transformed back to its original form (`reverseTransform`)*
```
[
    'bar' [
        'baz' => 1000.0
    ],
];
```

Simple formulas and type definitions are some of the "advanced" features of array-transform. Please refer to the documentation for more details.

*There are weird mapping cases where a `reverseTransform` is not possible. Refer to the documentation for details.

Installation
------------

Via composer:

```
composer require mohmann/array-transform
```

Usage
-----

WIP. Check the `examples/` directory for now.

Documentation
-------------

WIP. Check the `doc/` directory for a [mapping example](doc/full-mapping.yaml).

Development / Testing
---------------------

Refer to the `Makefile` for helpful commands, e.g.:

```
make stan
make test
make inf
```

License
-------

array-transform is released under the MIT License. See the bundled LICENSE file for details.
