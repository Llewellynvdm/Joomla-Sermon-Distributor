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

<?php echo LayoutHelper::render('external_source.details_above', $this); ?>
<div class="form-horizontal">

	<?php echo Html::_('bootstrap.startTabSet', 'external_sourceTab', ['active' => 'details', 'recall' => true]); ?>

	<?php echo Html::_('bootstrap.addTab', 'external_sourceTab', 'details', Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo LayoutHelper::render('external_source.details_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo LayoutHelper::render('external_source.details_right', $this); ?>
			</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo LayoutHelper::render('external_source.details_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo Html::_('bootstrap.endTab'); ?>

	<?php echo Html::_('bootstrap.addTab', 'external_sourceTab', 'build_option', Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_BUILD_OPTION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo LayoutHelper::render('external_source.build_option_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo Html::_('bootstrap.endTab'); ?>

	<?php $this->ignore_fieldsets = array('details','metadata','vdmmetadata','accesscontrol'); ?>
	<?php $this->tab_name = 'external_sourceTab'; ?>
	<?php echo LayoutHelper::render('joomla.edit.params', $this); ?>

	<?php if ($this->canDo->get('external_source.edit.created_by') || $this->canDo->get('external_source.edit.created') || $this->canDo->get('external_source.edit.state') || ($this->canDo->get('external_source.delete') && $this->canDo->get('external_source.edit.state'))) : ?>
	<?php echo Html::_('bootstrap.addTab', 'external_sourceTab', 'publishing', Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo LayoutHelper::render('external_source.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo LayoutHelper::render('external_source.publlshing', $this); ?>
			</div>
		</div>
	<?php echo Html::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo Html::_('bootstrap.addTab', 'external_sourceTab', 'permissions', Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_PERMISSION', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<fieldset class="adminform">
					<div class="adminformlist">
					<?php foreach ($this->form->getFieldset('accesscontrol') as $field): ?>
						<div>
							<?php echo $field->label; echo $field->input;?>
						</div>
						<div class="clearfix"></div>
					<?php endforeach; ?>
					</div>
				</fieldset>
			</div>
		</div>
	<?php echo Html::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo Html::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="external_source.edit" />
		<?php echo Html::_('form.token'); ?>
	</div>
</div>

<div class="clearfix"></div>
<?php echo LayoutHelper::render('external_source.details_under', $this); ?>
</form>
</div>

<script type="text/javascript">

// #jform_externalsources listeners for externalsources_vvvvvwe function
jQuery('#jform_externalsources').on('keyup',function()
{
	var externalsources_vvvvvwe = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwe = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwe(externalsources_vvvvvwe,permissiontype_vvvvvwe);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwe = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwe = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwe(externalsources_vvvvvwe,permissiontype_vvvvvwe);

});

// #jform_permissiontype listeners for permissiontype_vvvvvwe function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var externalsources_vvvvvwe = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwe = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwe(externalsources_vvvvvwe,permissiontype_vvvvvwe);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwe = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwe = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwe(externalsources_vvvvvwe,permissiontype_vvvvvwe);

});

// #jform_externalsources listeners for externalsources_vvvvvwg function
jQuery('#jform_externalsources').on('keyup',function()
{
	var externalsources_vvvvvwg = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwg = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwg(externalsources_vvvvvwg,permissiontype_vvvvvwg);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwg = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwg = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwg(externalsources_vvvvvwg,permissiontype_vvvvvwg);

});

// #jform_permissiontype listeners for permissiontype_vvvvvwg function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var externalsources_vvvvvwg = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwg = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwg(externalsources_vvvvvwg,permissiontype_vvvvvwg);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwg = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwg = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwg(externalsources_vvvvvwg,permissiontype_vvvvvwg);

});

// #jform_externalsources listeners for externalsources_vvvvvwi function
jQuery('#jform_externalsources').on('keyup',function()
{
	var externalsources_vvvvvwi = jQuery("#jform_externalsources").val();
	vvvvvwi(externalsources_vvvvvwi);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwi = jQuery("#jform_externalsources").val();
	vvvvvwi(externalsources_vvvvvwi);

});

// #jform_update_method listeners for update_method_vvvvvwj function
jQuery('#jform_update_method').on('keyup',function()
{
	var update_method_vvvvvwj = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwj(update_method_vvvvvwj);

});
jQuery('#adminForm').on('change', '#jform_update_method',function (e)
{
	e.preventDefault();
	var update_method_vvvvvwj = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwj(update_method_vvvvvwj);

});

// #jform_build listeners for build_vvvvvwk function
jQuery('#jform_build').on('keyup',function()
{
	var build_vvvvvwk = jQuery("#jform_build").val();
	vvvvvwk(build_vvvvvwk);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var build_vvvvvwk = jQuery("#jform_build").val();
	vvvvvwk(build_vvvvvwk);

});

// #jform_build listeners for build_vvvvvwl function
jQuery('#jform_build').on('keyup',function()
{
	var build_vvvvvwl = jQuery("#jform_build").val();
	vvvvvwl(build_vvvvvwl);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var build_vvvvvwl = jQuery("#jform_build").val();
	vvvvvwl(build_vvvvvwl);

});

// #jform_externalsources listeners for externalsources_vvvvvwm function
jQuery('#jform_externalsources').on('keyup',function()
{
	var externalsources_vvvvvwm = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwm = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwm(externalsources_vvvvvwm,update_method_vvvvvwm);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwm = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwm = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwm(externalsources_vvvvvwm,update_method_vvvvvwm);

});

// #jform_update_method listeners for update_method_vvvvvwm function
jQuery('#jform_update_method').on('keyup',function()
{
	var externalsources_vvvvvwm = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwm = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwm(externalsources_vvvvvwm,update_method_vvvvvwm);

});
jQuery('#adminForm').on('change', '#jform_update_method',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwm = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwm = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwm(externalsources_vvvvvwm,update_method_vvvvvwm);

});

// #jform_externalsources listeners for externalsources_vvvvvwo function
jQuery('#jform_externalsources').on('keyup',function()
{
	var externalsources_vvvvvwo = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwo = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwo(externalsources_vvvvvwo,update_method_vvvvvwo);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwo = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwo = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwo(externalsources_vvvvvwo,update_method_vvvvvwo);

});

// #jform_update_method listeners for update_method_vvvvvwo function
jQuery('#jform_update_method').on('keyup',function()
{
	var externalsources_vvvvvwo = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwo = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwo(externalsources_vvvvvwo,update_method_vvvvvwo);

});
jQuery('#adminForm').on('change', '#jform_update_method',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwo = jQuery("#jform_externalsources").val();
	var update_method_vvvvvwo = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvwo(externalsources_vvvvvwo,update_method_vvvvvwo);

});

// #jform_externalsources listeners for externalsources_vvvvvwq function
jQuery('#jform_externalsources').on('keyup',function()
{
	var externalsources_vvvvvwq = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwq = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwq = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwq = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwq = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwq = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq);

});

// #jform_dropboxoptions listeners for dropboxoptions_vvvvvwq function
jQuery('#jform_dropboxoptions').on('keyup',function()
{
	var externalsources_vvvvvwq = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwq = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwq = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq);

});
jQuery('#adminForm').on('change', '#jform_dropboxoptions',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwq = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwq = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwq = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq);

});

