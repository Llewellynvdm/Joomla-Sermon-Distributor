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
	@subpackage		SermondistributorHelper.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace TrueChristianChurch\Component\Sermondistributor\Administrator\Helper;



// register additional namespace
\spl_autoload_register(function ($class) {
	// project-specific base directories and namespace prefix
	$search = [
		'libraries/jcb_powers/VDM.Joomla.FOF' => 'VDM\\Joomla\\FOF',
		'libraries/jcb_powers/VDM.Joomla' => 'VDM\\Joomla'
	];
	// Start the search and load if found
	$found = false;
	$found_base_dir = "";
	$found_len = 0;
	foreach ($search as $base_dir => $prefix)
	{
		// does the class use the namespace prefix?
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) === 0)
		{
			// we have a match so load the values
			$found = true;
			$found_base_dir = $base_dir;
			$found_len = $len;
			// done here
			break;
		}
	}
	// check if we found a match
	if (!$found)
	{
		// not found so move to the next registered autoloader
		return;
	}
	// get the relative class name
	$relative_class = substr($class, $found_len);
	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = JPATH_ROOT . '/' . $found_base_dir . '/src' . str_replace('\\', '/', $relative_class) . '.php';
	// if the file exists, require it
	if (file_exists($file))
	{
		require $file;
	}
});

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Access\Access;
use Joomla\CMS\Access\Rules as AccessRules;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Language\Language;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Version;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Joomla\CMS\Filesystem\Folder;
use VDM\Joomla\FOF\Encrypt\AES;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\FileHelper;
use VDM\Joomla\Utilities\JsonHelper;
use VDM\Joomla\Utilities\ObjectHelper;
use VDM\Joomla\Utilities\StringHelper as UtilitiesStringHelper;
use VDM\Joomla\Utilities\MimeHelper;
use VDM\Joomla\Utilities\GetHelper;
use VDM\Joomla\Utilities\FormHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor component helper.
 *
 * @since   3.0
 */
abstract class SermondistributorHelper
{
	/**
	 * Composer Switch
	 *
	 * @var      array
	 */
	protected static $composer = [];

	/**
	 * The Main Active Language
	 *
	 * @var      string
	 */
	public static $langTag;

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
	 * prepare base64 string for url
	 *
	 * @deprecate Use urlencode();
	 */
	public static function base64_urlencode($string, $encode = false)
	{
		if ($encode)
		{
			$string = base64_encode($string);
		}
		return str_replace(array('+', '/'), array('-', '_'), $string);
	}

