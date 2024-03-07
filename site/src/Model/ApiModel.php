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
	@subpackage		ApiModel.php
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
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\FileHelper;
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\Utilities\ObjectHelper;
use VDM\Joomla\Utilities\GetHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor List Model for Api
 *
 * @since  1.6
 */
class ApiModel extends ListModel
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
		'components/com_sermondistributor/assets/css/api.css'
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
		// Make sure all records load, since no pagination allowed.
		$this->setState('list.limit', 0);
		// Get a db connection.
		$db = $this->getDatabase();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_external_source as a
		$query->select($db->quoteName(
			array('a.description','a.externalsources','a.build','a.published'),
			array('description','externalsources','build','published')));
		$query->from($db->quoteName('#__sermondistributor_external_source', 'a'));
		// Get where a.published is 1
		$query->where('a.published = 1');

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
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = ComponentHelper::getParams('com_sermondistributor', true);

		// Insure all item fields are adapted where needed.
		if (UtilitiesArrayHelper::check($items))
		{
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = ($item->id ?? '0') . (isset($item->alias) ? ':' . $item->alias : '');
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
	* 	Run External Update
	**/
	public function setExternalUpdate()
	{
		// get the next update value
		$update = SermondistributorHelper::getNextUpdateValues(true); // id, target, typeID
		if (UtilitiesArrayHelper::check($update))
		{
			// add a worker
			SermondistributorHelper::setWorker($update, 'theQueue');
		}
	}

	/**
	* 	The Queue to Update Local Listing of External Source
	**/
	public function theQueue($id, $target, $typeID)
	{		
		if (1 == $typeID)
		{
			$type = 'manual';
		}
		elseif (2 == $typeID)
		{
			$type = 'auto';
		}
		// first get the file path
		$path_filename = FileHelper::getPath('update', 'error', $id.$target.$typeID, '.txt', JPATH_COMPONENT_ADMINISTRATOR);
		// check the type
		if (isset($type) && StringHelper::check($type))
		{
			// run the updatetype
			if (SermondistributorHelper::updateExternalSource($id, $target, $type))
			{
				// now update the system if needed
				if ('auto' == $type)
				{
					$this->runUpdateSystemWithExternalSource = true;
				}
				SermondistributorHelper::writeFile($path_filename,'success');
				return true;
			}
			SermondistributorHelper::writeFile($path_filename,SermondistributorHelper::getUpdateError($id));
			return false;
		}
		SermondistributorHelper::writeFile($path_filename,Text::_('COM_SERMONDISTRIBUTOR_BTHERE_WAS_AN_ERRORB'));
		return false;
	}

	/**
	* 	Update the System with External Source local listing
	**/
	public function updateSystemWithExternalSource()
	{
		// check if we should update with auto listing
		$links_dropbox_auto = SermondistributorHelper::getExternalSourceLink('auto', 2);
		if (UtilitiesArrayHelper::check($links_dropbox_auto))
		{
			// load system aliases
			$this->getSermonAliasesUsed();
			// set the class var for sermons
			$this->sermons = new \stdClass();
			// we must first get all the preacher names
			foreach ($links_dropbox_auto as $placeholder => $link)
			{
				// convert the name to get needed info
				$name = str_replace('VDM_pLeK_h0uEr/', '', $placeholder);
				$chunks = (array) explode('/', $name);
				// set some defaults
				$preacherName = '';
				$seriesName = '';
				$preacher = 0;
				$series = 0;
				$nr = 0;
				foreach ($chunks as $chunk)
				{
					if ($nr == 0 && strpos($chunk,'.') === false)
					{
						$preacherName = str_replace('-', ' ', str_replace('_', ' ', $chunk));
						$preacherName = trim(preg_replace('/\s+/', ' ', urldecode($preacherName)));
						$preacher = $this->getIdByName($preacherName,'preacher');
					}
					elseif ($nr == 1 && strpos($chunk,'.') === false)
					{
						$seriesName = str_replace('-', ' ', str_replace('_', ' ', $chunk));
						$seriesName = trim(preg_replace('/\s+/', ' ', urldecode($seriesName)));
						$series = $this->getIdByName($seriesName,'series');
					}
					elseif (($nr == 2 || $nr == 1) && strpos($chunk,'.') !== false)
					{
						$chunk = urldecode($chunk);
						// load thise sermon data to the global object
						$this->loadSermonData($preacher,$preacherName,$series,$seriesName,$chunk,$placeholder);
					}
					$nr++;
				}
			}
			return $this->setSermons();
		}
		return false;
	}

	protected function setSermons()
	{
		// check if we have values
		if (ObjectHelper::check($this->sermons))
		{
			foreach ($this->sermons as $sermon)
			{
				// make sure the lock is removed
				unset($sermon->lock);
				// convert the placeholders to json object
				$registry = new JRegistry;
				$registry->loadArray($sermon->auto_sermons);
				$sermon->auto_sermons = (string) $registry;
				// always the same
				$sermon->link_type = $this->app_params->get('auto_link_type', 1);
				$sermon->source = 2;
				$sermon->build = 2;
				// reset id
				$aId = 0;
				if (isset($sermon->id) && $sermon->id)
				{
					// change to modified
					$sermon->modified = $sermon->created;
					// make sure we dont change these if 
					// the sermon is being updated
					unset($sermon->created);
					unset($sermon->metadesc);
					unset($sermon->metakey);
					unset($sermon->metadata);
					
					// update
					$done = $this->db->updateObject('#__sermondistributor_sermon', $sermon, 'id');
					if ($done)
					{
						$aId = $sermon->id;
					}
				}
				else
				{
					// add some defaults
					$sermon->version = 1;
					$sermon->published = $this->app_params->get('sermon_state', 1);
					$sermon->access = 1; // TODO must use a global setting here
					$sermon->metakey = implode(', ',array_unique($sermon->metakey));
					// insert
					$done = $this->db->insertObject('#__sermondistributor_sermon', $sermon);
					if ($done)
					{
						$aId =  $this->db->insertid();
						// make sure the access of asset is set
						SermondistributorHelper::setAsset($aId,'sermon');
					}
				}
				// set global id list
				if ($aId > 0)
				{
					$this->allSermons[] = $aId;
				}
			}
		}
		return $this->allSermonsCheckStatus();
	}

	protected function allSermonsCheckStatus()
	{
		$query = $this->db->getQuery(true);
		// Fields to update.
		$fields = array(
			$this->db->quoteName('published') . ' = 0'
		);
		if (isset($this->allSermons) && UtilitiesArrayHelper::check($this->allSermons))
		{
			// unpublish those AUTO sermons not found in this id list
			$conditions = array(
				$this->db->quoteName('source') . ' = 2', 
				$this->db->quoteName('build') . ' = 2', 
				$this->db->quoteName('id') . ' NOT IN (' . implode(',',$this->allSermons) . ')'
			);
		}
		else
		{
			// unpublish all AUTO sermons
			$conditions = array(
				$this->db->quoteName('source') . ' = 2', 
				$this->db->quoteName('build') . ' = 2'
			);
		}
		$query->update($this->db->quoteName('#__sermondistributor_sermon'))->set($fields)->where($conditions);
		$this->db->setQuery($query);
		return $this->db->execute();
	}

	protected function loadSermonData($preacher,$preacherName,$series,$seriesName,$sermon,$placeholder)
	{
		// 34key521__the_file_name___tag1_tag-with2_tag-three____Salvation_Messages_____The_short_description_can_only_be_one_sentence.mp3
		$description = '';
		$category = 0;
		$tags = false;
		$combined = false;
		// remove the file type from the string
		$fileType = pathinfo($sermon, PATHINFO_EXTENSION);
		if ($fileType)
		{
			$sermon = str_replace('.'.$fileType, '', $sermon);
		}
		else
		{
			$fileType = 'error';
		}
		// first we check if this file has discription
		if (strpos($sermon,'_____') !== false)
		{
			list($sermon, $description) = explode('_____', $sermon);
			$description = preg_replace('/\s+/', ' ', str_replace('-', ' ', str_replace('_', ' ', $description))).'.';
		}
		// second we check if this file has category
		if (strpos($sermon,'____') !== false)
		{
			list($sermon, $categoryName) = explode('____', $sermon);
			$categoryName = preg_replace('/\s+/', ' ', str_replace('-', ' ', str_replace('_', ' ',$categoryName)));
			$category = $this->getCatogoryId($categoryName);
		}
		// third we check if this file has tags
		if (strpos($sermon,'___') !== false)
		{
			list($sermon, $tags) = explode('___', $sermon); // TODO not loading the tags yet, soon...
		}
		// forth we check if this file is combined with another
		if (strpos($sermon,'__') !== false)
		{
			list($key,$sermon) = explode('__', $sermon);
			$key = md5($key);
			$combined = true;
		}
		else
		{
			$key = md5($sermon.$preacher.$series.$category);
		}
		// set the name of the sermon
		$name = trim(preg_replace('/\s+/', ' ', str_replace('-', ' ', str_replace('_', ' ', $sermon))));		
		// check if this sermon has been set
		if (isset($this->sermons->$key))
		{
			$SERMON = strtoupper($sermon);
			if ($combined && $sermon == $SERMON)
			{
				$name = ucfirst(strtolower($name));
				$this->sermons->$key->name = $name;
				// make sure we always use the new name as alias
				$this->sermons->$key->alias = $this->getAlias($name,'sermon');
				// lock item so other will not change the name
				$this->sermons->$key->lock = true;
			}
			elseif ($combined && !isset($this->sermons->$key->lock) && strlen($name) > strlen($this->sermons->$key->name))
			{
				$this->sermons->$key->name = $name;
				// make sure we always use the new name as alias
				$this->sermons->$key->alias = $this->getAlias($name,'sermon');
			}
		}
		else
		{
			// load the sermon data
			$this->sermons->$key = new \stdClass();
		}
		// check if this value has been set
		if (!isset($this->sermons->$key->alias))
		{
			// make sure we always use the new name as alias
			$this->sermons->$key->alias = $this->getAlias($name,'sermon');
		}
		
		// check if this item exist (update if it does)
		$id = $this->getSermonId($this->sermons->$key->alias,$preacher);
		if ($id)
		{
			$this->sermons->$key->id = $id;
		}
		// check if this value has been set
		if (!isset($this->sermons->$key->name))
		{
			$this->sermons->$key->name = $name;
		}
		// check if this value has been set
		if (!isset($this->sermons->$key->short_description) && StringHelper::check($description))
		{
			$this->sermons->$key->short_description = $description;
		}
		// check if this value has been set
		if (!isset($this->sermons->$key->preacher) && $preacher > 0)
		{
			$this->sermons->$key->preacher = $preacher;
		}
		// check if this value has been set
		if (!isset($this->sermons->$key->series) && $series > 0)
		{
			$this->sermons->$key->series = $series;
		}
		// check if this value has been set
		if (!isset($this->sermons->$key->catid) && $category > 0)
		{
			$this->sermons->$key->catid = $category;
		}
		// check if this value has been set
		if (!isset($this->sermons->$key->created))
		{
			// set the date object
			$date = Factory::getDate();
			$this->sermons->$key->created = $date->toSql();
		}		
		// build the Download File NAme - first add the preacher name if set
		if ($preacher)
		{
			$downloadName[] = StringHelper::safe($preacherName,'U');
		}
		// add the series name if set
		if ($series)
		{
			$downloadName[] = StringHelper::safe($seriesName,'F');
		}
		// add the category name if set
		if ($category)
		{
			$downloadName[] = StringHelper::safe($categoryName, 'F');
		}
		// add the main file name
		$downloadName[] = StringHelper::safe($name,'F');
		// now build the download file name
		$downloadName = implode('__', $downloadName).'.'.$fileType;			
		// load the placeholder to the sermon
		$this->sermons->$key->auto_sermons[$downloadName] = $placeholder;
		// set default metadate
		if (!isset($this->sermons->$key->metadesc) && StringHelper::check($description))
		{	
			// Only process once per/sermon
			$bad_characters = array("\"", "<", ">");
			$this->sermons->$key->metadesc = JString::str_ireplace($bad_characters, "", $description); // just to be save
		}
		// Array of characters to remove.
		$bad_characters = array("\n", "\r", "\"", "<", ">");
		// set the meta key words
		$this->sermons->$key->metakey[] = trim(JString::str_ireplace($bad_characters, '', $name)); // just to be save
		if ($series)
		{
			$this->sermons->$key->metakey[] = trim(JString::str_ireplace($bad_characters, '', $seriesName)); // just to be save
		}
		if ($preacher)
		{
			$this->sermons->$key->metakey[] = trim(JString::str_ireplace($bad_characters, '', $preacherName)); // just to be save
		}
		if ($category)
		{
			$this->sermons->$key->metakey[] = trim(JString::str_ireplace($bad_characters, '', $categoryName)); // just to be save
		}
		$author = ($preacher) ? trim($preacherName): '';
		$this->sermons->$key->metadata = '{"robots":"","author":"'.$author.'","rights":"See our site terms."}';
		
		return true;
	}
	
	protected function getAlias($name,$type = false)
	{
		// sanitize the name to an alias
		if (Factory::getConfig()->get('unicodeslugs') == 1)
		{
			$alias = JFilterOutput::stringURLUnicodeSlug($name);
		}
		else
		{
			$alias = JFilterOutput::stringURLSafe($name);
		}
		if ($type)
		{
			return $this->getUniqe($alias,'alias',$type);
		}
		return $alias;
	}

	protected function getIdByName($name,$type)
	{
		// sanitize the name to an alias
		$alias = $this->getAlias($name);
		// check if there is a recored
		if ($id = GetHelper::var($type, $alias, 'alias', 'id'))
		{
			return $id;
		}
		else
		{
			// check if the name is all lowercase
			if ($name == strtolower($name))
			{
				// the whole name is lowercase then fix
				$name = ucwords($name);
			}
			// create the record
			$object = new \stdClass();
			// set the date object
			$date = Factory::getDate();
			// build the object
			$object->name			= $name;
			$object->alias			= $alias;
			$object->published		= $this->app_params->get($type.'_state', 1);
			$object->created		= $date->toSql();
			$object->version		= 1;
			$object->access			= 1; // TODO must use a global setting here
			// Insert the object into the table.
			$done = $this->db->insertObject('#__sermondistributor_'.$type, $object);
			// if done return last used id
			if ($done)
			{
				$newId =  $this->db->insertid();
				// make sure the access of asset is set
				SermondistributorHelper::setAsset($newId,$type);
				return $newId;
			}
		}
		return 0;
	}
	
	protected function getSermonId($alias = false,$preacher)
	{
		if (!$alias)
		{
			// sanitize the name to an alias
			$alias = $this->getAlias($name,'sermon');
		}
		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->select($this->db->quoteName(array('id')));
		$query->from($this->db->quoteName('#__sermondistributor_sermon'));
		$query->where($this->db->quoteName('alias') . ' = '. $this->db->quote($alias));
		$query->where($this->db->quoteName('preacher') . ' = '. $preacher);
		$query->where($this->db->quoteName('build') . ' = '. 2);
		$query->where($this->db->quoteName('source') . ' = '. 2);
		$this->db->setQuery($query);
		$this->db->execute();
		if ($this->db->getNumRows())
		{
			return $this->db->loadResult();
		}
		return 0;
	}
	
	protected $category = array();
	
	protected function getCatogoryId($name)
	{
		// sanitize the name to an alias
		$alias = $this->getAlias($name);
		if (!isset($this->category[$alias]))
		{
			// Create a new query object.
			$query = $this->db->getQuery(true);
			$query->select($this->db->quoteName(array('id')));
			$query->from($this->db->quoteName('#__categories'));
			$query->where($this->db->quoteName('alias') . ' = '. $this->db->quote($alias));
			$query->where($this->db->quoteName('extension') . ' = '. $this->db->quote('com_sermondistributor.sermons'));
			$this->db->setQuery($query);
			$this->db->execute();
			if ($this->db->getNumRows())
			{
				$this->category[$alias] = $this->db->loadResult();
			}
			else
			{
				// if still not set, then create category
				$this->category[$alias] = $this->createCategory($name,$alias);
			}
		}
		return $this->category[$alias];
	}
	
	protected function createCategory($name,$alias)
	{
		// load the category table
		JTable::addIncludePath(JPATH_LIBRARIES . '/joomla/database/table');
		$category = JTable::getInstance('Category');
		$category->extension = 'com_sermondistributor.sermons';
		$category->title = $name;
		$category->alias = $alias;
		$category->description = '';
		$category->published = 1;
		$category->access = 1;
		$category->params = '{"category_layout":"","image":"","image_alt":""}';
		$category->metadata = '{"author":"","robots":""}';
		$category->language = '*';
		$category->setLocation(1, 'last-child');
		$category->store(true);
		$category->rebuildPath($category->id);
		return $category->id;
	}
	
	/**
	 * Method to generate a uniqe value.
	 *
	 * @param   string  $field name.
	 * @param   string  $value data.
	 * @param   string  $type table.
	 *
	 * @return  string  New value.
	 */
	protected function getUniqe($value,$field,$type)
	{
		// insure the filed is always uniqe
		while (isset($this->uniqeValueArray[$type][$field][$value]))
		{
			$value = JString::increment($value, 'dash');
		}
		$this->uniqeValueArray[$type][$field][$value] = $value;
		return $value;
	}
	
	protected function getSermonAliasesUsed()
	{
		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->select($this->db->quoteName(array('alias')));
		$query->from($this->db->quoteName('#__sermondistributor_sermon'));
		$query->where($this->db->quoteName('build') . ' != 2');
		$this->db->setQuery($query);
		$this->db->execute();
		if ($this->db->getNumRows())
		{
			$aliases = $this->db->loadColumn();
			foreach($aliases as $alias)
			{
				$this->uniqeValueArray['sermon']['alias'][$alias] = $alias;
			}
		}
	}
}
