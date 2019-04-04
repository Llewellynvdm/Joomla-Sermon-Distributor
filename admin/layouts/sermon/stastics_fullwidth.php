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
	@subpackage		stastics_fullwidth.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// set the defaults
$items = $displayData->vwbstastics;
$user = JFactory::getUser();
$id = $displayData->item->id;
// set the edit URL
$edit = "index.php?option=com_sermondistributor&view=statistics&task=statistic.edit";
// set a return value
$return = ($id) ? "index.php?option=com_sermondistributor&view=sermon&layout=edit&id=" . $id : "";
// check for a return value
$jinput = JFactory::getApplication()->input;
if ($_return = $jinput->get('return', null, 'base64'))
{
	$return .= "&return=" . $_return;
}
// check if return value was set
if (SermondistributorHelper::checkString($return))
{
	// set the referral values
	$ref = ($id) ? "&ref=sermon&refid=" . $id . "&return=" . urlencode(base64_encode($return)) : "&return=" . urlencode(base64_encode($return));
}
else
{
	$ref = ($id) ? "&ref=sermon&refid=" . $id : "";
}

?>
<div class="form-vertical">
<?php if (SermondistributorHelper::checkArray($items)): ?>
<table class="footable table data statistics metro-blue" data-page-size="20" data-filter="#filter_statistics">
<thead>
	<tr>
		<th data-toggle="true">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_FILENAME_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_SERMON_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_PREACHER_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_SERIES_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_COUNTER_LABEL'); ?>
		</th>
		<th width="10" data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_STATUS'); ?>
		</th>
		<th width="5" data-type="numeric" data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_ID'); ?>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($items as $i => $item): ?>
	<?php
		$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = SermondistributorHelper::getActions('statistic',$item,'statistics');
	?>
	<tr>
		<td>
			<?php if ($canDo->get('statistic.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?><?php echo $ref; ?>"><?php echo $item->filename; ?></a>
				<?php if ($item->checked_out): ?>
					<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'statistics.', $canCheckin); ?>
				<?php endif; ?>
			<?php else: ?>
				<?php echo $item->filename; ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->sermon_name); ?>
		</td>
		<td>
			<?php if ($user->authorise('preacher.edit', 'com_sermondistributor.preacher.' . (int)$item->preacher)): ?>
				<a href="index.php?option=com_sermondistributor&view=preachers&task=preacher.edit&id=<?php echo $item->preacher; ?><?php echo $ref; ?>"><?php echo $displayData->escape($item->preacher_name); ?></a>
			<?php else: ?>
				<?php echo $displayData->escape($item->preacher_name); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php if ($user->authorise('series.edit', 'com_sermondistributor.series.' . (int)$item->series)): ?>
				<a href="index.php?option=com_sermondistributor&view=all_series&task=series.edit&id=<?php echo $item->series; ?><?php echo $ref; ?>"><?php echo $displayData->escape($item->series_name); ?></a>
			<?php else: ?>
				<?php echo $displayData->escape($item->series_name); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->counter); ?>
		</td>
		<?php if ($item->published == 1):?>
			<td class="center"  data-value="1">
				<span class="status-metro status-published" title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_PUBLISHED');  ?>">
					<?php echo JText::_('COM_SERMONDISTRIBUTOR_PUBLISHED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 0):?>
			<td class="center"  data-value="2">
				<span class="status-metro status-inactive" title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_INACTIVE');  ?>">
					<?php echo JText::_('COM_SERMONDISTRIBUTOR_INACTIVE'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 2):?>
			<td class="center"  data-value="3">
				<span class="status-metro status-archived" title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_ARCHIVED');  ?>">
					<?php echo JText::_('COM_SERMONDISTRIBUTOR_ARCHIVED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == -2):?>
			<td class="center"  data-value="4">
				<span class="status-metro status-trashed" title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_TRASHED');  ?>">
					<?php echo JText::_('COM_SERMONDISTRIBUTOR_TRASHED'); ?>
				</span>
			</td>
		<?php endif; ?>
		<td class="nowrap center hidden-phone">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>
</tbody>
<tfoot class="hide-if-no-paging">
	<tr>
		<td colspan="7">
			<div class="pagination pagination-centered"></div>
		</td>
	</tr>
</tfoot>
</table>
<?php else: ?>
	<div class="alert alert-no-items">
		<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	</div>
<?php endif; ?>
</div>
