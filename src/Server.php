<?php

namespace jalsoedesign\filezilla;

use jalsoedesign\filezilla\enum\CharsetEncoding;
use jalsoedesign\filezilla\enum\Colour;
use jalsoedesign\filezilla\enum\LogonType;
use jalsoedesign\filezilla\enum\PassiveMode;
use jalsoedesign\filezilla\enum\ServerProtocol;
use jalsoedesign\filezilla\enum\ServerType;

/**
 * Class Server
 *
 * @package jalsoedesign\filezilla
 */
class Server extends AbstractServerFolderChild
{
	/** @var string The server host (required) */
	protected string $host;

	/** @var int The server port (required) */
	protected int $port;

	/**
	 * @var int $protocol The server protocol (ServerProtocol - required)
	 *
	 * @see ServerProtocol
	 */
	protected int $protocol;

	/**
	 * @var int $logonType The server logon type (LogonType - required)
	 *
	 * @see LogonType
	 */
	protected int $logonType;

	/** @var string|null The server username (optional) */
	protected ?string $user = null;

	/** @var string|null The server password (optional) */
	protected ?string $password = null;

	/** @var string|null The path to the server key file (optional) */
	protected ?string $keyFile = null;

	/** @var string|null The account of the server (optional) */
	protected ?string $account = null;

	/** @var int|null The background colour (optional) */
	protected ?int $colour = null;

	/** @var int|null The timezone offset (optional) */
	protected ?int $timezoneOffset = null;

	/**
	 * @var string|null $passiveMode The passive mode for this server (PassiveMode - optional, only relevant for FTP servers)
	 *
	 * @see PassiveMode
	 */
	protected ?string $passiveMode = null;

	/** @var int|null The maximum concurrent connections to the server - 0 means no limit (optional) */
	protected ?int $maximumMultipleConnections = null;

	/**
	 * @var string|null $encodingType The server encoding type (CharsetEncoding - optional)
	 *
	 * @see CharsetEncoding
	 */
	protected ?string $encodingType = null;

	/** @var string|null The Custom Encoding field assuming encoding type = Custom (optional) */
	protected ?string $customEncoding = null;

	/** @var bool|null Whether to bypass the proxy (optional) */
	protected ?bool $bypassProxy = null;

	/**
	 * @var int $type The server type (ServerType)
	 *
	 * @see ServerType
	 */
	protected int $type = ServerType::_DEFAULT;

	/** @var string The server name in FileZilla */
	protected string $name;

	/** @var string|null The server comments (optional) */
	protected ?string $comments = null;

	/** @var string|null The initial local directory (optional) */
	protected ?string $localDirectory = null;

	/** @var string|null The initial remote directory (raw, optional) */
	protected ?string $remoteDirectory = null;

	/** @var bool|null Whether or not to use synchronized browsing (optional) */
	protected ?bool $synchronizedBrowsing = null;

	/** @var bool|null Whether or not to use directory comparison (optional) */
	protected ?bool $directoryComparison = null;

	/**
	 * Gets the required properties
	 *
	 * @return string[]
	 */
	protected function getRequiredProperties() : array {
		return ['name', 'host', 'protocol', 'port', 'logonType'];
	}

	/**
	 * Gets the server host
	 *
	 * @return string
	 */
	public function getHost(): string
	{
		return $this->host;
	}

	/**
	 * Gets the server port
	 *
	 * @return int
	 */
	public function getPort(): int
	{
		return $this->port;
	}

	/**
	 * Gets the server protocol
	 *
	 * @see ServerProtocol
	 *
	 * @return int
	 */
	public function getProtocol(): int
	{
		return $this->protocol;
	}

	/**
	 * Gets the server logon type
	 *
	 * @see LogonType
	 *
	 * @return int
	 */
	public function getLogonType(): int
	{
		return $this->logonType;
	}

	/**
	 * Gets the server username
	 *
	 * @return ?string Nullable user field
	 */
	public function getUser(): ?string
	{
		return $this->user;
	}

