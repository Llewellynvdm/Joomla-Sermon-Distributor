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

	@version		5.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		categoriesrow.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/



use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;
use TrueChristianChurch\Component\Sermondistributor\Site\Helper\SermondistributorHelper;

// No direct access to this file
defined('JPATH_BASE') or die;



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
<td data-value="<?php echo count($displayData->idCatidSermonB); ?>">
		<?php $badge_class = (count($displayData->idCatidSermonB) > 0) ? 'uk-badge-success':'uk-badge-warning'; ?>
		<div class="uk-badge uk-badge <?php echo $badge_class; ?>"><?php echo count($displayData->idCatidSermonB); ?></div>
</td>
<?php endif; ?>
