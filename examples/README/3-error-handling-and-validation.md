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

<details>
<summary>Expected Output</summary>

```
Error retrieving server: Could not find child at path "Protocols/invalid-server"
```

</details>
