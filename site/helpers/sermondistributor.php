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

	@version		2.0.x
	@build			3rd March, 2018
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
 * Sermondistributor component helper
 */
abstract class SermondistributorHelper
{  

	/**
	* 	The global params
	**/
	protected static $params = false;

	/**
	* 	Update Watcher
	**/
	public static $updateWatch = 1;

	/**
	* 	Update Watcher (if array is only one value)
	**/
	public static $updateWatch_ = 0;

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
								// lock the info key
								$infoKey = $safe->encryptString($key);
								$sermon->download_links[$filename] = $downloadURL.'&info='.self::base64_urlencode($infoKey).'&link='.self::base64_urlencode($dropURL).'&filename='.$filename;
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
								// lock the info key
								$infoKey = $safe->encryptString($key);
								$sermon->download_links[$filename] = $downloadURL.'&info='.self::base64_urlencode($infoKey).'&link='.self::base64_urlencode($dropURL).'&filename='.$filename;
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

	public static function getNextUpdateValues($asArray = false)
	{
		// find the next value
		$next = false;
		// get actual update values
		$updates = self::getExternalListingUpdateKeys();
		// get last update
		$updatePath = self::getFilePath('path', 'updatelast', 'txt', 'vDm', JPATH_COMPONENT_ADMINISTRATOR);
		if (($lastUpdate = @file_get_contents($updatePath)) !== FALSE && self::checkArray($updates))
		{
			// is it time to watch
			if (self::$updateWatch_ > 0)
			{
				// increment the watch, as this is the start of new round
				self::$updateWatch++;
				// new round has started
				self::$updateWatch_ = 0;
			}
			// now check what is next
			$lastKey = array_search($lastUpdate, $updates);
			if (!is_null($lastKey))
			{
				$nextKey = $lastKey + 1;
				if (isset($updates[$nextKey]))
				{
					$next = $updates[$nextKey];
				}
				else
				{
					// last item in array, so next round about to start
					self::$updateWatch_++;
				}
			}
		}
		// rest and start with the first key
		if (!$next && self::checkArray($updates))
		{
			// save the first set
			$start = reset($updates);
			$next = $start;
		}
		// save to file if next is found
		if ($next)
		{
			self::writeFile($updatePath,$next);
			// convert to array of needed
			if ($asArray)
			{
				if (strpos($next, ',') !== false)
				{
					$next = array_map('trim', explode(',', $next));
				}
				else
				{
					return false;
				}
			}
		}
		return $next;
	}

	/**
	*	Get the file path or url
	* 
	*	@param  string   $type              The (url/path) type to return
	*	@param  string   $target            The Params Target name (if set)
	*	@param  string   $fileType          The kind of filename to generate (if not set no file name is generated)
	*	@param  string   $key               The key to adjust the filename (if not set ignored)
	*	@param  string   $default           The default path if not set in Params (fallback path)
	*	@param  bool     $createIfNotSet    The switch to create the folder if not found
	*
	*	@return  string    On success the path or url is returned based on the type requested
	* 
	*/
	public static function getFilePath($type = 'path', $target = 'filepath', $fileType = null, $key = '', $default = JPATH_SITE . '/images/', $createIfNotSet = true)
	{
		// get the global settings
		if (!self::checkObject(self::$params))
		{
			self::$params = JComponentHelper::getParams('com_sermondistributor');
		}
		$filePath = self::$params->get($target, $default);
		// check the file path (revert to default only of not a hidden file path)
		if ('hiddenfilepath' !== $target && strpos($filePath, JPATH_SITE) === false)
		{
			$filePath = $default;
		}
		jimport('joomla.filesystem.folder');
		// create the folder if it does not exist
		if ($createIfNotSet && !JFolder::exists($filePath))
		{
			JFolder::create($filePath);
		}
		// setup the file name
		$fileName = '';
		// Get basic key
		$basickey = 'Th!s_iS_n0t_sAfe_buT_b3tter_then_n0thiug';
		if (method_exists(get_called_class(), "getCryptKey")) 
		{
			$basickey = self::getCryptKey('basic', $basickey);
		}
		// check the key
		if (!self::checkString($key))
		{
			$key = 'vDm';
		}
		// set the file name
		if (self::checkString($fileType))
		{
			// set the name
			$fileName = trim(md5($type.$target.$basickey.$key) . '.' . trim($fileType, '.'));
		}
		else
		{
			$fileName = trim(md5($type.$target.$basickey.$key)) . '.txt';
		}
		// return the url
		if ('url' === $type)
		{
			if (strpos($filePath, JPATH_SITE) !== false)
			{
				$filePath = trim( str_replace( JPATH_SITE, '', $filePath), '/');
				return JURI::root() . $filePath . '/' . $fileName;
			}
			// since the path is behind the root folder of the site, return only the root url (may be used to build the link)
			return JURI::root();
		}
		// sanitize the path
		return '/' . trim( $filePath, '/' ) . '/' . $fileName;
	}


	/**
	*	Write a file to the server
	* 
	*	@param  string   $path    The path and file name where to safe the data
	*	@param  string   $data    The data to safe
	*
	*	@return  bool true   On success
	* 
	*/
	public static function writeFile($path, $data)
	{
		$klaar = false;
		if (self::checkString($data))
		{
			// open the file
			$fh = fopen($path, "w");
			if (!is_resource($fh))
			{
				return $klaar;
			}
			// write to the file
			if (fwrite($fh, $data))
			{
				// has been done
				$klaar = true;
			}
			// close file.
			fclose($fh);
		}
		return $klaar;
	}

	protected static function saveFile($data, $path_filename)
	{
		return self::writeFile($path_filename, $data);
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

	public static function countDownload($counter, $filename)
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

	public static function getFileInfo($key)
	{
		if (self::checkString($key) && (base64_encode(base64_decode($key, true)) === $key))
		{
			// Get local key
			$localkey = self::getLocalKey();
			$opener = new FOFEncryptAes($localkey, 128);
			$key = rtrim($opener->decryptString($key), "\0");
			// load the links from the database
			$db = JFactory::getDbo();
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->select('size');
			$query->from($db->quoteName('#__sermondistributor_local_listing'));
			$query->where($db->quoteName('key') . ' = '. $db->quote($key));
 			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$info = array();
				// get the size of the file
				$info['filesize'] = $db->loadResult();
				// get the mime type
				$info['type'] = self::mimeType($key);
				// return info
				return $info;
			}
		}
		return false;
	}

