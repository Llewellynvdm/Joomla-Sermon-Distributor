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

	@version		2.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		preachers.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

/**
 * Sermondistributor Model for Preachers
 */
class SermondistributorModelPreachers extends JModelList
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
		$this->user = JFactory::getUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
		$this->initSet = true; 
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_preacher as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.icon','a.email','a.website','a.description','a.hits','a.ordering'),
			array('id','asset_id','name','alias','icon','email','website','description','hits','ordering')));
		$query->from($db->quoteName('#__sermondistributor_preacher', 'a'));
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
		$user = JFactory::getUser();
		// check if this user has permission to access item
		if (!$user->authorise('site.preachers.access', 'com_sermondistributor'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_SERMONDISTRIBUTOR_NOT_AUTHORISED_TO_VIEW_PREACHERS'), 'error');
			// redirect away to the home page if no access allowed.
			$app->redirect(JURI::root());
			return false;
		}
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_sermondistributor', true);

		// Insure all item fields are adapted where needed.
		if (SermondistributorHelper::checkArray($items))
		{
			// Load the JEvent Dispatcher
			JPluginHelper::importPlugin('content');
			$this->_dispatcher = JFactory::getApplication();
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = (isset($item->alias) && isset($item->id)) ? $item->id.':'.$item->alias : $item->id;
				// Check if item has params, or pass whole item.
				$params = (isset($item->params) && SermondistributorHelper::checkJson($item->params)) ? json_decode($item->params) : $item;
				// Make sure the content prepare plugins fire on description
				$_description = new stdClass();
				$_description->text =& $item->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->triggerEvent("onContentPrepare", array('com_sermondistributor.preacher.description', &$_description, &$params, 0));
				// Checking if description has uikit components that must be loaded.
				$this->uikitComp = SermondistributorHelper::getUikitComp($item->description,$this->uikitComp);
				// set idPreacherSermonB to the $item object.
				$item->idPreacherSermonB = $this->getIdPreacherSermonDcaa_B($item->id);
			}
		}


		if (SermondistributorHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				if (!SermondistributorHelper::checkArray($item->idPreacherSermonB))
				{
					// remove empty preacher
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
	public function getIdPreacherSermonDcaa_B($id)
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_sermon as b
		$query->select($db->quoteName(
			array('b.id'),
			array('id')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'b'));
		$query->where('b.preacher = ' . $db->quote($id));
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
		if (isset($this->uikitComp) && SermondistributorHelper::checkArray($this->uikitComp))
		{
			return $this->uikitComp;
		}
		return false;
	}
}
