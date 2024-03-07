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
	@subpackage		default_preacherbox.php
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
$style = $this->params->get('preacher_list_style');
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
$contrast = $this->params->get('preacher_box_contrast');
$contrastClass = '';
if ($contrast)
{
	$contrastClass = 'uk-text-contrast';
}

// set Panal Size
$mediumMain = '1-4';
if ((($this->params->get('preacher_website') && $this->preacher->website) || ($this->params->get('preacher_email') && $this->preacher->email)|| $this->params->get('preacher_sermon_count') || $this->params->get('preacher_sermons_download_counter'))
	&& ($this->params->get('preacher_desc') && $this->preacher->description))
{
	$mediumDesc = '2-4';
	$mediumMid = '1-4';
}
elseif ((($this->params->get('preacher_website') && $this->preacher->website) || ($this->params->get('preacher_email') && $this->preacher->email)|| $this->params->get('preacher_sermon_count') || $this->params->get('preacher_sermons_download_counter')) 
	|| ($this->params->get('preacher_desc') && $this->preacher->description))
{
	$mediumDesc = '3-4';
	$mediumMid = '3-4';
}
elseif (!$this->params->get('preacher_icon'))
{
	$mediumMain = '1-1';
}

?>
<div class="uk-block uk-block-primary">
	<div class="uk-container">
	<h1 class="<?php echo $contrastClass; ?>"><?php echo $this->preacher->name; ?></h1>
	<div class="uk-grid" data-uk-grid-margin="{target:'.uk-panel'}">
	<div class="uk-width-medium-<?php echo $mediumMain; ?>">
		<div class="uk-panel">
		<?php if ($this->params->get('preacher_hits')): ?>
			<?php
				$hits_state	= ($this->preacher->hits > 0) ? true:false;
				$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
				$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
			?>
			<div class="uk-panel-badge uk-badge <?php echo $badge_class; ?>">
				<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
				<?php echo Text::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $this->preacher->hits; ?>
			</div>
		<?php endif ;?>
		<?php if ($this->params->get('preacher_icon')): ?>
			<?php $this->preacher->icon = ($this->preacher->icon) ? $this->preacher->icon : $this->params->get('preacher_default_icon'); ?>
			<?php if ($this->preacher->icon): ?>
				<img class="uk-thumbnail uk-thumbnail-expand" src="<?php echo $this->preacher->icon; ?>" alt="<?php echo $this->preacher->name; ?>">
			<?php endif; ?>
		<?php endif; ?>
		</div>
	</div>
	<?php if (($this->params->get('preacher_website') && $this->preacher->website) || ($this->params->get('preacher_email') && $this->preacher->email) || $this->params->get('preacher_sermons_download_counter') || $this->params->get('preacher_sermon_count')): ?>
		<div class="uk-width-medium-<?php echo $mediumMid; ?>">
			<div class="uk-panel">
				<ul class="uk-list<?php echo $listClass; ?>">
					<?php if ($this->params->get('preacher_sermon_count')): ?>
						<li class="<?php echo $contrastClass; ?>"><?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>: <?php echo $this->sermonTotal; ?></li>
					<?php endif; ?>
					<?php if ($this->params->get('preacher_sermons_download_counter')): ?>
						<li class="<?php echo $contrastClass; ?>"><?php echo Text::_('COM_SERMONDISTRIBUTOR_TOTAL_DOWNLOADS'); ?>: <?php echo $this->downloadTotal; ?></li>
					<?php endif; ?>
					<?php if ($this->params->get('preacher_website') && $this->preacher->website): ?>
						<li><i class="uk-icon-external-link"></i> <a class="<?php echo $contrastClass; ?>" href="<?php echo $this->preacher->website; ?>" target="_blank" data-uk-tooltip title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_GO_TO_WEBSITE_OF'); ?> <?php echo $this->preacher->name; ?>"><?php echo $this->preacher->website; ?></a></li>
					<?php endif; ?>
					<?php if ($this->params->get('preacher_email') && $this->preacher->email): ?>
						<li><i class="uk-icon-envelope-o"></i> <a class="<?php echo $contrastClass; ?>" href="mailto:<?php echo $this->preacher->email; ?>" data-uk-tooltip title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_SEND_EMAIL_TO'); ?> <?php echo $this->preacher->name; ?>"><?php echo $this->preacher->email; ?></a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>
	<?php if ($this->params->get('preacher_desc') && $this->preacher->description): ?>
		<div class="uk-width-medium-<?php echo $mediumDesc; ?>">
			<div class="uk-panel <?php echo $contrastClass; ?>">
				<?php echo $this->preacher->description; ?>
			</div>
		</div>
	<?php endif; ?>
	</div>
	</div>
</div>
