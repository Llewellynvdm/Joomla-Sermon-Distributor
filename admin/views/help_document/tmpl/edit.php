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
	@build			20th February, 2016
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

	<?php echo JLayoutHelper::render('help_document.details_above', $this); ?><div class="form-horizontal">

	<?php echo JHtml::_('bootstrap.startTabSet', 'help_documentTab', array('active' => 'details')); ?>

	<?php echo JHtml::_('bootstrap.addTab', 'help_documentTab', 'details', JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_DETAILS', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.details_left', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.details_right', $this); ?>
			</div>
		</div>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span12">
				<?php echo JLayoutHelper::render('help_document.details_fullwidth', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>

	<?php if ($this->canDo->get('help_document.delete') || $this->canDo->get('core.edit.created_by') || $this->canDo->get('help_document.edit.state') || $this->canDo->get('core.edit.created')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'help_documentTab', 'publishing', JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.publishing', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('help_document.metadata', $this); ?>
			</div>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>

	<?php if ($this->canDo->get('core.admin')) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'help_documentTab', 'permissions', JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_PERMISSION', true)); ?>
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
		<input type="hidden" name="task" value="help_document.edit" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</div>

<div class="clearfix"></div>
<?php echo JLayoutHelper::render('help_document.details_under', $this); ?>
</form>

<script type="text/javascript">

// #jform_location listeners for location_KPuQSWe function
jQuery('#jform_location').on('keyup',function()
{
	var location_KPuQSWe = jQuery("#jform_location input[type='radio']:checked").val();
	KPuQSWe(location_KPuQSWe);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_KPuQSWe = jQuery("#jform_location input[type='radio']:checked").val();
	KPuQSWe(location_KPuQSWe);

});

// #jform_location listeners for location_exhczAS function
jQuery('#jform_location').on('keyup',function()
{
	var location_exhczAS = jQuery("#jform_location input[type='radio']:checked").val();
	exhczAS(location_exhczAS);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_exhczAS = jQuery("#jform_location input[type='radio']:checked").val();
	exhczAS(location_exhczAS);

});

// #jform_type listeners for type_LRbFdTD function
jQuery('#jform_type').on('keyup',function()
{
	var type_LRbFdTD = jQuery("#jform_type").val();
	LRbFdTD(type_LRbFdTD);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_LRbFdTD = jQuery("#jform_type").val();
	LRbFdTD(type_LRbFdTD);

});

// #jform_type listeners for type_SVZGybe function
jQuery('#jform_type').on('keyup',function()
{
	var type_SVZGybe = jQuery("#jform_type").val();
	SVZGybe(type_SVZGybe);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_SVZGybe = jQuery("#jform_type").val();
	SVZGybe(type_SVZGybe);

});

// #jform_type listeners for type_ULuYNQg function
jQuery('#jform_type').on('keyup',function()
{
	var type_ULuYNQg = jQuery("#jform_type").val();
	ULuYNQg(type_ULuYNQg);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_ULuYNQg = jQuery("#jform_type").val();
	ULuYNQg(type_ULuYNQg);

});

// #jform_target listeners for target_HWyJIuC function
jQuery('#jform_target').on('keyup',function()
{
	var target_HWyJIuC = jQuery("#jform_target input[type='radio']:checked").val();
	HWyJIuC(target_HWyJIuC);

});
jQuery('#adminForm').on('change', '#jform_target',function (e)
{
	e.preventDefault();
	var target_HWyJIuC = jQuery("#jform_target input[type='radio']:checked").val();
	HWyJIuC(target_HWyJIuC);

});

</script>