	/**
	* 	File Extension to Mimetype
	* 	
	**/
	protected static $fileExtentionToMimeType = array(
		"3dml"		=> "text/vnd.in3d.3dml",
		"3g2"		=> "video/3gpp2",
		"3gp"		=> "video/3gpp",
		"7z"		=> "application/x-7z-compressed",
		"aab"		=> "application/x-authorware-bin",
		"aac"		=> "audio/x-aac",
		"aam"		=> "application/x-authorware-map",
		"aas"		=> "application/x-authorware-seg",
		"abw"		=> "application/x-abiword",
		"ac"		=> "application/pkix-attr-cert",
		"acc"		=> "application/vnd.americandynamics.acc",
		"ace"		=> "application/x-ace-compressed",
		"acu"		=> "application/vnd.acucobol",
		"adp"		=> "audio/adpcm",
		"aep"		=> "application/vnd.audiograph",
		"afp"		=> "application/vnd.ibm.modcap",
		"ahead"		=> "application/vnd.ahead.space",
		"ai"		=> "application/postscript",
		"aif"		=> "audio/x-aiff",
		"air"		=> "application/vnd.adobe.air-application-installer-package+zip",
		"ait"		=> "application/vnd.dvb.ait",
		"ami"		=> "application/vnd.amiga.ami",
		"apk"		=> "application/vnd.android.package-archive",
		"application"	=> "application/x-ms-application",
		"apr"		=> "application/vnd.lotus-approach",
		"asf"		=> "video/x-ms-asf",
		"aso"		=> "application/vnd.accpac.simply.aso",
		"atc"		=> "application/vnd.acucorp",
		"atom"		=> "application/atom+xml",
		"atomcat"	=> "application/atomcat+xml",
		"atomsvc"	=> "application/atomsvc+xml",
		"atx"		=> "application/vnd.antix.game-component",
		"au"		=> "audio/basic",
		"avi"		=> "video/x-msvideo",
		"aw"		=> "application/applixware",
		"azf"		=> "application/vnd.airzip.filesecure.azf",
		"azs"		=> "application/vnd.airzip.filesecure.azs",
		"azw"		=> "application/vnd.amazon.ebook",
		"bcpio"		=> "application/x-bcpio",
		"bdf"		=> "application/x-font-bdf",
		"bdm"		=> "application/vnd.syncml.dm+wbxml",
		"bed"		=> "application/vnd.realvnc.bed",
		"bh2"		=> "application/vnd.fujitsu.oasysprs",
		"bin"		=> "application/octet-stream",
		"bmi"		=> "application/vnd.bmi",
		"bmp"		=> "image/bmp",
		"box"		=> "application/vnd.previewsystems.box",
		"btif"		=> "image/prs.btif",
		"bz"		=> "application/x-bzip",
		"bz2"		=> "application/x-bzip2",
		"c"			=> "text/x-c",
		"c11amc"	=> "application/vnd.cluetrust.cartomobile-config",
		"c11amz"	=> "application/vnd.cluetrust.cartomobile-config-pkg",
		"c4g"		=> "application/vnd.clonk.c4group",
		"cab"		=> "application/vnd.ms-cab-compressed",
		"car"		=> "application/vnd.curl.car",
		"cat"		=> "application/vnd.ms-pki.seccat",
		"ccxml"		=> "application/ccxml+xml,",
		"cdbcmsg"	=> "application/vnd.contact.cmsg",
		"cdkey"		=> "application/vnd.mediastation.cdkey",
		"cdmia"		=> "application/cdmi-capability",
		"cdmic"		=> "application/cdmi-container",
		"cdmid"		=> "application/cdmi-domain",
		"cdmio"		=> "application/cdmi-object",
		"cdmiq"		=> "application/cdmi-queue",
		"cdx"		=> "chemical/x-cdx",
		"cdxml"		=> "application/vnd.chemdraw+xml",
		"cdy"		=> "application/vnd.cinderella",
		"cer"		=> "application/pkix-cert",
		"cgm"		=> "image/cgm",
		"chat"		=> "application/x-chat",
		"chm"		=> "application/vnd.ms-htmlhelp",
		"chrt"		=> "application/vnd.kde.kchart",
		"cif"		=> "chemical/x-cif",
		"cii"		=> "application/vnd.anser-web-certificate-issue-initiation",
		"cil"		=> "application/vnd.ms-artgalry",
		"cla"		=> "application/vnd.claymore",
		"class"		=> "application/java-vm",
		"clkk"		=> "application/vnd.crick.clicker.keyboard",
		"clkp"		=> "application/vnd.crick.clicker.palette",
		"clkt"		=> "application/vnd.crick.clicker.template",
		"clkw"		=> "application/vnd.crick.clicker.wordbank",
		"clkx"		=> "application/vnd.crick.clicker",
		"clp"		=> "application/x-msclip",
		"cmc"		=> "application/vnd.cosmocaller",
		"cmdf"		=> "chemical/x-cmdf",
		"cml"		=> "chemical/x-cml",
		"cmp"		=> "application/vnd.yellowriver-custom-menu",
		"cmx"		=> "image/x-cmx",
		"cod"		=> "application/vnd.rim.cod",
		"cpio"		=> "application/x-cpio",
		"cpt"		=> "application/mac-compactpro",
		"crd"		=> "application/x-mscardfile",
		"crl"		=> "application/pkix-crl",
		"cryptonote"	=> "application/vnd.rig.cryptonote",
		"csh"		=> "application/x-csh",
		"csml"		=> "chemical/x-csml",
		"csp"		=> "application/vnd.commonspace",
		"css"		=> "text/css",
		"csv"		=> "text/csv",
		"cu"		=> "application/cu-seeme",
		"curl"		=> "text/vnd.curl",
		"cww"		=> "application/prs.cww",
		"dae"		=> "model/vnd.collada+xml",
		"daf"		=> "application/vnd.mobius.daf",
		"davmount"	=> "application/davmount+xml",
		"dcurl"		=> "text/vnd.curl.dcurl",
		"dd2"		=> "application/vnd.oma.dd2+xml",
		"ddd"		=> "application/vnd.fujixerox.ddd",
		"deb"		=> "application/x-debian-package",
		"der"		=> "application/x-x509-ca-cert",
		"dfac"		=> "application/vnd.dreamfactory",
		"dir"		=> "application/x-director",
		"dis"		=> "application/vnd.mobius.dis",
		"djvu"		=> "image/vnd.djvu",
		"dna"		=> "application/vnd.dna",
		"doc"		=> "application/msword",
		"docm"		=> "application/vnd.ms-word.document.macroenabled.12",
		"docx"		=> "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
		"dotm"		=> "application/vnd.ms-word.template.macroenabled.12",
		"dotx"		=> "application/vnd.openxmlformats-officedocument.wordprocessingml.template",
		"dp"		=> "application/vnd.osgi.dp",
		"dpg"		=> "application/vnd.dpgraph",
		"dra"		=> "audio/vnd.dra",
		"dsc"		=> "text/prs.lines.tag",
		"dssc"		=> "application/dssc+der",
		"dtb"		=> "application/x-dtbook+xml",
		"dtd"		=> "application/xml-dtd",
		"dts"		=> "audio/vnd.dts",
		"dtshd"		=> "audio/vnd.dts.hd",
		"dvi"		=> "application/x-dvi",
		"dwf"		=> "model/vnd.dwf",
		"dwg"		=> "image/vnd.dwg",
		"dxf"		=> "image/vnd.dxf",
		"dxp"		=> "application/vnd.spotfire.dxp",
		"ecelp4800"	=> "audio/vnd.nuera.ecelp4800",
		"ecelp7470"	=> "audio/vnd.nuera.ecelp7470",
		"ecelp9600"	=> "audio/vnd.nuera.ecelp9600",
		"edm"		=> "application/vnd.novadigm.edm",
		"edx"		=> "application/vnd.novadigm.edx",
		"efif"		=> "application/vnd.picsel",
		"ei6"		=> "application/vnd.pg.osasli",
		"eml"		=> "message/rfc822",
		"emma"		=> "application/emma+xml",
		"eol"		=> "audio/vnd.digital-winds",
		"eot"		=> "application/vnd.ms-fontobject",
		"epub"		=> "application/epub+zip",
		"es"		=> "application/ecmascript",
		"es3"		=> "application/vnd.eszigno3+xml",
		"esf"		=> "application/vnd.epson.esf",
		"etx"		=> "text/x-setext",
		"exe"		=> "application/x-msdownload",
		"exi"		=> "application/exi",
		"ext"		=> "application/vnd.novadigm.ext",
		"ez2"		=> "application/vnd.ezpix-album",
		"ez3"		=> "application/vnd.ezpix-package",
		"f"			=> "text/x-fortran",
		"f4v"		=> "video/x-f4v",
		"fbs"		=> "image/vnd.fastbidsheet",
		"fcs"		=> "application/vnd.isac.fcs",
		"fdf"		=> "application/vnd.fdf",
		"fe_launch"	=> "application/vnd.denovo.fcselayout-link",
		"fg5"		=> "application/vnd.fujitsu.oasysgp",
		"fh"		=> "image/x-freehand",
		"fig"		=> "application/x-xfig",
		"fli"		=> "video/x-fli",
		"flo"		=> "application/vnd.micrografx.flo",
		"flv"		=> "video/x-flv",
		"flw"		=> "application/vnd.kde.kivio",
		"flx"		=> "text/vnd.fmi.flexstor",
		"fly"		=> "text/vnd.fly",
		"fm"		=> "application/vnd.framemaker",
		"fnc"		=> "application/vnd.frogans.fnc",
		"fpx"		=> "image/vnd.fpx",
		"fsc"		=> "application/vnd.fsc.weblaunch",
		"fst"		=> "image/vnd.fst",
		"ftc"		=> "application/vnd.fluxtime.clip",
		"fti"		=> "application/vnd.anser-web-funds-transfer-initiation",
		"fvt"		=> "video/vnd.fvt",
		"fxp"		=> "application/vnd.adobe.fxp",
		"fzs"		=> "application/vnd.fuzzysheet",
		"g2w"		=> "application/vnd.geoplan",
		"g3"		=> "image/g3fax",
		"g3w"		=> "application/vnd.geospace",
		"gac"		=> "application/vnd.groove-account",
		"gdl"		=> "model/vnd.gdl",
		"geo"		=> "application/vnd.dynageo",
		"gex"		=> "application/vnd.geometry-explorer",
		"ggb"		=> "application/vnd.geogebra.file",
		"ggt"		=> "application/vnd.geogebra.tool",
		"ghf"		=> "application/vnd.groove-help",
		"gif"		=> "image/gif",
		"gim"		=> "application/vnd.groove-identity-message",
		"gmx"		=> "application/vnd.gmx",
		"gnumeric"	=> "application/x-gnumeric",
		"gph"		=> "application/vnd.flographit",
		"gqf"		=> "application/vnd.grafeq",
		"gram"		=> "application/srgs",
		"grv"		=> "application/vnd.groove-injector",
		"grxml"		=> "application/srgs+xml",
		"gsf"		=> "application/x-font-ghostscript",
		"gtar"		=> "application/x-gtar",
		"gtm"		=> "application/vnd.groove-tool-message",
		"gtw"		=> "model/vnd.gtw",
		"gv"		=> "text/vnd.graphviz",
		"gxt"		=> "application/vnd.geonext",
		"h261"		=> "video/h261",
		"h263"		=> "video/h263",
		"h264"		=> "video/h264",
		"hal"		=> "application/vnd.hal+xml",
		"hbci"		=> "application/vnd.hbci",
		"hdf"		=> "application/x-hdf",
		"hlp"		=> "application/winhlp",
		"hpgl"		=> "application/vnd.hp-hpgl",
		"hpid"		=> "application/vnd.hp-hpid",
		"hps"		=> "application/vnd.hp-hps",
		"hqx"		=> "application/mac-binhex40",
		"htke"		=> "application/vnd.kenameaapp",
		"html"		=> "text/html",
		"hvd"		=> "application/vnd.yamaha.hv-dic",
		"hvp"		=> "application/vnd.yamaha.hv-voice",
		"hvs"		=> "application/vnd.yamaha.hv-script",
		"i2g"		=> "application/vnd.intergeo",
		"icc"		=> "application/vnd.iccprofile",
		"ice"		=> "x-conference/x-cooltalk",
		"ico"		=> "image/x-icon",
		"ics"		=> "text/calendar",
		"ief"		=> "image/ief",
		"ifm"		=> "application/vnd.shana.informed.formdata",
		"igl"		=> "application/vnd.igloader",
		"igm"		=> "application/vnd.insors.igm",
		"igs"		=> "model/iges",
		"igx"		=> "application/vnd.micrografx.igx",
		"iif"		=> "application/vnd.shana.informed.interchange",
		"imp"		=> "application/vnd.accpac.simply.imp",
		"ims"		=> "application/vnd.ms-ims",
		"ipfix"		=> "application/ipfix",
		"ipk"		=> "application/vnd.shana.informed.package",
		"irm"		=> "application/vnd.ibm.rights-management",
		"irp"		=> "application/vnd.irepository.package+xml",
		"itp"		=> "application/vnd.shana.informed.formtemplate",
		"ivp"		=> "application/vnd.immervision-ivp",
		"ivu"		=> "application/vnd.immervision-ivu",
		"jad"		=> "text/vnd.sun.j2me.app-descriptor",
		"jam"		=> "application/vnd.jam",
		"jar"		=> "application/java-archive",
		"java"		=> "text/x-java-source,java",
		"jisp"		=> "application/vnd.jisp",
		"jlt"		=> "application/vnd.hp-jlyt",
		"jnlp"		=> "application/x-java-jnlp-file",
		"joda"		=> "application/vnd.joost.joda-archive",
		"jpeg"		=> "image/jpeg",
		"jpg"		=> "image/jpeg",
		"jpgv"		=> "video/jpeg",
		"jpm"		=> "video/jpm",
		"js"		=> "application/javascript",
		"json"		=> "application/json",
		"karbon"	=> "application/vnd.kde.karbon",
		"kfo"		=> "application/vnd.kde.kformula",
		"kia"		=> "application/vnd.kidspiration",
		"kml"		=> "application/vnd.google-earth.kml+xml",
		"kmz"		=> "application/vnd.google-earth.kmz",
		"kne"		=> "application/vnd.kinar",
		"kon"		=> "application/vnd.kde.kontour",
		"kpr"		=> "application/vnd.kde.kpresenter",
		"ksp"		=> "application/vnd.kde.kspread",
		"ktx"		=> "image/ktx",
		"ktz"		=> "application/vnd.kahootz",
		"kwd"		=> "application/vnd.kde.kword",
		"lasxml"	=> "application/vnd.las.las+xml",
		"latex"		=> "application/x-latex",
		"lbd"		=> "application/vnd.llamagraphics.life-balance.desktop",
		"lbe"		=> "application/vnd.llamagraphics.life-balance.exchange+xml",
		"les"		=> "application/vnd.hhe.lesson-player",
		"link66"	=> "application/vnd.route66.link66+xml",
		"lrm"		=> "application/vnd.ms-lrm",
		"ltf"		=> "application/vnd.frogans.ltf",
		"lvp"		=> "audio/vnd.lucent.voice",
		"lwp"		=> "application/vnd.lotus-wordpro",
		"m21"		=> "application/mp21",
		"m3u"		=> "audio/x-mpegurl",
		"m3u8"		=> "application/vnd.apple.mpegurl",
		"m4v"		=> "video/x-m4v",
		"ma"		=> "application/mathematica",
		"mads"		=> "application/mads+xml",
		"mag"		=> "application/vnd.ecowin.chart",
		"map"		=> "application/json",
		"mathml"	=> "application/mathml+xml",
		"mbk"		=> "application/vnd.mobius.mbk",
		"mbox"		=> "application/mbox",
		"mc1"		=> "application/vnd.medcalcdata",
		"mcd"		=> "application/vnd.mcd",
		"mcurl"		=> "text/vnd.curl.mcurl",
		"md"		=> "text/x-markdown",
		"mdb"		=> "application/x-msaccess",
		"mdi"		=> "image/vnd.ms-modi",
		"meta4"		=> "application/metalink4+xml",
		"mets"		=> "application/mets+xml",
		"mfm"		=> "application/vnd.mfmp",
		"mgp"		=> "application/vnd.osgeo.mapguide.package",
		"mgz"		=> "application/vnd.proteus.magazine",
		"mid"		=> "audio/midi",
		"mif"		=> "application/vnd.mif",
		"mj2"		=> "video/mj2",
		"mlp"		=> "application/vnd.dolby.mlp",
		"mmd"		=> "application/vnd.chipnuts.karaoke-mmd",
		"mmf"		=> "application/vnd.smaf",
		"mmr"		=> "image/vnd.fujixerox.edmics-mmr",
		"mny"		=> "application/x-msmoney",
		"mods"		=> "application/mods+xml",
		"movie"		=> "video/x-sgi-movie",
		"mp1"		=> "audio/mpeg",
		"mp2"		=> "audio/mpeg",
		"mp3"		=> "audio/mpeg",
		"mp4"		=> "video/mp4",
		"mp4a"		=> "audio/mp4",
		"mpc"		=> "application/vnd.mophun.certificate",
		"mpeg"		=> "video/mpeg",
		"mpga"		=> "audio/mpeg",
		"mpkg"		=> "application/vnd.apple.installer+xml",
		"mpm"		=> "application/vnd.blueice.multipass",
		"mpn"		=> "application/vnd.mophun.application",
		"mpp"		=> "application/vnd.ms-project",
		"mpy"		=> "application/vnd.ibm.minipay",
		"mqy"		=> "application/vnd.mobius.mqy",
		"mrc"		=> "application/marc",
		"mrcx"		=> "application/marcxml+xml",
		"mscml"		=> "application/mediaservercontrol+xml",
		"mseq"		=> "application/vnd.mseq",
		"msf"		=> "application/vnd.epson.msf",
		"msh"		=> "model/mesh",
		"msl"		=> "application/vnd.mobius.msl",
		"msty"		=> "application/vnd.muvee.style",
		"mts"		=> "model/vnd.mts",
		"mus"		=> "application/vnd.musician",
		"musicxml"	=> "application/vnd.recordare.musicxml+xml",
		"mvb"		=> "application/x-msmediaview",
		"mwf"		=> "application/vnd.mfer",
		"mxf"		=> "application/mxf",
		"mxl"		=> "application/vnd.recordare.musicxml",
		"mxml"		=> "application/xv+xml",
		"mxs"		=> "application/vnd.triscape.mxs",
		"mxu"		=> "video/vnd.mpegurl",
		"n-gage"	=> "application/vnd.nokia.n-gage.symbian.install",
		"n3"		=> "text/n3",
		"nbp"		=> "application/vnd.wolfram.player",
		"nc"		=> "application/x-netcdf",
		"ncx"		=> "application/x-dtbncx+xml",
		"ngdat"		=> "application/vnd.nokia.n-gage.data",
		"nlu"		=> "application/vnd.neurolanguage.nlu",
		"nml"		=> "application/vnd.enliven",
		"nnd"		=> "application/vnd.noblenet-directory",
		"nns"		=> "application/vnd.noblenet-sealer",
		"nnw"		=> "application/vnd.noblenet-web",
		"npx"		=> "image/vnd.net-fpx",
		"nsf"		=> "application/vnd.lotus-notes",
		"oa2"		=> "application/vnd.fujitsu.oasys2",
		"oa3"		=> "application/vnd.fujitsu.oasys3",
		"oas"		=> "application/vnd.fujitsu.oasys",
		"obd"		=> "application/x-msbinder",
		"oda"		=> "application/oda",
		"odb"		=> "application/vnd.oasis.opendocument.database",
		"odc"		=> "application/vnd.oasis.opendocument.chart",
		"odf"		=> "application/vnd.oasis.opendocument.formula",
		"odft"		=> "application/vnd.oasis.opendocument.formula-template",
		"odg"		=> "application/vnd.oasis.opendocument.graphics",
		"odi"		=> "application/vnd.oasis.opendocument.image",
		"odm"		=> "application/vnd.oasis.opendocument.text-master",
		"odp"		=> "application/vnd.oasis.opendocument.presentation",
		"ods"		=> "application/vnd.oasis.opendocument.spreadsheet",
		"odt"		=> "application/vnd.oasis.opendocument.text",
		"oga"		=> "audio/ogg",
		"ogv"		=> "video/ogg",
		"ogx"		=> "application/ogg",
		"onetoc"	=> "application/onenote",
		"opf"		=> "application/oebps-package+xml",
		"org"		=> "application/vnd.lotus-organizer",
		"osf"		=> "application/vnd.yamaha.openscoreformat",
		"osfpvg"	=> "application/vnd.yamaha.openscoreformat.osfpvg+xml",
		"otc"		=> "application/vnd.oasis.opendocument.chart-template",
		"otf"		=> "application/x-font-otf",
		"otg"		=> "application/vnd.oasis.opendocument.graphics-template",
		"oth"		=> "application/vnd.oasis.opendocument.text-web",
		"oti"		=> "application/vnd.oasis.opendocument.image-template",
		"otp"		=> "application/vnd.oasis.opendocument.presentation-template",
		"ots"		=> "application/vnd.oasis.opendocument.spreadsheet-template",
		"ott"		=> "application/vnd.oasis.opendocument.text-template",
		"oxt"		=> "application/vnd.openofficeorg.extension",
		"p"			=> "text/x-pascal",
		"p10"		=> "application/pkcs10",
		"p12"		=> "application/x-pkcs12",
		"p7b"		=> "application/x-pkcs7-certificates",
		"p7m"		=> "application/pkcs7-mime",
		"p7r"		=> "application/x-pkcs7-certreqresp",
		"p7s"		=> "application/pkcs7-signature",
		"p8"		=> "application/pkcs8",
		"par"		=> "text/plain-bas",
		"paw"		=> "application/vnd.pawaafile",
		"pbd"		=> "application/vnd.powerbuilder6",
		"pbm"		=> "image/x-portable-bitmap",
		"pcf"		=> "application/x-font-pcf",
		"pcl"		=> "application/vnd.hp-pcl",
		"pclxl"		=> "application/vnd.hp-pclxl",
		"pcurl"		=> "application/vnd.curl.pcurl",
		"pcx"		=> "image/x-pcx",
		"pdb"		=> "application/vnd.palm",
		"pdf"		=> "application/pdf",
		"pfa"		=> "application/x-font-type1",
		"pfr"		=> "application/font-tdpfr",
		"pgm"		=> "image/x-portable-graymap",
		"pgn"		=> "application/x-chess-pgn",
		"pgp"		=> "application/pgp-signature",
		"pic"		=> "image/x-pict",
		"pki"		=> "application/pkixcmp",
		"pkipath"	=> "application/pkix-pkipath",
		"plb"		=> "application/vnd.3gpp.pic-bw-large",
		"plc"		=> "application/vnd.mobius.plc",
		"plf"		=> "application/vnd.pocketlearn",
		"pls"		=> "application/pls+xml",
		"pml"		=> "application/vnd.ctc-posml",
		"png"		=> "image/png",
		"pnm"		=> "image/x-portable-anymap",
		"portpkg"	=> "application/vnd.macports.portpkg",
		"potm"		=> "application/vnd.ms-powerpoint.template.macroenabled.12",
		"potx"		=> "application/vnd.openxmlformats-officedocument.presentationml.template",
		"ppam"		=> "application/vnd.ms-powerpoint.addin.macroenabled.12",
		"ppd"		=> "application/vnd.cups-ppd",
		"ppm"		=> "image/x-portable-pixmap",
		"ppsm"		=> "application/vnd.ms-powerpoint.slideshow.macroenabled.12",
		"ppsx"		=> "application/vnd.openxmlformats-officedocument.presentationml.slideshow",
		"ppt"		=> "application/vnd.ms-powerpoint",
		"pptm"		=> "application/vnd.ms-powerpoint.presentation.macroenabled.12",
		"pptx"		=> "application/vnd.openxmlformats-officedocument.presentationml.presentation",
		"prc"		=> "application/x-mobipocket-ebook",
		"pre"		=> "application/vnd.lotus-freelance",
		"prf"		=> "application/pics-rules",
		"psb"		=> "application/vnd.3gpp.pic-bw-small",
		"psd"		=> "image/vnd.adobe.photoshop",
		"psf"		=> "application/x-font-linux-psf",
		"pskcxml"	=> "application/pskc+xml",
		"ptid"		=> "application/vnd.pvi.ptid1",
		"pub"		=> "application/x-mspublisher",
		"pvb"		=> "application/vnd.3gpp.pic-bw-var",
		"pwn"		=> "application/vnd.3m.post-it-notes",
		"pya"		=> "audio/vnd.ms-playready.media.pya",
		"pyv"		=> "video/vnd.ms-playready.media.pyv",
		"qam"		=> "application/vnd.epson.quickanime",
		"qbo"		=> "application/vnd.intu.qbo",
		"qfx"		=> "application/vnd.intu.qfx",
		"qps"		=> "application/vnd.publishare-delta-tree",
		"qt"		=> "video/quicktime",
		"qxd"		=> "application/vnd.quark.quarkxpress",
		"ram"		=> "audio/x-pn-realaudio",
		"rar"		=> "application/x-rar-compressed",
		"ras"		=> "image/x-cmu-raster",
		"rcprofile"	=> "application/vnd.ipunplugged.rcprofile",
		"rdf"		=> "application/rdf+xml",
		"rdz"		=> "application/vnd.data-vision.rdz",
		"rep"		=> "application/vnd.businessobjects",
		"res"		=> "application/x-dtbresource+xml",
		"rgb"		=> "image/x-rgb",
		"rif"		=> "application/reginfo+xml",
		"rip"		=> "audio/vnd.rip",
		"rl"		=> "application/resource-lists+xml",
		"rlc"		=> "image/vnd.fujixerox.edmics-rlc",
		"rld"		=> "application/resource-lists-diff+xml",
		"rm"		=> "application/vnd.rn-realmedia",
		"rmp"		=> "audio/x-pn-realaudio-plugin",
		"rms"		=> "application/vnd.jcp.javame.midlet-rms",
		"rnc"		=> "application/relax-ng-compact-syntax",
		"rp9"		=> "application/vnd.cloanto.rp9",
		"rpss"		=> "application/vnd.nokia.radio-presets",
		"rpst"		=> "application/vnd.nokia.radio-preset",
		"rq"		=> "application/sparql-query",
		"rs"		=> "application/rls-services+xml",
		"rsd"		=> "application/rsd+xml",
		"rss"		=> "application/rss+xml",
		"rtf"		=> "application/rtf",
		"rtx"		=> "text/richtext",
		"s"			=> "text/x-asm",
		"saf"		=> "application/vnd.yamaha.smaf-audio",
		"sbml"		=> "application/sbml+xml",
		"sc"		=> "application/vnd.ibm.secure-container",
		"scd"		=> "application/x-msschedule",
		"scm"		=> "application/vnd.lotus-screencam",
		"scq"		=> "application/scvp-cv-request",
		"scs"		=> "application/scvp-cv-response",
		"scurl"		=> "text/vnd.curl.scurl",
		"sda"		=> "application/vnd.stardivision.draw",
		"sdc"		=> "application/vnd.stardivision.calc",
		"sdd"		=> "application/vnd.stardivision.impress",
		"sdkm"		=> "application/vnd.solent.sdkm+xml",
		"sdp"		=> "application/sdp",
		"sdw"		=> "application/vnd.stardivision.writer",
		"see"		=> "application/vnd.seemail",
		"seed"		=> "application/vnd.fdsn.seed",
		"sema"		=> "application/vnd.sema",
		"semd"		=> "application/vnd.semd",
		"semf"		=> "application/vnd.semf",
		"ser"		=> "application/java-serialized-object",
		"setpay"	=> "application/set-payment-initiation",
		"setreg"	=> "application/set-registration-initiation",
		"sfd-hdstx"	=> "application/vnd.hydrostatix.sof-data",
		"sfs"		=> "application/vnd.spotfire.sfs",
		"sgl"		=> "application/vnd.stardivision.writer-global",
		"sgml"		=> "text/sgml",
		"sh"		=> "application/x-sh",
		"shar"		=> "application/x-shar",
		"shf"		=> "application/shf+xml",
		"sis"		=> "application/vnd.symbian.install",
		"sit"		=> "application/x-stuffit",
		"sitx"		=> "application/x-stuffitx",
		"skp"		=> "application/vnd.koan",
		"sldm"		=> "application/vnd.ms-powerpoint.slide.macroenabled.12",
		"sldx"		=> "application/vnd.openxmlformats-officedocument.presentationml.slide",
		"slt"		=> "application/vnd.epson.salt",
		"sm"		=> "application/vnd.stepmania.stepchart",
		"smf"		=> "application/vnd.stardivision.math",
		"smi"		=> "application/smil+xml",
		"snf"		=> "application/x-font-snf",
		"spf"		=> "application/vnd.yamaha.smaf-phrase",
		"spl"		=> "application/x-futuresplash",
		"spot"		=> "text/vnd.in3d.spot",
		"spp"		=> "application/scvp-vp-response",
		"spq"		=> "application/scvp-vp-request",
		"src"		=> "application/x-wais-source",
		"sru"		=> "application/sru+xml",
		"srx"		=> "application/sparql-results+xml",
		"sse"		=> "application/vnd.kodak-descriptor",
		"ssf"		=> "application/vnd.epson.ssf",
		"ssml"		=> "application/ssml+xml",
		"st"		=> "application/vnd.sailingtracker.track",
		"stc"		=> "application/vnd.sun.xml.calc.template",
		"std"		=> "application/vnd.sun.xml.draw.template",
		"stf"		=> "application/vnd.wt.stf",
		"sti"		=> "application/vnd.sun.xml.impress.template",
		"stk"		=> "application/hyperstudio",
		"stl"		=> "application/vnd.ms-pki.stl",
		"str"		=> "application/vnd.pg.format",
		"stw"		=> "application/vnd.sun.xml.writer.template",
		"sub"		=> "image/vnd.dvb.subtitle",
		"sus"		=> "application/vnd.sus-calendar",
		"sv4cpio"	=> "application/x-sv4cpio",
		"sv4crc"	=> "application/x-sv4crc",
		"svc"		=> "application/vnd.dvb.service",
		"svd"		=> "application/vnd.svd",
		"svg"		=> "image/svg+xml",
		"swf"		=> "application/x-shockwave-flash",
		"swi"		=> "application/vnd.aristanetworks.swi",
		"sxc"		=> "application/vnd.sun.xml.calc",
		"sxd"		=> "application/vnd.sun.xml.draw",
		"sxg"		=> "application/vnd.sun.xml.writer.global",
		"sxi"		=> "application/vnd.sun.xml.impress",
		"sxm"		=> "application/vnd.sun.xml.math",
		"sxw"		=> "application/vnd.sun.xml.writer",
		"t"			=> "text/troff",
		"tao"		=> "application/vnd.tao.intent-module-archive",
		"tar"		=> "application/x-tar",
		"tcap"		=> "application/vnd.3gpp2.tcap",
		"tcl"		=> "application/x-tcl",
		"teacher"	=> "application/vnd.smart.teacher",
		"tei"		=> "application/tei+xml",
		"tex"		=> "application/x-tex",
		"texinfo"	=> "application/x-texinfo",
		"tfi"		=> "application/thraud+xml",
		"tfm"		=> "application/x-tex-tfm",
		"thmx"		=> "application/vnd.ms-officetheme",
		"tiff"		=> "image/tiff",
		"tmo"		=> "application/vnd.tmobile-livetv",
		"torrent"	=> "application/x-bittorrent",
		"tpl"		=> "application/vnd.groove-tool-template",
		"tpt"		=> "application/vnd.trid.tpt",
		"tra"		=> "application/vnd.trueapp",
		"trm"		=> "application/x-msterminal",
		"tsd"		=> "application/timestamped-data",
		"tsv"		=> "text/tab-separated-values",
		"ttf"		=> "application/x-font-ttf",
		"ttl"		=> "text/turtle",
		"twd"		=> "application/vnd.simtech-mindmapper",
		"txd"		=> "application/vnd.genomatix.tuxedo",
		"txf"		=> "application/vnd.mobius.txf",
		"txt"		=> "text/plain",
		"ufd"		=> "application/vnd.ufdl",
		"umj"		=> "application/vnd.umajin",
		"unityweb"	=> "application/vnd.unity",
		"uoml"		=> "application/vnd.uoml+xml",
		"uri"		=> "text/uri-list",
		"ustar"		=> "application/x-ustar",
		"utz"		=> "application/vnd.uiq.theme",
		"uu"		=> "text/x-uuencode",
		"uva"		=> "audio/vnd.dece.audio",
		"uvh"		=> "video/vnd.dece.hd",
		"uvi"		=> "image/vnd.dece.graphic",
		"uvm"		=> "video/vnd.dece.mobile",
		"uvp"		=> "video/vnd.dece.pd",
		"uvs"		=> "video/vnd.dece.sd",
		"uvu"		=> "video/vnd.uvvu.mp4",
		"uvv"		=> "video/vnd.dece.video",
		"vcd"		=> "application/x-cdlink",
		"vcf"		=> "text/x-vcard",
		"vcg"		=> "application/vnd.groove-vcard",
		"vcs"		=> "text/x-vcalendar",
		"vcx"		=> "application/vnd.vcx",
		"vis"		=> "application/vnd.visionary",
		"viv"		=> "video/vnd.vivo",
		"vsd"		=> "application/vnd.visio",
		"vsf"		=> "application/vnd.vsf",
		"vtu"		=> "model/vnd.vtu",
		"vxml"		=> "application/voicexml+xml",
		"wad"		=> "application/x-doom",
		"wav"		=> "audio/x-wav",
		"wax"		=> "audio/x-ms-wax",
		"wbmp"		=> "image/vnd.wap.wbmp",
		"wbs"		=> "application/vnd.criticaltools.wbs+xml",
		"wbxml"		=> "application/vnd.wap.wbxml",
		"weba"		=> "audio/webm",
		"webm"		=> "video/webm",
		"webp"		=> "image/webp",
		"wg"		=> "application/vnd.pmi.widget",
		"wgt"		=> "application/widget",
		"wm"		=> "video/x-ms-wm",
		"wma"		=> "audio/x-ms-wma",
		"wmd"		=> "application/x-ms-wmd",
		"wmf"		=> "application/x-msmetafile",
		"wml"		=> "text/vnd.wap.wml",
		"wmlc"		=> "application/vnd.wap.wmlc",
		"wmls"		=> "text/vnd.wap.wmlscript",
		"wmlsc"		=> "application/vnd.wap.wmlscriptc",
		"wmv"		=> "video/x-ms-wmv",
		"wmx"		=> "video/x-ms-wmx",
		"wmz"		=> "application/x-ms-wmz",
		"woff"		=> "application/x-font-woff",
		"woff2"		=> "application/font-woff2",
		"wpd"		=> "application/vnd.wordperfect",
		"wpl"		=> "application/vnd.ms-wpl",
		"wps"		=> "application/vnd.ms-works",
		"wqd"		=> "application/vnd.wqd",
		"wri"		=> "application/x-mswrite",
		"wrl"		=> "model/vrml",
		"wsdl"		=> "application/wsdl+xml",
		"wspolicy"	=> "application/wspolicy+xml",
		"wtb"		=> "application/vnd.webturbo",
		"wvx"		=> "video/x-ms-wvx",
		"x3d"		=> "application/vnd.hzn-3d-crossword",
		"xap"		=> "application/x-silverlight-app",
		"xar"		=> "application/vnd.xara",
		"xbap"		=> "application/x-ms-xbap",
		"xbd"		=> "application/vnd.fujixerox.docuworks.binder",
		"xbm"		=> "image/x-xbitmap",
		"xdf"		=> "application/xcap-diff+xml",
		"xdm"		=> "application/vnd.syncml.dm+xml",
		"xdp"		=> "application/vnd.adobe.xdp+xml",
		"xdssc"		=> "application/dssc+xml",
		"xdw"		=> "application/vnd.fujixerox.docuworks",
		"xenc"		=> "application/xenc+xml",
		"xer"		=> "application/patch-ops-error+xml",
		"xfdf"		=> "application/vnd.adobe.xfdf",
		"xfdl"		=> "application/vnd.xfdl",
		"xhtml"		=> "application/xhtml+xml",
		"xif"		=> "image/vnd.xiff",
		"xlam"		=> "application/vnd.ms-excel.addin.macroenabled.12",
		"xls"		=> "application/vnd.ms-excel",
		"xlsb"		=> "application/vnd.ms-excel.sheet.binary.macroenabled.12",
		"xlsm"		=> "application/vnd.ms-excel.sheet.macroenabled.12",
		"xlsx"		=> "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
		"xltm"		=> "application/vnd.ms-excel.template.macroenabled.12",
		"xltx"		=> "application/vnd.openxmlformats-officedocument.spreadsheetml.template",
		"xml"		=> "application/xml",
		"xo"		=> "application/vnd.olpc-sugar",
		"xop"		=> "application/xop+xml",
		"xpi"		=> "application/x-xpinstall",
		"xpm"		=> "image/x-xpixmap",
		"xpr"		=> "application/vnd.is-xpr",
		"xps"		=> "application/vnd.ms-xpsdocument",
		"xpw"		=> "application/vnd.intercon.formnet",
		"xslt"		=> "application/xslt+xml",
		"xsm"		=> "application/vnd.syncml+xml",
		"xspf"		=> "application/xspf+xml",
		"xul"		=> "application/vnd.mozilla.xul+xml",
		"xwd"		=> "image/x-xwindowdump",
		"xyz"		=> "chemical/x-xyz",
		"yaml"		=> "text/yaml",
		"yang"		=> "application/yang",
		"yin"		=> "application/yin+xml",
		"zaz"		=> "application/vnd.zzazz.deck+xml",
		"zip"		=> "application/zip",
		"zir"		=> "application/vnd.zul",
		"zmm"		=> "application/vnd.handheld-entertainment+xml"
	);
	
