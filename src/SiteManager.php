<?php

namespace jalsoedesign\filezilla;

/**
 * Class SiteManager
 *
 * @package jalsoedesign\filezilla
 */
class SiteManager extends Folder {
	/** @var string The version from the sitemanager.xml config */
	protected string $version;

	/** @var string The platform from the sitemanager.xml config */
	protected string $platform;

	/** @var ValueEncoder The value encoder for different platforms */
	protected ValueEncoder $valueEncoder;

    /**
     * Automatically creates the SiteManager from the default FileZilla configuration path
     * based on the operating system.
     *
     * @return SiteManager
     *
     * @throws \Exception
     */
    public static function fromSystem() : SiteManager {
        $siteManagerPath = null;

	    if (stristr(PHP_OS, 'WIN')) {
		    // Windows: %AppData%/FileZilla/sitemanager.xml
		    $siteManagerPath = sprintf('%s/FileZilla/sitemanager.xml', getenv('APPDATA'));
	    } else {
		    // macOS, Linux and other UNIX-like systems: $HOME/.config/filezilla/sitemanager.xml
		    $siteManagerPath = sprintf('%s/.config/filezilla/sitemanager.xml', getenv('HOME'));
	    }

        if (!file_exists($siteManagerPath)) {
            throw new \Exception(sprintf('Could not find system sitemanager.xml at %s', $siteManagerPath));
        }

        return new SiteManager($siteManagerPath);
    }

    /**
     * SiteManager constructor.
     *
     * @param $siteManagerPth $path The path to the sitemanager.xml file
     *
     * @throws \Exception If an invalid sitemanager.xml file was found
     */
	public function __construct(string $siteManagerPth) {
	    // Load the sitemanager.xml file
	    $dom = new \DOMDocument();
	    $dom->load($siteManagerPth);

		$xpath = new \DOMXPath($dom);
		$filezillaTag = $xpath->query('/FileZilla3')->item(0);

		if ($filezillaTag === null) {
			throw new \RuntimeException('FileZilla3 tag not found in the sitemanager.xml file.');
		}

		$this->version = $filezillaTag->getAttribute('version');
		$this->platform = $filezillaTag->getAttribute('platform');

		// At some point this needed conversion and suddenly it didn't - maybe delete ValueEncoder in the future
		//$sourceEncoding = $this->platform === 'windows' ? 'Windows-1252' : 'UTF-8';
		$sourceEncoding = 'UTF-8';

		$this->valueEncoder = new ValueEncoder($sourceEncoding);

	    $servers = $dom->getElementsByTagName('Servers');

	    if ($servers->length <= 0) {
	        throw new \Exception('Could not find a <Servers> tag in the FileZilla config');
        }

		$children = $this->parseServers($servers->item(0));

	    // Clean up
	    unset($dom);

		parent::__construct('', [
			'name' => '',
			'children' => $children,
		]);
	}

    /**
     * Parses all servers recursively in a \DOMNode instance
     *
     * @param \DOMNode $node The dom node to currently search - will recursively be child nodes if any "Folder" tags are found
     * @param string $folderPath The current folder path as the code iterates through the folders
     *
     * @return Server[]|Folder[]
     */
	protected function parseServers(\DOMNode $node, string $folderPath = '') : array {
        $parsedServers = [];

        /** @var \DOMNodeList $childNodes */
        $childNodes = $node->childNodes;

        if ($childNodes->length > 0) {
            foreach ($childNodes as $childNode) {
                /** @var \DOMNode $childNode */
                if ($childNode->nodeName === 'Folder') {
                    // Iterate down through the folder to find servers inside of it
                    $folderName = $childNode->firstChild->textContent;

					$folderName = $this->valueEncoder->decode($folderName);

                    $parsedServers[$folderName] = new Folder($folderPath, [
						'name' => $folderName,
	                    'children' => $this->parseServers($childNode, $this->getChildPath($folderName, $folderPath)),
                    ]);
                } else if ($childNode->nodeName === 'Server') {
                    // Found a server - let's parse this and add it to our $parsedServers array
                    $server = ServerFactory::createServerFromDomNode($this->valueEncoder, $folderPath, $childNode);

					$serverName = $server->getName();

                    $parsedServers[ $serverName ] = $server;
                }
            }
        }

        return $parsedServers;
    }

	/**
	 * Gets the config "version" from the main <FileZilla3> config tag
	 *
	 * @return string
	 */
	public function getVersion(): string {
		return $this->version;
	}

	/**
	 * Gets the config "platform" from the main <FileZilla3> config tag
	 *
	 * @return string
	 */
	public function getPlatform(): string {
		return $this->platform;
	}
}
