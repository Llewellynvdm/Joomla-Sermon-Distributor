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

	@version		1.3.0
	@build			23rd December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermonslistitem.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');



?>
<a <?php if ($displayData->params->get($displayData->viewKey.'_sermons_download_count')): ?> data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_DOWNLOAD_COUNT'); ?>: <?php echo 4; ?>" <?php endif; ?>href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
<?php if (('preacher' == $displayData->viewKey || 'category' == $displayData->viewKey) && $displayData->params->get($displayData->viewKey.'_sermons_series')): ?>
	<a href="<?php echo $displayData->series_link; ?>" data-uk-tooltip title="<?php echo $displayData->series_name; ?>"><?php echo $displayData->series_name; ?></a>
<?php endif ;?>
<?php if (('series' == $displayData->viewKey || 'category' == $displayData->viewKey) && $displayData->params->get($displayData->viewKey.'_sermons_preacher')): ?>
	<a href="<?php echo $displayData->preacher_link; ?>" data-uk-tooltip title="<?php echo $displayData->preacher_name; ?>"><?php echo $displayData->preacher_name; ?></a>
<?php endif ;?>
<?php if ('category' != $displayData->viewKey && $displayData->params->get($displayData->viewKey.'_sermons_category')): ?>
	<a href="mailto:<?php echo $displayData->category; ?>" data-uk-tooltip title="<?php echo $displayData->category; ?>"><?php echo $displayData->category; ?></a>
<?php endif ;?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_desc') && $displayData->short_description): ?>
	<?php echo $displayData->short_description; ?>
<?php elseif ($displayData->params->get($displayData->viewKey.'_sermons_desc') && $displayData->desc): ?>
	<?php echo $displayData->desc; ?>
<?php endif; ?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_hits')): ?>
	<em><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?></em>
<?php endif ;?>
