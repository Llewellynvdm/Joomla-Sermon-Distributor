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
	@build			11th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		preachersrow.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');



?>
<td data-value="<?php echo $displayData->alias; ?>">
	<a href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
</td>
<?php if ($displayData->params->get('preachers_desc')): ?>
<td>
	<?php if ($displayData->description): ?>
		<?php echo $displayData->description; ?>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if ($displayData->params->get('preachers_website')): ?>
<td>
	<?php if ($displayData->website): ?>
		<?php echo $displayData->website; ?>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if ($displayData->params->get('preachers_email')): ?>
<td>
	<?php if ($displayData->email): ?>
		<?php echo $displayData->email; ?>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if ($displayData->params->get('preachers_hits')): ?>
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
<?php if ($displayData->params->get('preachers_sermon_count')): ?>
<td data-value="<?php echo count($displayData->idPreacherSermonB); ?>">
		<?php $badge_class = (count($displayData->idPreacherSermonB) > 0) ? 'uk-badge-success':'uk-badge-warning'; ?>
		<div class="uk-badge uk-badge <?php echo $badge_class; ?>"><?php echo count($displayData->idPreacherSermonB); ?></div>
</td>
<?php endif; ?>
