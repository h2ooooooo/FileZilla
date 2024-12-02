<?php

namespace jalsoedesign\filezilla\enum;

/**
 * Class LogonType
 *
 * @package jalsoedesign\filezilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h - enum LogonType
 */
class LogonType extends Enum {
	const ANONYMOUS = 0;
	const NORMAL = 1;
	const ASK = 2; // ASK should not be sent to the engine, it's intended to be used by the interface
	const INTERACTIVE = 3;
	const ACCOUNT = 4;
	const KEY = 5;
	const PROFILE = 6; // S3 profile
	const ADC = 7; // Google Cloud Application Default Credenttials
	const COUNT = 8;
}
