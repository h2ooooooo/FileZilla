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
