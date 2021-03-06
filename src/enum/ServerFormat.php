<?php

namespace jalsoedesign\FileZilla\enum;

/**
 * Class ServerFormat
 *
 * @package jalsoedesign\FileZilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h
 */
class ServerFormat extends Enum {
	const HOST_ONLY = 0;
	const WITH_OPTIONAL_PORT = 1;
	const WITH_USER_AND_OPTIONAL_PORT = 2;
	const URL = 3;
	const URL_WITH_PASSWORD = 4;
}