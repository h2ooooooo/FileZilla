<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 06/11/2017
 * Time: 19:47
 */

namespace jalsoedesign\FileZilla\enum;

/**
 * Class Enum
 *
 * @package jalsoedesign\FileZilla\enum
 */
class Enum {
    /**
     * Gets the name (constant) of a single constant by value
     *
     * @param mixed $value The value to get the constant name from
     * @return string|null
     *
     * @throws \ReflectionException
     */
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

    /**
     * Gets the camelCase'd name (constant) of a single constant by value
     *
     * @param mixed $value The value to get the constant name from
     * @return string|null
     *
     * @throws \ReflectionException
     */
	public static function toCamelCase($value) {
		$constantName = self::getConstantName($value);

		// No, it's not a weird emoji - it's an underscore followed by any character
		$constantName = preg_replace_callback('~_(.)~m', function($matches) {
			return strtoupper($matches[1]);
		}, strtolower($constantName));

		return $constantName;
	}

    /**
     * Get all the constants using ReflectionClass
     *
     * @return array An array of constants from $reflectionClass->getConstants()
     *
     * @throws \ReflectionException
     */
	private static function getConstants() {
        $reflectionClass = new \ReflectionClass(get_called_class());

        return $reflectionClass->getConstants();
	}
}