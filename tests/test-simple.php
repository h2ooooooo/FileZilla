<?php

use jalsoedesign\FileZilla\SiteManager;

require_once(__DIR__ . '/../vendor/autoload.php');

$siteManager = new SiteManager(__DIR__ . '/fixtures/sitemanager.xml');

$server = $siteManager->getServer('Rebex/FTP');

printf('Host: %s', $server->getHost()); // test.rebex.net

$fileSystem = $server->getFileSystem();

$contents = $fileSystem->listContents();

print_r($contents);
/**
 * (
 *  [0] => Array
 *      (
 *          [type] => dir
 *          [path] => aspnet_client
 *          [timestamp] => 1449173640
 *          [dirname] =>
 *          [basename] => aspnet_client
 *          [filename] => aspnet_client
 *      )
 *  [1] => Array
 *      (
 *          [type] => dir
 *          [path] => pub
 *          [timestamp] => 1445960760
 *          [dirname] =>
 *          [basename] => pub
 *          [filename] => pub
 *      )
 *  [2] => Array
 *      (
 *          [type] => file
 *          [path] => readme.txt
 *          [visibility] => public
 *          [size] => 403
 *          [timestamp] => 1396966140
 *          [dirname] =>
 *          [basename] => readme.txt
 *          [extension] => txt
 *          [filename] => readme
 *      )
 * )
 */