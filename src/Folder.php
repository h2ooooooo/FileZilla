<?php

namespace jalsoedesign\filezilla;

use jalsoedesign\filezilla\enum\CharsetEncoding;
use jalsoedesign\filezilla\enum\LogonType;
use jalsoedesign\filezilla\enum\PassiveMode;
use jalsoedesign\filezilla\enum\ServerProtocol;
use jalsoedesign\filezilla\enum\ServerType;

/**
 * Class Server
 *
 * @package jalsoedesign\filezilla
 */
class Folder extends AbstractServerFolderChild
{
	/** @var string The server name */
	protected string $name;

	/** @var Server[]|Folder[] */
	protected array $children;

	/**
	 * Gets the required properties
	 *
	 * @return string[]
	 */
	protected function getRequiredProperties(): array {
		return ['name', 'children'];
	}

	/**
	 * Gets the full server path
	 *
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Gets the full server path
	 *
	 * @param bool    $recursive   Whether to return recursive folders too
	 * @param ?string $filterClass Whether to filter by a specific type (Server, Folder) - should be the full class
	 *
	 * @return string
	 */
	public function getChildren(bool $recursive = false, ?string $filterClass = null): array
	{
		return $this->__getChildren($this->children, $recursive, $filterClass);
	}

	/**
	 * Gets the server count
	 *
	 * @param bool $recursive Whether to return the count of single level servers (false) or all servers including in sub folders (true)
	 *
	 * @return int
	 */
	public function countServers(bool $recursive = false): int
	{
		$servers = $this->getServers($recursive);

		return count($servers);
	}

	/**
	 * Gets the folder count
	 *
	 * @param bool $recursive Whether to return the count of single level folders (false) or all folders including in sub folders (true)
	 *
	 * @return int
	 */
	public function countFolders(bool $recursive = false) : int
	{
		$servers = $this->getFolders($recursive);

		return count($servers);
	}

	/**
	 * @param bool $recursive Whether to return recursive folders too
	 *
	 * @return Server[]
	 */
	public function getServers(bool $recursive = false) : array
	{
		return $this->getChildren($recursive, Server::class);
	}

	/**
	 * @param bool $recursive Whether to return recursive folders too
	 *
	 * @return Folder[]
	 */
	public function getFolders(bool $recursive = false) : array
	{
		return $this->getChildren($recursive, Folder::class);
	}

	/**
	 * @param Server[]|Folder[] $children    The children of the Folder to currently search for
	 * @param bool              $recursive   Whether to return recursive folders too
	 * @param ?string           $filterClass Whether to filter by a specific type (Server, Folder) - should be the full class
	 *
	 * @return Folder[]
	 */
	private function __getChildren(array $children, bool $recursive = false, ?string $filterClass = null) : array
	{
		$returnChildren = [];

		foreach ($children as $child) {
			if ($filterClass === null || $child instanceof $filterClass) {
				$returnChildren[$child->getPath()] = $child;
			}

			if ($child instanceof Folder) {
				if ($recursive) {
					$returnChildrenSub = $child->getChildren(true, $filterClass);

					foreach ($returnChildrenSub as $key => $value) {
						$key = $value->getPath();

						$returnChildren[$key] = $value;
					}
				}
			}
		}

		return $returnChildren;
	}

	/**
	 * @param string $childName
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 */
	protected function hasDirectChild(string $childName) : bool {
		return array_key_exists($childName, $this->children);
	}

	/**
	 * @param string $childName
	 *
	 * @return Server|Folder
	 *
	 * @throws \Exception
	 */
	protected function getDirectChild(string $childName) : Server|Folder {
		if (!$this->hasDirectChild($childName)) {
			throw new \Exception(sprintf('Child at path "%s/%s" does not exist.', $this->getPath() . $childName));
		}

		return $this->children[$childName];
	}

	/**
	 * @param string $childName
	 * @param ?string $childPath
	 *
	 * @return string
	 */
	public function getChildPath(string $childName, ?string $childPath = null) : string {
		$path = $childPath !== null ? $childPath : $this->getPath();

		return (!empty($path) ? $path . '/' : '') . $childName;
	}

	/**
	 * @param string $childPath
	 *
	 * @return Server|Folder
	 *
	 * @throws \Exception
	 */
	protected function getChild(string $childPath) : Server|Folder {
		$pathSplit = explode('/', $childPath);

		$currentFolder = $this;

		$pathSplitLength = count($pathSplit);
		$pathSplitLengthMinusOne = $pathSplitLength - 1;

		for ($i = 0; $i < $pathSplitLength; $i++) {
			if (!$currentFolder->hasDirectChild($pathSplit[$i])) {
				throw new \Exception(sprintf('Could not find child at path "%s"', $this->getChildPath($pathSplit[$i], $currentFolder->getPath())));
			}

			$currentFolder = $currentFolder->getDirectChild($pathSplit[$i]);

			if ($currentFolder instanceof Server) {
				// If we found a Server we can't go any deeper - let's verify this is the last entry

				if ($i !== $pathSplitLengthMinusOne) {
					throw new \Exception(sprintf('Child at path "%s" is a server, can\'t go any deeper', $currentFolder->getPath()));
				}
			}
		}

		return $currentFolder;
	}

	/**
	 * @param string $serverPath
	 *
	 * @return Server
	 * @throws \Exception
	 */
	public function getServer(string $serverPath) : Server {
		$server = $this->getChild($serverPath);

    	if ($server instanceof Folder) {
		    throw new \Exception(sprintf('Path "%s" was a folder, not a server', $serverPath));
	    }

		return $server;
	}

	/**
	 * @param string $folderPath
	 *
	 * @return Server
	 * @throws \Exception
	 */
	public function getFolder(string $folderPath) : Folder {
		$server = $this->getChild($folderPath);

		if ($server instanceof Server) {
			throw new \Exception(sprintf('Path "%s" was a server, not a folder', $folderPath));
		}

		return $server;
	}
}
