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