// #jform_permissiontype listeners for permissiontype_vvvvvwq function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var externalsources_vvvvvwq = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwq = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwq = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwq = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwq = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwq = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwq(externalsources_vvvvvwq,dropboxoptions_vvvvvwq,permissiontype_vvvvvwq);

});

// #jform_dropboxoptions listeners for dropboxoptions_vvvvvwr function
jQuery('#jform_dropboxoptions').on('keyup',function()
{
	var dropboxoptions_vvvvvwr = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwr = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwr = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr);

});
jQuery('#adminForm').on('change', '#jform_dropboxoptions',function (e)
{
	e.preventDefault();
	var dropboxoptions_vvvvvwr = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwr = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwr = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr);

});

// #jform_externalsources listeners for externalsources_vvvvvwr function
jQuery('#jform_externalsources').on('keyup',function()
{
	var dropboxoptions_vvvvvwr = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwr = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwr = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var dropboxoptions_vvvvvwr = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwr = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwr = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr);

});

// #jform_permissiontype listeners for permissiontype_vvvvvwr function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var dropboxoptions_vvvvvwr = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwr = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwr = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var dropboxoptions_vvvvvwr = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwr = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwr = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwr(dropboxoptions_vvvvvwr,externalsources_vvvvvwr,permissiontype_vvvvvwr);

});

// #jform_permissiontype listeners for permissiontype_vvvvvws function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var permissiontype_vvvvvws = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvws = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvws = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var permissiontype_vvvvvws = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvws = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvws = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws);

});

// #jform_externalsources listeners for externalsources_vvvvvws function
jQuery('#jform_externalsources').on('keyup',function()
{
	var permissiontype_vvvvvws = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvws = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvws = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var permissiontype_vvvvvws = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvws = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvws = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws);

});

