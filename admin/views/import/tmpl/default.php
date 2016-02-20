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
	@subpackage		default.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHtml::_('jquery.framework');
JHtml::_('bootstrap.tooltip');
JHtml::_('script', 'system/core.js', false, true);
?>
<script type="text/javascript">
	
	<?php if ($this->hasPackage && SermondistributorHelper::checkArray($this->headerList)) : ?>
	Joomla.continueImport = function()
	{
		var form = document.getElementById('adminForm');
		var error = false;
		var therequired = [<?php $i = 0; foreach($this->headerList as $name => $title) { echo ($i != 0)? ', "vdm_'.$name.'"':'"vdm_'.$name.'"'; $i++; } ?>];
		for(i = 0; i < therequired.length; i++)
		{
			if(jQuery('#'+therequired[i]).val() == "" )
			{
				error = true;
				break;
			}
		}
		// do field validation
		if (error)
		{
			alert("<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_PLEASE_SELECT_ALL_COLUMNS', true); ?>");
		}
		else
		{
			jQuery('#loading').css('display', 'block');

			form.gettype.value = 'continue';
			form.submit();
		}
	};
	<?php else: ?>
	Joomla.submitbutton = function()
	{
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.import_package.value == "")
		{
			alert("<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_PLEASE_SELECT_A_FILE', true); ?>");
		}
		else
		{
			jQuery('#loading').css('display', 'block');

			form.gettype.value = 'upload';
			form.submit();
		}
	};

	Joomla.submitbutton3 = function()
	{
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.import_directory.value == ""){
			alert("<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_PLEASE_SELECT_A_DIRECTORY', true); ?>");
		}
		else
		{
			jQuery('#loading').css('display', 'block');

			form.gettype.value = 'folder';
			form.submit();
		}
	};

	Joomla.submitbutton4 = function()
	{
		var form = document.getElementById('adminForm');

		// do field validation
		if (form.import_url.value == "" || form.import_url.value == "http://")
		{
			alert("<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_ENTER_A_URL', true); ?>");
		}
		else
		{
			jQuery('#loading').css('display', 'block');

			form.gettype.value = 'url';
			form.submit();
		}
	};
	<?php endif; ?>

	// Add spindle-wheel for importations:
	jQuery(document).ready(function($) {
		var outerDiv = $('body');

		$('<div id="loading"></div>')
			.css("background", "rgba(255, 255, 255, .8) url('components/com_sermondistributor/assets/images/import.gif') 50% 15% no-repeat")
			.css("top", outerDiv.position().top - $(window).scrollTop())
			.css("left", outerDiv.position().left - $(window).scrollLeft())
			.css("width", outerDiv.width())
			.css("height", outerDiv.height())
			.css("position", "fixed")
			.css("opacity", "0.80")
			.css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
			.css("filter", "alpha(opacity = 80)")
			.css("display", "none")
			.appendTo(outerDiv);
	});

</script>

