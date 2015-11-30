<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			30th November, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		edit.php
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

// #jform_source listeners for source_rftyiTC function
jQuery('#jform_source').on('keyup',function()
{
	var source_rftyiTC = jQuery("#jform_source").val();
	rftyiTC(source_rftyiTC);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_rftyiTC = jQuery("#jform_source").val();
	rftyiTC(source_rftyiTC);

});

// #jform_source listeners for source_liGWajx function
jQuery('#jform_source').on('keyup',function()
{
	var source_liGWajx = jQuery("#jform_source").val();
	var build_liGWajx = jQuery("#jform_build input[type='radio']:checked").val();
	liGWajx(source_liGWajx,build_liGWajx);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_liGWajx = jQuery("#jform_source").val();
	var build_liGWajx = jQuery("#jform_build input[type='radio']:checked").val();
	liGWajx(source_liGWajx,build_liGWajx);

});

// #jform_build listeners for build_liGWajx function
jQuery('#jform_build').on('keyup',function()
{
	var source_liGWajx = jQuery("#jform_source").val();
	var build_liGWajx = jQuery("#jform_build input[type='radio']:checked").val();
	liGWajx(source_liGWajx,build_liGWajx);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_liGWajx = jQuery("#jform_source").val();
	var build_liGWajx = jQuery("#jform_build input[type='radio']:checked").val();
	liGWajx(source_liGWajx,build_liGWajx);

});

// #jform_source listeners for source_jAMSvXi function
jQuery('#jform_source').on('keyup',function()
{
	var source_jAMSvXi = jQuery("#jform_source").val();
	var build_jAMSvXi = jQuery("#jform_build input[type='radio']:checked").val();
	jAMSvXi(source_jAMSvXi,build_jAMSvXi);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_jAMSvXi = jQuery("#jform_source").val();
	var build_jAMSvXi = jQuery("#jform_build input[type='radio']:checked").val();
	jAMSvXi(source_jAMSvXi,build_jAMSvXi);

});

// #jform_build listeners for build_jAMSvXi function
jQuery('#jform_build').on('keyup',function()
{
	var source_jAMSvXi = jQuery("#jform_source").val();
	var build_jAMSvXi = jQuery("#jform_build input[type='radio']:checked").val();
	jAMSvXi(source_jAMSvXi,build_jAMSvXi);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var source_jAMSvXi = jQuery("#jform_source").val();
	var build_jAMSvXi = jQuery("#jform_build input[type='radio']:checked").val();
	jAMSvXi(source_jAMSvXi,build_jAMSvXi);

});

// #jform_build listeners for build_hkBIRwX function
jQuery('#jform_build').on('keyup',function()
{
	var build_hkBIRwX = jQuery("#jform_build input[type='radio']:checked").val();
	var source_hkBIRwX = jQuery("#jform_source").val();
	hkBIRwX(build_hkBIRwX,source_hkBIRwX);

});
jQuery('#adminForm').on('change', '#jform_build',function (e)
{
	e.preventDefault();
	var build_hkBIRwX = jQuery("#jform_build input[type='radio']:checked").val();
	var source_hkBIRwX = jQuery("#jform_source").val();
	hkBIRwX(build_hkBIRwX,source_hkBIRwX);

});

// #jform_source listeners for source_hkBIRwX function
jQuery('#jform_source').on('keyup',function()
{
	var build_hkBIRwX = jQuery("#jform_build input[type='radio']:checked").val();
	var source_hkBIRwX = jQuery("#jform_source").val();
	hkBIRwX(build_hkBIRwX,source_hkBIRwX);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var build_hkBIRwX = jQuery("#jform_build input[type='radio']:checked").val();
	var source_hkBIRwX = jQuery("#jform_source").val();
	hkBIRwX(build_hkBIRwX,source_hkBIRwX);

});

// #jform_source listeners for source_cyLHBtp function
jQuery('#jform_source').on('keyup',function()
{
	var source_cyLHBtp = jQuery("#jform_source").val();
	cyLHBtp(source_cyLHBtp);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_cyLHBtp = jQuery("#jform_source").val();
	cyLHBtp(source_cyLHBtp);

});

// #jform_source listeners for source_XcZLWuJ function
jQuery('#jform_source').on('keyup',function()
{
	var source_XcZLWuJ = jQuery("#jform_source").val();
	XcZLWuJ(source_XcZLWuJ);

});
jQuery('#adminForm').on('change', '#jform_source',function (e)
{
	e.preventDefault();
	var source_XcZLWuJ = jQuery("#jform_source").val();
	XcZLWuJ(source_XcZLWuJ);

});

// #jform_link_type listeners for link_type_LnTfUMv function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_LnTfUMv = jQuery("#jform_link_type input[type='radio']:checked").val();
	LnTfUMv(link_type_LnTfUMv);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_LnTfUMv = jQuery("#jform_link_type input[type='radio']:checked").val();
	LnTfUMv(link_type_LnTfUMv);

});

// #jform_link_type listeners for link_type_SPbxTGc function
jQuery('#jform_link_type').on('keyup',function()
{
	var link_type_SPbxTGc = jQuery("#jform_link_type input[type='radio']:checked").val();
	SPbxTGc(link_type_SPbxTGc);

});
jQuery('#adminForm').on('change', '#jform_link_type',function (e)
{
	e.preventDefault();
	var link_type_SPbxTGc = jQuery("#jform_link_type input[type='radio']:checked").val();
	SPbxTGc(link_type_SPbxTGc);

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
