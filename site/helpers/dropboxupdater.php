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

	@version		2.1.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		dropboxupdater.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Dropbox updater component helper
 */
class Dropboxupdater
{
	
	/**
	* 	update flag (if false update will not happen)
	**/
	protected $okay = true;
	
	/**
	* 	update flag (if false update will not happen)
	**/
	protected $data = null;
	
	/**
	* 	the file Key
	**/
	protected $fileKey;
	
	/**
	* 	allow a forced update
	**/
	protected $forceUpdate;
	
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
	* 	update targets
	**/
	protected $updateTarget;

	/**
	* 	info related to this update
	**/
	protected $updateInfo;
	protected $infoFilePath;
	
	/**
	* 	Main dropbox class
	**/
	protected $dropbox;
	
	/**
	* 	component parameters
	**/
	protected $app_params;
	
	/**
	* 	the errors
	**/
	protected $errors = array();

	/**
	*	everything we want done when initialized
	**/
	public function __construct()
	{
		// get params
		$this->app_params = JComponentHelper::getParams('com_sermondistributor');
	}

	/**
	*	get the logged errors array
	**/
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	*	set the error to the log
	**/
	protected function setErrors($error)
	{
		if (SermondistributorHelper::checkString($error))
		{
			$this->errors[] = $error;
		}
		elseif (SermondistributorHelper::checkArray($error))
		{
			foreach($error as $log)
			{
				if (SermondistributorHelper::checkString($log))
				{
					$this->errors[] = $log;
				}
			}
		}
	}

	/**
	*	update method
	**/
	public function update($id, $target, $type = false, $forceUpdate = false, $sleutel = null)
	{
		if ($type)
		{
			// start fresh
			$this->okay = true;
			$this->data = null;
			// is this a forced run
			$this->forceUpdate = $forceUpdate;
			// the file key
			$this->fileKey = SermondistributorHelper::safeString($id.$target.$type);
			// set the type of this listing
			$this->type = $type;
			
			// get the external source data being updated
			$this->setExternalSourceData($id);
			
			// load the token if manualy set
			if ($sleutel)
			{
				$this->setExternalSourceData($id, array('oauthtoken' => $sleutel));
			}
	
			// what update method is set
			$this->setUpdateMethod();

			// set the update links
			$this->setUpdateTarget($target);

			// set needed dates
			if ($this->okay)
			{
				$this->setDates();
			}

			// get info data or set if not found
			if ($this->okay)
			{
				$this->setUpdateInfoData();
			}

			// check if update should run now
			if ($this->okay)
			{
				$this->checkUpdateStatus();
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
				$this->setDetailsConfig();
				// load the file
				JLoader::import('dropbox', JPATH_COMPONENT_SITE.'/helpers');
				$build = 1;
				if ('auto' == $this->type)
				{
					$build = 2;
				}
				// load the dropbox class
				$this->dropbox = new Dropbox(SermondistributorHelper::getModel('local_listing', JPATH_COMPONENT_ADMINISTRATOR), $build);
				// okay now update
				$this->okay = $this->doUpdate();
			}
			// always reset if all okay
			return $this->resetUpdate();
		}
		$this->setErrors('The update type is unknown.');
		return false;
	}
	
	/**
	 *	set the exsternal source data
	 */
	protected function setExternalSourceData($id, $data = array())
	{
		// get the data if not set
		if (!$this->data || !SermondistributorHelper::checkObject($this->data))
		{
			// load model to get the data
			$model = SermondistributorHelper::getModel('external_source', JPATH_COMPONENT_ADMINISTRATOR);
			$this->data = $model->getItem($id);
		}
		// if new data is set load it
		if (SermondistributorHelper::checkArray($data))
		{
			foreach ($data as $key => $value)
			{
				$this->data->$key = $value;
			}
		}				
	}

	/**
	*	set update mehtod
	**/
	protected function setUpdateMethod()
	{
		if ($this->forceUpdate)
		{
			// this is a manual method
			$this->updateMethod = 'manual';
		}
		elseif (2 == $this->data->update_method)
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
	*	set update target
	**/
	protected function setUpdateTarget($nr)
	{
		// get target based on type and position
		if ('full' == $this->data->permissiontype && $nr > 0)
		{
			$position = $nr - 1;
			if (1 == $this->data->dropboxoptions && SermondistributorHelper::checkJson($this->data->sharedurl))
			{
				$targets = json_decode($this->data->sharedurl)->tsharedurl;
			}
			elseif (2 == $this->data->dropboxoptions && SermondistributorHelper::checkJson($this->data->folder))
			{
				$targets = json_decode($this->data->folder)->tfolder;
			}
			// check if we found any
			if (!isset($targets[$position]) || !SermondistributorHelper::checkString($targets[$position]))
			{
				$this->setErrors('The target Shared-url or Folder is not set.');
				$this->okay = false;
			}
			else
			{
				$this->updateTarget = $targets[$position];
			}
		}
		else
		{
			$this->updateTarget = '';
		}
	}

	/**
	*	set the configeration for exsternal source class
	**/
	protected function setDetailsConfig()
	{
		// reset config
		$this->detailsConfig = array();
		// the source ID
		$this->detailsConfig['sourceID'] = $this->data->id;
		// get the legal files set
		$this->detailsConfig['addTypes'] = $this->data->filetypes;
		// set other config settings
		$this->detailsConfig['dropboxOption'] = $this->data->dropboxoptions;
		// set dropbox target
		$this->detailsConfig['dropboxTarget'] = $this->updateTarget;
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
			$timer = $this->data->update_timer;
			if ($timer != 0)
			{
				// Get Next Update Time
				$this->next = strtotime('+'.$timer.' minutes', $this->today);
			}
			// if timer is 0 we should not update
			else
			{
				$this->setErrors('The timer is not setup correctly.');
				$this->okay = false;
			}			
		}
	}
	
	/**
	*	set update info data
	**/
	protected function setUpdateInfoData()
	{
		// set the info file name
		$fileName = md5($this->fileKey.'info');
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
			$this->setErrors('There is an update already running.');
		}
		// check if the time has come to do the next update
		elseif (('auto' == $this->updateMethod) && ($this->updateInfo->nextupdate > $this->today))
		{
			$this->okay = false;
			$this->setErrors('It is not yet time to run this update.');
		}
		else
		{
			$this->updateInfo->updatenow = true;
		}
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
		ini_set('max_execution_time', $this->app_params->get('max_execution_time', 500));
		// get data of all the shared links of all target items
		if (!$this->dropbox->getFiles($this->data->oauthtoken, $this->data->permissiontype, $this->detailsConfig))
		{
			$this->setErrors($this->dropbox->error_summary);
			return false;
		}
		// if this is a manual update, then revoke the token
		if ($this->forceUpdate)
		{
			$this->dropbox->revokeToken();
		}
		return true;
	}
	
	public function resetUpdate()
	{
		if ($this->okay || (isset($this->dropbox->forceReset) && $this->dropbox->forceReset))
		{
			// make sure the update reset
			$this->updateInfo->nextupdate = $this->next;
			$this->updateInfo->updateactive = false;
			$this->updateInfo->updatenow = false;
			// store final update
			$this->saveUpdateInfo();
		}
		return $this->okay;
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
