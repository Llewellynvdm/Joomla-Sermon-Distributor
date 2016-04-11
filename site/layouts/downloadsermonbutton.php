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
	@build			11th April, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		downloadsermonbutton.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');



?>
<?php if (isset($displayData->download_links) && count($displayData->download_links) == 1): ?>
	<?php foreach ($displayData->download_links as $filename => $link): ?>
		<a class="uk-button uk-width-1-1 uk-margin-small-bottom uk-button-success"<?php echo $displayData->onclick[$filename]; ?> href="<?php echo $link; ?>" title="<?php echo $filename; ?>">
			<i class="uk-icon-download"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOAD'); ?>
		</a>
	<?php endforeach; ?>
<?php elseif (isset($displayData->download_links) && count($displayData->download_links) > 1): ?>
	<?php $modalId = SermondistributorHelper::randomkey(5); ?>
	<button class="uk-button uk-width-1-1 uk-button-success" data-uk-modal="{target:'#download-<?php echo $modalId; ?>'}" ><i class="uk-icon-download"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOADS'); ?></button>
	<div id="download-<?php echo $modalId; ?>" class="uk-modal">
		<div class="uk-modal-dialog">
			<a class="uk-modal-close uk-close"></a>
			<ul class="uk-list">
			<?php $num = 'A'; foreach ($displayData->download_links as $filename => $link): ?>
				<li>
					<a class="uk-button uk-margin-small-bottom uk-width-1-1 uk-button-success"<?php echo $displayData->onclick[$filename]; ?> href="<?php echo $link; ?>" title="<?php echo $filename; ?>">
						<i class="uk-icon-download"></i> <?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOAD').' '.$num; $num++;?>
						<?php if ($displayData->params->get($displayData->viewKey.'_sermons_download_counter') && isset($displayData->statistic[$filename])): ?>
							<span data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOAD_COUNTER'); ?>">(<?php echo $displayData->statistic[$filename]; ?>)</span>
						<?php endif; ?>
					</a>
				</li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>