	/**
	 * Get the mime type based on file extension
	 * 
	 * @param   string   $file The file name or path
	 *
	 * @return  string the mime type on success
	 * 
	 */
	protected static function mimeType($file)
	{
		/**
		 *                  **DISCLAIMER**
		 * This will just match the file extension to the following
		 * array. It does not guarantee that the file is TRULY that
		 * of the extension that this function returns.
		 */

		// get the extension form file
		$extension = \strtolower(\pathinfo($file, \PATHINFO_EXTENSION));
		// check if we have the extension listed
		if (isset(self::$fileExtentionToMimeType[$extension]))
		{
			return self::$fileExtentionToMimeType[$extension];
		}
		return false;
	}
	
	protected static function getDownloadFileName(&$sermon, $file, $type)
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
			$file_path = self::getFilePath('path', 'updateerror', 'txt', $fileKey, JPATH_COMPONENT_ADMINISTRATOR);
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
	* 	Workers to load tasks
	*
	*	@var array 
	*/
	protected static $worker = array();

	/**
	*	Set a worker dynamic URLs
	*
	* 	@var array 
	*/
	protected static $workerURL = array();	

	/**
	*	Set a worker dynamic HEADERs
	*
	* 	@var array 
	*/
	protected static $workerHEADER = array();

	/**
	* 	Curl Error Notice
	*
	*	@var bool 
	*/
	protected static $curlErrorLoaded = false;

