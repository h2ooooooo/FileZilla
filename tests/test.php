<?php

namespace jalsoedesign\FileZilla\tests;

use CLImax\ApplicationUtf8;
use CLImax\Plugins\HighlightPlugin;
use jalsoedesign\FileZilla\enum\PassiveMode;
use jalsoedesign\FileZilla\Server;
use jalsoedesign\FileZilla\SiteManager;
use League\Flysystem\Adapter\Ftp;
use League\Flysystem\Filesystem;
use VladimirYuldashev\Flysystem\CurlFtpAdapter;

require_once(__DIR__ . '/../vendor/autoload.php');

/**
 * Class TestApplication
 * @package jalsoedesign\FileZilla\tests
 *
 * @note Execute simply with "php test.php"
 */
class TestApplication extends ApplicationUtf8 {
    /**
     * @throws \Exception
     */
    public function init() {
        $this->registerPlugin(new HighlightPlugin());

        $siteManager = new SiteManager(__DIR__ . '/fixtures/sitemanager.xml');

        $this->testServer($siteManager->getServer('Rebex/FTP'), 'ftpInsecure');
        $this->testServer($siteManager->getServer('Rebex/FTP SSL (explicit)'), 'ftpSslExplicit');
        $this->testServer($siteManager->getServer('Rebex/FTP SSL (implicit)'), 'ftpSslImplicit');
        $this->testServer($siteManager->getServer('Rebex/SFTP'), 'sftp');
    }

    /**
     * @param \jalsoedesign\FileZilla\Server $server
     * @param string $type
     *
     * @throws \Exception
     *
     * @return bool
     */
    private function testServer(Server $server, $type) {
        $serverName = $server->getName();
        $this->info(sprintf('Testing server {{%s}} - (%s)..', $serverName, $type));

        try {
            $this->__testServer($server);

            $this->success(sprintf('Successfully tested server {{%s}} - (%s)', $serverName, $type));

            return true;
        } catch (\Exception $e) {
            $this->error(sprintf('Error testing server {{%s}} - (%s): %s', $serverName, $type, $e->getMessage()));

            return false;
        }
    }

    /**
     * @param \jalsoedesign\FileZilla\Server $server
     *
     * @throws \Exception
     */
    private function __testServer(Server $server) {
        $fileSystem = $server->getFileSystem();

        $contents = $fileSystem->listContents();

        if (empty($contents)) {
            $this->warning(sprintf('No content found!'));

            return;
        }

        $table = $this->table();

        foreach ($contents as $item) {
            $table->addRow([
                'type' => !empty($item['type']) ? $item['type'] : null,
                'basename' => !empty($item['basename']) ? $item['basename'] : null,
                'size' => !empty($item['size']) ? $item['size'] : null,
            ]);
        }

        $table->output();
    }
}

TestApplication::launch();