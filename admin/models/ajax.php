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
	@subpackage		ajax.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

/**
 * Sermondistributor Ajax Model
 */
class SermondistributorModelAjax extends JModelList
{
	protected $app_params;
	
	public function __construct() 
	{		
		parent::__construct();		
		// get params
		$this->app_params	= JComponentHelper::getParams('com_sermondistributor');
		
	}

	// Used in sermon

	/**
	* 	Check and if a vdm notice is new (per/user)
	**/
	public function isNew($notice)
	{
		// first get the file path
		$path_filename = SermondistributorHelper::getFilePath('path', 'usernotice', 'md', JFactory::getUser()->username, JPATH_COMPONENT_ADMINISTRATOR);
		// check if the file is set
		if (($content = @file_get_contents($path_filename)) !== FALSE)
		{
			if ($notice == $content)
			{
				return false;
			}
		}
		return true;
	}

	/**
	* 	set That a notice has been read (per/user)
	**/
	public function isRead($notice)
	{
		// first get the file path
		$path_filename = SermondistributorHelper::getFilePath('path', 'usernotice', 'md', JFactory::getUser()->username, JPATH_COMPONENT_ADMINISTRATOR);
		// set as read if not already set
		if (($content = @file_get_contents($path_filename)) !== FALSE)
		{
			if ($notice == $content)
			{
				return true;
			}
		}
		return $this->saveFile($notice,$path_filename);
	}

	protected function saveFile($data,$path_filename)
	{
		if (SermondistributorHelper::checkString($data))
		{
			$fp = fopen($path_filename, 'w');
			fwrite($fp, $data);
			fclose($fp);
			return true;
		}
		return false;
	}

	// Used in external_source
	protected $functionArray = array();
	
	protected $languageArray = array();

	protected function setLanguage($key,$lang)
	{
		$this->languageArray[$key] = $lang;
	}

	public function getLanguage()
	{
		// return the language string that were set
		return $this->languageArray;
	}

	protected function setAutoLangZero()
	{
		// set the headers
		$headers = array(
			'tsharedurl' => JText::_('COM_SERMONDISTRIBUTOR_SHAREDURL'),
			'tfolder' => JText::_('COM_SERMONDISTRIBUTOR_TARGETED_FOLDER')
		);
		// loop the array
		foreach ($headers as $key => $lang)
		{
			$this->setLanguage($key,$lang);
		}
	}
	
	protected function autoLoader()
	{
		$functions = range(0,10);
		foreach ($functions as $function)
		{
			$function = 'setAutoLang'.SermondistributorHelper::safeString($function, 'f');
			if (method_exists($this, $function))
			{
				$this->{$function}();
			}
		}
		foreach ($functions as $function)
		{
			$function = 'setAutoFunc'.SermondistributorHelper::safeString($function, 'f');
			if (method_exists($this, $function))
			{
				$this->{$function}();
			}
		}
	}

	public function getBuildTable($idName,$oject)
	{
		if (SermondistributorHelper::checkJson($oject) && SermondistributorHelper::checkString($idName))
		{
			$array = json_decode($oject, true);
			if (SermondistributorHelper::checkArray($array))
			{ 
				// make sure we run the autoloader to insure all is set
				$this->autoLoader();
				// set the target headers
				$targetHeaders 	= $this->getLanguage();
				// start table build
				$table 		= '<table id="table_'.$idName.'" class="uk-table" style="margin: 5px 0 20px;"><thead><tr>';
				$rows		= array();
				foreach ($array as $header => $values)
				{
					if (SermondistributorHelper::checkArray($values))
					{
						$targetHeader = (isset($targetHeaders[$header])) ? $targetHeaders[$header] : SermondistributorHelper::safeString($header, 'W');
						$table .= '<th style="padding: 10px; text-align: center; border: 1px solid rgb(221, 221, 221);" scope="col">'.$targetHeader.'</th>';
						foreach ($values as $nr => $value)
						{
							// set the value for the row
							$this->setRows($nr, $this->setValue($header, $value), $rows);
						}
					}
				}
				// close header start body
				$table .= '</tr></thead><tbody>';
				// add rows to table
				if (SermondistributorHelper::checkArray($rows))
				{
					foreach ($rows as $row)
					{
						$table .= '<tr>'.$row.'</tr>';
					}
				}
				
				// close the body and table
				$table .= '</tbody></table>';
				// return the table
				return $table;
			}
		}
		return false;
	}

	protected function setValue($header,$value)
	{
		if (array_key_exists($header, $this->functionArray) && method_exists($this, $this->functionArray[$header]))
		{
			$value = $this->{$this->functionArray[$header]}($header,$value);
		}
		// if no value are set
		if (!SermondistributorHelper::checkString($value))
		{
			$value = '-';
		}
		return $value;
	}

