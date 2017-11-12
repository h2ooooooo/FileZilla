<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 07-06-2017
 * Time: 11:01
 */

namespace jalsoedesign\FileZilla;

use jalsoedesign\FileZilla\enum\PassiveMode;
use jalsoedesign\FileZilla\enum\ServerType;

class Server {
	protected $host;
	protected $port;
	/**
	 * @var int $protocol
	 *
	 *   0 - FTP
	 *   1 - SFTP
	 *   2 - FTPS
	 *   3 - FTPES
	 *   4 - INSECURE_FTP
	 */
	protected $protocol;
	protected $type;
	protected $user;
	protected $password;
	/**
	 * @var int $logonType
	 *
	 *   0 - anonymous,
	 *   1 - normal,
	 *   2 - ask, // ask should not be sent to the engine, it's intended to be used by the interface
	 *   3 - interactive,
	 *   4 - account,
	 *   5 - key,
	 *   6 - count
	 * }
	 */
	protected $logonType;
	protected $timezoneOffset;
	/**
	 * @var
	 *
	 *   MODE_DEFAULT
	 * MODE_ACTIVE
	 * MODE_PASSIVE
	 */
	protected $passiveMode;
	protected $maximumMultipleConnections;
	/**
	 * @var
	 *
	 * ENCODING_AUTO
	 * ENCODING_UTF8
	 * ENCODING_CUSTOM
	 */
	protected $encodingType;
	protected $bypassProxy;
	protected $name;
	protected $comments;
	protected $localDirectory;
	/**
	 * 1 0 4 home 3 usr 3 www
	 *
	 *   1
	 *     path type (ServerType)
	 *
	 *   0
	 *     prefix length
	 *
	 *   4 home
	 *     4 - length of "home"
	 *
	 *   3 usr
	 *     3 - length of "usr"
	 *
	 *   3 www
	 *     3 - length of "www"
	 */
	protected $remoteDirectory;
	protected $syncronizedBrowsing;
	protected $directoryComparison;

	/**
	 * Server constructor.
	 *
	 * @param \SimpleXMLElement $xmlElement
	 */
	public function __construct($xmlElement) {
		$tagMapper = [
			'Host'                       => 'host',
			'Port'                       => 'port',
			'Protocol'                   => 'protocol',
			'Type'                       => 'type',
			'User'                       => 'user',
			'Pass'                       => 'password',
			'Logontype'                  => 'logonType',
			'TimezoneOffset'             => 'timezoneOffset',
			'PasvMode'                   => 'passiveMode',
			'MaximumMultipleConnections' => 'maximumMultipleConnections',
			'EncodingType'               => 'encodingType',
			'BypassProxy'                => 'bypassProxy',
			'Name'                       => 'name',
			'Comments'                   => 'comments',
			'LocalDir'                   => 'localDirectory',
			'RemoteDir'                  => 'remoteDirectory',
			'SyncBrowsing'               => 'synchronizedBrowsing',
			'DirectoryComparison'        => 'directoryComparison',
		];

		$caster = [
			'port'                       => 'int',
			'protocol'                   => 'int',
			'type'                       => 'int',
			'logonType'                  => 'int',
			'timezoneOffset'             => 'int',
			'maximumMultipleConnections' => 'int',
			'bypassProxy'                => 'bool',
			'synchronizedBrowsing'       => 'bool',
			'directoryComparison'        => 'bool',
		];

		foreach ($xmlElement->children() as $tag) {
			/** @var \SimpleXMLElement $tag */
			$tagName = $tag->getName();

			if ( ! isset($tagMapper[ $tagName ])) {
				continue;
			}

			$property = $tagMapper[ $tagName ];

			$value = (string) $tag;

			foreach ($tag->attributes() as $attribute) {
				/** @var \SimpleXMLElement $attribute */
				$attributeName  = $attribute->getName();
				$attributeValue = (string) $attribute;

				if ($attributeName === 'encoding') {
					if ($attributeValue === 'base64') {
						$value = base64_decode($value);
					}
				}
			}

			if (isset($caster[ $property ])) {
				settype($value, $caster[ $property ]);
			}

			$this->{$property} = $value;
		}
	}

	/**
	 * 1 0 4 home 3 usr 3 www
	 *
	 *   1
	 *     path type (ServerType)
	 *
	 *   0
	 *     prefix length
	 *
	 *   4 home
	 *     4 - length of "home"
	 *
	 *   3 usr
	 *     3 - length of "usr"
	 *
	 *   3 www
	 *     3 - length of "www"
	 */
	public function getRemoteDirectory() {
		$remoteDirectory = $this->remoteDirectory;

		if (preg_match('~^(\d+)\s+(\d+)\s+(.+)$~', $remoteDirectory, $match)) {
			$pathType     = (int) $match[1];
			$prefixLength = (int) $match[2];

			$offset = 0;

			$pathSegments = [];

			while (preg_match('~(\d+)~', $match[3], $pathLengthMatch, PREG_OFFSET_CAPTURE, $offset)) {
				$pathStringLength = strlen($pathLengthMatch[1][0]);
				$pathLength       = (int) $pathLengthMatch[1][0];
				$pathLengthOffset = (int) $pathLengthMatch[1][1];

				$pathSegments[] = substr($match[3], $pathLengthOffset + $pathStringLength + 1, $pathLength);

				$offset = $pathLengthOffset + $pathStringLength + $pathLength + 1;
			}

			$directorySeparator = '/';

			if ($pathType === ServerType::DOS) {
				$directorySeparator = '\\';
			}

			$path = implode($directorySeparator, $pathSegments);

			if ($pathType === ServerType::UNIX) {
				$path = '/' . $path;
			}

			return $path;
		}

		return '/';
	}

	public function getHost() {
		return $this->host;
	}

	public function getPort() {
		return $this->port;
	}

	public function getProtocol() {
		return $this->protocol;
	}

	public function getType() {
		return $this->type;
	}

	public function getUser() {
		return $this->user;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getLogonType() {
		return $this->logonType;
	}

	public function getTimezoneOffset() {
		return $this->timezoneOffset;
	}

	public function getPassiveMode() {
		return $this->passiveMode;
	}

	public function getMaximumMultipleConnections() {
		return $this->maximumMultipleConnections;
	}

	public function getEncodingType() {
		return $this->encodingType;
	}

	public function getBypassProxy() {
		return $this->bypassProxy;
	}

	public function getName() {
		return $this->name;
	}

	public function getComments() {
		return $this->comments;
	}

	public function getLocalDirectory() {
		return $this->localDirectory;
	}

	public function getSyncronizedBrowsing() {
		return $this->syncronizedBrowsing;
	}

	public function getDirectoryComparison() {
		return $this->directoryComparison;
	}
}