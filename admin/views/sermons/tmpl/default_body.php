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

	@version		2.0.x
	@build			3rd March, 2018
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		default_body.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

$edit = "index.php?option=com_sermondistributor&view=sermons&task=sermon.edit";

?>
<?php foreach ($this->items as $i => $item): ?>
	<?php
		$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = SermondistributorHelper::getActions('sermon',$item,'sermons');
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="order nowrap center hidden-phone">
		<?php if ($canDo->get('sermon.edit.state')): ?>
			<?php
				if ($this->saveOrder)
				{
					$iconClass = ' inactive';
				}
				else
				{
					$iconClass = ' inactive tip-top" hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
				}
			?>
			<span class="sortable-handler<?php echo $iconClass; ?>">
				<i class="icon-menu"></i>
			</span>
			<?php if ($this->saveOrder) : ?>
				<input type="text" style="display:none" name="order[]" size="5"
				value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
			<?php endif; ?>
		<?php else: ?>
			&#8942;
		<?php endif; ?>
		</td>
		<td class="nowrap center">
		<?php if ($canDo->get('sermon.edit')): ?>
				<?php if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo JHtml::_('grid.id', $i, $item->id); ?>
					<?php else: ?>
						&#9633;
					<?php endif; ?>
				<?php else: ?>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				<?php endif; ?>
		<?php else: ?>
			&#9633;
		<?php endif; ?>
		</td>
		<td class="nowrap">
			<?php if ($canDo->get('sermon.edit')): ?>
				<div class="name">
					<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>"><?php echo $this->escape($item->name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'sermons.', $canCheckin); ?>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<div class="name"><?php echo $this->escape($item->name); ?></div>
			<?php endif; ?>
		</td>
		<td class="nowrap">
			<?php if ($this->user->authorise('preacher.edit', 'com_sermondistributor.preacher.' . (int)$item->preacher)): ?>
				<div class="name">
					<a href="index.php?option=com_sermondistributor&view=preachers&task=preacher.edit&id=<?php echo $item->preacher; ?>&ref=sermons"><?php echo $this->escape($item->preacher_name); ?></a>
				</div>
			<?php else: ?>
				<div class="name"><?php echo $this->escape($item->preacher_name); ?></div>
			<?php endif; ?>
		</td>
		<td class="nowrap">
			<?php if ($this->user->authorise('series.edit', 'com_sermondistributor.series.' . (int)$item->series)): ?>
				<div class="name">
					<a href="index.php?option=com_sermondistributor&view=all_series&task=series.edit&id=<?php echo $item->series; ?>&ref=sermons"><?php echo $this->escape($item->series_name); ?></a>
				</div>
			<?php else: ?>
				<div class="name"><?php echo $this->escape($item->series_name); ?></div>
			<?php endif; ?>
		</td>
		<td class="hidden-phone">
			<?php echo $this->escape($item->short_description); ?>
		</td>
		<td class="nowrap">
			<?php if ($this->user->authorise('core.edit', 'com_sermondistributor.sermons.category.' . (int)$item->catid)): ?>
				<a href="index.php?option=com_categories&task=category.edit&id=<?php echo (int)$item->catid; ?>&extension=com_sermondistributor.sermons"><?php echo $this->escape($item->category_title); ?></a>
			<?php else: ?>
				<?php echo $this->escape($item->category_title); ?>
			<?php endif; ?>
		</td>
		<td class="hidden-phone">
			<?php echo JText::_($item->link_type); ?>
		</td>
		<td class="hidden-phone">
			<?php echo JText::_($item->source); ?>
		</td>
		<td class="center">
		<?php if ($canDo->get('sermon.edit.state')) : ?>
				<?php if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'sermons.', true, 'cb'); ?>
					<?php else: ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'sermons.', false, 'cb'); ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'sermons.', true, 'cb'); ?>
				<?php endif; ?>
		<?php else: ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'sermons.', false, 'cb'); ?>
		<?php endif; ?>
		</td>
		<td class="nowrap center hidden-phone">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>