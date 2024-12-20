<?php

use jalsoedesign\filezilla\enum\LogonType;
use jalsoedesign\filezilla\enum\ServerProtocol;
use jalsoedesign\filezilla\SiteManager;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

// Load the SiteManager instance from a custom FileZilla configuration
$siteManager = new SiteManager(__DIR__ . '/../tests/fixtures/sitemanager.xml');

// Get all servers
$server = $siteManager->getServer('Settings/Advanced/_default');

printf('Server Path: %s' . PHP_EOL, $server->getPath());
printf('Server Name: %s' . PHP_EOL, $server->getName());
printf('Synchronized Browsing: %s' . PHP_EOL, $server->getSynchronizedBrowsing() ? 'Enabled' : 'Disabled');
printf('Directory Comparison: %s' . PHP_EOL, $server->getDirectoryComparison() ? 'Enabled' : 'Disabled');
printf('Host: %s' . PHP_EOL, $server->getHost());
printf('Port: %s' . PHP_EOL, $server->getPort());
printf('Protocol: %s (%d)' . PHP_EOL, ServerProtocol::getConstantName($server->getProtocol()), $server->getProtocol());
printf('Logon Type: %s (%d)' . PHP_EOL, LogonType::getConstantName($server->getLogonType()), $server->getLogonType());
printf('User: %s' . PHP_EOL, $server->getUser());
printf('Password: %s' . PHP_EOL, $server->getPassword());
