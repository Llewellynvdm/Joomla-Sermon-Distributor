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
	@build			2nd July, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermons.php
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
 * Sermons Model
 */
class SermondistributorModelSermons extends JModelList
{
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
        {
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.name','name',
				'a.preacher','preacher',
				'a.series','series',
				'a.short_description','short_description',
				'c.title','category_title',
				'c.id', 'category_id',
				'a.catid', 'catid',
				'a.link_type','link_type',
				'a.source','source'
			);
		}

		parent::__construct($config);
	}
	
	/**
	 * Method to auto-populate the model state.
	 *
	 * @return  void
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}
		$name = $this->getUserStateFromRequest($this->context . '.filter.name', 'filter_name');
		$this->setState('filter.name', $name);

		$preacher = $this->getUserStateFromRequest($this->context . '.filter.preacher', 'filter_preacher');
		$this->setState('filter.preacher', $preacher);

		$series = $this->getUserStateFromRequest($this->context . '.filter.series', 'filter_series');
		$this->setState('filter.series', $series);

		$short_description = $this->getUserStateFromRequest($this->context . '.filter.short_description', 'filter_short_description');
		$this->setState('filter.short_description', $short_description);

		$category = $app->getUserStateFromRequest($this->context . '.filter.category', 'filter_category');
		$this->setState('filter.category', $category);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$catid = $app->getUserStateFromRequest($this->context . '.filter.catid', 'filter_catid');
		$this->setState('filter.catid', $catid);

		$link_type = $this->getUserStateFromRequest($this->context . '.filter.link_type', 'filter_link_type');
		$this->setState('filter.link_type', $link_type);

		$source = $this->getUserStateFromRequest($this->context . '.filter.source', 'filter_source');
		$this->setState('filter.source', $source);
        
		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);
        
		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		$this->setState('filter.access', $access);
        
		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);
        
		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		// List state information.
		parent::populateState($ordering, $direction);
	}
	
	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{ 
		// [Interpretation 10357] check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// [Interpretation 10432] set values to display correctly.
		if (SermondistributorHelper::checkArray($items))
		{
			// [Interpretation 10435] get user object.
			$user = JFactory::getUser();
			foreach ($items as $nr => &$item)
			{
				$access = ($user->authorise('sermon.access', 'com_sermondistributor.sermon.' . (int) $item->id) && $user->authorise('sermon.access', 'com_sermondistributor'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		} 

		// [Interpretation 10704] set selection value to a translatable value
		if (SermondistributorHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// [Interpretation 10711] convert link_type
				$item->link_type = $this->selectionTranslation($item->link_type, 'link_type');
				// [Interpretation 10711] convert source
				$item->source = $this->selectionTranslation($item->source, 'source');
			}
		}

        
		// return items
		return $items;
	}

	/**
	* Method to convert selection values to translatable string.
	*
	* @return translatable string
	*/
	public function selectionTranslation($value,$name)
	{
		// [Interpretation 10737] Array of link_type language strings
		if ($name == 'link_type')
		{
			$link_typeArray = array(
				1 => 'COM_SERMONDISTRIBUTOR_SERMON_ENCRYPTED',
				2 => 'COM_SERMONDISTRIBUTOR_SERMON_DIRECT'
			);
			// [Interpretation 10768] Now check if value is found in this array
			if (isset($link_typeArray[$value]) && SermondistributorHelper::checkString($link_typeArray[$value]))
			{
				return $link_typeArray[$value];
			}
		}
		// [Interpretation 10737] Array of source language strings
		if ($name == 'source')
		{
			$sourceArray = array(
				0 => 'COM_SERMONDISTRIBUTOR_SERMON_SELECT_SOURCE',
				1 => 'COM_SERMONDISTRIBUTOR_SERMON_LOCAL_FOLDER',
				2 => 'COM_SERMONDISTRIBUTOR_SERMON_DROPBOX',
				3 => 'COM_SERMONDISTRIBUTOR_SERMON_URL'
			);
			// [Interpretation 10768] Now check if value is found in this array
			if (isset($sourceArray[$value]) && SermondistributorHelper::checkString($sourceArray[$value]))
			{
				return $sourceArray[$value];
			}
		}
		return $value;
	}
	
	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return	string	An SQL query
	 */
	protected function getListQuery()
	{
		// [Interpretation 7224] Get the user object.
		$user = JFactory::getUser();
		// [Interpretation 7226] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [Interpretation 7229] Select some fields
		$query->select('a.*');
		$query->select($db->quoteName('c.title','category_title'));

		// [Interpretation 7236] From the sermondistributor_item table
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));
		$query->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('c.id') . ')');

		// [Interpretation 7377] From the sermondistributor_preacher table.
		$query->select($db->quoteName('g.name','preacher_name'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_preacher', 'g') . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('g.id') . ')');

		// [Interpretation 7377] From the sermondistributor_series table.
		$query->select($db->quoteName('h.name','series_name'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_series', 'h') . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('h.id') . ')');

		// [Interpretation 7250] Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// [Interpretation 7262] Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// [Interpretation 7265] Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}
		// [Interpretation 7270] Implement View Level Access
		if (!$user->authorise('core.options', 'com_sermondistributor'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		// [Interpretation 7347] Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search, true) . '%');
				$query->where('(a.name LIKE '.$search.' OR a.preacher LIKE '.$search.' OR g.name LIKE '.$search.' OR a.series LIKE '.$search.' OR h.name LIKE '.$search.' OR a.short_description LIKE '.$search.' OR a.catid LIKE '.$search.' OR a.link_type LIKE '.$search.' OR a.scripture LIKE '.$search.')');
			}
		}

		// [Interpretation 7531] Filter by preacher.
		if ($preacher = $this->getState('filter.preacher'))
		{
			$query->where('a.preacher = ' . $db->quote($db->escape($preacher, true)));
		}
		// [Interpretation 7531] Filter by series.
		if ($series = $this->getState('filter.series'))
		{
			$query->where('a.series = ' . $db->quote($db->escape($series, true)));
		}
		// [Interpretation 7540] Filter by Link_type.
		if ($link_type = $this->getState('filter.link_type'))
		{
			$query->where('a.link_type = ' . $db->quote($db->escape($link_type, true)));
		}
		// [Interpretation 7540] Filter by Source.
		if ($source = $this->getState('filter.source'))
		{
			$query->where('a.source = ' . $db->quote($db->escape($source, true)));
		}

		// [Interpretation 7284] Filter by a single or group of categories.
		$baselevel = 1;
		$categoryId = $this->getState('filter.category_id');

		if (is_numeric($categoryId))
		{
			$cat_tbl = JTable::getInstance('Category', 'JTable');
			$cat_tbl->load($categoryId);
			$rgt = $cat_tbl->rgt;
			$lft = $cat_tbl->lft;
			$baselevel = (int) $cat_tbl->level;
			$query->where('c.lft >= ' . (int) $lft)
				->where('c.rgt <= ' . (int) $rgt);
		}
		elseif (is_array($categoryId))
		{
			JArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.category IN (' . $categoryId . ')');
		}


		// [Interpretation 7306] Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'asc');	
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	* Method to get list export data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExportData($pks)
	{
		// [Interpretation 6959] setup the query
		if (SermondistributorHelper::checkArray($pks))
		{
			// [Interpretation 6962] Set a value to know this is exporting method.
			$_export = true;
			// [Interpretation 6964] Get the user object.
			$user = JFactory::getUser();
			// [Interpretation 6966] Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// [Interpretation 6969] Select some fields
			$query->select('a.*');

			// [Interpretation 6971] From the sermondistributor_sermon table
			$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));
			$query->where('a.id IN (' . implode(',',$pks) . ')');
			// [Interpretation 6981] Implement View Level Access
			if (!$user->authorise('core.options', 'com_sermondistributor'))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}

			// [Interpretation 6988] Order the results by ordering
			$query->order('a.ordering  ASC');

			// [Interpretation 6990] Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// [Interpretation 10432] set values to display correctly.
				if (SermondistributorHelper::checkArray($items))
				{
					// [Interpretation 10435] get user object.
					$user = JFactory::getUser();
					foreach ($items as $nr => &$item)
					{
						$access = ($user->authorise('sermon.access', 'com_sermondistributor.sermon.' . (int) $item->id) && $user->authorise('sermon.access', 'com_sermondistributor'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						// [Interpretation 10651] unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// [Interpretation 10660] Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (SermondistributorHelper::checkObject($headers))
				{
					array_unshift($items,$headers);
				}
				return $items;
			}
		}
		return false;
	}

	/**
	* Method to get header.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getExImPortHeaders()
	{
		// [Interpretation 7010] Get a db connection.
		$db = JFactory::getDbo();
		// [Interpretation 7012] get the columns
		$columns = $db->getTableColumns("#__sermondistributor_sermon");
		if (SermondistributorHelper::checkArray($columns))
		{
			// [Interpretation 7016] remove the headers you don't import/export.
			unset($columns['asset_id']);
			unset($columns['checked_out']);
			unset($columns['checked_out_time']);
			$headers = new stdClass();
			foreach ($columns as $column => $type)
			{
				$headers->{$column} = $column;
			}
			return $headers;
		}
		return false;
	} 
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 *
	 */
	protected function getStoreId($id = '')
	{
		// [Interpretation 9975] Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.preacher');
		$id .= ':' . $this->getState('filter.series');
		$id .= ':' . $this->getState('filter.short_description');
		$id .= ':' . $this->getState('filter.category');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.catid');
		$id .= ':' . $this->getState('filter.link_type');
		$id .= ':' . $this->getState('filter.source');

		return parent::getStoreId($id);
	}

	/**
	* Build an SQL query to checkin all items left checked out longer then a set time.
	*
	* @return  a bool
	*
	*/
	protected function checkInNow()
	{
		// [Interpretation 10373] Get set check in time
		$time = JComponentHelper::getParams('com_sermondistributor')->get('check_in');
		
		if ($time)
		{

			// [Interpretation 10378] Get a db connection.
			$db = JFactory::getDbo();
			// [Interpretation 10380] reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__sermondistributor_sermon'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// [Interpretation 10388] Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// [Interpretation 10390] reset query
				$query = $db->getQuery(true);

				// [Interpretation 10392] Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// [Interpretation 10397] Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// [Interpretation 10402] Check table
				$query->update($db->quoteName('#__sermondistributor_sermon'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
