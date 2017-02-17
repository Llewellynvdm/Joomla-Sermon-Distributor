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

	@version		@update number 8 of this MVC
	@build			18th November, 2015
	@created		29th October, 2015
	@package		Sermon Distributor
	@subpackage		statistics.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla controlleradmin library
jimport('joomla.application.component.controlleradmin');

/**
 * Statistics Controller
 */
class SermondistributorControllerStatistics extends JControllerAdmin
{
	protected $text_prefix = 'COM_SERMONDISTRIBUTOR_STATISTICS';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Statistic', $prefix = 'SermondistributorModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}

	public function exportData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('statistic.export', 'com_sermondistributor') && $user->authorise('core.export', 'com_sermondistributor'))
		{
			// Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// Sanitize the input
			JArrayHelper::toInteger($pks);
			// Get the model
			$model = $this->getModel('Statistics');
			// get the data to export
			$data = $model->getExportData($pks);
			if (SermondistributorHelper::checkArray($data))
			{
				// now set the data to the spreadsheet
				$date = JFactory::getDate();
				SermondistributorHelper::xls($data,'Statistics_'.$date->format('jS_F_Y'),'Statistics exported ('.$date->format('jS F, Y').')','statistics');
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_SERMONDISTRIBUTOR_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_sermondistributor&view=statistics', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('statistic.import', 'com_sermondistributor') && $user->authorise('core.import', 'com_sermondistributor'))
		{
			// Get the import model
			$model = $this->getModel('Statistics');
			// get the headers to import
			$headers = $model->getExImPortHeaders();
			if (SermondistributorHelper::checkObject($headers))
			{
				// Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('statistic_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'statistics');
				$session->set('dataType_VDM_IMPORTINTO', 'statistic');
				// Redirect to import view.
				$message = JText::_('COM_SERMONDISTRIBUTOR_IMPORT_SELECT_FILE_FOR_STATISTICS');
				$this->setRedirect(JRoute::_('index.php?option=com_sermondistributor&view=import', false), $message);
				return;
			}
		}
		// Redirect to the list screen with error.
		$message = JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_sermondistributor&view=statistics', false), $message, 'error');
		return;
	} 
}
