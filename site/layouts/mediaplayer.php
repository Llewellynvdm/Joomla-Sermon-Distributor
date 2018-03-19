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
	@subpackage		mediaplayer.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');

$players = array();
$num = 'A';
if (isset($displayData->download_links) && count($displayData->download_links))
{
	foreach ($displayData->download_links as $filename => $link)
	{
		if (1 == $displayData->playerKey && strpos($filename, '.mp3') !== false) // TODO only mp3 at this time
		{
			// use sound manager
			$audio = array();
			$audio['link'] = $link;
			if (1 == count($displayData->download_links))
			{
				$audio['name'] = JText::_('COM_SERMONDISTRIBUTOR_PLAY_AUDIO_FILE');
			}
			else
			{
				$audio['name'] = JText::_('COM_SERMONDISTRIBUTOR_PLAY_AUDIO_FILE').' '.$num;
			}
			$audio['filename'] = $filename;
			$players[] = JLayoutHelper::render('soundmanagerthreesixty', $audio);
			$num++;
		}
		elseif (2 == $displayData->playerKey)
		{
			// use jPlayer
			if (1 == count($displayData->download_links))
			{
				$name = JText::_('COM_SERMONDISTRIBUTOR_AUDIO_FILE');
			}
			else
			{
				$name = JText::_('COM_SERMONDISTRIBUTOR_AUDIO_FILE').' '.$num;
			}
			if (!isset($players['script']))
			{
				$players['script'] = array();
				$players['supplied'] = array();
			}
			if (strpos($filename, '.mp3') !== false)
			{
				$players['script'][] = 'title: "'.$name.'", mp3: "'.$link.'"';
				$players['supplied'][] = 'mp3';
			}
			elseif (strpos($filename, '.m4a') !== false)
			{
				$players['script'][] = 'title: "'.$name.'", m4a: "'.$link.'"';
				$players['supplied'][] = 'm4a';
			}
			$num++;
		}
		elseif (3 == $displayData->playerKey)
		{
			// use html5 player (plain and simple)
			$players[] = JLayoutHelper::render('htmlfive', $link);	
		}
	}
	// use jPlayer layout
	if (isset($players['script']) && SermondistributorHelper::checkArray($players['script']))
	{
		$players['swfPath'] = JURI::root() .'media/com_sermondistributor/jplayer/jplayer';
		if (2 == $displayData->playerKey && 1 == count($displayData->download_links))
		{
			$players = JLayoutHelper::render('jplayerbluemonday', $players);
		}
		elseif (2 == $displayData->playerKey && count($displayData->download_links) > 1)
		{
			$players = JLayoutHelper::render('jplayerbluemondaylist', $players);
		}
	}
}

?>
<?php if (JFactory::getApplication()->client->browser): ?>
	<?php if (1 == $displayData->playerKey && SermondistributorHelper::checkArray($players)): ?>
		<li><?php echo implode('',$players); ?></li>
	<?php elseif (2 == $displayData->playerKey && SermondistributorHelper::checkString($players)): ?>
		<div class="uk-width-1-1 uk-margin"><div class="uk-panel"><?php echo $players; ?></div></div>
	<?php elseif (3 == $displayData->playerKey && SermondistributorHelper::checkArray($players)): ?>
		<div class="uk-width-1-1 uk-margin"><div class="uk-panel"><?php echo implode('</div><div class="uk-panel">', $players);  ?></div></div>
	<?php endif; ?>
<?php endif; ?>
