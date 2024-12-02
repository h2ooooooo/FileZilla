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

<details>
<summary>Expected Output</summary>

```
Custom SiteManager loaded successfully.
```

</details>