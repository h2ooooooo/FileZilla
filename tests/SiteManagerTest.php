<?php

namespace Tests;

use jalsoedesign\filezilla\enum\CharsetEncoding;
use jalsoedesign\filezilla\enum\Colour;
use jalsoedesign\filezilla\enum\LogonType;
use jalsoedesign\filezilla\enum\PassiveMode;
use jalsoedesign\filezilla\enum\ServerProtocol;
use jalsoedesign\filezilla\enum\ServerType;
use jalsoedesign\filezilla\SiteManager;
use PHPUnit\Framework\TestCase;

class SiteManagerTest extends TestCase {
	protected SiteManager $siteManager;

	protected function setUp(): void {
		// Path to the test fixture file
		$fixturePath = sprintf('%s/fixtures/sitemanager.xml', __DIR__);

		// Ensure the file exists
		if ( ! file_exists($fixturePath)) {
			$this->fail(sprintf('Fixture file not found: %s', $fixturePath));
		}

		// Create the SiteManager instance
		$this->siteManager = new SiteManager($fixturePath);
	}

	public function xtestServers(): void {
		// Retrieve all server names (paths)
		$serverPaths = $this->siteManager->getServers();

		foreach ($serverPaths as $serverPath => $server) {
			// Determine which fields to test based on the server's path
			$fieldsToTest = $this->getFieldsToTestForPath($serverPath);

			foreach ($fieldsToTest as $field => $expectedValue) {
				$getter = 'get' . ucfirst($field);
				if ( ! method_exists($server, $getter)) {
					$this->fail("Getter method {$getter} does not exist for field '{$field}' in server: {$serverPath}");
				}

				$actualValue = $server->$getter();
				$this->assertEquals(
					$expectedValue,
					$actualValue,
					sprintf("Field '%s' does not match for server '%s'", $field, $serverPath)
				);
			}
		}
	}

