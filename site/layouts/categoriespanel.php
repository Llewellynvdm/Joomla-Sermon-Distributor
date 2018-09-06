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
	@subpackage		categoriespanel.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('JPATH_BASE') or die('Restricted access');



?>
<div class="uk-panel uk-panel-box">
	<?php if ($displayData->params->get('categories_hits')): ?>
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
	<h3 class="uk-panel-title">
		<a href="<?php echo $displayData->link; ?>" title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?> <?php echo $displayData->name; ?>">
			<?php echo $displayData->name; ?>
		</a>
	</h3>
	<?php if ($displayData->params->get('categories_icon')): ?>
		<?php $displayData->icon = (isset($displayData->icon) && $displayData->icon) ? $displayData->icon : $displayData->params->get('category_default_icon'); ?>
		<?php if ($displayData->icon): ?>
			<a href="<?php echo $displayData->link; ?>">
			<img class="uk-thumbnail uk-thumbnail-expand" src="<?php echo $displayData->icon; ?>" alt="<?php echo $displayData->name; ?>">
			</a>
			<hr />
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($displayData->params->get('categories_desc') && $displayData->desc): ?>
		<?php echo $displayData->desc; ?>
		<hr />
	<?php endif; ?>
	<a class="uk-button uk-width-1-1 uk-margin-small-bottom uk-button-success" href="<?php echo $displayData->link; ?>">
		<?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?>
		<?php if ($displayData->params->get('categories_sermon_count')): ?>
			<span data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>">(<?php echo count($displayData->idCatidSermonB); ?>)</span>
		<?php endif; ?>
	</a>
</div>
