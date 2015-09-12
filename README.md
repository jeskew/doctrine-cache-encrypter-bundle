# Doctrine Cache Encrypter Bundle

[![Build Status](https://travis-ci.org/jeskew/doctrine-cache-encrypter-bundle.svg?branch=master)](https://travis-ci.org/jeskew/doctrine-cache-encrypter-bundle)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/jeskew/doctrine-cache-encrypter-bundle/?branch=master)
[![Apache 2 License](https://img.shields.io/packagist/l/jeskew/doctrine-cache-encrypter-bundle.svg?style=flat)](https://www.apache.org/licenses/LICENSE-2.0.html)
[![Total Downloads](https://img.shields.io/packagist/dt/jeskew/doctrine-cache-encrypter-bundle.svg?style=flat)](https://packagist.org/packages/jeskew/doctrine-cache-encrypter)
[![Author](http://img.shields.io/badge/author-@jreskew-blue.svg?style=flat-square)](https://twitter.com/jreskew)

The purpose of this bundle is to provide a thin wrapper around the [Doctrine
Cache Encrypter](https://github.com/jeskew/doctrine-cache-encrypter) to make it
work more seamlessly with the Symfony framework. This bundle will allow you to
create an encrypted cache service from a regular Doctrine Cache service by 
tagging it.

## Usage

Any service that is an instance of `Doctrine\Common\Cache\Cache` can be tagged
with the `cache.encrypted` tag to create an additional service that will encrypt
values either with a password or a public/private key pair. 

Sample configuration can be found in the `tests/fixtures` folder for [YAML](https://github.com/jeskew/doctrine-cache-encrypter-bundle/blob/master/tests/fixtures/config.yml), [PHP](https://github.com/jeskew/doctrine-cache-encrypter-bundle/blob/master/tests/fixtures/config.php), and [XML](https://github.com/jeskew/doctrine-cache-encrypter-bundle/blob/master/tests/fixtures/config.xml).
