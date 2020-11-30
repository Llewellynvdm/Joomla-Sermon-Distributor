<?php
/*-------------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
 ____                                                  ____                 __               __               __
/\  _`\                                               /\  _`\   __         /\ \__         __/\ \             /\ \__
\ \,\L\_\     __   _ __    ___ ___     ___     ___    \ \ \/\ \/\_\    ____\ \ ,_\  _ __ /\_\ \ \____  __  __\ \ ,_\   ___   _ __
 \/_\__ \   /'__`\/\`'__\/' __` __`\  / __`\ /' _ `\   \ \ \ \ \/\ \  /',__\\ \ \/ /\`'__\/\ \ \ '__`\/\ \/\ \\ \ \/  / __`\/\`'__\
   /\ \L\ \/\  __/\ \ \/ /\ \/\ \/\ \/\ \L\ \/\ \/\ \   \ \ \_\ \ \ \/\__, `\\ \ \_\ \ \/ \ \ \ \ \L\ \ \ \_\ \\ \ \_/\ \L\ \ \ \/
   \ `\____\ \____\\ \_\ \ \_\ \_\ \_\ \____/\ \_\ \_\   \ \____/\ \_\/\____/ \ \__\\ \_\  \ \_\ \_,__/\ \____/ \ \__\ \____/\ \_\
    \/_____/\/____/ \/_/  \/_/\/_/\/_/\/___/  \/_/\/_/    \/___/  \/_/\/___/   \/__/ \/_/   \/_/\/___/  \/___/   \/__/\/___/  \/_/

/------------------------------------------------------------------------------------------------------------------------------------/

	@version		2.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_categories-table.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Column counter
$column_nr = 1;
// build the table class
$color = $this->params->get('categories_table_color');
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
		$tableClass = ' categories-table';
	break;
	default:
		// Light Blue and other
		$tableClass = '';
	break;
}

?>
<table class="footable<?php echo $tableClass; ?>" data-page-size="100">
	<thead>
		<tr>
			<th data-toggle="true"><?php echo JText::_('COM_SERMONDISTRIBUTOR_NAME'); ?></th>
			<?php if ($this->params->get('categories_desc')): ?>
				<th data-hide="all"><?php echo JText::_('COM_SERMONDISTRIBUTOR_DESCRIPTION'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('categories_hits')): ?>
				<th data-type="numeric"><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); $column_nr++; ?></th>
			<?php endif; ?>
			<?php if ($this->params->get('categories_sermon_count')): ?>
				<th data-type="numeric" data-hide="phone,tablet"><?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); $column_nr++;?></th>
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
		<tr><?php $item->params = $this->params; echo JLayoutHelper::render('categoriesrow', $item); ?></tr>
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
