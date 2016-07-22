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

	@version		1.3.4
	@build			17th July, 2016
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
	*	The Global Site Event Method.
	**/
	public static function globalEvent($document)
	{
		self::loadDropboxAjax($document);
	} 

	/**
	* 	Load the Dropbox Ajax to page
	**/
	public static function loadDropboxAjax($document)
	{
		$document->addScriptDeclaration("
			jQuery(window).load(function() {
				checkDropboxListing(3);
			});
			
			function checkDropboxListing(fromview){
				var getUrl = '".JURI::root()."index.php?option=com_sermondistributor&task=ajax.checkDropboxListing&format=json';
				if(fromview > 0){
					var request = 'token=".JSession::getFormToken()."&fromview='+fromview;
				}
				return jQuery.ajax({
					type: 'GET',
					url: getUrl,
					dataType: 'jsonp',
					data: request,
					jsonp: 'callback'
				});
			}
		");
	}

	/**
	* 	The dropbox links auto
	**/
	protected static $links_dropbox_auto;
	
	/**
	* 	The dropbox links manual
	**/
	protected static $links_dropbox_manual;

	/**
	* 	The dropbox info File Name
	**/
	protected static $autoprogress = false;

	/**
	* 	The dropbox info File Name
	**/
	protected static $manualprogress = false;

	/**
	* 	The dropbox info File Name
	**/
	protected static $autolisting = false;

	/**
	* 	The dropbox info File Name
	**/
	protected static $manuallisting = false;

	/**
	* 	check Dropbox Local Listing
	**/
	public static function getDownloadLinks(&$sermon)
	{
		$links = array();
		// Get local key
		$localkey = self::getLocalKey();
		// decrypt the urls
		$safe = new FOFEncryptAes($localkey, 256);
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
		$keyCounter = base64_encode($safe->encryptString(json_encode($keyCounter)));
		$token = JSession::getFormToken();
		$downloadURL = JURI::root().'index.php?option=com_sermondistributor&task=download.file&key='.$keyCounter.'&token='.$token;
		// check if local .htaccess should be set
		$setHtaccess = false;
		$onclick = ' onclick="sermonCounter(\''.$keyCounter.'\',\'FILENAME\');"';
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
							$lockedFolderPath = base64_encode($safe->encryptString($localFolder.$key));
							$sermon->download_links[$filename] = $downloadURL.'&link='.$lockedFolderPath.'&filename='.$filename;
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
				$dropboxButton = JComponentHelper::getParams('com_sermondistributor')->get('add_to_dropbox', 1);
				if (1 == $sermon->build)
				{
					if (self::checkArray($sermon->manual_files))
					{
						// manual dropbox
						foreach($sermon->manual_files as $key)
						{
							// get the link
							$dropURL = self::getDropboxLink('manual',1,$key);
							if (1 == $sermon->link_type && $dropURL)
							{
								// get the file name use the same method as the auto
								$filename = self::getDownloadFileName($sermon,$key,'dropbox_manual');
								$sermon->download_links[$filename] = $downloadURL.'&link='.$dropURL.'&filename='.$filename;
								$sermon->onclick[$filename] = '';
							}
							elseif (2 == $sermon->link_type && $dropURL)
							{
								$filename = str_replace('VDM_pLeK_h0uEr/', '', $key);
								$sermon->download_links[$filename] = rtrim($safe->decryptString(base64_decode($dropURL)));
								$sermon->onclick[$filename] = str_replace('FILENAME', $filename, $onclick);
							}
							// build dropbox switch if needed
							if (1 == $dropboxButton && $dropURL)
							{
								$sermon->dropbox_buttons[$filename] = str_replace('?dl=1', '?dl=0', rtrim($safe->decryptString(base64_decode($dropURL))));
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
							$dropURL = self::getDropboxLink('auto',1,$key);
							if (1 == $sermon->link_type && $dropURL)
							{
								// get the file name (use the same method as the auto
								$sermon->download_links[$filename] = $downloadURL.'&link='.$dropURL.'&filename='.$filename;
								$sermon->onclick[$filename] = '';
							}
							elseif (2 == $sermon->link_type && $dropURL)
							{
								$sermon->download_links[$filename] = rtrim($safe->decryptString(base64_decode($dropURL)));
								$sermon->onclick[$filename] = str_replace('FILENAME', $filename, $onclick);
							}
							// build dropbox switch if needed
							if (1 == $dropboxButton && $dropURL)
							{
								$sermon->dropbox_buttons[$filename] = str_replace('?dl=1', '?dl=0', rtrim($safe->decryptString(base64_decode($dropURL))));
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
					$lockedURL = base64_encode($safe->encryptString($sermon->url));
					$sermon->download_links[$filename] = $downloadURL.'&link='.$lockedURL.'&filename='.$filename;
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
	
	public static function getDropboxLink($type, $return = false, $target = false)
	{
		// make sure all defaults are set
		$found = self::checkDropboxLocalListing($type);
		if ($found)
		{
			switch($return)
			{
				case 1:
					// return a link
					if (isset(self::${'links_dropbox_'.$type}[$target]))
					{
						return self::${'links_dropbox_'.$type}[$target];
					}
					break;
				case 2:
					// return all links
					return self::${'links_dropbox_'.$type};
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
		$opener = new FOFEncryptAes($localkey, 256);
		$counter = json_decode(rtrim($opener->decryptString(base64_decode($counter))));
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
	* 	check Dropbox Local Listing (do we have the files)
	**/
	public static function checkDropboxLocalListing($type)
	{
		$filePath = self::getFilePath($type);
		// get the local links
		if (self::checkArray(self::${'links_dropbox_'.$type}))
		{
			// return true we have links loaded
			return true;
		}
		elseif (($jsonlinks = @file_get_contents($filePath)) !== FALSE)
		{			
			if (self::checkString($jsonlinks))
			{
				// get saved links
				$storeage = json_decode($jsonlinks,true);
				if (self::checkArray($storeage))
				{
					// load the saved links
					self::${'links_dropbox_'.$type} = $storeage;
					return true;
				}
			}
		}
		return false;
	}
	
	protected static function getFilePath($type, $name = 'listing')
	{
		if (!self::checkString(self::${$type.$name}))
		{
			// Get local key
			$localkey = self::getLocalKey();
			// set the name
			$fileName = md5($type.$name.$localkey);
			// set file path			
			self::${$type.$name} = JPATH_COMPONENT_SITE.'/helpers/'.$fileName.'.json';
		}
		// return the path
		return self::${$type.$name};
	}

	/**
	* 	get the localkey
	**/
	protected static $localkey = false;
	
	public static function getLocalKey()
	{
		if (!self::$localkey)
		{
			// get the main key
			self::$localkey = md5(JComponentHelper::getParams('com_sermondistributor')->get('link_encryption', 'localKey34fdWEkl'));
		}
		return self::$localkey;
	}

	public static function updateDropbox($type = false, $force = false)
	{
		// load the file
		JLoader::import('dropboxupdater', JPATH_COMPONENT_SITE.'/helpers');
		// update types
		$types = array('manual','auto');
		// okay now update this type
		if (self::checkString($type) && in_array($type,$types))
		{
			$dropbox = new Dropboxupdater();
			return $dropbox->update($type,$force);
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
	public static function getModel($name, $path = JPATH_COMPONENT_SITE, $component = 'sermondistributor')
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
				if (!$permission && !is_null($permission))
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

	public static function checkArray($array)
	{
		if (isset($array) && is_array($array) && count($array) > 0)
		{
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
		// remove all numbers and replace with english text version (works well only up to a thousand)
		$string = self::replaceNumbers($string);

		if (self::checkString($string))
		{
			// remove all other characters
			$string = trim($string);
			$string = preg_replace('/'.$spacer.'+/', ' ', $string);
			$string = preg_replace('/\s+/', ' ', $string);
			$string = preg_replace("/[^A-Za-z ]/", '', $string);
			// return a string with all first letter of each word uppercase(no undersocre)
			if ($type == 'W')
				    {
			    return ucwords(strtolower($string));
			}
				    elseif ($type == 'w')
				    {
			    return strtolower($string);
			}
				    elseif ($type == 'Ww')
				    {
			    return ucfirst(strtolower($string));
			}
				    elseif ($type == 'WW')
				    {
			    return strtoupper($string);
			}
			elseif ($type == 'U')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// return all upper
				return strtoupper($string);
			}
			elseif ($type == 'F')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// return with first caracter to upper
				return ucfirst(strtolower($string));
			}
			elseif ($type == 'L')
			{
				// replace white space with underscore
				$string = preg_replace('/\s+/', $spacer, $string);
				// default is to return lower
				    return strtolower($string);
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
}