	/**
	* 	Set a worker url
	* 
	*	@param  string   $function    The function to target to perform the task
	*	@param  string   $url            The url of where the task is to be performed
	*
	* 	@return  void
	* 
	*/
	public static function setWorkerUrl(&$function, &$url)
	{
		// set the URL if found
		if (self::checkString($url))
		{
			// make sure task function url is up
			self::$workerURL[$function] = $url;
		}
	}

	/**
	* 	Set a worker headers
	* 
	*	@param  string   $function    The function to target to perform the task
	*	@param  array    $headers    The headers needed for these workers/function
	*
	* 	@return  void
	* 
	*/
	public static function setWorkerHeaders(&$function, &$headers)
	{
		// set the Headers if found
		if (self::checkArray($headers))
		{
			// make sure task function headers are set
			self::$workerHEADER[$function] = $headers;
		}
	}

	/**
	* 	Set a worker that needs to perform a task
	* 
	*	@param  mixed   $data         The data to pass to the task
	*	@param  string   $function    The function to target to perform the task
	*	@param  string   $url            The url of where the task is to be performed
	*	@param  array    $headers    The headers needed for these workers/function
	*
	* 	@return  void
	* 
	*/
	public static function setWorker($data, $function, $url = null, $headers = null)
	{
		// make sure task function is up
		if (!isset(self::$worker[$function]))
		{
			self::$worker[$function] = array();
		}
		// load the task
		self::$worker[$function][] = self::lock($data);
		// set the Headers if found
		if ($headers && !isset(self::$workerHEADER[$function]))
		{
			self::setWorkerHeaders($function, $headers);
		}
		// set the URL if found
		if ($url && !isset(self::$workerURL[$function]))
		{
			self::setWorkerUrl($function, $url);
		}
	}

