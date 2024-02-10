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
	@subpackage		ajax.php
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
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\ObjectHelper;
use VDM\Joomla\Utilities\GetHelper;

/**
 * Sermondistributor Ajax List Model
 */
class SermondistributorModelAjax extends ListModel
{
	protected $app_params;

	public function __construct()
	{
		parent::__construct();
		// get params
		$this->app_params = ComponentHelper::getParams('com_sermondistributor');

	}

	// Used in sermon
	/**
	* 	Update the file download counter
	**/
	public function countDownload($key,$filename)
	{
		// now count the download
		return SermondistributorHelper::countDownload($key,$filename);
	}

	protected function saveFile($data,$path_filename)
	{
		if (StringHelper::check($data))
		{
			$fp = fopen($path_filename, 'w');
			fwrite($fp, $data);
			fclose($fp);
			return true;
		}
		return false;
	}

	/**
	* 	The Queue to Update Local Listing of External Source
	**/
	public function theQueue($id, $target, $type)
	{		
		if (1 == $type)
		{
			$type = 'manual';
		}
		elseif (2 == $type)
		{
			$type = 'auto';
		}
		// the types allowed
		$types = array('manual','auto');
		// check the type
		if (StringHelper::check($type) && in_array($type,$types))
		{
			// run the updatetype
			if (SermondistributorHelper::updateExternalSource($id, $target, $type))
			{
				// now update the system if needed
				if ('auto' == $type)
				{
					$this->updateSystemWithExternalSource($id);
				}
				return array('success' => true);
			}
			// return array('error' => SermondistributorHelper::getUpdateError($id));
		}
		// return array('error' => Text::_('COM_SERMONDISTRIBUTOR_BCOULD_NOT_USE_THE_GIVEN_TOKEN_OR_THE_GIVEN_BUILD_OPTION_DOES_NOT_EXISTB'));
	}

	/**
	* 	Update the System with External Source local listing
	**/
	protected function updateSystemWithExternalSource($id)
	{
		// check if we should update with auto listing
		$links_dropbox_auto = SermondistributorHelper::getExternalSourceLink('auto', 2);
		if (UtilitiesArrayHelper::check($links_dropbox_auto))
		{
			$bucket = array();
			$db = Factory::getDbo();
			// load system aliases
			$this->getSermonAliasesUsed($db);
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
						$preacher = $this->getIdByName($preacherName,'preacher',$db);
					}
					elseif ($nr == 1 && strpos($chunk,'.') === false)
					{
						$seriesName = str_replace('-', ' ', str_replace('_', ' ', $chunk));
						$seriesName = trim(preg_replace('/\s+/', ' ', urldecode($seriesName)));
						$series = $this->getIdByName($seriesName,'series',$db);
					}
					elseif (($nr == 2 || $nr == 1) && strpos($chunk,'.') !== false)
					{
						$chunk = urldecode($chunk);
						// load thise sermon data to the global object
						$this->loadSermonData($preacher,$preacherName,$series,$seriesName,$chunk,$placeholder,$db);
					}
					$nr++;
				}
			}
			return $this->setSermons($db);
		}
		return false;
	}

	protected function setSermons($db)
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
					$done = $db->updateObject('#__sermondistributor_sermon', $sermon, 'id');
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
					$done = $db->insertObject('#__sermondistributor_sermon', $sermon);
					if ($done)
					{
						$aId =  $db->insertid();
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
		return $this->allSermonsCheckStatus($db);
	}

	protected function allSermonsCheckStatus($db)
	{
		$query = $db->getQuery(true);
		// Fields to update.
		$fields = array(
			$db->quoteName('published') . ' = 0'
		);
		if (isset($this->allSermons) && UtilitiesArrayHelper::check($this->allSermons))
		{
			// unpublish those AUTO sermons not found in this id list
			$conditions = array(
				$db->quoteName('source') . ' = 2', 
				$db->quoteName('build') . ' = 2', 
				$db->quoteName('id') . ' NOT IN (' . implode(',',$this->allSermons) . ')'
			);
		}
		else
		{
			// unpublish all AUTO sermons
			$conditions = array(
				$db->quoteName('source') . ' = 2', 
				$db->quoteName('build') . ' = 2'
			);
		}
		$query->update($db->quoteName('#__sermondistributor_sermon'))->set($fields)->where($conditions);
		$db->setQuery($query);
		return $db->execute();
	}

	protected function loadSermonData($preacher,$preacherName,$series,$seriesName,$sermon,$placeholder,$db)
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
			$category = $this->getCatogoryId($categoryName,$db);
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
		$id = $this->getSermonId($this->sermons->$key->alias,$preacher,$db);
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

	protected function getIdByName($name,$type,$db)
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
			$done = $db->insertObject('#__sermondistributor_'.$type, $object);
			// if done return last used id
			if ($done)
			{
				$newId =  $db->insertid();
				// make sure the access of asset is set
				SermondistributorHelper::setAsset($newId,$type);
				return $newId;
			}
		}
		return 0;
	}
	
	protected function getSermonId($alias = false,$preacher,$db)
	{
		if (!$alias)
		{
			// sanitize the name to an alias
			$alias = $this->getAlias($name,'sermon');
		}
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id')));
		$query->from($db->quoteName('#__sermondistributor_sermon'));
		$query->where($db->quoteName('alias') . ' = '. $db->quote($alias));
		$query->where($db->quoteName('preacher') . ' = '. $preacher);
		$query->where($db->quoteName('build') . ' = '. 2);
		$query->where($db->quoteName('source') . ' = '. 2);
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadResult();
		}
		return 0;
	}
	
	protected $category = array();
	
	protected function getCatogoryId($name,$db)
	{
		// sanitize the name to an alias
		$alias = $this->getAlias($name);
		if (!isset($this->category[$alias]))
		{
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__categories'));
			$query->where($db->quoteName('alias') . ' = '. $db->quote($alias));
			$query->where($db->quoteName('extension') . ' = '. $db->quote('com_sermondistributor.sermons'));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$this->category[$alias] = $db->loadResult();
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
	
	protected function getSermonAliasesUsed($db)
	{
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('alias')));
		$query->from($db->quoteName('#__sermondistributor_sermon'));
		$query->where($db->quoteName('build') . ' != 2');
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$aliases = $db->loadColumn();
			foreach($aliases as $alias)
			{
				$this->uniqeValueArray['sermon']['alias'][$alias] = $alias;
			}
		}
	}
}
