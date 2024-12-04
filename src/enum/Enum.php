<?php

namespace jalsoedesign\filezilla\enum;

/**
 * Class Enum
 *
 * @package jalsoedesign\filezilla\enum
 */
class Enum {
    /**
     * Gets the name (constant) of a single constant by value
     *
     * @param string|int $value The value to get the constant name from
     * @return string|null
     */
	public static function getConstantName(string|int $value) : ?string {
		$constants = self::getConstants();

		if (array_key_exists($value, $constants)) {
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
     * @param string|int $value The value to get the constant name from
     * @return string|null
     */
	public static function toCamelCase(string|int $value) : ?string {
		$constantName = self::getConstantName($value);

		// No, it's not a weird emoji - it's a regex matching an underscore followed by any character
		return preg_replace_callback('~_(.)~m', function($matches) {
			return strtoupper($matches[1]);
		}, strtolower($constantName));
	}

    /**
     * Get all the constants using ReflectionClass
     *
     * @return array An array of constants from $reflectionClass->getConstants()
     */
	private static function getConstants() : array {
        $reflectionClass = new \ReflectionClass(get_called_class());

        return $reflectionClass->getConstants();
	}
}
