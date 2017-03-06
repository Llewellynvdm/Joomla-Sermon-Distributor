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
	@build			28th February, 2017
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
		$this->registerTask('theQueue', 'ajax');
		$this->registerTask('updateLocalListingExternal', 'ajax');
		$this->registerTask('isNew', 'ajax');
		$this->registerTask('isRead', 'ajax');
		$this->registerTask('getBuildTable', 'ajax');
		$this->registerTask('getSourceStatus', 'ajax');
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
				case 'theQueue':
					try
					{
						$returnRaw = $jinput->get('raw', false, 'BOOLEAN');
						$listValue = $jinput->get('list', NULL, 'INT');
						$tarValue = $jinput->get('tar', NULL, 'INT');
						$typeValue = $jinput->get('type', NULL, 'INT');
						if($listValue && $tarValue && $typeValue)
						{
							$result = $this->getModel('ajax')->theQueue($listValue, $tarValue, $typeValue);
						}
						else
						{
							$result = false;
						}
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback . "(".json_encode($result).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($result);
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'updateLocalListingExternal':
					try
					{
						$returnRaw = $jinput->get('raw', false, 'BOOLEAN');
						$idValue = $jinput->get('id', NULL, 'INT');
						$targetValue = $jinput->get('target', NULL, 'INT');
						$typeValue = $jinput->get('type', NULL, 'INT');
						$sleutelValue = $jinput->get('sleutel', NULL, 'CMD');
						if($idValue && $targetValue && $typeValue && $sleutelValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->updateLocalListingExternal($idValue, $targetValue, $typeValue, $sleutelValue);
						}
						else
						{
							$result = false;
						}
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback . "(".json_encode($result).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($result);
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'isNew':
					try
					{
						$returnRaw = $jinput->get('raw', false, 'BOOLEAN');
						$noticeValue = $jinput->get('notice', NULL, 'STRING');
						if($noticeValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->isNew($noticeValue);
						}
						else
						{
							$result = false;
						}
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback . "(".json_encode($result).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($result);
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'isRead':
					try
					{
						$returnRaw = $jinput->get('raw', false, 'BOOLEAN');
						$noticeValue = $jinput->get('notice', NULL, 'STRING');
						if($noticeValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->isRead($noticeValue);
						}
						else
						{
							$result = false;
						}
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback . "(".json_encode($result).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($result);
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'getBuildTable':
					try
					{
						$returnRaw = $jinput->get('raw', false, 'BOOLEAN');
						$idNameValue = $jinput->get('idName', NULL, 'WORD');
						$ojectValue = $jinput->get('oject', NULL, 'STRING');
						if($idNameValue && $ojectValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->getBuildTable($idNameValue, $ojectValue);
						}
						else
						{
							$result = false;
						}
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback . "(".json_encode($result).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($result);
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback."(".json_encode($e).");";
						}
						else
						{
							echo "(".json_encode($e).");";
						}
					}
				break;
				case 'getSourceStatus':
					try
					{
						$returnRaw = $jinput->get('raw', false, 'BOOLEAN');
						$idValue = $jinput->get('id', NULL, 'INT');
						if($idValue && $user->id != 0)
						{
							$result = $this->getModel('ajax')->getSourceStatus($idValue);
						}
						else
						{
							$result = false;
						}
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback . "(".json_encode($result).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($result);
						}
						else
						{
							echo "(".json_encode($result).");";
						}
					}
					catch(Exception $e)
					{
						if($callback = $jinput->get('callback', null, 'CMD'))
						{
							echo $callback."(".json_encode($e).");";
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
			if($callback = $jinput->get('callback', null, 'CMD'))
                        {
				echo $callback."(".json_encode(false).");";
			}
                        else
                        {
				echo "(".json_encode(false).");";
			}
		}
	}
}
