<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 07-06-2017
 * Time: 12:42
 */

namespace jalsoedesign\FileZilla\enum;

/**
 * Class LogonType
 *
 * @package jalsoedesign\FileZilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h
 */
class LogonType extends Enum {
	const ANONYMOUS = 0;
	const NORMAL = 1;
	const ASK = 2; // ASK should not be sent to the engine, it's intended to be used by the interface
	const INTERACTIVE = 3;
	const ACCOUNT = 4;
}