	protected function getFieldsToTestForPath(string $serverPath): array {
		// Define path-specific testing rules for all servers
		$fieldTests = [
			// Protocols group
			'Protocols/azure-blob-storage.example.com'                       => [
				'host'     => 'azure-blob-storage.example.com',
				'protocol' => ServerProtocol::AZURE_BLOB,
			],
			'Protocols/azure-file-storage.example.com'                       => [
				'host'     => 'azure-file-storage.example.com',
				'protocol' => ServerProtocol::AZURE_FILE,
			],
			'Protocols/backblaze-b2.example.com'                             => [
				'host'     => 'backblaze-b2.example.com',
				'protocol' => ServerProtocol::B2,
			],
			'Protocols/box.example.com'                                      => [
				'host'     => 'box.example.com',
				'protocol' => ServerProtocol::BOX,
			],
			'Protocols/dropbox.example.com'                                  => [
				'host'     => 'dropbox.example.com',
				'protocol' => ServerProtocol::DROPBOX,
			],
			'Protocols/ftp.example.com - explicit ftp over tls'              => [
				'host'     => 'ftp.example.com',
				'protocol' => ServerProtocol::FTPES,
			],
			'Protocols/ftp.example.com - explicit ftp over tls if available' => ['host'     => 'ftp.example.com',
			                                                                     'protocol' => ServerProtocol::FTP,
			],
			'Protocols/ftp.example.com - implicit ftp over tls'              => ['host'     => 'ftp.example.com',
			                                                                     'protocol' => ServerProtocol::FTPS,
			],
			'Protocols/ftp.example.com - plain ftp'                          => ['host'     => 'ftp.example.com',
			                                                                     'protocol' => ServerProtocol::INSECURE_FTP,
			],
			'Protocols/google-cloudstorage.example.com'                      => ['host'     => 'google-cloudstorage.example.com',
			                                                                     'protocol' => ServerProtocol::GOOGLE_CLOUD,
			],
			'Protocols/google-drive.example.com'                             => ['host'     => 'google-drive.example.com',
			                                                                     'protocol' => ServerProtocol::GOOGLE_DRIVE,
			],
			'Protocols/onedrive.example.com'                                 => ['host'     => 'onedrive.example.com',
			                                                                     'protocol' => ServerProtocol::ONEDRIVE,
			],
			'Protocols/openstack-swift.example.com'                          => ['host'     => 'openstack-swift.example.com',
			                                                                     'protocol' => ServerProtocol::SWIFT,
			],
			'Protocols/rackspace-cloud-storage.example.com'                  => ['host'     => 'rackspace-cloud-storage.example.com',
			                                                                     'protocol' => ServerProtocol::RACKSPACE,
			],
			'Protocols/s3.example.com'                                       => ['host'     => 's3.example.com',
			                                                                     'protocol' => ServerProtocol::S3,
			],
			'Protocols/sftp.example.com'                                     => ['host'     => 'sftp.example.com',
			                                                                     'protocol' => ServerProtocol::SFTP,
			],
			'Protocols/webdav.example.com - http'                            => ['host'     => 'webdav.example.com',
			                                                                     'protocol' => ServerProtocol::INSECURE_WEBDAV,
			],
			'Protocols/webdav.example.com - https'                           => ['host'     => 'webdav.example.com',
			                                                                     'protocol' => ServerProtocol::WEBDAV,
			],

			// Settings/Adjusted server time group
			'Settings/Adjusted server time/minus 1 hour'                     => ['timezoneOffset' => -60],
			'Settings/Adjusted server time/plus 1 hour'                      => ['timezoneOffset' => 60],
			'Settings/Adjusted server time/plus 30 minutes'                  => ['timezoneOffset' => 30],

			// Settings/Directory comparison group
			'Settings/Directory comparison/disabled'                         => ['directoryComparison' => false],
			'Settings/Directory comparison/enabled'                          => ['directoryComparison' => true],

			// Settings/Local directory group
			'Settings/Local directory/local directory - Unix'                => ['localDirectory' => '/unix/path'],
			'Settings/Local directory/local directory - Windows'             => ['localDirectory' => 'C:\windows\path'],

			// Settings/Remote directory group
			'Settings/Remote directory/remote directory - Unix'              => ['remoteDirectory' => '/unix/path'],
			'Settings/Remote directory/remote directory - Windows'           => ['remoteDirectory' => 'C:\windows\path'],

			// Settings/Server type group
			'Settings/Server type/cygwin'                                    => ['type' => ServerType::CYGWIN],
			'Settings/Server type/default'                                   => ['type' => ServerType::_DEFAULT],
			'Settings/Server type/dos with back slashes'                     => ['type' => ServerType::DOS],
			'Settings/Server type/dos with forward slashes'                  => ['type' => ServerType::DOS_FWD_SLASHES],
			'Settings/Server type/doslike'                                   => ['type' => ServerType::DOS_VIRTUAL],
			'Settings/Server type/hp nonstop'                                => ['type' => ServerType::HP_NONSTOP],
			'Settings/Server type/mvs'                                       => ['type' => ServerType::MVS],
			'Settings/Server type/unix'                                      => ['type' => ServerType::UNIX],
			'Settings/Server type/vms'                                       => ['type' => ServerType::VMS],
			'Settings/Server type/vxworks'                                   => ['type' => ServerType::VXWORKS],
			'Settings/Server type/zvm'                                       => ['type' => ServerType::ZVM],

			// Settings/Synchronized browsing group
			'Settings/Synchronized browsing/disabled'                        => ['synchronizedBrowsing' => false],
			'Settings/Synchronized browsing/enabled'                         => ['synchronizedBrowsing' => true],

			// Settings/Charset group
			'Settings/Charset/autodetect'                                    => ['encodingType' => CharsetEncoding::ENCODING_AUTO],
			'Settings/Charset/custom charset - utf16'                        => ['encodingType'   => CharsetEncoding::ENCODING_CUSTOM,
			                                                                     'customEncoding' => 'utf16',
			],
			'Settings/Charset/force utf8'                                    => ['encodingType' => CharsetEncoding::ENCODING_UTF8],

			// Settings/General/Comments group
			'Settings/General/Comments/no comments'                          => ['comments' => ''],
			'Settings/General/Comments/with comments'                        => ['comments' => 'This is a decent server to put your files on!'],

			// Transfer Settings group
			'Transfer Settings/transfer mode - No limit'                     => ['maximumMultipleConnections' => 0],
			'Transfer Settings/transfer mode - 6 limit'                      => ['maximumMultipleConnections' => 6],

			'Transfer Settings/transfer mode - Active'  => ['passiveMode' => PassiveMode::MODE_ACTIVE],
			'Transfer Settings/transfer mode - Default' => ['passiveMode' => PassiveMode::MODE_DEFAULT],
			'Transfer Settings/transfer mode - Passive' => ['passiveMode' => PassiveMode::MODE_PASSIVE],

			'Settings/Advanced/_default' => [
				'host'                       => 'ftp.example.com',
				'port'                       => 21,
				'protocol'                   => ServerProtocol::FTP,
				'type'                       => ServerType::_DEFAULT,
				'user'                       => 'user',
				'password'                   => 'pass',
				'logonType'                  => LogonType::NORMAL,
				'timezoneOffset'             => 0,
				'passiveMode'                => PassiveMode::MODE_DEFAULT,
				'maximumMultipleConnections' => 0,
				'encodingType'               => CharsetEncoding::ENCODING_AUTO,
				'bypassProxy'                => false,
				'name'                       => '_default',
				'comments'                   => '',
				'localDirectory'             => '',
				'remoteDirectory'            => '',
				'synchronizedBrowsing'       => false,
				'directoryComparison'        => false,
			],
		];

		// Match the server path to the field tests
		foreach ($fieldTests as $serverPathFields => $fields) {
			if ($serverPath === $serverPathFields) {
				return $fields;
			}
		}

		// Default: no fields to test for paths not explicitly defined
		return [];
	}

