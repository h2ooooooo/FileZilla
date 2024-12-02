<?php

namespace jalsoedesign\filezilla\enum;

/**
 * Class CharsetEncoding
 *
 * @package jalsoedesign\filezilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h- enum CharsetEncoding
 */
class CharsetEncoding extends Enum {
	const ENCODING_AUTO = 'Auto';
	const ENCODING_UTF8 = 'UTF-8';
	const ENCODING_CUSTOM = 'Custom';
}
