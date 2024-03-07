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

	@version		4.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		CategoryModel.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace TrueChristianChurch\Component\Sermondistributor\Site\Model;

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
use TrueChristianChurch\Component\Sermondistributor\Site\Helper\SermondistributorHelper;
use TrueChristianChurch\Component\Sermondistributor\Site\Helper\RouteHelper;
use Joomla\CMS\Helper\TagsHelper;
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\JsonHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor List Model for Category
 *
 * @since  1.6
 */
class CategoryModel extends ListModel
{
	/**
	 * Represents the current user object.
	 *
	 * @var   User  The user object representing the current user.
	 * @since 3.2.0
	 */
	protected User $user;

	/**
	 * The unique identifier of the current user.
	 *
	 * @var   int|null  The ID of the current user.
	 * @since 3.2.0
	 */
	protected ?int $userId;

	/**
	 * Flag indicating whether the current user is a guest.
	 *
	 * @var   int  1 if the user is a guest, 0 otherwise.
	 * @since 3.2.0
	 */
	protected int $guest;

	/**
	 * An array of groups that the current user belongs to.
	 *
	 * @var   array|null  An array of user group IDs.
	 * @since 3.2.0
	 */
	protected ?array $groups;

	/**
	 * An array of view access levels for the current user.
	 *
	 * @var   array|null  An array of access level IDs.
	 * @since 3.2.0
	 */
	protected ?array $levels;

	/**
	 * The application object.
	 *
	 * @var   CMSApplicationInterface  The application instance.
	 * @since 3.2.0
	 */
	protected CMSApplicationInterface $app;

	/**
	 * The input object, providing access to the request data.
	 *
	 * @var   Input  The input object.
	 * @since 3.2.0
	 */
	protected Input $input;

	/**
	 * The styles array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $styles = [
		'components/com_sermondistributor/assets/css/site.css',
		'components/com_sermondistributor/assets/css/category.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'components/com_sermondistributor/assets/js/site.js'
 	];

	/**
	 * A custom property for UIKit components. (not used unless you load v2)
	 */
	protected $uikitComp;

	/**
	 * Constructor
	 *
	 * @param   array                 $config   An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   ?MVCFactoryInterface  $factory  The factory.
	 *
	 * @since   1.6
	 * @throws  \Exception
	 */
	public function __construct($config = [], MVCFactoryInterface $factory = null)
	{
		parent::__construct($config, $factory);

		$this->app ??= Factory::getApplication();
		$this->input ??= $this->app->getInput();

		// Set the current user for authorisation checks (for those calling this model directly)
		$this->user ??= $this->getCurrentUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();

		// will be removed
		$this->initSet = true;
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return   string  An SQL query
	 * @since    1.6
	 */
	protected function getListQuery()
	{
		// Get a db connection.
		$db = $this->getDatabase();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_sermon as a
		$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.link_type','a.short_description','a.icon','a.preacher','a.series','a.catid','a.description','a.source','a.build','a.manual_files','a.local_files','a.url','a.auto_sermons','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering'),
			array('id','asset_id','name','alias','link_type','short_description','icon','preacher','series','catid','description','source','build','manual_files','local_files','url','auto_sermons','published','created_by','modified_by','created','modified','version','hits','ordering')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));

		// Get from #__sermondistributor_preacher as c
		$query->select($db->quoteName(
			array('c.name','c.alias'),
			array('preacher_name','preacher_alias')));
		$query->join('LEFT', ($db->quoteName('#__sermondistributor_preacher', 'c')) . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('c.id') . ')');

		// Get from #__sermondistributor_series as d
		$query->select($db->quoteName(
			array('d.name','d.alias'),
			array('series_name','series_alias')));
		$query->join('LEFT', ($db->quoteName('#__sermondistributor_series', 'd')) . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('d.id') . ')');