	public function testServerProperties(): void {
		$server = $this->siteManager->getServer('Settings/Advanced/_default');

		$this->assertEquals('ftp.example.com', $server->getHost(), 'Host does not match');
		$this->assertEquals(21, $server->getPort(), 'Port does not match');
		$this->assertEquals(ServerProtocol::FTP, $server->getProtocol(), 'Protocol does not match');
		$this->assertEquals(LogonType::NORMAL, $server->getLogonType(), 'LogonType does not match');
		$this->assertEquals('user', $server->getUser(), 'User does not match');
		$this->assertEquals('pass', $server->getPassword(), 'Password does not match');
		$this->assertFalse($server->getSynchronizedBrowsing(), 'SynchronizedBrowsing should be disabled');
		$this->assertFalse($server->getDirectoryComparison(), 'DirectoryComparison should be disabled');
	}

	public function testErrorHandlingForInvalidServer(): void {
		$this->expectException(\Exception::class);
		$this->expectExceptionMessage('Could not find child at path "Protocols/invalid-server"');

		$this->siteManager->getServer('Protocols/invalid-server');
	}

	public function testRetrieveServerWithCustomPort(): void {
		$server = $this->siteManager->getServer('Settings/General/Custom port/custom port 8080');
		$this->assertEquals(8080, $server->getPort(), 'Port does not match for custom port server');
	}

	public function testCharsetEncodings(): void {
		$serverAuto = $this->siteManager->getServer('Settings/Charset/autodetect');
		$this->assertEquals(CharsetEncoding::ENCODING_AUTO, $serverAuto->getEncodingType(),
			'EncodingType does not match for autodetect');

		$serverUtf8 = $this->siteManager->getServer('Settings/Charset/force utf8');
		$this->assertEquals(CharsetEncoding::ENCODING_UTF8, $serverUtf8->getEncodingType(),
			'EncodingType does not match for UTF-8');

		$serverCustom = $this->siteManager->getServer('Settings/Charset/custom charset - utf16');
		$this->assertEquals(CharsetEncoding::ENCODING_CUSTOM, $serverCustom->getEncodingType(),
			'EncodingType does not match for custom charset');
		$this->assertEquals('utf16', $serverCustom->getCustomEncoding(), 'CustomEncoding does not match');
	}

