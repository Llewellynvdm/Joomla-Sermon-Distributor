<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_sermons-table.php
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

// Column counter
$column_nr = 1;
// build the table class
$color = $this->params->get('preacher_sermons_table_color');
switch ($color)
{
	case 1:
		// Red
		$tableClass = ' metro-red';
	break;
	case 2:
		// Purple
		$tableClass = ' metro-purple';
	break;
	case 3:
		// Green
		$tableClass = ' metro-green';
	break;
	case 4:
		// Dark Blue
		$tableClass = ' metro-blue';
	break;
	case 6:
		// Uikit style
		$tableClass = ' uk-table';
	break;
	case 7:
		// Custom Style
		$tableClass = ' preacher-sermons-table';
	break;
	default:
		// Light Blue and other
		$tableClass = '';
	break;
}

?>
<br />
<table class="footable<?php echo $tableClass; ?>" data-page-size="100">
	<thead>
		<tr>
			<th data-toggle="true"><?php echo JText::_('COM_SERMONDISTRIBUTOR_NAME'); ?></th>
			<?php if ($this->params->get('preacher_sermons_desc')): ?>
				<th data-hide="all"><?php echo JText::_('COM_SERMONDISTRIBUTOR_DESCRIPTION'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('preacher_sermons_preacher', 0)): ?>
				<th data-hide="phone,tablet"><?php echo JText::_('COM_SERMONDISTRIBUTOR_PREACHER'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('preacher_sermons_series', 0)): ?>
				<th data-hide="phone,tablet"><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERIES'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('preacher_sermons_category', 0)): ?>
				<th data-hide="phone,tablet" ><?php echo JText::_('COM_SERMONDISTRIBUTOR_CATEGORY'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('preacher_sermons_downloads')): ?>
				<th><?php echo JText::_('COM_SERMONDISTRIBUTOR_FILES'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('preacher_sermons_download_counter')): ?>
				<th data-hide="phone,tablet" data-type="numeric"><?php echo JText::_('COM_SERMONDISTRIBUTOR_DOWNLOADS'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('preacher_sermons_hits')): ?>
				<th data-hide="phone,tablet" data-type="numeric"><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); $column_nr++; ?></th>
			<?php endif; ?>
		</tr>
	</thead>
	<tfoot class="hide-if-no-paging">
		<tr>
			<td colspan="<?php echo $column_nr; ?>">
				<div class="pagination pagination-centered"></div>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php foreach ($this->items as $item): ?>
		<tr><?php $item->params = $this->params; echo JLayoutHelper::render('sermonsrow', $item); ?></tr>
	<?php endforeach; ?>
	</tbody>
</table>

<script>
// page loading pause
jQuery(window).load(function() {
	// do some stuff once page is loaded
	jQuery('.footable').footable();
});
</script>
