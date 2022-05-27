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

	@version		2.1.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');



// Set the heading of the page
$heading = ($this->params->get('page_heading')) ? $this->params->get('page_heading'):(isset($this->menu->title)) ? $this->menu->title:'';
?>
<form action="<?php echo JRoute::_('index.php?option=com_sermondistributor'); ?>" method="post" name="adminForm" id="adminForm">
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

<?php if (isset($this->items) && isset($this->pagination) && isset($this->pagination->pagesTotal) && $this->pagination->pagesTotal > 1): ?>
	<div class="pagination">
		<?php if ($this->params->def('show_pagination_results', 1)) : ?>
			<p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> <?php echo $this->pagination->getLimitBox(); ?></p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?>
	</div>
<?php endif; ?><?php echo $this->toolbar->render(); ?>
<input type="hidden" name="task" value="" />
<?php echo JHtml::_('form.token'); ?>
</form>
