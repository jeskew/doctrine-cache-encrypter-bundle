# Doctrine Cache Encrypter Bundle

[![Build Status](https://travis-ci.org/jeskew/doctrine-cache-encrypter-bundle.svg?branch=master)](https://travis-ci.org/jeskew/doctrine-cache-encrypter-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/?branch=master)
[![Apache 2 License](https://img.shields.io/packagist/l/jsq/doctrine-cache-encrypter-bundle.svg?style=flat)](https://www.apache.org/licenses/LICENSE-2.0.html)
[![Total Downloads](https://img.shields.io/packagist/dt/jsq/doctrine-cache-encrypter-bundle.svg?style=flat)](https://packagist.org/packages/jeskew/doctrine-cache-encrypter-bundle)
[![Author](http://img.shields.io/badge/author-@jreskew-blue.svg?style=flat-square)](https://twitter.com/jreskew)

The purpose of this bundle is to provide a thin wrapper around the [Doctrine
Cache Encrypter](https://github.com/jeskew/doctrine-cache-encrypter) to make it
work more seamlessly with the Symfony framework. This bundle will allow you to
create an encrypted cache service from a regular Doctrine Cache service by 
tagging it.

## Usage

Any service that is an instance of `Doctrine\Common\Cache\Cache` can be tagged
with the `cache.encrypted` tag to create an additional service that will encrypt
values either with a password or against an array of public keys.

Sample configuration can be found in the `tests/fixtures` folder for [YAML](https://github.com/jeskew/doctrine-cache-encrypter-bundle/blob/master/tests/fixtures/config.yml), [PHP](https://github.com/jeskew/doctrine-cache-encrypter-bundle/blob/master/tests/fixtures/config.php), and [XML](https://github.com/jeskew/doctrine-cache-encrypter-bundle/blob/master/tests/fixtures/config.xml).

To use a service for any configuration value, use `@` followed by the service
name, such as `@a_service`. This syntax will be converted to a service during
container compilation. If you want to use a string literal that begins with `@`,
you will need to escape it by adding another `@` sign.

Please note that you will need to pass in an array of public keys, not just a
single one, as that encrypter is designed to work across multiple servers, each
with its own key pair. As Symfony DI tags can only be string values, you will
need to use a service (with `@service` notation) that returns an array.
