<?php

namespace jalsoedesign\filezilla;

/**
 * Class OsUtil - provides the operating system mockable
 *
 * @package jalsoedesign\filezilla
 */
class OsUtil {
	private static ?string $mockedOperatingSystem = null;
	private static array $mockedEnv = [];

	/**
	 * Gets the operating system from PHP_OS or mocked value.
	 *
	 * @return string
	 */
	public static function getOperatingSystem(): string {
		return static::$mockedOperatingSystem ?? PHP_OS;
	}

	/**
	 * Sets a mocked operating system value for testing.
	 *
	 * @param string $os
	 */
	public static function mockOperatingSystem(string $os): void {
		static::$mockedOperatingSystem = $os;
	}

	/**
	 * Clears the mocked operating system.
	 */
	public static function clearMock(): void {
		static::$mockedOperatingSystem = null;
		static::$mockedEnv = [];
	}

	/**
	 * Retrieves the value of a specified environment variable.
	 *
	 * @param string $env The name of the environment variable to retrieve.
	 *
	 * @return string The value of the environment variable, or an empty string if it is not set.
	 */
	public static function getEnv(string $env) : string
	{
		return static::$mockedEnv[$env] ?? '';
	}

	/**
	 * Mocks an environment variable with a given value.
	 *
	 * @param string $env   The name of the environment variable to mock.
	 * @param string $value The value to assign to the mocked environment variable.
	 *
	 * @return void
	 */
	public static function mockEnv(string $env, string $value): void
	{
		static::$mockedEnv[ $env ] = $value;
	}
}
