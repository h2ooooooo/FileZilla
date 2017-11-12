<?php
/**
 * Created by PhpStorm.
 * User: aj
 * Date: 07-06-2017
 * Time: 12:42
 */

namespace jalsoedesign\FileZilla\enum;

/**
 * Class ServerFormat
 *
 * @package jalsoedesign\FileZilla\enum
 *
 * @see https://github.com/basvodde/filezilla/blob/8f874dfe2fdfee4a95cf706bc860252a5793c32e/src/include/server.h
 */
class ServerFormat extends Enum {
	const HOST_ONLY = 0;
	const WITH_OPTIONAL_PORT = 1;
	const WITH_USER_AND_OPTIONAL_PORT = 2;
	const URL = 3;
	const URL_WITH_PASSWORD = 4;
}