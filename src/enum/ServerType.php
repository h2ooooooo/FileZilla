<?php

namespace jalsoedesign\filezilla\enum;

/**
 * Class ServerType
 *
 * @package jalsoedesign\filezilla\enum
 *
 * @see https://svn.filezilla-project.org/svn/FileZilla3/trunk/src/include/server.h - enum ServerType
 */
class ServerType extends Enum {
	const _DEFAULT = 0; // Default (Autodetect)

	const UNIX = 1; // Unix
	const VMS = 2; // VMS
	const DOS = 3; // DOS with backslash separators
	const MVS = 4; // MVS, OS/390, z/OS
	const VXWORKS = 5; // VxWorks
	const ZVM = 6; // z/VM
	const HP_NONSTOP = 7; // HP NonStop
	const DOS_VIRTUAL = 8; // DOS-like with virtual paths
	const CYGWIN = 9; // Cygwin
	const DOS_FWD_SLASHES = 10; // DOS with forward-slash separators

	const SERVERTYPE_MAX = 11;

	public static function toCamelCase($value) : ?string {
		if ($value === '_DEFAULT' || $value === ServerType::_DEFAULT) {
			return 'autoDetect';
		}

		return parent::toCamelCase($value);
	}
}
