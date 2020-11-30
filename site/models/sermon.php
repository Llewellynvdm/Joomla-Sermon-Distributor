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
	@subpackage		sermon.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

/**
 * Sermondistributor Sermon Model
 */
class SermondistributorModelSermon extends JModelItem
{
	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_sermondistributor.sermon';

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
	 * @var object item
	 */
	protected $item;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since   1.6
	 *
	 * @return void
	 */
	protected function populateState()
	{
		$this->app = JFactory::getApplication();
		$this->input = $this->app->input;
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
	 */
	public function getItem($pk = null)
	{
		$this->user = JFactory::getUser();
		// check if this user has permission to access item
		if (!$this->user->authorise('site.sermon.access', 'com_sermondistributor'))
		{
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('COM_SERMONDISTRIBUTOR_NOT_AUTHORISED_TO_VIEW_SERMON'), 'error');
			// redirect away to the default view if no access allowed.
			$app->redirect(JRoute::_('index.php?option=com_sermondistributor&view=preachers'));
			return false;
		}
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->initSet = true;

		$pk = (!empty($pk)) ? $pk : (int) $this->getState('sermon.id');
		
		if ($this->_item === null)
		{
			$this->_item = array();
		}

		if (!isset($this->_item[$pk]))
		{
			try
			{
				// Get a db connection.
				$db = JFactory::getDbo();

				// Create a new query object.
				$query = $db->getQuery(true);

				// Get from #__sermondistributor_sermon as a
				$query->select($db->quoteName(
			array('a.id','a.asset_id','a.name','a.alias','a.preacher','a.short_description','a.icon','a.scripture','a.series','a.catid','a.description','a.link_type','a.source','a.build','a.manual_files','a.local_files','a.url','a.not_required','a.auto_sermons','a.published','a.created_by','a.modified_by','a.created','a.modified','a.version','a.hits','a.ordering','a.metadesc','a.metakey','a.metadata'),
			array('id','asset_id','name','alias','preacher','short_description','icon','scripture','series','catid','description','link_type','source','build','manual_files','local_files','url','not_required','auto_sermons','published','created_by','modified_by','created','modified','version','hits','ordering','metadesc','metakey','metadata')));
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
					$app = JFactory::getApplication();
					// If no data is found redirect to default page and show warning.
					$app->enqueueMessage(JText::_('COM_SERMONDISTRIBUTOR_NOT_FOUND_OR_ACCESS_DENIED'), 'warning');
					$app->redirect(JRoute::_('index.php?option=com_sermondistributor&view=preachers'));
					return false;
				}
			// Load the JEvent Dispatcher
			JPluginHelper::importPlugin('content');
			$this->_dispatcher = JEventDispatcher::getInstance();
				// Check if we can decode local_files
				if (SermondistributorHelper::checkJson($data->local_files))
				{
					// Decode local_files
					$data->local_files = json_decode($data->local_files, true);
				}
				// Check if we can decode manual_files
				if (SermondistributorHelper::checkJson($data->manual_files))
				{
					// Decode manual_files
					$data->manual_files = json_decode($data->manual_files, true);
				}
				// Check if item has params, or pass whole item.
				$params = (isset($data->params) && SermondistributorHelper::checkJson($data->params)) ? json_decode($data->params) : $data;
				// Make sure the content prepare plugins fire on description
				$_description = new stdClass();
				$_description->text =& $data->description; // value must be in text
				// Since all values are now in text (Joomla Limitation), we also add the field name (description) to context
				$this->_dispatcher->trigger("onContentPrepare", array('com_sermondistributor.sermon.description', &$_description, &$params, 0));
				// Checking if description has uikit components that must be loaded.
				$this->uikitComp = SermondistributorHelper::getUikitComp($data->description,$this->uikitComp);
				// set the global sermon value.
				$this->a_sermon = $data->id;
				// set idSermonStatisticD to the $data object.
				$data->idSermonStatisticD = $this->getIdSermonStatisticEbbd_D($data->id);

				// set data object to item.
				$this->_item[$pk] = $data;
			}
			catch (Exception $e)
			{
				if ($e->getCode() == 404)
				{
					// Need to go thru the error handler to allow Redirect to work.
					JError::raiseWarning(404, $e->getMessage());
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
			if (isset($this->_item[$pk]->auto_sermons) && SermondistributorHelper::checkString($this->_item[$pk]->auto_sermons))
			{
				// Decode the auto files
				$this->_item[$pk]->auto_sermons = json_decode($this->_item[$pk]->auto_sermons, true);
			}
			// set statistic per filename if found
			if (isset($this->_item[$pk]->idSermonStatisticD) && SermondistributorHelper::checkArray($this->_item[$pk]->idSermonStatisticD))
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
			$this->_item[$pk]->preacher_link = JRoute::_(SermondistributorHelperRoute::getPreacherRoute($this->_item[$pk]->preacher_slug));
			$this->_item[$pk]->series_link = JRoute::_(SermondistributorHelperRoute::getSeriesRoute($this->_item[$pk]->series_slug));
			$this->_item[$pk]->category_link = JRoute::_(SermondistributorHelperRoute::getCategoryRoute($this->_item[$pk]->category_slug));
			// build the download links
			SermondistributorHelper::getDownloadLinks($this->_item[$pk]);
			// fix the scripture links that they will show
			if (isset($this->_item[$pk]->scripture) && SermondistributorHelper::checkString($this->_item[$pk]->scripture))
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
				$this->_item[$pk]->scripture = JHtml::_('content.prepare', $scripture);
			}
		}

		return $this->_item[$pk];
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
		$db = JFactory::getDbo();

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
