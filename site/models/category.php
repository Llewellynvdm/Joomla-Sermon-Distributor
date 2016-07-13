<?php
/*--------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
    __      __       _     _____                 _                                  _     __  __      _   _               _
    \ \    / /      | |   |  __ \               | |                                | |   |  \/  |    | | | |             | |
     \ \  / /_ _ ___| |_  | |  | | _____   _____| | ___  _ __  _ __ ___   ___ _ __ | |_  | \  / | ___| |_| |__   ___   __| |
      \ \/ / _` / __| __| | |  | |/ _ \ \ / / _ \ |/ _ \| '_ \| '_ ` _ \ / _ \ '_ \| __| | |\/| |/ _ \ __| '_ \ / _ \ / _` |
       \  / (_| \__ \ |_  | |__| |  __/\ V /  __/ | (_) | |_) | | | | | |  __/ | | | |_  | |  | |  __/ |_| | | | (_) | (_| |
        \/ \__,_|___/\__| |_____/ \___| \_/ \___|_|\___/| .__/|_| |_| |_|\___|_| |_|\__| |_|  |_|\___|\__|_| |_|\___/ \__,_|
                                                        | |                                                                 
                                                        |_| 				
/-------------------------------------------------------------------------------------------------------------------------------/

	@version		1.3.3
	@build			13th July, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		category.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Sermondistributor Model for Category
 */
