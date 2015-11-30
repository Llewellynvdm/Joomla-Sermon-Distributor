<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			30th November, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		downloadsermonbutton.php
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
