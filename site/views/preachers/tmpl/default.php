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
	@subpackage		default.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// Set the heading of the page
$heading = ($this->params->get('page_heading')) ? $this->params->get('page_heading'):(isset($this->menu->title)) ? $this->menu->title:'';

?>
 <?php if ($this->params->get('show_page_heading')): ?>
	<h1 class="uk-text-primary"><?php echo $heading; ?></h1>
<?php endif; ?>
<?php if ($this->items): ?>
	<?php if ($this->params->get('preachers_display') == 1) : ?>
		<?php echo $this->loadTemplate('preachers-table'); ?>
	<?php elseif ($this->params->get('preachers_display') == 2) : ?>
		<?php echo $this->loadTemplate('preachers-grid'); ?>
	<?php else: ?>
		<?php echo $this->loadTemplate('preachers-list'); ?>
	<?php endif; ?>
<?php else: ?>
	<div class="uk-alert uk-alert-warning" data-uk-alert>
		<a href="" class="uk-alert-close uk-close"></a>
		<p><?php echo JText::_('COM_SERMONDISTRIBUTOR_NO_PREACHERS_WERE_FOUND'); ?></p>
	</div>
<?php endif; ?>

<?php if (isset($this->items) && sermondistributorHelper::checkArray($this->items) && count($this->items) > 4): ?>
<form name="adminForm" method="post">
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> <?php echo $this->pagination->getLimitBox(); ?></p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
</form>
<?php endif; ?> <?php echo $this->toolbar->render(); ?>