<div id="installer-import" class="clearfix">
<form enctype="multipart/form-data" action="<?php echo JRoute::_('index.php?option=com_sermondistributor&view=import');?>" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate">

	<?php if (!empty( $this->sidebar)) : ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
	<?php else : ?>
		<div id="j-main-container">
	<?php endif;?>
    
    <?php if ($this->hasPackage && SermondistributorHelper::checkArray($this->headerList) && SermondistributorHelper::checkArray($this->headers)) : ?>
        <fieldset class="uploadform">
            <legend><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_LINK_FILE_TO_TABLE_COLUMNS'); ?></legend>
            <div class="control-group">
                <label class="control-label" ><h4><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_TABLE_COLUMNS'); ?></h4></label>
                <div class="controls">
                <label class="control-label" ><h4><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FILE_COLUMNS'); ?></h4></label>
                </div>
            </div>
            <?php foreach($this->headerList as $name => $title): ?>
            <div class="control-group">
                <label for="<?php echo $name; ?>" class="control-label" ><?php echo $title; ?></label>
                    <div class="controls">
                        <select  name="<?php echo $name; ?>"  id="vdm_<?php echo $name; ?>" required class="required input_box" >
                            <option value=""><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_PLEASE_SELECT_COLUMN'); ?></option>
                            <option value="IGNORE"><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_IGNORE_COLUMN'); ?></option>
                            <?php foreach($this->headers as $value => $option): ?>
                                <?php $selected = (strtolower($option) ==  strtolower ($title) || strtolower($option) == strtolower($name))? 'selected="selected"':''; ?>
                                <option value="<?php echo SermondistributorHelper::htmlEscape($value); ?>" class="required" <?php echo $selected ?>><?php echo SermondistributorHelper::htmlEscape($option); ?></option>
                            <?php endforeach; ?>
                         </select>
                    </div>
            </div>
            <?php endforeach; ?>
            <div class="form-actions">
                <input class="btn btn-primary" type="button" value="<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_CONTINUE'); ?>" onclick="Joomla.continueImport()" />
            </div>
        </fieldset>
        <input type="hidden" name="gettype" value="continue" />
    <?php else: ?>
		<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'upload')); ?>
    
                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'upload', JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FROM_UPLOAD', true)); ?>
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_UPDATE_DATA'); ?></legend>
                    <div class="control-group">
                        <label for="import_package" class="control-label"><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_SELECT_FILE'); ?></label>
                        <div class="controls">
                            <input class="input_box" id="import_package" name="import_package" type="file" size="57" />
                        </div>
                    </div>
                    <div class="form-actions">
                        <input class="btn btn-primary" type="button" value="<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_UPLOAD_BOTTON'); ?>" onclick="Joomla.submitbutton()" />&nbsp;&nbsp;&nbsp;<small><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FORMATS_ACCEPTED'); ?> (.csv .xls .ods)</small>
                    </div>
                </fieldset>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
    
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'directory', JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FROM_DIRECTORY', true)); ?>
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_UPDATE_DATA'); ?></legend>
                    <div class="control-group">
                        <label for="import_directory" class="control-label"><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_SELECT_FILE_DIRECTORY'); ?></label>
                        <div class="controls">
                            <input type="text" id="import_directory" name="import_directory" class="span5 input_box" size="70" value="<?php echo $this->state->get('import.directory'); ?>" />
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="button" class="btn btn-primary" value="<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_GET_BOTTON'); ?>" onclick="Joomla.submitbutton3()" />&nbsp;&nbsp;&nbsp;<small><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FORMATS_ACCEPTED'); ?> (.csv .xls .ods)</small>
                    </div>
                </fieldset>
            <?php echo JHtml::_('bootstrap.endTab'); ?>
    
            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'url', JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FROM_URL', true)); ?>
                <fieldset class="uploadform">
                    <legend><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_UPDATE_DATA'); ?></legend>
                    <div class="control-group">
                        <label for="import_url" class="control-label"><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_SELECT_FILE_URL'); ?></label>
                        <div class="controls">
                            <input type="text" id="import_url" name="import_url" class="span5 input_box" size="70" value="http://" />
                        </div>
                    </div>
                    <div class="form-actions">
                        <input type="button" class="btn btn-primary" value="<?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_GET_BOTTON'); ?>" onclick="Joomla.submitbutton4()" />&nbsp;&nbsp;&nbsp;<small><?php echo JText::_('COM_SERMONDISTRIBUTOR_IMPORT_FORMATS_ACCEPTED'); ?> (.csv .xls .ods)</small>
                    </div>
                </fieldset>
    
            <?php echo JHtml::_('bootstrap.endTab'); ?>
        
        <?php echo JHtml::_('bootstrap.endTabSet'); ?>
		<input type="hidden" name="gettype" value="upload" />
    <?php endif; ?>
    <input type="hidden" name="task" value="import.import" />
    <?php echo JHtml::_('form.token'); ?>
</form>
</div>
