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
	@build			17th August, 2017
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

// Set the component css/js
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_sermondistributor/assets/css/site.css');
$document->addScript('components/com_sermondistributor/assets/js/site.js');

// Require helper files
JLoader::register('SermondistributorHelper', dirname(__FILE__) . '/helpers/sermondistributor.php'); 
JLoader::register('SermondistributorHelperRoute', dirname(__FILE__) . '/helpers/route.php'); 

// Triger the Global Site Event
SermondistributorHelper::globalEvent($document);

// import joomla controller library
jimport('joomla.application.component.controller');

// Get an instance of the controller prefixed by Sermondistributor
$controller = JControllerLegacy::getInstance('Sermondistributor');

// Perform the request task
$jinput = JFactory::getApplication()->input;
$controller->execute($jinput->get('task', null, 'CMD'));

// Redirect if set by the controller
$controller->redirect();
