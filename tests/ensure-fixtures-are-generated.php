<?php

use jalsoedesign\filezilla\InternalFixtureGenerator;

require_once(__DIR__ . '/../vendor/autoload.php');

$fixtureGenerator = new InternalFixtureGenerator();

$fixtureGenerator->generateMassiveSiteManager();
