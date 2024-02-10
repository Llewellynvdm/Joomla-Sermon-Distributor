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

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermons_fullwidth.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\Utilities\ArrayHelper;

// set the defaults
$items = $displayData->vwcsermons;
$user = Factory::getUser();
$id = $displayData->item->id;
// set the edit URL
$edit = "index.php?option=com_sermondistributor&view=sermons&task=sermon.edit";
// set a return value
$return = ($id) ? "index.php?option=com_sermondistributor&view=series&layout=edit&id=" . $id : "";
// check for a return value
$jinput = Factory::getApplication()->input;
if ($_return = $jinput->get('return', null, 'base64'))
{
	$return .= "&return=" . $_return;
}
// check if return value was set
if (StringHelper::check($return))
{
	// set the referral values
	$ref = ($id) ? "&ref=series&refid=" . $id . "&return=" . urlencode(base64_encode($return)) : "&return=" . urlencode(base64_encode($return));
}
else
{
	$ref = ($id) ? "&ref=series&refid=" . $id : "";
}
// set the create new URL
$new = "index.php?option=com_sermondistributor&view=sermons&task=sermon.edit" . $ref;
// load the action object
$can = SermondistributorHelper::getActions('sermon');

?>
<div class="form-vertical">
<?php if ($can->get('sermon.create')): ?>
	<a class="btn btn-small btn-success" href="<?php echo $new; ?>"><span class="icon-new icon-white"></span> <?php echo Text::_('COM_SERMONDISTRIBUTOR_NEW'); ?></a><br /><br />
<?php endif; ?>
<?php if (ArrayHelper::check($items)): ?>
<table class="footable table data sermons metro-blue" data-page-size="20" data-filter="#filter_sermons">
<thead>
	<tr>
		<th data-toggle="true">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_NAME_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_PREACHER_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_SERIES_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_SHORT_DESCRIPTION_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_SERMONS_CATEGORIES'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_LINK_TYPE_LABEL'); ?>
		</th>
		<th data-hide="all">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_SOURCE_LABEL'); ?>
		</th>
		<th width="10" data-hide="phone,tablet">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_STATUS'); ?>
		</th>
		<th width="5" data-type="numeric" data-hide="phone,tablet">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_ID'); ?>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($items as $i => $item): ?>
	<?php
		$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
		$userChkOut = Factory::getUser($item->checked_out);
		$canDo = SermondistributorHelper::getActions('sermon',$item,'sermons');
	?>
	<tr>
		<td>
			<?php if ($canDo->get('sermon.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?><?php echo $ref; ?>"><?php echo $displayData->escape($item->name); ?></a>
				<?php if ($item->checked_out): ?>
					<?php echo Html::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'sermons.', $canCheckin); ?>
				<?php endif; ?>
			<?php else: ?>
				<?php echo $displayData->escape($item->name); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php if ($user->authorise('preacher.edit', 'com_sermondistributor.preacher.' . (int) $item->preacher)): ?>
				<a href="index.php?option=com_sermondistributor&view=preachers&task=preacher.edit&id=<?php echo $item->preacher; ?><?php echo $ref; ?>"><?php echo $displayData->escape($item->preacher_name); ?></a>
			<?php else: ?>
				<?php echo $displayData->escape($item->preacher_name); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->series_name); ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->short_description); ?>
		</td>
		<td>
			<?php if ($user->authorise('core.edit', 'com_sermondistributor.sermon.category.' . (int)$item->catid)): ?>
				<a href="index.php?option=com_categories&task=category.edit&id=<?php echo (int)$item->catid; ?>&extension=com_sermondistributor.sermon"><?php echo $displayData->escape($item->category_title); ?></a>
			<?php else: ?>
				<?php echo $displayData->escape($item->category_title); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo Text::_($item->link_type); ?>
		</td>
		<td>
			<?php echo Text::_($item->source); ?>
		</td>
		<?php if ($item->published == 1): ?>
			<td class="center"  data-value="1">
				<span class="status-metro status-published" title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_PUBLISHED');  ?>">
					<?php echo Text::_('COM_SERMONDISTRIBUTOR_PUBLISHED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 0): ?>
			<td class="center"  data-value="2">
				<span class="status-metro status-inactive" title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_INACTIVE');  ?>">
					<?php echo Text::_('COM_SERMONDISTRIBUTOR_INACTIVE'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 2): ?>
			<td class="center"  data-value="3">
				<span class="status-metro status-archived" title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_ARCHIVED');  ?>">
					<?php echo Text::_('COM_SERMONDISTRIBUTOR_ARCHIVED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == -2): ?>
			<td class="center"  data-value="4">
				<span class="status-metro status-trashed" title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_TRASHED');  ?>">
					<?php echo Text::_('COM_SERMONDISTRIBUTOR_TRASHED'); ?>
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
		<td colspan="9">
			<div class="pagination pagination-centered"></div>
		</td>
	</tr>
</tfoot>
</table>
<?php else: ?>
	<div class="alert alert-no-items">
		<?php echo Text::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	</div>
<?php endif; ?>
</div>
