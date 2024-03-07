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
	@subpackage		default.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
use TrueChristianChurch\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use VDM\Joomla\Utilities\ArrayHelper;
use VDM\Joomla\Utilities\StringHelper;

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('keepalive')->useScript('form.validate');
Html::_('bootstrap.tooltip');

// No direct access to this file
defined('_JEXEC') or die;

// get the needed module for translation
$model = SermondistributorHelper::getModel('external_sources');

?>
<?php if ($this->canDo->get('manual_updater.access')): ?>
<script type="text/javascript">
	Joomla.submitbutton = function(task) {
		if (task === 'manual_updater.back') {
			parent.history.back();
			return false;
		} else {
			var form = document.getElementById('adminForm');
			form.task.value = task;
			form.submit();
		}
	}
</script>
<form action="<?php echo Route::_('index.php?option=com_sermondistributor&view=manual_updater'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

<?php if (isset($this->items) && ArrayHelper::check($this->items)): ?>
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
	<?php echo Html::_('bootstrap.startAccordion', 'dashboard'); ?>
		<?php foreach ($this->items as $item): ?>
			<?php $key = StringHelper::safe($item->description).$item->id; ?>
			<?php $name = '<div id="heading_'. $key .'">'. $item->description . ' <small>(' . Text::_($model->selectionTranslation($item->build, 'build')) . ')</small></div>'; ?>
			<?php echo Html::_('bootstrap.addSlide', 'dashboard', $name, $key); ?>
				<div class="uk-form-row">
					<label class="uk-form-label" >
						<?php echo Text::_('COM_SERMONDISTRIBUTOR_ADD_THE_STRONGAPP_GENERATED_ACCESS_TOKENSTRONG_HERE'); ?>
					</label>
					<input 
						class="span12"
						type="text"
						id="sleutel-<?php echo $item->id; ?>"
						placeholder="<?php echo Text::_('COM_SERMONDISTRIBUTOR_ADD_TOKEN_HERE'); ?>"
					> 
				</div>
				<div class="alert alert-info">
					<p><?php echo Text::_('COM_SERMONDISTRIBUTOR_BY_USING_AN_ACCESS_TOKEN_YOU_WILL_BE_ABLE_TO_MAKE_API_CALLS_FOR_YOUR_OWN_ACCOUNT_WITHOUT_GOING_THROUGH_THE_AUTHORIZATION_FLOW_DURING_THIS_MANUAL_UPDATE_OF_THE_LOCAL_LISTINGBR_THE_TOKEN_WILL_NOT_BE_STORED_AND_FOR_SAFETY_THE_TOKEN_WILL_BE_REVOKED_ONCE_THE_UPDATE_IS_COMPLETED_SUCCESSFULLYBR_BTHIS_WILL_MEAN_IT_CAN_ONLY_BE_USED_THIS_ONCEB_YOU_WOULD_NEED_A_NEW_TOKEN_EACH_TIME_YOU_RUN_THIS_UPDATE_MANUALLY_BR_SMALLMAKE_SURE_TO_HAVE_SSL_INPLACE_ON_THIS_PAGE_WHEN_DOING_THIS_UPDATE_AS_AN_EXTRA_SECURITY_MEASURESMALL'); ?></p>
				</div>
				<?php $targets = SermondistributorHelper::getExternalListingUpdateKeys($item->id, 1, 2); ?>
				<?php if (ArrayHelper::check($targets)) : ?>
					<?php foreach($targets as $target): ?>
					<?php $error = SermondistributorHelper::getUpdateError(0, $item->id.$target.$item->build); ?>
					<?php if (StringHelper::check($error)): ?>
						<?php $errorKey = StringHelper::safe($item->id.$target.$item->build); ?>
						<a class="btn btn-danger" href="#<?php echo 'error'.$errorKey; ?>" data-toggle="modal"><?php echo Text::_('COM_SERMONDISTRIBUTOR_VIEW_ERROR'); ?></a>
						<?php echo Html::_('bootstrap.renderModal', 'error'.$errorKey, array('title' => Text::_('COM_SERMONDISTRIBUTOR_THERE_WAS_AN_ERROR_DURING_THE_LAST_UPDATE_ATTEMPT')), $error); ?>
					<?php endif; ?>
					<button onclick="updateLocalLinks('sleutel-<?php echo $item->id; ?>', <?php echo $target; ?>, <?php echo $item->id; ?>, <?php echo $item->build; ?>)" class="btn btn-big">
						<span class="icon-cog"></span>
							<?php echo Text::sprintf('COM_SERMONDISTRIBUTOR_UPDATE_LOCAL_LINKS_OF_TARGET_S_EXTERNAL_SOURCE', $target); ?>
					</button>
					<?php endforeach; ?>
				<?php else: ?>
					<?php echo Text::_('COM_SERMONDISTRIBUTOR_THERE_HAS_NO_TARGETS_BEEN_SET_PLEASE_MAKE_SURE_TO_ADD_TARGETS_TO_THIS_EXTERNAL_SOURCE'); ?>
				<?php endif; ?>
			<?php echo Html::_('bootstrap.endSlide'); ?>
		<?php endforeach; ?>
	<?php echo Html::_('bootstrap.endAccordion'); ?>
</div>
<div id="uploader" style="display:none;">
	<center><h1><?php echo Text::_('COM_SERMONDISTRIBUTOR_THE_UPDATE_IS_RUNNING'); ?><span class="loading-dots">.</span></h1><p><b>You can close the browser window!</b><br />This update will continue to run in the background on the server (and can take hours).<br />Check again latter since time of completion, or any errors will be logged and displayed here.</p></center>
</div>
<script>
// token 
var token = '<?php echo \JSession::getFormToken(); ?>';
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
	return false;
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
		<p><?php echo Text::_('COM_SERMONDISTRIBUTOR_NO_MANUAL_UPDATES_AVAILABLE'); ?></p>
	</div>
<?php endif; ?>
<input type="hidden" name="task" value="" />
<?php echo Html::_('form.token'); ?>
</form>
<?php else: ?>
		<h1><?php echo Text::_('COM_SERMONDISTRIBUTOR_NO_ACCESS_GRANTED'); ?></h1>
<?php endif; ?>
