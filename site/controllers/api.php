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
	@subpackage		api.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Utilities\ArrayHelper;

/**
 * Sermondistributor Api Form Controller
 */
class SermondistributorControllerApi extends FormController
{
	/**
	 * Current or most recently performed task.
	 *
	 * @var    string
	 * @since  12.2
	 * @note   Replaces _task.
	 */
	protected $task;

	public function __construct($config = array())
	{
		$this->view_list = 'preachers'; // safeguard for setting the return view listing to the default site view.
		parent::__construct($config);
	}

	public function worker()
	{
		// get input values
		$input = JFactory::getApplication()->input;
		// get DATA
		$DATA = $input->post->get('VDM_DATA', null, 'STRING');
		// get TASK
		$TASK = $input->server->get('HTTP_VDM_TASK', null, 'STRING');
		// check if correct value is given
		if (SermondistributorHelper::checkString($DATA) && SermondistributorHelper::checkString($TASK))
		{
			// get data array
			$dataArray = explode('___VDM___', $DATA);
			// get the task
			$task = SermondistributorHelper::unlock($TASK);
			// check the array
			if (SermondistributorHelper::checkArray($dataArray) && SermondistributorHelper::checkString($task))
			{
				// open theQueue function
				if ('theQueue' === $task)
				{
					if ($this->theQueue($dataArray))
					{
						echo 1;
						// clear session
						JFactory::getApplication()->getSession()->destroy();
						jexit();
					}
				}
			}
		}
		// not success
		echo 0;
		// clear session
		JFactory::getApplication()->getSession()->destroy();
		jexit();
	}

	protected function theQueue($dataArray)
	{
		// get the model
		$model = $this->getModel('Api', '', array());
		// set switch to run update system with external source
		$model->runUpdateSystemWithExternalSource = false;
		// loop over the data
		foreach ($dataArray as $data)
		{
			$data = SermondistributorHelper::unlock($data);
			if (SermondistributorHelper::checkArray($data) && count($data) == 3)
			{
				if (!$model->theQueue($data[0], $data[1], $data[2])) // id, target, typeID
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		if ($model->runUpdateSystemWithExternalSource)
		{
			$model->updateSystemWithExternalSource();
		}
		return true;
	}

	public function externalUpdate()
	{
		// get the model
		$model = $this->getModel('Api', '', array());
		// load all updates into workers
		do {
			// set update
			$model->setExternalUpdate();
		} while (SermondistributorHelper::$updateWatch == 1);  // only do one round
		// run the workers
		if (SermondistributorHelper::runWorker('theQueue', 2))
		{
			// not success
			echo 1;
			// clear session
			JFactory::getApplication()->getSession()->destroy();
			jexit();
		}
		// not success
		echo 0;
		// clear session
		JFactory::getApplication()->getSession()->destroy();
		jexit();
	}

	protected function getApiUser()
	{
		// get params
		$params = JComponentHelper::getParams('com_sermondistributor');
		// return user object
		return JFactory::getUser($params->get('api', 0, 'INT'));
	}

	/**
	 * Method to check if you can edit an existing record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key; default is id.
	 *
	 * @return  boolean
	 *
	 * @since   12.2
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// to insure no other tampering
		return false;
	}

        /**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = array())
	{
		// to insure no other tampering
		return false;
	}

	/**
	 * Method to check if you can save a new or existing record.
	 *
	 * Extended classes can override this if necessary.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   12.2
	 */
	protected function allowSave($data, $key = 'id')
	{
		// to insure no other tampering
		return false;
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   JModelLegacy  $model      The data model object.
	 * @param   array         $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   12.2
	 */
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
	}
}
