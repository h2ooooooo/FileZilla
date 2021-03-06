<?php

namespace jalsoedesign\FileZilla\enum;

/**
 * Class CharsetEncoding
 *
 * @package jalsoedesign\FileZilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h
 */
class CharsetEncoding extends Enum {
	const ENCODING_AUTO = 0;
	const ENCODING_UTF8 = 1;
	const ENCODING_CUSTO = 2;
}