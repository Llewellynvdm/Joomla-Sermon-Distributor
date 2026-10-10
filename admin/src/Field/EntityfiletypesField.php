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
	@subpackage		EntityfiletypesField.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Component\ComponentHelper;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Utilities\JsonHelper;
use JoomSermon\Joomla\Utilities\ArrayHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Entityfiletypes Form Field class for the Sermondistributor component
 *
 * @since  1.6
 */
class EntityfiletypesField extends ListField
{
	/**
	 * The entityfiletypes field type.
	 *
	 * @var        string
	 */
	public $type = 'Entityfiletypes';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array    An array of Html options.
	 * @since   1.6
	 */
	protected function getOptions()
	{
		// Get the user object.
		$user = Factory::getApplication()->getIdentity();
		// Get the database object.
		$db = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.guid','a.name','a.target'),array('guid','file_type_name','target')));
		$query->from($db->quoteName('#__sermondistributor_file_type', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.name ASC');
		// Implement View Level Access (if set in table)
		if (!$user->authorise('core.options', 'com_sermondistributor'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$entity = $this->getAttribute('entity');
		$options = [];
		if ($items)
		{
			if ($this->multiple === false)
			{
				$options[] = Html::_('select.option', '', Text::_('COM_SERMONDISTRIBUTOR_SELECT_AN_OPTION'));
			}
			foreach($items as $item)
			{
				if (JsonHelper::check($item->target))
				{
					$item->target = json_decode($item->target, true);
				}
				if (ArrayHelper::check($item->target) && in_array($entity, $item->target))
				{
					$options[] = Html::_('select.option', $item->guid, $item->file_type_name);
				}
			}
		}
		if (count($options) <= 1)
		{
			return [Html::_('select.option', '', Text::sprintf('COM_SERMONDISTRIBUTOR_NO_FILE_TYPES_LINKED_TO_THE_S_AREA', $entity))];
		}
		return $options;
	}
}
