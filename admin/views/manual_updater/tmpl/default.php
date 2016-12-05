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
	@subpackage		default.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

// get the needed module for translation
$model = SermondistributorHelper::getModel('external_sources');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
?>
<?php if ($this->canDo->get('manual_updater.access')): ?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task == 'manual_updater.back') {
			parent.history.back();
			return false;
		} else {
			var form = document.getElementById('adminForm');
			form.task.value = task;
			form.submit();
		}
	}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_sermondistributor&view=manual_updater'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
</form>
<?php if (isset($this->items) && SermondistributorHelper::checkArray($this->items)): ?>
<script type="text/javascript">
// Add spindle-wheel for update:
jQuery(document).ready(function($) {
	// waiting spinner
	var outerDiv = jQuery('body');
	jQuery('<div id="loading"></div>')
		.css("background", "rgba(255, 255, 255, .8) url('components/com_sermondistributor/assets/images/import.gif') 50% 15% no-repeat")
		.css("top", outerDiv.position().top - jQuery(window).scrollTop())
		.css("left", outerDiv.position().left - jQuery(window).scrollLeft())
		.css("width", outerDiv.width())
		.css("height", outerDiv.height())
		.css("position", "fixed")
		.css("opacity", "0.80")
		.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
		.css("filter", "alpha(opacity = 80)")
		.css("display", "none")
		.appendTo(outerDiv);
	jQuery('#loading').show();
	// when page is ready remove and show
	jQuery(window).load(function() {
		jQuery('#sermondistributor_loader').fadeIn('fast');
		jQuery('#loading').hide();
	});
});
</script>
<div id="sermondistributor_loader">
	<?php echo JHtml::_('bootstrap.startAccordion', 'dashboard'); ?>
		<?php foreach ($this->items as $item): ?>
			<?php $key = SermondistributorHelper::safeString($item->description).$item->id; ?>
			<?php $name = '<div id="heading_'. $key .'">'. $item->description . ' <small>(' . JText::_($model->selectionTranslation($item->build, 'build')) . ')</small></div>'; ?>
			<?php echo JHtml::_('bootstrap.addSlide', 'dashboard', $name, $key); ?>
				<div class="uk-form-row">
					<label class="uk-form-label" >
						<?php echo JText::_('COM_SERMONDISTRIBUTOR_ADD_THE_STRONGAPP_GENERATED_ACCESS_TOKENSTRONG_HERE'); ?>
					</label>
					<input 
						class="span12"
						type="text"
						id="sleutel-<?php echo $item->id; ?>"
						placeholder="<?php echo JText::_('COM_SERMONDISTRIBUTOR_ADD_TOKEN_HERE'); ?>"
					> 
				</div>
				<div class="alert alert-info">
					<p><?php echo JText::_('COM_SERMONDISTRIBUTOR_BY_USING_AN_ACCESS_TOKEN_YOU_WILL_BE_ABLE_TO_MAKE_API_CALLS_FOR_YOUR_OWN_ACCOUNT_WITHOUT_GOING_THROUGH_THE_AUTHORIZATION_FLOW_DURING_THIS_MANUAL_UPDATE_OF_THE_LOCAL_LISTINGBR_THE_TOKEN_WILL_NOT_BE_STORED_AND_FOR_SAFETY_THE_TOKEN_WILL_BE_REVOKED_ONCE_THE_UPDATE_IS_COMPLETED_SUCCESSFULLYBR_BTHIS_WILL_MEAN_IT_CAN_ONLY_BE_USED_THIS_ONCEB_YOU_WOULD_NEED_A_NEW_TOKEN_EACH_TIME_YOU_RUN_THIS_UPDATE_MANUALLY_BR_SMALLMAKE_SURE_TO_HAVE_SSL_INPLACE_ON_THIS_PAGE_WHEN_DOING_THIS_UPDATE_AS_AN_EXTRA_SECURITY_MEASURESMALL'); ?></p>
				</div>
				<?php $targets = SermondistributorHelper::getExternalListingUpdateKeys($item->id, 1, 2); ?>
				<?php if (SermondistributorHelper::checkArray($targets)) : ?>
					<?php foreach($targets as $target): ?>
					<?php $error = SermondistributorHelper::getUpdateError(0, $item->id.$target.$item->build); ?>
					<?php if (SermondistributorHelper::checkString($error)): ?>
						<?php $errorKey = SermondistributorHelper::safeString($item->id.$target.$item->build); ?>
						<a class="btn btn-danger" href="#<?php echo 'error'.$errorKey; ?>" data-toggle="modal"><?php echo JText::_('COM_SERMONDISTRIBUTOR_VIEW_ERROR'); ?></a>
						<?php echo JHtml::_('bootstrap.renderModal', 'error'.$errorKey, array('title' => JText::_('COM_SERMONDISTRIBUTOR_THERE_WAS_AN_ERROR_DURING_THE_LAST_UPDATE_ATTEMPT')), $error); ?>
					<?php endif; ?>
					<button onclick="updateLocalLinks('sleutel-<?php echo $item->id; ?>', <?php echo $target; ?>, <?php echo $item->id; ?>, <?php echo $item->build; ?>)" class="btn btn-big">
						<span class="icon-cog"></span>
							<?php echo JText::sprintf('COM_SERMONDISTRIBUTOR_UPDATE_LOCAL_LINKS_OF_TARGET_S_EXTERNAL_SOURCE', $target); ?>
					</button>
					<?php endforeach; ?>
				<?php else: ?>
					<?php echo JText::_('COM_SERMONDISTRIBUTOR_THERE_HAS_NO_TARGETS_BEEN_SET_PLEASE_MAKE_SURE_TO_ADD_TARGETS_TO_THIS_EXTERNAL_SOURCE'); ?>
				<?php endif; ?>
			<?php echo JHtml::_('bootstrap.endSlide'); ?>
		<?php endforeach; ?>
	<?php echo JHtml::_('bootstrap.endAccordion'); ?>
