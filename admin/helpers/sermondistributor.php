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

	@version		1.4.1
	@build			17th February, 2017
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermondistributor.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Sermondistributor component helper.
 */
abstract class SermondistributorHelper
{

	/**
	*	The Global Admin Event Method.
	**/
	public static function globalEvent($document)
	{
		self::loadExternalUpdateAjax($document);
	} 

	/**
	* 	Load the External Update Ajax to page
	**/
	public static function loadExternalUpdateAjax($document)
	{
		$update = self::getNextUpdateValues(); // id, target, type
		if ($update)
		{
			$document->addScriptDeclaration("
			jQuery(window).load(function() {
				theQueue(".$update.");
			});
			
			function theQueue(id, target, type) {
				var getUrl = '".JURI::root()."administrator/index.php?option=com_sermondistributor&task=ajax.theQueue&format=json';
				if(target > 0 && type > 0 && id > 0){
					var request = 'token=".JSession::getFormToken()."&tar='+target+'&list='+id+'&type='+type;
				}
				return jQuery.ajax({
					type: 'GET',
					url: getUrl,
					dataType: 'jsonp',
					data: request,
					jsonp: 'callback'
				});
			}");
		}
	} 

	/**
	* 	The external source links auto
	**/
	protected static $links_externalsource_auto;
	
	/**
	* 	The external source links manual
	**/
	protected static $links_externalsource_manual;

	/**
	* 	The external source selection auto
	**/
	protected static $select_externalsource_auto;
	
	/**
	* 	The external source selection manual
	**/
	protected static $select_externalsource_manual;

	/**
	* 	The user notice info File Name
	**/
	protected static $usernotice = false;

	/**
	* 	The update error info File Name
	**/
	protected static $updateerror = false;

	/**
	* 	The update last File path
	**/
	protected static $updatelast = false;

	/**
	* 	The update errors
	**/
	protected static $updateErrors = array();
			
	/**
	* 	prepare base64 string for url
	**/
	public static function base64_urlencode($string, $encode = false)
	{
		if ($encode)
		{
			$string = base64_encode($string);
		}
		return str_replace(array('+', '/'), array('-', '_'), $string);
	}

	/**
	* 	prepare base64 string form url
	**/
	public static function base64_urldecode($string, $decode = false)
	{
		$string = str_replace(array('-', '_'), array('+', '/'), $string);
		if ($decode)
		{
			$string = base64_decode($string);
		}
		return $string;
	}
			
	/**
	* 	get Download links of a sermon
	**/
	public static function getDownloadLinks(&$sermon)
	{
		$links = array();
		// Get local key
		$localkey = self::getLocalKey();
		// decrypt the urls
		$safe = new FOFEncryptAes($localkey, 128);
		// internal download url
		$keyCounter = new stdClass;
		$keyCounter->sermon = $sermon->id;
		if ($sermon->preacher)
		{
			$keyCounter->preacher = $sermon->preacher;
		}
		if ($sermon->series)
		{
			$keyCounter->series = $sermon->series;
		}
		$keyCounterRAW = $safe->encryptString(json_encode($keyCounter));
		$keyCounter = self::base64_urlencode($keyCounterRAW);
		$token = JSession::getFormToken();
		$downloadURL = JURI::root().'index.php?option=com_sermondistributor&task=download.file&key='.$keyCounter.'&token='.$token;
		// check if local .htaccess should be set
		$setHtaccess = false;
		$onclick = ' onclick="sermonCounter(\''.$keyCounterRAW.'\',\'FILENAME\');"';
		// check what source of our link
		switch ($sermon->source)
		{
			case 1:
				// local file get local folder and check if outside root (if not then allow direct)
				$localFolder = JComponentHelper::getParams('com_sermondistributor')->get('localfolder', JPATH_ROOT.'/images').'/';
				// should we allow direct downloads
				$allowDirect = false;
				if (2 == $sermon->link_type && strpos($localFolder, JPATH_ROOT) !== false)
				{
					$allowDirect = true;
					$localFolderURL = JURI::root().str_replace(JPATH_ROOT, '', $localFolder);
					// insure no double // is in the URL
					$localFolderURL = str_replace('//', '/', $localFolderURL);
					$localFolderURL = str_replace(':/', '://', $localFolderURL);
				}
				// insure no double // is in the path name
				$localFolder = str_replace('//', '/', $localFolder);
				$localFolder = str_replace(':/', '://', $localFolder);
				if (self::checkArray($sermon->local_files))
				{
					foreach($sermon->local_files as $key)
					{
						if (1 == $sermon->link_type || !$allowDirect)
						{
							// get the file name use the same method as the auto
							$filename = self::getDownloadFileName($sermon,$key,'local');
							$lockedFolderPath = $safe->encryptString($localFolder.$key);
							$sermon->download_links[$filename] = $downloadURL.'&link='.self::base64_urlencode($lockedFolderPath).'&filename='.$filename;
							$sermon->onclick[$filename] = '';
						}
						elseif (2 == $sermon->link_type && $allowDirect)
						{
							$filename = $key;
							$sermon->download_links[$filename] = $localFolderURL.$key;
							$sermon->onclick[$filename] = str_replace('FILENAME', $filename, $onclick);
							$setHtaccess = true;
						}
					}
				}
				break;
			case 2:
				// Dropbox get global dropbox switch 
				$addToButton = JComponentHelper::getParams('com_sermondistributor')->get('add_to_button', 0);
				if (1 == $sermon->build)
				{
					if (self::checkArray($sermon->manual_files))
					{
						// manual dropbox
						foreach($sermon->manual_files as $key)
						{
							// get the link
							$dropURL = self::getExternalSourceLink('manual',1,$key);
							if (1 == $sermon->link_type && $dropURL)
							{
								// get the file name use the same method as the auto
								$filename = self::getDownloadFileName($sermon,$key,'dropbox_manual');
								// should we encrypt string this string
								if ('localKey34fdWEkl' == $localkey || (base64_encode(base64_decode($dropURL, true)) !== $dropURL)) // hmmm no global key has been set
								{
									$dropURL = $safe->encryptString($dropURL);
								}
								$sermon->download_links[$filename] = $downloadURL.'&link='.self::base64_urlencode($dropURL).'&filename='.$filename;
								$sermon->onclick[$filename] = '';
							}
							elseif (2 == $sermon->link_type && $dropURL)
							{
								$filename = str_replace('VDM_pLeK_h0uEr/', '', $key);
								if ('localKey34fdWEkl' == $localkey) // hmmm no global key has been set (so don't decrypt)
								{
									$sermon->download_links[$filename] = $dropURL;
								}
								else
								{
									$sermon->download_links[$filename] = rtrim($safe->decryptString($dropURL), "\0");
								}
								$sermon->onclick[$filename] = str_replace('FILENAME', $filename, $onclick);
							}
							// build dropbox switch if needed
							if (1 == $addToButton && $dropURL)
							{
								if ('localKey34fdWEkl' == $localkey) // hmmm no global key has been set (so don't decrypt)
								{
									$sermon->dropbox_buttons[$filename] = str_replace('?dl=1', '?dl=0', $dropURL);
								}
								else
								{
									$sermon->dropbox_buttons[$filename] = str_replace('?dl=1', '?dl=0', rtrim($safe->decryptString($dropURL), "\0"));
								}
								$sermon->onclick_drobox[$filename] = str_replace('FILENAME', $filename, $onclick);
							}
						}
					}
				}
				elseif (2 == $sermon->build)
				{
					if (self::checkArray($sermon->auto_sermons))
					{
						// automatic dropbox
						foreach($sermon->auto_sermons as $filename => $key)
						{
							// get the link
							$dropURL = self::getExternalSourceLink('auto',1,$key);
							if (1 == $sermon->link_type && $dropURL)
							{
								// should we encrypt string this string
								if ('localKey34fdWEkl' == $localkey || (base64_encode(base64_decode($dropURL, true)) !== $dropURL)) // hmmm no global key has been set
								{
									$dropURL = $safe->encryptString($dropURL);
								}
								// get the file name (use the same method as the auto
								$sermon->download_links[$filename] = $downloadURL.'&link='.self::base64_urlencode($dropURL).'&filename='.$filename;
								$sermon->onclick[$filename] = '';
							}
							elseif (2 == $sermon->link_type && $dropURL)
							{
								if ('localKey34fdWEkl' == $localkey) // hmmm no global key has been set (so don't decrypt)
								{
									$sermon->download_links[$filename] = $dropURL;
								}
								else
								{
									$sermon->download_links[$filename] = rtrim($safe->decryptString($dropURL), "\0");
								}
								$sermon->onclick[$filename] = str_replace('FILENAME', $filename, $onclick);
							}
							// build dropbox switch if needed
							if (1 == $addToButton && $dropURL)
							{
								if ('localKey34fdWEkl' == $localkey) // hmmm no global key has been set (so don't decrypt)
								{
									$sermon->dropbox_buttons[$filename] = str_replace('?dl=1', '?dl=0', $dropURL);
								}
								else
								{
									$sermon->dropbox_buttons[$filename] = str_replace('?dl=1', '?dl=0', rtrim($safe->decryptString($dropURL), "\0"));
								}
								$sermon->onclick_drobox[$filename] = str_replace('FILENAME', $filename, $onclick);
							}
						}
					}
				}
				break;
			case 3:
				// url get the file name use the same method as the auto
				$filename = self::getDownloadFileName($sermon,$sermon->url,'url');
				if (1 == $sermon->link_type)
				{
					$lockedURL = $safe->encryptString($sermon->url);
					$sermon->download_links[$filename] = $downloadURL.'&link='.self::base64_urlencode($lockedURL).'&filename='.$filename;
					$sermon->onclick[$filename] = '';
				}
				elseif (2 == $sermon->link_type)
				{
					$sermon->download_links[$filename] = $sermon->url;
					$sermon->onclick[$filename] = str_replace('FILENAME', $filename, $onclick);
				}
				break;
		}
		// remove the values no longer needed
		unset($sermon->local_files);
		unset($sermon->manual_files);
		unset($sermon->auto_sermons);
		unset($sermon->url);
		// should we set the local .htaccess for the download folder
		if ($setHtaccess)
		{
			// TODO we may need to add this latter to enforce download of files.
		}
		return true;
	}

	protected static function getNextUpdateValues()
	{
		// get actual update values
		$updates = self::getExternalListingUpdateKeys();
		// get last update
		$updatePath = self::getFilePath('update', 'last', 'vDm', '.txt', JPATH_COMPONENT_ADMINISTRATOR);
		if (($lastUpdate = @file_get_contents($updatePath)) !== FALSE && self::checkArray($updates))
		{
			// now check what is next
			$lastKey = array_search($lastUpdate, $updates);
			if (!is_null($lastKey))
			{
				$nextKey = $lastKey + 1;
				if (isset($updates[$nextKey]))
				{
					self::saveFile($updates[$nextKey],$updatePath);
					return $updates[$nextKey];
				}
			}
		}
		// rest and start with the first key
		if (self::checkArray($updates))
		{
			// save the first set
			$start = reset($updates);
			self::saveFile($start,$updatePath);
			return $start;
		}
		return false;
	}

	protected static function saveFile($data,$path_filename)
	{
		if (self::checkString($data))
		{
			$fp = fopen($path_filename, 'w');
			fwrite($fp, $data);
			fclose($fp);
			return true;
		}
		return false;
	}

	public static function getExternalListingUpdateKeys($id = null, $updateMethod = 2, $returnType = 1)
	{
		// first check if this file already has statistics
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('id','sharedurl','folder','permissiontype','dropboxoptions','build')));
		$query->from($db->quoteName('#__sermondistributor_external_source'));
		if ($updateMethod && is_numeric($updateMethod))
		{
			$query->where($db->quoteName('update_method') . ' = '. (int) $updateMethod);
		}
		if ($id && is_numeric($id))
		{
			$query->where($db->quoteName('id') . ' = '. (int) $id);
		}
		elseif ($id && self::checkArray($id))
		{
			$ids = implode(',', array_map( 'intval', $id));
			$query->where($db->quoteName('id') . ' IN  (' . $ids . ')');
		}
		$query->where($db->quoteName('published') . ' = 1');
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$results = $db->loadObjectList();
			$updates = array();
			foreach ($results as $result)
			{
				if ('full' == $result->permissiontype)
				{
					// load folder or urls
					switch ($result->dropboxoptions)
					{
						case 1: // sharedurl
							if (self::checkJson($result->sharedurl))
							{
								$targets = json_decode($result->sharedurl)->tsharedurl;
							}
						break;
						case 2: // folders
							if (self::checkJson($result->folder))
							{
								$targets = json_decode($result->folder)->tfolder;
							}
						break;
					}
					if (self::checkArray($targets))
					{
						foreach ($targets as $key => $value)
						{
							$nr = $key + 1;
							// id, target, type
							if (1 == $returnType)
							{
								$updates[] = $result->id . ', '. $nr . ', ' . $result->build;
							}
							else // only return the targets
							{
								$updates[] = $nr;
							}
						}
					}
				}
				else
				{
					// id, target, type
					if (1 == $returnType)
					{
						$updates[] = $result->id . ', 1, '. $result->build;
					}
					else // only return the targets
					{
						$updates[] = 1;
					}
				}
			}
			return $updates;
		}
		return false;
	}
	
	public static function getExternalSourceLink($type, $return = 7, $get = false, $target = 'links')
	{
		// make sure all defaults are set
		$found = self::checkExternalSourceLocalListing($type, $target);
		if ($found)
		{
			switch($return)
			{
				case 1:
					// return a link
					if (isset(self::${$target.'_externalsource_'.$type}[$get]))
					{
						return self::${$target.'_externalsource_'.$type}[$get];
					}
					break;
				case 2:
					// return all links
					return self::${$target.'_externalsource_'.$type};
					break;
				default :
					// just confirm that it is set
					return true;
					break;
			}
		}
		return false;
	}

	public static function countDownload($counter,$filename)
	{
		// Get local key
		$localkey = self::getLocalKey();
		$opener = new FOFEncryptAes($localkey, 128);
		$counter = json_decode(rtrim($opener->decryptString($counter), "\0"));
		if (self::checkObject($counter))
		{
			$counter->filename = $filename;
			// set the date object
			$date = JFactory::getDate();
			// first check if this file already has statistics
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id','counter')));
			$query->from($db->quoteName('#__sermondistributor_statistic'));
			$query->where($db->quoteName('sermon') . ' = '. (int) $counter->sermon);
			$query->where($db->quoteName('filename') . ' = '. $db->quote($counter->filename));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$statistic = $db->loadObject();
				// already has an entry
				$statistic->counter++;
				$statistic->modified = $date->toSql();
				// update the entry
				return $db->updateObject('#__sermondistributor_statistic', $statistic, 'id');
			}
			else
			{
				// set a new entry
				$counter->counter = 1;
				$counter->published = 1;
				$counter->created = $date->toSql();
				$counter->access = 1;
				$counter->version = 1;
				// set a new entry
				$done = $db->insertObject('#__sermondistributor_statistic', $counter);
				// if done return last used id
				if ($done)
				{
					$newId =  $db->insertid();
					// make sure the access of asset is set
					return self::setAsset($newId,'statistic');
				}
			}
		}
		return false;
	}
	
	protected static function getDownloadFileName(&$sermon,$file,$type)
	{
		// first get file name and file type
		$file = str_replace('VDM_pLeK_h0uEr/', '', $file);
		$fileType = pathinfo($file, PATHINFO_EXTENSION);
		if ($fileType)
		{
			$file = str_replace('.'.$fileType, '', $file);
		}
		else
		{
			$fileType = 'error';
		}
		// now build download name
		$downloadName = array();
		// build the Download File Name - first add the preacher name if set
		if ($sermon->preacher)
		{
			$downloadName[] = self::safeString($sermon->preacher_name,'U');
		}
		// add the series name if set
		if ($sermon->series)
		{
			$downloadName[] = self::safeString($sermon->series_name,'F');
		}
		// add the category name if set
		if ($sermon->catid && self::checkString($sermon->category))
		{
			$downloadName[] = self::safeString($sermon->category, 'F');
		}
		if ('dropbox_manual' == $type || 'local' == $type)
		{
			// add the main file name
			$downloadName[] = self::safeString($sermon->name,'F');
			$downloadName[] = self::safeString($file,'F');
		}
		else
		{
			$downloadName[] = self::safeString($sermon->name,'F');
			if ('error' == $fileType || strpos('?', $fileType) !== false || strpos('&', $fileType) !== false )
			{
				$fileType = 'mp3'; // TODO we don't know the url filetype (setting to mp3 but this could be wrong)
			}
		}
		// now build the download file name
		return implode('__', $downloadName).'.'.$fileType;
	}

	/**
	* 	check External Source Local Listing (do we have the files)
	**/
	public static function checkExternalSourceLocalListing($type, $get = 'links')
	{
		// get the local links
		if (isset(self::${$get.'_externalsource_'.$type}) && self::checkArray(self::${$get.'_externalsource_'.$type}))
		{
			// return true we have links loaded
			return true;
		}
		else
		{
			$target = array('links' => 'url', 'select' => 'name');
			$build = array( 'auto' => 2, 'manual' => 1);
			if (isset($build[$type]))
			{
				// load the links from the database
				$db = JFactory::getDbo();
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->select($db->quoteName(array('key', $target[$get])));
				$query->from($db->quoteName('#__sermondistributor_local_listing'));
				$query->where($db->quoteName('build') . ' = '. (int) $build[$type]);
				$query->where($db->quoteName('published') . ' = 1'); // TODO we can now limit the links to access groups
 				// Reset the query using our newly populated query object.
				$db->setQuery($query);
				$db->execute();
				if ($db->getNumRows())
				{
					self::${$get.'_externalsource_'.$type} = $db->loadAssocList('key', $target[$get]);
					// return true we have links loaded
					return true;
				}
			}
		}
		return false;
	}
	
	public static function getFilePath($type, $name = 'listing', $key = 'vDm', $fileType = '.json', $PATH = JPATH_COMPONENT_SITE)
	{
		if (!isset(self::${$type.$name}[$key]) || !self::checkString(self::${$type.$name}[$key]))
		{
			// Get local key
			$localkey = self::getLocalKey();
			// check the key
			$keyMD5 = '';
			if ('vDm' != $key)
			{
				$keyMD5 = $key;
			}
			// set the name
			$fileName = md5($type.$name.$localkey.$keyMD5);
			// set file path			
			self::${$type.$name}[$key] = $PATH.'/helpers/'.$fileName.$fileType;
		}
		if (isset(self::${$type.$name}[$key]) && self::checkString(self::${$type.$name}[$key]))
		{
			// return the path
			return self::${$type.$name}[$key];
		}
		return '';
	}

	/**
	* 	get the localkey
	**/
	protected static $localkey = array();
	
	public static function getLocalKey($type = 'basic_key')
	{
		if (!isset(self::$localkey[$type]))
		{
			// get the main key
			self::$localkey[$type] = JComponentHelper::getParams('com_sermondistributor')->get($type, 'localKey34fdWEkl');
		}
		return self::$localkey[$type];
	}

	public static function updateExternalSource($id, $target = 0, $type = false, $force = false, $sleutel = null)
	{
		$source = self::getVar('external_source', (int) $id, 'id', 'externalsources');
		if (1 == $source) // Dropbox is the source
		{
			// load the file
			JLoader::import('dropboxupdater', JPATH_COMPONENT_SITE.'/helpers');
			// update types
			$types = array('manual','auto');
			// okay now update this type
			if (self::checkString($type) && in_array($type,$types))
			{
				$dropbox = new Dropboxupdater();
				if ($dropbox->update($id, $target, $type, $force, $sleutel))
				{
					return true;
				}
				self::setUpdateError($id, $dropbox->getErrors());
				return false;
			}
		}
		self::setUpdateError($id, array(JText::_('COM_SERMONDISTRIBUTOR_THE_EXTERNAL_SOURCE_COULD_NOT_BE_FOUND')));
		return false;
	}

	public static function getSourceStatus($id)
	{
		// fist get errors if any is found
		$errors = array();
		if ($targets = self::getExternalListingUpdateKeys($id, null, 1))
		{
			foreach ($targets as $target)
			{
				$key = preg_replace('/[ ,]+/', '', trim($target));
				if ($error = self::getUpdateError(0, $key))
				{
					$errors[] = $error;
				}
			}
		}
		// check when was the last update
		$updateInfo = self::updateInfo($id);
		if (!$updateInfo)
		{
			$errors[] = JText::_('COM_SERMONDISTRIBUTOR_THIS_SOURCE_HAS_NO_LOCAL_LISTING_SET');
		}
		// build the return string
		if (isset($updateInfo['last']) || self::checkArray($errors))
		{
			$body = array();
			// great we have source status
			if (isset($updateInfo['last']))
			{
				$body[] = '<h3>'. JText::_('COM_SERMONDISTRIBUTOR_LISTING_INFO') . '</h3>';
				$body[] = '<p><b>'. JText::_('COM_SERMONDISTRIBUTOR_LAST_UPDATE') . ':</b> <em>'.$updateInfo['last'];
				$body[] = '</em><br /><b>'. JText::_('COM_SERMONDISTRIBUTOR_NUMBER_OF_FILES_LISTED') . ':</b> <em>'.$updateInfo['qty'];
				$body[] = '</em></p>';
			}
			// now set any errors found
			if (self::checkArray($errors))
			{
				$body[] = '<h3>'. JText::_('COM_SERMONDISTRIBUTOR_NOTICE') . '</h3>';
				$body[] = implode('', $errors);
			}
			return '<a class="btn btn-small btn-success" href="#source-status'.$id.'" data-toggle="modal">'.JText::_('COM_SERMONDISTRIBUTOR_VIEW_UPDATE_STATUS').'</a>' 
				. JHtml::_('bootstrap.renderModal', 'source-status'.$id, array('title' => JText::_('COM_SERMONDISTRIBUTOR_SOURCE_STATUS_REPORT')), implode('', $body));
		}
 		// no status found
		return false;
	}

	public static function updateInfo($id)
	{
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.created','a.modified')));
		$query->from($db->quoteName('#__sermondistributor_local_listing', 'a'));
		$query->where($db->quoteName('a.external_source') . ' = ' . (int) $id);
 		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		$db->execute();
		if ($qty = $db->getNumRows())
		{
			$data = $db->loadRowList();
			$last = 0;
			foreach ($data as $dates)
			{
				foreach ($dates as $date)
				{
					$time = strtotime($date);
					if ($time > $last)
					{
						$last = $time;
					}
				}
			}
			$info['qty'] = (int) $qty;
			$info['last'] = self::fancyDate($last);
			return $info;
		}
		return false;
	}
	
	public static function getUpdateError($id, $fileKey = null)
	{
		// get update error from file
		if ($fileKey)
		{
			$file_path = self::getFilePath('update', 'error', $fileKey, '.txt', JPATH_COMPONENT_ADMINISTRATOR);
			// check if it is set
			if (($text = @file_get_contents($file_path)) !== FALSE)
			{
				// no error on success
				if ('success' != $text)
				{
					return $text;
				}
			}
			return false;
		}
		elseif (isset(self::$updateErrors[$id]) && self::checkArray(self::$updateErrors[$id]))
		{
			return '<ul><li>'.implode('</li><li>', self::$updateErrors[$id]).'</li></ul>';
		}
		return JText::_('COM_SERMONDISTRIBUTOR_UNKNOWN_ERROR_HAS_OCCURRED');
	}
	
	protected static function setUpdateError($id, $errorArray)
	{
		if (self::checkArray($errorArray) && $id > 0)
		{
			foreach ($errorArray as $error)
			{
				if (!isset(self::$updateErrors[$id]))
				{
					self::$updateErrors[$id] = array();
				}
				self::$updateErrors[$id][] = $error;
			}
		}
	}
			
	/**
	 *	Change to nice fancy date
	 */
	public static function fancyDate($date)
	{
		if (!self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('jS \o\f F Y',$date);
	}

	/**
	 *	Change to nice fancy day time and date
	 */
	public static function fancyDayTimeDate($time)
	{
		if (!self::isValidTimeStamp($time))
		{
			$time = strtotime($time);
		}
		return date('D ga jS \o\f F Y',$time);
	}

	/**
	 *	Change to nice fancy time and date
	 */
	public static function fancyDateTime($time)
	{
		if (!self::isValidTimeStamp($time))
		{
			$time = strtotime($time);
		}
		return date('(G:i) jS \o\f F Y',$time);
	}

	/**
	 *	Change to nice hour:minutes time
	 */
	public static function fancyTime($time)
	{
		if (!self::isValidTimeStamp($time))
		{
			$time = strtotime($time);
		}
		return date('G:i',$time);
	}

	/**
	 *	Check if string is a valid time stamp
	 */
	public static function isValidTimeStamp($timestamp)
	{
		return ((int) $timestamp === $timestamp)
		&& ($timestamp <= PHP_INT_MAX)
		&& ($timestamp >= ~PHP_INT_MAX);
	}
			 
	/**
	*	Load the Component xml manifest.
	**/
        public static function manifest()
	{
                $manifestUrl = JPATH_ADMINISTRATOR."/components/com_sermondistributor/sermondistributor.xml";
                return simplexml_load_file($manifestUrl);
	}

	/**
	*	Load the Contributors details.
	**/
	public static function getContributors()
	{
		// get params
		$params	= JComponentHelper::getParams('com_sermondistributor');
		// start contributors array
		$contributors = array();
		// get all Contributors (max 20)
		$searchArray = range('0','20');
		foreach($searchArray as $nr)
                {
			if ((NULL !== $params->get("showContributor".$nr)) && ($params->get("showContributor".$nr) == 1 || $params->get("showContributor".$nr) == 3))
                        {
				// set link based of selected option
				if($params->get("useContributor".$nr) == 1)
                                {
					$link_front = '<a href="mailto:'.$params->get("emailContributor".$nr).'" target="_blank">';
					$link_back = '</a>';
				}
                                elseif($params->get("useContributor".$nr) == 2)
                                {
					$link_front = '<a href="'.$params->get("linkContributor".$nr).'" target="_blank">';
					$link_back = '</a>';
				}
                                else
                                {
					$link_front = '';
					$link_back = '';
				}
				$contributors[$nr]['title']	= self::htmlEscape($params->get("titleContributor".$nr));
				$contributors[$nr]['name']	= $link_front.self::htmlEscape($params->get("nameContributor".$nr)).$link_back;
			}
		}
		return $contributors;
	}

	/**
	*	Load the Component Help URLs.
	**/
	public static function getHelpUrl($view)
	{
		$user	= JFactory::getUser();
		$groups = $user->get('groups');
		$db	= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select(array('a.id','a.groups','a.target','a.type','a.article','a.url'));
		$query->from('#__sermondistributor_help_document AS a');
		$query->where('a.admin_view = '.$db->quote($view));
		$query->where('a.location = 1');
		$query->where('a.published = 1');
		$db->setQuery($query);
		$db->execute();
		if($db->getNumRows())
		{
			$helps = $db->loadObjectList();
			if (self::checkArray($helps))
			{
				foreach ($helps as $nr => $help)
				{
					if ($help->target == 1)
					{
						$targetgroups = json_decode($help->groups, true);
						if (!array_intersect($targetgroups, $groups))
						{
							// if user not in those target groups then remove the item
							unset($helps[$nr]);
							continue;
						}
					}
					// set the return type
					switch ($help->type)
					{
						// set joomla article
						case 1:
							return self::loadArticleLink($help->article);
						break;
						// set help text
						case 2:
							return self::loadHelpTextLink($help->id);
						break;
						// set Link
						case 3:
							return $help->url;
						break;
					}
				}
			}
		}
		return false;
	}

	/**
	*	Get the Article Link.
	**/
	protected static function loadArticleLink($id)
	{
		return JURI::root().'index.php?option=com_content&view=article&id='.$id.'&tmpl=component&layout=modal';
	}

	/**
	*	Get the Help Text Link.
	**/
	protected static function loadHelpTextLink($id)
	{
		$token = JSession::getFormToken();
		return 'index.php?option=com_sermondistributor&task=help.getText&id=' . (int) $id . '&token=' . $token;
	}

	/**
	*	Configure the Linkbar.
	**/
	public static function addSubmenu($submenu)
	{
                // load user for access menus
                $user = JFactory::getUser();
                // load the submenus to sidebar
                JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_DASHBOARD'), 'index.php?option=com_sermondistributor&view=sermondistributor', $submenu === 'sermondistributor');
		if ($user->authorise('preacher.access', 'com_sermondistributor') && $user->authorise('preacher.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_PREACHERS'), 'index.php?option=com_sermondistributor&view=preachers', $submenu === 'preachers');
		}
		if ($user->authorise('sermon.access', 'com_sermondistributor') && $user->authorise('sermon.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_SERMONS'), 'index.php?option=com_sermondistributor&view=sermons', $submenu === 'sermons');
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SERMON_SERMON_CATEGORY'), 'index.php?option=com_categories&view=categories&extension=com_sermondistributor.sermons', $submenu === 'categories.sermons');
		}
		if ($user->authorise('series.access', 'com_sermondistributor') && $user->authorise('series.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_SERIES_LIST'), 'index.php?option=com_sermondistributor&view=series_list', $submenu === 'series_list');
		}
		if ($user->authorise('statistic.access', 'com_sermondistributor') && $user->authorise('statistic.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_STATISTICS'), 'index.php?option=com_sermondistributor&view=statistics', $submenu === 'statistics');
		}
		if ($user->authorise('external_source.access', 'com_sermondistributor') && $user->authorise('external_source.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_EXTERNAL_SOURCES'), 'index.php?option=com_sermondistributor&view=external_sources', $submenu === 'external_sources');
		}
		// Access control (manual_updater.access && manual_updater.submenu).
		if ($user->authorise('manual_updater.access', 'com_sermondistributor') && $user->authorise('manual_updater.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_MANUAL_UPDATER'), 'index.php?option=com_sermondistributor&view=manual_updater', $submenu === 'manual_updater');
		}
		if ($user->authorise('local_listing.access', 'com_sermondistributor') && $user->authorise('local_listing.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_LOCAL_LISTINGS'), 'index.php?option=com_sermondistributor&view=local_listings', $submenu === 'local_listings');
		}
		if ($user->authorise('help_document.access', 'com_sermondistributor') && $user->authorise('help_document.submenu', 'com_sermondistributor'))
		{
			JHtmlSidebar::addEntry(JText::_('COM_SERMONDISTRIBUTOR_SUBMENU_HELP_DOCUMENTS'), 'index.php?option=com_sermondistributor&view=help_documents', $submenu === 'help_documents');
		}
	} 

	/**
	* 	UIKIT Component Classes
	**/
	public static $uk_components = array(
			'data-uk-grid' => array(
				'grid' ),
			'uk-accordion' => array(
				'accordion' ),
			'uk-autocomplete' => array(
				'autocomplete' ),
			'data-uk-datepicker' => array(
				'datepicker' ),
			'uk-form-password' => array(
				'form-password' ),
			'uk-form-select' => array(
				'form-select' ),
			'data-uk-htmleditor' => array(
				'htmleditor' ),
			'data-uk-lightbox' => array(
				'lightbox' ),
			'uk-nestable' => array(
				'nestable' ),
			'UIkit.notify' => array(
				'notify' ),
			'data-uk-parallax' => array(
				'parallax' ),
			'uk-search' => array(
				'search' ),
			'uk-slider' => array(
				'slider' ),
			'uk-slideset' => array(
				'slideset' ),
			'uk-slideshow' => array(
				'slideshow',
				'slideshow-fx' ),
			'uk-sortable' => array(
				'sortable' ),
			'data-uk-sticky' => array(
				'sticky' ),
			'data-uk-timepicker' => array(
				'timepicker' ),
			'data-uk-tooltip' => array(
				'tooltip' ),
			'uk-placeholder' => array(
				'placeholder' ),
			'uk-dotnav' => array(
				'dotnav' ),
			'uk-slidenav' => array(
				'slidenav' ),
			'uk-form' => array(
				'form-advanced' ),
			'uk-progress' => array(
				'progress' ),
			'upload-drop' => array(
				'upload', 'form-file' )
			);
	
	/**
	* 	Add UIKIT Components
	**/
	public static $uikit = false;

	/**
	* 	Get UIKIT Components
	**/
	public static function getUikitComp($content,$classes = array())
	{
		if (strpos($content,'class="uk-') !== false)
		{
			// reset
			$temp = array();
			foreach (self::$uk_components as $looking => $add)
			{
				if (strpos($content,$looking) !== false)
				{
					$temp[] = $looking;
				}
			}
			// make sure uikit is loaded to config
			if (strpos($content,'class="uk-') !== false)
			{
				self::$uikit = true;
			}
			// sorter
			if (self::checkArray($temp))
			{
				// merger
				if (self::checkArray($classes))
				{
					$newTemp = array_merge($temp,$classes);
					$temp = array_unique($newTemp);
				}
				return $temp;
			}
		}	
		if (self::checkArray($classes))
		{
			return $classes;
		}
		return false;
	} 

	/**
	 * Prepares the xml document
	 */
	public static function xls($rows,$fileName = null,$title = null,$subjectTab = null,$creator = 'Vast Development Method',$description = null,$category = null,$keywords = null,$modified = null)
	{
		// set the user
		$user = JFactory::getUser();
		
		// set fieldname if not set
		if (!$fileName)
		{
			$fileName = 'exported_'.JFactory::getDate()->format('jS_F_Y');
		}
		// set modiefied if not set
		if (!$modified)
		{
			$modified = $user->name;
		}
		// set title if not set
		if (!$title)
		{
			$title = 'Book1';
		}
		// set tab name if not set
		if (!$subjectTab)
		{
			$subjectTab = 'Sheet1';
		}
		
		// make sure the file is loaded		
		JLoader::import('PHPExcel', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator($creator)
									 ->setCompany('Vast Development Method')
									 ->setLastModifiedBy($modified)
									 ->setTitle($title)
									 ->setSubject($subjectTab);
		if (!$description)
		{
			$objPHPExcel->getProperties()->setDescription($description);
		}
		if (!$keywords)
		{
			$objPHPExcel->getProperties()->setKeywords($keywords);
		}
		if (!$category)
		{
			$objPHPExcel->getProperties()->setCategory($category);
		}
		
		// Some styles
		$headerStyles = array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '1171A3'),
				'size'  => 12,
				'name'  => 'Verdana'
		));
		$sideStyles = array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '444444'),
				'size'  => 11,
				'name'  => 'Verdana'
		));
		$normalStyles = array(
			'font'  => array(
				'color' => array('rgb' => '444444'),
				'size'  => 11,
				'name'  => 'Verdana'
		));
		
		// Add some data
		if (self::checkArray($rows))
		{
			$i = 1;
			foreach ($rows as $array){
				$a = 'A';
				foreach ($array as $value){
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($a.$i, $value);
					if ($i == 1){
						$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getStyle($a.$i)->applyFromArray($headerStyles);
						$objPHPExcel->getActiveSheet()->getStyle($a.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					} elseif ($a === 'A'){
						$objPHPExcel->getActiveSheet()->getStyle($a.$i)->applyFromArray($sideStyles);
					} else {
						$objPHPExcel->getActiveSheet()->getStyle($a.$i)->applyFromArray($normalStyles);
					}
					$a++;
				}
				$i++;
			}
		}
		else
		{
			return false;
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($subjectTab);
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$fileName.'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		jexit();
	}
	
	/**
	* Get CSV Headers
	*/
	public static function getFileHeaders($dataType)
	{		
		// make sure these files are loaded		
		JLoader::import('PHPExcel', JPATH_COMPONENT_ADMINISTRATOR . '/helpers');
		JLoader::import('ChunkReadFilter', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/PHPExcel/Reader');
		// get session object
		$session	= JFactory::getSession();
		$package	= $session->get('package', null);
		$package	= json_decode($package, true);
		// set the headers
		if(isset($package['dir']))
		{
			$chunkFilter = new PHPExcel_Reader_chunkReadFilter();
			// only load first three rows
			$chunkFilter->setRows(2,1);
			// identify the file type
			$inputFileType = PHPExcel_IOFactory::identify($package['dir']);
			// create the reader for this file type
			$excelReader = PHPExcel_IOFactory::createReader($inputFileType);
			// load the limiting filter
			$excelReader->setReadFilter($chunkFilter);
			$excelReader->setReadDataOnly(true);
			// load the rows (only first three)
			$excelObj = $excelReader->load($package['dir']);
			$headers = array();
			foreach ($excelObj->getActiveSheet()->getRowIterator() as $row)
			{
				if($row->getRowIndex() == 1)
				{
					$cellIterator = $row->getCellIterator();
					$cellIterator->setIterateOnlyExistingCells(false);
					foreach ($cellIterator as $cell)
					{
						if (!is_null($cell))
						{
							$headers[$cell->getColumn()] = $cell->getValue();
						}
					}
					$excelObj->disconnectWorksheets();
					unset($excelObj);
					break;
				}
			}
			return $headers;
		}
		return false;
	}

	public static function getVar($table, $where = null, $whereString = 'user', $what = 'id', $operator = '=', $main = 'sermondistributor')
	{
		if(!$where)
		{
			$where = JFactory::getUser()->id;
		}
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array($what)));
		if (empty($table))
		{
			$query->from($db->quoteName('#__'.$main));
		}
		else
		{
			$query->from($db->quoteName('#__'.$main.'_'.$table));
		}
		if (is_numeric($where))
		{
			$query->where($db->quoteName($whereString) . ' '.$operator.' '.(int) $where);
		}
		elseif (is_string($where))
		{
			$query->where($db->quoteName($whereString) . ' '.$operator.' '. $db->quote((string)$where));
		}
		else
		{
			return false;
		}
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadResult();
		}
		return false;
	}

	public static function getVars($table, $where = null, $whereString = 'user', $what = 'id', $operator = 'IN', $main = 'sermondistributor', $unique = true)
	{
		if(!$where)
		{
			$where = JFactory::getUser()->id;
		}

		if (!self::checkArray($where) && $where > 0)
		{
			$where = array($where);
		}

		if (self::checkArray($where))
		{
			// Get a db connection.
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);

			$query->select($db->quoteName(array($what)));
			$query->from($db->quoteName('#__'.$main.'_'.$table));
			$query->where($db->quoteName($whereString) . ' '.$operator.' (' . implode(',',$where) . ')');
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				if ($unique)
				{
					return array_unique($db->loadColumn());
				}
				return $db->loadColumn();
			}
		}
		return false;
	}

	public static function jsonToString($value, $sperator = ", ", $table = null)
	{
                // check if string is JSON
                $result = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE)
		{
			// is JSON
			if (self::checkArray($result))
			{
				if (self::checkString($table))
				{
					$names = array();
					foreach ($result as $val)
					{
						if ($name = self::getVar($table, $val, 'id', 'name'))
						{
							$names[] = $name;
						}
					}
					if (self::checkArray($names))
					{
						return (string) implode($sperator,$names);
					}	
				}
				return (string) implode($sperator,$result);
			}
                        return (string) json_decode($value);
                }
                return $value;
        }

	public static function isPublished($id,$type)
	{
		if ($type == 'raw')
                {
			$type = 'item';
		}
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select(array('a.published'));
		$query->from('#__sermondistributor_'.$type.' AS a');
		$query->where('a.id = '. (int) $id);
		$query->where('a.published = 1');
		$db->setQuery($query);
		$db->execute();
		$found = $db->getNumRows();
		if($found)
                {
			return true;
		}
		return false;
	}

	public static function getGroupName($id)
	{
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select(array('a.title'));
		$query->from('#__usergroups AS a');
		$query->where('a.id = '. (int) $id);
		$db->setQuery($query);
		$db->execute();
		$found = $db->getNumRows();
		if($found)
                {
			return $db->loadResult();
		}
		return $id;
	}

        /**
	*	Get the actions permissions
	**/
        public static function getActions($view,&$record = null,$views = null)
	{
		jimport('joomla.access.access');

		$user	= JFactory::getUser();
		$result	= new JObject;
		$view	= self::safeString($view);
                if (self::checkString($views))
                {
			$views = self::safeString($views);
                }
		// get all actions from component
		$actions = JAccess::getActions('com_sermondistributor', 'component');
                // set acctions only set in component settiongs
                $componentActions = array('core.admin','core.manage','core.options','core.export');
		// loop the actions and set the permissions
		foreach ($actions as $action)
                {
			// set to use component default
			$fallback= true;
			if (self::checkObject($record) && isset($record->id) && $record->id > 0 && !in_array($action->name,$componentActions))
			{
				// The record has been set. Check the record permissions.
				$permission = $user->authorise($action->name, 'com_sermondistributor.'.$view.'.' . (int) $record->id);
				if (!$permission) // TODO removed && !is_null($permission)
				{
					if ($action->name == 'core.edit' || $action->name == $view.'.edit')
					{
						if ($user->authorise('core.edit.own', 'com_sermondistributor.'.$view.'.' . (int) $record->id))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback= false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback= false;
							}
						}
						elseif ($user->authorise($view.'edit.own', 'com_sermondistributor.'.$view.'.' . (int) $record->id))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback= false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback= false;
							}
						}
						elseif ($user->authorise('core.edit.own', 'com_sermondistributor'))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback= false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback= false;
							}
						}
						elseif ($user->authorise($view.'edit.own', 'com_sermondistributor'))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback= false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback= false;
							}
						}
					}
				}
				elseif (self::checkString($views) && isset($record->catid) && $record->catid > 0)
				{
                                        // make sure we use the core. action check for the categories
                                        if (strpos($action->name,$view) !== false && strpos($action->name,'core.') === false ) {
                                                $coreCheck		= explode('.',$action->name);
                                                $coreCheck[0]	= 'core';
                                                $categoryCheck	= implode('.',$coreCheck);
                                        }
                                        else
                                        {
                                                $categoryCheck = $action->name;
                                        }
                                        // The record has a category. Check the category permissions.
					$catpermission = $user->authorise($categoryCheck, 'com_sermondistributor.'.$views.'.category.' . (int) $record->catid);
					if (!$catpermission && !is_null($catpermission))
					{
						if ($action->name == 'core.edit' || $action->name == $view.'.edit')
						{
							if ($user->authorise('core.edit.own', 'com_sermondistributor.'.$views.'.category.' . (int) $record->catid))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback= false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback= false;
								}
							}
							elseif ($user->authorise($view.'edit.own', 'com_sermondistributor.'.$views.'.category.' . (int) $record->catid))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback= false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback= false;
								}
							}
							elseif ($user->authorise('core.edit.own', 'com_sermondistributor'))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback= false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback= false;
								}
							}
							elseif ($user->authorise($view.'edit.own', 'com_sermondistributor'))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback= false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback= false;
								}
							}
						}
					}
				}
			}
			// if allowed then fallback on component global settings
			if ($fallback)
			{
				$result->set($action->name, $user->authorise($action->name, 'com_sermondistributor'));
			}
		}
		return $result;
	}

	/**
	*	Get any component's model
	**/
	public static function getModel($name, $path = JPATH_COMPONENT_ADMINISTRATOR, $component = 'sermondistributor')
	{
		// load some joomla helpers
		JLoader::import('joomla.application.component.model');
		// load the model file
		JLoader::import( $name, $path . '/models' );
		// return instance
		return JModelLegacy::getInstance( $name, $component.'Model' );
	}
	
	/**
	*	Add to asset Table
	*/
	public static function setAsset($id,$table)
	{
		$parent = JTable::getInstance('Asset');
		$parent->loadByName('com_sermondistributor');
		
		$parentId = $parent->id;
		$name     = 'com_sermondistributor.'.$table.'.'.$id;
		$title    = '';

		$asset = JTable::getInstance('Asset');
		$asset->loadByName($name);

		// Check for an error.
		$error = $asset->getError();

		if ($error)
		{
			return false;
		}
		else
		{
			// Specify how a new or moved node asset is inserted into the tree.
			if ($asset->parent_id != $parentId)
			{
				$asset->setLocation($parentId, 'last-child');
			}

			// Prepare the asset to be stored.
			$asset->parent_id = $parentId;
			$asset->name      = $name;
			$asset->title     = $title;
			// get the default asset rules
			$rules = self::getDefaultAssetRules('com_sermondistributor',$table);
			if ($rules instanceof JAccessRules)
			{
				$asset->rules = (string) $rules;
			}

			if (!$asset->check() || !$asset->store())
			{
				JFactory::getApplication()->enqueueMessage($asset->getError(), 'warning');
				return false;
			}
			else
			{
				// Create an asset_id or heal one that is corrupted.
				$object = new stdClass();

				// Must be a valid primary key value.
				$object->id = $id;
				$object->asset_id = (int) $asset->id;

				// Update their asset_id to link to the asset table.
				return JFactory::getDbo()->updateObject('#__sermondistributor_'.$table, $object, 'id');
			}
		}
		return false;
	}
	
	/**
	 *	Gets the default asset Rules for a component/view.
	 */
	protected static function getDefaultAssetRules($component,$view)
	{
		// Need to find the asset id by the name of the component.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('name') . ' = ' . $db->quote($component));
		$db->setQuery($query);
		$db->execute();
		if ($db->loadRowList())
		{
			// asset alread set so use saved rules
			$assetId = (int) $db->loadResult();
			$result =  JAccess::getAssetRules($assetId);
			if ($result instanceof JAccessRules)
			{
				$_result = (string) $result;
				$_result = json_decode($_result);
				foreach ($_result as $name => &$rule)
				{
					$v = explode('.', $name);
					if ($view !== $v[0])
					{
						// remove since it is not part of this view
						unset($_result->$name);
					}
					else
					{
						// clear the value since we inherit
						$rule = array();
					}
				}
				// check if there are any view values remaining
				if (count($_result))
				{
					$_result = json_encode($_result);
					$_result = array($_result);
					// Instantiate and return the JAccessRules object for the asset rules.
					$rules = new JAccessRules($_result);

					return $rules;
				}
				return $result;
			}
		}
		return JAccess::getAssetRules(0);
	}

	public static function renderBoolButton()
	{
		$args = func_get_args();

		// get the radio element
		$button = JFormHelper::loadFieldType('radio');

		// setup the properties
		$name	 	= self::htmlEscape($args[0]);
		$additional = isset($args[1]) ? (string) $args[1] : '';
		$value		= $args[2];
		$yes 	 	= isset($args[3]) ? self::htmlEscape($args[3]) : 'JYES';
		$no 	 	= isset($args[4]) ? self::htmlEscape($args[4]) : 'JNO';

		// prepare the xml
		$element = new SimpleXMLElement('<field name="'.$name.'" type="radio" class="btn-group"><option '.$additional.' value="0">'.$no.'</option><option '.$additional.' value="1">'.$yes.'</option></field>');

		// run
		$button->setup($element, $value);

		return $button->input;

	}
	
	public static function checkJson($string)
	{
		if (self::checkString($string))
		{
			json_decode($string);
			return (json_last_error() === JSON_ERROR_NONE);
		}
		return false;
	}

	public static function checkObject($object)
	{
		if (isset($object) && is_object($object) && count($object) > 0)
		{
			return true;
		}
		return false;
	}

	public static function checkArray($array, $removeEmptyString = false)
	{
		if (isset($array) && is_array($array) && count($array) > 0)
		{
			// also make sure the empty strings are removed
			if ($removeEmptyString)
			{
				foreach ($array as $key => $string)
				{
					if (empty($string))
					{
						unset($array[$key]);
					}
				}
				return self::checkArray($array, false);
			}
			return true;
		}
		return false;
	}

	public static function checkString($string)
	{
		if (isset($string) && is_string($string) && strlen($string) > 0)
		{
			return true;
		}
		return false;
	}

	public static function mergeArrays($arrays)
	{
		if(self::checkArray($arrays))
		{
			$arrayBuket = array();
			foreach ($arrays as $array)
			{
				if (self::checkArray($array))
				{
					$arrayBuket = array_merge($arrayBuket, $array);
				}
			}
			return $arrayBuket;
		}
		return false;
	}

	public static function sorten($string, $length = 40, $addTip = true)
	{
		if (self::checkString($string))
        {
			$initial = strlen($string);
			$words = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
			$words_count = count($words);

			$word_length = 0;
			$last_word = 0;
			for (; $last_word < $words_count; ++$last_word)
			{
				$word_length += strlen($words[$last_word]);
				if ($word_length > $length)
				{
					break;
				}
			}

			$newString	= implode(array_slice($words, 0, $last_word));
			$final	= strlen($newString);
			if ($initial != $final && $addTip)
			{
				$title = self::sorten($string, 400 , false);
				return '<span class="hasTip" title="'.$title.'" style="cursor:help">'.trim($newString).'...</span>';
			}
			elseif ($initial != $final && !$addTip)
			{
				return trim($newString).'...';
			}
		}
		return $string;
	}

	public static function safeString($string, $type = 'L', $spacer = '_')
	{
		// remove all numbers and replace with english text version (works well only up to millions)
                $string = self::replaceNumbers($string);
		// 0nly continue if we have a string
                if (self::checkString($string))
                {
			// remove all other characters
			$string = trim($string);
			$string = preg_replace('/'.$spacer.'+/', ' ', $string);
			$string = preg_replace('/\s+/', ' ', $string);
			$string = preg_replace("/[^A-Za-z ]/", '', $string);
			// select final adaptations
			if ($type === 'L' || $type === 'strtolower')
                        {
                                // replace white space with underscore
                                $string = preg_replace('/\s+/', $spacer, $string);
                                // default is to return lower
                                return strtolower($string);
                        }
			elseif ($type === 'W')
			{
				// return a string with all first letter of each word uppercase(no undersocre)
				return ucwords(strtolower($string));
			}
			elseif ($type === 'w' || $type === 'word')
			{
				// return a string with all lowercase(no undersocre)
				return strtolower($string);
			}
			elseif ($type === 'Ww' || $type === 'Word')
			{
				// return a string with first letter of the first word uppercase and all the rest lowercase(no undersocre)
				return ucfirst(strtolower($string));
			}
			elseif ($type === 'WW' || $type === 'WORD')
			{
				// return a string with all the uppercase(no undersocre)
				return strtoupper($string);
			}
                        elseif ($type === 'U' || $type === 'strtoupper')
                        {
                                // replace white space with underscore
                                $string = preg_replace('/\s+/', $spacer, $string);
                                // return all upper
                                return strtoupper($string);
                        }
                        elseif ($type === 'F' || $type === 'ucfirst')
                        {
                                // replace white space with underscore
                                $string = preg_replace('/\s+/', $spacer, $string);
                                // return with first caracter to upper
                                return ucfirst(strtolower($string));
                        }
                        elseif ($type === 'cA' || $type === 'cAmel' || $type === 'camelcase')
			{
				// convert all words to first letter uppercase
				$string = ucwords(strtolower($string));
				// remove white space
				$string = preg_replace('/\s+/', '', $string);
				// now return first letter lowercase
				return lcfirst($string);
			}
                        // return string
                        return $string;
                }
                // not a string
                return '';
	}

        public static function htmlEscape($var, $charset = 'UTF-8', $sorten = false, $length = 40)
	{
		if (self::checkString($var))
		{
			$filter = new JFilterInput();
			$string = $filter->clean(html_entity_decode(htmlentities($var, ENT_COMPAT, $charset)), 'HTML');
			if ($sorten)
			{
                                return self::sorten($string,$length);
			}
			return $string;
                }
		else
		{
			return '';
                }
	}

	public static function replaceNumbers($string)
	{
		// set numbers array
		$numbers = array();
		// first get all numbers
		preg_match_all('!\d+!', $string, $numbers);
		// check if we have any numbers
		if (isset($numbers[0]) && self::checkArray($numbers[0]))
		{
			foreach ($numbers[0] as $number)
			{
				$searchReplace[$number] = self::numberToString((int)$number);
			}
			// now replace numbers in string
			$string = str_replace(array_keys($searchReplace), array_values($searchReplace),$string);
			// check if we missed any, strange if we did.
			return self::replaceNumbers($string);
		}
		// return the string with no numbers remaining.
		return $string;
	}
	
	/**
	*	Convert an integer into an English word string
	*	Thanks to Tom Nicholson <http://php.net/manual/en/function.strval.php#41988>
	*
	*	@input	an int
	*	@returns a string
	**/
	public static function numberToString($x)
	{
		$nwords = array( "zero", "one", "two", "three", "four", "five", "six", "seven",
			"eight", "nine", "ten", "eleven", "twelve", "thirteen",
			"fourteen", "fifteen", "sixteen", "seventeen", "eighteen",
			"nineteen", "twenty", 30 => "thirty", 40 => "forty",
			50 => "fifty", 60 => "sixty", 70 => "seventy", 80 => "eighty",
			90 => "ninety" );

		if(!is_numeric($x))
		{
			$w = $x;
		}
		elseif(fmod($x, 1) != 0)
		{
			$w = $x;
		}
		else
		{
			if($x < 0)
			{
				$w = 'minus ';
				$x = -$x;
			}
			else
			{
				$w = '';
				// ... now $x is a non-negative integer.
			}

			if($x < 21)   // 0 to 20
			{
				$w .= $nwords[$x];
			}
			elseif($x < 100)  // 21 to 99
			{ 
				$w .= $nwords[10 * floor($x/10)];
				$r = fmod($x, 10);
				if($r > 0)
				{
					$w .= ' '. $nwords[$r];
				}
			}
			elseif($x < 1000)  // 100 to 999
			{
				$w .= $nwords[floor($x/100)] .' hundred';
				$r = fmod($x, 100);
				if($r > 0)
				{
					$w .= ' and '. self::numberToString($r);
				}
			}
			elseif($x < 1000000)  // 1000 to 999999
			{
				$w .= self::numberToString(floor($x/1000)) .' thousand';
				$r = fmod($x, 1000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$w .= 'and ';
					}
					$w .= self::numberToString($r);
				}
			} 
			else //  millions
			{    
				$w .= self::numberToString(floor($x/1000000)) .' million';
				$r = fmod($x, 1000000);
				if($r > 0)
				{
					$w .= ' ';
					if($r < 100)
					{
						$w .= 'and ';
					}
					$w .= self::numberToString($r);
				}
			}
		}
		return $w;
	}

	/**
	*	Random Key
	*
	*	@returns a string
	**/
	public static function randomkey($size)
	{
		$bag = "abcefghijknopqrstuwxyzABCDDEFGHIJKLLMMNOPQRSTUVVWXYZabcddefghijkllmmnopqrstuvvwxyzABCEFGHIJKNOPQRSTUWXYZ";
		$key = array();
		$bagsize = strlen($bag) - 1;
		for ($i = 0; $i < $size; $i++)
		{
			$get = rand(0, $bagsize);
			$key[] = $bag[$get];
		}
		return implode($key);
	}

	public static function getCryptKey($type, $default = null)
	{
		if ('basic' === $type)
		{
			// Get the global params
			$params = JComponentHelper::getParams('com_sermondistributor', true);
			$basic_key = $params->get('basic_key', $default);
			if ($basic_key)
			{
				return $basic_key;
			}
		}
		return false;
	}
}
