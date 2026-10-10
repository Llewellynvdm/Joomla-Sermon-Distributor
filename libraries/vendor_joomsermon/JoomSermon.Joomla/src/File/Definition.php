<?php

/***[JCBGUI.power.licensing_template.1230.$$$$]***/
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

namespace JoomSermon\Joomla\File;


use JoomSermon\Joomla\Utilities\MimeHelper;
use JoomSermon\Joomla\Interfaces\File\DefinitionInterface;


/**
 * File Definition Class
 * 
 * This class represents a **single physical file** on disk and exposes
 * a consistent and immutable API for downstream usage.
 * 
 * It is not:
 * - a database record
 * - a file manager
 * - a transport layer
 * 
 * It **is**:
 * - a file identity
 * - a metadata container
 * - a filesystem reference
 * 
 * Instances of this class are constructed exclusively from upload
 * results produced by the File Agent class.
 * 
 * @since  5.1.4
 */
final class Definition implements DefinitionInterface
{

/***[JCBGUI.power.main_class_code.1230.$$$$]***/
	/**
	 * Original client filename.
	 *
	 * This is the name of the file as submitted by the browser.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $name;

	/**
	 * Server-side filename.
	 *
	 * This is the name used for storage on disk and may contain
	 * a random prefix.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $file_name;

	/**
	 * Random fragment injected during upload (if any).
	 *
	 * This value is retained to expose naming behaviour explicitly to callers.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $random;

	/**
	 * File extension (without dot).
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
	 * Resolved MIME type.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $mime;

	/**
	 * Absolute filesystem path.
	 *
	 * @var   string
	 * @since 5.1.4
	 */
	protected string $file_path;

	/**
	 * Construct a new File object.
	 *
	 * The `$details` array must represent a fully uploaded and
	 * written file as produced by the Handler class.
	 *
	 * Required keys:
	 * - name
	 * - file_name
	 * - full_path
	 *
	 * Optional keys:
	 * - random
	 * - extension
	 * - size
	 * - mime
	 *
	 * @param  array  $details  Upload detail map.
	 *
	 * @throws \InvalidArgumentException  If required fields are missing.
	 * @throws \RuntimeException          If the file does not exist on disk.
	 *
	 * @since  5.1.4
	 */
	public function __construct(array $details)
	{
		foreach (['name','file_name','full_path'] as $key)
		{
			if (empty($details[$key]))
			{
				throw new \InvalidArgumentException("File object missing required key: {$key}");
			}
		}

		if (!is_file($details['full_path']))
		{
			throw new \RuntimeException("File does not exist on disk: {$details['full_path']}");
		}

		$this->name      = (string) $details['name'];
		$this->file_name = (string) $details['file_name'];
		$this->file_path = (string) $details['full_path'];
		$this->random    = (string) ($details['random'] ?? '');

		// Normalize extension from path if missing
		$this->extension = (string) ($details['extension'] ?? MimeHelper::extension($this->file_path));

		// Enforce real file values if omitted
		$this->size = (int) ($details['size'] ?? filesize($this->file_path));
		$this->mime = (string) ($details['mime'] ?? MimeHelper::mimeType($this->file_path));
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
	 * Get random naming fragment (if used).
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function random(): string
	{
		return $this->random;
	}

	/**
	 * Get file size (bytes).
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
	 * Export file information as array.
	 *
	 * @return array<string,mixed>
	 * @since  5.1.4
	 */
	public function toArray(): array
	{
		return [
			'name'      => $this->name,
			'file_name' => $this->file_name,
			'file_path' => $this->file_path,
			'random'    => $this->random,
			'extension' => $this->extension,
			'size'      => $this->size,
			'mime'      => $this->mime
		];
	}/***[/JCBGUI$$$$]***/

}

