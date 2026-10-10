<?php

/***[JCBGUI.power.licensing_template.980.$$$$]***/
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



/***[JCBGUI.power.head.980.$$$$]***/
use Joomla\CMS\Image\Image as JoomlaImage;
use Joomla\CMS\Log\Log;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\MimeHelper;


/**
 * Image Class
 * 
 * @since  5.1.1
 */
final class Image
{

/***[JCBGUI.power.main_class_code.980.$$$$]***/
	/**
	 * Process one image into multiple dimensioned versions.
	 *
	 * @param   string  $source         Full path to source image.
	 * @param   string  $destinationDir Destination folder (will be created if missing).
	 * @param   array   $dimensions     Format: [['name' => 'thumb.jpg', 'width' => 100, 'height' => 100], ...]
	 *
	 * @return  array  Result array: ['thumb.jpg' => [...metadata...], 'invalid.jpg' => null, ...]
	 * @since   5.1.1
	 */
	public function process(string $source, string $destinationDir, array $dimensions): array
	{
		$results = [];

		foreach ($dimensions as $set)
		{
			if (
				!isset($set['name'], $set['width'], $set['height']) ||
				!is_numeric($set['width']) ||
				!is_numeric($set['height'])
			)
			{
				$results[$set['name'] ?? 'unknown'] = null;
				continue;
			}

			$outputPath = rtrim($destinationDir, DIRECTORY_SEPARATOR)
				. DIRECTORY_SEPARATOR . $set['name'];

			$results[$set['name']] = $this->cropResize(
				$source,
				$outputPath,
				(int) $set['width'],
				(int) $set['height']
			);
		}

		return $results;
	}

	/**
	 * Crop or scale an image to target size using center crop or just resize if aspect ratio matches.
	 *
	 * @param   string  $source      Full absolute path to source image.
	 * @param   string  $destination Full absolute path to destination image.
	 * @param   int     $targetW     Target width
	 * @param   int     $targetH     Target height
	 *
	 * @return  array|null  Image metadata on success, false on failure.
	 * @since   5.1.1
	 */
	public function cropResize(string $source, string $destination, int $targetW, int $targetH): ?array
	{
		try
		{
			if (!is_file($source))
			{
				throw new \RuntimeException("Source image not found: $source");
			}

			$destFolder = dirname($destination);
			if (!is_dir($destFolder))
			{
				Folder::create($destFolder);
			}

			$image = new JoomlaImage($source);

			if (!$image->isLoaded())
			{
				throw new \RuntimeException("Failed to load image: $source");
			}

			$originalW = $image->getWidth();
			$originalH = $image->getHeight();

			// If already correct size, copy directly
			if ($originalW === $targetW && $originalH === $targetH)
			{
				File::copy($source, $destination);
			}
			else
			{
				// Perform crop-resize directly
				$image = $image->cropResize($targetW, $targetH, true);
				$type = $this->getImageType($source);

				if ($type === null || !$image->toFile($destination, $type))
				{
					throw new \RuntimeException("Failed to save image to $destination");
				}
			}

			// Return metadata
			return [
				'name'      => basename($destination),
				'extension' => MimeHelper::Extension($destination),
				'size'      => is_file($destination) ? filesize($destination) : 0,
				'mime'      => MimeHelper::MimeType($destination),
				'path'      => $destination,
			];
		}
		catch (\Throwable $e)
		{
			Log::add($e->getMessage(), Log::ERROR, 'image-cropper');
			return null;
		}
	}

	/**
	 * Retrieve image metadata information from a given path.
	 *
	 * Supported requests:
	 * - type   : Image extension (jpg, png, gif, etc.)
	 * - width  : Image width in pixels
	 * - height : Image height in pixels
	 * - attr   : HTML width/height attribute string
	 * - all    : Full getimagesize() result array
	 *
	 * @param  string  $path     Relative or absolute image path.
	 * @param  string  $request  Requested information type.
	 *
	 * @return mixed  Requested data or false if unavailable.
	 * @since  5.1.4
	 */
	public function info(string $path, string $request = 'type')
	{
		$image = $this->normalizePath($path);

		if (!is_file($image))
		{
			return false;
		}

		$result = getimagesize($image);

		if ($result === false)
		{
			return false;
		}

		switch ($request)
		{
			case 'width':
				return $result[0];

			case 'height':
				return $result[1];

			case 'type':
				return $this->resolveImageExtension($result[2]);

			case 'attr':
				return $result[3] ?? null;

			case 'all':
			default:
				return $result;
		}
	}

	/**
	 * Resolve image extension from IMAGETYPE constant.
	 *
	 * @param  int  $type
	 *
	 * @return string|null
	 * @since  5.1.4
	 */
	protected function resolveImageExtension(int $type): ?string
	{
		static $map = [
			IMAGETYPE_GIF      => 'gif',
			IMAGETYPE_JPEG    => 'jpg',
			IMAGETYPE_PNG     => 'png',
			IMAGETYPE_BMP     => 'bmp',
			IMAGETYPE_TIFF_II => 'tiff',
			IMAGETYPE_TIFF_MM => 'tiff',
			IMAGETYPE_ICO     => 'ico',
			IMAGETYPE_WEBP    => 'webp',
		];

		return $map[$type] ?? null;
	}

	/**
	 * Get the image type constant from the file path
	 *
	 * @param  string  $path  Absolute path to the image file
	 *
	 * @return int|null  Returns the IMAGETYPE_* constant or null if undetectable
	 * @since  5.1.1
	 */
	protected function getImageType(string $path): ?int
	{
		// Use exif_imagetype to get the constant
		$type = @exif_imagetype($path);

		// Validate it's a known IMAGETYPE
		return is_int($type) ? $type : null;
	}

	/**
	 * Normalize relative or absolute paths.
	 *
	 * @param  string  $path
	 *
	 * @return string
	 * @since  5.1.4
	 */
	protected function normalizePath(string $path): string
	{
		return str_starts_with($path, JPATH_SITE)
			? $path
			: JPATH_SITE . '/' . ltrim($path, '/');
	}/***[/JCBGUI$$$$]***/

}

