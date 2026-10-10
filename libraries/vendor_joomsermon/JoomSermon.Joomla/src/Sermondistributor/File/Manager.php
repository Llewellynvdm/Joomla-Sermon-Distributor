<?php

/***[JCBGUI.power.licensing_template.835.$$$$]***/
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



/***[JCBGUI.power.head.835.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\CMS\User\User;
use Joomla\CMS\Language\Text;
use Joomla\Filesystem\File;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\Data\ItemInterface as Item;
use JoomSermon\Joomla\Interfaces\Data\ItemsInterface as Items;
use JoomSermon\Joomla\Sermondistributor\File\Type;
use JoomSermon\Joomla\Interfaces\File\AgentInterface as Agent;
use JoomSermon\Joomla\Sermondistributor\File\Image;
use JoomSermon\Joomla\Sermondistributor\File\Definition as Definition;
use JoomSermon\Joomla\File\Definition as FileDefinition;
use JoomSermon\Joomla\Sermondistributor\Interfaces\File\DefinitionInterface as FileDefinitionInterface;
use JoomSermon\Joomla\Interfaces\File\DefinitionInterface as FileInterface;
use JoomSermon\Joomla\Sermondistributor\Interfaces\File\TypeDefinitionInterface as TypeDefinition;
use JoomSermon\Joomla\Data\Guid;
use JoomSermon\Joomla\Utilities\MimeHelper;
use JoomSermon\Joomla\Interfaces\File\PersistentManagerInterface;


/**
 * File Manager Class
 * 
 * @since  5.0.2
 */
class Manager implements PersistentManagerInterface
{

/***[JCBGUI.power.main_class_code.835.$$$$]***/
	/**
	 * The Globally Unique Identifier.
	 *
	 * @since 5.0.2
	 */
	use Guid;

	/**
	 * The Item Class.
	 *
	 * @var   Item
	 * @since 5.0.2
	 */
	protected Item $item;

	/**
	 * The Items Class.
	 *
	 * @var   Items
	 * @since 5.0.2
	 */
	protected Items $items;

	/**
	 * The Type Class.
	 *
	 * @var   Type
	 * @since 5.0.2
	 */
	protected Type $type;

	/**
	 * The Agent Class.
	 *
	 * @var   Agent
	 * @since 5.1.4
	 */
	protected Agent $agent;

	/**
	 * The Image Class.
	 *
	 * @var   Image
	 * @since 5.1.1
	 */
	protected Image $image;

	/**
	 * The active user
	 *
	 * @var    User
	 * @since 5.0.2
	 */
	protected User $user;

	/**
	 * Table Name
	 *
	 * @var    string
	 * @since 5.0.2
	 */
	protected string $table = 'file';

	/**
	 * Constructor.
	 *
	 * @param Item   $item   The Item Class.
	 * @param Items  $items  The Items Class.
	 * @param Type   $type   The Type Class.
	 * @param Agent  $agent  The Agent Class.
	 * @param Image  $image  The Image Class.
	 *
	 * @since 5.0.2
	 */
	public function __construct(Item $item, Items $items, Type $type, Agent $agent,
		Image $image)
	{
		$this->item = $item;
		$this->items = $items;
		$this->type = $type;
		$this->agent = $agent;
		$this->image = $image;
		$this->user = Factory::getApplication()->getIdentity();
	}

	/**
	 * Upload a file, of a given file type and link it to an entity.
	 *
	 * @param string $guid    The file type guid
	 * @param string $entity  The entity guid
	 * @param string $target  The target entity name
	 *
	 * @return void
	 * @throws \InvalidArgumentException If the file type is not valid.
	 * @throws \RuntimeException If there is an error during file upload.
	 * @since 5.0.2
	 */
	public function upload(string $guid, string $entity, string $target): void
	{
		if (($typeDefinition = $this->type->definition($guid, $target)) === null)
		{
			throw new \InvalidArgumentException(Text::sprintf('COM_SERMONDISTRIBUTOR_FILE_TYPE_NOT_VALID_IN_S_AREA', $target));
		}

		// make sure the user have permissions to upload this file type
		if (!in_array($typeDefinition->access(), $this->user->getAuthorisedViewLevels()))
		{
			throw new \InvalidArgumentException(Text::sprintf('COM_SERMONDISTRIBUTOR_YOU_DO_NOT_HAVE_PERMISSIONS_TO_UPLOAD_S', $typeDefinition->name()));
		}

		$fileDefinition = $this->agent->type($typeDefinition)->get();

		if ($typeDefinition->type() === 'image' && !empty($typeDefinition->crop()))
		{
			$this->processImages($fileDefinition, $guid, $entity, $target, $typeDefinition);
		}
		else
		{
			// store file in the file table
			$this->item->table($this->getTable())->set(
				$this->modelFileDefinition($fileDefinition, $guid, $entity, $target, $typeDefinition)
			);
		}

		$this->limitFileType($typeDefinition, $guid, $entity, $target);
	}

