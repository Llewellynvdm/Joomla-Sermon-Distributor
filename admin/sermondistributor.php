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

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermondistributor.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// register additional namespace
\spl_autoload_register(function ($class) {
	// project-specific base directories and namespace prefix
	$search = [
		'libraries/jcb_powers/VDM.Joomla.FOF' => 'VDM\\Joomla\\FOF',
		'libraries/jcb_powers/VDM.Joomla' => 'VDM\\Joomla'
	];
	// Start the search and load if found
	$found = false;
	$found_base_dir = "";
	$found_len = 0;
	foreach ($search as $base_dir => $prefix)
	{
		// does the class use the namespace prefix?
		$len = strlen($prefix);
		if (strncmp($prefix, $class, $len) === 0)
		{
			// we have a match so load the values
			$found = true;
			$found_base_dir = $base_dir;
			$found_len = $len;
			// done here
			break;
		}
	}
	// check if we found a match
	if (!$found)
	{
		// not found so move to the next registered autoloader
		return;
	}
	// get the relative class name
	$relative_class = substr($class, $found_len);
	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = JPATH_ROOT . '/' . $found_base_dir . '/src' . str_replace('\\', '/', $relative_class) . '.php';
	// if the file exists, require it
	if (file_exists($file))
	{
		require $file;
	}
});

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\MVC\Controller\BaseController;

// Access check.
if (!Factory::getUser()->authorise('core.manage', 'com_sermondistributor'))
{
	throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Add CSS file for all pages
Html::_('stylesheet', 'components/com_sermondistributor/assets/css/admin.css', ['version' => 'auto']);
Html::_('script', 'components/com_sermondistributor/assets/js/admin.js', ['version' => 'auto']);

// require helper files
JLoader::register('SermondistributorHelper', __DIR__ . '/helpers/sermondistributor.php');
JLoader::register('JHtmlBatch_', __DIR__ . '/helpers/html/batch_.php');

// Get an instance of the controller prefixed by Sermondistributor
$controller = BaseController::getInstance('Sermondistributor');

// Perform the Request task
$controller->execute(Factory::getApplication()->input->get('task'));

// Redirect if set by the controller
$controller->redirect();
