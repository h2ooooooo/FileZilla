<?php

namespace jalsoedesign\FileZilla\enum;

/**
 * Class PassiveMode
 *
 * @package jalsoedesign\FileZilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h
 */
class PassiveMode extends Enum {
	const MODE_DEFAULT = 'MODE_DEFAULT'; // Server default
	const MODE_ACTIVE = 'MODE_ACTIVE'; // Force active mode
	const MODE_PASSIVE = 'MODE_PASSIVE'; // Force passive mode
}