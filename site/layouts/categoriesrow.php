<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		categoriesrow.php
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
<td data-value="<?php echo $displayData->alias; ?>">
	<a href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
</td>
<?php if ($displayData->params->get('categories_desc')): ?>
<td>
	<?php if ($displayData->description): ?>
		<?php echo $displayData->description; ?>
	<?php endif; ?>
</td>
<?php endif; ?>
<?php if ($displayData->params->get('categories_hits')): ?>
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
<?php if ($displayData->params->get('categories_sermon_count')): ?>
<td data-value="<?php echo count($displayData->idSeriesSermonB); ?>">
		<?php $badge_class = (count($displayData->idSeriesSermonB) > 0) ? 'uk-badge-success':'uk-badge-warning'; ?>
		<div class="uk-badge uk-badge <?php echo $badge_class; ?>"><?php echo count($displayData->idSeriesSermonB); ?></div>
</td>
<?php endif; ?>
