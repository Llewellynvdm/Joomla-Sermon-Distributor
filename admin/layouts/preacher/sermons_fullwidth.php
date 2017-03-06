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

	@version		1.4.1
	@build			28th February, 2017
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermons_fullwidth.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('_JEXEC') or die('Restricted access');

// set the defaults
$items	= $displayData->vvvsermons;
$user	= JFactory::getUser();
$id	= $displayData->item->id;
$edit	= "index.php?option=com_sermondistributor&view=sermons&task=sermon.edit";
$ref	= ($id) ? "&ref=preacher&refid=".$id : "";
$new	= "index.php?option=com_sermondistributor&view=sermon&layout=edit".$ref;
$can	= SermondistributorHelper::getActions('sermon');

?>
<div class="form-vertical">
<?php if ($can->get('sermon.create')): ?>
	<a class="btn btn-small btn-success" href="<?php echo $new; ?>"><span class="icon-new icon-white"></span> <?php echo JText::_('COM_SERMONDISTRIBUTOR_NEW'); ?></a><br /><br />
<?php endif; ?>
<?php if (SermondistributorHelper::checkArray($items)): ?>
<table class="footable table data sermons metro-blue" data-page-size="20" data-filter="#filter_sermons">
<thead>
	<tr>
		<th data-toggle="true">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_NAME_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_PREACHER_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_SERIES_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_SHORT_DESCRIPTION_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_SERMON_CATEGORY'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_LINK_TYPE_LABEL'); ?>
		</th>
		<th data-hide="all">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_SOURCE_LABEL'); ?>
		</th>
		<th width="10" data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_STATUS'); ?>
		</th>
		<th width="5" data-type="numeric" data-hide="phone,tablet">
			<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_ID'); ?>
		</th>
	</tr>
</thead>
<tbody>
<?php foreach ($items as $i => $item): ?>
	<?php
		$canCheckin = $user->authorise('core.manage', 'com_checkin') || $item->checked_out == $user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = SermondistributorHelper::getActions('sermon',$item,'sermons');
	?>
	<tr>
		<td class="nowrap">
			<?php if ($canDo->get('sermon.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>&ref=preacher&refid=<?php echo $id; ?>"><?php echo $displayData->escape($item->name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'sermons.', $canCheckin); ?>
					<?php endif; ?>
			<?php else: ?>
				<div class="name"><?php echo $displayData->escape($item->name); ?></div>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->preacher_name); ?>
		</td>
		<td class="nowrap">
			<?php if ($user->authorise('series.edit', 'com_sermondistributor.series.' . (int)$item->series)): ?>
				<a href="index.php?option=com_sermondistributor&view=all_series&task=series.edit&id=<?php echo $item->series; ?>&ref=preacher&refid=<?php echo $id; ?>"><?php echo $displayData->escape($item->series_name); ?></a>
			<?php else: ?>
				<div class="name"><?php echo $displayData->escape($item->series_name); ?></div>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $displayData->escape($item->short_description); ?>
		</td>
		<td class="nowrap">
			<?php if ($user->authorise('core.edit', 'com_sermondistributor.sermons.category.' . (int)$item->catid)): ?>
				<a href="index.php?option=com_categories&task=category.edit&id=<?php echo (int)$item->catid; ?>&extension=com_sermondistributor.sermons"><?php echo $displayData->escape($item->category_title); ?></a>
			<?php else: ?>
				<?php echo $displayData->escape($item->category_title); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo JText::_($item->link_type); ?>
		</td>
		<td>
			<?php echo JText::_($item->source); ?>
		</td>
		<?php if ($item->published == 1):?>
			<td class="center"  data-value="1">
				<span class="status-metro status-published" title="<?php echo JText::_('PUBLISHED');  ?>">
					<?php echo JText::_('PUBLISHED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 0):?>
			<td class="center"  data-value="2">
				<span class="status-metro status-inactive" title="<?php echo JText::_('INACTIVE');  ?>">
					<?php echo JText::_('INACTIVE'); ?>
				</span>
			</td>
		<?php elseif ($item->published == 2):?>
			<td class="center"  data-value="3">
				<span class="status-metro status-archived" title="<?php echo JText::_('ARCHIVED');  ?>">
					<?php echo JText::_('ARCHIVED'); ?>
				</span>
			</td>
		<?php elseif ($item->published == -2):?>
			<td class="center"  data-value="4">
				<span class="status-metro status-trashed" title="<?php echo JText::_('ARCHIVED');  ?>">
					<?php echo JText::_('ARCHIVED'); ?>
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
		<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
	</div>
<?php endif; ?>
</div>
