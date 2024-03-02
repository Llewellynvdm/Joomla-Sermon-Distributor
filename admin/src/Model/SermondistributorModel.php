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

	@version		5.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		SermondistributorModel.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace TrueChristianChurch\Component\Sermondistributor\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\User\User;
use Joomla\Utilities\ArrayHelper;
use Joomla\Input\Input;
use TrueChristianChurch\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\StringHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor List Model
 *
 * @since  1.6
 */
class SermondistributorModel extends ListModel
{
	public function getIcons()
	{
		// load user for access menus
		$user = Factory::getApplication()->getIdentity();
		// reset icon array
		$icons  = [];
		// view groups array
		$viewGroups = array(
			'main' => array('png.preacher.add', 'png.preachers', 'png.sermon.add', 'png.sermons', 'png.sermons.catid_qpo0O0oqp_com_sermondistributor_po0O0oq_sermon', 'png.series.add', 'png.series_list', 'png.statistics', 'png.external_source.add', 'png.external_sources', 'png.manual_updater', 'png.local_listings', 'png.help_documents')
		);
		// view access array
		$viewAccess = [
			'manual_updater.access' => 'manual_updater.access',
			'manual_updater.submenu' => 'manual_updater.submenu',
			'manual_updater.dashboard_list' => 'manual_updater.dashboard_list',
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
			'external_source.create' => 'external_source.create',
			'external_sources.access' => 'external_source.access',
			'external_source.access' => 'external_source.access',
			'external_sources.submenu' => 'external_source.submenu',
			'external_sources.dashboard_list' => 'external_source.dashboard_list',
			'external_source.dashboard_add' => 'external_source.dashboard_add',
			'local_listing.create' => 'local_listing.create',
			'local_listings.access' => 'local_listing.access',
			'local_listing.access' => 'local_listing.access',
			'local_listings.submenu' => 'local_listing.submenu',
			'local_listings.dashboard_list' => 'local_listing.dashboard_list',
			'help_document.create' => 'help_document.create',
			'help_documents.access' => 'help_document.access',
			'help_document.access' => 'help_document.access',
			'help_documents.submenu' => 'help_document.submenu',
			'help_documents.dashboard_list' => 'help_document.dashboard_list',
		];
		// loop over the $views
		foreach($viewGroups as $group => $views)
		{
			$i = 0;
			if (UtilitiesArrayHelper::check($views))
			{
				foreach($views as $view)
				{
					$add = false;
					// external views (links)
					if (strpos($view,'||') !== false)
					{
						$dwd = explode('||', $view);
						if (count($dwd) == 3)
						{
							list($type, $name, $url) = $dwd;
							$viewName = $name;
							$alt      = $name;
							$url      = $url;
							$image    = $name . '.' . $type;
							$name     = 'COM_SERMONDISTRIBUTOR_DASHBOARD_' . StringHelper::safe($name,'U');
						}
					}
					// internal views
					elseif (strpos($view,'.') !== false)
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
									$url   = 'index.php?option=com_sermondistributor&view=' . $name . '&layout=edit';
									$image = $name . '_' . $action.  '.' . $type;
									$alt   = $name . '&nbsp;' . $action;
									$name  = 'COM_SERMONDISTRIBUTOR_DASHBOARD_'.StringHelper::safe($name,'U').'_ADD';
									$add   = true;
								break;
								default:
									// check for new convention (more stable)
									if (strpos($action, '_qpo0O0oqp_') !== false)
									{
										list($action, $extension) = (array) explode('_qpo0O0oqp_', $action);
										$extension = str_replace('_po0O0oq_', '.', $extension);
									}
									else
									{
										$extension = 'com_sermondistributor.' . $name;
									}
									$url   = 'index.php?option=com_categories&view=categories&extension=' . $extension;
									$image = $name . '_' . $action . '.' . $type;
									$alt   = $viewName . '&nbsp;' . $action;
									$name  = 'COM_SERMONDISTRIBUTOR_DASHBOARD_' . StringHelper::safe($name,'U') . '_' . StringHelper::safe($action,'U');
								break;
							}
						}
						else
						{
							$viewName = $name;
							$alt      = $name;
							$url      = 'index.php?option=com_sermondistributor&view=' . $name;
							$image    = $name . '.' . $type;
							$name     = 'COM_SERMONDISTRIBUTOR_DASHBOARD_' . StringHelper::safe($name,'U');
							$hover    = false;
						}
					}
					else
					{
						$viewName = $view;
						$alt      = $view;
						$url      = 'index.php?option=com_sermondistributor&view=' . $view;
						$image    = $view . '.png';
						$name     = ucwords($view).'<br /><br />';
						$hover    = false;
					}
					// first make sure the view access is set
					if (UtilitiesArrayHelper::check($viewAccess))
					{
						// setup some defaults
						$dashboard_add = false;
						$dashboard_list = false;
						$accessTo = '';
						$accessAdd = '';
						// access checking start
						$accessCreate = (isset($viewAccess[$viewName.'.create'])) ? StringHelper::check($viewAccess[$viewName.'.create']):false;
						$accessAccess = (isset($viewAccess[$viewName.'.access'])) ? StringHelper::check($viewAccess[$viewName.'.access']):false;
						// set main controllers
						$accessDashboard_add = (isset($viewAccess[$viewName.'.dashboard_add'])) ? StringHelper::check($viewAccess[$viewName.'.dashboard_add']):false;
						$accessDashboard_list = (isset($viewAccess[$viewName.'.dashboard_list'])) ? StringHelper::check($viewAccess[$viewName.'.dashboard_list']):false;
						// check for adding access
						if ($add && $accessCreate)
						{
							$accessAdd = $viewAccess[$viewName.'.create'];
						}
						elseif ($add)
						{
							$accessAdd = 'core.create';
						}
						// check if access to view is set
						if ($accessAccess)
						{
							$accessTo = $viewAccess[$viewName.'.access'];
						}
						// set main access controllers
						if ($accessDashboard_add)
						{
							$dashboard_add    = $user->authorise($viewAccess[$viewName.'.dashboard_add'], 'com_sermondistributor');
						}
						if ($accessDashboard_list)
						{
							$dashboard_list = $user->authorise($viewAccess[$viewName.'.dashboard_list'], 'com_sermondistributor');
						}
						if (StringHelper::check($accessAdd) && StringHelper::check($accessTo))
						{
							// check access
							if($user->authorise($accessAdd, 'com_sermondistributor') && $user->authorise($accessTo, 'com_sermondistributor') && $dashboard_add)
							{
								$icons[$group][$i]        = new \StdClass;
								$icons[$group][$i]->url   = $url;
								$icons[$group][$i]->name  = $name;
								$icons[$group][$i]->image = $image;
								$icons[$group][$i]->alt   = $alt;
							}
						}
						elseif (StringHelper::check($accessTo))
						{
							// check access
							if($user->authorise($accessTo, 'com_sermondistributor') && $dashboard_list)
							{
								$icons[$group][$i]        = new \StdClass;
								$icons[$group][$i]->url   = $url;
								$icons[$group][$i]->name  = $name;
								$icons[$group][$i]->image = $image;
								$icons[$group][$i]->alt   = $alt;
							}
						}
						elseif (StringHelper::check($accessAdd))
						{
							// check access
							if($user->authorise($accessAdd, 'com_sermondistributor') && $dashboard_add)
							{
								$icons[$group][$i]        = new \StdClass;
								$icons[$group][$i]->url   = $url;
								$icons[$group][$i]->name  = $name;
								$icons[$group][$i]->image = $image;
								$icons[$group][$i]->alt   = $alt;
							}
						}
						else
						{
							$icons[$group][$i]        = new \StdClass;
							$icons[$group][$i]->url   = $url;
							$icons[$group][$i]->name  = $name;
							$icons[$group][$i]->image = $image;
							$icons[$group][$i]->alt   = $alt;
						}
					}
					else
					{
						$icons[$group][$i]        = new \StdClass;
						$icons[$group][$i]->url   = $url;
						$icons[$group][$i]->name  = $name;
						$icons[$group][$i]->image = $image;
						$icons[$group][$i]->alt   = $alt;
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


	public function getWiki()
	{
		$document = Factory::getDocument();
		$document->addScriptDeclaration('
		var gewiki = "https://raw.githubusercontent.com/wiki/Llewellynvdm/Joomla-Sermon-Distributor/Home.md";
		document.addEventListener("DOMContentLoaded", function () {
			fetch(gewiki)
				.then(response => {
					if (!response.ok) {
						throw new Error("Network response was not ok");
					}
					return response.text();
				})
				.then(wiki => {
					document.getElementById("wiki-md").innerHTML = marked.parse(wiki);
				})
				.catch(error => {
					console.error("There has been a problem with your fetch operation:", error);
					document.getElementById("wiki-md").innerHTML = "'.Text::_('COM_SERMONDISTRIBUTOR_PLEASE_CHECK_AGAIN_LATTER').'";
				});
		});');

		return '<div id="wiki-md"><small>'.Text::_('COM_SERMONDISTRIBUTOR_THE_WIKI_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}

	 

	public function getNoticeboard()
	{
		// get the document to load the scripts
		$document = Factory::getDocument();
		Html::_('script', "media/com_sermondistributor/js/marked.js", ['version' => 'auto']);
		$document->addScriptDeclaration('
		var token = "' . Session::getFormToken() . '";
		var noticeboard = "https://vdm.bz/sermondistributor-noticeboard-md";
		document.addEventListener("DOMContentLoaded", function() {
			fetch(noticeboard)
			.then(response => {
				if (!response.ok) {
					throw new Error("Network response was not ok");
				}
				return response.text();
			})
			.then(board => {
				if (board.length > 5) {
					document.getElementById("noticeboard-md").innerHTML = marked.parse(board);
					getIS(1, board)
					.then(result => {
						if (result) {
							document.querySelectorAll("#cpanel_tabTabs a").forEach(link => {
								if (link.href.includes("#vast_development_method") || link.href.includes("#notice_board")) {
									var textVDM = link.textContent;
									link.innerHTML = "<span class=\"label label-important vdm-new-notice\">1</span> " + textVDM;
									link.id = "vdm-new-notice";
									document.getElementById("vdm-new-notice").addEventListener("click", () => {
										getIS(2, board)
										.then(result => {
											if (result) {
												document.querySelectorAll(".vdm-new-notice").forEach(element => {
													element.style.opacity = 0;
												});
											}
										});
									});
								}
							});
						}
					});
				} else {
					document.getElementById("noticeboard-md").innerHTML = "'.Text::_('COM_SERMONDISTRIBUTOR_ALL_IS_GOOD_PLEASE_CHECK_AGAIN_LATER').'.";
				}
			})
			.catch(error => {
				console.error("There was an error!", error);
				document.getElementById("noticeboard-md").innerHTML = "'.Text::_('COM_SERMONDISTRIBUTOR_ALL_IS_GOOD_PLEASE_CHECK_AGAIN_LATER').'.";
			});
		});

		// to check is READ/NEW
		function getIS(type, notice) {
			let getUrl = "";
			if (type === 1) {
				getUrl = "index.php?option=com_sermondistributor&task=ajax.isNew&format=json&raw=true";
			} else if (type === 2) {
				getUrl = "index.php?option=com_sermondistributor&task=ajax.isRead&format=json&raw=true";
			}
			let request = new URLSearchParams();
			if (token.length > 0 && notice.length) {
				request.append(token, "1");
				request.append("notice", notice);
			}
			return fetch(getUrl, {
				method: "POST",
				headers: {
					"Content-Type": "application/x-www-form-urlencoded;charset=UTF-8"
				},
				body: request
			}).then(response => response.json());
		}
		
document.addEventListener("DOMContentLoaded", function() {
	document.querySelectorAll(".loading-dots").forEach(function(loading_dots) {
		let x = 0;
		let intervalId = setInterval(function() {
			if (!loading_dots.classList.contains("loading-dots")) {
				clearInterval(intervalId);
				return;
			}
			let dots = ".".repeat(x % 8);
			loading_dots.textContent = dots;
			x++;
		}, 500);
	});
});');

		return '<div id="noticeboard-md">'.Text::_('COM_SERMONDISTRIBUTOR_THE_NOTICE_BOARD_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}

	public function getReadme()
	{
		$document = Factory::getDocument();
		$document->addScriptDeclaration('
		var getreadme = "'. Uri::root() . 'administrator/components/com_sermondistributor/README.txt";
		document.addEventListener("DOMContentLoaded", function () {
			fetch(getreadme)
			.then(response => {
				if (!response.ok) {
				    throw new Error("Network response was not ok");
				}
				return response.text();
			})
			.then(readme => {
				document.getElementById("readme-md").innerHTML = marked.parse(readme);
			})
			.catch(error => {
				console.error("There has been a problem with your fetch operation:", error);
				document.getElementById("readme-md").innerHTML = "'.Text::_('COM_SERMONDISTRIBUTOR_PLEASE_CHECK_AGAIN_LATER').'.";
			});
		});');

		return '<div id="readme-md"><small>'.Text::_('COM_SERMONDISTRIBUTOR_THE_README_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}
}
