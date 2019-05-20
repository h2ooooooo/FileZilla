<?php

namespace jalsoedesign\FileZilla;

use jalsoedesign\FileZilla\enum\LogonType;
use jalsoedesign\FileZilla\enum\CharsetEncoding;
use jalsoedesign\FileZilla\enum\PassiveMode;
use jalsoedesign\FileZilla\enum\ServerProtocol;
use jalsoedesign\FileZilla\enum\ServerType;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Filesystem;
use League\Flysystem\Sftp\SftpAdapter;
use VladimirYuldashev\Flysystem\CurlFtpAdapter;

/**
 * Class Server
 *
 * @package jalsoedesign\FileZilla
 */
class Server
{
    /** @var string The server host */
    protected $host;

    /** @var int The server port */
    protected $port;

    /**
     * @var int $protocol The server protocol (ServerProtocol)
     * @see ServerProtocol
     */
    protected $protocol;

    /**
     * @var int $type The server type (ServerType)
     * @see ServerType
     */
    protected $type;

    /** @var string The server username */
    protected $user;

    /** @var string The server password */
    protected $password;

    /** @var string The path to the server key file */
    protected $keyFile;

    /**
     * @var int $logonType The server logon type (LogonType)
     * @see LogonType
     */
    protected $logonType;

    /** @var int The timezone offset */
    protected $timezoneOffset;

    /**
     * @var int $passiveMode The passive mode for this server (PassiveMode - only relevant for FTP servers)
     * @see PassiveMode
     */
    protected $passiveMode;

    /** @var int The maximum concurrent connections to the server - 0 means no limit */
    protected $maximumMultipleConnections;

    /**
     * @var int $encodingType The server encoding type (CharsetEncoding)
     * @see CharsetEncoding
     */
    protected $encodingType;

    /** @var bool Whether or not to bypass the proxy */
    protected $bypassProxy;

    /** @var string The server name in FileZilla */
    protected $name;

    /** @var string The server comments */
    protected $comments;

    /** @var string The initial local directory */
    protected $localDirectory;

    /** @var string The initial remote directory (raw) */
    protected $remoteDirectory;

    /** @var bool Whether or not to use synchronized browsing */
    protected $synchronizedBrowsing;

    /** @var bool Whether or not to use directory comparison */
    protected $directoryComparison;

