<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			30th November, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		batchselection.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

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