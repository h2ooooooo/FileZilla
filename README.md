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

## Note

In order to support implicit FTPS this package uses (``)[]. The problem with this package is that the support for explicit FTPS is faulty seeing as it prepends `ftps://` to the hostname, resulting in the initial connection to be SSL (explicit FTP should connect without SSL and enter the state instead).

To fix this there's a patch applied automatically by composer located here:

[`patches/CurlFtpAdapter_explicit_ftp.patch`](./patches/CurlFtpAdapter_explicit_ftp.patch)