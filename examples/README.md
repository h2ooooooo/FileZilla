# Basic Usage

This example demonstrates how to load a `sitemanager.xml` file and iterate through all the servers, printing their
details.

```php
<?php

use jalsoedesign\filezilla\enum\LogonType;
use jalsoedesign\filezilla\enum\ServerProtocol;
use jalsoedesign\filezilla\SiteManager;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

// Load the SiteManager instance from a custom FileZilla configuration
$siteManager = new SiteManager(__DIR__ . '/../tests/fixtures/sitemanager.xml');

// Get all servers
$server = $siteManager->getServer('Settings/Advanced/_default');

printf('Server Path: %s' . PHP_EOL, $server->getPath());
printf('Server Name: %s' . PHP_EOL, $server->getName());
printf('Synchronized Browsing: %s' . PHP_EOL, $server->getSynchronizedBrowsing() ? 'Enabled' : 'Disabled');
printf('Directory Comparison: %s' . PHP_EOL, $server->getDirectoryComparison() ? 'Enabled' : 'Disabled');
printf('Host: %s' . PHP_EOL, $server->getHost());
printf('Port: %s' . PHP_EOL, $server->getPort());
printf('Protocol: %s (%d)' . PHP_EOL, ServerProtocol::getConstantName($server->getProtocol()), $server->getProtocol());
printf('Logon Type: %s (%d)' . PHP_EOL, LogonType::getConstantName($server->getLogonType()), $server->getLogonType());
printf('User: %s' . PHP_EOL, $server->getUser());
printf('Password: %s' . PHP_EOL, $server->getPassword());
```

**Example**: [`1-get-single-server-properties.php`](../1-get-single-server-properties.php)

<details>
<summary>Expected Output</summary>

```
Server Path: Settings/Advanced/_default
Server Name: _default
Synchronized Browsing: Disabled
Directory Comparison: Disabled
Host: ftp.example.com
Port: 21
Protocol: FTP (0)
Logon Type: NORMAL (1)
User: user
Password: pass
```

</details>

-------

# Custom SiteManager Path

This example demonstrates how to load a `sitemanager.xml` file from a custom path.

```php
<?php

use jalsoedesign\filezilla\SiteManager;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

// Specify the path to the custom sitemanager.xml file
$customPath = '/path/to/custom/sitemanager.xml';
$siteManager = new SiteManager($customPath);

echo 'Custom SiteManager loaded successfully.';
```

**Example**: [`2-custom-sitemanager-path.php`](../2-custom-sitemanager-path.php)

<details>
<summary>Expected Output</summary>

```
Custom SiteManager loaded successfully.
```

</details>


--------

# Error Handling and Validation

This example demonstrates how to handle errors when loading the `sitemanager.xml` file or retrieving servers.

```php
<?php

use jalsoedesign\filezilla\SiteManager;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

try {
	$siteManager = new SiteManager(__DIR__ . '/../tests/fixtures/sitemanager.xml');
} catch (Exception $e) {
	printf('Error loading SiteManager: %s', $e->getMessage());

	exit;
}

try {
	$server = $siteManager->getServer('Protocols/invalid-server');
} catch (Exception $e) {
	printf('Error retrieving server: %s', $e->getMessage());

	exit;
}
```

**Example**: [`3-error-handling-and-validation.php`](../3-error-handling-and-validation.php)

<details>
<summary>Expected Output</summary>

```
Error retrieving server: Could not find child at path "Protocols/invalid-server"
```

</details>


--------

# Query Servers by Folder

This example demonstrates how to query servers from specific folders in the `sitemanager.xml` file.

```php
<?php

use jalsoedesign\filezilla\SiteManager;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

$siteManager = new SiteManager(__DIR__ . '/../tests/fixtures/sitemanager.xml');

// Get all servers in the 'Protocols' folder
$protocolsFolder = $siteManager->getFolder('Settings/Advanced');

printf('Protocols folder path: %s' . PHP_EOL, $protocolsFolder->getPath());

echo PHP_EOL;

$servers = $protocolsFolder->getServers(true);

foreach ($servers as $server) {
	printf('Server found at path: %s' . PHP_EOL, $server->getPath());
}

echo PHP_EOL;

$folders = $protocolsFolder->getFolders(true);

foreach ($folders as $folder) {
	printf('Folder found at path: %s' . PHP_EOL, $folder->getPath());
}
```

**Example**: [`4-iterate-children.php`](../4-iterate-children.php)

<details>
<summary>Expected Output</summary>

