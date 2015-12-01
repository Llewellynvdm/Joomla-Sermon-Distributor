<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		mediaplayer.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');

$players = array();
$num = 'A';
if (isset($displayData->download_links) && count($displayData->download_links))
{
	foreach ($displayData->download_links as $filename => $link)
	{
		if (strpos($filename, '.mp3') !== false) // TODO only mp3 at this time
		{
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
			$players[] = JLayoutHelper::render('audioplayer', $audio);
			$num++;
		}
	}
}

?>
<?php if (SermondistributorHelper::checkArray($players)): ?>
	<li><?php echo implode('',$players); ?></li>
<?php endif; ?>
