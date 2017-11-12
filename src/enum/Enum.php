<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 06/11/2017
 * Time: 19:47
 */

namespace jalsoedesign\FileZilla\enum;

class Enum {
	private $_constants;

	public static function getConstantName($value) {
		$constants = self::getConstants();

		if (isset($constants[$value])) {
			return $value;
		}

		foreach ($constants as $constant => $constantValue) {
			if ($value === $constantValue) {
				return $constant;
			}
		}

		return null;
	}

	public static function toCamelCase($value) {
		$constantName = self::getConstantName($value);

		// No; it's not a weird emoji - it's an underscore followed by any character
		$constantName = preg_replace_callback('~_(.)~m', function($matches) {
			return strtoupper($matches[1]);
		}, strtolower($constantName));

		return $constantName;
	}

	private static function getConstants() {
		$reflectionClass = new \ReflectionClass(get_called_class());

		return $reflectionClass->getConstants();
	}
}