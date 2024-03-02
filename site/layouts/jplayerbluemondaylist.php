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
	@subpackage		jplayerbluemondaylist.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/



use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;
use TrueChristianChurch\Component\Sermondistributor\Site\Helper\SermondistributorHelper;
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\Utilities\ArrayHelper;

// No direct access to this file
defined('JPATH_BASE') or die;

$random = StringHelper::random(5);
if (isset($displayData['script']) && ArrayHelper::check($displayData['script']))
{
	$script = array();
	$script[] = 'jQuery(document).ready(function(){';
	$script[] = 'new jPlayerPlaylist({';
	$script[] = 'jPlayer: "#jquery_jplayer_list_'.$random.'",';
	$script[] = 'cssSelectorAncestor: "#jp_container_list_'.$random.'"';
	$script[] = '}, [ {';
	$script[] = implode('}, {', $displayData['script']);
	$script[] = '} ], {';
	$script[] = 'swfPath: "'.$displayData['swfPath'].'",';
	$script[] = 'supplied: "'.implode(', ', $displayData['supplied']).'",';
	$script[] = 'wmode: "window",';
	$script[] = 'useStateClassSkin: true,';
	$script[] = 'autoBlur: false,';
	$script[] = 'smoothPlayBar: true,';
	$script[] = 'keyEnabled: true';
	$script[] = '}); });';
	// get the document
	$document = Factory::getDocument();
	// add script to document header
	$document->addScriptDeclaration(implode("\n", $script));
}

?>
<div id="jquery_jplayer_list_<?php echo $random; ?>" class="jp-jplayer" style="display: none;"></div>
<div id="jp_container_list_<?php echo $random; ?>" class="jp-audio" role="application" aria-label="media player">
	<div class="jp-type-playlist">
		<div class="jp-gui jp-interface">
			<div class="jp-controls">
				<button class="jp-previous" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_PREVIOUS'); ?></button>
				<button class="jp-play" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_PLAY'); ?></button>
				<button class="jp-next" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_NEXT'); ?></button>
				<button class="jp-stop" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_STOP'); ?></button>
			</div>
			<div class="jp-progress">
				<div class="jp-seek-bar">
					<div class="jp-play-bar"></div>
				</div>
			</div>
			<div class="jp-volume-controls">
				<button class="jp-mute" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_MUTE'); ?></button>
				<button class="jp-volume-max" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_MAX_VOLUME'); ?></button>
				<div class="jp-volume-bar">
					<div class="jp-volume-bar-value"></div>
				</div>
			</div>
			<div class="jp-time-holder">
				<div class="jp-current-time" role="timer" aria-label="time">&nbsp;</div>
				<div class="jp-duration" role="timer" aria-label="duration">&nbsp;</div>
			</div>
			<div class="jp-toggles">
				<button class="jp-repeat" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_REPEAT'); ?></button>
				<button class="jp-shuffle" role="button" tabindex="0"><?php echo Text::_('COM_SERMONDISTRIBUTOR_SHUFFLE'); ?></button>
			</div>
		</div>
		<div class="jp-playlist">
			<ul>
				<li>&nbsp;</li>
			</ul>
		</div>
		<div class="jp-no-solution">
			<span><?php echo Text::_('COM_SERMONDISTRIBUTOR_UPDATE_REQUIRED'); ?></span>
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_TO_PLAY_THE_MEDIA_YOU_WILL_NEED_TO_EITHER_UPDATE_YOUR_BROWSER_TO_A_RECENT_VERSION_OR_UPDATE_YOUR'); ?> <a href="http://get.adobe.com/flashplayer/" target="_blank"><?php echo Text::_('COM_SERMONDISTRIBUTOR_FLASH_PLUGIN'); ?></a>.
		</div>
	</div>
</div>