// #jform_dropboxoptions listeners for dropboxoptions_vvvvvws function
jQuery('#jform_dropboxoptions').on('keyup',function()
{
	var permissiontype_vvvvvws = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvws = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvws = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws);

});
jQuery('#adminForm').on('change', '#jform_dropboxoptions',function (e)
{
	e.preventDefault();
	var permissiontype_vvvvvws = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvws = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvws = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvws(permissiontype_vvvvvws,externalsources_vvvvvws,dropboxoptions_vvvvvws);

});

// #jform_externalsources listeners for externalsources_vvvvvwt function
jQuery('#jform_externalsources').on('keyup',function()
{
	var externalsources_vvvvvwt = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwt = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwt = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwt = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwt = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwt = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt);

});

// #jform_dropboxoptions listeners for dropboxoptions_vvvvvwt function
jQuery('#jform_dropboxoptions').on('keyup',function()
{
	var externalsources_vvvvvwt = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwt = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwt = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt);

});
jQuery('#adminForm').on('change', '#jform_dropboxoptions',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwt = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwt = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwt = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt);

});

// #jform_permissiontype listeners for permissiontype_vvvvvwt function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var externalsources_vvvvvwt = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwt = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwt = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var externalsources_vvvvvwt = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwt = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var permissiontype_vvvvvwt = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwt(externalsources_vvvvvwt,dropboxoptions_vvvvvwt,permissiontype_vvvvvwt);

});

// #jform_dropboxoptions listeners for dropboxoptions_vvvvvwu function
jQuery('#jform_dropboxoptions').on('keyup',function()
{
	var dropboxoptions_vvvvvwu = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwu = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwu = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu);

});
jQuery('#adminForm').on('change', '#jform_dropboxoptions',function (e)
{
	e.preventDefault();
	var dropboxoptions_vvvvvwu = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwu = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwu = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu);

});

// #jform_externalsources listeners for externalsources_vvvvvwu function
jQuery('#jform_externalsources').on('keyup',function()
{
	var dropboxoptions_vvvvvwu = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwu = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwu = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var dropboxoptions_vvvvvwu = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwu = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwu = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu);

});

// #jform_permissiontype listeners for permissiontype_vvvvvwu function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var dropboxoptions_vvvvvwu = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwu = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwu = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var dropboxoptions_vvvvvwu = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	var externalsources_vvvvvwu = jQuery("#jform_externalsources").val();
	var permissiontype_vvvvvwu = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	vvvvvwu(dropboxoptions_vvvvvwu,externalsources_vvvvvwu,permissiontype_vvvvvwu);

});

// #jform_permissiontype listeners for permissiontype_vvvvvwv function
jQuery('#jform_permissiontype').on('keyup',function()
{
	var permissiontype_vvvvvwv = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvwv = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwv = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv);

});
jQuery('#adminForm').on('change', '#jform_permissiontype',function (e)
{
	e.preventDefault();
	var permissiontype_vvvvvwv = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvwv = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwv = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv);

});

// #jform_externalsources listeners for externalsources_vvvvvwv function
jQuery('#jform_externalsources').on('keyup',function()
{
	var permissiontype_vvvvvwv = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvwv = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwv = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv);

});
jQuery('#adminForm').on('change', '#jform_externalsources',function (e)
{
	e.preventDefault();
	var permissiontype_vvvvvwv = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvwv = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwv = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv);

});

// #jform_dropboxoptions listeners for dropboxoptions_vvvvvwv function
jQuery('#jform_dropboxoptions').on('keyup',function()
{
	var permissiontype_vvvvvwv = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvwv = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwv = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv);

});
jQuery('#adminForm').on('change', '#jform_dropboxoptions',function (e)
{
	e.preventDefault();
	var permissiontype_vvvvvwv = jQuery("#jform_permissiontype input[type='radio']:checked").val();
	var externalsources_vvvvvwv = jQuery("#jform_externalsources").val();
	var dropboxoptions_vvvvvwv = jQuery("#jform_dropboxoptions input[type='radio']:checked").val();
	vvvvvwv(permissiontype_vvvvvwv,externalsources_vvvvvwv,dropboxoptions_vvvvvwv);

});

// #jform_update_method listeners for update_method_vvvvvww function
jQuery('#jform_update_method').on('keyup',function()
{
	var update_method_vvvvvww = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvww(update_method_vvvvvww);

});
jQuery('#adminForm').on('change', '#jform_update_method',function (e)
{
	e.preventDefault();
	var update_method_vvvvvww = jQuery("#jform_update_method input[type='radio']:checked").val();
	vvvvvww(update_method_vvvvvww);

});



jQuery('input.form-field-repeatable').on('value-update', function(e, value){
	if (value)
	{
		value = JSON.stringify(value);
		getBuildTable(value,e.currentTarget.id);
	}
});
</script>