		// Get from #__categories as b
		$query->select($db->quoteName(
			array('b.title'),
			array('category')));
		$query->join('LEFT', ($db->quoteName('#__categories', 'b')) . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('b.id') . ')');
		$query->where('a.access IN (' . implode(',', $this->levels) . ')');
		// Check if $this->input->getInt('id', 0) is a string or numeric value.
		$checkValue = $this->input->getInt('id', 0);
		if (isset($checkValue) && StringHelper::check($checkValue))
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
	 * @since   1.6
	 */
	public function getItems()
	{
		$user = $this->user;
		// check if this user has permission to access item
		if (!$user->authorise('site.category.access', 'com_sermondistributor'))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_NOT_AUTHORISED_TO_VIEW_CATEGORY'), 'error');
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
				// Check if we can decode local_files
				if (isset($item->local_files) && JsonHelper::check($item->local_files))
				{
					// Decode local_files
					$item->local_files = json_decode($item->local_files, true);
				}
				// Check if we can decode manual_files
				if (isset($item->manual_files) && JsonHelper::check($item->manual_files))
				{
					// Decode manual_files
					$item->manual_files = json_decode($item->manual_files, true);
				}
				// Check if item has params, or pass whole item.
				$params = (isset($item->params) && JsonHelper::check($item->params)) ? json_decode($item->params) : $item;
				// Make sure the content prepare plugins fire on description
				$_description = new \stdClass();
				$_description->text =& $item->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->triggerEvent("onContentPrepare", array('com_sermondistributor.category.description', &$_description, &$params, 0));
				// set idSermonStatisticE to the $item object.
				$item->idSermonStatisticE = $this->getIdSermonStatisticBcea_E($item->id);
			}
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
				if (isset($item->auto_sermons) && StringHelper::check($item->auto_sermons))
				{
					// Decode the auto files
					$item->auto_sermons = json_decode($item->auto_sermons, true);
				}
				// set statistic per filename if found
				if (isset($item->idSermonStatisticE) && UtilitiesArrayHelper::check($item->idSermonStatisticE))
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
				$item->link = Route::_(RouteHelper::getSermonRoute($item->slug));
				$item->preacher_link = Route::_(RouteHelper::getPreacherRoute($item->preacher_slug));
				$item->series_link = Route::_(RouteHelper::getSeriesRoute($item->series_slug));
				// set view key
				$item->viewKey = 'category';
				SermondistributorHelper::getDownloadLinks($item);
			}
		}

		// return items
		return $items;
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
	 * Method to get an array of Statistic Objects.
	 *
	 * @return mixed  An array of Statistic Objects on success, false on failure.
	 *
	 */
	public function getIdSermonStatisticBcea_E($id)
	{
		// Get a db connection.
		$db = $this->getDatabase();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_statistic as e
		$query->select($db->quoteName(
			array('e.filename','e.sermon','e.preacher','e.series','e.counter'),
			array('filename','sermon','preacher','series','counter')));
		$query->from($db->quoteName('#__sermondistributor_statistic', 'e'));
		$query->where('e.sermon = ' . $db->quote($id));

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
	 * Custom Method
	 *
	 * @return mixed  item data object on success, false on failure.
	 *
	 */
	public function getCategory()
	{
		// Get a db connection.
		$db = $this->getDatabase();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__categories as a
		$query->select($db->quoteName(
			array('a.id','a.parent_id','a.lft','a.rgt','a.level','a.title','a.alias','a.note','a.description','a.params','a.metadesc','a.metakey','a.metadata','a.hits','a.language','a.version'),
			array('id','parent_id','lft','rgt','level','name','alias','note','description','params','metadesc','metakey','metadata','hits','language','version')));
		$query->from($db->quoteName('#__categories', 'a'));
		// Check if $this->input->getInt('id', 0) is a string or numeric value.
		$checkValue = $this->input->getInt('id', 0);
		if (isset($checkValue) && StringHelper::check($checkValue))
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
		// Get where a.published is 1
		$query->where('a.published = 1');
		$query->order('a.title ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		// Load the results as a stdClass object.
		$data = $db->loadObject();

		if (empty($data))
		{
			return false;
		}
		// set idCatidSermonB to the $data object.
		$data->idCatidSermonB = $this->getIdCatidSermonFbdf_B($data->id);

		// return data object.
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
		// Get a db connection.
		$db = $this->getDatabase();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_sermon as b
		$query->select($db->quoteName(
			array('b.id'),
			array('id')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'b'));
		$query->where('b.catid = ' . $db->quote($id));

		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();

		// check if there was data returned
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// Convert the parameter fields into objects.
			foreach ($items as $nr => &$item)
			{
				// set idSermonStatisticC to the $item object.
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
		// Get a db connection.
		$db = $this->getDatabase();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_statistic as c
		$query->select($db->quoteName(
			array('c.counter'),
			array('counter')));
		$query->from($db->quoteName('#__sermondistributor_statistic', 'c'));
		$query->where('c.sermon = ' . $db->quote($id));

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

}
