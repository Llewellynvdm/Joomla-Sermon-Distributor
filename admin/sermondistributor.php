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

	@version		2.0.x
	@build			3rd March, 2018
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermondistributor.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Access check.
if (!JFactory::getUser()->authorise('core.manage', 'com_sermondistributor'))
{
	return JError::raiseWaring(404, JText::_('JERROR_ALERTNOAUTHOR'));
};

// Load cms libraries
JLoader::registerPrefix('J', JPATH_PLATFORM . '/cms');
// Load joomla libraries without overwrite
JLoader::registerPrefix('J', JPATH_PLATFORM . '/joomla',false);

// Add CSS file for all pages
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_sermondistributor/assets/css/admin.css');
$document->addScript('components/com_sermondistributor/assets/js/admin.js');

// require helper files
JLoader::register('SermondistributorHelper', dirname(__FILE__) . '/helpers/sermondistributor.php'); 
JLoader::register('JHtmlBatch_', dirname(__FILE__) . '/helpers/html/batch_.php'); 

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Sermondistributor
$controller = JControllerLegacy::getInstance('Sermondistributor');

// Perform the Request task
$controller->execute(JFactory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
