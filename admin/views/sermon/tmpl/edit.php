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
	@subpackage		edit.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$componentParams = JComponentHelper::getParams('com_sermondistributor');
?>

<form action="<?php echo JRoute::_('index.php?option=com_sermondistributor&layout=edit&id='.(int) $this->item->id.$this->referral); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">

	<?php echo JLayoutHelper::render('sermon.details_above', $this); ?><div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'sermonTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'sermonTab', 'details', JText::_('COM_SERMONDISTRIBUTOR_SERMON_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('sermon.details_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('sermon.details_right', $this); ?>
			</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('sermon.details_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'sermonTab', 'files', JText::_('COM_SERMONDISTRIBUTOR_SERMON_FILES', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('sermon.files_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('statistic.access')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'sermonTab', 'stastics', JText::_('COM_SERMONDISTRIBUTOR_SERMON_STASTICS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('sermon.stastics_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('sermon.delete') || $this->canDo->get('sermon.edit.created_by') || $this->canDo->get('sermon.edit.state') || $this->canDo->get('sermon.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'sermonTab', 'publishing', JText::_('COM_SERMONDISTRIBUTOR_SERMON_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('sermon.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('sermon.metadata', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'sermonTab', 'permissions', JText::_('COM_SERMONDISTRIBUTOR_SERMON_PERMISSION', true)); ?>
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
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php echo JHtml::_('bootstrap.endTabSet'); ?>

	<div>
		<input type="hidden" name="task" value="sermon.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>

<div class="clearfix"></div>
<?php echo JLayoutHelper::render('sermon.details_under', $this); ?>
</form>

<script type="text/javascript">

// #jform_source listeners for source_vvvvvvv function
jQuery('#jform_source').on('keyup',function()
{
	var source_vvvvvvv = jQuery("#jform_source").val();
	vvvvvvv(source_vvvvvvv);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_vvvvvvv = jQuery("#jform_source").val();
	vvvvvvv(source_vvvvvvv);

});

// #jform_source listeners for source_vvvvvvw function
jQuery('#jform_source').on('keyup',function()
{
	var source_vvvvvvw = jQuery("#jform_source").val();
	var build_vvvvvvw = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvw(source_vvvvvvw,build_vvvvvvw);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_vvvvvvw = jQuery("#jform_source").val();
	var build_vvvvvvw = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvw(source_vvvvvvw,build_vvvvvvw);

});

// #jform_build listeners for build_vvvvvvw function
jQuery('#jform_build').on('keyup',function()
{
	var source_vvvvvvw = jQuery("#jform_source").val();
	var build_vvvvvvw = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvw(source_vvvvvvw,build_vvvvvvw);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_vvvvvvw = jQuery("#jform_source").val();
	var build_vvvvvvw = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvw(source_vvvvvvw,build_vvvvvvw);

});

// #jform_source listeners for source_vvvvvvy function
jQuery('#jform_source').on('keyup',function()
{
	var source_vvvvvvy = jQuery("#jform_source").val();
	var build_vvvvvvy = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvy(source_vvvvvvy,build_vvvvvvy);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_vvvvvvy = jQuery("#jform_source").val();
	var build_vvvvvvy = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvy(source_vvvvvvy,build_vvvvvvy);

});

// #jform_build listeners for build_vvvvvvy function
jQuery('#jform_build').on('keyup',function()
{
	var source_vvvvvvy = jQuery("#jform_source").val();
	var build_vvvvvvy = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvy(source_vvvvvvy,build_vvvvvvy);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_vvvvvvy = jQuery("#jform_source").val();
	var build_vvvvvvy = jQuery("#jform_build input[type='radio']:checked").val();
	vvvvvvy(source_vvvvvvy,build_vvvvvvy);

});

// #jform_build listeners for build_vvvvvvz function
jQuery('#jform_build').on('keyup',function()
{
	var build_vvvvvvz = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vvvvvvz = jQuery("#jform_source").val();
	vvvvvvz(build_vvvvvvz,source_vvvvvvz);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var build_vvvvvvz = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vvvvvvz = jQuery("#jform_source").val();
	vvvvvvz(build_vvvvvvz,source_vvvvvvz);

});

// #jform_source listeners for source_vvvvvvz function
jQuery('#jform_source').on('keyup',function()
{
	var build_vvvvvvz = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vvvvvvz = jQuery("#jform_source").val();
	vvvvvvz(build_vvvvvvz,source_vvvvvvz);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var build_vvvvvvz = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vvvvvvz = jQuery("#jform_source").val();
	vvvvvvz(build_vvvvvvz,source_vvvvvvz);

});

// #jform_source listeners for source_vvvvvwa function
jQuery('#jform_source').on('keyup',function()
{
	var source_vvvvvwa = jQuery("#jform_source").val();
	vvvvvwa(source_vvvvvwa);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_vvvvvwa = jQuery("#jform_source").val();
	vvvvvwa(source_vvvvvwa);

});

// #jform_source listeners for source_vvvvvwb function
jQuery('#jform_source').on('keyup',function()
{
	var source_vvvvvwb = jQuery("#jform_source").val();
	vvvvvwb(source_vvvvvwb);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_vvvvvwb = jQuery("#jform_source").val();
	vvvvvwb(source_vvvvvwb);

});

// #jform_link_type listeners for link_type_vvvvvwc function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_vvvvvwc = jQuery("#jform_link_type input[type='radio']:checked").val();
	vvvvvwc(link_type_vvvvvwc);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_vvvvvwc = jQuery("#jform_link_type input[type='radio']:checked").val();
	vvvvvwc(link_type_vvvvvwc);

});

// #jform_link_type listeners for link_type_vvvvvwd function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_vvvvvwd = jQuery("#jform_link_type input[type='radio']:checked").val();
	vvvvvwd(link_type_vvvvvwd);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_vvvvvwd = jQuery("#jform_link_type input[type='radio']:checked").val();
	vvvvvwd(link_type_vvvvvwd);

});



<?php
	// setup the return url
	$uri = (string) JUri::getInstance();
	$return = urlencode(base64_encode($uri));
	$optionsURL = 'index.php?option=com_config&view=component&component=com_sermondistributor&return='.$return;
?>

jQuery('.options-link').on('click',function (e)
{
	e.preventDefault();
	location.href="<?php echo $optionsURL; ?>";
});

// load the auto sermons if set or notice if none is found
var auto_sermons = jQuery('#jform_auto_sermons').val();
var htmlDropNote = '<h1><?php echo JText::_('COM_SERMONDISTRIBUTOR_NO_FILES_LINKED_YET'); ?></h1>';
htmlDropNote += '<div class="alert alert-warning"><?php echo JText::_('COM_SERMONDISTRIBUTOR_ALWAYS_BETTER_TO_ADD_THE_FILES_TO_DROPBOX_AND_LET_THE_SYSTEM_CREATE_THE_SERMON_FOR_YOU_PLEASE_READ_INSTRUCTIONS_BELOW_CAREFULLY'); ?></div>';
if (auto_sermons != 1 && auto_sermons.length > 0)
{
	htmlDropNote = '<h1><?php echo JText::_('COM_SERMONDISTRIBUTOR_THE_FILES_LINKED_FROM_DROPBOX'); ?></h1>';
	auto_sermons = jQuery.parseJSON(auto_sermons);
	htmlDropNote += '<div class="alert alert-success"><ul>';
	jQuery.each(auto_sermons, function(filename,fileKey) {
		htmlDropNote += '<li><b><?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOAD_NAME'); ?>:</b> ';
		htmlDropNote += filename;
		htmlDropNote += '<br /><b><?php echo JText::_('COM_SERMONDISTRIBUTOR_DROPBOX_RELATION'); ?>:</b> ';
		htmlDropNote += fileKey.replace("VDM_pLeK_h0uEr/", "");
		htmlDropNote += '</li>';
	});
	htmlDropNote += '</ul></div>';
}
jQuery('.note_auto_dropbox').closest('.control-group').prepend(htmlDropNote);
</script>
