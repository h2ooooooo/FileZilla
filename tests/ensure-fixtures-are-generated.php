<?php

use Tests\InternalFixtureGenerator;

require_once(__DIR__ . '/../vendor/autoload.php');

require_once(__DIR__ . '/InternalFixtureGenerator.php');
$fixtureGenerator = new InternalFixtureGenerator();

// too heavy, works fine
//$fixtureGenerator->generateMassiveSiteManager();
