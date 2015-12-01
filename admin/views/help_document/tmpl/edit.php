<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
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

// #jform_location listeners for location_YqmLlcw function
jQuery('#jform_location').on('keyup',function()
{
	var location_YqmLlcw = jQuery("#jform_location input[type='radio']:checked").val();
	YqmLlcw(location_YqmLlcw);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_YqmLlcw = jQuery("#jform_location input[type='radio']:checked").val();
	YqmLlcw(location_YqmLlcw);

});

// #jform_location listeners for location_ErKnwQB function
jQuery('#jform_location').on('keyup',function()
{
	var location_ErKnwQB = jQuery("#jform_location input[type='radio']:checked").val();
	ErKnwQB(location_ErKnwQB);

});
jQuery('#adminForm').on('change', '#jform_location',function (e)
{
	e.preventDefault();
	var location_ErKnwQB = jQuery("#jform_location input[type='radio']:checked").val();
	ErKnwQB(location_ErKnwQB);

});

// #jform_type listeners for type_pfUDjYG function
jQuery('#jform_type').on('keyup',function()
{
	var type_pfUDjYG = jQuery("#jform_type").val();
	pfUDjYG(type_pfUDjYG);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_pfUDjYG = jQuery("#jform_type").val();
	pfUDjYG(type_pfUDjYG);

});

// #jform_type listeners for type_cfyFlnv function
jQuery('#jform_type').on('keyup',function()
{
	var type_cfyFlnv = jQuery("#jform_type").val();
	cfyFlnv(type_cfyFlnv);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_cfyFlnv = jQuery("#jform_type").val();
	cfyFlnv(type_cfyFlnv);

});

// #jform_type listeners for type_SSRqCog function
jQuery('#jform_type').on('keyup',function()
{
	var type_SSRqCog = jQuery("#jform_type").val();
	SSRqCog(type_SSRqCog);

});
jQuery('#adminForm').on('change', '#jform_type',function (e)
{
	e.preventDefault();
	var type_SSRqCog = jQuery("#jform_type").val();
	SSRqCog(type_SSRqCog);

});

// #jform_target listeners for target_qdgfzre function
jQuery('#jform_target').on('keyup',function()
{
	var target_qdgfzre = jQuery("#jform_target input[type='radio']:checked").val();
	qdgfzre(target_qdgfzre);

});
jQuery('#adminForm').on('change', '#jform_target',function (e)
{
	e.preventDefault();
	var target_qdgfzre = jQuery("#jform_target input[type='radio']:checked").val();
	qdgfzre(target_qdgfzre);

});

</script>
