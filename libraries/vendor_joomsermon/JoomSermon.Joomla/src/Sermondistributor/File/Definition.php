<?php

/***[JCBGUI.power.licensing_template.1235.$$$$]***/
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


use JoomSermon\Joomla\Utilities\MimeHelper;
use JoomSermon\Joomla\Sermondistributor\Interfaces\File\DefinitionInterface;


/**
 * File Definition Class
 * 
 * File Definition (Database + Filesystem Hybrid).
 * 
 * This class extends the behaviour of the original temporary-file Definition
 *    by supporting database-driven fields while preserving the interface and
 *    behaviour of the base Definition class.
 * 
 * @since  5.1.4
 */
final class Definition implements DefinitionInterface
{

/***[JCBGUI.power.main_class_code.1235.$$$$]***/
	/**
	 * Original client filename.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $name;

	/**
	 * Filesystem filename (derived from file_path).
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $file_name;

	/**
	 * Random naming fragment (unused in DB mode).
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $random = '';

	/**
	 * File extension.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $extension;

	/**
	 * File size in bytes.
	 *
	 * @var   int
	 * @since 5.1.4
	 */
	protected int $size;

	/**
	 * MIME type.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $mime;

	/**
	 * Full absolute filesystem path.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $file_path;

	/**
	 * File type identifier.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $file_type;

	/**
	 * Entity type name.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $entity_type;

	/**
	 * Entity identifier.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $entity;

	/**
	 * Access level.
	 *
	 * @var   int
	 * @since 5.1.4
	 */
	protected int $access;

	/**
	 * Globionic GUID.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $guid;

	/**
	 * User ID of creator.
	 *
	 * @var   int
	 * @since 5.1.4
	 */
	protected int $created_by;

	/**
	 * Construct a Definition object using database-loaded metadata.
	 *
	 * @param  array  $details  File + DB record metadata.
	 *
	 * @throws \InvalidArgumentException
	 * @throws \RuntimeException
	 *
	 * @since  5.1.4
	 */
	public function __construct(array $details)
	{
		$required = [
			'name',
			'file_type',
			'file_path',
			'entity_type',
			'entity',
			'access',
			'guid',
			'created_by'
		];

		foreach ($required as $key)
		{
			if (!isset($details[$key]) || $details[$key] === '')
			{
				throw new \InvalidArgumentException("Definition missing required key: {$key}");
			}
		}

		$this->name      = (string) $details['name'];
		$this->file_path = (string) $details['file_path'];
		$this->file_name = basename($this->full_path);

		if (!is_file($this->file_path))
		{
			throw new \RuntimeException("File does not exist on disk: {$this->file_path}");
		}

		$this->extension = (string) ($details['extension'] ?? MimeHelper::extension($this->file_path));
		$this->size      = (int)    ($details['size']      ?? filesize($this->file_path));
		$this->mime      = (string) ($details['mime']      ?? MimeHelper::mimeType($this->file_path));

		$this->file_type   = (string) $details['file_type'];
		$this->entity_type = (string) $details['entity_type'];
		$this->entity      = (string) $details['entity'];
		$this->access      = (int)    $details['access'];
		$this->guid        = (string) $details['guid'];
		$this->created_by  = (int)    $details['created_by'];
	}

	/**
	 * Get original client filename.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function name(): string
	{
		return $this->name;
	}

	/**
	 * Get filesystem filename.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function fileName(): string
	{
		return $this->file_name;
	}

	/**
	 * Get file extension.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function extension(): string
	{
		return $this->extension;
	}

	/**
	 * Get random naming fragment.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function random(): string
	{
		return $this->random;
	}

	/**
	 * Get file size.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function size(): int
	{
		return $this->size;
	}

	/**
	 * Get MIME type.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function mime(): string
	{
		return $this->mime;
	}

	/**
	 * Get absolute file path.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function filePath(): string
	{
		return $this->file_path;
	}

	/**
	 * Get file type identifier.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function fileType(): string
	{
		return $this->file_type;
	}

	/**
	 * Get entity type.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function entityType(): string
	{
		return $this->entity_type;
	}

	/**
	 * Get entity reference.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function entity(): string
	{
		return $this->entity;
	}

	/**
	 * Get access level.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function access(): int
	{
		return $this->access;
	}

	/**
	 * Get Globionic GUID.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function guid(): string
	{
		return $this->guid;
	}

	/**
	 * Get creator ID.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function createdBy(): int
	{
		return $this->created_by;
	}

	/**
	 * Export full file metadata as array.
	 *
	 * @return array<string,mixed>
	 * @since  5.1.4
	 */
	public function toArray(): array
	{
		return [
			// Base inherited fields
			'name'        => $this->name,
			'file_name'   => $this->file_name,
			'file_path'   => $this->file_path,
			'random'      => $this->random,
			'extension'   => $this->extension,
			'size'        => $this->size,
			'mime'        => $this->mime,

			// Database-driven metadata
			'file_type'   => $this->file_type,
			'entity_type' => $this->entity_type,
			'entity'      => $this->entity,
			'access'      => $this->access,
			'guid'        => $this->guid,
			'created_by'  => $this->created_by
		];
	}/***[/JCBGUI$$$$]***/

}