```
Protocols folder path: Settings/Advanced

Server found at path: Settings/Advanced/_default
Server found at path: Settings/Advanced/Adjusted server time/minus 1 hour
Server found at path: Settings/Advanced/Adjusted server time/plus 1 hour
Server found at path: Settings/Advanced/Adjusted server time/plus 30 minutes
Server found at path: Settings/Advanced/Bypass proxy/disabled
Server found at path: Settings/Advanced/Bypass proxy/enabled
Server found at path: Settings/Advanced/Directory comparison/disabled
Server found at path: Settings/Advanced/Directory comparison/enabled
Server found at path: Settings/Advanced/Local directory/local directory - Unix
Server found at path: Settings/Advanced/Local directory/local directory - Windows
Server found at path: Settings/Advanced/Remote directory/remote directory - Unix
Server found at path: Settings/Advanced/Remote directory/remote directory - Windows
Server found at path: Settings/Advanced/Server type/cygwin
Server found at path: Settings/Advanced/Server type/default
Server found at path: Settings/Advanced/Server type/dos with back slashes
Server found at path: Settings/Advanced/Server type/dos with forward slashes
Server found at path: Settings/Advanced/Server type/doslike
Server found at path: Settings/Advanced/Server type/hp nonstop
Server found at path: Settings/Advanced/Server type/mvs
Server found at path: Settings/Advanced/Server type/unix
Server found at path: Settings/Advanced/Server type/vms
Server found at path: Settings/Advanced/Server type/vxworks
Server found at path: Settings/Advanced/Server type/zvm
Server found at path: Settings/Advanced/Synchronized browsing/disabled
Server found at path: Settings/Advanced/Synchronized browsing/enabled

Folder found at path: Settings/Advanced/Adjusted server time
Folder found at path: Settings/Advanced/Bypass proxy
Folder found at path: Settings/Advanced/Directory comparison
Folder found at path: Settings/Advanced/Local directory
Folder found at path: Settings/Advanced/Remote directory
Folder found at path: Settings/Advanced/Server type
Folder found at path: Settings/Advanced/Synchronized browsing
```

</details>


--------

# Server Folder Structure

This example demonstrates how to display the folder structure of all servers in the `sitemanager.xml` file.

```php
<?php

use jalsoedesign\filezilla\enum\ServerProtocol;
use jalsoedesign\filezilla\enum\ServerType;
use jalsoedesign\filezilla\Server;
use jalsoedesign\filezilla\Folder;
use jalsoedesign\filezilla\SiteManager;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

$siteManager = new SiteManager(__DIR__ . '/../tests/fixtures/sitemanager.xml');

$servers = $siteManager->getServers();
$ascii = convertToAscii($siteManager);

echo $ascii;

function convertToAscii(Folder $folder, $prefix = '') {
	$folderName = $folder->getName();

	if (empty($folderName)) {
		$folderName = 'My Sites';
	}

	$output = $prefix . '📁 ' . $folderName . PHP_EOL;
	$children = $folder->getChildren();

	$childCount = count($children);
	foreach ($children as $index => $child) {
		$isLast = ($index === $childCount - 1);
		$childPrefix = $prefix . ($isLast ? '└── ' : '├── ');

		if ($child instanceof Folder) {
			// Recursively handle Folder
			$output .= convertToAscii($child, $prefix . ($isLast ? '    ' : '│   '));
		} else if ($child instanceof Server) {
			$cloudTypes = [
				ServerProtocol::AZURE_BLOB,
				ServerProtocol::AZURE_FILE,
				ServerProtocol::B2,
				ServerProtocol::BOX,
				ServerProtocol::DROPBOX,
				ServerProtocol::GOOGLE_DRIVE,
				ServerProtocol::ONEDRIVE,
				ServerProtocol::SWIFT,
				ServerProtocol::RACKSPACE,
				ServerProtocol::S3,
			];

			$icon = in_array($child->getProtocol(), $cloudTypes) ? '☁️' : '🌐';

			// Handle Server
			$output .= $childPrefix . $icon . ' ' . $child->getName() . PHP_EOL;
		}
	}

	return $output;
}
```

**Example**: [`5-server-folder-structure.php`](../5-server-folder-structure.php)

<details>
<summary>Expected Output</summary>

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


--------

# Working with Enums

This example demonstrates how to use enums for protocols, logon types, and server types.

```php
<?php

use jalsoedesign\filezilla\enum\CharsetEncoding;
use jalsoedesign\filezilla\enum\LogonType;
use jalsoedesign\filezilla\enum\PassiveMode;
use jalsoedesign\filezilla\enum\ServerType;
use jalsoedesign\filezilla\SiteManager;
use jalsoedesign\filezilla\enum\ServerProtocol;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

$siteManager = new SiteManager(__DIR__ . '/../tests/fixtures/sitemanager.xml');

$server = $siteManager->getServer('Settings/Advanced/_default');

printf('Server Name: %s' . PHP_EOL, $server->getName());
printConstantValue('Encoding type', $server->getEncodingType(), CharsetEncoding::class);
printConstantValue('Logon type', $server->getLogonType(), LogonType::class);
printConstantValue('PasvMode', $server->getPassiveMode(), PassiveMode::class);
printConstantValue('Protocol', $server->getProtocol(), ServerProtocol::class);
printConstantValue('Type', $server->getType(), ServerType::class);

function printConstantValue($constantNameText, $constantValue, $constantClass) {
	// eg. CharsetEncoding::getConstantName($constantValue)
	$constantName = call_user_func($constantClass . '::getConstantName', $constantValue);

	// eg. CharsetEncoding::toCamelCase($constantValue)
	$camelCase = call_user_func($constantClass . '::toCamelCase', $constantValue);

	printf('%s: %s (%s / %s)' . PHP_EOL, $constantNameText, $constantValue, $constantName, $camelCase);
}
```

**Example**: [`6-working-with-enums.php`](../6-working-with-enums.php)

<details>
<summary>Expected Output</summary>

```
Server Name: _default
Encoding type: Auto (ENCODING_AUTO / encodingAuto)
Logon type: 1 (NORMAL / normal)
PasvMode: MODE_DEFAULT (MODE_DEFAULT / modeDefault)
Protocol: 0 (FTP / ftp)
Type: 0 (_DEFAULT / autoDetect)
```

</details>