	public function testTransferSettings(): void {
		$serverNoLimit = $this->siteManager->getServer('Settings/Transfer Settings/transfer mode - No limit');
		$this->assertEquals(0, $serverNoLimit->getMaximumMultipleConnections(), 'Max connections should be unlimited');

		$serverLimited = $this->siteManager->getServer('Settings/Transfer Settings/transfer mode - 6 limit');
		$this->assertEquals(6, $serverLimited->getMaximumMultipleConnections(), 'Max connections should be 6');

		$serverActive = $this->siteManager->getServer('Settings/Transfer Settings/transfer mode - Active');
		$this->assertEquals(PassiveMode::MODE_ACTIVE, $serverActive->getPassiveMode(), 'PassiveMode should be Active');

		$serverPassive = $this->siteManager->getServer('Settings/Transfer Settings/transfer mode - Passive');
		$this->assertEquals(PassiveMode::MODE_PASSIVE, $serverPassive->getPassiveMode(),
			'PassiveMode should be Passive');
	}

	public function testSynchronizedBrowsing(): void {
		$disabledServer = $this->siteManager->getServer('Settings/Advanced/Synchronized browsing/disabled');
		$this->assertFalse($disabledServer->getSynchronizedBrowsing(), 'SynchronizedBrowsing should be disabled');

		$enabledServer = $this->siteManager->getServer('Settings/Advanced/Synchronized browsing/enabled');
		$this->assertTrue($enabledServer->getSynchronizedBrowsing(), 'SynchronizedBrowsing should be enabled');
		$this->assertEquals('/local/path', $enabledServer->getLocalDirectory(), 'Local directory does not match');
		$this->assertEquals('/remote/path', $enabledServer->getRemoteDirectory(), 'Remote directory does not match');
	}

	public function testDirectoryComparison(): void {
		$disabledServer = $this->siteManager->getServer('Settings/Advanced/Directory comparison/disabled');
		$this->assertFalse($disabledServer->getDirectoryComparison(), 'DirectoryComparison should be disabled');

		$enabledServer = $this->siteManager->getServer('Settings/Advanced/Directory comparison/enabled');
		$this->assertTrue($enabledServer->getDirectoryComparison(), 'DirectoryComparison should be enabled');
	}

	public function testCustomComments(): void {
		$serverNoComments = $this->siteManager->getServer('Settings/General/Comments/no comments');
		$this->assertEmpty($serverNoComments->getComments(), 'Comments should be empty');

		$serverWithComments = $this->siteManager->getServer('Settings/General/Comments/with comments');
		$this->assertEquals(
			'This is a decent server to put your files on!',
			$serverWithComments->getComments(),
			'Comments do not match'
		);
	}

	public function testTimezoneOffsets(): void {
		$minusOneHour = $this->siteManager->getServer('Settings/Advanced/Adjusted server time/minus 1 hour');
		$this->assertEquals(-60, $minusOneHour->getTimezoneOffset(), 'TimezoneOffset does not match for minus 1 hour');

		$plusOneHour = $this->siteManager->getServer('Settings/Advanced/Adjusted server time/plus 1 hour');
		$this->assertEquals(60, $plusOneHour->getTimezoneOffset(), 'TimezoneOffset does not match for plus 1 hour');

		$plusThirtyMinutes = $this->siteManager->getServer('Settings/Advanced/Adjusted server time/plus 30 minutes');
		$this->assertEquals(30, $plusThirtyMinutes->getTimezoneOffset(),
			'TimezoneOffset does not match for plus 30 minutes');
	}

	public function testBypassProxySettings(): void {
		$proxyDisabled = $this->siteManager->getServer('Settings/Advanced/Bypass proxy/disabled');
		$this->assertFalse($proxyDisabled->getBypassProxy(), 'BypassProxy should be disabled');

		$proxyEnabled = $this->siteManager->getServer('Settings/Advanced/Bypass proxy/enabled');
		$this->assertTrue($proxyEnabled->getBypassProxy(), 'BypassProxy should be enabled');
	}

