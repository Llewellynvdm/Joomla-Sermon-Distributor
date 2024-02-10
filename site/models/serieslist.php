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

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		serieslist.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Helper\TagsHelper;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\JsonHelper;

/**
 * Sermondistributor List Model for Serieslist
 */
class SermondistributorModelSerieslist extends ListModel
{
	/**
	 * Model user data.
	 *
	 * @var        strings
	 */
	protected $user;
	protected $userId;
	protected $guest;
	protected $groups;
	protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		// Get the current user for authorisation checks
		$this->user = Factory::getUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->app = Factory::getApplication();
		$this->input = $this->app->input;
		$this->initSet = true; 
		// Get a db connection.
		$db = Factory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_series as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.description','a.icon','a.hits','a.ordering'),
			array('id','asset_id','name','alias','description','icon','hits','ordering')));
		$query->from($db->quoteName('#__sermondistributor_series', 'a'));
		$query->where('a.access IN (' . implode(',', $this->levels) . ')');
		// Get where a.published is 1
		$query->where('a.published = 1');
		$query->order('a.ordering ASC');

		// return the query object
		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
		$user = Factory::getUser();
		// check if this user has permission to access item
		if (!$user->authorise('site.serieslist.access', 'com_sermondistributor'))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_NOT_AUTHORISED_TO_VIEW_SERIESLIST'), 'error');
			// redirect away to the default view if no access allowed.
			$app->redirect(Route::_('index.php?option=com_sermondistributor&view=preachers'));
			return false;
		}
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = ComponentHelper::getParams('com_sermondistributor', true);

		// Insure all item fields are adapted where needed.
		if (UtilitiesArrayHelper::check($items))
		{
			// Load the JEvent Dispatcher
			PluginHelper::importPlugin('content');
			$this->_dispatcher = Factory::getApplication();
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = ($item->id ?? '0') . (isset($item->alias) ? ':' . $item->alias : '');
				// Check if item has params, or pass whole item.
				$params = (isset($item->params) && JsonHelper::check($item->params)) ? json_decode($item->params) : $item;
				// Make sure the content prepare plugins fire on description
				$_description = new \stdClass();
				$_description->text =& $item->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->triggerEvent("onContentPrepare", array('com_sermondistributor.series.description', &$_description, &$params, 0));
				// Checking if description has uikit components that must be loaded.
				$this->uikitComp = SermondistributorHelper::getUikitComp($item->description,$this->uikitComp);
				// set idSeriesSermonB to the $item object.
				$item->idSeriesSermonB = $this->getIdSeriesSermonBcae_B($item->id);
			}
		}


		if (UtilitiesArrayHelper::check($items))
		{
			foreach ($items as $nr => &$item)
			{
				if (!UtilitiesArrayHelper::check($item->idSeriesSermonB))
				{
					// remove empty series
					unset($items[$nr]);
				}
			}
		}

		// return items
		return $items;
	}

	/**
	 * Method to get an array of Sermon Objects.
	 *
	 * @return mixed  An array of Sermon Objects on success, false on failure.
	 *
	 */
	public function getIdSeriesSermonBcae_B($id)
	{
		// Get a db connection.
		$db = Factory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_sermon as b
		$query->select($db->quoteName(
			array('b.id'),
			array('id')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'b'));
		$query->where('b.series = ' . $db->quote($id));
		$query->where('b.access IN (' . implode(',', $this->levels) . ')');
		// Get where b.published is 1
		$query->where('b.published = 1');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			return $db->loadObjectList();
		}
		return false;
	}


	/**
	 * Get the uikit needed components
	 *
	 * @return mixed  An array of objects on success.
	 *
	 */
	public function getUikitComp()
	{
		if (isset($this->uikitComp) && UtilitiesArrayHelper::check($this->uikitComp))
		{
			return $this->uikitComp;
		}
		return false;
	}
}
