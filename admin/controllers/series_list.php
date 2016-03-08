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

	@version		1.3.1
	@build			8th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		series_list.php
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
 * Series_list Controller
 */
class SermondistributorControllerSeries_list extends JControllerAdmin
{
	protected $text_prefix = 'COM_SERMONDISTRIBUTOR_SERIES_LIST';
	/**
	 * Proxy for getModel.
	 * @since	2.5
	 */
	public function getModel($name = 'Series', $prefix = 'SermondistributorModel', $config = array())
	{
		$model = parent::getModel($name, $prefix, array('ignore_request' => true));
		
		return $model;
	}

	public function exportData()
	{
		// [Interpretation 6556] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [Interpretation 6558] check if export is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('series.export', 'com_sermondistributor') && $user->authorise('core.export', 'com_sermondistributor'))
		{
			// [Interpretation 6562] Get the input
			$input = JFactory::getApplication()->input;
			$pks = $input->post->get('cid', array(), 'array');
			// [Interpretation 6565] Sanitize the input
			JArrayHelper::toInteger($pks);
			// [Interpretation 6567] Get the model
			$model = $this->getModel('Series_list');
			// [Interpretation 6569] get the data to export
			$data = $model->getExportData($pks);
			if (SermondistributorHelper::checkArray($data))
			{
				// [Interpretation 6573] now set the data to the spreadsheet
				$date = JFactory::getDate();
				SermondistributorHelper::xls($data,'Series_list_'.$date->format('jS_F_Y'),'Series list exported ('.$date->format('jS F, Y').')','series list');
			}
		}
		// [Interpretation 6578] Redirect to the list screen with error.
		$message = JText::_('COM_SERMONDISTRIBUTOR_EXPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_sermondistributor&view=series_list', false), $message, 'error');
		return;
	}


	public function importData()
	{
		// [Interpretation 6587] Check for request forgeries
		JSession::checkToken() or die(JText::_('JINVALID_TOKEN'));
		// [Interpretation 6589] check if import is allowed for this user.
		$user = JFactory::getUser();
		if ($user->authorise('series.import', 'com_sermondistributor') && $user->authorise('core.import', 'com_sermondistributor'))
		{
			// [Interpretation 6593] Get the import model
			$model = $this->getModel('Series_list');
			// [Interpretation 6595] get the headers to import
			$headers = $model->getExImPortHeaders();
			if (SermondistributorHelper::checkObject($headers))
			{
				// [Interpretation 6599] Load headers to session.
				$session = JFactory::getSession();
				$headers = json_encode($headers);
				$session->set('series_VDM_IMPORTHEADERS', $headers);
				$session->set('backto_VDM_IMPORT', 'series_list');
				$session->set('dataType_VDM_IMPORTINTO', 'series');
				// [Interpretation 6605] Redirect to import view.
				$message = JText::_('COM_SERMONDISTRIBUTOR_IMPORT_SELECT_FILE_FOR_SERIES_LIST');
				$this->setRedirect(JRoute::_('index.php?option=com_sermondistributor&view=import', false), $message);
				return;
			}
		}
		// [Interpretation 6617] Redirect to the list screen with error.
		$message = JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FAILED');
		$this->setRedirect(JRoute::_('index.php?option=com_sermondistributor&view=series_list', false), $message, 'error');
		return;
	} 
}
