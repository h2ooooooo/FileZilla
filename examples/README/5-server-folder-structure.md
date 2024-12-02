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