</div>
<div id="uploader" style="display:none;">
	<center><h1><?php echo JText::_('COM_SERMONDISTRIBUTOR_THE_UPDATE_IS_RUNNING'); ?><span class="loading-dots">.</span></h1><p><b>You can close the browser window!</b><br />This update will continue to run in the background on the server (and can take hours).<br />Check again latter since time of completion, or any errors will be logged and displayed here.</p></center>
</div>
<script>
// token 
var token = '<?php echo JSession::getFormToken(); ?>';
// nice little dot trick :)
jQuery(document).ready( function($) {
  var x=0;
  setInterval(function() {
	var dots = "";
	x++;
	for (var y=0; y < x%8; y++) {
		dots+=".";
	}
	$(".loading-dots").text(dots);
  } , 500);
});
function updateLocalLinks(fieldId, target, id, type)
{
	var sleutel = jQuery('#'+fieldId).val();
	jQuery('#error-key').remove();
	if(sleutel === '') { 
		jQuery('#'+fieldId).closest('div').prepend('<span id="error-key" style="color:red;"><b>No token was added, please add the token and try again.</b></span>');
	} else {
		jQuery('#sermondistributor_loader').hide();
		jQuery('#loading').css('display', 'block');
		jQuery('#uploader').show();
		server_updateLocalLinks(target, type, id, sleutel).done(function(result) {
			jQuery('#sermondistributor_loader').show();
			jQuery('#loading').css('display', 'none');
			jQuery('#uploader').hide();
			if (result.success) {	
				var message = ['<h3>The update was successfully completed.</h3>'];
				Joomla.renderMessages({'success': message});
			} else {
				var message = [];
				if (result.error) {
					message.push(result.error);
					message.push('<h3>The update is unable to run, this could be because of the following reasons:</h3>');
				} else {
					message.push('<h3>An unknown error has occurred.</h3>');
				}
				Joomla.renderMessages({'error': message});
			}
		});
	}
}
function server_updateLocalLinks(target, type, id, sleutel)
{
	var getUrl = 'index.php?option=com_sermondistributor&task=ajax.updateLocalListingExternal&format=json';
	if(id > 0 && token.length == 32 && type > 0 && target > 0){
		var request = 'token='+token+'&target='+target+'&type='+type+'&id='+id+'&sleutel='+sleutel;
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
	<div class="uk-alert uk-alert-success" data-uk-alert>
		<p><?php echo JText::_('COM_SERMONDISTRIBUTOR_NO_MANUAL_UPDATES_AVAILABLE'); ?></p>
	</div>
<?php endif; ?>
<?php else: ?>
        <h1><?php echo JText::_('COM_SERMONDISTRIBUTOR_NO_ACCESS_GRANTED'); ?></h1>
<?php endif; ?>
