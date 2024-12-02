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
