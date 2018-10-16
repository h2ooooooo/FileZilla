<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 07-06-2017
 * Time: 11:01
 */

namespace jalsoedesign\FileZilla;

class SiteManager {
	protected $servers;

	public function __construct($path) {
		$xml = simplexml_load_file($path);

		$this->servers = $this->parseServers($xml->Servers->children());
	}

	/**
	 * @param \SimpleXMLElement[] $servers
	 *
	 * @return array
	 */
	protected function parseServers($servers) {
		$parsedServers = [];

		foreach ($servers as $child) {
			/** @var \SimpleXMLElement $child */
			$childTag = $child->getName();

			/** @var \DOMElement $domElement */
			$domElement = dom_import_simplexml($child);

			if ($childTag === 'Folder') {
				$folderName = $domElement->firstChild->textContent;

				/** @var \SimpleXMLElement[] $folderChildren */
				$folderChildren = $child->children();

				$parsedServers[ $folderName ] = $this->parseServers($folderChildren);
			} else if ($childTag === 'Server') {
				$server = new Server($child);

				$parsedServers[ $server->getName() ] = $server;
			}
		}

		return $parsedServers;
	}

	public function getServerNames() {
		return $this->__getServerNames($this->servers);
	}
	
	private function __getServerNames($serverArray, $prefix = null) {
		$serverNames = [];

		$_prefix = ($prefix !== null ? $prefix . '/' : '');
		
		foreach ($serverArray as $key => $value) {
			if ($value instanceof Server) {
				$serverNames[] = $_prefix . $key;

				continue;
			}
			
			$serverNames = array_merge(
				$serverNames,
				$this->__getServerNames($value, $_prefix . $key)
			);
		}
		
		return $serverNames;
	}

	/**
	 * @param string $server
	 *
	 * @throws \Exception
	 *
	 * @return Server
	 */
	public function getServer($server) {
		$serverSplit = preg_split('~[\\\/]+~', trim($server, '\\\/ '), -1, PREG_SPLIT_NO_EMPTY);

		$servers = $this->servers;

		for ($i = 0, $len = count($serverSplit); $i < $len; $i++) {
			$serverSplitPart = $serverSplit[ $i ];

			if ( ! isset($servers[ $serverSplitPart ])) {
				throw new \Exception(sprintf('Could not find server part "%s" (in "%s")', $serverSplitPart, $server));
			}

			$servers = $servers[ $serverSplitPart ];
		}

		return !empty($servers) ? $servers : null;
	}
}