	/**
	 * prepare base64 string form url
	 *
	 * @deprecate
	 */
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
		$safe = new AES($localkey, 128);
		// internal download url
		$keyCounter = new \stdClass;
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
		$token = Session::getFormToken();
		$downloadURL = Uri::root().'index.php?option=com_sermondistributor&task=download.file&key='.$keyCounter.'&token='.$token;
		// check if local .htaccess should be set
		$setHtaccess = false;
		$onclick = ' onclick="sermonCounter(\''.$keyCounterRAW.'\',\'FILENAME\');"';
		// check what source of our link
		switch ($sermon->source)
		{
			case 1:
				// local file get local folder and check if outside root (if not then allow direct)
				$localFolder = ComponentHelper::getParams('com_sermondistributor')->get('localfolder', JPATH_ROOT.'/images').'/';
				// should we allow direct downloads
				$allowDirect = false;
				if (2 == $sermon->link_type && strpos($localFolder, JPATH_ROOT) !== false)
				{
					$allowDirect = true;
					$localFolderURL = Uri::root().str_replace(JPATH_ROOT, '', $localFolder);
					// insure no double // is in the URL
					$localFolderURL = str_replace('//', '/', $localFolderURL);
					$localFolderURL = str_replace(':/', '://', $localFolderURL);
				}
				// insure no double // is in the path name
				$localFolder = str_replace('//', '/', $localFolder);
				$localFolder = str_replace(':/', '://', $localFolder);
				if (UtilitiesArrayHelper::check($sermon->local_files))
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
				$addToButton = ComponentHelper::getParams('com_sermondistributor')->get('add_to_button', 0);
				if (1 == $sermon->build)
				{
					if (UtilitiesArrayHelper::check($sermon->manual_files))
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
					if (UtilitiesArrayHelper::check($sermon->auto_sermons))
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
		$updatePath = FileHelper::getPath('path', 'updatelast', 'txt', 'vDm', JPATH_COMPONENT_ADMINISTRATOR);
		if (($lastUpdate = FileHelper::getContent($updatePath, FALSE)) !== FALSE && UtilitiesArrayHelper::check($updates))
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
		if (!$next && UtilitiesArrayHelper::check($updates))
		{
			// save the first set
			$start = reset($updates);
			$next = $start;
		}
		// save to file if next is found
		if ($next)
		{
			FileHelper::write($updatePath,$next);
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
	 * Get the file path or url
	 *
	 * @param  string   $type              The (url/path) type to return
	 * @param  string   $target            The Params Target name (if set)
	 * @param  string   $fileType          The kind of filename to generate (if not set no file name is generated)
	 * @param  string   $key               The key to adjust the filename (if not set ignored)
	 * @param  string   $default           The default path if not set in Params (fallback path)
	 * @param  bool     $createIfNotSet    The switch to create the folder if not found
	 *
	 * @return  string    On success the path or url is returned based on the type requested
	 * @deprecated 3.3 Use FileHelper::getPath(...);
	 */
	public static function getFilePath($type = 'path', $target = 'filepath', $fileType = null, $key = '', $default = '', $createIfNotSet = true)
	{
		// Get the file path or url
		return FileHelper::getPath($type, $target, $fileType, $key, $default, $createIfNotSet);
	}

	/**
	 * Write a file to the server
	 *
	 * @param  string   $path    The path and file name where to safe the data
	 * @param  string   $data    The data to safe
	 *
	 * @return  bool true   On success
	 * @deprecated 3.3 Use FileHelper::write(...);
	 */
	public static function writeFile($path, $data)
	{
		return FileHelper::write($path, $data);
	}
	protected static function saveFile($data, $path_filename)
	{
		return FileHelper::write($path_filename, $data);
	}

	public static function getExternalListingUpdateKeys($id = null, $updateMethod = 2, $returnType = 1)
	{
		// first check if this file already has statistics
		$db = Factory::getDbo();
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
		elseif ($id && UtilitiesArrayHelper::check($id))
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
							if (JsonHelper::check($result->sharedurl))
							{
								$targets = json_decode($result->sharedurl)->tsharedurl;
							}
						break;
						case 2: // folders
							if (JsonHelper::check($result->folder))
							{
								$targets = json_decode($result->folder)->tfolder;
							}
						break;
					}
					if (UtilitiesArrayHelper::check($targets))
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
		$opener = new AES($localkey, 128);
		$counter = json_decode(rtrim($opener->decryptString($counter), "\0"));
		if (ObjectHelper::check($counter))
		{
			$counter->filename = $filename;
			// set the date object
			$date = Factory::getDate();
			// first check if this file already has statistics
			$db = Factory::getDbo();
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
		if (UtilitiesStringHelper::check($key) && (base64_encode(base64_decode($key, true)) === $key))
		{
			// Get local key
			$localkey = self::getLocalKey();
			$opener = new AES($localkey, 128);
			$key = rtrim($opener->decryptString($key), "\0");
			// load the links from the database
			$db = Factory::getDbo();
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
				$info['type'] = MimeHelper::mimeType($key);
				// return info
				return $info;
			}
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
			$downloadName[] = UtilitiesStringHelper::safe($sermon->preacher_name,'U');
		}
		// add the series name if set
		if ($sermon->series)
		{
			$downloadName[] = UtilitiesStringHelper::safe($sermon->series_name,'F');
		}
		// add the category name if set
		if ($sermon->catid && UtilitiesStringHelper::check($sermon->category))
		{
			$downloadName[] = UtilitiesStringHelper::safe($sermon->category, 'F');
		}
		if ('dropbox_manual' == $type || 'local' == $type)
		{
			// add the main file name
			$downloadName[] = UtilitiesStringHelper::safe($sermon->name,'F');
			$downloadName[] = UtilitiesStringHelper::safe($file,'F');
		}
		else
		{
			$downloadName[] = UtilitiesStringHelper::safe($sermon->name,'F');
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
		if (isset(self::${$get.'_externalsource_'.$type}) && UtilitiesArrayHelper::check(self::${$get.'_externalsource_'.$type}))
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
				$db = Factory::getDbo();
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
			self::$localkey[$type] = ComponentHelper::getParams('com_sermondistributor')->get($type, 'localKey34fdWEkl');
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
			if (UtilitiesStringHelper::check($type) && in_array($type,$types))
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
		self::setUpdateError($id, array(Text::_('COM_SERMONDISTRIBUTOR_THE_EXTERNAL_SOURCE_COULD_NOT_BE_FOUND')));
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
			$errors[] = Text::_('COM_SERMONDISTRIBUTOR_THIS_SOURCE_HAS_NO_LOCAL_LISTING_SET');
		}
		// build the return string
		if (isset($updateInfo['last']) || UtilitiesArrayHelper::check($errors))
		{
			$body = array();
			// great we have source status
			if (isset($updateInfo['last']))
			{
				$body[] = '<h3>'. Text::_('COM_SERMONDISTRIBUTOR_LISTING_INFO') . '</h3>';
				$body[] = '<p><b>'. Text::_('COM_SERMONDISTRIBUTOR_LAST_UPDATE') . ':</b> <em>'.$updateInfo['last'];
				$body[] = '</em><br /><b>'. Text::_('COM_SERMONDISTRIBUTOR_NUMBER_OF_FILES_LISTED') . ':</b> <em>'.$updateInfo['qty'];
				$body[] = '</em></p>';
			}
			// now set any errors found
			if (UtilitiesArrayHelper::check($errors))
			{
				$body[] = '<h3>'. Text::_('COM_SERMONDISTRIBUTOR_NOTICE') . '</h3>';
				$body[] = implode('', $errors);
			}
			return '<a class="btn btn-small btn-success" href="#source-status'.$id.'" data-toggle="modal">'.Text::_('COM_SERMONDISTRIBUTOR_VIEW_UPDATE_STATUS').'</a>' 
				. Html::_('bootstrap.renderModal', 'source-status'.$id, array('title' => Text::_('COM_SERMONDISTRIBUTOR_SOURCE_STATUS_REPORT')), implode('', $body));
		}
 		// no status found
		return false;
	}

	public static function updateInfo($id)
	{
		$db = Factory::getDbo();
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
			$file_path = FileHelper::getPath('path', 'updateerror', 'txt', $fileKey, JPATH_COMPONENT_ADMINISTRATOR);
			// check if it is set
			if (($text = FileHelper::getContent($file_path, FALSE)) !== FALSE)
			{
				// no error on success
				if ('success' != $text)
				{
					return $text;
				}
			}
			return false;
		}
		elseif (isset(self::$updateErrors[$id]) && UtilitiesArrayHelper::check(self::$updateErrors[$id]))
		{
			return '<ul><li>'.implode('</li><li>', self::$updateErrors[$id]).'</li></ul>';
		}
		return Text::_('COM_SERMONDISTRIBUTOR_UNKNOWN_ERROR_HAS_OCCURRED');
	}

	protected static function setUpdateError($id, $errorArray)
	{
		if (UtilitiesArrayHelper::check($errorArray) && $id > 0)
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
	 * Change to nice fancy date
	 */
	public static function fancyDate($date, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('jS \o\f F Y',$date);
	}

	/**
	 * get date based in period past
	 */
	public static function fancyDynamicDate($date, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		// older then year
		$lastyear = date("Y", strtotime("-1 year"));
		$tragetyear = date("Y", $date);
		if ($tragetyear <= $lastyear)
		{
			return date('m/d/y', $date);
		}
		// same day
		$yesterday = strtotime("-1 day");
		if ($date > $yesterday)
		{
			return date('g:i A', $date);
		}
		// just month day
		return date('M j', $date);
	}

	/**
	 * Change to nice fancy day time and date
	 */
	public static function fancyDayTimeDate($time, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($time))
		{
			$time = strtotime($time);
		}
		return date('D ga jS \o\f F Y',$time);
	}

	/**
	 * Change to nice fancy time and date
	 */
	public static function fancyDateTime($time, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($time))
		{
			$time = strtotime($time);
		}
		return date('(G:i) jS \o\f F Y',$time);
	}