	/**
	 * Get the file definition
	 *
	 * @param string $guid The file guid
	 *
	 * @return FileDefinitionInterface|null
	 * @since 5.1.4
	 */
	public function definition(string $guid): ?FileDefinitionInterface
	{
		if (($file = $this->item->table($this->getTable())->get($guid)) !== null &&
			in_array($file->access, $this->user->getAuthorisedViewLevels()))
		{
			return new Definition((array) $file);
		}

		return null;
	}

	/**
	 * Get the file definition as array
	 *
	 * @param string $guid The file guid
	 *
	 * @return array|null
	 * @since 5.0.2
	 *
	 * @deprecated 5.1.4 Use $this->definition(...); 
	 * @removal x.2  (means 4.2, 5.2 , 6.2 if JCB)
	 */
	public function download(string $guid): ?array
	{
		if (($file = $this->item->table($this->getTable())->get($guid)) !== null &&
			in_array($file->access, $this->user->getAuthorisedViewLevels()))
		{
			return (array) $file;
		}

		return null;
	}

	/**
	 * Delete a file.
	 *
	 * @param string $guid  The file guid
	 *
	 * @return void
	 * @since 5.0.2
	 */
	public function delete(string $guid): void
	{
		if (($file = $this->item->table($this->getTable())->get($guid)) !== null &&
			in_array($file->access, $this->user->getAuthorisedViewLevels()))
		{
			$this->item->table($this->getTable())->delete($guid); // from DB
			$this->agent->delete($file->file_path); // from file system
		}
	}

	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return self
	 * @since  5.0.2
	 */
	public function table(string $table): self
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since   5.0.2
	 */
	public function getTable(): string
	{
		return $this->table;
	}

	/**
	 * Process the image(s) as needed based on crop settings
	 *
	 * @param FileInterface  $fileDefinition   The uploaded file details.
	 * @param string         $guid             The file type guid
	 * @param string         $entity           The entity guid
	 * @param string         $target           The target entity name
	 * @param TypeDefinition $typeDefinition   The file type
	 *
	 * @return void
	 * @since  5.1.1
	 */
	protected function processImages(FileInterface $fileDefinition, string $guid, string $entity, string $target, TypeDefinition $typeDefinition): void
	{
		$source = $fileDefinition->filePath();
		$details = $fileDefinition->toArray();
		$path = $typeDefinition->path();
		$cropping = $typeDefinition->crop();

		$placeholders = [
			'{number}' => $this->getFileNumber($typeDefinition, $entity),
			'{name}' => $this->getFileName($fileDefinition, $entity),
			'{extension}' => $fileDefinition->extension(),
			'{random}' => $this->getRandomFileName($entity)
		];

		foreach ($cropping as &$crop)
		{
			$crop['name'] = str_replace(array_keys($placeholders), array_values($placeholders), $crop['name']);
		}
		unset($crop);

		$images = $this->image->process($source, $path, $cropping);

		foreach($images as $image)
		{
			if (empty($image))
			{
				continue;
			}

			$details['name'] = $image['name'];
			$details['extension'] = $image['extension'];
			$details['size'] = $image['size'];
			$details['mime'] = $image['mime'];
			$details['full_path'] = $image['path'];

			$newFileDefinition = new FileDefinition($details);

			// store file in the file table
			$this->item->table($this->getTable())->set(
				$this->modelFileDefinition($newFileDefinition, $guid, $entity, $target, $typeDefinition)
			);
		}

		// clean up source image
		$this->agent->delete($source);
	}

