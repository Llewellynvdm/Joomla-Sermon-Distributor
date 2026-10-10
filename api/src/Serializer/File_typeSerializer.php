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
	@subpackage		File_typeSerializer.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Api\Serializer;

use Joomla\CMS\Serializer\JoomlaSerializer;
use Joomla\CMS\Tag\TagApiSerializerTrait;
use Tobscure\JsonApi\Collection;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * File_type Api Serializer
 *
 * Builds the relationships of the file_type resource, for the item and
 * the list representation alike.
 *
 * @since  4.0.0
 */
class File_typeSerializer extends JoomlaSerializer
{

	/**
	 * Build the created_by relationship.
	 *
	 * @param   \stdClass  $item  The item.
	 *
	 * @return  Relationship
	 *
	 * @since   4.0.0
	 */
	public function createdBy($item)
	{
		// Relate the created_by to the users resource.
		return $this->related($item->created_by ?? null, 'users');
	}

	/**
	 * Build the modified_by relationship.
	 *
	 * @param   \stdClass  $item  The item.
	 *
	 * @return  Relationship
	 *
	 * @since   4.0.0
	 */
	public function modifiedBy($item)
	{
		// Relate the modified_by to the users resource.
		return $this->related($item->modified_by ?? null, 'users');
	}

	/**
	 * Build the relationship to one related resource, or to many when the value holds several ids.
	 *
	 * @param   mixed   $value  The id of the related resource, or the ids of the related resources.
	 * @param   string  $type   The type of the related resource.
	 *
	 * @return  Relationship
	 *
	 * @since   4.0.0
	 */
	protected function related($value, string $type): Relationship
	{
		$serializer = new JoomlaSerializer($type);

		if (is_array($value))
		{
			$resources = [];

			foreach ($value as $id)
			{
				if ($id !== null && $id !== '' && !is_array($id) && !is_object($id))
				{
					$resources[] = new Resource($id, $serializer);
				}
			}

			return new Relationship(new Collection($resources, $serializer));
		}

		return new Relationship(new Resource($value, $serializer));
	}
}