	protected function setRows($nr, $value, &$rows)
	{
		// build rows
		if (!isset($rows[$nr]))
		{
			$rows[$nr] = '<td style="padding: 10px; text-align: center; border: 1px solid rgb(221, 221, 221);">'.$value.'</td>';
		}
		else
		{
			$rows[$nr] .= '<td style="padding: 10px; text-align: center; border: 1px solid rgb(221, 221, 221);">'.$value.'</td>';
		}
	}

	public function getSourceStatus($id)
	{
		return SermondistributorHelper::getSourceStatus($id);
	}	

	/**
	* 	get the component details (html)
	**/
	public function getCronPath($type)
	{
		$result = array('error' => '<span style="color: red;">' . JText::_('COM_SERMONDISTRIBUTOR_NO_CRONJOB_PATH_FOUND_SINCE_INCORRECT_TYPE_REQUESTED') . '</span>');
		if ('externalUpdate' === $type)
		{
			$result['error'] = '<span style="color: red;">' . JText::sprintf('COM_SERMONDISTRIBUTOR_NO_CRONJOB_PATH_FOUND_FOR_S', $type) . '</span>';
			if ($this->hasCurl())
			{
				$path = '*/15 * * * * curl -s "' .JURI::root() . 'index.php?option=com_sermondistributor&task=api.externalUpdate" >/dev/null 2>&1';
			}
			else
			{
				$path = '*/15 * * * * wget "' .JURI::root() . 'index.php?option=com_sermondistributor&task=api.externalUpdate" >/dev/null 2>&1';
			}
			$result['path'] =  '<code>' . $path . '</code>';
		}
		return $result;
	}
	
	protected function hasCurl()
	{
		return function_exists('curl_version');
	}

	// Used in manual_updater
	/**
	* 	Update Local Listing of External Source (manually)
	**/
	public function updateLocalListingExternal($id, $target, $typeID, $sleutel)
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
		$path_filename = SermondistributorHelper::getFilePath('update', 'error', $id.$target.$typeID, '.txt', JPATH_COMPONENT_ADMINISTRATOR);
		// check "die sleutel" and the type
		if (isset($type) && SermondistributorHelper::checkString($sleutel))
		{
			// get database object
			if (!isset($this->db) || !SermondistributorHelper::checkObject($this->db))
			{
				$this->db = JFactory::getDBO();
			}
			// run the update
			if (SermondistributorHelper::updateExternalSource($id, $target, $type, true, $sleutel))
			{
				// now update the system if needed
				if ('auto' == $type)
				{
					$this->updateSystemWithExternalSource();
				}
				SermondistributorHelper::writeFile($path_filename,'success');
				return array('success' => true);
			}
			// store the error
			$error = SermondistributorHelper::getUpdateError($id);
			SermondistributorHelper::writeFile($path_filename,$error);
			// return the error
			return array('error' => $error);
		}
		// store the error
		$error = JText::_('COM_SERMONDISTRIBUTOR_BCOULD_NOT_USE_THE_GIVEN_TOKEN_OR_THE_GIVEN_BUILD_OPTION_DOES_NOT_EXISTB');
		SermondistributorHelper::writeFile($path_filename,$error);
		return array('error' => $error);
	}

	/**
	* 	Update the System with External Source local listing
	**/
	public function updateSystemWithExternalSource()
	{
		// check if we should update with auto listing
		$links_dropbox_auto = SermondistributorHelper::getExternalSourceLink('auto', 2);
		if (SermondistributorHelper::checkArray($links_dropbox_auto))
		{
			// load system aliases
			$this->getSermonAliasesUsed();
			// set the class var for sermons
			$this->sermons = new stdClass();
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
		if (SermondistributorHelper::checkObject($this->sermons))
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
		if (isset($this->allSermons) && SermondistributorHelper::checkArray($this->allSermons))
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
			$this->sermons->$key = new stdClass();
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
		if (!isset($this->sermons->$key->short_description) && SermondistributorHelper::checkString($description))
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
			$date = JFactory::getDate();
			$this->sermons->$key->created = $date->toSql();
		}		
		// build the Download File NAme - first add the preacher name if set
		if ($preacher)
		{
			$downloadName[] = SermondistributorHelper::safeString($preacherName,'U');
		}
		// add the series name if set
		if ($series)
		{
			$downloadName[] = SermondistributorHelper::safeString($seriesName,'F');
		}
		// add the category name if set
		if ($category)
		{
			$downloadName[] = SermondistributorHelper::safeString($categoryName, 'F');
		}
		// add the main file name
		$downloadName[] = SermondistributorHelper::safeString($name,'F');
		// now build the download file name
		$downloadName = implode('__', $downloadName).'.'.$fileType;			
		// load the placeholder to the sermon
		$this->sermons->$key->auto_sermons[$downloadName] = $placeholder;
		// set default metadate
		if (!isset($this->sermons->$key->metadesc) && SermondistributorHelper::checkString($description))
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
		if (JFactory::getConfig()->get('unicodeslugs') == 1)
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
		if ($id = SermondistributorHelper::getVar($type, $alias, 'alias', 'id'))
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
			$object = new stdClass();
			// set the date object
			$date = JFactory::getDate();
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
