<?php

/***[JCBGUI.power.licensing_template.836.$$$$]***/
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



/***[JCBGUI.power.head.836.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Router\Route;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\Data\ItemInterface as Item;
use JoomSermon\Joomla\Interfaces\Data\ItemsInterface as Items;


/**
 * File Display Class
 * 
 * @since  5.0.2
 */
final class Display
{

/***[JCBGUI.power.main_class_code.836.$$$$]***/
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
	 * The file site url
	 *
	 * @var   string
	 * @since 5.0.2
	 */
	protected string $url;

	/**
	 * The file types
	 *
	 * @var   array
	 * @since 5.0.2
	 */
	protected array $fileTypes;

	/**
	 * The active user access
	 *
	 * @var    array
	 * @since 5.0.2
	 */
	protected array $access;

	/**
	 * The File Type Task
	 *
	 * @var    array
	 * @since  5.0.2
	 */
	protected array $fileTypeTasks = [1 => 'image' , 2 => 'file' , 3 => 'media', 4 => 'file'];

	/**
	 * Constructor.
	 *
	 * @param Item    $item    The Item Class.
	 * @param Items   $items   The Items Class.
	 *
	 * @since 5.0.2
	 */
	public function __construct(Item $item, Items $items, )
	{
		$this->item = $item;
		$this->items = $items;
		$this->url = rtrim(Uri::root(), '/');

		$user = Factory::getApplication()->getIdentity();
		$this->access = $user->getAuthorisedViewLevels();
	}

	/**
	 * Get the file data that belong to this entity
	 *
	 * @param string $entity  The entity guid
	 * @param string $target  The target entity name
	 *
	 * @return array|null
	 * @since 5.0.2
	 */
	public function get(string $entity, string $target): ?array
	{
		if (($files = $this->items->table('file')->get([$entity], 'entity')) !== null)
		{
			foreach ($files as $n => $file)
			{
				if ($file->entity_type !== $target ||
					!in_array($file->access, $this->access))
				{
					unset($files[$n]);
					continue;
				}
				$this->setFileTypeTask($file);
				$this->setFileTypeName($file);
				$this->setFileDownloadLink($file);
			}

			// If the $files array is empty, return null
			return !empty($files) ? $files : null;
		}

		return null;
	}

	/**
	 * Add the file type name to this file
	 *
	 * @param object $file   The file being updated
	 *
	 * @return void
	 * @since  5.0.2
	 */
	protected function setFileTypeName(object &$file): void
	{
		if (($fileType = $this->getFileType($file->file_type ?? null)) !== null)
		{
			$file->type_name = $fileType->name;
		}
		else
		{
			$file->type_name = 'error';
		}
	}

	/**
	 * Add the file type task to this file
	 *
	 * @param object $file   The file being updated
	 *
	 * @return void
	 * @since  5.0.2
	 */
	protected function setFileTypeTask(object &$file): void
	{
		if (($fileType = $this->getFileType($file->file_type ?? null)) !== null)
		{
			$file->task = $this->getFileTypeTask($fileType);
		}
	}

	/**
	 * Add the file download link
	 *
	 * @param object $file   The file being updated
	 *
	 * @return void
	 * @since  5.0.2
	 */
	protected function setFileDownloadLink(object &$file): void
	{
		if (isset($file->task))
		{
			// Build the query parameters
			$queryParams = [
				'option' => 'com_sermondistributor',
				'controller' => 'download',
				'task' => 'download.' . $file->task,
				'file' => $file->guid,
				'name' => $file->name
			];

			// Build the full URL
			$file->link =  $this->url . Route::_('index.php?' . http_build_query($queryParams));
		}
	}

	/**
	 * Retrieves the file type task name
	 *
	 * @param object  $data   The type data array
	 *
	 * @return string   The field name
	 * @since  5.0.2
	 */
	protected function getFileTypeTask(object $data): string
	{
		$type = $data->type ?? 4;
		if (isset($this->fileTypeTasks[$type]))
		{
			return $this->fileTypeTasks[$type];
		}
		return 'file';
	}

	/**
	 * Retrieves the file type details
	 *
	 * @param string|null $guid   The GUID (Globally Unique Identifier) used as the key to retrieve the file type.
	 *
	 * @return object|null   The item object if found, or null if the item does not exist.
	 * @since  5.0.2
	 */
	protected function getFileType(?string $guid): ?object
	{
		if ($guid === null)
		{
			return null;
		}

		if (isset($this->fileTypes[$guid]))
		{
			return $this->fileTypes[$guid];
		}

		$this->fileTypes[$guid] =  $this->item->table('file_type')->get($guid);

		return $this->fileTypes[$guid];
	}/***[/JCBGUI$$$$]***/

}

