<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			30th November, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		addtodropboxbutton.php
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



?>
<?php if ($displayData->params->get('add_to_dropbox') && isset($displayData->dropbox_buttons)): ?>
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
						<a class="uk-button uk-margin-small-bottom uk-width-1-1" target="_blank"<?php echo $displayData->onclick_drobox[$filename]; ?> href="<?php echo $link; ?>"  title="<?php echo $filename; ?>">
							<i class="uk-icon-dropbox"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_SAVE_TO_YOUR_DROPBOX').' '.$num; $num++;?>
						</a>
					</li>
				<?php endforeach; ?>
				</ul>
			</div>
		 </div>
	<?php endif; ?>
<?php endif; ?>
