<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 07-06-2017
 * Time: 12:42
 */

namespace jalsoedesign\FileZilla\enum;

/**
 * Class PassiveMode
 *
 * @package jalsoedesign\FileZilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h
 */
class PassiveMode extends Enum {
	const MODE_DEFAULT = 0; // Server default
	const MODE_ACTIVE = 1; // Force active mode
	const MODE_PASSIVE = 2; // Force passive mode
}