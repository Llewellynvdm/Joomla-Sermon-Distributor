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

	@version		1.3.0
	@build			21st February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		dropboxupdater.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Dropbox updater component helper
 */
class Dropboxupdater
{
	/**
	* 	to see where we are in the prosses
	**/
	public $progress = array('report' => 0);
	
	/**
	* 	update flag (if false update will not happen)
	**/
	protected $okay = true;
	
	/**
	* 	the type of linksto update
	**/
	protected $type;
	
	/**
	* 	allow a forced manual update
	**/
	protected $runManual;
	
	/**
	* 	Todays date-time
	**/
	protected $today;
	
	/**
	* 	Next updat date-time
	**/
	protected $next;
	
	/**
	* 	update method
	**/
	protected $updateMethod;
	
	/**
	* 	update links
	**/
	protected $updateLinks = array();
	
	/**
	* 	Listing of dropbox links
	**/
	protected $listing = array();

	/**
	* 	info related to this update
	**/
	protected $updateInfo;
	protected $infoFilePath;
	
	/**
	* 	get the localkey
	**/
	protected $localkey = false;
	
	/**
	* 	component parameters
	**/
	protected $app_params;

	/**
	*	everything we want done when initialized
	**/
	public function __construct()
	{
		// get params
		$this->app_params = JComponentHelper::getParams('com_sermondistributor');

		// set local key
		$this->localkey = md5($this->app_params->get('link_encryption', 'localKey34fdWEkl'));
	}

	/**
	*	update mehtod
	**/
	public function update($type = false, $runManual = false)
	{
		if ($type)
		{
			// start frech
			$this->okay = true;
			// set type
			$this->type = $type;
			$this->runManual = $runManual;
			
			// set progress file name
			$this->progressFilePath = JPATH_COMPONENT_SITE.'/helpers/'.md5($this->type.'progress'.$this->localkey).'.json';
	
			// what update method is set
			$this->setUpdateMethod();

			// set the update links
			$this->setUpdateLinks();

			// set needed dates
			if ($this->okay)
			{
				$this->setDates();
			}

			// get info data or set if not found
			if ($this->okay)
			{
				$this->setInfoData();
			}

			// check if update should run now
			if ($this->okay)
			{
				$this->checkUpdateStatus();
			}
			// set progress
			if ($this->okay)
			{
				$this->saveProgress();
			}
			
			// before update save update info incase class is called again
			if($this->okay)
			{
				if ($this->updateInfo->updatenow)
				{
					// turn update to active
					$this->updateInfo->updatenow = false;
					$this->updateInfo->updateactive = true;
					// now save the date
					$this->okay = $this->saveUpdateInfo();
				}
				else
				{
					$this->okay = false;
				}
			}
			
			// run the update if all is okay
			if ($this->okay)
			{
				// set the config
				$this->setDropboxConfig();
				// set progress
				$this->progress['report'] = 30;
				$this->saveProgress();
				// load the file
				JLoader::import('dropbox', JPATH_COMPONENT_SITE.'/helpers');
				// okay now update
				if ($this->doUpdate())
				{
					return $this->okay;
				}
			}
		}
		return false;
	}

	/**
	*	set update mehtod
	**/
	protected function setUpdateMethod()
	{
		$method = $this->app_params->get($this->type.'_link_update_method', 0);
		if ($this->runManual)
		{
			// this is a manual method
			$this->updateMethod = 'manual';
		}
		elseif (2 == $method)
		{	
			// this it an auto mehtod
			$this->updateMethod = 'auto';
		}
		else
		{
			$this->okay = false;
		}
	}
	
	/**
	*	set update Links
	**/
	protected function setUpdateLinks()
	{
		// the number of links
		$numbers = range(1, 4);
		// now check if they are set
		foreach ($numbers as $number)
		{
			// set the number to string
			$numStr = SermondistributorHelper::safeString($number);
			// Get the url
			$url = $this->app_params->get($this->type.'dropbox'.$numStr, null);
			// only load those that are set
			if ($url)
			{
				$this->updateLinks[] = $url;
			}
		}
		// check if we found any
		if (!isset($this->updateLinks) || !SermondistributorHelper::checkArray($this->updateLinks))
		{
			$this->okay = false;
		}
	}
	
	/**
	*	set next update time
	**/
	protected function setDates()
	{			
		// set todays date/time
		$this->today = time();
		
		// set the next update date/time
		if ('manual' == $this->updateMethod)
		{
			// set the date to two days ago to ensure the update runs now
			$this->next = strtotime("-2 days");
		}
		else
		{
			// based on the auto time we will set the next update date/time
			$timer = $this->app_params->get($this->type.'_dropbox_timer', '60');
			if ($timer != 0)
			{
				// Get Next Update Time
				$this->next = strtotime('+'.$timer.' minutes', $this->today);
			}
			// if timer is 0 we should not update
			else
			{
				$this->okay = false;
			}			
		}
	}
	