	/**
	*	Run set Workers
	*
	*	@param  string      $function    The function to target to perform the task
	*	@param  string      $perTask    The amount of task per worker
	* 	@param  function   $callback   The option to do a call back when task is completed
	*	@param  int           $threadSize   The size of the thread
	*
	*	@return  bool true   On success
	*
	*/
	public static function runWorker($function, $perTask = 50, $callback = null, $threadSize = 20)
	{
		// set task
		$task = self::lock($function);
		// build headers
		$headers = array('VDM-TASK: ' .$task);
		// build dynamic headers
		if (isset(self::$workerHEADER[$function]) && self::checkArray(self::$workerHEADER[$function]))
		{
			foreach (self::$workerHEADER[$function] as $header)
			{
				$headers[] = $header;
			}
		}
		// build worker options
		$options = array();
		// make sure worker is up
		if (isset(self::$worker[$function]) && self::checkArray(self::$worker[$function]))
		{
			// this load method is for each
			if (1 == $perTask)
			{
				// working with a string = 1
				$headers[] = 'VDM-VALUE-TYPE: ' .self::lock(1);
				// now load the options
				foreach (self::$worker[$function] as $data)
				{
					$options[] = array(CURLOPT_HTTPHEADER => $headers, CURLOPT_POST => 1,  CURLOPT_POSTFIELDS => 'VDM_DATA='. $data);
				}
			}
			// this load method is for bundles 
			else
			{
				// working with an array = 2
				$headers[] = 'VDM-VALUE-TYPE: ' .self::lock(2);
				// now load the options
				$work = array_chunk(self::$worker[$function], $perTask);
				foreach ($work as $data)
				{
					$options[] = array(CURLOPT_HTTPHEADER => $headers, CURLOPT_POST => 1,  CURLOPT_POSTFIELDS => 'VDM_DATA='. implode('___VDM___', $data));
				}
			}
			// relieve worker of task/function
			self::$worker[$function] = array();
		}
		// do the execution
		if (self::checkArray($options))
		{
			if (isset(self::$workerURL[$function]))
			{
				$url = self::$workerURL[$function];
			}
			else
			{
				$url = JURI::root() . '/index.php?option=com_sermondistributor&task=api.worker';
			}
			return self::curlMultiExec($url, $options, $callback, $threadSize);
		}
		return false;
	}

