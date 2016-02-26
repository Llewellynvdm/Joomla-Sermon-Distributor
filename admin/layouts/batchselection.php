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
	@subpackage		batchselection.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('JPATH_BASE') or die;

JHtmlBehavior::core();
$divWrapper 	= range(1,120,2);
$counter 		= 0;
?>
<?php if ($displayData->ListSelection) : ?>
<div class="row-fluid">
	<?php foreach ($displayData->ListSelection as $ListSelection) : ?>
    <div class="control-group span6">
    	<div class="controls">
		<label for="<?php echo $ListSelection['name']; ?>" class="element-invisible"><?php echo $ListSelection['label']; ?></label>
		<select name="<?php echo $ListSelection['name']; ?>" id="<?php echo $ListSelection['name']; ?>" class="span12 small">
			<?php if (!$ListSelection['noDefault']) : ?>
				<option value=""><?php echo $ListSelection['label']; ?></option>
			<?php endif; ?>
			<?php echo $ListSelection['options']; ?>
		</select>
      	</div>
	</div>
		<?php if (in_array($counter,$divWrapper)) : ?>
</div>
<div class="row-fluid">
		<?php endif; ?>
        <?php $counter++; ?>
	<?php endforeach; ?>
</div>
<div class="control-group radio" id="batch-move-copy">
	<div class="controls">
        <label class="radio" id="batch[move_copy]c-lbl" for="batch[move_copy]c">
        <input type="radio" value="c" id="batch[move_copy]c" name="batch[move_copy]"><?php echo JText::_('Copy'); ?></label>
        <label class="radio" id="batch[move_copy]m-lbl" for="batch[move_copy]m">
        <input type="radio" checked="checked" value="m" id="batch[move_copy]m" name="batch[move_copy]"><?php echo JText::_('Update'); ?></label>
    </div>
</div>
<?php endif; ?>