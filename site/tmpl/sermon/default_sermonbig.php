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
	@subpackage		default_sermonbig.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;

// No direct access to this file
defined('_JEXEC') or die;

// build the list class
$style = $this->params->get('sermon_list_style');
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

// build the contrast text option
$contrast = $this->params->get('sermon_box_contrast');
$contrastClass = '';
if ($contrast)
{
	$contrastClass = 'uk-text-contrast';
}

// set Panal Size
$mediumMain = '1-4';
if (($this->params->get('sermon_download_counter') || $this->params->get('sermon_preacher') || $this->params->get('sermon_series') || $this->params->get('sermon_category') || $this->params->get('sermon_downloads'))
	&& ($this->params->get('sermon_desc') && ($this->item->description || $this->item->short_description)))
{
	$mediumDesc = '2-4';
	$mediumMid = '1-4';
}
elseif (($this->params->get('sermon_download_counter') || $this->params->get('sermon_preacher') || $this->params->get('sermon_series') || $this->params->get('sermon_category') || $this->params->get('sermon_downloads'))
	|| ($this->params->get('sermon_desc') && ($this->item->description || $this->item->short_description)))
{
	$mediumDesc = '3-4';
	$mediumMid = '3-4';
}
elseif (!$this->params->get('sermon_icon'))
{
	$mediumMain = '1-1';
}
// set params to item
$this->item->params = $this->params; 

?>
<div class="uk-block">
	<div class="uk-container">
	<h1 class="<?php echo $contrastClass; ?>"><?php echo $this->item->name; ?></h1>
	<div class="uk-grid" data-uk-grid-margin="{target:'.uk-panel'}">
	<div class="uk-width-medium-<?php echo $mediumMain; ?>">
		<div class="uk-panel">
		<?php if ($this->params->get('sermon_hits')): ?>
			<?php
				$hits_state	= ($this->item->hits > 0) ? true:false;
				$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
				$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
			?>
			<div class="uk-panel-badge uk-badge <?php echo $badge_class; ?>">
				<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
				<?php echo Text::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $this->item->hits; ?>
			</div>
		<?php endif ;?>
		<?php if ($this->params->get('sermon_icon')): ?>
			<?php $this->item->icon = ($this->item->icon) ? $this->item->icon : $this->params->get('sermon_default_icon'); ?>
			<?php if ($this->item->icon): ?>
				<img class="uk-thumbnail uk-thumbnail-expand" src="<?php echo $this->item->icon; ?>" alt="<?php echo $this->item->name; ?>">
			<?php endif; ?>
		<?php endif; ?>
		</div>
	</div>
	<?php if ($this->params->get('sermon_download_counter') || $this->params->get('sermon_preacher') || $this->params->get('sermon_series') || $this->params->get('sermon_category') || $this->params->get('sermon_downloads')): ?>
		<div class="uk-width-medium-<?php echo $mediumMid; ?>">
			<div class="uk-panel">
				<ul class="uk-list<?php echo $listClass; ?>">
					<?php if ($this->params->get('sermon_download_counter')): ?>
						<li class="<?php echo $contrastClass; ?>"><?php echo Text::_('COM_SERMONDISTRIBUTOR_TOTAL_DOWNLOADS'); ?>: <?php echo $this->item->statisticTotal; ?></li>
					<?php endif; ?>
					<?php if ($this->params->get('sermon_preacher') && $this->item->preacher): ?>
						<li class="<?php echo $contrastClass; ?>"><?php echo Text::_('COM_SERMONDISTRIBUTOR_PREACHER'); ?>: <a  class="<?php echo $contrastClass; ?>" href="<?php echo $this->item->preacher_link; ?>" data-uk-tooltip title="<?php echo $this->item->preacher_name; ?>"><?php echo $this->item->preacher_name; ?></a></li>
					<?php endif; ?>
					<?php if ($this->params->get('sermon_series') && $this->item->series): ?>
						<li class="<?php echo $contrastClass; ?>"><?php echo Text::_('COM_SERMONDISTRIBUTOR_SERIES'); ?>: <a  class="<?php echo $contrastClass; ?>" href="<?php echo $this->item->series_link; ?>" data-uk-tooltip title="<?php echo $this->item->series_name; ?>"><?php echo $this->item->series_name; ?></a></li>
					<?php endif; ?>
					<?php if ($this->params->get('sermon_category') && $this->item->category): ?>
						<li class="<?php echo $contrastClass; ?>"><?php echo Text::_('COM_SERMONDISTRIBUTOR_CATEGORY'); ?>: <a  class="<?php echo $contrastClass; ?>" href="<?php echo $this->item->category_link; ?>" data-uk-tooltip title="<?php echo $this->item->category; ?>"><?php echo $this->item->category; ?></a></li>
					<?php endif; ?>
					<?php if ($this->params->get('sermon_downloads')): ?>
						<li><?php echo LayoutHelper::render('downloadsermonbutton', $this->item); ?></li>
					<?php endif; ?>
					<?php if ($this->params->get('add_to_button') && isset($this->item->dropbox_buttons)): ?>
						<li><?php echo LayoutHelper::render('addtodropboxbutton', $this->item); ?></li>
					<?php endif; ?>
						<?php if (1 == $this->item->playerKey): ?>
							<?php echo LayoutHelper::render('mediaplayer', $this->item); ?>
						<?php endif; ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($this->params->get('sermon_desc') && ($this->item->description || $this->item->short_description)): ?>
		<div class="uk-width-medium-<?php echo $mediumDesc; ?>">
			<div class="uk-panel <?php echo $contrastClass; ?>">
				<?php if ($this->item->description): ?>
					<?php echo $this->item->description; ?>
				<?php elseif ($this->item->short_description): ?>
					<?php echo $this->item->short_description; ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	</div>
	</div>
</div>
<?php if (2 == $this->item->playerKey || 3 == $this->item->playerKey): ?>
	<?php echo LayoutHelper::render('mediaplayer', $this->item); ?>
<?php endif; ?>
