# jalsoedesign/filezilla

[![Author](https://img.shields.io/badge/author-jalsoedesign-blue.svg?style=flat-square)](https://jalsoedesign.net)
[![Software License](https://img.shields.io/github/license/h2ooooooo/FileZilla.svg?style=flat-square)](LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/jalsoedesign/filezilla.svg?style=flat-square)](https://packagist.org/packages/jalsoedesign/filezilla)
[![Total Downloads](https://img.shields.io/packagist/dt/jalsoedesign/filezilla.svg?style=flat-square)](https://packagist.org/packages/jalsoedesign/filezilla)
[![Github Issues](https://img.shields.io/github/issues/h2ooooooo/FileZilla.svg?style=flat-square)](https://github.com/jalsoedesign/filezilla/issues)

FileZilla `sitemanager.xml` parser used to get details for specific servers and/or connect to them using [`thephpleague/flysystem`](https://github.com/thephpleague/flysystem).

## Installation

You can install the library using [`composer`](https://getcomposer.org/):

    composer require jalsoedesign/filezilla

After this simply require `./vendor/autoload.php` and you can use the classes.
## Examples

### CLImax example
 
The example can be found at [`tests/test-climax.php`](./tests/test-climax.php)

This example will:

 - Open the `sitemanager.xml` fixture file
 - `[FTP]` Connect to `test.rebex.net:21` server and list files in the root folder 
 - `[FTPS]` Connect to `test.rebex.net:990` server and list files in the root folder 
 - `[FTPEX]` Connect to `test.rebex.net:21` server and list files in the root folder 
 - `[SFTP]` Connect to `test.rebex.net:22` server and list files in the root folder 
 
It can be run with `php ./tests/test-climax.php`.

### Simple example
 
The example can be found at [`tests/test-simple.php`](./tests/test-simple.php)

This example will:

 - Open the `sitemanager.xml` fixture file
 - Connect to the test.rebex.net server using insecure FTP
 - List the files in the root folder

It can be run with `php ./tests/test-simple.php`.

## Dependencies

The following composer libraries are used as dependencies:

| Library                                                                                                       | Version      | Reason                                                                                                  | 
|---------------------------------------------------------------------------------------------------------------|--------------|---------------------------------------------------------------------------------------------------------|
| [`ext-dom`](https://www.php.net/manual/en/book.dom.php)                                                       | `*`          | The [`DOM`](https://www.php.net/manual/en/book.dom.php) extension is required to read `sitemanager.xml` |
| [`league/flysystem`](https://packagist.org/packages/league/flysystem)                                         | `^1.0`       | Flysystem is used to be able to call `$server->getFilesystem()`                                         |
| [`league/flysystem-sftp`](https://packagist.org/packages/league/flysystem-sftp)                               | `^1.0`       | SFTP support for Flysystem                                                                              |
| [`chinlung/flysystem-curlftp`](https://packagist.org/packages/chinlung/flysystem-curlftp)                     | `^2.0`       | Added implicit FTPS support for Flysystem                                                               |
| [`jalsoedesign/climax`](https://packagist.org/packages/jalsoedesign/climax)                                   | `master-dev` | CLImax support for test application                                                                     |