	/**
	 * model the file definition to store in the file table
	 *
	 * @param FileInterface  $fileDefinition  The uploaded file details.
	 * @param string         $guid            The file type guid
	 * @param string         $entity          The entity guid
	 * @param string         $target          The target entity name
	 * @param TypeDefinition $typeDefinition  The file type
	 *
	 * @return object
	 * @since 5.1.4
	 */
	protected function modelFileDefinition(FileInterface $fileDefinition, string $guid, string $entity, string $target, TypeDefinition $typeDefinition): object
	{
		return (object) [
			'name' => $fileDefinition->name(),
			'file_type' => $guid,
			'extension' => $fileDefinition->extension(),
			'size' => $fileDefinition->size(),
			'mime' => $fileDefinition->mime(),
			'file_path' => $fileDefinition->filePath(),
			'entity_type' => $target,
			'entity' => $entity,
			'access' => $typeDefinition->downloadAccess(),
			'guid' => $this->getGuid('guid'),
			'created_by' => $this->user->id
		];
	}

	/**
	 * Get the file name without extension.
	 *
	 * If the original name is empty, return the entity GUID.
	 * If the name does not contain a '.', return the name as is.
	 * Otherwise, return the name without the final extension.
	 *
	 * @param   FileInterface  $fileDefinition  The uploaded file details.
	 * @param   string         $entity          The entity GUID used as fallback.
	 *
	 * @return  string  The extracted or fallback file name.
	 * @since   5.1.1
	 */
	protected function getFileName(FileInterface $fileDefinition, string $entity): string
	{
		// Check if name is set and non-empty
		$name = trim($fileDefinition->name());

		// Return entity if name is empty
		if ($name === '')
		{
			return $entity;
		}

		// If there is no dot in the name, assume no extension — return as-is
		if (strpos($name, '.') === false)
		{
			return $name;
		}

		// Use pathinfo to extract the name without extension
		$info = pathinfo($name);

		// Return filename (without extension)
		return $info['filename'] ?? $name;
	}

	/**
	 * Get the file number.
	 *
	 * NOTE:
	 * This logic assumes files are append-only.
	 * Deletions will cause numbering inconsistencies.
	 *
	 * @param  TypeDefinition  $typeDefinition  The uploaded file type definition.
	 * @param  string          $entity          The entity GUID.
	 *
	 * @return int
	 * @since  5.1.1
	 */
	protected function getFileNumber(TypeDefinition $typeDefinition, string $entity): int
	{
		$cropConfig = $typeDefinition->crop();

		if (empty($cropConfig))
		{
			return 1;
		}

		$cropCount = count($cropConfig);
		$fileNumber = 1;

		$files = $this->items
			->table($this->getTable())
			->values([$entity], 'entity');

		if ($files !== null)
		{
			$total = count($files);

			if ($total >= $cropCount)
			{
				$fileNumber = intdiv($total, $cropCount);
			}

			return ++$fileNumber;
		}

		return $fileNumber;
	}

	/**
	 * Generate a unique random-like 12-character string for a given GUID.
	 *
	 * Guarantees:
	 * - The same GUID will *never* produce the same value twice, even across executions.
	 * - Different GUIDs will never collide (practically impossible).
	 * - Safe alphanumeric output (A-Z, a-z, 0-9).
	 * - Lightweight, stateless, and reproducible randomness within one call.
	 *
	 * @param   string  $guid  The entity GUID.
	 *
	 * @return  string  A unique 12-character random-like string.
	 * @since   5.1.1
	 */
	protected function getRandomFileName(string $guid): string
	{
		// Combine GUID with microtime (ensures uniqueness across calls)
		$entropy = $guid . '-' . microtime(true) . '-' . random_int(PHP_INT_MIN, PHP_INT_MAX);

		// Create a cryptographic hash
		$hash = hash('sha256', $entropy, true);

		// Convert to safe characters and shorten to 5 chars
		$base62 = rtrim(strtr(base64_encode($hash), '+/', 'AZ'), '=');

		return substr($base62, 1, 13);
	}

