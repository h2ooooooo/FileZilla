<?php

namespace jalsoedesign\filezilla;

/**
 * Class Server
 *
 * @package jalsoedesign\filezilla
 */
class ValueEncoder
{
	/** @var string The source encoding */
	private string $sourceEncoding;

	/**
	 * @param string $sourceEncoding
	 */
	public function __construct(string $sourceEncoding) {
		$this->sourceEncoding = $sourceEncoding;
	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	public function decode(string $value): string {
		$value = trim($value);

		return static::convertEncoding($value, $this->sourceEncoding, 'UTF-8');
	}

	/**
	 * @param string $value
	 *
	 * @return string
	 */
	public function encode(string $value): string {
		$value = trim($value);

		return static::convertEncoding($value, 'UTF-8', $this->sourceEncoding);
	}

	/**
	 * @param string $value
	 * @param string $sourceEncoding
	 * @param string $destinationEncoding
	 *
	 * @return string
	 */
	private static function convertEncoding(string $value, string $sourceEncoding, string $destinationEncoding): string {
		if ($sourceEncoding === $destinationEncoding) {
			return $value;
		}

		if (!function_exists('mb_convert_encoding')) {
			return $value;
		}

		return mb_convert_encoding($value, $sourceEncoding, $destinationEncoding);
	}
}