	/**
	 * Gets the server password
	 *
	 * @return ?string Nullable password field
	 */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/**
	 * Gets the server key file
	 *
	 * @return ?string Nullable key file field
	 */
	public function getKeyFile(): ?string
	{
		return $this->keyFile;
	}

	/**
	 * Gets the server auth account
	 *
	 * @return ?string Nullable account field
	 */
	public function getAccount() : ?string
	{
		return $this->account;
	}

	/**
	 * Gets the server background colour
	 *
	 * @return int Background colour, defaults to Colour::NONE
	 */
	public function getColour(): int
	{
		$colour = $this->colour;

		if (empty($colour)) {
			return Colour::NONE;
		}

		return $this->colour;
	}

	/**
	 * Alias for $this->getColour()
	 *
	 * @return int Background colour (alias)
	 */
	public function getColor(): int
	{
		return $this->getColour();
	}

	/**
	 * Gets the server timezone offset
	 *
	 * @return ?int Nullable timezone offset field
	 */
	public function getTimezoneOffset(): ?int
	{
		return $this->timezoneOffset;
	}

	/**
	 * Gets the server passive mode setting
	 *
	 * @see PassiveMode
	 *
	 * @return ?string Nullable passive mode field
	 */
	public function getPassiveMode(): ?string
	{
		return $this->passiveMode;
	}

	/**
	 * Gets the maximum allowed concurrent connections
	 *
	 * @note 0 means no limit
	 *
	 * @return ?int Nullable maximum connections field
	 */
	public function getMaximumMultipleConnections(): ?int
	{
		return $this->maximumMultipleConnections;
	}

	/**
	 * Gets the encoding type of the server
	 *
	 * @see CharsetEncoding
	 *
	 * @return ?string Nullable encoding type field
	 */
	public function getEncodingType(): ?string
	{
		return $this->encodingType;
	}

	/**
	 * Gets the custom encoding if encodingType is Custom
	 *
	 * @return ?string Nullable custom encoding field
	 */
	public function getCustomEncoding(): ?string
	{
		return $this->customEncoding;
	}

	/**
	 * Gets whether or not to bypass the proxy
	 *
	 * @return ?bool Nullable bypass proxy field
	 */
	public function getBypassProxy(): ?bool
	{
		return $this->bypassProxy;
	}

	/**
	 * Gets the server type
	 *
	 * @see ServerType
	 *
	 * @return int The server type
	 */
	public function getType(): int
	{
		return $this->type;
	}

	/**
	 * Gets the server name
	 *
	 * @return string Nullable name field
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * Gets the server comments
	 *
	 * @return ?string Nullable comments field
	 */
	public function getComments(): ?string
	{
		return $this->comments;
	}

	/**
	 * Gets the initial local directory
	 *
	 * @return ?string Nullable local directory field
	 */
	public function getLocalDirectory(): ?string
	{
		return $this->localDirectory;
	}

	/**
	 * Gets the initial remote directory
	 *
	 * @param string|null $default The default return string
	 * @param bool $useRootPrefix Whether to add a / to the beginning of the string
	 *
	 * @return ?string Nullable remote directory field
	 */
	public function getRemoteDirectory(string $default = null, bool $useRootPrefix = true): ?string
	{
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
		$remoteDirectory = $this->remoteDirectory;

		if (!empty($remoteDirectory)) {
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
		}

		return $default;
	}

	/**
	 * Gets whether to use synchronized browsing
	 *
	 * @return ?bool Nullable synchronized browsing field
	 */
	public function getSynchronizedBrowsing(): ?bool
	{
		return $this->synchronizedBrowsing;
	}

	/**
	 * Gets whether to use directory comparison
	 *
	 * @return ?bool Nullable directory comparison field
	 */
	public function getDirectoryComparison(): ?bool
	{
		return $this->directoryComparison;
	}
}
