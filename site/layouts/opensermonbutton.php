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
	@subpackage		opensermonbutton.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('JPATH_BASE') or die('Restricted access');



?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_open') && $displayData->params->get('add_to_button') && isset($displayData->dropbox_buttons)): ?>
	<div class="uk-button-group uk-width-1-1">
		<a class="uk-button uk-margin-small-bottom uk-width-4-5 uk-button-primary" href="<?php echo $displayData->link; ?>" title="<?php echo $displayData->name; ?>">
			<i class="uk-icon-check"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?>
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_download_counter') && isset($displayData->statisticTotal)): ?>
				<span data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_DOWNLOAD_COUNTER_TOTAL'); ?>">(<?php echo $displayData->statisticTotal; ?>)</span>
			<?php endif; ?>
		</a>
		<?php if (count($displayData->dropbox_buttons) == 1): ?>
			<?php foreach ($displayData->dropbox_buttons as $filename => $link): ?>
				<a class="uk-button uk-margin-small-bottom uk-width-1-5" target="_blank"<?php echo $displayData->onclick_drobox[$filename]; ?> href="<?php echo $link; ?>" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SAVE_TO_YOUR_DROPBOX'); ?>"><i class="uk-icon-dropbox"></i></a>
			<?php endforeach; ?>
		<?php elseif (count($displayData->dropbox_buttons) > 1): ?>
			<?php $modalId = SermondistributorHelper::randomkey(5); ?>
			<button class="uk-button uk-margin-small-bottom uk-width-1-5" data-uk-modal="{target:'#download-<?php echo $modalId; ?>'}" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SAVE_TO_YOUR_DROPBOX'); ?>"><i class="uk-icon-dropbox"></i></button>
		<?php endif; ?>
	</div>
	<?php if (count($displayData->dropbox_buttons) > 1): ?>
		<div id="download-<?php echo $modalId; ?>" class="uk-modal">
			<div class="uk-modal-dialog">
				<a class="uk-modal-close uk-close"></a>
				<ul class="uk-list">
				<?php $num = 'A'; foreach ($displayData->dropbox_buttons as $filename => $link): ?>
					<li>
						<a class="uk-button uk-margin-small-bottom uk-width-1-1" target="_blank"<?php echo $displayData->onclick_drobox[$filename]; ?> href="<?php echo $link; ?>"  title="<?php echo $filename; ?>">
							<i class="uk-icon-dropbox"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_SAVE_TO_YOUR_DROPBOX').' '.$num; $num++;?>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		 </div>
	<?php endif; ?>
<?php elseif ($displayData->params->get($displayData->viewKey.'_sermons_open')): ?>
	<a class="uk-button uk-width-1-1 uk-margin-small-bottom uk-button-primary" href="<?php echo $displayData->link; ?>" title="<?php echo $displayData->name; ?>">
		<i class="uk-icon-check"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?>
		<?php if ($displayData->params->get($displayData->viewKey.'_sermons_download_counter') && isset($displayData->statisticTotal)): ?>
			<span data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_DOWNLOAD_COUNTER_TOTAL'); ?>">(<?php echo $displayData->statisticTotal; ?>)</span>
		<?php endif; ?>
	</a>
<?php elseif ($displayData->params->get('add_to_button') && isset($displayData->dropbox_buttons)): ?>
	<?php if (count($displayData->dropbox_buttons) == 1): ?>
		<?php foreach ($displayData->dropbox_buttons as $filename => $link): ?>
			<a class="uk-button uk-margin-small-bottom uk-width-1-1" target="_blank"<?php echo $displayData->onclick_drobox[$filename]; ?> href="<?php echo $link; ?>" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SAVE_TO_YOUR_DROPBOX'); ?>"><i class="uk-icon-dropbox"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_DROPBOX'); ?></a>
		<?php endforeach; ?>
	<?php elseif (count($displayData->dropbox_buttons) > 1): ?>
		<?php $modalId = SermondistributorHelper::randomkey(5); ?>
		<button class="uk-button uk-margin-small-bottom uk-width-1-1" data-uk-modal="{target:'#download-<?php echo $modalId; ?>'}" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SAVE_TO_YOUR_DROPBOX'); ?>"><i class="uk-icon-dropbox"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_DROPBOX'); ?></button>
		<div id="download-<?php echo $modalId; ?>" class="uk-modal">
			<div class="uk-modal-dialog">
				<a class="uk-modal-close uk-close"></a>
				<ul class="uk-list">
				<?php $num = 'A'; foreach ($displayData->dropbox_buttons as $filename => $link): ?>
					<li>
						<a class="uk-button uk-margin-small-bottom uk-width-1-1"<?php echo $displayData->onclick_drobox[$filename]; ?> target="_blank" href="<?php echo $link; ?>"  title="<?php echo $filename; ?>">
							<i class="uk-icon-dropbox"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_SAVE_TO_YOUR_DROPBOX').' '.$num; $num++;?>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		 </div>
	<?php endif; ?>
<?php endif; ?>
