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

	@version		1.3.2
	@build			9th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		preacherslistitem.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');



?>
<a <?php if ($displayData->params->get('preachers_sermon_count')): ?> data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>: <?php echo count($displayData->idPreacherSermonB); ?>" <?php endif; ?>href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
<?php if ($displayData->params->get('preachers_website')): ?>
	<a href="<?php echo $displayData->website; ?>" target="_blank" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_GO_TO_WEBSITE_OF'); ?> <?php echo $displayData->name; ?>"><?php echo $displayData->website; ?></a>
<?php endif ;?>
<?php if ($displayData->params->get('preachers_email')): ?>
	<a href="mailto:<?php echo $displayData->email; ?>" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SEND_EMAIL_TO'); ?> <?php echo $displayData->name; ?>"><?php echo $displayData->email; ?></a>
<?php endif ;?>
<?php if ($displayData->params->get('preachers_desc') && $displayData->desc): ?>
	<?php echo $displayData->desc; ?>
<?php endif; ?>
<?php if ($displayData->params->get('preachers_hits')): ?>
	<em><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?></em>
<?php endif ;?>