	/**
	 * Change to nice hour:minutes time
	 */
	public static function fancyTime($time, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($time))
		{
			$time = strtotime($time);
		}
		return date('G:i',$time);
	}

	/**
	 * set the date day as Sunday through Saturday
	 */
	public static function setDayName($date, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('l', $date);
	}

	/**
	 * set the date month as January through December
	 */
	public static function setMonthName($date, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('F', $date);
	}

	/**
	 * set the date day as 1st
	 */
	public static function setDay($date, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('jS', $date);
	}

	/**
	 * set the date month as 5
	 */
	public static function setMonth($date, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('n', $date);
	}

	/**
	 * set the date year as 2004 (for charts)
	 */
	public static function setYear($date, $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('Y', $date);
	}

	/**
	 * set the date as 2004/05 (for charts)
	 */
	public static function setYearMonth($date, $spacer = '/', $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('Y' . $spacer . 'm', $date);
	}

	/**
	 * set the date as 2004/05/03 (for charts)
	 */
	public static function setYearMonthDay($date, $spacer = '/', $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('Y' . $spacer . 'm' . $spacer . 'd', $date);
	}

	/**
	 * set the date as 03/05/2004
	 */
	public static function setDayMonthYear($date, $spacer = '/', $check_stamp = true)
	{
		if ($check_stamp && !self::isValidTimeStamp($date))
		{
			$date = strtotime($date);
		}
		return date('d' . $spacer . 'm' . $spacer . 'Y', $date);
	}

	/**
	 * Check if string is a valid time stamp
	 */
	public static function isValidTimeStamp($timestamp)
	{
		return ((int) $timestamp === $timestamp)
		&& ($timestamp <= PHP_INT_MAX)
		&& ($timestamp >= ~PHP_INT_MAX);
	}

	/**
	 * Check if string is a valid date
	 * https://www.php.net/manual/en/function.checkdate.php#113205
	 */
	public static function isValidateDate($date, $format = 'Y-m-d H:i:s')
	{
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}


	/**
	 * Workers to load tasks
	 *
	 * @var array
	 * @since   3.1
	 */
	protected static array $worker = [];

	/**
	 * Set a worker dynamic URLs
	 *
	 * @var array 
	 * @since   3.1
	 */
	protected static array $workerURL = [];	

	/**
	 * Set a worker dynamic HEADERs
	 *
	 * @var array 
	 * @since   3.1
	 */
	protected static array $workerHEADER = [];

	/**
	 * 	Curl Error Notice
	 *
	 * @var bool 
	 * @since   3.1
	 */
	protected static bool $curlErrorLoaded = false;

	/**
	 * check if a worker has more work
	 * 
	 * @param  string   $function    The function to target to perform the task
	 *
	 * @return  bool
	 * @since   3.1
	 */
	public static function hasWork(string $function): bool
	{
		if (isset(self::$worker[$function]) && UtilitiesArrayHelper::check(self::$worker[$function]))
		{
			return count( (array) self::$worker[$function]);
		}
		return false;
	}

	/**
	 * Set a worker url
	 * 
	 * @param  string   $function    The function to target to perform the task
	 * @param  string   $url            The url of where the task is to be performed
	 *
	 * @return  void
	 * @since   3.1
	  */
	public static function setWorkerUrl(string $function, string $url): void
	{
		// set the URL if found
		if (UtilitiesStringHelper::check($url))
		{
			// make sure task function url is up
			self::$workerURL[$function] = $url;
		}
	}

	/**
	 * Set a worker headers
	 * 
	 * @param  string      $function    The function to target to perform the task
	 * @param  array|null  $headers    The headers needed for these workers/function
	 *
	 * @return  void
	 * @since   3.1
	 */
	public static function setWorkerHeaders(string $function, ?array $headers): void
	{
		// set the Headers if found
		if (UtilitiesArrayHelper::check($headers))
		{
			// make sure task function headers are set
			self::$workerHEADER[$function] = $headers;
		}
	}

	/**
	 * Set a worker that needs to perform a task
	 * 
	 * @param  mixed    $data        The data to pass to the task
	 * @param  string   $function    The function to target to perform the task
	 * @param  string   $url         The url of where the task is to be performed
	 * @param  array    $headers     The headers needed for these workers/function
	 *
	 * @return  void
	 * @since   3.1
	 */
	public static function setWorker($data, string $function, ?string $url = null, ?array $headers = null)
	{
		// make sure task function is up
		if (!isset(self::$worker[$function]))
		{
			self::$worker[$function] = [];
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
	 * Run set Workers
	 *
	 * @param  string      $function    The function to target to perform the task
	 * @param  string      $perTask    The amount of task per worker
	 * @param  function    $callback   The option to do a call back when task is completed
	 * @param  int         $threadSize   The size of the thread
	 *
	 * @return  bool true   On success
	 * @since   3.1
	 */
	public static function runWorker(string $function, $perTask = 50, $callback = null, $threadSize = 20): bool
	{
		// set task
		$task = self::lock($function);
		// build headers
		$headers = array('VDM-TASK: ' .$task);
		// build dynamic headers
		if (isset(self::$workerHEADER[$function]) && UtilitiesArrayHelper::check(self::$workerHEADER[$function]))
		{
			foreach (self::$workerHEADER[$function] as $header)
			{
				$headers[] = $header;
			}
		}
		// build worker options
		$options = array();
		// make sure worker is up
		if (isset(self::$worker[$function]) && UtilitiesArrayHelper::check(self::$worker[$function]))
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
		if (UtilitiesArrayHelper::check($options))
		{
			if (isset(self::$workerURL[$function]))
			{
				$url = self::$workerURL[$function];
			}
			else
			{
				$url = Uri::root() . '/index.php?option=com_sermondistributor&task=api.worker';
			}
			return self::curlMultiExec($url, $options, $callback, $threadSize);
		}
		return false;
	}

	/**
	 *	Do a multi curl execution of tasks
	 *
	 * @param  string      $url               The url of where the task is to be performed
	 * @param  array       $_options      The array of curl options/headers to set
	 * @param  function   $callback      The option to do a call back when task is completed
	 * @param  int           $threadSize   The size of the thread
	 *
	 * @return  bool true   On success
	 * @since   3.1
	 */
	public static function curlMultiExec(&$url, &$_options, $callback = null, $threadSize = 20)
	{
		// make sure we have curl available
		if (!function_exists('curl_version'))
		{
			if (!self::$curlErrorLoaded)
			{
				// set the notice
				Factory::getApplication()->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_HTWOCURL_NOT_FOUNDHTWOPPLEASE_SETUP_CURL_ON_YOUR_SYSTEM_OR_BSERMONDISTRIBUTORB_WILL_NOT_FUNCTION_CORRECTLYP'), 'Error');
				// load the notice only once
				self::$curlErrorLoaded = true;
			}
			return false;
		}
		// make sure we have an url
		if (UtilitiesStringHelper::check($url))
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
	 * the locker
	 *
	 * @var array
	 * @since   3.1
	 */
	protected static array $locker = [];

	/**
	 * the dynamic replacement salt
	 *
	 * @var array
	 * @since   3.1
	 */
	protected static array $globalSalt = [];

	/**
	 * the timer
	 *
	 * @var object
	 * @since   3.1
	 */
	protected static $keytimer;

	/**
	 * To Lock string
	 *
	 * @param string       $string     The string/array to lock
	 * @param string|null  $key        The custom key to use
	 * @param int          $salt       The switch to add salt and type of salt
	 * @param int|null     $dynamic    The dynamic replacement array of salt build string
	 * @param int          $urlencode  The switch to control url encoding
	 *
	 * @return string    Encrypted String
	 * @since   3.1
	 */
	public static function lock(string $string, ?string $key = null, int $salt = 2, ?int $dynamic = null, $urlencode = true): string
	{
		// get the global settings
		if (!$key || !UtilitiesStringHelper::check($key))
		{
			// set temp timer
			$timer = 2;
			// if we have a timer use it
			if ($salt > 0)
			{
				$timer = $salt;
			}
			// set the default key
			$key = self::salt($timer, $dynamic);
			// try getting the system key
			if (method_exists(get_called_class(), "getCryptKey")) 
			{
				// try getting the medium key first the fall back to basic, and then default
				$key = self::getCryptKey('medium', self::getCryptKey('basic', $key));
			}
		}
		// check if we have a salt timer
		if ($salt > 0)
		{
			$key .= self::salt($salt, $dynamic);
		}
		// get the locker settings
		if (!isset(self::$locker[$key]) || !ObjectHelper::check(self::$locker[$key]))
		{
			self::$locker[$key] = new AES($key, 128);
		}
		// convert array or object to string
		if (UtilitiesArrayHelper::check($string) || ObjectHelper::check($string))
		{
			$string = serialize($string);
		}
		// prep for url
		if ($urlencode && method_exists(get_called_class(), "base64_urlencode"))
		{
			return self::base64_urlencode(self::$locker[$key]->encryptString($string));
		}
		return self::$locker[$key]->encryptString($string);
	}

	/**
	 * To un-Lock string
	 *
	 * @param string  $string       The string to unlock
	 * @param string  $key          The custom key to use
	 * @param int      $salt           The switch to add salt and type of salt
	 * @param int      $dynamic    The dynamic replacement array of salt build string
	 * @param int      $urlencode  The switch to control url decoding
	 *
	 * @return string    Decrypted String
	 * @since   3.1
	 */
	public static function unlock($string, $key = null, $salt = 2, $dynamic = null, $urlencode = true): string
	{
		// get the global settings
		if (!$key || !UtilitiesStringHelper::check($key))
		{
			// set temp timer
			$timer = 2;
			// if we have a timer use it
			if ($salt > 0)
			{
				$timer = $salt;
			}
			// set the default key
			$key = self::salt($timer, $dynamic);
			// try getting the system key
			if (method_exists(get_called_class(), "getCryptKey")) 
			{
				// try getting the medium key first the fall back to basic, and then default
				$key = self::getCryptKey('medium', self::getCryptKey('basic', $key));
			}
		}
		// check if we have a salt timer
		if ($salt > 0)
		{
			$key .= self::salt($salt, $dynamic);
		}
		// get the locker settings
		if (!isset(self::$locker[$key]) || !ObjectHelper::check(self::$locker[$key]))
		{
			self::$locker[$key] = new AES($key, 128);
		}
		// make sure we have real base64
		if ($urlencode && method_exists(get_called_class(), "base64_urldecode"))
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
	 * The Salt
	 *
	 * @param int   $type      The type of length the salt should be valid
	 * @param int   $dynamic   The dynamic replacement array of salt build string
	 *
	 * @return string
	 * @since   3.1
	 */
	public static function salt(int $type = 1, $dynamic = null): string
	{
		// get dynamic replacement salt
		$dynamic = self::getDynamicSalt($dynamic);
		// get the key timer
		if (!ObjectHelper::check(self::$keytimer))
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
		if (UtilitiesArrayHelper::check($dynamic))
		{
			return md5(str_replace(array_keys($dynamic), array_values($dynamic), self::$keytimer->format($format) . ' @ VDM.I0'));
		}
		return md5(self::$keytimer->format($format) . ' @ VDM.I0');
	}

	/**
	 * The function to insure the salt is valid within the given period (third try)
	 *
	 * @param   int $main    The main number
	 * @since   3.1
	 */
	protected static function periodFix(int $main): int
	{
		return round($main / 3) * 3;
	}

	/**
	 * Check if a string is serialized
	 *
	 * @param  string   $string
	 *
	 * @return Boolean
	 * @since   3.1
	 */
	public static function is_serial(string $string): bool
	{
		return (@unserialize($string) !== false);
	}

	/**
	 * Get dynamic replacement salt
	 * @since   3.1
	 */
	public static function getDynamicSalt($dynamic = null)
	{
		// load global if not manually set
		if (!UtilitiesArrayHelper::check($dynamic))
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
	 * The random or dynamic secret salt
	 * @since   3.1
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
		$string = UtilitiesStringHelper::safe($string);
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
	 * Get global replacement salt
	 * @since   3.1
	 */
	public static function getGlobalSalt()
	{
		// load from memory if found
		if (!UtilitiesArrayHelper::check(self::$globalSalt))
		{
			// get the global settings
			if (!ObjectHelper::check(self::$params))
			{
				self::$params = ComponentHelper::getParams('com_sermondistributor');
			}
			// check if we have a global dynamic replacement array available (format -->  ' 1->!,3->E,4->A')
			$tmp = self::$params->get('dynamic_salt', null);
			if (UtilitiesStringHelper::check($tmp) && strpos($tmp, ',') !== false && strpos($tmp, '->') !== false)
			{
				$salt = array_map('trim', (array) explode(',', $tmp));
				if (UtilitiesArrayHelper::check($salt ))
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
		if (UtilitiesArrayHelper::check(self::$globalSalt))
		{
			return self::$globalSalt;
		}
		// return default as fail safe
		return array('1' => '!', '3' => 'E', '4' => 'A');	
	}

	/**
	 * Close public protocol
	 * @since   3.1
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
	 * Open public protocol
	 * @since   3.1
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

	/**
	 * Load the Composer Vendors
	 */
	public static function composerAutoload($target)
	{
		// insure we load the composer vendor only once
		if (!isset(self::$composer[$target]))
		{
			// get the function name
			$functionName = UtilitiesStringHelper::safe('compose' . $target);
			// check if method exist
			if (method_exists(__CLASS__, $functionName))
			{
				return self::{$functionName}();
			}
			return false;
		}
		return self::$composer[$target];
	}

	/**
	 * Load the Component xml manifest.
	 */
	public static function manifest()
	{
		$manifestUrl = JPATH_ADMINISTRATOR."/components/com_sermondistributor/sermondistributor.xml";
		return simplexml_load_file($manifestUrl);
	}

	/**
	 * Joomla version object
	 */
	protected static $JVersion;

	/**
	 * set/get Joomla version
	 */
	public static function jVersion()
	{
		// check if set
		if (!ObjectHelper::check(self::$JVersion))
		{
			self::$JVersion = new Version();
		}
		return self::$JVersion;
	}

	/**
	 * Load the Contributors details.
	 */
	public static function getContributors()
	{
		// get params
		$params    = ComponentHelper::getParams('com_sermondistributor');
		// start contributors array
		$contributors = [];
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
				$contributors[$nr]['title']   = UtilitiesStringHelper::html($params->get("titleContributor".$nr));
				$contributors[$nr]['name']    = $link_front.UtilitiesStringHelper::html($params->get("nameContributor".$nr)).$link_back;
			}
		}
		return $contributors;
	}

	/**
	 *	Load the Component Help URLs.
	 **/
	public static function getHelpUrl($view)
	{
		$user	= Factory::getApplication()->getIdentity();
		$groups = $user->get('groups');
		$db	= Factory::getContainer()->get(DatabaseInterface::class);
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
			if (UtilitiesArrayHelper::check($helps))
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
		return Uri::root() . 'index.php?option=com_content&view=article&id='.$id.'&tmpl=component&layout=modal';
	}

	/**
	 *	Get the Help Text Link.
	 **/
	protected static function loadHelpTextLink($id)
	{
		$token = Session::getFormToken();
		return 'index.php?option=com_sermondistributor&task=help.getText&id=' . (int) $id . '&' . $token . '=1';
	}

	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu)
	{
		// load user for access menus
		$user = Factory::getApplication()->getIdentity();
		// load the submenus to sidebar
		\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_DASHBOARD'), 'index.php?option=com_sermondistributor&view=sermondistributor', $submenu === 'sermondistributor');
		if ($user->authorise('preacher.access', 'com_sermondistributor') && $user->authorise('preacher.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_PREACHERS'), 'index.php?option=com_sermondistributor&view=preachers', $submenu === 'preachers');
		}
		if ($user->authorise('sermon.access', 'com_sermondistributor') && $user->authorise('sermon.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_SERMONS'), 'index.php?option=com_sermondistributor&view=sermons', $submenu === 'sermons');
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SERMON_SERMONS_CATEGORIES'), 'index.php?option=com_categories&view=categories&extension=com_sermondistributor.sermon', $submenu === 'categories.sermon');
		}
		if ($user->authorise('series.access', 'com_sermondistributor') && $user->authorise('series.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_SERIES_LIST'), 'index.php?option=com_sermondistributor&view=series_list', $submenu === 'series_list');
		}
		if ($user->authorise('statistic.access', 'com_sermondistributor') && $user->authorise('statistic.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_STATISTICS'), 'index.php?option=com_sermondistributor&view=statistics', $submenu === 'statistics');
		}
		if ($user->authorise('external_source.access', 'com_sermondistributor') && $user->authorise('external_source.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_EXTERNAL_SOURCES'), 'index.php?option=com_sermondistributor&view=external_sources', $submenu === 'external_sources');
		}
		// Access control (manual_updater.access && manual_updater.submenu).
		if ($user->authorise('manual_updater.access', 'com_sermondistributor') && $user->authorise('manual_updater.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_MANUAL_UPDATER'), 'index.php?option=com_sermondistributor&view=manual_updater', $submenu === 'manual_updater');
		}
		if ($user->authorise('local_listing.access', 'com_sermondistributor') && $user->authorise('local_listing.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_LOCAL_LISTINGS'), 'index.php?option=com_sermondistributor&view=local_listings', $submenu === 'local_listings');
		}
		if ($user->authorise('help_document.access', 'com_sermondistributor') && $user->authorise('help_document.submenu', 'com_sermondistributor'))
		{
			\JHtmlSidebar::addEntry(Text::_('COM_SERMONDISTRIBUTOR_SUBMENU_HELP_DOCUMENTS'), 'index.php?option=com_sermondistributor&view=help_documents', $submenu === 'help_documents');
		}
	}

	/**
	* Prepares the xml document
	*/
	public static function xls($rows, $fileName = null, $title = null, $subjectTab = null, $creator = 'Vast Development Method', $description = null, $category = null,$keywords = null, $modified = null)
	{
		// set the user
		// set fileName if not set
		if (!$fileName)
		{
			$fileName = 'exported_'.Factory::getDate()->format('jS_F_Y');
		}
		// set modified if not set
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

		// make sure we have the composer classes loaded
		self::composerAutoload('phpspreadsheet');

		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()
			->setCreator($creator)
			->setCompany('Vast Development Method')
			->setLastModifiedBy($modified)
			->setTitle($title)
			->setSubject($subjectTab);
		// The file type
		$file_type = 'Xls';
		// set description
		if ($description)
		{
			$spreadsheet->getProperties()->setDescription($description);
		}
		// set keywords
		if ($keywords)
		{
			$spreadsheet->getProperties()->setKeywords($keywords);
		}
		// set category
		if ($category)
		{
			$spreadsheet->getProperties()->setCategory($category);
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
		if (($size = UtilitiesArrayHelper::check($rows)) !== false)
		{
			$i = 1;

			// Based on data size we adapt the behaviour.
			$xls_mode = 1;
			if ($size > 3000)
			{
				$xls_mode = 3;
				$file_type = 'Csv';
			}
			elseif ($size > 2000)
			{
				$xls_mode = 2;
			}

			// Set active sheet and get it.
			$active_sheet = $spreadsheet->setActiveSheetIndex(0);
			foreach ($rows as $array)
			{
				$a = 'A';
				foreach ($array as $value)
				{
					$active_sheet->setCellValue($a.$i, $value);
					if ($xls_mode != 3)
					{
						if ($i == 1)
						{
							$active_sheet->getColumnDimension($a)->setAutoSize(true);
							$active_sheet->getStyle($a.$i)->applyFromArray($headerStyles);
							$active_sheet->getStyle($a.$i)->getAlignment()->setHorizontal(PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						}
						elseif ($a === 'A')
						{
							$active_sheet->getStyle($a.$i)->applyFromArray($sideStyles);
						}
						elseif ($xls_mode == 1)
						{
							$active_sheet->getStyle($a.$i)->applyFromArray($normalStyles);
						}
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
		$spreadsheet->getActiveSheet()->setTitle($subjectTab);

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $fileName . '.' . strtolower($file_type) .'"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, $file_type);
		$writer->save('php://output');
		jexit();
	}

	/**
	* Get CSV Headers
	*/
	public static function getFileHeaders($dataType)
	{
		// make sure we have the composer classes loaded
		self::composerAutoload('phpspreadsheet');
		// get session object
		$session = Factory::getSession();
		$package = $session->get('package', null);
		$package = json_decode($package, true);
		// set the headers
		if(isset($package['dir']))
		{
			// only load first three rows
			$chunkFilter = new PhpOffice\PhpSpreadsheet\Reader\chunkReadFilter(2,1);
			// identify the file type
			$inputFileType = IOFactory::identify($package['dir']);
			// create the reader for this file type
			$excelReader = IOFactory::createReader($inputFileType);
			// load the limiting filter
			$excelReader->setReadFilter($chunkFilter);
			$excelReader->setReadDataOnly(true);
			// load the rows (only first three)
			$excelObj = $excelReader->load($package['dir']);
			$headers = [];
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

	/**
	* Load the Composer Vendor phpspreadsheet
	*/
	protected static function composephpspreadsheet()
	{
		// load the autoloader for phpspreadsheet
		require_once JPATH_SITE . '/libraries/phpspreadsheet/vendor/autoload.php';
		// do not load again
		self::$composer['phpspreadsheet'] = true;

		return  true;
	}

	/**
	 * Get a Variable
	 *
	 * @param   string   $table        The table from which to get the variable
	 * @param   string   $where        The value where
	 * @param   string   $whereString  The target/field string where/name
	 * @param   string   $what         The return field
	 * @param   string   $operator     The operator between $whereString/field and $where/value
	 * @param   string   $main         The component in which the table is found
	 *
	 * @return  mix string/int/float
	 * @deprecated 3.3 Use GetHelper::var(...);
	 */
	public static function getVar($table, $where = null, $whereString = 'user', $what = 'id', $operator = '=', $main = 'sermondistributor')
	{
		return GetHelper::var(
			$table,
			$where,
			$whereString,
			$what,
			$operator,
			$main
		);
	}

	/**
	 * Get array of variables
	 *
	 * @param   string   $table        The table from which to get the variables
	 * @param   string   $where        The value where
	 * @param   string   $whereString  The target/field string where/name
	 * @param   string   $what         The return field
	 * @param   string   $operator     The operator between $whereString/field and $where/value
	 * @param   string   $main         The component in which the table is found
	 * @param   bool     $unique       The switch to return a unique array
	 *
	 * @return  array
	 * @deprecated 3.3 Use GetHelper::vars(...);
	 */
	public static function getVars($table, $where = null, $whereString = 'user', $what = 'id', $operator = 'IN', $main = 'sermondistributor', $unique = true)
	{
		return GetHelper::vars(
			$table,
			$where,
			$whereString,
			$what,
			$operator,
			$main,
			$unique
		);
	}

	/**
	 * Convert a json object to a string
	 *
	 * @input    string  $value  The json string to convert
	 *
	 * @returns a string
	 * @deprecated 3.3 Use JsonHelper::string(...);
	 */
	public static function jsonToString($value, $sperator = ", ", $table = null, $id = 'id', $name = 'name')
	{
		return JsonHelper::string(
			$value,
			$sperator,
			$table,
			$id,
			$name
		);
	}

	public static function isPublished($id,$type)
	{
		if ($type == 'raw')
		{
			$type = 'item';
		}
		$db = Factory::getContainer()->get(DatabaseInterface::class);
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
		$db = Factory::getContainer()->get(DatabaseInterface::class);
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
	 * Get the action permissions
	 *
	 * @param  string   $view        The related view name
	 * @param  int      $record      The item to act upon
	 * @param  string   $views       The related list view name
	 * @param  mixed    $target      Only get this permission (like edit, create, delete)
	 * @param  string   $component   The target component
	 * @param  object   $user        The user whose permissions we are loading
	 *
	 * @return  object   The CMSObject of permission/authorised actions
	 *
	 */
	public static function getActions($view, &$record = null, $views = null, $target = null, $component = 'sermondistributor', $user = 'null')
	{
		// load the user if not given
		if (!ObjectHelper::check($user))
		{
			// get the user object
			$user = Factory::getApplication()->getIdentity();
		}
		// load the CMSObject
		$result = new CMSObject;
		// make view name safe (just incase)
		$view = UtilitiesStringHelper::safe($view);
		if (UtilitiesStringHelper::check($views))
		{
			$views = UtilitiesStringHelper::safe($views);
		 }
		// get all actions from component
		$actions = Access::getActionsFromFile(
			JPATH_ADMINISTRATOR . '/components/com_' . $component . '/access.xml',
			"/access/section[@name='component']/"
		);
		// if non found then return empty CMSObject
		if (empty($actions))
		{
			return $result;
		}
		// get created by if not found
		if (ObjectHelper::check($record) && !isset($record->created_by) && isset($record->id))
		{
			$record->created_by = GetHelper::var($view, $record->id, 'id', 'created_by', '=', $component);
		}
		// set actions only set in component settings
		$componentActions = array('core.admin', 'core.manage', 'core.options', 'core.export');
		// check if we have a target
		$checkTarget = false;
		if ($target)
		{
			// convert to an array
			if (UtilitiesStringHelper::check($target))
			{
				$target = array($target);
			}
			// check if we are good to go
			if (UtilitiesArrayHelper::check($target))
			{
				$checkTarget = true;
			}
		}
		// loop the actions and set the permissions
		foreach ($actions as $action)
		{
			// check target action filter
			if ($checkTarget && self::filterActions($view, $action->name, $target))
			{
				continue;
			}
			// set to use component default
			$fallback = true;
			// reset permission per/action
			$permission = false;
			$catpermission = false;
			// set area
			$area = 'comp';
			// check if the record has an ID and the action is item related (not a component action)
			if (ObjectHelper::check($record) && isset($record->id) && $record->id > 0 && !in_array($action->name, $componentActions) &&
				(strpos($action->name, 'core.') !== false || strpos($action->name, $view . '.') !== false))
			{
				// we are in item
				$area = 'item';
				// The record has been set. Check the record permissions.
				$permission = $user->authorise($action->name, 'com_' . $component . '.' . $view . '.' . (int) $record->id);
				// if no permission found, check edit own
				if (!$permission)
				{
					// With edit, if the created_by matches current user then dig deeper.
					if (($action->name === 'core.edit' || $action->name === $view . '.edit') && $record->created_by > 0 && ($record->created_by == $user->id))
					{
						// the correct target
						$coreCheck = (array) explode('.', $action->name);
						// check that we have both local and global access
						if ($user->authorise($coreCheck[0] . '.edit.own', 'com_' . $component . '.' . $view . '.' . (int) $record->id) &&
							$user->authorise($coreCheck[0]  . '.edit.own', 'com_' . $component))
						{
							// allow edit
							$result->set($action->name, true);
							// set not to use global default
							// because we already validated it
							$fallback = false;
						}
						else
						{
							// do not allow edit
							$result->set($action->name, false);
							$fallback = false;
						}
					}
				}
				elseif (UtilitiesStringHelper::check($views) && isset($record->catid) && $record->catid > 0)
				{
					// we are in item
					$area = 'category';
					// set the core check
					$coreCheck = explode('.', $action->name);
					$core = $coreCheck[0];
					// make sure we use the core. action check for the categories
					if (strpos($action->name, $view) !== false && strpos($action->name, 'core.') === false )
					{
						$coreCheck[0] = 'core';
						$categoryCheck = implode('.', $coreCheck);
					}
					else
					{
						$categoryCheck = $action->name;
					}
					// The record has a category. Check the category permissions.
					$catpermission = $user->authorise($categoryCheck, 'com_' . $component . '.' . $views . '.category.' . (int) $record->catid);
					if (!$catpermission && !is_null($catpermission))
					{
						// With edit, if the created_by matches current user then dig deeper.
						if (($action->name === 'core.edit' || $action->name === $view . '.edit') && $record->created_by > 0 && ($record->created_by == $user->id))
						{
							// check that we have both local and global access
							if ($user->authorise('core.edit.own', 'com_' . $component . '.' . $views . '.category.' . (int) $record->catid) &&
								$user->authorise($core . '.edit.own', 'com_' . $component))
							{
								// allow edit
								$result->set($action->name, true);
								// set not to use global default
								// because we already validated it
								$fallback = false;
							}
							else
							{
								// do not allow edit
								$result->set($action->name, false);
								$fallback = false;
							}
						}
					}
				}
			}
			// if allowed then fallback on component global settings
			if ($fallback)
			{
				// if item/category blocks access then don't fall back on global
				if ((($area === 'item') && !$permission) || (($area === 'category') && !$catpermission))
				{
					// do not allow
					$result->set($action->name, false);
				}
				// Finally remember the global settings have the final say. (even if item allow)
				// The local item permissions can block, but it can't open and override of global permissions.
				// Since items are created by users and global permissions is set by system admin.
				else
				{
					$result->set($action->name, $user->authorise($action->name, 'com_' . $component));
				}
			}
		}
		return $result;
	}

	/**
	 * Filter the action permissions
	 *
	 * @param  string   $action   The action to check
	 * @param  array    $targets  The array of target actions
	 *
	 * @return  boolean   true if action should be filtered out
	 *
	 */
	protected static function filterActions(&$view, &$action, &$targets)
	{
		foreach ($targets as $target)
		{
			if (strpos($action, $view . '.' . $target) !== false ||
				strpos($action, 'core.' . $target) !== false)
			{
				return false;
				break;
			}
		}
		return true;
	}

	/**
	 * Returns any Model object.
	 *
	 * @param   string  $type       The model type to instantiate
	 * @param   string  $prefix     Prefix for the model class name. Optional.
	 * @param   string  $component  Component name the model belongs to. Optional.
	 * @param   array   $config     Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel
	 * @throws \Exception
	 * @since   4.4
	 */
	public static function getModel(string $type, string $prefix = 'Administrator',
		string $component = 'sermondistributor', array $config = [])
	{
		// make sure the name is correct
		$type = UtilitiesStringHelper::safe($type, 'F');
		$component = strtolower($component);

		if ($prefix !== 'Site' && $prefix !== 'Administrator')
		{
			$prefix = self::getPrefixFromModelPath($prefix);
		}

		// Get the model through the MVCFactory
		return Factory::getApplication()->bootComponent('com_' . $component)->getMVCFactory()->createModel($type, $prefix, $config);
	}

	/**
	 * Get the prefix from the model path
	 *
	 * @param   string  $path    The model path
	 *
	 * @return  string  The prefix value
	 * @since    4.4
	 */
	protected static function getPrefixFromModelPath(string $path): string
	{
		// Check if $path starts with JPATH_ADMINISTRATOR path
		if (str_starts_with($path, JPATH_ADMINISTRATOR . '/components/'))
		{
			return 'Administrator';
		}
		// Check if $path starts with JPATH_SITE path
		elseif (str_starts_with($path, JPATH_SITE . '/components/'))
		{
			return 'Site';
		}

		return 'Administrator';
	}

	/**
	 * Add to asset Table
	 */
	public static function setAsset($id, $table, $inherit = true)
	{
		$parent = Table::getInstance('Asset');
		$parent->loadByName('com_sermondistributor');

		$parentId = $parent->id;
		$name     = 'com_sermondistributor.'.$table.'.'.$id;
		$title    = '';

		$asset = Table::getInstance('Asset');
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
			$rules = self::getDefaultAssetRules('com_sermondistributor', $table, $inherit);
			if ($rules instanceof AccessRules)
			{
				$asset->rules = (string) $rules;
			}

			if (!$asset->check() || !$asset->store())
			{
				Factory::getApplication()->enqueueMessage($asset->getError(), 'warning');
				return false;
			}
			else
			{
				// Create an asset_id or heal one that is corrupted.
				$object = new \StdClass();

				// Must be a valid primary key value.
				$object->id = $id;
				$object->asset_id = (int) $asset->id;

				// Update their asset_id to link to the asset table.
				return Factory::getDbo()->updateObject('#__sermondistributor_'.$table, $object, 'id');
			}
		}
		return false;
	}

	/**
	 * Gets the default asset Rules for a component/view.
	 */
	protected static function getDefaultAssetRules($component, $view, $inherit = true)
	{
		// if new or inherited
		$assetId = 0;
		// Only get the actual item rules if not inheriting
		if (!$inherit)
		{
			// Need to find the asset id by the name of the component.
			$db = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true)
				->select($db->quoteName('id'))
				->from($db->quoteName('#__assets'))
				->where($db->quoteName('name') . ' = ' . $db->quote($component));
			$db->setQuery($query);
			$db->execute();
			// check that there is a value
			if ($db->getNumRows())
			{
				// asset already set so use saved rules
				$assetId = (int) $db->loadResult();
			}
		}
		// get asset rules
		$result =  Access::getAssetRules($assetId);
		if ($result instanceof AccessRules)
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
				elseif ($inherit)
				{
					// clear the value since we inherit
					$rule = [];
				}
			}
			// check if there are any view values remaining
			if (count((array) $_result))
			{
				$_result = json_encode($_result);
				$_result = array($_result);
				// Instantiate and return the AccessRules object for the asset rules.
				$rules = new AccessRules($_result);
				// return filtered rules
				return $rules;
			}
		}
		return $result;
	}

	/**
	 * xmlAppend
	 *
	 * @param   SimpleXMLElement   $xml      The XML element reference in which to inject a comment
	 * @param   mixed              $node     A SimpleXMLElement node to append to the XML element reference, or a stdClass object containing a comment attribute to be injected before the XML node and a fieldXML attribute containing a SimpleXMLElement
	 *
	 * @return  void
	 * @deprecated 3.3 Use FormHelper::append($xml, $node);
	 */
	public static function xmlAppend(&$xml, $node)
	{
		FormHelper::append($xml, $node);
	}

	/**
	 * xmlComment
	 *
	 * @param   SimpleXMLElement   $xml        The XML element reference in which to inject a comment
	 * @param   string             $comment    The comment to inject
	 *
	 * @return  void
	 * @deprecated 3.3 Use FormHelper::comment($xml, $comment);
	 */
	public static function xmlComment(&$xml, $comment)
	{
		FormHelper::comment($xml, $comment);
	}

	/**
	 * xmlAddAttributes
	 *
	 * @param   SimpleXMLElement   $xml          The XML element reference in which to inject a comment
	 * @param   array              $attributes   The attributes to apply to the XML element
	 *
	 * @return  null
	 * @deprecated 3.3 Use FormHelper::attributes($xml, $attributes);
	 */
	public static function xmlAddAttributes(&$xml, $attributes = [])
	{
		FormHelper::attributes($xml, $attributes);
	}

	/**
	 * xmlAddOptions
	 *
	 * @param   SimpleXMLElement   $xml          The XML element reference in which to inject a comment
	 * @param   array              $options      The options to apply to the XML element
	 *
	 * @return  void
	 * @deprecated 3.3 Use FormHelper::options($xml, $options);
	 */
	public static function xmlAddOptions(&$xml, $options = [])
	{
		FormHelper::options($xml, $options);
	}

	/**
	 * get the field object
	 *
	 * @param   array      $attributes   The array of attributes
	 * @param   string     $default      The default of the field
	 * @param   array      $options      The options to apply to the XML element
	 *
	 * @return  object
	 * @deprecated 3.3 Use FormHelper::field($attributes, $default, $options);
	 */
	public static function getFieldObject(&$attributes, $default = '', $options = null)
	{
		return FormHelper::field($attributes, $default, $options);
	}

	/**
	 * get the field xml
	 *
	 * @param   array      $attributes   The array of attributes
	 * @param   array      $options      The options to apply to the XML element
	 *
	 * @return  object
	 * @deprecated 3.3 Use FormHelper::xml($attributes, $options);
	 */
	public static function getFieldXML(&$attributes, $options = null)
	{
		return FormHelper::xml($attributes, $options);
	}

	/**
	 * Render Bool Button
	 *
	 * @param   array   $args   All the args for the button
	 *                             0) name
	 *                             1) additional (options class) // not used at this time
	 *                             2) default
	 *                             3) yes (name)
	 *                             4) no (name)
	 *
	 * @return  string    The input html of the button
	 *
	 */
	public static function renderBoolButton()
	{
		$args = func_get_args();
		// check if there is additional button class
		$additional = isset($args[1]) ? (string) $args[1] : ''; // not used at this time
		// button attributes
		$buttonAttributes = array(
			'type' => 'radio',
			'name' => isset($args[0]) ? UtilitiesStringHelper::html($args[0]) : 'bool_button',
			'label' => isset($args[0]) ? UtilitiesStringHelper::safe(UtilitiesStringHelper::html($args[0]), 'Ww') : 'Bool Button', // not seen anyway
			'class' => 'btn-group',
			'filter' => 'INT',
			'default' => isset($args[2]) ? (int) $args[2] : 0);
		// set the button options
		$buttonOptions = array(
			'1' => isset($args[3]) ? UtilitiesStringHelper::html($args[3]) : 'JYES',
			'0' => isset($args[4]) ? UtilitiesStringHelper::html($args[4]) : 'JNO');
		// return the input
		return FormHelper::field($buttonAttributes, $buttonAttributes['default'], $buttonOptions)->input;
	}

	/**
	 * Check if have an json string
	 *
	 * @input    string   The json string to check
	 *
	 * @returns bool true on success
	 * @deprecated 3.3 Use JsonHelper::check($string);
	 */
	public static function checkJson($string)
	{
		return JsonHelper::check($string);
	}

	/**
	 * Check if have an object with a length
	 *
	 * @input    object   The object to check
	 *
	 * @returns bool true on success
	 * @deprecated 3.3 Use ObjectHelper::check($object);
	 */
	public static function checkObject($object)
	{
		return ObjectHelper::check($object);
	}

	/**
	 * Check if have an array with a length
	 *
	 * @input    array   The array to check
	 *
	 * @returns bool/int  number of items in array on success
	 * @deprecated 3.3 Use UtilitiesArrayHelper::check($array, $removeEmptyString);
	 */
	public static function checkArray($array, $removeEmptyString = false)
	{
		return UtilitiesArrayHelper::check($array, $removeEmptyString);
	}

	/**
	 * Check if have a string with a length
	 *
	 * @input    string   The string to check
	 *
	 * @returns bool true on success
	 * @deprecated 3.3 Use UtilitiesStringHelper::check($string);
	 */
	public static function checkString($string)
	{
		return UtilitiesStringHelper::check($string);
	}

	/**
	 * Check if we are connected
	 * Thanks https://stackoverflow.com/a/4860432/1429677
	 *
	 * @returns bool true on success
	 */
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

	/**
	 * Merge an array of array's
	 *
	 * @input    array   The arrays you would like to merge
	 *
	 * @returns array on success
	 * @deprecated 3.3 Use UtilitiesArrayHelper::merge($arrays);
	 */
	public static function mergeArrays($arrays)
	{
		return UtilitiesArrayHelper::merge($arrays);
	}

	// typo sorry!
	public static function sorten($string, $length = 40, $addTip = true)
	{
		return self::shorten($string, $length, $addTip);
	}

	/**
	 * Shorten a string
	 *
	 * @input    string   The you would like to shorten
	 *
	 * @returns string on success
	 * @deprecated 3.3 Use UtilitiesStringHelper::shorten(...);
	 */
	public static function shorten($string, $length = 40, $addTip = true)
	{
		return UtilitiesStringHelper::shorten($string, $length, $addTip);
	}

	/**
	 * Making strings safe (various ways)
	 *
	 * @input    string   The you would like to make safe
	 *
	 * @returns string on success
	 * @deprecated 3.3 Use UtilitiesStringHelper::safe(...);
	 */
	public static function safeString($string, $type = 'L', $spacer = '_', $replaceNumbers = true, $keepOnlyCharacters = true)
	{
		return UtilitiesStringHelper::safe(
			$string,
			$type,
			$spacer,
			$replaceNumbers,
			$keepOnlyCharacters
		);
	}

	/**
	 * Convert none English strings to code usable string
	 *
	 * @input    an string
	 *
	 * @returns a string
	 * @deprecated 3.3 Use UtilitiesStringHelper::transliterate($string);
	 */
	public static function transliterate($string)
	{
		return UtilitiesStringHelper::transliterate($string);
	}

	/**
	 * make sure a string is HTML save
	 *
	 * @input    an html string
	 *
	 * @returns a string
	 * @deprecated 3.3 Use UtilitiesStringHelper::html(...);
	 */
	public static function htmlEscape($var, $charset = 'UTF-8', $shorten = false, $length = 40)
	{
		return UtilitiesStringHelper::html(
			$var,
			$charset,
			$shorten,
			$length
		);
	}

	/**
	 * Convert all int in a string to an English word string
	 *
	 * @input    an string with numbers
	 *
	 * @returns a string
	 * @deprecated 3.3 Use UtilitiesStringHelper::numbers($string);
	 */
	public static function replaceNumbers($string)
	{
		return UtilitiesStringHelper::numbers($string);
	}

	/**
	 * Convert an integer into an English word string
	 * Thanks to Tom Nicholson <http://php.net/manual/en/function.strval.php#41988>
	 *
	 * @input    an int
	 * @returns a string
	 * @deprecated 3.3 Use UtilitiesStringHelper::number($x);
	 */
	public static function numberToString($x)
	{
		return UtilitiesStringHelper::number($x);
	}

	/**
	 * Random Key
	 *
	 * @returns a string
	 * @deprecated 3.3 Use UtilitiesStringHelper::random($size);
	 */
	public static function randomkey($size)
	{
		return UtilitiesStringHelper::random($size);
	}

	/**
	 *	Get The Encryption Keys
	 *
	 *	@param  string        $type     The type of key
	 *	@param  string/bool   $default  The return value if no key was found
	 *
	 *	@return  string   On success
	 *
	 **/
	public static function getCryptKey($type, $default = false)
	{
		// Get the global params
		$params = ComponentHelper::getParams('com_sermondistributor', true);
		// Basic Encryption Type
		if ('basic' === $type)
		{
			$basic_key = $params->get('basic_key', $default);
			if (UtilitiesStringHelper::check($basic_key))
			{
				return $basic_key;
			}
		}

		return $default;
	}
}
