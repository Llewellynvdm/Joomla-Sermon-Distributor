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

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		edit.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\Router\Route;
Html::addIncludePath(JPATH_COMPONENT.'/helpers/html');
Html::_('behavior.formvalidator');
Html::_('formbehavior.chosen', 'select');
Html::_('behavior.keepalive');

$componentParams = $this->params; // will be removed just use $this->params instead
?>
<script type="text/javascript">
	// waiting spinner
	var outerDiv = document.querySelector('body');
	var loadingDiv = document.createElement('div');
	loadingDiv.id = 'loading';
	loadingDiv.style.cssText = "background: rgba(255, 255, 255, .8) url('components/com_sermondistributor/assets/images/import.gif') 50% 15% no-repeat; top: " + (outerDiv.getBoundingClientRect().top + window.pageYOffset) + "px; left: " + (outerDiv.getBoundingClientRect().left + window.pageXOffset) + "px; width: " + outerDiv.offsetWidth + "px; height: " + outerDiv.offsetHeight + "px; position: fixed; opacity: 0.80; -ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80); filter: alpha(opacity=80); display: none;";
	outerDiv.appendChild(loadingDiv);
	loadingDiv.style.display = 'block';
	// when page is ready remove and show
	window.addEventListener('load', function() {
		var componentLoader = document.getElementById('sermondistributor_loader');
		if (componentLoader) componentLoader.style.display = 'block';
		loadingDiv.style.display = 'none';
	});
</script>
<div id="sermondistributor_loader" style="display: none;">
<form action="<?php echo Route::_('index.php?option=com_sermondistributor&layout=edit&id='. (int) $this->item->id . $this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

	<?php echo LayoutHelper::render('help_document.details_above', $this); ?>
<div class="form-horizontal">

	<?php echo Html::_('bootstrap.startTabSet', 'help_documentTab', array('active' => 'details')); ?>

	<?php echo Html::_('bootstrap.addTab', 'help_documentTab', 'details', Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo LayoutHelper::render('help_document.details_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo LayoutHelper::render('help_document.details_right', $this); ?>
			</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo LayoutHelper::render('help_document.details_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo Html::_('bootstrap.endTab'); ?>

	<?php $this->ignore_fieldsets = array('details','metadata','vdmmetadata','accesscontrol'); ?>
	<?php $this->tab_name = 'help_documentTab'; ?>
	<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

	<?php if ($this->canDo->get('core.edit.created_by') || $this->canDo->get('core.edit.created') || $this->canDo->get('help_document.edit.state') || ($this->canDo->get('help_document.delete') && $this->canDo->get('help_document.edit.state'))) : ?>
	<?php echo Html::_('bootstrap.addTab', 'help_documentTab', 'publishing', Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo LayoutHelper::render('help_document.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo LayoutHelper::render('help_document.metadata', $this); ?>
			</div>
		</div>
	<?php echo Html::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo Html::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="help_document.edit" />
		<?php echo Html::_('form.token'); ?>
	</div>
</div>

<div class="clearfix"></div>
<?php echo LayoutHelper::render('help_document.details_under', $this); ?>
</form>
</div>

<script type="text/javascript">

// #jform_location listeners for location_vvvvvwx function
jQuery('#jform_location').on('keyup',function()
{
	var location_vvvvvwx = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwx(location_vvvvvwx);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_vvvvvwx = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwx(location_vvvvvwx);

});

// #jform_location listeners for location_vvvvvwy function
jQuery('#jform_location').on('keyup',function()
{
	var location_vvvvvwy = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwy(location_vvvvvwy);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_vvvvvwy = jQuery("#jform_location input[type='radio']:checked").val();
	vvvvvwy(location_vvvvvwy);

});

// #jform_type listeners for type_vvvvvwz function
jQuery('#jform_type').on('keyup',function()
{
	var type_vvvvvwz = jQuery("#jform_type").val();
	vvvvvwz(type_vvvvvwz);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_vvvvvwz = jQuery("#jform_type").val();
	vvvvvwz(type_vvvvvwz);

});

// #jform_type listeners for type_vvvvvxa function
jQuery('#jform_type').on('keyup',function()
{
	var type_vvvvvxa = jQuery("#jform_type").val();
	vvvvvxa(type_vvvvvxa);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_vvvvvxa = jQuery("#jform_type").val();
	vvvvvxa(type_vvvvvxa);

});

// #jform_type listeners for type_vvvvvxb function
jQuery('#jform_type').on('keyup',function()
{
	var type_vvvvvxb = jQuery("#jform_type").val();
	vvvvvxb(type_vvvvvxb);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_vvvvvxb = jQuery("#jform_type").val();
	vvvvvxb(type_vvvvvxb);

});

// #jform_target listeners for target_vvvvvxc function
jQuery('#jform_target').on('keyup',function()
{
	var target_vvvvvxc = jQuery("#jform_target input[type='radio']:checked").val();
	vvvvvxc(target_vvvvvxc);

});
jQuery('#adminForm').on('change', '#jform_target',function (e)
{
	e.preventDefault();
	var target_vvvvvxc = jQuery("#jform_target input[type='radio']:checked").val();
	vvvvvxc(target_vvvvvxc);

});

</script>