class SermondistributorModelCategory extends JModelList
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
		$this->user		= JFactory::getUser();
		$this->userId		= $this->user->get('id');
		$this->guest		= $this->user->get('guest');
                $this->groups		= $this->user->get('groups');
                $this->authorisedGroups	= $this->user->getAuthorisedGroups();
		$this->levels		= $this->user->getAuthorisedViewLevels();
		$this->app		= JFactory::getApplication();
		$this->input		= $this->app->input;
		$this->initSet		= true; 
		// [Interpretation 2294] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 2303] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 1153] Get from #__sermondistributor_sermon as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.link_type','a.short_description','a.icon','a.preacher','a.series','a.catid','a.description','a.source','a.build','a.manual_files','a.local_files','a.url','a.auto_sermons','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','name','alias','link_type','short_description','icon','preacher','series','catid','description','source','build','manual_files','local_files','url','auto_sermons','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));

		// [Interpretation 1153] Get from #__sermondistributor_preacher as c
		$query->select($db->quoteName(
			array('c.name','c.alias'),
			array('preacher_name','preacher_alias')));
		$query->join('LEFT', ($db->quoteName('#__sermondistributor_preacher', 'c')) . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('c.id') . ')');

		// [Interpretation 1153] Get from #__sermondistributor_series as d
		$query->select($db->quoteName(
			array('d.name','d.alias'),
			array('series_name','series_alias')));
		$query->join('LEFT', ($db->quoteName('#__sermondistributor_series', 'd')) . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('d.id') . ')');

		// [Interpretation 1153] Get from #__categories as b
		$query->select($db->quoteName(
			array('b.title'),
			array('category')));
		$query->join('LEFT', ($db->quoteName('#__categories', 'b')) . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id') . ')');
		$query->where('a.access IN (' . implode(',', $this->levels) . ')');
		// [Interpretation 1498] Check if JRequest::getInt('id') is a string or numeric value.
		$checkValue = JRequest::getInt('id');
		if (isset($checkValue) && SermondistributorHelper::checkString($checkValue))
		{
			$query->where('a.catid = ' . $db->quote($checkValue));
		}
		elseif (is_numeric($checkValue))
		{
			$query->where('a.catid = ' . $checkValue);
		}
		else
		{
			return false;
		}
		$query->where('a.published = 1');
		$query->order('a.ordering ASC');

		// [Interpretation 2324] return the query object
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
                // check if this user has permission to access items
                if (!$user->authorise('site.category.access', 'com_sermondistributor'))
                {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('Not authorised!'), 'error');
			// redirect away if not a correct (TODO for now we go to default view)
			$app->redirect(JRoute::_('index.php?option=com_sermondistributor&view=preachers'));
			return false;
                } 
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_sermondistributor', true);

		// [Interpretation 2344] Convert the parameter fields into objects.
		foreach ($items as $nr => &$item)
		{
			// [Interpretation 2347] Always create a slug for sef URL's
			$item->slug = (isset($item->alias)) ? $item->id.':'.$item->alias : $item->id;
			if (SermondistributorHelper::checkString($item->local_files))
			{
				// [Interpretation 1355] Decode local_files
				$item->local_files = json_decode($item->local_files, true);
			}
			if (SermondistributorHelper::checkString($item->manual_files))
			{
				// [Interpretation 1355] Decode manual_files
				$item->manual_files = json_decode($item->manual_files, true);
			}
			// [Interpretation 1370] Make sure the content prepare plugins fire on description.
			$item->description = JHtml::_('content.prepare',$item->description);
			// [Interpretation 1372] Checking if description has uikit components that must be loaded.
			$this->uikitComp = SermondistributorHelper::getUikitComp($item->description,$this->uikitComp);
			// [Interpretation 1403] set idSermonStatisticE to the $item object.
			$item->idSermonStatisticE = $this->getIdSermonStatisticBcea_E($item->id);
		} 


		// do a quick build of all the sermon links
		if (isset($items) && $items)
		{
			$pastDate = strtotime('-1 week');
			foreach ($items as $nr => &$item)
			{
				$item->isNew = false;
				// check if sermon is new
				$createdTime = strtotime($item->created);
				if ($pastDate < $createdTime)
				{
					$item->isNew = true;
				}
				$item->statisticTotal = 0;
				if (isset($item->auto_sermons) && SermondistributorHelper::checkString($item->auto_sermons))
				{
					// Decode the auto files
					$item->auto_sermons = json_decode($item->auto_sermons, true);
				}
				// set statistic per filename if found
				if (isset($item->idSermonStatisticE) && SermondistributorHelper::checkArray($item->idSermonStatisticE))
				{
					foreach ($item->idSermonStatisticE as $statistic)
					{
						$item->statistic[$statistic->filename] = $statistic->counter;
					}
					// set the total downloads for this sermon
					$item->statisticTotal = array_sum($item->statistic);
				}
				unset($item->idSermonStatisticE);
				// set preacher slug
				$item->preacher_slug = (isset($item->preacher_alias)) ? $item->preacher.':'.$item->preacher_alias : $item->preacher;
				// build series slug
				$item->series_slug = (isset($item->series_alias)) ? $item->series.':'.$item->series_alias : $item->series;
				// build needed links
				$item->link = JRoute::_(SermondistributorHelperRoute::getSermonRoute($item->slug));
				$item->preacher_link = JRoute::_(SermondistributorHelperRoute::getPreacherRoute($item->preacher_slug));
				$item->series_link = JRoute::_(SermondistributorHelperRoute::getSeriesRoute($item->series_slug));
				// set view key
				$item->viewKey = 'category';
				SermondistributorHelper::getDownloadLinks($item);
			}
		}

		// return items
		return $items;
	} 

	/**
	* Method to get an array of Statistic Objects.
	*
	* @return mixed  An array of Statistic Objects on success, false on failure.
	*
	*/
	public function getIdSermonStatisticBcea_E($id)
	{
		// [Interpretation 2096] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 2098] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 2100] Get from #__sermondistributor_statistic as e
		$query->select($db->quoteName(
			array('e.filename','e.sermon','e.preacher','e.series','e.counter'),
			array('filename','sermon','preacher','series','counter')));
		$query->from($db->quoteName('#__sermondistributor_statistic', 'e'));
		$query->where('e.sermon = ' . $db->quote($id));

		// [Interpretation 2154] Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// [Interpretation 2157] check if there was data returned
		if ($db->getNumRows())
		{
			return $db->loadObjectList();
		}
		return false;
	}


	/**
	* Custom Method
	*
	* @return mixed  item data object on success, false on failure.
	*
	*/
	public function getCategory()
	{

		if (!isset($this->initSet) || !$this->initSet)
		{
			$this->user		= JFactory::getUser();
			$this->userId		= $this->user->get('id');
			$this->guest		= $this->user->get('guest');
			$this->groups		= $this->user->get('groups');
			$this->authorisedGroups	= $this->user->getAuthorisedGroups();
			$this->levels		= $this->user->getAuthorisedViewLevels();
			$this->initSet		= true;
		}
		// [Interpretation 1715] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 1717] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 1153] Get from #__categories as a
		$query->select($db->quoteName(
			array('a.id','a.parent_id','a.lft','a.rgt','a.level','a.title','a.alias','a.note','a.description','a.params','a.metadesc','a.metakey','a.metadata','a.hits','a.language','a.version'),
			array('id','parent_id','lft','rgt','level','name','alias','note','description','params','metadesc','metakey','metadata','hits','language','version')));
		$query->from($db->quoteName('#__categories', 'a'));
		// [Interpretation 1498] Check if JRequest::getInt('id') is a string or numeric value.
		$checkValue = JRequest::getInt('id');
		if (isset($checkValue) && SermondistributorHelper::checkString($checkValue))
		{
			$query->where('a.id = ' . $db->quote($checkValue));
		}
		elseif (is_numeric($checkValue))
		{
			$query->where('a.id = ' . $checkValue);
		}
		else
		{
			return false;
		}
		$query->where('a.published = 1');
		$query->order('a.title ASC');

		// [Interpretation 1728] Reset the query using our newly populated query object.
		$db->setQuery($query);
		// [Interpretation 1730] Load the results as a stdClass object.
		$data = $db->loadObject();

		if (empty($data))
		{
			return false;
		}
		// [Interpretation 1403] set idCatidSermonB to the $data object.
		$data->idCatidSermonB = $this->getIdCatidSermonFbdf_B($data->id);

		// [Interpretation 1830] return data object.
		return $data;
	}

	/**
	* Method to get an array of Sermon Objects.
	*
	* @return mixed  An array of Sermon Objects on success, false on failure.
	*
	*/
	public function getIdCatidSermonFbdf_B($id)
	{
		// [Interpretation 2096] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 2098] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 2100] Get from #__sermondistributor_sermon as b
		$query->select($db->quoteName(
			array('b.id'),
			array('id')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'b'));
		$query->where('b.catid  = ' . $db->quote($id));

		// [Interpretation 2154] Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// [Interpretation 2157] check if there was data returned
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// [Interpretation 2210] Convert the parameter fields into objects.
			foreach ($items as $nr => &$item)
			{
				// [Interpretation 1391] set idSermonStatisticC to the $item object.
				$item->idSermonStatisticC = $this->getIdSermonStatisticFbdf_C($item->id);
			}
			return $items;
		}
		return false;
	}

	/**
	* Method to get an array of Statistic Objects.
	*
	* @return mixed  An array of Statistic Objects on success, false on failure.
	*
	*/
	public function getIdSermonStatisticFbdf_C($id)
	{
		// [Interpretation 2096] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 2098] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 2100] Get from #__sermondistributor_statistic as c
		$query->select($db->quoteName(
			array('c.counter'),
			array('counter')));
		$query->from($db->quoteName('#__sermondistributor_statistic', 'c'));
		$query->where('c.sermon = ' . $db->quote($id));

		// [Interpretation 2154] Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// [Interpretation 2157] check if there was data returned
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