	public function testLocalDirectorySettings(): void {
		$unixDir = $this->siteManager->getServer('Settings/Advanced/Local directory/local directory - Unix');
		$this->assertEquals('/unix/path', $unixDir->getLocalDirectory(), 'LocalDirectory does not match for Unix');

		$windowsDir = $this->siteManager->getServer('Settings/Advanced/Local directory/local directory - Windows');
		$this->assertEquals('C:\\windows\\path', $windowsDir->getLocalDirectory(),
			'LocalDirectory does not match for Windows');
	}

	public function testRemoteDirectorySettings(): void {
		$unixDir = $this->siteManager->getServer('Settings/Advanced/Remote directory/remote directory - Unix');
		$this->assertEquals('/unix/path', $unixDir->getRemoteDirectory(), 'RemoteDirectory does not match for Unix');

		$windowsDir = $this->siteManager->getServer('Settings/Advanced/Remote directory/remote directory - Windows');
		$this->assertEquals('C:\\windows\\path', $windowsDir->getRemoteDirectory(),
			'RemoteDirectory does not match for Windows');
	}

	public function testServerTypes(): void {
		$types = [
			'cygwin'                   => ServerType::CYGWIN,
			'dos with back slashes'    => ServerType::DOS,
			'dos with forward slashes' => ServerType::DOS_FWD_SLASHES,
			'doslike'                  => ServerType::DOS_VIRTUAL,
			'hp nonstop'               => ServerType::HP_NONSTOP,
			'mvs'                      => ServerType::MVS,
			'unix'                     => ServerType::UNIX,
			'vms'                      => ServerType::VMS,
			'vxworks'                  => ServerType::VXWORKS,
			'zvm'                      => ServerType::ZVM,
		];

		foreach ($types as $name => $expectedType) {
			$server = $this->siteManager->getServer("Settings/Advanced/Server type/{$name}");
			$this->assertEquals($expectedType, $server->getType(), "ServerType does not match for {$name}");
		}
	}

	public function testBackgroundColors(): void {
		$none = $this->siteManager->getServer('Settings/General/Background color/none');
		$this->assertEquals('none', Colour::toCamelCase($none->getColour()), 'BackgroundColor should be empty');

		$red = $this->siteManager->getServer('Settings/General/Background color/red');
		$this->assertEquals('red', Colour::toCamelCase($red->getColour()), 'BackgroundColor does not match for red');
	}

	public function testCustomPorts(): void {
		$defaultPort = $this->siteManager->getServer('Settings/General/Custom port/default port');
		$this->assertEquals(21, $defaultPort->getPort(), 'Port does not match for default port');
	}

	public function testTransferModeDefault(): void {
		$defaultMode = $this->siteManager->getServer('Settings/Transfer Settings/transfer mode - Default');
		$this->assertEquals(PassiveMode::MODE_DEFAULT, $defaultMode->getPassiveMode(), 'PassiveMode should be Default');
	}

	public function testPath() : void {
		$defaultServer = $this->siteManager->getServer('Settings/Advanced/_default');

		$this->assertEquals('Settings/Advanced/_default', $defaultServer->getPath(), 'Path should be Settings/Advanced/_default');

		$advancedFolder = $this->siteManager->getFolder('Settings/Advanced');
		$defaultServer = $advancedFolder->getServer('_default');

		$this->assertEquals('Settings/Advanced/_default', $defaultServer->getPath(), 'Path should be Settings/Advanced/_default even when using relative folder');
	}

