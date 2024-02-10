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
	@subpackage		local_listings.php
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
use VDM\Joomla\Utilities\ObjectHelper;
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\FOF\Encrypt\AES;

/**
 * Local_listings List Model
 */
class SermondistributorModelLocal_listings extends ListModel
{
	public function __construct($config = [])
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'a.id','id',
				'a.published','published',
				'a.ordering','ordering',
				'a.created_by','created_by',
				'a.modified_by','modified_by',
				'a.build','build',
				'g.description','external_source',
				'a.name','name',
				'a.size','size',
				'a.key','key'
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
		$app = Factory::getApplication();

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

		$build = $this->getUserStateFromRequest($this->context . '.filter.build', 'filter_build');
		if ($formSubmited)
		{
			$build = $app->input->post->get('build');
			$this->setState('filter.build', $build);
		}

		$external_source = $this->getUserStateFromRequest($this->context . '.filter.external_source', 'filter_external_source');
		if ($formSubmited)
		{
			$external_source = $app->input->post->get('external_source');
			$this->setState('filter.external_source', $external_source);
		}

		$name = $this->getUserStateFromRequest($this->context . '.filter.name', 'filter_name');
		if ($formSubmited)
		{
			$name = $app->input->post->get('name');
			$this->setState('filter.name', $name);
		}

		$size = $this->getUserStateFromRequest($this->context . '.filter.size', 'filter_size');
		if ($formSubmited)
		{
			$size = $app->input->post->get('size');
			$this->setState('filter.size', $size);
		}

