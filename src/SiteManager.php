<?php

namespace jalsoedesign\FileZilla;

/**
 * Class SiteManager
 *
 * @package jalsoedesign\FileZilla
 */
class SiteManager {
    /**
     * Automatically creates the site manager from %AppData%/FileZilla/sitemanager.xml
     *
     * @return SiteManager
     *
     * @throws \Exception
     */
    public static function fromAppData() {
        $siteManagerPath = sprintf('%s/FileZilla/sitemanager.xml', getenv('appdata'));

        if (!file_exists($siteManagerPath)) {
            throw new \Exception(sprintf('Could not find sitemanager.xml at %s', $siteManagerPath));
        }

        return new SiteManager($siteManagerPath);
    }

    /** @var Server[] */
	protected $servers;

    /**
     * SiteManager constructor.
     *
     * @param string $path The path to the sitemanager.xml file
     *
     * @note The file is located by default in %AppData%/FileZilla/sitemanager.xml
     *
     * @throws \Exception If an invalid sitemanager.xml file was found
     */
	public function __construct($path) {
	    // Load the sitemanager.xml file
	    $dom = new \DOMDocument();
	    $dom->load($path);

	    $servers = $dom->getElementsByTagName('Servers');

	    if ($servers->length <= 0) {
	        throw new \Exception(sprintf('Could not find a <Servers> tag in the FileZilla config'));
        }

	    // Parse servers
        $this->servers = $this->parseServers($servers->item(0));

	    // Clean up
	    unset($dom);
	}

    /**
     * Parses all servers recursively in a \DOMNode instance
     *
     * @param \DOMNode $node The dom node to currently search - will recursively be child nodes if any "Folder" tags are found
     *
     * @return Server[]
     */
	protected function parseServers(\DOMNode $node) {
        $parsedServers = [];

        /** @var \DOMNodeList $childNodes */
        $childNodes = $node->childNodes;

        if ($childNodes->length > 0) {
            foreach ($childNodes as $childNode) {
                /** @var \DOMNode $childNode */
                if ($childNode->nodeName === 'Folder') {
                    // Iterate down through the folder to find servers inside of it
                    $folderName = $childNode->firstChild->textContent;

                    $parsedServers[$folderName] = $this->parseServers($childNode);
                } else if ($childNode->nodeName === 'Server') {
                    // Found a server - let's parse this and add it to our $parsedServers array
                    $server = ServerFactory::fromDomNode($childNode);

                    $parsedServers[ $server->getName() ] = $server;
                }
            }
        }

        return $parsedServers;
    }

    /**
     * Gets the name of all the servers in the sitemanager.xml file
     *
     * All of these names can be used in SiteManager->getServer()
     *
     * @return array An array of server names
     */
	public function getServerNames() {
		return $this->__getServerNames($this->servers);
	}

    /**
     * An internal recursive function to get server names
     *
     * @internal
     *
     * @param array $serverArray The current server array to look through
     * @param string $prefix Whether to add a prefix or not (is used for sub-folders)
     *
     * @return array An array of server names
     */
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
	 * @param string $server The path to the single server - will be the name in FileZilla along with the directories it's in
	 *
	 * @throws \Exception If a server by that name can't be found an exception will be thrown
	 *
	 * @return Server The server by that name
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