	/**
	 * Enforces a file-count limit per entity and removes oldest excess files.
	 * Also validates crop consistency for images.
	 *
	 * @param  TypeDefinition   $typeDefinition  The uploaded file-type details.
	 * @param  string           $type            The file-type GUID fallback.
	 * @param  string           $entity          The entity GUID.
	 * @param  string           $target          The entity target.
	 *
	 * @return void
	 * @since   5.1.4
	 */
	protected function limitFileType(TypeDefinition $typeDefinition, string $type, string $entity, string $target): void
	{
		$limit = $typeDefinition->quantity() ?? 0;
		if ($limit <= 0)
		{
			return;
		}

		$fileTypeGuid = $typeDefinition->guid() ?? '';
		if ($fileTypeGuid === '')
		{
			return;
		}

		$isImage   = false;
		$cropCount = 1;
		$crop = $typeDefinition->crop() ?? null;
		$fileType = $typeDefinition->type() ?? '';

		// Handle image type with crops
		if ($fileType === 'image' && !empty($crop))
		{
			$isImage = true;
			$cropCount = (int) \count($crop);
			$limit *= $cropCount;
		}

		$this->applyFileLimit($fileTypeGuid, $entity, $target, $limit, $isImage, $cropCount);
	}

	/**
	 * Applies the file limit logic and verifies crop consistency.
	 *
	 * @param  string  $fileTypeGuid  The file-type GUID.
	 * @param  string  $entity        The entity GUID.
	 * @param  string  $target        The entity target.
	 * @param  int     $limit         Maximum allowed files.
	 * @param  bool    $isImage       Whether this file type is an image.
	 * @param  int     $cropCount     Crop variant count for images.
	 *
	 * @return void
	 * @since   5.1.4
	 */
	private function applyFileLimit(
		string $fileTypeGuid,
		string $entity,
		string $target,
		int $limit,
		bool $isImage = false,
		int $cropCount = 1
	): void
	{
		if ($limit <= 0)
		{
			return;
		}

		// Retrieve all files for this entity
		$files = $this->items->table($this->getTable())->get([$entity], 'entity') ?? [];
		if (!$files)
		{
			return;
		}

		// Filter by matching type & target
		$targetFiles = [];
		foreach ($files as $file)
		{
			if (($file->file_type ?? null) === $fileTypeGuid && ($file->entity_type ?? null) === $target)
			{
				$targetFiles[] = $file;
			}
		}

		$total = \count($targetFiles);
		if ($total === 0)
		{
			return;
		}

		$table = $this->getTable();

		/**
		 * Crop integrity check:
		 *   If this is an image type and cropCount > 1,
		 *   verify that total files divide evenly by cropCount.
		 *   If not divisible, remove all old files and keep just the last uploaded version.
		 */
		if ($isImage && $cropCount > 1 && ($total % $cropCount) !== 0)
		{
			$limit = $cropCount;
		}

		// Standard limit enforcement if count exceeds the allowed number
		if ($total > $limit)
		{
			$oldest = $this->extractOldestFiles($targetFiles, $limit);
		}

		if (!$oldest)
		{
			return;
		}

		foreach ($oldest as $delete)
		{
			$guid = $delete->guid ?? null;
			$path = $delete->file_path ?? null;

			if ($guid)
			{
				$this->item->table($table)->delete($guid);
			}

			if ($path && \is_file($path))
			{
				File::delete($path);
			}
		}
	}

	/**
	 * Returns the oldest files exceeding the desired quantity.
	 *
	 * @param  array  $files     File objects containing a 'created' property.
	 * @param  int    $quantity  Desired number of items to remain.
	 *
	 * @return array<int,object>  The oldest files to remove.
	 * @since  5.1.4
	 */
	protected function extractOldestFiles(array $files, int $quantity): array
	{
		$count = \count($files);
		if ($count === 0 || $count <= $quantity)
		{
			return [];
		}

		// Inline timestamp collection for maximum speed
		$withTimestamps = [];
		foreach ($files as $file)
		{
			if (!empty($file->created))
			{
				$ts = \strtotime($file->created);
				if ($ts !== false)
				{
					$withTimestamps[] = [$ts, $file];
				}
			}
		}

		$total = \count($withTimestamps);
		if ($total === 0)
		{
			return [];
		}

		// Sort oldest → newest (integer compare)
		\usort($withTimestamps, static fn($a, $b) => $a[0] <=> $b[0]);

		$toRemove = $total - $quantity;
		if ($toRemove <= 0)
		{
			return [];
		}

		// Slice oldest segment and extract objects
		$oldest = [];
		foreach (\array_slice($withTimestamps, 0, $toRemove) as $pair)
		{
			$oldest[] = $pair[1];
		}

		return $oldest;
	}/***[/JCBGUI$$$$]***/

}