		$key = $this->getUserStateFromRequest($this->context . '.filter.key', 'filter_key');
		if ($formSubmited)
		{
			$key = $app->input->post->get('key');
			$this->setState('filter.key', $key);
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
		// Check in items
		$this->checkInNow();

		// load parent items
		$items = parent::getItems();

		// Set values to display correctly.
		if (UtilitiesArrayHelper::check($items))
		{
			// Get the user object if not set.
			if (!isset($user) || !ObjectHelper::check($user))
			{
				$user = Factory::getUser();
			}
			foreach ($items as $nr => &$item)
			{
				// Remove items the user can't access.
				$access = ($user->authorise('local_listing.access', 'com_sermondistributor.local_listing.' . (int) $item->id) && $user->authorise('local_listing.access', 'com_sermondistributor'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		}

		// set selection value to a translatable value
		if (UtilitiesArrayHelper::check($items))
		{
			foreach ($items as $nr => &$item)
			{
				// convert build
				$item->build = $this->selectionTranslation($item->build, 'build');
			}
		}


		// return items
		return $items;
	}

	/**
	 * Method to convert selection values to translatable string.
	 *
	 * @return  string   The translatable string.
	 */
	public function selectionTranslation($value,$name)
	{
		// Array of build language strings
		if ($name === 'build')
		{
			$buildArray = array(
				0 => 'COM_SERMONDISTRIBUTOR_LOCAL_LISTING_SELECT_AN_OPTION',
				1 => 'COM_SERMONDISTRIBUTOR_LOCAL_LISTING_MANUAL_LOCAL_SELECTION',
				2 => 'COM_SERMONDISTRIBUTOR_LOCAL_LISTING_DYNAMIC_AUTOMATIC_BUILD'
			);
			// Now check if value is found in this array
			if (isset($buildArray[$value]) && StringHelper::check($buildArray[$value]))
			{
				return $buildArray[$value];
			}
		}
		return $value;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return    string    An SQL query
	 */
	protected function getListQuery()
	{
		// Get the user object.
		$user = Factory::getUser();
		// Create a new query object.
		$db = Factory::getDBO();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the sermondistributor_item table
		$query->from($db->quoteName('#__sermondistributor_local_listing', 'a'));

		// From the sermondistributor_external_source table.
		$query->select($db->quoteName('g.description','external_source_description'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_external_source', 'g') . ' ON (' . $db->quoteName('a.external_source') . ' = ' . $db->quoteName('g.id') . ')');

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
				$query->where('(a.name LIKE '.$search.' OR a.build LIKE '.$search.' OR a.key LIKE '.$search.')');
			}
		}

		// Filter by Build.
		$_build = $this->getState('filter.build');
		if (is_numeric($_build))
		{
			if (is_float($_build))
			{
				$query->where('a.build = ' . (float) $_build);
			}
			else
			{
				$query->where('a.build = ' . (int) $_build);
			}
		}
		elseif (StringHelper::check($_build))
		{
			$query->where('a.build = ' . $db->quote($db->escape($_build)));
		}
		// Filter by External_source.
		$_external_source = $this->getState('filter.external_source');
		if (is_numeric($_external_source))
		{
			if (is_float($_external_source))
			{
				$query->where('a.external_source = ' . (float) $_external_source);
			}
			else
			{
				$query->where('a.external_source = ' . (int) $_external_source);
			}
		}
		elseif (StringHelper::check($_external_source))
		{
			$query->where('a.external_source = ' . $db->quote($db->escape($_external_source)));
		}

		// Add the list ordering clause.
		$orderCol = $this->getState('list.ordering', 'a.id');
		$orderDirn = $this->getState('list.direction', 'desc');
		if ($orderCol != '')
		{
			// Check that the order direction is valid encase we have a field called direction as part of filers.
			$orderDirn = (is_string($orderDirn) && in_array(strtolower($orderDirn), ['asc', 'desc'])) ? $orderDirn : 'desc';
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
		if (($pks_size = UtilitiesArrayHelper::check($pks)) !== false || 'bulk' === $pks)
		{
			// Set a value to know this is export method. (USE IN CUSTOM CODE TO ALTER OUTCOME)
			$_export = true;
			// Get the user object if not set.
			if (!isset($user) || !ObjectHelper::check($user))
			{
				$user = Factory::getUser();
			}
			// Create a new query object.
			$db = Factory::getDBO();
			$query = $db->getQuery(true);

			// Select some fields
			$query->select('a.*');

			// From the sermondistributor_local_listing table
			$query->from($db->quoteName('#__sermondistributor_local_listing', 'a'));
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

			// Order the results by ordering
			$query->order('a.ordering  ASC');

			// Load the items
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$items = $db->loadObjectList();

				// Get the basic encryption key.
				$basickey = SermondistributorHelper::getCryptKey('basic');
				// Get the encryption object.
				$basic = new AES($basickey);

				// Set values to display correctly.
				if (UtilitiesArrayHelper::check($items))
				{
					foreach ($items as $nr => &$item)
					{
						// Remove items the user can't access.
						$access = ($user->authorise('local_listing.access', 'com_sermondistributor.local_listing.' . (int) $item->id) && $user->authorise('local_listing.access', 'com_sermondistributor'));
						if (!$access)
						{
							unset($items[$nr]);
							continue;
						}

						if ($basickey && !is_numeric($item->url) && $item->url === base64_encode(base64_decode($item->url, true)))
						{
							// decrypt url
							$item->url = $basic->decryptString($item->url);
						}
						// unset the values we don't want exported.
						unset($item->asset_id);
						unset($item->checked_out);
						unset($item->checked_out_time);
					}
				}
				// Add headers to items array.
				$headers = $this->getExImPortHeaders();
				if (ObjectHelper::check($headers))
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
		$db = Factory::getDbo();
		// get the columns
		$columns = $db->getTableColumns("#__sermondistributor_local_listing");
		if (UtilitiesArrayHelper::check($columns))
		{
			// remove the headers you don't import/export.
			unset($columns['asset_id']);
			unset($columns['checked_out']);
			unset($columns['checked_out_time']);
			$headers = new \stdClass();
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
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.build');
		$id .= ':' . $this->getState('filter.external_source');
		$id .= ':' . $this->getState('filter.name');
		$id .= ':' . $this->getState('filter.size');
		$id .= ':' . $this->getState('filter.key');

		return parent::getStoreId($id);
	}

	/**
	 * Build an SQL query to checkin all items left checked out longer then a set time.
	 *
	 * @return bool
	 * @since 3.2.0
	 */
	protected function checkInNow(): bool
	{
		// Get set check in time
		$time = ComponentHelper::getParams('com_sermondistributor')->get('check_in');

		if ($time)
		{
			// Get a db connection.
			$db = Factory::getDbo();
			// Reset query.
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__sermondistributor_local_listing'));
			// Only select items that are checked out.
			$query->where($db->quoteName('checked_out') . '!=0');
			$db->setQuery($query, 0, 1);
			$db->execute();
			if ($db->getNumRows())
			{
				// Get Yesterdays date.
				$date = Factory::getDate()->modify($time)->toSql();
				// Reset query.
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

				// Check table.
				$query->update($db->quoteName('#__sermondistributor_local_listing'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				return $db->execute();
			}
		}

		return false;
	}
}
