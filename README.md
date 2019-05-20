# jalsoedesign/filezilla

FileZilla `sitemanager.xml` parser used to get details for specific servers and/or connect to them using [thephpleague/flysystem](https://github.com/thephpleague/flysystem).

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
