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
 * @see https://github.com/basvodde/filezilla/blob/8f874dfe2fdfee4a95cf706bc860252a5793c32e/src/include/server.h
 */
class PassiveMode extends Enum {
	const MODE_DEFAULT = 0;
	const MODE_ACTIVE = 1;
	const MODE_PASSIVE = 2;
}