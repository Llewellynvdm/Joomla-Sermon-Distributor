<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

?>
 <?php if ($this->series): ?>
	<?php if ($this->params->get('series_display') == 1) : ?>
		<div class="uk-grid" data-uk-grid-match="{target:'.uk-panel'}"><?php echo $this->loadTemplate('seriespanel'); ?></div>
	<?php elseif ($this->params->get('series_display') == 2) : ?>
		<?php echo $this->loadTemplate('seriesbox'); ?>
	<?php else: ?>
		<?php echo $this->loadTemplate('seriessmall'); ?>
	<?php endif; ?>
<?php else: ?>
	<div class="uk-alert uk-alert-warning" data-uk-alert>
		<a href="" class="uk-alert-close uk-close"></a>
		<p><?php echo JText::_('COM_SERMONDISTRIBUTOR_NO_SERIES_WAS_FOUND'); ?></p>
	</div>
<?php endif; ?>
<?php if ($this->items): ?>
	<?php if ($this->params->get('series_sermons_display') == 1) : ?>
		<?php echo $this->loadTemplate('sermons-table'); ?>
	<?php elseif ($this->params->get('series_sermons_display') == 2) : ?>
		<?php echo $this->loadTemplate('sermons-grid'); ?>
	<?php else: ?>
		<?php echo $this->loadTemplate('sermons-list'); ?>
	<?php endif; ?>
	<script>
	function sermonCounter(key,filename)
	{
		var getUrl = "index.php?option=com_sermondistributor&task=ajax.countDownload&format=json";
		if (key.length > 0 && filename.length > 0)
		{
			var request = 'token=<?php echo JSession::getFormToken(); ?>&key='+key+'&filename='+filename;
		}
		return jQuery.ajax({
			type: 'GET',
			url: getUrl,
			dataType: 'jsonp',
			data: request,
			jsonp: 'callback'
		});
	}
	</script>
<?php else: ?>
	<div class="uk-alert uk-alert-warning" data-uk-alert>
		<a href="" class="uk-alert-close uk-close"></a>
		<p><?php echo JText::_('COM_SERMONDISTRIBUTOR_NO_SERMONS_WERE_FOUND'); ?></p>
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
