<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermondistributor.php
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

// import the Joomla modellist library
jimport('joomla.application.component.modellist');
jimport('joomla.application.component.helper');

/**
 * Sermondistributor Model
 */
class SermondistributorModelSermondistributor extends JModelList
{
	public function getIcons()
	{
                // load user for access menus
                $user = JFactory::getUser();
                // reset icon array
		$icons  = array();
                // view groups array
		$viewGroups = array(
			'main' => array('png.preacher.add', 'png.preachers', 'png.sermon.add', 'png.sermons', 'png.sermons.catid', 'png.series.add', 'png.series_list', 'png.statistics', 'png.help_documents')
		);
		// [12175] view access array
		$viewAccess = array(
			'preacher.create' => 'preacher.create',
			'preachers.access' => 'preacher.access',
			'preacher.access' => 'preacher.access',
			'preachers.submenu' => 'preacher.submenu',
			'preachers.dashboard_list' => 'preacher.dashboard_list',
			'preacher.dashboard_add' => 'preacher.dashboard_add',
			'sermon.create' => 'sermon.create',
			'sermons.access' => 'sermon.access',
			'sermon.access' => 'sermon.access',
			'sermons.submenu' => 'sermon.submenu',
			'sermons.dashboard_list' => 'sermon.dashboard_list',
			'sermon.dashboard_add' => 'sermon.dashboard_add',
			'series.create' => 'series.create',
			'series_list.access' => 'series.access',
			'series.access' => 'series.access',
			'series_list.submenu' => 'series.submenu',
			'series_list.dashboard_list' => 'series.dashboard_list',
			'series.dashboard_add' => 'series.dashboard_add',
			'statistic.create' => 'statistic.create',
			'statistics.access' => 'statistic.access',
			'statistic.access' => 'statistic.access',
			'statistics.submenu' => 'statistic.submenu',
			'statistics.dashboard_list' => 'statistic.dashboard_list',
			'help_document.create' => 'help_document.create',
			'help_documents.access' => 'help_document.access',
			'help_document.access' => 'help_document.access',
			'help_documents.submenu' => 'help_document.submenu',
			'help_documents.dashboard_list' => 'help_document.dashboard_list');
		foreach($viewGroups as $group => $views)
                {
			$i = 0;
			if (SermondistributorHelper::checkArray($views))
                        {
				foreach($views as $view)
				{
					$add = false;
					if (strpos($view,'.') !== false)
                                        {
                                                $dwd = explode('.', $view);
                                                if (count($dwd) == 3)
                                                {
                                                        list($type, $name, $action) = $dwd;
                                                }
                                                elseif (count($dwd) == 2)
                                                {
                                                        list($type, $name) = $dwd;
                                                        $action = false;
                                                }
                                                if ($action)
                                                {
                                                        $viewName = $name;
                                                        switch($action)
                                                        {
                                                                case 'add':
                                                                        $url 	='index.php?option=com_sermondistributor&view='.$name.'&layout=edit';
                                                                        $image 	= $name.'_'.$action.'.'.$type;
                                                                        $alt 	= $name.'&nbsp;'.$action;
                                                                        $name	= 'COM_SERMONDISTRIBUTOR_DASHBOARD_'.SermondistributorHelper::safeString($name,'U').'_ADD';
                                                                        $add	= true;
                                                                break;
                                                                default:
                                                                        $url 	= 'index.php?option=com_categories&view=categories&extension=com_sermondistributor.'.$name;
                                                                        $image 	= $name.'_'.$action.'.'.$type;
                                                                        $alt 	= $name.'&nbsp;'.$action;
                                                                        $name	= 'COM_SERMONDISTRIBUTOR_DASHBOARD_'.SermondistributorHelper::safeString($name,'U').'_'.SermondistributorHelper::safeString($action,'U');
                                                                break;
                                                        }
                                                }
                                                else
                                                {
                                                        $viewName 	= $name;
                                                        $alt 		= $name;
                                                        $url 		= 'index.php?option=com_sermondistributor&view='.$name;
                                                        $image 		= $name.'.'.$type;
                                                        $name 		= 'COM_SERMONDISTRIBUTOR_DASHBOARD_'.SermondistributorHelper::safeString($name,'U');
                                                        $hover		= false;
                                                }
                                        }
                                        else
                                        {
                                                $viewName 	= $view;
                                                $alt 		= $view;
                                                $url 		= 'index.php?option=com_sermondistributor&view='.$view;
                                                $image 		= $view.'.png';
                                                $name 		= ucwords($view).'<br /><br />';
                                                $hover		= false;
                                        }
                                        // first make sure the view access is set
                                        if (SermondistributorHelper::checkArray($viewAccess))
                                        {
						// setup some defaults
						$dashboard_add = false;
						$dashboard_list = false;
                                                $accessTo = '';
                                                $accessAdd = '';
                                                // acces checking start
                                                $accessCreate = (isset($viewAccess[$viewName.'.create'])) ? SermondistributorHelper::checkString($viewAccess[$viewName.'.create']):false;
                                                $accessAccess = (isset($viewAccess[$viewName.'.access'])) ? SermondistributorHelper::checkString($viewAccess[$viewName.'.access']):false;
						// set main controllers
						$accessDashboard_add = (isset($viewAccess[$viewName.'.dashboard_add'])) ? SermondistributorHelper::checkString($viewAccess[$viewName.'.dashboard_add']):false;
						$accessDashboard_list = (isset($viewAccess[$viewName.'.dashboard_list'])) ? SermondistributorHelper::checkString($viewAccess[$viewName.'.dashboard_list']):false;
                                                // check for adding access
                                                if ($add && $accessCreate)
                                                {
                                                        $accessAdd = $viewAccess[$viewName.'.create'];
                                                }
                                                elseif ($add)
                                                {
                                                        $accessAdd = 'core.create';
                                                }
                                                // check if acces to view is set
                                                if ($accessAccess)
                                                {
                                                        $accessTo = $viewAccess[$viewName.'.access'];
                                                }
						// set main access controllers
						if ($accessDashboard_add)
						{
							$dashboard_add	= $user->authorise($viewAccess[$viewName.'.dashboard_add'], 'com_sermondistributor');
						}
						if ($accessDashboard_list)
						{
							$dashboard_list = $user->authorise($viewAccess[$viewName.'.dashboard_list'], 'com_sermondistributor');
						}
                                                if (SermondistributorHelper::checkString($accessAdd) && SermondistributorHelper::checkString($accessTo))
                                                {
                                                        // check access
                                                        if($user->authorise($accessAdd, 'com_sermondistributor') && $user->authorise($accessTo, 'com_sermondistributor') && $dashboard_add)
                                                        {
                                                                $icons[$group][$i]              = new StdClass;
                                                                $icons[$group][$i]->url 	= $url;
                                                                $icons[$group][$i]->name 	= $name;
                                                                $icons[$group][$i]->image 	= $image;
                                                                $icons[$group][$i]->alt 	= $alt;
                                                        }
                                                }
                                                elseif (SermondistributorHelper::checkString($accessTo))
                                                {
                                                        // check access
                                                        if($user->authorise($accessTo, 'com_sermondistributor') && $dashboard_list)
                                                        {
                                                                $icons[$group][$i]              = new StdClass;
                                                                $icons[$group][$i]->url 	= $url;
                                                                $icons[$group][$i]->name 	= $name;
                                                                $icons[$group][$i]->image 	= $image;
                                                                $icons[$group][$i]->alt 	= $alt;
                                                        }
                                                }
                                                elseif (SermondistributorHelper::checkString($accessAdd))
                                                {
                                                        // check access
                                                        if($user->authorise($accessAdd, 'com_sermondistributor') && $dashboard_add)
                                                        {
                                                                $icons[$group][$i]              = new StdClass;
                                                                $icons[$group][$i]->url 	= $url;
                                                                $icons[$group][$i]->name 	= $name;
                                                                $icons[$group][$i]->image 	= $image;
                                                                $icons[$group][$i]->alt 	= $alt;
                                                        }
                                                }
                                                else
                                                {
                                                        $icons[$group][$i]              = new StdClass;
                                                        $icons[$group][$i]->url 	= $url;
                                                        $icons[$group][$i]->name 	= $name;
                                                        $icons[$group][$i]->image 	= $image;
                                                        $icons[$group][$i]->alt 	= $alt;
                                                }
                                        }
                                        else
                                        {
                                                $icons[$group][$i]              = new StdClass;
                                                $icons[$group][$i]->url 	= $url;
                                                $icons[$group][$i]->name 	= $name;
                                                $icons[$group][$i]->image 	= $image;
                                                $icons[$group][$i]->alt 	= $alt;
                                        }
                                        $i++;
                                }
                        }
                        else
                        {
                                $icons[$group][$i] = false;
			}
		}
		return $icons;
	}
}
