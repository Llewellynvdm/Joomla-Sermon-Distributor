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

	@version		5.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		SermonModel.php
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
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\Input\Input;
use Joomla\Utilities\ArrayHelper;
use TrueChristianChurch\Component\Sermondistributor\Site\Helper\SermondistributorHelper;
use TrueChristianChurch\Component\Sermondistributor\Site\Helper\RouteHelper;
use Joomla\CMS\Helper\TagsHelper;
use VDM\Joomla\Utilities\JsonHelper;
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor Sermon Item Model
 *
 * @since  1.6
 */
class SermonModel extends ItemModel
{
	/**
	 * Model context string.
	 *
	 * @var     string
	 * @since   1.6
	 */
	protected $_context = 'com_sermondistributor.sermon';

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
		'components/com_sermondistributor/assets/css/sermon.css'
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
	 * A custom property for UI Kit components.
	 *
	 * @var   array|null  Property for storing UI Kit component-related data or objects.
	 * @since 3.2.0
	 */
	protected ?array $uikitComp;

	/**
	 * @var     object item
	 * @since   1.6
	 */
	protected $item;

	/**
	 * Constructor
	 *
	 * @param   array                 $config   An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   ?MVCFactoryInterface  $factory  The factory.
	 *
	 * @since   3.0
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
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function populateState()
	{
		// Get the itme main id
		$id = $this->input->getInt('id', null);
		$this->setState('sermon.id', $id);

		// Load the parameters.
		$params = $this->app->getParams();
		$this->setState('params', $params);

		parent::populateState();
	}

	/**
	 * Method to get article data.
	 *
	 * @param   integer  $pk  The id of the article.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		// check if this user has permission to access item
		if (!$this->user->authorise('site.sermon.access', 'com_sermondistributor'))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_NOT_AUTHORISED_TO_VIEW_SERMON'), 'error');
			// redirect away to the default view if no access allowed.
			$app->redirect(Route::_('index.php?option=com_sermondistributor&view=preachers'));
			return false;
		}

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('sermon.id');

		if ($this->_item === null)
		{
			$this->_item = [];
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				// Get a db connection.
				$db = $this->getDatabase();

				// Create a new query object.
				$query = $db->getQuery(true);

				// Get from #__sermondistributor_sermon as a
				$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.preacher','a.short_description','a.icon','a.scripture','a.series','a.catid','a.description','a.link_type','a.source','a.build','a.manual_files','a.local_files','a.url','a.auto_sermons','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering','a.metadesc','a.metakey','a.metadata'),
			array('id','asset_id','name','alias','preacher','short_description','icon','scripture','series','catid','description','link_type','source','build','manual_files','local_files','url','auto_sermons','published','created_by','modified_by','created','modified','version','hits','ordering','metadesc','metakey','metadata')));
				$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));

				// Get from #__sermondistributor_series as b
				$query->select($db->quoteName(
			array('b.name','b.alias'),
			array('series_name','series_alias')));
				$query->join('LEFT', ($db->quoteName('#__sermondistributor_series', 'b')) . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('b.id') . ')');

				// Get from #__sermondistributor_preacher as c
				$query->select($db->quoteName(
			array('c.name','c.alias'),
			array('preacher_name','preacher_alias')));
				$query->join('LEFT', ($db->quoteName('#__sermondistributor_preacher', 'c')) . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('c.id') . ')');

				// Get from #__categories as e
				$query->select($db->quoteName(
			array('e.alias','e.title'),
			array('category_alias','category')));
				$query->join('LEFT', ($db->quoteName('#__categories', 'e')) . ' ON (' . $db->quoteName('a.catid') . ' = ' . $db->quoteName('e.id') . ')');
				$query->where('a.access IN (' . implode(',', $this->levels) . ')');
				$query->where('a.id = ' . (int) $pk);
				// Get where a.published is 1
				$query->where('a.published = 1');
				$query->order('a.ordering ASC');

				// Reset the query using our newly populated query object.
				$db->setQuery($query);
				// Load the results as a stdClass object.
				$data = $db->loadObject();

				if (empty($data))
				{
					$app = Factory::getApplication();
					// If no data is found redirect to default page and show warning.
					$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_NOT_FOUND_OR_ACCESS_DENIED'), 'warning');
					$app->redirect(Route::_('index.php?option=com_sermondistributor&view=preachers'));
					return false;
				}
			// Load the JEvent Dispatcher
			PluginHelper::importPlugin('content');
			$this->_dispatcher = Factory::getApplication();
				// Check if we can decode local_files
				if (isset($data->local_files) && JsonHelper::check($data->local_files))
				{
					// Decode local_files
					$data->local_files = json_decode($data->local_files, true);
				}
				// Check if we can decode manual_files
				if (isset($data->manual_files) && JsonHelper::check($data->manual_files))
				{
					// Decode manual_files
					$data->manual_files = json_decode($data->manual_files, true);
				}
				// Check if item has params, or pass whole item.
				$params = (isset($data->params) && JsonHelper::check($data->params)) ? json_decode($data->params) : $data;
				// Make sure the content prepare plugins fire on description
				$_description = new \stdClass();
				$_description->text =& $data->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->triggerEvent("onContentPrepare", array('com_sermondistributor.sermon.description', &$_description, &$params, 0));
				// set the global sermon value.
				$this->a_sermon = $data->id;
				// set idSermonStatisticD to the $data object.
				$data->idSermonStatisticD = $this->getIdSermonStatisticEbbd_D($data->id);

				// set data object to item.
				$this->_item[$pk] = $data;
			}
			catch (\Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					throw $e;
				}
				else
				{
					$this->setError($e);
					$this->_item[$pk] = false;
				}
			}
		}

		if(isset($this->_item[$pk]))
		{
			// set some default tottals
			$this->_item[$pk]->statisticTotal = 0;
			// set the auto links if found
			if (isset($this->_item[$pk]->auto_sermons) && StringHelper::check($this->_item[$pk]->auto_sermons))
			{
				// Decode the auto files
				$this->_item[$pk]->auto_sermons = json_decode($this->_item[$pk]->auto_sermons, true);
			}
			// set statistic per filename if found
			if (isset($this->_item[$pk]->idSermonStatisticD) && UtilitiesArrayHelper::check($this->_item[$pk]->idSermonStatisticD))
			{
				foreach ($this->_item[$pk]->idSermonStatisticD as $statistic)
				{
					$this->_item[$pk]->statistic[$statistic->filename] = $statistic->counter;
				}
				// set the total downloads for this sermon
				$this->_item[$pk]->statisticTotal = array_sum($this->_item[$pk]->statistic);
			}
			unset($this->_item[$pk]->idSermonStatisticD);
			// build needed slugs
			$this->_item[$pk]->preacher_slug = (isset($this->_item[$pk]->preacher_alias)) ? $this->_item[$pk]->preacher.':'.$this->_item[$pk]->preacher_alias : $this->_item[$pk]->preacher;
			$this->_item[$pk]->series_slug = (isset($this->_item[$pk]->series_alias)) ? $this->_item[$pk]->series.':'.$this->_item[$pk]->series_alias : $this->_item[$pk]->series;
			$this->_item[$pk]->category_slug = (isset($this->_item[$pk]->category_alias)) ? $this->_item[$pk]->catid.':'.$this->_item[$pk]->category_alias : $this->_item[$pk]->catid;
			// now build the links
			$this->_item[$pk]->preacher_link = Route::_(RouteHelper::getPreacherRoute($this->_item[$pk]->preacher_slug));
			$this->_item[$pk]->series_link = Route::_(RouteHelper::getSeriesRoute($this->_item[$pk]->series_slug));
			$this->_item[$pk]->category_link = Route::_(RouteHelper::getCategoryRoute($this->_item[$pk]->category_slug));
			// build the download links
			SermondistributorHelper::getDownloadLinks($this->_item[$pk]);
			// fix the scripture links that they will show
			if (isset($this->_item[$pk]->scripture) && StringHelper::check($this->_item[$pk]->scripture))
			{
				if (strpos(",",$this->_item[$pk]->scripture) !== false)
				{
					$scripture = (array) explode(",",$this->_item[$pk]->scripture);
				}
				elseif (strpos(";",$this->_item[$pk]->scripture) !== false)
				{
					$scripture = (array) explode(";",$this->_item[$pk]->scripture);
				}
				else
				{
					$scripture = (array) $this->_item[$pk]->scripture;
				}
				// now load the getBible taging
				$scripture = '<p><span class="getBible">'.implode(' [in]</span></p><p><span class="getBible">',$scripture).' [in]</span></p>';
				$this->_item[$pk]->scripture = Html::_('content.prepare', $scripture);
			}
		}

		return $this->_item[$pk];
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
	public function getIdSermonStatisticEbbd_D($id)
	{
		// Get a db connection.
		$db = $this->getDatabase();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_statistic as d
		$query->select($db->quoteName(
			array('d.filename','d.counter'),
			array('filename','counter')));
		$query->from($db->quoteName('#__sermondistributor_statistic', 'd'));
		$query->where('d.sermon = ' . $db->quote($id));

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
