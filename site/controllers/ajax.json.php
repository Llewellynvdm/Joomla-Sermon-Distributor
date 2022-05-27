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
	@subpackage		ajax.json.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\Utilities\ArrayHelper;

/**
 * Sermondistributor Ajax Base Controller
 */
class SermondistributorControllerAjax extends BaseController
{
	public function __construct($config)
	{
		parent::__construct($config);
		// make sure all json stuff are set
		JFactory::getDocument()->setMimeEncoding( 'application/json' );
		// get the application
		$app = JFactory::getApplication();
		$app->setHeader('Content-Disposition','attachment;filename="getajax.json"');
		$app->setHeader('Access-Control-Allow-Origin', '*');
		// load the tasks 
		$this->registerTask('theQueue', 'ajax');
		$this->registerTask('countDownload', 'ajax');
	}

	public function ajax()
	{
		// get the user for later use
		$user 		= JFactory::getUser();
		// get the input values
		$jinput 	= JFactory::getApplication()->input;
		// check if we should return raw
		$returnRaw	= $jinput->get('raw', false, 'BOOLEAN');
		// return to a callback function
		$callback	= $jinput->get('callback', null, 'CMD');
		// Check Token!
		$token 		= JSession::getFormToken();
		$call_token	= $jinput->get('token', 0, 'ALNUM');
		if($jinput->get($token, 0, 'ALNUM') || $token === $call_token)
		{
			// get the task
			$task = $this->getTask();
			switch($task)
			{
				case 'theQueue':
					try
					{
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
						if($callback)
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
						if($callback)
						{
							echo $callback."(".json_encode($e).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($e);
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
						if($callback)
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
						if($callback)
						{
							echo $callback."(".json_encode($e).");";
						}
						elseif($returnRaw)
						{
							echo json_encode($e);
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
			// return to a callback function
			if($callback)
			{
				echo $callback."(".json_encode(false).");";
			}
			// return raw
			elseif($returnRaw)
			{
				echo json_encode(false);
			}
			else
  			{
				echo "(".json_encode(false).");";
			}
		}
	}
}
