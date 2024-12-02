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
