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

	@version		2.0.x
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
			'main' => array('png.preacher.add', 'png.preachers', 'png.sermon.add', 'png.sermons', 'png.sermons.catid_qpo0O0oqp_com_sermondistributor_po0O0oq_sermon', 'png.series.add', 'png.series_list', 'png.statistics', 'png.external_source.add', 'png.external_sources', 'png.manual_updater', 'png.local_listings', 'png.help_documents')
		);
		// view access array
		$viewAccess = array(
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
			'help_documents.dashboard_list' => 'help_document.dashboard_list');
		// loop over the $views
		foreach($viewGroups as $group => $views)
		{
			$i = 0;
			if (SermondistributorHelper::checkArray($views))
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
							$viewName 	= $name;
							$alt 		= $name;
							$url 		= $url;
							$image 		= $name . '.' . $type;
							$name 		= 'COM_SERMONDISTRIBUTOR_DASHBOARD_' . SermondistributorHelper::safeString($name,'U');
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
									$url	= 'index.php?option=com_sermondistributor&view=' . $name . '&layout=edit';
									$image	= $name . '_' . $action.  '.' . $type;
									$alt	= $name . '&nbsp;' . $action;
									$name	= 'COM_SERMONDISTRIBUTOR_DASHBOARD_'.SermondistributorHelper::safeString($name,'U').'_ADD';
									$add	= true;
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
									$url	= 'index.php?option=com_categories&view=categories&extension=' . $extension;
									$image	= $name . '_' . $action . '.' . $type;
									$alt	= $viewName . '&nbsp;' . $action;
									$name	= 'COM_SERMONDISTRIBUTOR_DASHBOARD_' . SermondistributorHelper::safeString($name,'U') . '_' . SermondistributorHelper::safeString($action,'U');
								break;
							}
						}
						else
						{
							$viewName 	= $name;
							$alt 		= $name;
							$url 		= 'index.php?option=com_sermondistributor&view=' . $name;
							$image 		= $name . '.' . $type;
							$name 		= 'COM_SERMONDISTRIBUTOR_DASHBOARD_' . SermondistributorHelper::safeString($name,'U');
							$hover		= false;
						}
					}
					else
					{
						$viewName 	= $view;
						$alt 		= $view;
						$url 		= 'index.php?option=com_sermondistributor&view=' . $view;
						$image 		= $view . '.png';
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
						// access checking start
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
						// check if access to view is set
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
								$icons[$group][$i]			= new StdClass;
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
								$icons[$group][$i]			= new StdClass;
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
								$icons[$group][$i]			= new StdClass;
								$icons[$group][$i]->url 	= $url;
								$icons[$group][$i]->name 	= $name;
								$icons[$group][$i]->image 	= $image;
								$icons[$group][$i]->alt 	= $alt;
							}
						}
						else
						{
							$icons[$group][$i]			= new StdClass;
							$icons[$group][$i]->url 	= $url;
							$icons[$group][$i]->name 	= $name;
							$icons[$group][$i]->image 	= $image;
							$icons[$group][$i]->alt 	= $alt;
						}
					}
					else
					{
						$icons[$group][$i]			= new StdClass;
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


	public function getGithub()
	{
		// load jquery (not sure why... but else the timeago breaks)
		JHtml::_('jquery.framework');
		// get the document to load the scripts
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() . "media/com_sermondistributor/js/timeago.js");
		$document->addScriptDeclaration('
		var urlToGetAllOpenIssues = "https://api.github.com/repos/Llewellynvdm/Joomla-Sermon-Distributor/issues?state=open&page=1&per_page=5";
		var urlToGetAllClosedIssues = "https://api.github.com/repos/Llewellynvdm/Joomla-Sermon-Distributor/issues?state=closed&page=1&per_page=5";
		var urlToGetAllReleases = "https://api.github.com/repos/Llewellynvdm/Joomla-Sermon-Distributor/releases?page=1&per_page=5";
		jQuery(document).ready(function () {
			jQuery.getJSON(urlToGetAllOpenIssues, function (openissues) {
				jQuery("#openissues").html("");
				jQuery.each(openissues, function (i, issue) {
					// set time ago
					var timeago = jQuery.timeago(new Date(issue.created_at)); 
					jQuery("#openissues")
            				.append("<h3><a href=\"" + issue.html_url + "\" target=\"_blank\">" + issue.title + "</a></h3>")
					.append("<img alt=\"@" + issue.user.login + "\" style=\"vertical-align: baseline;\" src=\"" + issue.user.avatar_url +"&amp;s=60\" width=\"30\" height=\"30\"> ")
            				.append("<em><a href=\"" + issue.user.html_url + "\" target=\"_blank\">" + issue.user.login + "</a> '.JText::_('COM_SERMONDISTRIBUTOR_OPENED_THIS').' <a href=\"" + issue.html_url + "\" target=\"_blank\">'.JText::_('COM_SERMONDISTRIBUTOR_ISSUE').'-" + issue.number + "</a> (" + timeago + ")</em> ")
            				.append(marked.parse(issue.body))
            				.append("<a href=\"" + issue.html_url + "\" target=\"_blank\"><span class=\'icon-new-tab\'></span>'.JText::_('COM_SERMONDISTRIBUTOR_RESPOND_TO_THIS_ISSUE_ON_GITHUB').'</a>...<hr />");
    				});
			});
			jQuery.getJSON(urlToGetAllClosedIssues, function (closedissues) {
				jQuery("#closedissues").html("");
				jQuery.each(closedissues, function (i, issue) {
					// set time ago
					var timeago = jQuery.timeago(new Date(issue.created_at)); 
					jQuery("#closedissues")
            				.append("<h3><a href=\"" + issue.html_url + "\" target=\"_blank\">" + issue.title + "</a></h3>")
					.append("<img alt=\"@" + issue.user.login + "\" style=\"vertical-align: baseline;\" src=\"" + issue.user.avatar_url +"&amp;s=60\" width=\"30\" height=\"30\"> ")
            				.append("<em><a href=\"" + issue.user.html_url + "\" target=\"_blank\">" + issue.user.login + "</a> '.JText::_('COM_SERMONDISTRIBUTOR_OPENED').' <a href=\"" + issue.html_url + "\" target=\"_blank\">'.JText::_('COM_SERMONDISTRIBUTOR_ISSUE').'-" + issue.number + "</a> (" + timeago + ")</em>")
            				.append(marked.parse(issue.body))
            				.append("<a href=\"" + issue.html_url + "\" target=\"_blank\"><span class=\'icon-new-tab\'></span>'.JText::_('COM_SERMONDISTRIBUTOR_REVIEW_THIS_ISSUE_ON_GITHUB').'</a>...<hr />");
    				});
			});
			jQuery.getJSON(urlToGetAllReleases, function (tagreleases) {				
				// set the update notice while we are at it
				var activeVersion = tagreleases[0].tag_name.substring(1);
				if (activeVersion === manifest.version) {
					// local version is in sync with latest release
					jQuery(".update-notice").html("<small><span style=\'color:green;\'><span class=\'icon-shield\'></span>'.JText::_('COM_SERMONDISTRIBUTOR_UP_TO_DATE').'</span></small>");
				} else {
					// split versions in to array
					var activeVersionArray = activeVersion.split(".");
					var localVersionArray = manifest.version.split(".");					
					if ((+localVersionArray[0] > +activeVersionArray[0]) || 
					(+localVersionArray[0] == +activeVersionArray[0] && +localVersionArray[1] > +activeVersionArray[1]) || 
					(+localVersionArray[0] == +activeVersionArray[0] && +localVersionArray[1] == +activeVersionArray[1] && +localVersionArray[2] > +activeVersionArray[2])) {
						// local version head latest release
						jQuery(".update-notice").html("<small><span style=\'color:#F7B033;\'><span class=\'icon-wrench\'></span>'.JText::_('COM_SERMONDISTRIBUTOR_BETA_RELEASE').'</span></small>");
					} else {
						// local version behind latest release
						jQuery(".update-notice").html("<small><span style=\'color:red;\'><span class=\'icon-warning-circle\'></span>'.JText::_('COM_SERMONDISTRIBUTOR_OUT_OF_DATE').'</span></small>");
					}
				}
				// set the taged releases
				jQuery("#tagreleases").html("");
				jQuery.each(tagreleases, function (i, tagrelease) {
					// set active release
					var activeNotice = "";
					if (i === 0) {
						var activeNotice = "<a class=\'btn btn-small btn-success\' href=\'https://github.com/Llewellynvdm/Joomla-Sermon-Distributor/releases/latest\'><span class=\'icon-shield icon-white\'></span> '.JText::_('COM_SERMONDISTRIBUTOR_LATEST_RELEASE').'</a><br /><br />";
					}
					// set time ago
					var timeago = jQuery.timeago(new Date(tagrelease.published_at)); 
					jQuery("#tagreleases")
            				.append("<h3><a href=\"" + tagrelease.html_url + "\" target=\"_blank\">" + tagrelease.name + "</a></h3>")
					.append(activeNotice)
					.append("<img alt=\"@" + tagrelease.author.login + "\" style=\"vertical-align: baseline;\" src=\"" + tagrelease.author.avatar_url +"&amp;s=60\" width=\"30\" height=\"30\"> ")
            				.append("<em><a href=\"" + tagrelease.author.html_url + "\" target=\"_blank\">" + tagrelease.author.login + "</a> '.JText::_('COM_SERMONDISTRIBUTOR_RELEASED_THIS').'<em> <b><span class=\'icon-tag-2\'></span>" + tagrelease.tag_name+ "</b> (" + timeago + ")")
            				.append(marked.parse(tagrelease.body))
            				.append(" <a class=\"hasTooltip\" href=\"" + tagrelease.assets[0].browser_download_url + "\" title=\"'.JText::_('COM_SERMONDISTRIBUTOR_DOWNLOAD').' " + tagrelease.assets[0].name + "\" target=\"_self\"><span class=\'icon-download\'></span>" + tagrelease.assets[0].name + "</a> (<a class=\"hasTooltip\" href=\"" + tagrelease.assets[0].browser_download_url + "\" title=\"'.JText::_('COM_SERMONDISTRIBUTOR_TOTAL_DOWNLOADS').'\"><small>" + tagrelease.assets[0].download_count + "</small></a>) ")
            				.append("| <a href=\"" + tagrelease.html_url + "\" target=\"_blank\" title=\"'.JText::_('COM_SERMONDISTRIBUTOR_OPEN').' " + tagrelease.name + " '.JText::_('COM_SERMONDISTRIBUTOR_ON_GITHUB').'\"><span class=\'icon-new-tab\'></span>'.JText::_('COM_SERMONDISTRIBUTOR_OPEN_ON_GITHUB').'</a>...<hr />");
    				});
			});
		});');
		$create = '<div class="btn-group pull-right">
					<a href="https://github.com/Llewellynvdm/Joomla-Sermon-Distributor/issues/new" class="btn btn-primary"  target="_blank">'.JText::_('COM_SERMONDISTRIBUTOR_NEW_ISSUE').'</a>
				</div></br >';
		$moreopen = '<b><a href="https://github.com/Llewellynvdm/Joomla-Sermon-Distributor/issues" target="_blank">'.JText::_('COM_SERMONDISTRIBUTOR_VIEW_MORE_ISSUES_ON_GITHUB').'</a>...</b> ';
		$moreclosed = '<b><a href="https://github.com/Llewellynvdm/Joomla-Sermon-Distributor/issues?q=is%3Aissue+is%3Aclosed" target="_blank">'.JText::_('COM_SERMONDISTRIBUTOR_VIEW_MORE_ISSUES_ON_GITHUB').'</a>...</b> ';
		$viewissues = '<b><a href="https://github.com/Llewellynvdm/Joomla-Sermon-Distributor/releases" target="_blank">'.JText::_('COM_SERMONDISTRIBUTOR_VIEW_MORE_RELEASES_ON_GITHUB').'</a>...</b> ';

		return (object) array(
				'openissues' => $create.'<div id="openissues">'.JText::_('COM_SERMONDISTRIBUTOR_A_FEW_OPEN_ISSUES_FROM_GITHUB_IS_LOADING').'.<span class="loading-dots">.</span></small></div>'.$moreopen, 
				'closedissues' => $create.'<div id="closedissues">'.JText::_('COM_SERMONDISTRIBUTOR_A_FEW_CLOSED_ISSUES_FROM_GITHUB_IS_LOADING').'.<span class="loading-dots">.</span></small></div>'.$moreclosed,
				'tagreleases' => '<div id="tagreleases">'.JText::_('COM_SERMONDISTRIBUTOR_LAST_FEW_RELEASES_FROM_GITHUB_IS_LOADING').'.<span class="loading-dots">.</span></small></div>'.$viewissues
		);
	}

	public function getWiki()
	{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
		var gewiki = "https://raw.githubusercontent.com/wiki/Llewellynvdm/Joomla-Sermon-Distributor/Home.md";
		jQuery(document).ready(function () {
			jQuery.get(gewiki)
			.success(function(wiki) { 
				jQuery("#wiki-md").html(marked.parse(wiki));
			})
			.error(function(jqXHR, textStatus, errorThrown) { 
				jQuery("#wiki-md").html("'.JText::_('COM_SERMONDISTRIBUTOR_PLEASE_CHECK_AGAIN_LATTER').'");
			});
		});');

		return '<div id="wiki-md"><small>'.JText::_('COM_SERMONDISTRIBUTOR_THE_WIKI_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}

	 

	public function getNoticeboard()
	{
		// get the document to load the scripts
		$document = JFactory::getDocument();
		$document->addScript(JURI::root() . "media/com_sermondistributor/js/marked.js");
		$document->addScriptDeclaration('
		var token = "'.JSession::getFormToken().'";
		var noticeboard = "https://vdm.bz/sermondistributor-noticeboard-md";
		jQuery(document).ready(function () {
			jQuery.get(noticeboard)
			.success(function(board) { 
				if (board.length > 5) {
					jQuery("#noticeboard-md").html(marked.parse(board));
					getIS(1,board).done(function(result) {
						if (result){
							jQuery("#cpanel_tabTabs a").each(function() {
								if (this.href.indexOf("#vast_development_method") >= 0 || this.href.indexOf("#notice_board") >= 0) {
									var textVDM = jQuery(this).text();
									jQuery(this).html("<span class=\"label label-important vdm-new-notice\">1</span> "+textVDM);
									jQuery(this).attr("id","vdm-new-notice");
									jQuery("#vdm-new-notice").click(function() {
										getIS(2,board).done(function(result) {
												if (result) {
												jQuery(".vdm-new-notice").fadeOut(500);
											}
										});
									});
								}
							});
						}
					});
				} else {
					jQuery("#noticeboard-md").html("'.JText::_('COM_SERMONDISTRIBUTOR_ALL_IS_GOOD_PLEASE_CHECK_AGAIN_LATTER').'");
				}
			})
			.error(function(jqXHR, textStatus, errorThrown) { 
				jQuery("#noticeboard-md").html("'.JText::_('COM_SERMONDISTRIBUTOR_ALL_IS_GOOD_PLEASE_CHECK_AGAIN_LATTER').'");
			});
		});
		// to check is READ/NEW
		function getIS(type,notice){
			if(type == 1){
				var getUrl = "index.php?option=com_sermondistributor&task=ajax.isNew&format=json&raw=true";
			} else if (type == 2) {
				var getUrl = "index.php?option=com_sermondistributor&task=ajax.isRead&format=json&raw=true";
			}	
			if(token.length > 0 && notice.length){
				var request = token+"=1&notice="+notice;
			}
			return jQuery.ajax({
				type: "POST",
				url: getUrl,
				dataType: "json",
				data: request,
				jsonp: false
			});
		}
		
// nice little dot trick :)
jQuery(document).ready( function($) {
  var x=0;
  setInterval(function() {
	var dots = "";
	x++;
	for (var y=0; y < x%8; y++) {
		dots+=".";
	}
	$(".loading-dots").text(dots);
  } , 500);
});');

		return '<div id="noticeboard-md">'.JText::_('COM_SERMONDISTRIBUTOR_THE_NOTICE_BOARD_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}

	public function getReadme()
	{
		$document = JFactory::getDocument();
		$document->addScriptDeclaration('
		var getreadme = "'. JURI::root() . 'administrator/components/com_sermondistributor/README.txt";
		jQuery(document).ready(function () {
			jQuery.get(getreadme)
			.success(function(readme) { 
				jQuery("#readme-md").html(marked(readme));
			})
			.error(function(jqXHR, textStatus, errorThrown) { 
				jQuery("#readme-md").html("'.JText::_('COM_SERMONDISTRIBUTOR_PLEASE_CHECK_AGAIN_LATTER').'");
			});
		});');

		return '<div id="readme-md"><small>'.JText::_('COM_SERMONDISTRIBUTOR_THE_README_IS_LOADING').'.<span class="loading-dots">.</span></small></div>';
	}
}
