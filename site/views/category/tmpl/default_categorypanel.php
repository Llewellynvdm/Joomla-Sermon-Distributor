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
	@subpackage		default_categorypanel.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// build the list class
$style = $this->params->get('category_list_style');
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
if (($this->params->get('category_sermon_count') || $this->params->get('category_sermons_download_counter'))
	&& ($this->params->get('category_desc') && $this->category->description))
{
	$mediumDesc = '2-4';
	$mediumMid = '1-4';
}
elseif (($this->params->get('category_sermon_count') || $this->params->get('category_sermons_download_counter')) 
	|| ($this->params->get('category_desc') && $this->category->description))
{
	$mediumDesc = '3-4';
	$mediumMid = '3-4';
}
elseif (!$this->params->get('category_icon'))
{
	$mediumMain = '1-1';
}

?>
<div class="uk-width-medium-<?php echo $mediumMain; ?>">
	<div class="uk-panel uk-panel-box">
	<?php if ($this->params->get('category_hits')): ?>
		<?php
			$hits_state	= ($this->category->hits > 0) ? true:false;
			$badge_class	= ($hits_state) ? 'uk-badge-success':'uk-badge-warning';
			$badge_icon	= ($hits_state) ? 'check-circle':'minus-circle';
		?>
		<div class="uk-panel-badge uk-badge <?php echo $badge_class; ?>">
			<i class="uk-icon-<?php echo $badge_icon; ?>"></i>
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $this->category->hits; ?>
		</div>
	<?php endif ;?>
	<h1 class="uk-panel-title"><?php echo $this->category->name; ?></h1>
	<?php if ($this->params->get('category_icon')): ?>
		<?php $this->category->icon = (isset($this->category->icon) && $this->category->icon) ? $this->category->icon : $this->params->get('category_default_icon'); ?>
		<?php if ($this->category->icon): ?>
			<img class="uk-thumbnail uk-thumbnail-expand" src="<?php echo $this->category->icon; ?>" alt="<?php echo $this->category->name; ?>">
		<?php endif; ?>
	<?php endif; ?>
	</div>
</div>
<?php if ($this->params->get('category_sermon_count') || $this->params->get('category_sermons_download_counter')): ?>
	<div class="uk-width-medium-<?php echo $mediumMid; ?>">
		<div class="uk-panel uk-panel-box">
			<ul class="uk-list<?php echo $listClass; ?>">
				<?php if ($this->params->get('category_sermon_count')): ?>
					<li><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>: <?php echo $this->sermonTotal; ?></li>
				<?php endif; ?>
				<?php if ($this->params->get('category_sermons_download_counter')): ?>
					<li><?php echo JText::_('COM_SERMONDISTRIBUTOR_TOTAL_DOWNLOADS'); ?>: <?php echo $this->downloadTotal; ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>
<?php if ($this->params->get('category_desc') && $this->category->description): ?>
	<div class="uk-width-medium-<?php echo $mediumDesc; ?>">
		<div class="uk-panel uk-panel-box">
			<?php echo $this->category->description; ?>
		</div>
	</div>
<?php endif; ?>
