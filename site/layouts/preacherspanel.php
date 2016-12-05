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

	@version		1.4.0
	@build			4th December, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		preacherspanel.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');

// build the list class
$style = $displayData->params->get('preachers_list_style');
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
	<?php if ($displayData->params->get('preachers_hits')): ?>
		<?php
			$hits_state	= ($displayData->hits > 0) ? true:false;
			$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
			$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
		?>
		<div class="uk-panel-badge uk-badge <?php echo $badge_class; ?>">
			<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?>
		</div>
	<?php endif ;?>
	<h3 class="uk-panel-title">
		<a href="<?php echo $displayData->link; ?>" title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?> <?php echo $displayData->name; ?>">
			<?php echo $displayData->name; ?>
		</a>
	</h3>
	<?php if ($displayData->params->get('preachers_icon')): ?>
		<?php $displayData->icon = ($displayData->icon) ? $displayData->icon : $displayData->params->get('preacher_default_icon'); ?>
		<?php if ($displayData->icon): ?>
			<a href="<?php echo $displayData->link; ?>" title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?> <?php echo $displayData->name; ?>">
			<img class="uk-thumbnail uk-thumbnail-expand" src="<?php echo $displayData->icon; ?>" alt="<?php echo $displayData->name; ?>">
			</a>
			<?php if ($displayData->params->get('preachers_desc') && $displayData->desc): ?>
				<hr />
			<?php elseif ((!$displayData->params->get('preachers_website') || !$displayData->website) && (!$displayData->params->get('preachers_email') || !$displayData->email)): ?>
				<hr />
			<?php endif; ?>
		<?php endif; ?>
	<?php endif; ?>
	<?php if ($displayData->params->get('preachers_desc') && $displayData->desc): ?>
		<?php echo $displayData->desc; ?>
	<?php endif; ?>
	<?php if (($displayData->params->get('preachers_website') && $displayData->website) || ($displayData->params->get('preachers_email') && $displayData->email)): ?>
		<ul class="uk-list<?php echo $listClass; ?>">
			<?php if ($displayData->params->get('preachers_website') && $displayData->website): ?>
				<li><i class="uk-icon-external-link"></i> <a href="<?php echo $displayData->website; ?>" target="_blank" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_GO_TO_WEBSITE_OF'); ?> <?php echo $displayData->name; ?>"><?php echo $displayData->website; ?></a></li>
			<?php endif; ?>
			<?php if ($displayData->params->get('preachers_email') && $displayData->email): ?>
				<li><i class="uk-icon-envelope-o"></i> <a href="mailto:<?php echo $displayData->email; ?>" data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SEND_EMAIL_TO'); ?> <?php echo $displayData->name; ?>"><?php echo $displayData->email; ?></a></li>
			<?php endif; ?>
		</ul>
	<?php elseif ($displayData->params->get('preachers_desc') && $displayData->desc): ?>
		<hr />
	<?php endif; ?>
	<a class="uk-button uk-width-1-1 uk-margin-small-bottom uk-button-success" href="<?php echo $displayData->link; ?>" title="<?php echo $displayData->name; ?>">
		<?php echo JText::_('COM_SERMONDISTRIBUTOR_OPEN'); ?>
		<?php if ($displayData->params->get('preachers_sermon_count')): ?>
			<span data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>">(<?php echo count($displayData->idPreacherSermonB); ?>)</span>
		<?php endif; ?>
	</a>
</div>
