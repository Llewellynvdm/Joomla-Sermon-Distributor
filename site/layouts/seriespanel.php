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
	@build			13th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		seriespanel.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');



?>
<div class="uk-panel uk-panel-box">
	<?php if ($displayData->params->get('list_series_hits')): ?>
		<?php
			$hits_state	= ($displayData->hits > 0) ? true:false;
			$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
			$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
		?>
		<div class="uk-panel-badge uk-badge <?php echo $badge_class; ?>">
			<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?>
		</div>
	<?php endif ;?>
	<h3 class="uk-panel-title"><?php echo $displayData->name; ?></h3>
	<?php if ($displayData->params->get('list_series_icon')): ?>
		<?php $displayData->icon = ($displayData->icon) ? $displayData->icon : $displayData->params->get('series_default_icon'); ?>
		<?php if ($displayData->icon): ?>
			<a href="<?php echo $displayData->link; ?>">
			<img class="uk-thumbnail uk-thumbnail-expand" src="<?php echo $displayData->icon; ?>" alt="<?php echo $displayData->name; ?>">
			</a>
			<hr />
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($displayData->params->get('list_series_desc') && $displayData->desc): ?>
		<?php echo $displayData->desc; ?>
		<hr />
	<?php endif; ?>
	<a class="uk-button uk-width-1-1 uk-margin-small-bottom uk-button-success" href="<?php echo $displayData->link; ?>">
		<?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?>
		<?php if ($displayData->params->get('list_series_sermon_count')): ?>
			<span data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>">(<?php echo count($displayData->idSeriesSermonB); ?>)</span>
		<?php endif; ?>
	</a>
</div>
