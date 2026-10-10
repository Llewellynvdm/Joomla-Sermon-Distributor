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
	@subpackage		AddressesModel.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\Utilities\ArrayHelper;
use Joomla\Input\Input;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use Joomla\CMS\Helper\TagsHelper;
use JoomSermon\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use JoomSermon\Joomla\Utilities\ObjectHelper;
use JoomSermon\Joomla\Utilities\StringHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Addresses List Model
 *
 * @since  1.6
 */
class AddressesModel extends ListModel
{
	/**
	 * The application object.
	 *
	 * @var   CMSApplicationInterface  The application instance.
	 * @since 3.2.0
	 */
	protected CMSApplicationInterface $app;

	/**
	 * The styles array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $styles = [
		'administrator/components/com_sermondistributor/assets/css/admin.css',
		'administrator/components/com_sermondistributor/assets/css/addresses.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'administrator/components/com_sermondistributor/assets/js/admin.js'
 	];

	/**
	 * Constructor
	 *
	 * @param   array                 $config   An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   ?MVCFactoryInterface  $factory  The factory.
	 *
	 * @since   1.6
	 * @throws  \Exception
	 */
	public function __construct($config = [], ?MVCFactoryInterface $factory = null)
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
				'g.name','type',
				'h.name','country',
				'i.name','state',
				'j.name','city',
				'a.line_one','line_one'
			);
		}

		parent::__construct($config, $factory);

		$this->app ??= Factory::getApplication();
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
	 * @since   1.7.0
	 */
	protected function populateState($ordering = null, $direction = null)
	{
		$app = $this->app;
		$input = $this->app->getInput();

		// Adjust the context to support modal layouts.
		if ($layout = $input->get('layout'))
		{
			$this->context .= '.' . $layout;
		}

		// Check if the form was submitted
		$formSubmited = $input->post->get('form_submited');

		$access = $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', 0, 'int');
		if ($formSubmited)
		{
			$access = $input->post->get('access');
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

		$type = $this->getUserStateFromRequest($this->context . '.filter.type', 'filter_type');
		if ($formSubmited)
		{
			$type = $input->post->get('type');
			$this->setState('filter.type', $type);
		}

		$country = $this->getUserStateFromRequest($this->context . '.filter.country', 'filter_country');
		if ($formSubmited)
		{
			$country = $input->post->get('country');
			$this->setState('filter.country', $country);
		}

		$state = $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state');
		if ($formSubmited)
		{
			$state = $input->post->get('state');
			$this->setState('filter.state', $state);
		}

		$city = $this->getUserStateFromRequest($this->context . '.filter.city', 'filter_city');
		if ($formSubmited)
		{
			$city = $input->post->get('city');
			$this->setState('filter.city', $city);
		}

		$line_one = $this->getUserStateFromRequest($this->context . '.filter.line_one', 'filter_line_one');
		if ($formSubmited)
		{
			$line_one = $input->post->get('line_one');
			$this->setState('filter.line_one', $line_one);
		}

		// List state information.
		parent::populateState($ordering, $direction);
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 * @since   1.6
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
				$user = $this->getCurrentUser();
			}
			foreach ($items as $nr => &$item)
			{
				// Remove items the user can't access.
				$access = ($user->authorise('address.access', 'com_sermondistributor.address.' . (int) $item->id) && $user->authorise('address.access', 'com_sermondistributor'));
				if (!$access)
				{
					unset($items[$nr]);
					continue;
				}

			}
		}

		// return items
		return $items;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return  string    An SQL query
	 * @since   1.6
	 */
	protected function getListQuery()
	{
		// Get the user object.
		$user = $this->getCurrentUser();
		// Create a new query object.
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the sermondistributor_item table
		$query->from($db->quoteName('#__sermondistributor_address', 'a'));

		// From the sermondistributor_address_type table.
		$query->select($db->quoteName(['g.name','g.id'],['type_name','type_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_address_type', 'g') . ' ON (' . $db->quoteName('a.type') . ' = ' . $db->quoteName('g.guid') . ')');

		// From the sermondistributor_country table.
		$query->select($db->quoteName(['h.name','h.id'],['country_name','country_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_country', 'h') . ' ON (' . $db->quoteName('a.country') . ' = ' . $db->quoteName('h.guid') . ')');

		// From the sermondistributor_state table.
		$query->select($db->quoteName(['i.name','i.id'],['state_name','state_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_state', 'i') . ' ON (' . $db->quoteName('a.state') . ' = ' . $db->quoteName('i.guid') . ')');

		// From the sermondistributor_city table.
		$query->select($db->quoteName(['j.name','j.id'],['city_name','city_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_city', 'j') . ' ON (' . $db->quoteName('a.city') . ' = ' . $db->quoteName('j.guid') . ')');

		// From the sermondistributor_preacher table.
		$query->select($db->quoteName(['k.name','k.id'],['preacher_name','preacher_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_preacher', 'k') . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('k.guid') . ')');

		// From the sermondistributor_church table.
		$query->select($db->quoteName(['l.name','l.id'],['church_name','church_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_church', 'l') . ' ON (' . $db->quoteName('a.church') . ' = ' . $db->quoteName('l.guid') . ')');

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
		elseif (UtilitiesArrayHelper::check($_access))
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
				$query->where('(a.line_one LIKE '.$search.' OR a.type LIKE '.$search.' OR g.name LIKE '.$search.' OR a.country LIKE '.$search.' OR h.name LIKE '.$search.' OR a.state LIKE '.$search.' OR i.name LIKE '.$search.' OR a.city LIKE '.$search.' OR j.name LIKE '.$search.' OR a.line_two LIKE '.$search.')');
			}
		}

		// Filter by Type.
		$_type = $this->getState('filter.type');
		if (is_numeric($_type))
		{
			if (is_float($_type))
			{
				$query->where('a.type = ' . (float) $_type);
			}
			else
			{
				$query->where('a.type = ' . (int) $_type);
			}
		}
		elseif (StringHelper::check($_type))
		{
			$query->where('a.type = ' . $db->quote($db->escape($_type)));
		}
		// Filter by Country.
		$_country = $this->getState('filter.country');
		if (is_numeric($_country))
		{
			if (is_float($_country))
			{
				$query->where('a.country = ' . (float) $_country);
			}
			else
			{
				$query->where('a.country = ' . (int) $_country);
			}
		}
		elseif (StringHelper::check($_country))
		{
			$query->where('a.country = ' . $db->quote($db->escape($_country)));
		}
		// Filter by State.
		$_state = $this->getState('filter.state');
		if (is_numeric($_state))
		{
			if (is_float($_state))
			{
				$query->where('a.state = ' . (float) $_state);
			}
			else
			{
				$query->where('a.state = ' . (int) $_state);
			}
		}
		elseif (StringHelper::check($_state))
		{
			$query->where('a.state = ' . $db->quote($db->escape($_state)));
		}
		// Filter by City.
		$_city = $this->getState('filter.city');
		if (is_numeric($_city))
		{
			if (is_float($_city))
			{
				$query->where('a.city = ' . (float) $_city);
			}
			else
			{
				$query->where('a.city = ' . (int) $_city);
			}
		}
		elseif (StringHelper::check($_city))
		{
			$query->where('a.city = ' . $db->quote($db->escape($_city)));
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
	 * Method to get a store id based on model configuration state.
	 *
	 * @return  string  A store id.
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . $this->getState('filter.id');
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.published');
		// Check if the value is an array
		$_access = $this->getState('filter.access');
		if (UtilitiesArrayHelper::check($_access))
		{
			$id .= ':' . implode(':', $_access);
		}
		// Check if this is only an number or string
		elseif (is_numeric($_access)
		 || StringHelper::check($_access))
		{
			$id .= ':' . $_access;
		}
		$id .= ':' . $this->getState('filter.ordering');
		$id .= ':' . $this->getState('filter.created_by');
		$id .= ':' . $this->getState('filter.modified_by');
		$id .= ':' . $this->getState('filter.type');
		$id .= ':' . $this->getState('filter.country');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . $this->getState('filter.city');
		$id .= ':' . $this->getState('filter.line_one');

		return parent::getStoreId($id);
	}

	/**
	 * Method to get the styles that have to be included on the view
	 *
	 * @return  array    styles files
	 * @since   4.3
	 */
	public function getStyles(): array
	{
		return $this->styles;
	}

	/**
	 * Method to set the styles that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setStyles(string $path): void
	{
		$this->styles[] = $path;
	}

	/**
	 * Method to get the script that have to be included on the view
	 *
	 * @return  array    script files
	 * @since   4.3
	 */
	public function getScripts(): array
	{
		return $this->scripts;
	}

	/**
	 * Method to set the script that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setScript(string $path): void
	{
		$this->scripts[] = $path;
	}

	/**
	 * Build an SQL query to check in all items left checked out longer then a set time.
	 *
	 * @return void
	 * @throws \DateMalformedStringException
	 * @since 3.2.0
	 */
	protected function checkInNow(): void
	{
		// Get set check in time
		$time = ComponentHelper::getParams('com_sermondistributor')->get('check_in');

		if ($time)
		{
			// Get a db connection.
			$db = $this->getDatabase();
			// Reset query.
			$query = $db->getQuery(true);
			$query->select('*');
			$query->from($db->quoteName('#__sermondistributor_address'));
			// Only select items that are checked out.
			$query->where($db->quoteName('checked_out') . ' >= 0');
			// Query only to see if we have a rows
			$db->setQuery($query, 0, 1);
			$db->execute();
			if ($db->getNumRows())
			{
				// Get target date in the past.
				$date = Factory::getDate()->modify($time)->toSql();
				// Reset query.
				$query = $db->getQuery(true);

				// Fields to update.
				$fields = [
					$db->quoteName('checked_out_time') . ' = NULL',
					$db->quoteName('checked_out') . ' = NULL'
				];

				// Conditions for which records should be updated.
				$conditions = [
					$db->quoteName('checked_out') . ' = 0 OR ' . $db->quoteName('checked_out') . ' > 0',
					$db->quoteName('checked_out_time') . ' < ' . $db->quote($date)
				];

				// Check table.
				$query->update($db->quoteName('#__sermondistributor_address'))->set($fields)->where($conditions); 

				$db->setQuery($query);

				$db->execute();
			}
		}
	}
}