	public function testFolderIteration(): void {
		$variousFolder = $this->siteManager->getFolder('Various');

		$serverNames = array_keys($variousFolder->getServers());

		$this->assertCount(2, $serverNames, 'Too many servers in Various folder');

		$this->assertEquals('Various/Special !"#¤%&()=<>', $serverNames[0], 'Did not read special characters correctly');
		$this->assertEquals('Various/Utf8 💞💢💫', $serverNames[1], 'Did not read unicode characters correctly');

		$settingsFolder = $this->siteManager->getFolder('Settings');

		$folders = $settingsFolder->getFolders();
		$servers = $settingsFolder->getServers();

		$this->assertCount(4, $folders, 'Received an invalid amount of folders');
		$this->assertCount(0, $servers, 'Received an invalid amount of servers');

		$foldersSimple = [];

		foreach ($folders as $folder) {
			$foldersSimple[] = [
				'path' => $folder->getPath(),
				'name' => $folder->getName(),
			];
		}

		$this->assertEquals([
			[
				'path' => 'Settings/Advanced',
				'name' => 'Advanced',
			],
			[
				'path' => 'Settings/Charset',
				'name' => 'Charset',
			],
			[
				'path' => 'Settings/General',
				'name' => 'General',
			],
			[
				'path' => 'Settings/Transfer Settings',
				'name' => 'Transfer Settings',
			]
		], $foldersSimple);

		$advancedFolder = $settingsFolder->getFolder('Advanced');

		$advancedServers = $advancedFolder->getServers();

		$advancedServersSimple = [];

		foreach ($advancedServers as $advancedServer) {
			$advancedServersSimple[] = [
				'path' => $advancedServer->getPath(),
				'name' => $advancedServer->getName(),
			];
		}

		$this->assertEquals([
			[
				'path' => 'Settings/Advanced/_default',
				'name' => '_default',
			]
		], $advancedServersSimple);
	}

	public function testLoadInvalidSiteManager() : void {
		$this->expectExceptionMessage('Could not find a <Servers> tag in the FileZilla config');

		$siteManager = new SiteManager(__DIR__ . '/fixtures/sitemanager.invalid.xml');
	}

	public function testLoadMassiveSiteManager() : void {
		// From InternalFixtureGenerator::generateMassiveSiteManager
		$maxDepth = 6;
		$foldersPerLevel = 5;
		$serversPerFolder = 5;

		$totalExpectedServers = 0;
		$totalExpectedFolders = 0;

		for ($depth = 1; $depth <= $maxDepth; $depth++) {
			$totalExpectedFolders += pow($foldersPerLevel, $depth);
		}

		$totalExpectedServers = $totalExpectedFolders * $serversPerFolder;

		// Load massive file
		$siteManager = new SiteManager(__DIR__ . '/fixtures/generated/sitemanager.massive.xml');

		$allServers = $siteManager->getServers(true);

		$this->assertCount($totalExpectedServers, $allServers, 'Could not read massive site manager servers correctly');

		$allFolders = $siteManager->getFolders(true);

		$this->assertCount($totalExpectedFolders, $allFolders, 'Could not read massive site manager folders correctly');
	}

	public function testRecursiveFetch() : void {
		$recursiveServers = $this->siteManager->getServers(true);
		$this->assertCount(59, $recursiveServers, 'Recursive server count is incorrect');

		$recursiveFolders = $this->siteManager->getFolders(true);
		$this->assertCount(17, $recursiveFolders, 'Recursive folder count is incorrect');
	}

	public function testSpecialCharacters(): void {
		$specialServer = $this->siteManager->getServer('Various/Special !"#¤%&()=<>');
		$unicodeServer = $this->siteManager->getServer('Various/Utf8 💞💢💫');

		$this->assertNotEmpty($specialServer, 'Could not get server with special characters in the name');
		$this->assertNotEmpty($unicodeServer, 'Could not get server with unicode characters in the name');

		$this->assertEquals(
			'!"#¤%&/()=<>!"#¤%&/()=<>!"#¤%&/()=<>!"#¤%&/()=<>!"#¤%&/()=<>!"#¤%&/()=<>',
			$specialServer->getComments(),
			'Could not read server comments with special characters'
		);

		$this->assertEquals(
			'This is a comments field with some emojis ❤🧡💛💚💙💜🤎🖤🤍💔❣💕💞💓💗💖',
			$unicodeServer->getComments(),
			'Could not read server comments with special characters'
		);
	}

	public function testMainFzTag() : void {
		$this->assertEquals('3.64.0', $this->siteManager->getVersion(), 'Could not read version from main FZ config tag');
		$this->assertEquals('windows', $this->siteManager->getPlatform(), 'Could not read platform from main FZ config tag');
	}
}
