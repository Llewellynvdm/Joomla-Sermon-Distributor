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
	@build			26th February, 2016
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

// #jform_source listeners for source_LeYQWzT function
jQuery('#jform_source').on('keyup',function()
{
	var source_LeYQWzT = jQuery("#jform_source").val();
	LeYQWzT(source_LeYQWzT);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_LeYQWzT = jQuery("#jform_source").val();
	LeYQWzT(source_LeYQWzT);

});

// #jform_source listeners for source_qFuRtXH function
jQuery('#jform_source').on('keyup',function()
{
	var source_qFuRtXH = jQuery("#jform_source").val();
	var build_qFuRtXH = jQuery("#jform_build input[type='radio']:checked").val();
	qFuRtXH(source_qFuRtXH,build_qFuRtXH);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_qFuRtXH = jQuery("#jform_source").val();
	var build_qFuRtXH = jQuery("#jform_build input[type='radio']:checked").val();
	qFuRtXH(source_qFuRtXH,build_qFuRtXH);

});

// #jform_build listeners for build_qFuRtXH function
jQuery('#jform_build').on('keyup',function()
{
	var source_qFuRtXH = jQuery("#jform_source").val();
	var build_qFuRtXH = jQuery("#jform_build input[type='radio']:checked").val();
	qFuRtXH(source_qFuRtXH,build_qFuRtXH);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_qFuRtXH = jQuery("#jform_source").val();
	var build_qFuRtXH = jQuery("#jform_build input[type='radio']:checked").val();
	qFuRtXH(source_qFuRtXH,build_qFuRtXH);

});

// #jform_source listeners for source_nqLwYLV function
jQuery('#jform_source').on('keyup',function()
{
	var source_nqLwYLV = jQuery("#jform_source").val();
	var build_nqLwYLV = jQuery("#jform_build input[type='radio']:checked").val();
	nqLwYLV(source_nqLwYLV,build_nqLwYLV);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_nqLwYLV = jQuery("#jform_source").val();
	var build_nqLwYLV = jQuery("#jform_build input[type='radio']:checked").val();
	nqLwYLV(source_nqLwYLV,build_nqLwYLV);

});

// #jform_build listeners for build_nqLwYLV function
jQuery('#jform_build').on('keyup',function()
{
	var source_nqLwYLV = jQuery("#jform_source").val();
	var build_nqLwYLV = jQuery("#jform_build input[type='radio']:checked").val();
	nqLwYLV(source_nqLwYLV,build_nqLwYLV);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_nqLwYLV = jQuery("#jform_source").val();
	var build_nqLwYLV = jQuery("#jform_build input[type='radio']:checked").val();
	nqLwYLV(source_nqLwYLV,build_nqLwYLV);

});

// #jform_build listeners for build_vGBwfrO function
jQuery('#jform_build').on('keyup',function()
{
	var build_vGBwfrO = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vGBwfrO = jQuery("#jform_source").val();
	vGBwfrO(build_vGBwfrO,source_vGBwfrO);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var build_vGBwfrO = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vGBwfrO = jQuery("#jform_source").val();
	vGBwfrO(build_vGBwfrO,source_vGBwfrO);

});

// #jform_source listeners for source_vGBwfrO function
jQuery('#jform_source').on('keyup',function()
{
	var build_vGBwfrO = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vGBwfrO = jQuery("#jform_source").val();
	vGBwfrO(build_vGBwfrO,source_vGBwfrO);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var build_vGBwfrO = jQuery("#jform_build input[type='radio']:checked").val();
	var source_vGBwfrO = jQuery("#jform_source").val();
	vGBwfrO(build_vGBwfrO,source_vGBwfrO);

});

// #jform_source listeners for source_sqkUWYd function
jQuery('#jform_source').on('keyup',function()
{
	var source_sqkUWYd = jQuery("#jform_source").val();
	sqkUWYd(source_sqkUWYd);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_sqkUWYd = jQuery("#jform_source").val();
	sqkUWYd(source_sqkUWYd);

});

// #jform_source listeners for source_htLRPCv function
jQuery('#jform_source').on('keyup',function()
{
	var source_htLRPCv = jQuery("#jform_source").val();
	htLRPCv(source_htLRPCv);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_htLRPCv = jQuery("#jform_source").val();
	htLRPCv(source_htLRPCv);

});

// #jform_link_type listeners for link_type_BPUcgat function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_BPUcgat = jQuery("#jform_link_type input[type='radio']:checked").val();
	BPUcgat(link_type_BPUcgat);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_BPUcgat = jQuery("#jform_link_type input[type='radio']:checked").val();
	BPUcgat(link_type_BPUcgat);

});

// #jform_link_type listeners for link_type_TvqhKCs function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_TvqhKCs = jQuery("#jform_link_type input[type='radio']:checked").val();
	TvqhKCs(link_type_TvqhKCs);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_TvqhKCs = jQuery("#jform_link_type input[type='radio']:checked").val();
	TvqhKCs(link_type_TvqhKCs);

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
