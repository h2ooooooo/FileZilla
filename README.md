# FileZilla Configuration Parser

[![Latest Stable Version](https://img.shields.io/packagist/v/jalsoedesign/filezilla.svg)](https://packagist.org/packages/jalsoedesign/filezilla)
[![License](https://img.shields.io/packagist/l/jalsoedesign/filezilla.svg)](https://github.com/h2ooooooo/FileZilla/blob/main/LICENSE)

A powerful PHP library for parsing and working with FileZilla `sitemanager.xml` files. It allows you to extract server
details, manage configurations, and integrate FileZilla data into your PHP applications.

---

## Features

- 🎉 **Parse FileZilla `sitemanager.xml` files**: Extract and work with server folders as well as individual server
  details.
- 🔒 **Stop using credentials in your code**: If FileZilla already provides this unencrypted, and you're already using
  it, why not just read it from there?
- ✨ **Supports all FileZilla server attributes**: Including host, port, protocol, and more.
- 🔧 **Flexible API**: Query specific server details or all servers in a folder.
- 📜 **Enum-Based Design**: Strongly typed enums for protocols, logon types, and more.
- ⚙️ **Works out of the box**: The library defaults to the `sitemanager.xml` of your system. No need to specify a path.
- 🚀 **PHP 8.0+ Compatible**: Built with modern PHP practices and strict typing.

---

## Installation

Install the package via Composer:

```bash
composer require jalsoedesign/filezilla
```

---

## Usage

### Basic usage

Load the default sitemanager.xml from your system (using `fromSystem()`) and iterate through all servers and print their
name.

```php
use jalsoedesign\filezilla\SiteManager;

$siteManager = SiteManager::fromSystem();

$servers = $siteManager->getServers();

foreach ($servers as $server) {
    echo 'Full server path: ' . $server->getPath() . PHP_EOL;
}
```

### Many other examples

See the [**examples folder**](./examples).

### Structure of sitemanager.xml

The test fixture [`tests/fixtures/sitemanager.xml`](tests/fixtures/sitemanager.xml) provides an example of all possible
options.

<details>
<summary>Folder structure of sitemanager.xml</summary>

```
📁 My Sites
│   📁 Protocols
│   ├── ☁️ azure-blob-storage.example.com
│   ├── ☁️ azure-file-storage.example.com
│   ├── ☁️ backblaze-b2.example.com
│   ├── ☁️ box.example.com
│   ├── ☁️ dropbox.example.com
│   ├── 🌐 ftp.example.com - explicit ftp over tls
│   ├── 🌐 ftp.example.com - explicit ftp over tls if available
│   ├── 🌐 ftp.example.com - implicit ftp over tls
│   ├── 🌐 ftp.example.com - plain ftp
│   ├── 🌐 google-cloudstorage.example.com
│   ├── ☁️ google-drive.example.com
│   ├── ☁️ onedrive.example.com
│   ├── ☁️ openstack-swift.example.com
│   ├── ☁️ rackspace-cloud-storage.example.com
│   ├── ☁️ s3.example.com
│   ├── 🌐 sftp.example.com
│   ├── 🌐 webdav.example.com - http
│   ├── 🌐 webdav.example.com - https
│   📁 Settings
│   │   📁 Advanced
│   │   ├── 🌐 _default
│   │   │   📁 Adjusted server time
│   │   │   ├── 🌐 minus 1 hour
│   │   │   ├── 🌐 plus 1 hour
│   │   │   ├── 🌐 plus 30 minutes
│   │   │   📁 Bypass proxy
│   │   │   ├── 🌐 disabled
│   │   │   ├── 🌐 enabled
│   │   │   📁 Directory comparison
│   │   │   ├── 🌐 disabled
│   │   │   ├── 🌐 enabled
│   │   │   📁 Local directory
│   │   │   ├── 🌐 local directory - Unix
│   │   │   ├── 🌐 local directory - Windows
│   │   │   📁 Remote directory
│   │   │   ├── 🌐 remote directory - Unix
│   │   │   ├── 🌐 remote directory - Windows
│   │   │   📁 Server type
│   │   │   ├── 🌐 cygwin
│   │   │   ├── 🌐 default
│   │   │   ├── 🌐 dos with back slashes
│   │   │   ├── 🌐 dos with forward slashes
│   │   │   ├── 🌐 doslike
│   │   │   ├── 🌐 hp nonstop
│   │   │   ├── 🌐 mvs
│   │   │   ├── 🌐 unix
│   │   │   ├── 🌐 vms
│   │   │   ├── 🌐 vxworks
│   │   │   ├── 🌐 zvm
│   │   │   📁 Synchronized browsing
│   │   │   ├── 🌐 disabled
│   │   │   ├── 🌐 enabled
│   │   📁 Charset
│   │   ├── 🌐 autodetect
│   │   ├── 🌐 custom charset - utf16
│   │   ├── 🌐 force utf8
│   │   📁 General
│   │   │   📁 Background color
│   │   │   ├── 🌐 none
│   │   │   ├── 🌐 red
│   │   │   📁 Comments
│   │   │   ├── 🌐 no comments
│   │   │   ├── 🌐 with comments
│   │   │   📁 Custom port
│   │   │   ├── 🌐 custom port 8080
│   │   │   ├── 🌐 default port
│   │   📁 Transfer Settings
│   │   ├── 🌐 transfer mode - 6 limit
│   │   ├── 🌐 transfer mode - Active
│   │   ├── 🌐 transfer mode - Default
│   │   ├── 🌐 transfer mode - No limit
│   │   ├── 🌐 transfer mode - Passive
│   📁 Various
│   ├── 🌐 Special !"#¤%&()=<>
│   ├── 🌐 Utf8 💞💢💫
```

</details>

---

## Documentation

### API Reference

#### Server

The `Server` class represents a single server configuration from the FileZilla `sitemanager.xml` file. It provides
access to all attributes of a server, including its host, protocol, directories, and more.

| Method                                                                   | Returns | Description                                                             |
|--------------------------------------------------------------------------|---------|-------------------------------------------------------------------------|
| `getHost()`                                                              | string  | Get the server's hostname or IP address.                                |
| `getPort()`                                                              | int     | Get the server's port number.                                           |
| `getProtocol()`                                                          | int     | Get the server's protocol ([`ServerProtocol enum`](#Enums)).            |
| `getLogonType()`                                                         | int     | Get the server's logon type ([`LogonType enum`](#Enums)).               |
| `getUser()`                                                              | ?string | Get the username for authentication (nullable).                         |
| `getPassword()`                                                          | ?string | Get the password for authentication (nullable).                         |
| `getKeyFile()`                                                           | ?string | Get the path to the server's private key file (nullable).               |
| `getTimezoneOffset()`                                                    | ?int    | Get the server's timezone offset (nullable).                            |
| `getPassiveMode()`                                                       | ?string | Get the server's passive mode ([`PassiveMode enum`](#Enums), nullable). |
| `getMaximumMultipleConnections()`                                        | ?int    | Get the maximum concurrent connections (nullable).                      |
| `getEncodingType()`                                                      | ?string | Get the encoding type ([`CharsetEncoding enum`](#Enums), nullable).     |
| `getCustomEncoding()`                                                    | ?string | Get the custom encoding if the encoding type is custom.                 |
| `getBypassProxy()`                                                       | ?bool   | Check whether the server bypasses the proxy (nullable).                 |
| `getType()`                                                              | int     | Get the server's type ([`ServerType enum`](#Enums)).                    |
| `getName()`                                                              | string  | Get the name of the server as it appears in FileZilla.                  |
| `getComments()`                                                          | ?string | Get the server's comments (nullable).                                   |
| `getLocalDirectory()`                                                    | ?string | Get the initial local directory for the server (nullable).              |
| `getRemoteDirectory(string $default = null, bool $useRootPrefix = true)` | ?string | Parse and return the server's remote directory (nullable).              |
| `getSynchronizedBrowsing()`                                              | ?bool   | Check whether synchronized browsing is enabled (nullable).              |
| `getDirectoryComparison()`                                               | ?bool   | Check whether directory comparison is enabled (nullable).               |
| `getColour()`                                                            | int     | Get the background color.                                               |
| `getColor()`                                                             | ?string | Alias for `getColour()` (nullable).                                     |

#### Folder

The `Folder` class represents a collection of `Server` and/or `Folder` objects, allowing for recursive structures of
servers and folders.

| Method                                                | Returns           | Description                                                                 |
|-------------------------------------------------------|-------------------|-----------------------------------------------------------------------------|
| `getName()`                                           | string            | Get the name of the folder.                                                 |
| `getChildren(bool $recursive, ?string $filter)`       | Server[]/Folder[] | Get all children in the folder, optionally recursive and filtered by type.  |
| `countServers(bool $recursive)`                       | int               | Count the number of servers in the folder, optionally including subfolders. |
| `countFolders(bool $recursive)`                       | int               | Count the number of folders in the folder, optionally including subfolders. |
| `getServers(bool $recursive)`                         | Server[]          | Get all servers in the folder, optionally including subfolders.             |
| `getFolders(bool $recursive)`                         | Folder[]          | Get all folders in the folder, optionally including subfolders.             |
| `getServer(string $serverPath)`                       | Server            | Get a specific server by its path.                                          |
| `getFolder(string $folderPath)`                       | Folder            | Get a specific folder by its path.                                          |
| `getChildPath(string $childName, ?string $childPath)` | string            | Construct a full path for a child within the folder.                        |

#### SiteManager

The `SiteManager` class extends `Folder`, inheriting all of its functionality, and represents the root structure of
FileZilla's server and folder configuration as defined in the `sitemanager.xml` file. In addition to managing nested
folders and servers, it provides access to metadata about the configuration file itself.

| Method                      | Returns     | Description                                                              |
|-----------------------------|-------------|--------------------------------------------------------------------------|
| `SiteManager::fromSystem()` | SiteManager | Create a `SiteManager` instance from the system's default configuration. |
| `getVersion()`              | string      | Get the version of the `sitemanager.xml` file.                           |
| `getPlatform()`             | string      | Get the platform specified in the `sitemanager.xml` file.                |

### Enums

| Enum Class        | Description                                                      |
|-------------------|------------------------------------------------------------------|
| `CharsetEncoding` | Charset encodings (Auto, UTF-8 or Custom)                        |
| `Colour`          | Background colours (e.g. Red, Green, Blue).                      |
| `LogonType`       | User authentication types (e.g., Normal, Anonymous).             |
| `PassiveType`     | The PasvMode setting (MODE_DEFAULT, MODE_ACTIVE or MODE_PASSIVE) |
| `ServerProtocol`  | Server protocols like FTP, SFTP, FTPS, etc.                      |
| `ServerType`      | Server types like Unix, DOS, etc.                                |

All enum classes have `Enum::toCamelCase($value)` as well as `Enum::getConstantName($value)` methods.

## Filezilla config parsing

| FileZilla Field            | Attribute                  | Casting | Note                                            |
|----------------------------|----------------------------|---------|-------------------------------------------------|
| Host                       | host                       | string  | Required                                        |
| Port                       | port                       | int     |                                                 |
| Protocol                   | protocol                   | int     |                                                 |
| Type                       | type                       | int     |                                                 |
| User                       | user                       | string  | Required                                        |
| Pass                       | password                   | string  | Required. Base64 encoded if `encoding="base64"` |
| Keyfile                    | keyFile                    | string  | Required                                        |
| Colour                     | colour                     | int     |                                                 |
| Logontype                  | logonType                  | int     |                                                 |
| TimezoneOffset             | timezoneOffset             | int     |                                                 |
| PasvMode                   | passiveMode                | string  | Required                                        |
| MaximumMultipleConnections | maximumMultipleConnections | int     |                                                 |
| EncodingType               | encodingType               | string  | Required                                        |
| BypassProxy                | bypassProxy                | bool    |                                                 |
| Name                       | name                       | string  | Required                                        |
| Comments                   | comments                   | string  | Required                                        |
| LocalDir                   | localDirectory             | string  | Required                                        |
| RemoteDir                  | remoteDirectory            | string  | Required                                        |
| SyncBrowsing               | synchronizedBrowsing       | bool    |                                                 |
| DirectoryComparison        | directoryComparison        | bool    |                                                 |
| CustomEncoding             | customEncoding             | string  | Required                                        |

---

## Contributing

Contributions are welcome! Please submit a pull request or open an issue if you find a bug or have an idea for an
enhancement.

---

## License

This library is open-sourced under the [MIT license](https://github.com/jalsoedesign/filezilla/blob/main/LICENSE).

---

## Credits

- **Author**: [JalsoeDesign](https://www.jalsoedesign.net)

---

## Support

If you find this library useful, feel free to ⭐ the repository or share your feedback!
