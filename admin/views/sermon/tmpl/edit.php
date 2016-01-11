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

	@version		1.3.0
	@build			11th January, 2016
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

	<?php echo JHtml::_('bootstrap.addTab', 'sermonTab', 'stastics', JText::_('COM_SERMONDISTRIBUTOR_SERMON_STASTICS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('sermon.stastics_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

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

// #jform_source listeners for source_dOVAunr function
jQuery('#jform_source').on('keyup',function()
{
	var source_dOVAunr = jQuery("#jform_source").val();
	dOVAunr(source_dOVAunr);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_dOVAunr = jQuery("#jform_source").val();
	dOVAunr(source_dOVAunr);

});

// #jform_source listeners for source_mDbfJMD function
jQuery('#jform_source').on('keyup',function()
{
	var source_mDbfJMD = jQuery("#jform_source").val();
	var build_mDbfJMD = jQuery("#jform_build input[type='radio']:checked").val();
	mDbfJMD(source_mDbfJMD,build_mDbfJMD);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_mDbfJMD = jQuery("#jform_source").val();
	var build_mDbfJMD = jQuery("#jform_build input[type='radio']:checked").val();
	mDbfJMD(source_mDbfJMD,build_mDbfJMD);

});

// #jform_build listeners for build_mDbfJMD function
jQuery('#jform_build').on('keyup',function()
{
	var source_mDbfJMD = jQuery("#jform_source").val();
	var build_mDbfJMD = jQuery("#jform_build input[type='radio']:checked").val();
	mDbfJMD(source_mDbfJMD,build_mDbfJMD);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_mDbfJMD = jQuery("#jform_source").val();
	var build_mDbfJMD = jQuery("#jform_build input[type='radio']:checked").val();
	mDbfJMD(source_mDbfJMD,build_mDbfJMD);

});

// #jform_source listeners for source_iqNHLdU function
jQuery('#jform_source').on('keyup',function()
{
	var source_iqNHLdU = jQuery("#jform_source").val();
	var build_iqNHLdU = jQuery("#jform_build input[type='radio']:checked").val();
	iqNHLdU(source_iqNHLdU,build_iqNHLdU);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_iqNHLdU = jQuery("#jform_source").val();
	var build_iqNHLdU = jQuery("#jform_build input[type='radio']:checked").val();
	iqNHLdU(source_iqNHLdU,build_iqNHLdU);

});

// #jform_build listeners for build_iqNHLdU function
jQuery('#jform_build').on('keyup',function()
{
	var source_iqNHLdU = jQuery("#jform_source").val();
	var build_iqNHLdU = jQuery("#jform_build input[type='radio']:checked").val();
	iqNHLdU(source_iqNHLdU,build_iqNHLdU);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_iqNHLdU = jQuery("#jform_source").val();
	var build_iqNHLdU = jQuery("#jform_build input[type='radio']:checked").val();
	iqNHLdU(source_iqNHLdU,build_iqNHLdU);

});

// #jform_build listeners for build_kkZAuiN function
jQuery('#jform_build').on('keyup',function()
{
	var build_kkZAuiN = jQuery("#jform_build input[type='radio']:checked").val();
	var source_kkZAuiN = jQuery("#jform_source").val();
	kkZAuiN(build_kkZAuiN,source_kkZAuiN);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var build_kkZAuiN = jQuery("#jform_build input[type='radio']:checked").val();
	var source_kkZAuiN = jQuery("#jform_source").val();
	kkZAuiN(build_kkZAuiN,source_kkZAuiN);

});

// #jform_source listeners for source_kkZAuiN function
jQuery('#jform_source').on('keyup',function()
{
	var build_kkZAuiN = jQuery("#jform_build input[type='radio']:checked").val();
	var source_kkZAuiN = jQuery("#jform_source").val();
	kkZAuiN(build_kkZAuiN,source_kkZAuiN);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var build_kkZAuiN = jQuery("#jform_build input[type='radio']:checked").val();
	var source_kkZAuiN = jQuery("#jform_source").val();
	kkZAuiN(build_kkZAuiN,source_kkZAuiN);

});

// #jform_source listeners for source_SmvypXs function
jQuery('#jform_source').on('keyup',function()
{
	var source_SmvypXs = jQuery("#jform_source").val();
	SmvypXs(source_SmvypXs);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_SmvypXs = jQuery("#jform_source").val();
	SmvypXs(source_SmvypXs);

});

// #jform_source listeners for source_ENosobD function
jQuery('#jform_source').on('keyup',function()
{
	var source_ENosobD = jQuery("#jform_source").val();
	ENosobD(source_ENosobD);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_ENosobD = jQuery("#jform_source").val();
	ENosobD(source_ENosobD);

});

// #jform_link_type listeners for link_type_aYIZzei function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_aYIZzei = jQuery("#jform_link_type input[type='radio']:checked").val();
	aYIZzei(link_type_aYIZzei);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_aYIZzei = jQuery("#jform_link_type input[type='radio']:checked").val();
	aYIZzei(link_type_aYIZzei);

});

// #jform_link_type listeners for link_type_oRQiMgE function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_oRQiMgE = jQuery("#jform_link_type input[type='radio']:checked").val();
	oRQiMgE(link_type_oRQiMgE);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_oRQiMgE = jQuery("#jform_link_type input[type='radio']:checked").val();
	oRQiMgE(link_type_oRQiMgE);

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
var htmlDropNote = '<h1><?php echo JText::_('No Files Linked Yet'); ?></h1>';
htmlDropNote += '<div class="alert alert-warning"><?php echo JText::_('Always better to add the files to Dropbox and let the system create the sermon for you. Please read instructions below carefully.'); ?></div>';
if (auto_sermons != 1 && auto_sermons.length > 0)
{
	htmlDropNote = '<h1><?php echo JText::_('The Files Linked from Dropbox'); ?></h1>';
	auto_sermons = jQuery.parseJSON(auto_sermons);
	htmlDropNote += '<div class="alert alert-success"><ul>';
	jQuery.each(auto_sermons, function(filename,fileKey) {
		htmlDropNote += '<li><b><?php echo JText::_('Download Name'); ?>:</b> ';
		htmlDropNote += filename;
		htmlDropNote += '<br /><b><?php echo JText::_('Dropbox Relation'); ?>:</b> ';
		htmlDropNote += fileKey.replace("VDM_pLeK_h0uEr/", "");
		htmlDropNote += '</li>';
	});
	htmlDropNote += '</ul></div>';
}
jQuery('.note_auto_dropbox').closest('.control-group').prepend(htmlDropNote);
</script>
