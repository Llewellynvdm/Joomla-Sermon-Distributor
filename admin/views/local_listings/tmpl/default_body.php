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

	@version		@update number 11 of this MVC
	@build			31st March, 2017
	@created		20th November, 2016
	@package		Sermon Distributor
	@subpackage		default_body.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access'); 

$edit = "index.php?option=com_sermondistributor&view=local_listings&task=local_listing.edit";

?>
<?php foreach ($this->items as $i => $item): ?>
	<?php
		$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		$userChkOut = JFactory::getUser($item->checked_out);
		$canDo = SermondistributorHelper::getActions('local_listing',$item,'local_listings');
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="order nowrap center hidden-phone">
		<?php if ($canDo->get('local_listing.edit.state')): ?>
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
		<?php if ($canDo->get('local_listing.edit')): ?>
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
			<?php if ($canDo->get('local_listing.edit')): ?>
				<div class="name">
					<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>"><?php echo $this->escape($item->name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo JHtml::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'local_listings.', $canCheckin); ?>
					<?php endif; ?>
				</div>
			<?php else: ?>
				<div class="name"><?php echo $this->escape($item->name); ?></div>
			<?php endif; ?>
		</td>
		<td class="hidden-phone">
			<?php echo JText::_($item->build); ?>
		</td>
		<td class="hidden-phone">
			<?php echo $this->escape($item->size); ?>
		</td>
		<td class="nowrap">
			<?php if ($this->user->authorise('external_source.edit', 'com_sermondistributor.external_source.' . (int)$item->external_source)): ?>
				<div class="name">
					<a href="index.php?option=com_sermondistributor&view=external_sources&task=external_source.edit&id=<?php echo $item->external_source; ?>&ref=local_listings"><?php echo $this->escape($item->external_source_description); ?></a>
				</div>
			<?php else: ?>
				<div class="name"><?php echo $this->escape($item->external_source_description); ?></div>
			<?php endif; ?>
		</td>
		<td class="hidden-phone">
			<?php echo $this->escape($item->key); ?>
		</td>
		<td class="center">
		<?php if ($canDo->get('local_listing.edit.state')) : ?>
				<?php if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'local_listings.', true, 'cb'); ?>
					<?php else: ?>
						<?php echo JHtml::_('jgrid.published', $item->published, $i, 'local_listings.', false, 'cb'); ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo JHtml::_('jgrid.published', $item->published, $i, 'local_listings.', true, 'cb'); ?>
				<?php endif; ?>
		<?php else: ?>
			<?php echo JHtml::_('jgrid.published', $item->published, $i, 'local_listings.', false, 'cb'); ?>
		<?php endif; ?>
		</td>
		<td class="nowrap center hidden-phone">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>