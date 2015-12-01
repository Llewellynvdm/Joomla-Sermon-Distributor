<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		ajax.json.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

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
		$this->registerTask('updateDropboxListing', 'ajax');
		$this->registerTask('getUpdateProgress', 'ajax');
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
						if($viewValue && $user->id != 0)
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
				case 'updateDropboxListing':
					try
					{
						$typeValue = $jinput->get('type', NULL, 'INT');
						if($typeValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->updateDropbox($typeValue);
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
				case 'getUpdateProgress':
					try
					{
						$typeValue = $jinput->get('type', NULL, 'INT');
						if($typeValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->updateProgress($typeValue);
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
