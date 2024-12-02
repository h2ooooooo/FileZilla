<?php

namespace jalsoedesign\filezilla;

class InternalFixtureGenerator {
	private string $saveFolder;

	public function __construct() {
		$this->saveFolder = realpath(__DIR__ . '/../tests/fixtures') . DIRECTORY_SEPARATOR . 'generated';
	}

	private function ensureGeneratedFolderIsCreated(): void {
		if (is_dir($this->saveFolder)) {
			return;
		}

		mkdir($this->saveFolder, 0777, true);
	}

	/**
	 * @return string
	 * @throws \DOMException
	 */
	public function generateMassiveSiteManager(): string {
		$this->ensureGeneratedFolderIsCreated();

		$targetFile = $this->saveFolder . '/sitemanager.massive.xml';

		if (file_exists($targetFile)) {
			// Already generated
			return $targetFile;
		}

		printf('Generating massive sitemanager file for testing at %s' . PHP_EOL, $targetFile);

		// Configuration for the structure
		$maxDepth = 6; // Maximum folder depth
		$foldersPerLevel = 5; // Number of folders at each level
		$serversPerFolder = 5; // Number of servers per folder

		// Initial XML structure
		$xml = new \DOMDocument('1.0', 'UTF-8');

		$filezilla = $xml->createElement('FileZilla3');
		$filezilla->setAttribute('version', '3.64.0');
		$filezilla->setAttribute('platform', 'windows');

		$serversRoot = $xml->createElement('Servers');
		$filezilla->appendChild($serversRoot);

		// Generate the hierarchical structure
		$this->generateFoldersAndServers($xml, $serversRoot, '', $maxDepth, $foldersPerLevel, $serversPerFolder);

		$xml->appendChild($filezilla);
		$xml->save($targetFile);

		clearstatcache();

		$targetFileSize = filesize($targetFile);
		$formattedSize = $this->formatFileSize($targetFileSize);

		printf('Saved to %s (%s)' . PHP_EOL, $targetFile, $formattedSize);

		return $targetFile;
	}

	/**
	 * @param \DOMDocument $xml
	 * @param \DOMNode     $parent
	 * @param string       $parentPath
	 * @param int          $depth
	 * @param int          $foldersPerLevel
	 * @param int          $serversPerFolder
	 *
	 * @return void
	 * @throws \DOMException
	 */
	private function generateFoldersAndServers(
		\DOMDocument $xml,
		\DOMNode $parent,
		string $parentPath,
		int $depth,
		int $foldersPerLevel,
		int $serversPerFolder
	): void {
		if ($depth <= 0) {
			return;
		}

		for ($i = 0; $i < $foldersPerLevel; $i++) {
			$folderName = sprintf('%sFolder-%d', $parentPath, $i);
			$folder = $xml->createElement('Folder');
			$folder->setAttribute('expanded', '1');
			$folder->appendChild($xml->createTextNode($folderName));

			// Add servers to this folder
			for ($j = 0; $j < $serversPerFolder; $j++) {
				$serverName = sprintf('%sServer-%d', $folderName, $j);
				$server = $xml->createElement('Server');

				// Add server details
				$server->appendChild($xml->createElement('Host', sprintf('host-%s.example.com', $serverName)));
				$server->appendChild($xml->createElement('Port', '21'));
				$server->appendChild($xml->createElement('Protocol', '0'));
				$server->appendChild($xml->createElement('User', 'user'));
				$server->appendChild($xml->createElement('Pass', base64_encode('pass')));
				$server->appendChild($xml->createElement('Logontype', '1'));
				$server->appendChild($xml->createElement('Name', $serverName));

				$folder->appendChild($server);
			}

			// Recursively add subfolders
			$this->generateFoldersAndServers($xml, $folder, $folderName . '/', $depth - 1, $foldersPerLevel, $serversPerFolder);

			$parent->appendChild($folder);
		}
	}

	private function formatFileSize(int $size): string {
		$units = ['B', 'KB', 'MB', 'GB', 'TB'];
		$unitIndex = 0;

		while ($size >= 1024 && $unitIndex < count($units) - 1) {
			$size /= 1024;
			$unitIndex++;
		}

		return sprintf('%.2f %s', $size, $units[$unitIndex]);
	}
}
