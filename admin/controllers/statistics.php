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
	@subpackage		statistics.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\AdminController;
use Joomla\Utilities\ArrayHelper;

/**
 * Statistics Admin Controller
 */
class SermondistributorControllerStatistics extends AdminController
{
	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_SERMONDISTRIBUTOR_STATISTICS';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JModelLegacy  The model.
	 *
	 * @since   1.6
	 */
	public function getModel($name = 'Statistic', $prefix = 'SermondistributorModel', $config = array('ignore_request' => true))
	{
		return parent::getModel($name, $prefix, $config);
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
			$pks = ArrayHelper::toInteger($pks);
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
