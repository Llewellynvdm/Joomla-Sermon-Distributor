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
	@build			10th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_sermonpanel.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

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
<div class="uk-width-medium-<?php echo $mediumMain; ?>">
	<div class="uk-panel uk-panel-box">
	<?php if ($this->params->get('sermon_hits')): ?>
		<?php
			$hits_state	= ($this->item->hits > 0) ? true:false;
			$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
			$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
		?>
		<div class="uk-panel-badge uk-badge <?php echo $badge_class; ?>">
			<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $this->item->hits; ?>
		</div>
	<?php endif ;?>
	<h1 class="uk-panel-title"><?php echo $this->item->name; ?></h1>
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
		<div class="uk-panel uk-panel-box">
			<ul class="uk-list<?php echo $listClass; ?>">
				<?php if ($this->params->get('sermon_download_counter')): ?>
					<li><?php echo JText::_('COM_SERMONDISTRIBUTOR_TOTAL_DOWNLOADS'); ?>: <?php echo $this->item->statisticTotal; ?></li>
				<?php endif; ?>
				<?php if ($this->params->get('sermon_preacher') && $this->item->preacher): ?>
					<li><?php echo JText::_('COM_SERMONDISTRIBUTOR_PREACHER'); ?>: <a href="<?php echo $this->item->preacher_link; ?>" data-uk-tooltip title="<?php echo $this->item->preacher_name; ?>"><?php echo $this->item->preacher_name; ?></a></li>
				<?php endif; ?>
				<?php if ($this->params->get('sermon_series') && $this->item->series): ?>
					<li><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERIES'); ?>: <a href="<?php echo $this->item->series_link; ?>" data-uk-tooltip title="<?php echo $this->item->series_name; ?>"><?php echo $this->item->series_name; ?></a></li>
				<?php endif; ?>
				<?php if ($this->params->get('sermon_category') && $this->item->category): ?>
					<li><?php echo JText::_('COM_SERMONDISTRIBUTOR_CATEGORY'); ?>: <a href="<?php echo $this->item->category_link; ?>" data-uk-tooltip title="<?php echo $this->item->category; ?>"><?php echo $this->item->category; ?></a></li>
				<?php endif; ?>
				<?php if ($this->params->get('sermon_downloads')): ?>
					<li><?php echo JLayoutHelper::render('downloadsermonbutton', $this->item); ?></li>
				<?php endif; ?>
				<?php if ($this->params->get('add_to_dropbox') && isset($this->item->dropbox_buttons)): ?>
					<li><?php echo JLayoutHelper::render('addtodropboxbutton', $this->item); ?></li>
				<?php endif; ?>
					<?php echo JLayoutHelper::render('mediaplayer', $this->item); ?>
			</ul>
		</div>
	</div>
<?php endif; ?>
<?php if ($this->params->get('sermon_desc') && ($this->item->description || $this->item->short_description)): ?>
	<div class="uk-width-medium-<?php echo $mediumDesc; ?>">
		<div class="uk-panel uk-panel-box">
			<?php if ($this->item->description): ?>
				<?php echo $this->item->description; ?>
			<?php elseif ($this->item->short_description): ?>
				<?php echo $this->item->short_description; ?>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