	/**
	*	set update mehtod
	**/
	protected function setInfoData()
	{
		// set the info file name
		$fileName = md5($this->type.'info'.$this->localkey);
		// set file path
		$this->infoFilePath = JPATH_COMPONENT_SITE.'/helpers/'.$fileName.'.json';
		
		if (($json = @file_get_contents($this->infoFilePath)) !== FALSE)
		{
			$this->updateInfo = json_decode($json);
		}
		else
		{
			$this->updateInfo = new stdClass;
			$this->updateInfo->nextupdate = 0;
			$this->updateInfo->updateactive = false;
			$this->updateInfo->updatenow = false;
		}
	}

	/**
	*	check update status
	**/
	protected function checkUpdateStatus()
	{
		// check if there is already an update running
		if ($this->updateInfo->updateactive)
		{
			$this->okay = false;
		}
		// check if the time has come to do the next update
		elseif (('auto' == $this->updateMethod) && ($this->updateInfo->nextupdate > $this->today))
		{
			$this->okay = false;
		}
		else
		{
			$this->updateInfo->updatenow = true;
		}
	}

	/**
	*	save the update info
	**/
	protected function saveProgress()
	{
		return $this->saveJson(json_encode($this->progress),$this->progressFilePath);
	}

	/**
	*	save the update info
	**/
	protected function saveUpdateInfo()
	{
		return $this->saveJson(json_encode($this->updateInfo),$this->infoFilePath);
	}

	/**
	*	update the local listing
	**/
	protected function doUpdate()
	{
		// we need more then the normal time to run this script 5 minutes at least.
		ini_set('max_execution_time', 500);
		// get data of all the urls
		foreach ($this->updateLinks as $mainUrl)
		{
			// set progress
			if ($this->progress['report'] < 60)
			{
				$this->progress['report'] = $this->progress['report'] + 5;
				$this->saveProgress();
			}
			// get ldropbox links
			$dropbox = new Dropbox($mainUrl, $this->dropboxConfig);
			// set progress
			if ($this->progress['report'] < 70)
			{
				$this->progress['report'] = $this->progress['report'] + 5;
				$this->saveProgress();
			}
			// get the links
			if (SermondistributorHelper::checkArray($dropbox->files))
			{
				$this->listing = array_merge($this->listing, $dropbox->files);
			}
			// set progress
			if ($this->progress['report'] < 80)
			{
				$this->progress['report'] = $this->progress['report'] + 5;
				$this->saveProgress();
			}
			unset($dropbox);
		}
		
		// now store the new listing
		return $this->setNewListing();
	}
	
	protected function setNewListing()
	{		
		// set progress
		$this->progress['report'] = 100;
		$this->saveProgress();
		// reset storage
		$storeage = array();
		// set the listing file name
		$fileName = md5($this->type.'listing'.$this->localkey);
		// set file path
		$listingFilePath = JPATH_COMPONENT_SITE.'/helpers/'.$fileName.'.json';
		
		// now store the new listing
		if (SermondistributorHelper::checkArray($this->listing))
		{			
			// encrypt the urls
			$locker = new FOFEncryptAes($this->localkey, 256);
			foreach ($this->listing as $folder => $link)
			{
				$storeage[$folder] = base64_encode($locker->encryptString($link));
			}
		}
		else
		{
			$this->okay = false;
		}
		// save the update links.
		$this->saveJson(json_encode($storeage),$listingFilePath);
		// make sure the update reset
		$this->updateInfo->nextupdate = $this->next;
		$this->updateInfo->updateactive = false;
		$this->updateInfo->updatenow = false;
				
		return $this->saveUpdateInfo();
	}

	/**
	*	set the configeration for dropbox class
	**/
	protected function setDropboxConfig()
	{
		// reset config
		$this->dropboxConfig = array();
		// get the legal files set
		$getfiles = $this->app_params->get('dropbox_filetypes', null);
		if (SermondistributorHelper::checkArray($getfiles))
		{
			$this->dropboxConfig['get'] = $getfiles;
		}
		// set other config settings
		$this->dropboxConfig['save'] = false;
		$this->dropboxConfig['download'] = false;
	}

	protected function saveJson($data,$filename)
	{
		if (SermondistributorHelper::checkString($data))
		{
			$fp = fopen($filename, 'w');
			fwrite($fp, $data);
			fclose($fp);
			return true;
		}
		return false;
	}
}
