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
	@build			26th May, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermonsrow.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');



?>
<td data-value="<?php echo $displayData->alias; ?>">
	<?php if ($displayData->params->get($displayData->viewKey.'_sermons_open')): ?>
		<a href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
	<?php else: ?>
		<?php echo $displayData->name; ?>
	<?php endif; ?>
	<?php echo JLayoutHelper::render('isnew', $displayData); ?>
	<?php echo JLayoutHelper::render('addtodropboxicon', $displayData); ?>
</td>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_desc')): ?>
<td>
	<?php if ($displayData->short_description): ?>
		<?php echo $displayData->short_description; ?>
	<?php elseif ($displayData->description): ?>
		<?php echo $displayData->description; ?>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if (($displayData->viewKey == 'series' || $displayData->viewKey == 'category') && $displayData->params->get($displayData->viewKey.'_sermons_preacher')): ?>
<td>
	<?php if ($displayData->preacher): ?>
		<a class="" href="<?php echo $displayData->preacher_link; ?>" title="<?php echo $displayData->preacher_name ?>">
			<?php echo $displayData->preacher_name ?>
		</a>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if (($displayData->viewKey == 'preacher' || $displayData->viewKey == 'category')  && $displayData->params->get($displayData->viewKey.'_sermons_series')): ?>
<td>
	<?php if ($displayData->series): ?>
		<a class="" href="<?php echo $displayData->series_link; ?>" title="<?php echo $displayData->series_name ?>">
			<?php echo $displayData->series_name ?>
		</a>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if ($displayData->viewKey != 'category' && $displayData->params->get($displayData->viewKey.'_sermons_category')): ?>
<td>
	<?php if ($displayData->category): ?>
		<a class="" href="<?php echo $displayData->category_link; ?>" title="<?php echo $displayData->category ?>">
			<?php echo $displayData->category; ?>
		</a>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_downloads')): ?>
<td>
	<?php echo JLayoutHelper::render('downloadsermonbutton', $displayData); ?>
</td>
<?php endif; ?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_download_counter')): ?>
<td>
	<?php
		$counter_state	= ($displayData->statisticTotal > 0) ? true:false;
		$counter_class	= ($counter_state) ? 'uk-badge-success':'uk-badge-warning';
		$counter_icon	= ($counter_state) ? 'check-circle':'minus-circle';
	?>
	
	<div class="uk-badge uk-badge <?php echo $counter_class; ?>">
		<span data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_TOTAL_DOWNLOADS'); ?>">
		<i class="uk-icon-<?php echo $counter_icon; ?>"></i>
		<?php echo $displayData->statisticTotal; ?>
		</span>
	</div>
</td>
<?php endif; ?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_hits')): ?>
<td data-value="<?php echo $displayData->hits; ?>">
		<?php
			$hits_state	= ($displayData->hits > 0) ? true:false;
			$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
			$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
		?>
		<div class="uk-badge uk-badge <?php echo $badge_class; ?>">
			<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
			<?php echo $displayData->hits; ?>
		</div>
</td>
<?php endif ;?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_count')): ?>
<td data-value="<?php echo 2; ?>">
		<?php $badge_class = (1) ? 'uk-badge-success':'uk-badge-warning'; ?>
		<div class="uk-badge uk-badge <?php echo $badge_class; ?>"><?php echo 2; ?></div>
</td>
<?php endif; ?>
