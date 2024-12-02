<?php

namespace jalsoedesign\filezilla;

/**
 * Class Server
 *
 * @package jalsoedesign\filezilla
 */
abstract class AbstractServerFolderChild
{
	/** @var string The folder path that this child is inside of */
	protected string $folderPath;

	public function __construct(string $folderPath, $properties)
	{
		$this->folderPath = $folderPath;

		$requiredProperties = $this->getRequiredProperties();

		// Ensure required fields are present
		foreach ($requiredProperties as $requiredField) {
			if (!array_key_exists($requiredField, $properties)) {
				throw new \InvalidArgumentException(sprintf('Missing required field: %s', $requiredField));
			}
		}

		// Assign properties
		foreach ($properties as $property => $value) {
			if (!property_exists($this, $property)) {
				throw new \InvalidArgumentException(sprintf('Property %s does not exist', $property));
			}

			$this->{$property} = $value;
		}
	}

	/**
	 * Gets the folder path that this child is inside of
	 *
	 * @return string
	 */
	public function getFolderPath() : string
	{
		return $this->folderPath;
	}

	/**
	 * @return string
	 */
	abstract public function getName() : string;

	/**
	 * @return string[]
	 */
	abstract protected function getRequiredProperties() : array;

	/**
	 * Gets the full server path
	 *
	 * @return string
	 */
	public function getPath(): string
	{
		$folderPath = $this->getFolderPath();

		return (!empty($folderPath) ? $folderPath . '/' : '') . $this->getName();
	}

	/**
	 * Gets the name of the folder the server is inside
	 *
	 * @return ?string
	 */
	public function getFolderName(): ?string
	{
		$path = $this->getFolderPath();
		$pathExplode = explode('/', $path);

		return count($pathExplode) > 0 ? reset($pathExplode) : null;
	}
}
