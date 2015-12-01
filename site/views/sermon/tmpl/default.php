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
 
<?php if ($this->item): ?>
	<?php if ($this->params->get('sermon_display') == 1) : ?>
		<div class="uk-grid" data-uk-grid-match="{target:'.uk-panel'}"><?php echo $this->loadTemplate('sermonpanel'); ?></div>
	<?php elseif ($this->params->get('sermon_display') == 2) : ?>
		<?php echo $this->loadTemplate('sermonbox'); ?>
	<?php else: ?>
		<?php echo $this->loadTemplate('sermonbig'); ?>
	<?php endif; ?>
	<?php if (isset($this->item->scripture) && SermondistributorHelper::checkString($this->item->scripture)): ?>
		<div><?php echo $this->item->scripture; ?></div>
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
	soundManager.setup({
		url: '<?php echo JURI::root(true); ?>/media/com_sermondistributor/soundmanager/swf',
		flashVersion: 9,
		onready: function() {
			// Ready to use; soundManager.createSound() etc. can now be called.
		}
	});
	</script>
<?php else: ?>
	<div class="uk-alert uk-alert-warning" data-uk-alert>
		<a href="" class="uk-alert-close uk-close"></a>
		<p><?php echo JText::_('COM_SERMONDISTRIBUTOR_NO_SERMON_WAS_FOUND'); ?></p>
	</div>
<?php endif; ?> <?php echo $this->toolbar->render(); ?> 