	/**
	*	Do a multi curl execution of tasks
	*
	* 	@param  string      $url               The url of where the task is to be performed
	*  	@param  array       $_options      The array of curl options/headers to set
	*	@param  function   $callback      The option to do a call back when task is completed
	*	@param  int           $threadSize   The size of the thread
	*
	* 	@return  bool true   On success
	*
	*/
	public static function curlMultiExec(&$url, &$_options, $callback = null, $threadSize = 20)
	{
		// make sure we have curl available
		if (!function_exists('curl_version'))
		{
			if (!self::$curlErrorLoaded)
			{
				// set the notice
				JFactory::getApplication()->enqueueMessage(JText::_('COM_SERMONDISTRIBUTOR_HTWOCURL_NOT_FOUNDHTWOPPLEASE_SETUP_CURL_ON_YOUR_SYSTEM_OR_BSERMONDISTRIBUTORB_WILL_NOT_FUNCTION_CORRECTLYP'), 'Error');
				// load the notice only once
				self::$curlErrorLoaded = true;
			}
			return false;
		}
		// make sure we have an url
		if (self::checkString($url))
		{
			// make sure the thread size isn't greater than the # of _options
			$threadSize = (count($_options) < $threadSize) ? count($_options) : $threadSize;
			// set the options
			$options = array();
			$options[CURLOPT_URL] = $url;
			$options[CURLOPT_USERAGENT] = 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2.12) Gecko/20101026 Firefox/3.6.12';
			$options[CURLOPT_RETURNTRANSFER] = TRUE;
			$options[CURLOPT_SSL_VERIFYPEER] = FALSE;
			// start multi threading :)
			$handle = curl_multi_init();
			// start the first batch of requests
			for ($i = 0; $i < $threadSize; $i++)
			{
				if (isset($_options[$i]))
				{
					$ch = curl_init();
					foreach ($_options[$i] as $curlopt => $string)
					{
						$options[$curlopt] = $string;
					}
					curl_setopt_array($ch, $options);
					curl_multi_add_handle($handle, $ch);
				}
			}
			// we wait for all the calls to finish (should not take long)
			do {
				while(($execrun = curl_multi_exec($handle, $working)) == CURLM_CALL_MULTI_PERFORM);
					if($execrun != CURLM_OK)
						break;
				// a request was just completed -- find out which one
				while($done = curl_multi_info_read($handle))
				{
					if (is_callable($callback))
					{
						// $info = curl_getinfo($done['handle']);
						// request successful. process output using the callback function.
						$output = curl_multi_getcontent($done['handle']);
						$callback($output);
					}
					$key = $i + 1;
					if(isset($_options[$key]))
					{
						// start a new request (it's important to do this before removing the old one)
						$ch = curl_init(); $i++;
						// add options
						foreach ($_options[$key] as $curlopt => $string)
						{
							$options[$curlopt] = $string;
						}
						curl_setopt_array($ch, $options);
						curl_multi_add_handle($handle, $ch);
						// remove options again
						foreach ($_options[$key] as $curlopt => $string)
						{
							unset($options[$curlopt]);
						}
					}
					// remove the curl handle that just completed
					curl_multi_remove_handle($handle, $done['handle']);
				}
				// stop wasting CPU cycles and rest for a couple ms
				usleep(10000);
			} while ($working);
			// close the curl multi thread
			curl_multi_close($handle);
			// okay done
			return true;
		}
		return false;
	}

	/**
	* 	the locker
	*
	*  	@var array 
	**/
	protected static $locker = array();

	/**
	* 	the dynamic replacement salt
	*
	*  	@var array 
	**/
	protected static $globalSalt = array();

	/**
	* 	the timer
	*
	*  	@var object
	**/
	protected static $keytimer;

	/**
	*	To Lock string
	*
	*	@param string  $string       The string/array to lock
	*	@param string  $key          The custom key to use
	*	@param int      $salt           The switch to add salt and type of salt
	*	@param int      $dynamic    The dynamic replacement array of salt build string
	*	@param int      $urlencode  The switch to control url encoding
	**/
	public static function lock($string, $key = null, $salt = 2, $dynamic = null, $urlencode = true)
	{
		// get the global settings
		if (!$key || !self::checkString($key))
		{
			// set temp timer
			$timer = 2;
			// if we have a timer use it
			if ($salt > 0)
			{
				$timer = $salt;
			}
			if (method_exists(get_called_class(), "getCryptKey")) 
			{
				$key = self::getCryptKey('basic', self::salt($timer, $dynamic));
			}
			else
			{
				$key = self::salt($timer, $dynamic);
			}
		}
		// check if we have a salt timer
		if ($salt > 0)
		{
			$key .= self::salt($salt, $dynamic);
		}
		// get the locker settings
		if (!isset(self::$locker[$key]) || !self::checkObject(self::$locker[$key]))
		{
			self::$locker[$key] = new FOFEncryptAes($key, 128);
		}
		// convert array to string
		if (self::checkArray($string))
		{
			$string = serialize($string);
		}
		// prep for url
		if ($urlencode)
		{
			return self::base64_urlencode(self::$locker[$key]->encryptString($string));
		}
		return self::$locker[$key]->encryptString($string);
	}

	/**
	* 	To un-Lock string
	*
	*	@param string  $string       The string to unlock
	*	@param string  $key          The custom key to use
	*	@param int      $salt           The switch to add salt and type of salt
	*	@param int      $dynamic    The dynamic replacement array of salt build string
	*	@param int      $urlencode  The switch to control url decoding
	**/
	public static function unlock($string, $key = null, $salt = 2, $dynamic = null, $urlencode = true)
	{
		// get the global settings
		if (!$key || !self::checkString($key))
		{
			// set temp timer
			$timer = 2;
			// if we have a timer use it
			if ($salt > 0)
			{
				$timer = $salt;
			}
			// get secure key
			if (method_exists(get_called_class(), "getCryptKey")) 
			{
				$key = self::getCryptKey('basic', self::salt($timer, $dynamic));
			}
			else
			{
				$key = self::salt($timer, $dynamic);
			}
		}
		// check if we have a salt timer
		if ($salt > 0)
		{
			$key .= self::salt($salt, $dynamic);
		}
		// get the locker settings
		if (!isset(self::$locker[$key]) || !self::checkObject(self::$locker[$key]))
		{
			self::$locker[$key] = new FOFEncryptAes($key, 128);
		}
		// make sure we have real base64
		if ($urlencode)
		{
			$string = self::base64_urldecode($string);
		}
		// basic decrypt string.
		if (!empty($string) && !is_numeric($string) && $string === base64_encode(base64_decode($string, true)))
		{
			$string = rtrim(self::$locker[$key]->decryptString($string), "\0");
			// convert serial string to array
			if (self::is_serial($string))
			{
				$string = unserialize($string);
			}
		}
		return $string;
	}

	/**
	* 	The Salt
	*
	*	@param int      $type          The type of length the salt should be valid
	*	@param int      $dynamic    The dynamic replacement array of salt build string
	**/
	public static function salt($type = 1, $dynamic = null)
	{
		// get dynamic replacement salt
		$dynamic = self::getDynamicSalt($dynamic);
		// get the key timer
		if (!self::checkObject(self::$keytimer))
		{
			// load the date time object
			self::$keytimer = new DateTime;
			// set the correct time stamp
			$vdmLocalTime = new DateTimeZone('Africa/Windhoek');
			self::$keytimer->setTimezone($vdmLocalTime);
		}
		// set type
		if ($type == 2)
		{
			// hour
			$format = 'Y-m-d \o\n ' . self::periodFix(self::$keytimer->format('H'));
		}
		elseif ($type == 3)
		{
			// day
			$format = 'Y-m-' . self::periodFix(self::$keytimer->format('d'));
		}
		elseif ($type == 4)
		{
			// month
			$format = 'Y-' . self::periodFix(self::$keytimer->format('m'));
		}
		else
		{
			// minute
			$format = 'Y-m-d \o\n H:' . self::periodFix(self::$keytimer->format('i'));
		}
		// get key
		if (self::checkArray($dynamic))
		{
			return md5(str_replace(array_keys($dynamic), array_values($dynamic), self::$keytimer->format($format) . ' @ VDM.I0'));
		}
		return md5(self::$keytimer->format($format) . ' @ VDM.I0');
	}

	/**
	*	The function to insure the salt is valid within the given period (third try)
	*
	*	@param int $main    The main number
	*/
	protected static function periodFix($main)
	{
		return round($main / 3) * 3;
	}

	/**
	*	Check if a string is serialized
	*	@param string $string
	*/
	public static function is_serial($string)
	{
		return (@unserialize($string) !== false);
	}

	/**
	*	Get dynamic replacement salt
	*/
	public static function getDynamicSalt($dynamic = null)
	{
		// load global if not manually set
		if (!self::checkArray($dynamic))
		{
			return self::getGlobalSalt();
		}
		// return manual values if set
		else
		{
			return $dynamic;
		}
	}

	/**
	*	The random or dynamic secret salt
	*/
	public static function getSecretSalt($string = null, $size = 9)
	{
		// set the string
		if (!$string)
		{
			// get random string 
			$string = self::randomkey($size);
		}
		// convert string to array
		$string = self::safeString($string);
		// convert string to array
		$array = str_split($string);
		// insure only unique values are used
		$array = array_unique($array);
		// set the size
		$size = ($size <= count($array)) ? $size : count($array);
		// down size the 
		return array_slice($array, 0, $size);
	}

	/**
	*	Get global replacement salt
	*/
	public static function getGlobalSalt()
	{
		// load from memory if found
		if (!self::checkArray(self::$globalSalt))
		{
			// get the global settings
			if (!self::checkObject(self::$params))
			{
				self::$params = JComponentHelper::getParams('com_sermondistributor');
			}
			// check if we have a global dynamic replacement array available (format -->  ' 1->!,3->E,4->A')
			$tmp = self::$params->get('dynamic_salt', null);
			if (self::checkString($tmp) && strpos($tmp, ',') !== false && strpos($tmp, '->') !== false)
			{
				$salt = array_map('trim', (array) explode(',', $tmp));
				if (self::checkArray($salt ))
				{
					foreach($salt as $replace)
					{
						$dynamic = array_map('trim', (array) explode('->', $replace));
						if (isset($dynamic[0]) && isset($dynamic[1]))
						{
							self::$globalSalt[$dynamic[0]] = $dynamic[1];
						}
					}
				}
			}
		}
		// return global if found
		if (self::checkArray(self::$globalSalt))
		{
			return self::$globalSalt;
		}
		// return default as fail safe
		return array('1' => '!', '3' => 'E', '4' => 'A');	
	}

	/**
	*	Close public protocol
	*/
	public static function closePublicProtocol($id, $public)
	{
		// get secret salt
		$secretSalt = self::getSecretSalt(self::salt(1,array('4' => 'R','1' => 'E','2' => 'G','7' => 'J','8' => 'A')));
		// get the key
		$key = self::salt(1, $secretSalt);
		// get secret salt
		$secret = self::getSecretSalt();
		// set the secret
		$close['SECRET'] = self::lock($secret, $key, 1, array('1' => 's', '3' => 'R', '4' => 'D'));
		// get the key
		$key = self::salt(1, $secret);
		// get the public key
		$close['PUBLIC'] = self::lock($public, $key, 1, array('1' => '!', '3' => 'E', '4' => 'A'));
		// get secret salt
		$secretSalt = self::getSecretSalt($public);
		// get the key
		$key = self::salt(1, $secretSalt);
		// get the ID
		$close['ID'] = self::unlock($id, $key, 1, array('1' => 'i', '3' => 'e', '4' => 'B'));
		// return closed values
		return $close;
	}

	/**
	*	Open public protocol
	*/
	public static function openPublicProtocol($SECRET, $ID, $PUBLIC)
	{
		// get secret salt
		$secretSalt = self::getSecretSalt(self::salt(1,array('4' => 'R','1' => 'E','2' => 'G','7' => 'J','8' => 'A')));
		// get the key
		$key = self::salt(1, $secretSalt);
		// get the $SECRET
		$SECRET = self::unlock($SECRET, $key, 1, array('1' => 's', '3' => 'R', '4' => 'D'));
		// get the key
		$key = self::salt(1, $SECRET);
		// get the public key
		$open['public'] = self::unlock($PUBLIC, $key, 1, array('1' => '!', '3' => 'E', '4' => 'A'));
		// get secret salt
		$secretSalt = self::getSecretSalt($open['public']);
		// get the key
		$key = self::salt(1, $secretSalt);
		// get the ID
		$open['id'] = self::unlock($ID, $key, 1, array('1' => 'i', '3' => 'e', '4' => 'B'));
		// return opened values
		return $open;
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
	
	/**
	*	Load the Component xml manifest.
	**/
	public static function manifest()
	{
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_sermondistributor/sermondistributor.xml";
		return simplexml_load_file($manifestUrl);
	}
	
	/**
	*	Joomla version object
	**/	
	protected static $JVersion;
	
	/**
	*	set/get Joomla version
	**/
	public static function jVersion()
	{
		// check if set
		if (!self::checkObject(self::$JVersion))
		{
			self::$JVersion = new JVersion();
		}
		return self::$JVersion;
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
			if ((NULL !== $params->get("showContributor".$nr)) && ($params->get("showContributor".$nr) == 2 || $params->get("showContributor".$nr) == 3))
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
		$query->where('a.site_view = '.$db->quote($view));
		$query->where('a.location = 2');
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
	*	Get any component's model
	**/
	public static function getModel($name, $path = JPATH_COMPONENT_SITE, $component = 'Sermondistributor', $config = array())
	{
		// fix the name
		$name = self::safeString($name);
		// full path
		$fullPath = $path . '/models';
		// set prefix
		$prefix = $component.'Model';
		// load the model file
		JModelLegacy::addIncludePath($fullPath, $prefix);
		// get instance
		$model = JModelLegacy::getInstance($name, $prefix, $config);
		// if model not found (strange)
		if ($model == false)
		{
			jimport('joomla.filesystem.file');
			// get file path
			$filePath = $path.'/'.$name.'.php';
			$fullPath = $fullPath.'/'.$name.'.php';
			// check if it exists
			if (JFile::exists($filePath))
			{
				// get the file
				require_once $filePath;
			}
			elseif (JFile::exists($fullPath))
			{
				// get the file
				require_once $fullPath;
			}
			// build class names
			$modelClass = $prefix.$name;
			if (class_exists($modelClass))
			{
				// initialize the model
				return new $modelClass($config);
			}
		}
		return $model;
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
			$this->setError($error);

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
			// prep main <-- why? well if $main='' is empty then $table can be categories or users
			if (self::checkString($main))
			{
				$main = '_'.ltrim($main, '_');
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
				$query->from($db->quoteName('#_'.$main.'_'.$table));
			}
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
			$fallback = true;
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
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
							}
						}
						elseif ($user->authorise($view.'edit.own', 'com_sermondistributor.'.$view.'.' . (int) $record->id))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
							}
						}
						elseif ($user->authorise('core.edit.own', 'com_sermondistributor'))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
							}
						}
						elseif ($user->authorise($view.'edit.own', 'com_sermondistributor'))
						{
							// If the owner matches 'me' then allow.
							if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
							{
								$result->set($action->name, true);
								// set not to use component default
								$fallback = false;
							}
							else
							{
								$result->set($action->name, false);
								// set not to use component default
								$fallback = false;
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
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
								}
							}
							elseif ($user->authorise($view.'edit.own', 'com_sermondistributor.'.$views.'.category.' . (int) $record->catid))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
								}
							}
							elseif ($user->authorise('core.edit.own', 'com_sermondistributor'))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
								}
							}
							elseif ($user->authorise($view.'edit.own', 'com_sermondistributor'))
							{
								// If the owner matches 'me' then allow.
								if (isset($record->created_by) && $record->created_by > 0 && ($record->created_by == $user->id))
								{
									$result->set($action->name, true);
									// set not to use component default
									$fallback = false;
								}
								else
								{
									$result->set($action->name, false);
									// set not to use component default
									$fallback = false;
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
	
	/**
	*	Check if we are connected
	*	Thanks https://stackoverflow.com/a/4860432/1429677
	*
	*	@returns bool true on success
	**/
	public static function isConnected()
	{
		// If example.com is down, then probably the whole internet is down, since IANA maintains the domain. Right?
		$connected = @fsockopen("www.example.com", 80); 
			// website, port  (try 80 or 443)
		if ($connected)
		{
			//action when connected
			$is_conn = true;
			fclose($connected);
		}
		else
		{
			//action in connection failure
			$is_conn = false;
		}
		return $is_conn;
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

	// typo sorry!
	public static function sorten($string, $length = 40, $addTip = true)
	{
		return self::shorten($string, $length, $addTip);
	}

	public static function shorten($string, $length = 40, $addTip = true)
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
				$title = self::shorten($string, 400 , false);
				return '<span class="hasTip" title="'.$title.'" style="cursor:help">'.trim($newString).'...</span>';
			}
			elseif ($initial != $final && !$addTip)
			{
				return trim($newString).'...';
			}
		}
		return $string;
	}

	public static function safeString($string, $type = 'L', $spacer = '_', $replaceNumbers = true)
	{
		if ($replaceNumbers === true)
		{
			// remove all numbers and replace with english text version (works well only up to millions)
			$string = self::replaceNumbers($string);
		}
		// 0nly continue if we have a string
		if (self::checkString($string))
		{
			// create file name without the extention that is safe
			if ($type === 'filename')
			{
				// make sure VDM is not in the string
				$string = str_replace('VDM', 'vDm', $string);
				// Remove anything which isn't a word, whitespace, number
				// or any of the following caracters -_()
				// If you don't need to handle multi-byte characters
				// you can use preg_replace rather than mb_ereg_replace
				// Thanks @Łukasz Rysiak!
				// $string = mb_ereg_replace("([^\w\s\d\-_\(\)])", '', $string);
				$string = preg_replace("([^\w\s\d\-_\(\)])", '', $string);
				// http://stackoverflow.com/a/2021729/1429677
				return preg_replace('/\s+/', ' ', $string);
			}
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

	public static function htmlEscape($var, $charset = 'UTF-8', $shorten = false, $length = 40)
	{
		if (self::checkString($var))
		{
			$filter = new JFilterInput();
			$string = $filter->clean(html_entity_decode(htmlentities($var, ENT_COMPAT, $charset)), 'HTML');
			if ($shorten)
			{
           		return self::shorten($string,$length);
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
