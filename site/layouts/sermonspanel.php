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

	@version		4.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermonspanel.php
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

// build the list class
$style = $displayData->params->get($displayData->viewKey.'_sermons_list_style');
switch ($style)
{
	case 1:
		// Lines
		$listClass = ' uk-list-line';
	break;
	case 2:
		// Striped
		$listClass = ' uk-list-striped';
	break;
	case 3:
		// Spaced
		$listClass = ' uk-list-space';
	break;
	default:
		// Plain
		$listClass = '';
	break;
}

?>
<div class="uk-panel uk-panel-box">
	<?php if ($displayData->params->get($displayData->viewKey.'_sermons_hits')): ?>
		<?php
			$hits_state	= ($displayData->hits > 0) ? true:false;
			$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
			$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
		?>
		<div class="uk-panel-badge uk-badge <?php echo $badge_class; ?>">
			<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?>
		</div>
	<?php endif ;?>
	<?php echo LayoutHelper::render('isnew', $displayData); ?>
	<h3 class="uk-panel-title">
		<a href="<?php echo $displayData->link; ?>" title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_OPEN'); ?> <?php echo $displayData->name; ?>">
			<?php echo $displayData->name; ?>
		</a>
	</h3>
	<?php if ($displayData->params->get($displayData->viewKey.'_sermons_icon')): ?>
		<?php $displayData->icon = ($displayData->icon) ? $displayData->icon : $displayData->params->get('sermon_default_icon'); ?>
		<?php if ($displayData->icon): ?>
			<a href="<?php echo $displayData->link; ?>" title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_OPEN_SERMON'); ?> <?php echo $displayData->name; ?>">
				<img class="uk-thumbnail uk-thumbnail-expand" src="<?php echo $displayData->icon; ?>" alt="<?php echo $displayData->name; ?>">
			</a>
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_desc') && ($displayData->desc || $displayData->short_description)): ?>
				<hr />
			<?php elseif ($displayData->viewKey == 'series' && ((!$displayData->params->get($displayData->viewKey.'_sermons_preacher') || !$displayData->preacher_name) && (!$displayData->params->get($displayData->viewKey.'_sermons_category') || !$displayData->category))): ?>
				<hr />
			<?php elseif ($displayData->viewKey == 'preacher' && ((!$displayData->params->get($displayData->viewKey.'_sermons_series') || !$displayData->series_name) && (!$displayData->params->get($displayData->viewKey.'_sermons_category') || !$displayData->category))): ?>
				<hr />
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($displayData->params->get($displayData->viewKey.'_sermons_desc') && $displayData->short_description): ?>
		<?php echo $displayData->short_description; ?>
	<?php elseif ($displayData->params->get($displayData->viewKey.'_sermons_desc') && $displayData->desc): ?>
		<?php echo $displayData->desc; ?>
	<?php endif; ?>
	<?php if ($displayData->viewKey == 'preacher' && (($displayData->params->get($displayData->viewKey.'_sermons_series') && $displayData->series_name) || ($displayData->params->get($displayData->viewKey.'_sermons_category') && $displayData->category))): ?>
		<ul class="uk-list<?php echo $listClass; ?>">
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_series') && $displayData->series_name): ?>
				<li><?php echo Text::_('COM_SERMONDISTRIBUTOR_SERIES'); ?>: <a href="<?php echo $displayData->series_link; ?>" data-uk-tooltip title="<?php echo $displayData->series_name; ?>"><?php echo $displayData->series_name; ?></a></li>
			<?php endif; ?>
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_category') && $displayData->category): ?>
				<li><?php echo Text::_('COM_SERMONDISTRIBUTOR_CATEGORY'); ?>: <a href="<?php echo $displayData->category_link; ?>" data-uk-tooltip title="<?php echo $displayData->category; ?>"><?php echo $displayData->category; ?></a></li>
			<?php endif; ?>
		</ul>			
	<?php elseif ($displayData->viewKey == 'series' && (($displayData->params->get($displayData->viewKey.'_sermons_preacher') && $displayData->preacher_name) || ($displayData->params->get($displayData->viewKey.'_sermons_category') && $displayData->category))): ?>
		<ul class="uk-list<?php echo $listClass; ?>">
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_preacher') && $displayData->preacher_name): ?>
				<li><?php echo Text::_('COM_SERMONDISTRIBUTOR_PREACHER'); ?>: <a href="<?php echo $displayData->preacher_link; ?>" data-uk-tooltip title="<?php echo $displayData->preacher_name; ?>"><?php echo $displayData->preacher_name; ?></a></li>
			<?php endif; ?>
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_category') && $displayData->category): ?>
				<li><?php echo Text::_('COM_SERMONDISTRIBUTOR_CATEGORY'); ?>: <a href="<?php echo $displayData->category_link; ?>" data-uk-tooltip title="<?php echo $displayData->category; ?>"><?php echo $displayData->category; ?></a></li>
			<?php endif; ?>
		</ul>			
	<?php elseif ($displayData->viewKey == 'category' && (($displayData->params->get($displayData->viewKey.'_sermons_series') && $displayData->series_name) || ($displayData->params->get($displayData->viewKey.'_sermons_preacher') && $displayData->preacher_name))): ?>
		<ul class="uk-list<?php echo $listClass; ?>">
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_preacher') && $displayData->preacher_name): ?>
				<li><?php echo Text::_('COM_SERMONDISTRIBUTOR_PREACHER'); ?>: <a href="<?php echo $displayData->preacher_link; ?>" data-uk-tooltip title="<?php echo $displayData->preacher_name; ?>"><?php echo $displayData->preacher_name; ?></a></li>
			<?php endif; ?>
			<?php if ($displayData->params->get($displayData->viewKey.'_sermons_series') && $displayData->series_name): ?>
				<li><?php echo Text::_('COM_SERMONDISTRIBUTOR_SERIES'); ?>: <a href="<?php echo $displayData->series_link; ?>" data-uk-tooltip title="<?php echo $displayData->series_name; ?>"><?php echo $displayData->series_name; ?></a></li>
			<?php endif; ?>
		</ul>
	<?php elseif (($displayData->params->get($displayData->viewKey.'_sermons_desc') && ($displayData->desc || $displayData->short_description)) && (($displayData->params->get($displayData->viewKey.'_sermons_open') || ($displayData->params->get('add_to_button') && isset($displayData->dropbox_buttons))) || ($displayData->params->get($displayData->viewKey.'_sermons_downloads') && isset($displayData->download_links)))): ?>
		<hr />
	<?php endif; ?>
	<?php if (($displayData->params->get($displayData->viewKey.'_sermons_open') || ($displayData->params->get('add_to_button') && isset($displayData->dropbox_buttons))) && ($displayData->params->get($displayData->viewKey.'_sermons_downloads') && isset($displayData->download_links))): ?>
		<?php echo LayoutHelper::render('opensermonbutton', $displayData); ?>
		<?php echo LayoutHelper::render('downloadsermonbutton', $displayData); ?>
	<?php elseif ($displayData->params->get($displayData->viewKey.'_sermons_open') || ($displayData->params->get('add_to_button') && isset($displayData->dropbox_buttons))): ?>
		<?php echo LayoutHelper::render('opensermonbutton', $displayData); ?>
	<?php endif; ?>
</div>
