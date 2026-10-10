<?php

/***[JCBGUI.power.licensing_template.1236.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Sermondistributor\File;


use JoomSermon\Joomla\Sermondistributor\Interfaces\File\TypeDefinitionInterface;


/**
 * File Type Definition Class
 * 
 * Represents a complete file type definition including upload rules, access rules, and metadata.
 * 
 * This class extends the original concept with additional
 *    JCB-driven configuration options such as GUID, access,
 *    quantity limits, and crop settings.
 * 
 * @since  5.1.4
 */
final class TypeDefinition implements TypeDefinitionInterface
{

/***[JCBGUI.power.main_class_code.1236.$$$$]***/
	/**
	 * Unique GUID for this type definition.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $guid;

	/**
	 * Human-readable name of this type.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $name;

	/**
	 * Access level required to upload.
	 *
	 * @var   int
	 * @since 5.1.4
	 */
	protected int $access;

	/**
	 * Maximum quantity allowed (0 allowed, but must be numeric).
	 *
	 * @var   int
	 * @since 5.1.4
	 */
	protected int $quantity;

	/**
	 * Access level required to download.
	 *
	 * @var   int
	 * @since 5.1.4
	 */
	protected int $downloadAccess;

	/**
	 * HTML form upload field name.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $field;

	/**
	 * File type identifier (file, image, media, document).
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $type;

	/**
	 * Upload filter strategy.
	 *
	 * @var   string|null
	 * @since 5.1.4
	 */
	protected ?string $filter;

	/**
	 * Target filesystem path where files are stored.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $path;

	/**
	 * Allowed file extensions.
	 *
	 * @var   array<int,string>
	 * @since 5.1.4
	 */
	protected array $formats;

	/**
	 * Crop configuration for images.
	 *
	 * @var   array
	 * @since 5.1.4
	 */
	protected array $crop;

	/**
	 * Constructor.
	 *
	 * Required keys:
	 * - guid (string)
	 * - name (string)
	 * - access (int)
	 * - quantity (int)
	 * - download_access (int)
	 * - field (string)
	 * - type (string)
	 * - path (string)
	 *
	 * Optional:
	 * - filter (string|null)
	 * - formats (array)
	 * - crop (array)
	 *
	 * @param  array  $config  Full configuration map.
	 *
	 * @throws \InvalidArgumentException
	 * @since  5.1.4
	 */
	public function __construct(array $config)
	{
		$required = [
			'guid',
			'name',
			'access',
			'quantity',
			'download_access',
			'field',
			'type',
			'path',
		];

		$not_empty = [
			'guid',
			'name',
			'field',
			'type',
			'path'
		];

		foreach ($required as $key)
		{
			if (!array_key_exists($key, $config))
			{
				throw new \InvalidArgumentException("Missing type definition config: {$key}");
			}

			if (in_array($key, $not_empty, true) && trim((string) $config[$key]) === '')
			{
				throw new \InvalidArgumentException("Empty value not allowed for config: {$key}");
			}
		}

		$this->guid           = (string) $config['guid'];
		$this->name           = (string) $config['name'];
		$this->access         = (int) $config['access'];
		$this->quantity       = (int) $config['quantity'];
		$this->downloadAccess = (int) $config['download_access'];
		$this->field          = (string) $config['field'];
		$this->type           = (string) $config['type'];
		$this->path           = (string) $config['path'];
		$this->filter         = $config['filter'] ?? null;
		$this->formats        = isset($config['formats']) ? (array) $config['formats'] : [];
		$this->crop           = isset($config['crop']) ? (array) $config['crop'] : [];
	}

	/**
	 * Get GUID.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function guid(): string
	{
		return $this->guid;
	}

	/**
	 * Get human-readable name.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function name(): string
	{
		return $this->name;
	}

	/**
	 * Get upload access level.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function access(): int
	{
		return $this->access;
	}

	/**
	 * Get quantity limit.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function quantity(): int
	{
		return $this->quantity;
	}

	/**
	 * Get download access level.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function downloadAccess(): int
	{
		return $this->downloadAccess;
	}

	/**
	 * Get upload field name.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function field(): string
	{
		return $this->field;
	}

	/**
	 * Get type identifier.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function type(): string
	{
		return $this->type;
	}

	/**
	 * Get filter mode.
	 *
	 * @return string|null
	 * @since  5.1.4
	 */
	public function filter(): ?string
	{
		return $this->filter;
	}

	/**
	 * Get target path.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function path(): string
	{
		return $this->path;
	}

	/**
	 * Get allowed extensions.
	 *
	 * @return array<int,string>
	 * @since  5.1.4
	 */
	public function formats(): array
	{
		return $this->formats;
	}

	/**
	 * Get crop configuration.
	 *
	 * @return array
	 * @since  5.1.4
	 */
	public function crop(): array
	{
		return $this->crop;
	}

	/**
	 * Export the full type definition as an associative array.
	 *
	 * This returns the exact structure used by the constructor,
	 * ensuring it can be safely stored, serialized, or rebuilt.
	 *
	 * @return array  The full configuration map.
	 * @since  5.1.4
	 */
	public function toArray(): array
	{
		return [
			'guid'            => $this->guid,
			'name'            => $this->name,
			'access'          => $this->access,
			'quantity'        => $this->quantity,
			'download_access' => $this->downloadAccess,
			'field'           => $this->field,
			'type'            => $this->type,
			'formats'         => $this->formats,
			'filter'          => $this->filter,
			'path'            => $this->path,
			'crop'            => $this->crop,
		];
	}/***[/JCBGUI$$$$]***/

}

