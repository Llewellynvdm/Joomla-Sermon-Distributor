<?php
/*-------------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
 ____                                                  ____                 __               __               __
/\  _`\                                               /\  _`\   __         /\ \__         __/\ \             /\ \__
\ \,\L\_\     __   _ __    ___ ___     ___     ___    \ \ \/\ \/\_\    ____\ \ ,_\  _ __ /\_\ \ \____  __  __\ \ ,_\   ___   _ __
 \/_\__ \   /'__`\/\`'__\/' __` __`\  / __`\ /' _ `\   \ \ \ \ \/\ \  /',__\\ \ \/ /\`'__\/\ \ \ '__`\/\ \/\ \\ \ \/  / __`\/\`'__\
   /\ \L\ \/\  __/\ \ \/ /\ \/\ \/\ \/\ \L\ \/\ \/\ \   \ \ \_\ \ \ \/\__, `\\ \ \_\ \ \/ \ \ \ \ \L\ \ \ \_\ \\ \ \_/\ \L\ \ \ \/
   \ `\____\ \____\\ \_\ \ \_\ \_\ \_\ \____/\ \_\ \_\   \ \____/\ \_\/\____/ \ \__\\ \_\  \ \_\ \_,__/\ \____/ \ \__\ \____/\ \_\
    \/_____/\/____/ \/_/  \/_/\/_/\/_/\/___/  \/_/\/_/    \/___/  \/_/\/___/   \/__/ \/_/   \/_/\/___/  \/___/   \/__/\/___/  \/_/

/------------------------------------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@created		9th July, 2026
	@package		Sermon Distributor
	@subpackage		JsonapiView.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Api\View\File_types;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\View\JsonApiView as BaseApiView;
use Joomla\Registry\Registry;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Component\Sermondistributor\Api\Serializer\File_typeSerializer;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor Json View class for the File_types
 *
 * @since  4.0.0
 */
class JsonapiView extends BaseApiView
{
	/**
	 * The fields to render items in the documents
	 *
	 * @var    array
	 * @since  4.0.0
	 */
	protected $fieldsToRenderList = [
		'id',
		'name',
		'type',
		'path',
		'download_access',
		'quantity',
		'guid',
		'target',
		'crop',
		'file_formats',
		'document_formats',
		'media_formats',
		'image_formats',
		'created',
		'created_by',
		'modified',
		'modified_by',
		'published',
		'ordering',
		'access',
		'version',
		'hits',
		'metakey',
		'metadesc',
		'metadata',
	];

	/**
	 * The relationships the items have
	 *
	 * @var    array
	 * @since  4.0.0
	 */
	protected $relationship = [
		'created_by',
		'modified_by',
	];

	/**
	 * Constructor.
	 *
	 * @param   array  $config  A named configuration array for object construction.
	 *                          contentType: the name (optional) of the content type to use for the serialization
	 *
	 * @since   4.0.0
	 */
	public function __construct($config = [])
	{
		if (\array_key_exists('contentType', $config))
		{
			$this->serializer = new File_typeSerializer($config['contentType']);
		}

		parent::__construct($config);
	}

	/**
	 * Execute and display a template script.
	 *
	 * @param   ?array  $items  Array of items
	 *
	 * @return  string
	 *
	 * @since   4.0.0
	 */
	public function displayList(?array $items = null)
	{

		return parent::displayList($items);
	}

	/**
	 * Prepare item before render.
	 *
	 * @param   object  $item  The model item
	 *
	 * @return  object
	 *
	 * @since   4.0.0
	 */
	protected function prepareItem($item)
	{
		if (isset($item->target) && is_string($item->target) && $item->target !== '')
		{
			// Convert the target field to an array.
			$registry = new Registry;
			$registry->loadString($item->target);
			$item->target = $registry->toArray();
		}

		if (isset($item->crop) && is_string($item->crop) && $item->crop !== '')
		{
			// Convert the crop field to an array.
			$registry = new Registry;
			$registry->loadString($item->crop);
			$item->crop = $registry->toArray();
		}

		if (isset($item->file_formats) && is_string($item->file_formats) && $item->file_formats !== '')
		{
			// Convert the file_formats field to an array.
			$registry = new Registry;
			$registry->loadString($item->file_formats);
			$item->file_formats = $registry->toArray();
		}

		if (isset($item->document_formats) && is_string($item->document_formats) && $item->document_formats !== '')
		{
			// Convert the document_formats field to an array.
			$registry = new Registry;
			$registry->loadString($item->document_formats);
			$item->document_formats = $registry->toArray();
		}

		if (isset($item->media_formats) && is_string($item->media_formats) && $item->media_formats !== '')
		{
			// Convert the media_formats field to an array.
			$registry = new Registry;
			$registry->loadString($item->media_formats);
			$item->media_formats = $registry->toArray();
		}

		if (isset($item->image_formats) && is_string($item->image_formats) && $item->image_formats !== '')
		{
			// Convert the image_formats field to an array.
			$registry = new Registry;
			$registry->loadString($item->image_formats);
			$item->image_formats = $registry->toArray();
		}

		return parent::prepareItem($item);
	}
}
