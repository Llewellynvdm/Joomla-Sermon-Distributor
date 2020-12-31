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
	@subpackage		sermons.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

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
				'a.access','access',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'g.name','preacher',
				'h.name','series',
				'c.title','category_title',
				'c.id', 'category_id',
				'a.catid','catid',
				'a.link_type','link_type',
				'a.source','source',
				'a.name','name',
				'a.short_description','short_description'
			);
		}

		parent::__construct($config);
	}

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @param   string  $ordering   An optional ordering field.
	 * @param   string  $direction  An optional direction (asc|desc).
	 *
	 * @return  void
	 *
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = JFactory::getApplication();

		// Adjust the context to support modal layouts.
		if ($layout = $app->input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// Check if the form was submitted
		$formSubmited = $app->input->post->get('form_submited');

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		if ($formSubmited)
		{
			$access = $app->input->post->get('access');
			$this->setState('filter.access', $access);
		}

		$published = $this->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '');
		$this->setState('filter.published', $published);

		$created_by = $this->getUserStateFromRequest($this->context . '.filter.created_by', 'filter_created_by', '');
		$this->setState('filter.created_by', $created_by);

		$created = $this->getUserStateFromRequest($this->context . '.filter.created', 'filter_created');
		$this->setState('filter.created', $created);

		$sorting = $this->getUserStateFromRequest($this->context . '.filter.sorting', 'filter_sorting', 0, 'int');
		$this->setState('filter.sorting', $sorting);

		$search = $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search');
		$this->setState('filter.search', $search);

		$preacher = $this->getUserStateFromRequest($this->context . '.filter.preacher', 'filter_preacher');
		if ($formSubmited)
		{
			$preacher = $app->input->post->get('preacher');
			$this->setState('filter.preacher', $preacher);
		}

		$series = $this->getUserStateFromRequest($this->context . '.filter.series', 'filter_series');
		if ($formSubmited)
		{
			$series = $app->input->post->get('series');
			$this->setState('filter.series', $series);
		}

		$category = $app->getUserStateFromRequest($this->context . '.filter.category', 'filter_category');
		$this->setState('filter.category', $category);

		$categoryId = $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id');
		$this->setState('filter.category_id', $categoryId);

		$catid = $this->getUserStateFromRequest($this->context . '.filter.catid', 'filter_catid');
		if ($formSubmited)
		{
			$catid = $app->input->post->get('catid');
			$this->setState('filter.catid', $catid);
		}

		$link_type = $this->getUserStateFromRequest($this->context . '.filter.link_type', 'filter_link_type');
		if ($formSubmited)
		{
			$link_type = $app->input->post->get('link_type');
			$this->setState('filter.link_type', $link_type);
		}

		$source = $this->getUserStateFromRequest($this->context . '.filter.source', 'filter_source');
		if ($formSubmited)
		{
			$source = $app->input->post->get('source');
			$this->setState('filter.source', $source);
		}

		$name = $this->getUserStateFromRequest($this->context . '.filter.name', 'filter_name');
		if ($formSubmited)
		{
			$name = $app->input->post->get('name');
			$this->setState('filter.name', $name);
		}

		$short_description = $this->getUserStateFromRequest($this->context . '.filter.short_description', 'filter_short_description');
		if ($formSubmited)
		{
			$short_description = $app->input->post->get('short_description');
			$this->setState('filter.short_description', $short_description);
		}

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
		// check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// Set values to display correctly.
		if (SermondistributorHelper::checkArray($items))
		{
			// Get the user object if not set.
			if (!isset($user) || !SermondistributorHelper::checkObject($user))
			{
				$user = JFactory::getUser();
			}
			foreach ($items as $nr => &$item)
			{
				// Remove items the user can't access.
				$access = ($user->authorise('sermon.access', 'com_sermondistributor.sermon.' . (int) $item->id) && $user->authorise('sermon.access', 'com_sermondistributor'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		}

		// set selection value to a translatable value
		if (SermondistributorHelper::checkArray($items))
		{
			foreach ($items as $nr => &$item)
			{
				// convert link_type
				$item->link_type = $this->selectionTranslation($item->link_type, 'link_type');
				// convert source
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
		// Array of link_type language strings
		if ($name === 'link_type')
		{
			$link_typeArray = array(
				1 => 'COM_SERMONDISTRIBUTOR_SERMON_ENCRYPTED',
				2 => 'COM_SERMONDISTRIBUTOR_SERMON_DIRECT'
			);
			// Now check if value is found in this array
			if (isset($link_typeArray[$value]) && SermondistributorHelper::checkString($link_typeArray[$value]))
			{
				return $link_typeArray[$value];
			}
		}
		// Array of source language strings
		if ($name === 'source')
		{
			$sourceArray = array(
				0 => 'COM_SERMONDISTRIBUTOR_SERMON_SELECT_SOURCE',
				1 => 'COM_SERMONDISTRIBUTOR_SERMON_LOCAL_FOLDER',
				2 => 'COM_SERMONDISTRIBUTOR_SERMON_EXTERNAL_SOURCE',
				3 => 'COM_SERMONDISTRIBUTOR_SERMON_URL'
			);
			// Now check if value is found in this array
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
		// Get the user object.
		$user = JFactory::getUser();
		// Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');
		$query->select($db->quoteName('c.title','category_title'));

		// From the sermondistributor_item table
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));
		$query->join('LEFT', $db->quoteName('#__categories', 'c') . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('c.id') . ')');

		// From the sermondistributor_preacher table.
		$query->select($db->quoteName('g.name','preacher_name'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_preacher', 'g') . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('g.id') . ')');

		// From the sermondistributor_series table.
		$query->select($db->quoteName('h.name','series_name'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_series', 'h') . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('h.id') . ')');

		// Filter by published state
		$published = $this->getState('filter.published');
		if (is_numeric($published))
		{
			$query->where('a.published = ' . (int) $published);
		}
		elseif ($published === '')
		{
			$query->where('(a.published = 0 OR a.published = 1)');
		}

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// Filter by access level.
		$_access = $this->getState('filter.access');
		if ($_access && is_numeric($_access))
		{
			$query->where('a.access = ' . (int) $_access);
		}
		elseif (SermondistributorHelper::checkArray($_access))
		{
			// Secure the array for the query
			$_access = ArrayHelper::toInteger($_access);
			// Filter by the Access Array.
			$query->where('a.access IN (' . implode(',', $_access) . ')');
		}
		// Implement View Level Access
		if (!$user->authorise('core.options', 'com_sermondistributor'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}
		// Filter by search.
		$search = $this->getState('filter.search');
		if (!empty($search))
		{
			if (stripos($search, 'id:') === 0)
			{
				$query->where('a.id = ' . (int) substr($search, 3));
			}
			else
			{
				$search = $db->quote('%' . $db->escape($search) . '%');
				$query->where('(a.name LIKE '.$search.' OR a.preacher LIKE '.$search.' OR g.name LIKE '.$search.' OR a.series LIKE '.$search.' OR h.name LIKE '.$search.' OR a.short_description LIKE '.$search.' OR a.catid LIKE '.$search.' OR a.link_type LIKE '.$search.' OR a.scripture LIKE '.$search.')');
			}
		}

		// Filter by Preacher.
		$_preacher = $this->getState('filter.preacher');
		if (is_numeric($_preacher))
		{
			if (is_float($_preacher))
			{
				$query->where('a.preacher = ' . (float) $_preacher);
			}
			else
			{
				$query->where('a.preacher = ' . (int) $_preacher);
			}
		}
		elseif (SermondistributorHelper::checkString($_preacher))
		{
			$query->where('a.preacher = ' . $db->quote($db->escape($_preacher)));
		}
		elseif (SermondistributorHelper::checkArray($_preacher))
		{
			// Secure the array for the query
			$_preacher = array_map( function ($val) use(&$db) {
				if (is_numeric($val))
				{
					if (is_float($val))
					{
						return (float) $val;
					}
					else
					{
						return (int) $val;
					}
				}
				elseif (SermondistributorHelper::checkString($val))
				{
					return $db->quote($db->escape($val));
				}
			}, $_preacher);
			// Filter by the Preacher Array.
			$query->where('a.preacher IN (' . implode(',', $_preacher) . ')');
		}
		// Filter by Series.
		$_series = $this->getState('filter.series');
		if (is_numeric($_series))
		{
			if (is_float($_series))
			{
				$query->where('a.series = ' . (float) $_series);
			}
			else
			{
				$query->where('a.series = ' . (int) $_series);
			}
		}
		elseif (SermondistributorHelper::checkString($_series))
		{
			$query->where('a.series = ' . $db->quote($db->escape($_series)));
		}
		elseif (SermondistributorHelper::checkArray($_series))
		{
			// Secure the array for the query
			$_series = array_map( function ($val) use(&$db) {
				if (is_numeric($val))
				{
					if (is_float($val))
					{
						return (float) $val;
					}
					else
					{
						return (int) $val;
					}
				}
				elseif (SermondistributorHelper::checkString($val))
				{
					return $db->quote($db->escape($val));
				}
			}, $_series);
			// Filter by the Series Array.
			$query->where('a.series IN (' . implode(',', $_series) . ')');
		}
		// Filter by Link_type.
		$_link_type = $this->getState('filter.link_type');
		if (is_numeric($_link_type))
		{
			if (is_float($_link_type))
			{
				$query->where('a.link_type = ' . (float) $_link_type);
			}
			else
			{
				$query->where('a.link_type = ' . (int) $_link_type);
			}
		}
		elseif (SermondistributorHelper::checkString($_link_type))
		{
			$query->where('a.link_type = ' . $db->quote($db->escape($_link_type)));
		}
		// Filter by Source.
		$_source = $this->getState('filter.source');
		if (is_numeric($_source))
		{
			if (is_float($_source))
			{
				$query->where('a.source = ' . (float) $_source);
			}
			else
			{
				$query->where('a.source = ' . (int) $_source);
			}
		}
		elseif (SermondistributorHelper::checkString($_source))
		{
			$query->where('a.source = ' . $db->quote($db->escape($_source)));
		}

		// Filter by a single or group of categories.
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
			$categoryId = ArrayHelper::toInteger($categoryId);
			$categoryId = implode(',', $categoryId);
			$query->where('a.catid IN (' . $categoryId . ')');
		}


		// Add the list ordering clause.
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');
		if ($orderCol != '')
		{
			$query->order($db->escape($orderCol . ' ' . $orderDirn));
		}

		return $query;
	}

	/**
	 * Method to get list export data.
	 *
	 * @param   array  $pks  The ids of the items to get
	 * @param   JUser  $user  The user making the request
	 *
	 * @return mixed  An array of data items on success, false on failure.
	 */
	public function getExportData($pks, $user = null)
	{
		// setup the query
		if (($pks_size = SermondistributorHelper::checkArray($pks)) !== false || 'bulk' === $pks)
		{
			// Set a value to know this is export method. (USE IN CUSTOM CODE TO ALTER OUTCOME)
			$_export = true;
			// Get the user object if not set.
			if (!isset($user) || !SermondistributorHelper::checkObject($user))
			{
				$user = JFactory::getUser();
			}
			// Create a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// Select some fields
			$query->select('a.*');

			// From the sermondistributor_sermon table
			$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));
			// The bulk export path
			if ('bulk' === $pks)
			{
				$query->where('a.id > 0');
			}
			// A large array of ID's will not work out well
			elseif ($pks_size > 500)
			{
				// Use lowest ID
				$query->where('a.id >= ' . (int) min($pks));
				// Use highest ID
				$query->where('a.id <= ' . (int) max($pks));
			}
			// The normal default path
			else
			{
				$query->where('a.id IN (' . implode(',',$pks) . ')');
			}
			// Implement View Level Access
			if (!$user->authorise('core.options', 'com_sermondistributor'))
			{
				$groups = implode(',', $user->getAuthorisedViewLevels());
				$query->where('a.access IN (' . $groups . ')');
			}

			// Order the results by ordering
			$query->order('a.ordering  ASC');

			// Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// Set values to display correctly.
				if (SermondistributorHelper::checkArray($items))
				{
					foreach ($items as $nr => &$item)
					{
						// Remove items the user can't access.
						$access = ($user->authorise('sermon.access', 'com_sermondistributor.sermon.' . (int) $item->id) && $user->authorise('sermon.access', 'com_sermondistributor'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						// unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// Add headers to items array.
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
		// Get a db connection.
		$db = JFactory::getDbo();
		// get the columns
		$columns = $db->getTableColumns("#__sermondistributor_sermon");
		if (SermondistributorHelper::checkArray($columns))
		{
			// remove the headers you don't import/export.
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
		// Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		// Check if the value is an array
		$_access = $this->getState('filter.access');
		if (SermondistributorHelper::checkArray($_access))
		{
			$id .= ':' . implode(':', $_access);
		}
		// Check if this is only an number or string
		elseif (is_numeric($_access)
		 || SermondistributorHelper::checkString($_access))
		{
			$id .= ':' . $_access;
		}
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		// Check if the value is an array
		$_preacher = $this->getState('filter.preacher');
		if (SermondistributorHelper::checkArray($_preacher))
		{
			$id .= ':' . implode(':', $_preacher);
		}
		// Check if this is only an number or string
		elseif (is_numeric($_preacher)
		 || SermondistributorHelper::checkString($_preacher))
		{
			$id .= ':' . $_preacher;
		}
		// Check if the value is an array
		$_series = $this->getState('filter.series');
		if (SermondistributorHelper::checkArray($_series))
		{
			$id .= ':' . implode(':', $_series);
		}
		// Check if this is only an number or string
		elseif (is_numeric($_series)
		 || SermondistributorHelper::checkString($_series))
		{
			$id .= ':' . $_series;
		}
		// Check if the value is an array
		$_category = $this->getState('filter.category');
		if (SermondistributorHelper::checkArray($_category))
		{
			$id .= ':' . implode(':', $_category);
		}
		// Check if this is only an number or string
		elseif (is_numeric($_category)
		 || SermondistributorHelper::checkString($_category))
		{
			$id .= ':' . $_category;
		}
		// Check if the value is an array
		$_category_id = $this->getState('filter.category_id');
		if (SermondistributorHelper::checkArray($_category_id))
		{
			$id .= ':' . implode(':', $_category_id);
		}
		// Check if this is only an number or string
		elseif (is_numeric($_category_id)
		 || SermondistributorHelper::checkString($_category_id))
		{
			$id .= ':' . $_category_id;
		}
		// Check if the value is an array
		$_catid = $this->getState('filter.catid');
		if (SermondistributorHelper::checkArray($_catid))
		{
			$id .= ':' . implode(':', $_catid);
		}
		// Check if this is only an number or string
		elseif (is_numeric($_catid)
		 || SermondistributorHelper::checkString($_catid))
		{
			$id .= ':' . $_catid;
		}
		$id .= ':' . $this->getState('filter.link_type');
		$id .= ':' . $this->getState('filter.source');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.short_description');

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
		// Get set check in time
		$time = JComponentHelper::getParams('com_sermondistributor')->get('check_in');

		if ($time)
		{

			// Get a db connection.
			$db = JFactory::getDbo();
			// reset query
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__sermondistributor_sermon'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				// Get Yesterdays date
				$date = JFactory::getDate()->modify($time)->toSql();
				// reset query
				$query = $db->getQuery(true);

				// Fields to update.
				$fields = array(
					$db->quoteName('checked_out_time') . '=\'0000-00-00 00:00:00\'',
					$db->quoteName('checked_out') . '=0'
				);

				// Conditions for which records should be updated.
				$conditions = array(
					$db->quoteName('checked_out') . '!=0', 
					$db->quoteName('checked_out_time') . '<\''.$date.'\''
				);

				// Check table
				$query->update($db->quoteName('#__sermondistributor_sermon'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}

		return false;
	}
}
