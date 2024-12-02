<?php

use jalsoedesign\filezilla\SiteManager;

// Load composer
require_once(__DIR__ . '/../vendor/autoload.php');

// Specify the path to the custom sitemanager.xml file
$customPath = '/path/to/custom/sitemanager.xml';
$siteManager = new SiteManager($customPath);

echo 'Custom SiteManager loaded successfully.';
