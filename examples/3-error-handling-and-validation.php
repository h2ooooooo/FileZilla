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
