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
	@build			7th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		ajax.json.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controllerform library
jimport('joomla.application.component.controller');

/**
 * Sermondistributor Ajax Controller
 */
class SermondistributorControllerAjax extends JControllerLegacy
{
	public function __construct($config)
	{
		parent::__construct($config);
		// make sure all json stuff are set
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		JResponse::setHeader('Content-Disposition','attachment;filename="getajax.json"');
		JResponse::setHeader("Access-Control-Allow-Origin", "*");
		// load the tasks 
		$this->registerTask('checkDropboxListing', 'ajax');
		$this->registerTask('countDownload', 'ajax');
	}

	public function ajax()
	{
		$user 		= JFactory::getUser();
		$jinput 	= JFactory::getApplication()->input;
		// Check Token!
		$token 		= JSession::getFormToken();
		$call_token	= $jinput->get('token', 0, 'ALNUM');
		if($token == $call_token)
                {
			$task = $this->getTask();
			switch($task)
                        {
				case 'checkDropboxListing':
					try
					{
						$viewValue = $jinput->get('view', NULL, 'INT');
						if($viewValue)
						{
							$result = $this->getModel('ajax')->dropbox($viewValue);
						}
						else
						{
							$result = false;
						}
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback'] . "(".json_encode($result).");";
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback']."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'countDownload':
					try
					{
						$keyValue = $jinput->get('key', NULL, 'BASE64');
						$filenameValue = $jinput->get('filename', NULL, 'CMD');
						if($keyValue && $filenameValue)
						{
							$result = $this->getModel('ajax')->countDownload($keyValue, $filenameValue);
						}
						else
						{
							$result = false;
						}
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback'] . "(".json_encode($result).");";
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if(array_key_exists('callback',$_GET))
						{
							echo $_GET['callback']."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
			}
		}
                else
                {
			if(array_key_exists('callback',$_GET))
                        {
				echo $_GET['callback']."(".json_encode(false).");";
			}
                        else
                        {
				echo "(".json_encode(false).");";
			}
		}
	}
}