    /**
     * Server constructor.
     *
     * @param array $properties The properties (usually from ServerFactory)
     */
    public function __construct($properties)
    {
        foreach ($properties as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * Gets the server remote directory
     *
     * @param string $default The default return string
     *
     * @return string
     */
    public function getRemoteDirectory($default = null)
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

        if (preg_match('~^(\d+)\s+(\d+)\s+(.+)$~', $remoteDirectory, $match)) {
            $pathType = (int)$match[1];
            $prefixLength = (int)$match[2];

            $offset = 0;

            $pathSegments = [];

            while (preg_match('~(\d+)~', $match[3], $pathLengthMatch, PREG_OFFSET_CAPTURE, $offset)) {
                $pathStringLength = strlen($pathLengthMatch[1][0]);
                $pathLength = (int)$pathLengthMatch[1][0];
                $pathLengthOffset = (int)$pathLengthMatch[1][1];

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

        return $default;
    }

    /**
     * Gets the server host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Gets the server port
     *
     * @return int
     */
    public function getPort()
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
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Gets the server type
     *
     * @see ServerType
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the server username
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Gets the server password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Gets the server key file (private key) if one is present
     *
     * @return string|null
     */
    public function getKeyFile()
    {
        return $this->keyFile;
    }

    /**
     * Gets the server login type
     *
     * @see LogonType
     *
     * @return int
     */
    public function getLogonType()
    {
        return $this->logonType;
    }

    /**
     * Gets the server timezone offset
     *
     * @return int
     */
    public function getTimezoneOffset()
    {
        return $this->timezoneOffset;
    }

    /**
     * Gets the server passive mode setting
     *
     * @see PassiveMode
     *
     * @return int
     */
    public function getPassiveMode()
    {
        return $this->passiveMode;
    }

    /**
     * Gets the maximum allowed concurrent connections
     *
     * @note 0 means no limit
     *
     * @return int
     */
    public function getMaximumMultipleConnections()
    {
        return $this->maximumMultipleConnections;
    }

    /**
     * Gets the encoding type of the server
     *
     * @see CharsetEncoding
     *
     * @return int
     */
    public function getEncodingType()
    {
        return $this->encodingType;
    }

    /**
     * Gets whether or not to bypass the proxy
     *
     * @return bool
     */
    public function getBypassProxy()
    {
        return $this->bypassProxy;
    }

    /**
     * Gets the server name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Gets the server comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Gets the initial local directory
     *
     * @return string
     */
    public function getLocalDirectory()
    {
        return $this->localDirectory;
    }

    /**
     * Gets whether or not to use synchronized browsing
     *
     * @return bool
     */
    public function getSynchronizedBrowsing()
    {
        return $this->synchronizedBrowsing;
    }

    /**
     * Gets whether or not to use directory comparison
     *
     * @return bool
     */
    public function getDirectoryComparison()
    {
        return $this->directoryComparison;
    }

    /**
     * Gets the Flysystem adapter timeout
     *
     * @return int
     */
    private function getFlysystemAdapterTimeout() {
        return 10;
    }

    /**
     * Gets a Flysystem adapter from the profile
     *
     * @param int $directoryPerm The directory perm to create the Flysystem adapters with
     *
     * @return \League\Flysystem\Filesystem
     *
     * @throws \Exception
     */
    public function getFileSystem($directoryPerm = 0755)
    {
        $flySystemAdapter = null;

        $serverProtocol = $this->getProtocol();

        switch ($serverProtocol) {
            case ServerProtocol::FTP:
            case ServerProtocol::INSECURE_FTP:
            case ServerProtocol::FTPS:
            case ServerProtocol::FTPES:
                // FTP
                $ssl = $serverProtocol === ServerProtocol::FTPS || $serverProtocol === ServerProtocol::FTPES;
                $passiveMode = $this->getPassiveMode() === PassiveMode::MODE_PASSIVE;

                if ($ssl) {
                    if ($passiveMode) {
                        // CurlFtpAdapter has no "passive" setting
                        // Ftp adapter has no implicit SSL
                        throw new \Exception(sprintf('Cannot use both SSL FTP and passive mode in FTP connection'));
                    }

                    $flySystemAdapter = new CurlFtpAdapter([
                        'host'          => $this->getHost(),
                        'port'          => $this->getPort(),
                        'username'      => $this->getUser(),
                        'password'      => $this->getPassword(),
                        'root'          => $this->getRemoteDirectory(),
                        'timeout'       => $this->getFlysystemAdapterTimeout(),
                        'directoryPerm' => $directoryPerm,
                        'ssl'           => $serverProtocol === ServerProtocol::FTPS ? CurlFtpAdapter::SSL_IMPLICIT : CurlFtpAdapter::SSL_EXPLICIT,
                    ]);
                } else {
                    $flySystemAdapter = new Ftp([
                        'host'          => $this->getHost(),
                        'port'          => $this->getPort(),
                        'username'      => $this->getUser(),
                        'password'      => $this->getPassword(),
                        'passive'       => $passiveMode,
                        'root'          => $this->getRemoteDirectory(),
                        'timeout'       => $this->getFlysystemAdapterTimeout(),
                        'directoryPerm' => $directoryPerm,
                        'ssl'           => $ssl
                    ]);
                }

                break;
            case ServerProtocol::SFTP:
                // SFTP
                $flySystemAdapter = new SftpAdapter([
                    'host'          => $this->getHost(),
                    'port'          => $this->getPort(),
                    'username'      => $this->getUser(),
                    'password'      => $this->getPassword(),
                    'privateKey'    => $this->getKeyFile(),
                    'root'          => $this->getRemoteDirectory(),
                    'timeout'       => $this->getFlysystemAdapterTimeout(),
                    'directoryPerm' => $directoryPerm
                ]);

                break;
        }

        if (empty($flySystemAdapter)) {
            throw new \Exception(sprintf('FlySystem adapter for server protocol %s not found',
                ServerProtocol::getConstantName($serverProtocol)));
        }

        // Return the Flysystem FileSystem instance
        return new Filesystem($flySystemAdapter);
    }
}