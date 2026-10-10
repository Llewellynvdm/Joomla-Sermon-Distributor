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

	@version		6.0.0
	@created		9th July, 2026
	@package		Sermon Distributor
	@subpackage		default_body.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Sermondistributor\Utilities\Permitted\Actions;
use Joomla\CMS\User\UserFactoryInterface;

// No direct access to this file
defined('_JEXEC') or die;

$edit = "index.php?option=com_sermondistributor&view=sermon_tags&task=sermon_tag.edit";

?>
<?php foreach ($this->items as $i => $item): ?>
	<?php
		$canCheckin = $this->user->authorise('core.manage', 'com_checkin') || $item->checked_out == $this->user->id || $item->checked_out == 0;
		$userChkOut = Factory::getContainer()->
			get(UserFactoryInterface::class)->
				loadUserById((int) ($item->checked_out ?? 0));
		$canDo = Actions::get('sermon_tag', $item, 'sermon_tags');
	?>
	<tr class="row<?php echo $i % 2; ?>">
		<td class="order nowrap center hidden-phone">
		<?php if (!$this->isModal && $canDo->get('sermon_tag.edit.state')): ?>
			<?php
				$iconClass = '';
				if (!$this->saveOrder)
				{
					$iconClass = ' inactive tip-top" hasTooltip" title="' . Html::tooltipText('JORDERINGDISABLED');
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
		<?php if (!$this->isModal && $canDo->get('sermon_tag.edit')): ?>
				<?php if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo Html::_('grid.id', $i, $item->id); ?>
					<?php else: ?>
						&#9633;
					<?php endif; ?>
				<?php else: ?>
					<?php echo Html::_('grid.id', $i, $item->id); ?>
				<?php endif; ?>
		<?php else: ?>
			&#9633;
		<?php endif; ?>
		</td>
		<td class="nowrap">
			<div class="name">
				<?php if (!$this->isModal && $canDo->get('sermon_tag.edit')): ?>
					<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?>"><?php echo $this->sanitize($item->tag_name); ?></a>
					<?php if ($item->checked_out): ?>
						<?php echo Html::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'sermon_tags.', $canCheckin); ?>
					<?php endif; ?>
				<?php else: ?>
					<?php if (!$this->isModal): ?>
						<?php echo $this->sanitize($item->tag_name); ?>
					<?php else: ?>
						<?php
							$link = "{$edit}&id={$item->id}";
							$dataId = $item->{$this->getModalTitleKey()} ?? 0;
							$itemHtml = '<a href="' . $this->sanitize($link, false) . '">' . $this->sanitize($item->tag_name, false) . '</a>';
							$attribs = 'data-content-select data-content-type="com_sermondistributor.sermon_tag"'
								. ' data-id="' . $this->sanitize($dataId, false) . '"'
								. ' data-title="' . $this->sanitize($item->tag_name, false) . '"'
								. ' data-uri="' . $this->sanitize($link, false) . '"'
								. ' data-html="' . $this->sanitize($itemHtml, false) . '"';
						?>
						<a class="select-link" href="javascript:void(0)" <?php echo $attribs; ?>>
							<?php echo $this->sanitize($item->tag_name); ?>
						</a>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</td>
		<td class="nowrap">
			<div class="name">
				<?php if (!$this->isModal && $this->user->authorise('sermon.edit', 'com_sermondistributor.sermon.' . (int) ($item->sermon_id ?? 0))): ?>
					<a href="index.php?option=com_sermondistributor&view=sermons&task=sermon.edit&id=<?php echo $item->sermon_id; ?>&return=<?php echo $this->return_here; ?>"><?php echo $this->sanitize($item->sermon_name); ?></a>
				<?php else: ?>
					<?php echo $this->sanitize($item->sermon_name); ?>
				<?php endif; ?>
			</div>
		</td>
		<td class="center">
		<?php if (!$this->isModal && $canDo->get('sermon_tag.edit.state')) : ?>
				<?php if ($item->checked_out) : ?>
					<?php if ($canCheckin) : ?>
						<?php echo Html::_('jgrid.published', $item->published, $i, 'sermon_tags.', true, 'cb'); ?>
					<?php else: ?>
						<?php echo Html::_('jgrid.published', $item->published, $i, 'sermon_tags.', false, 'cb'); ?>
					<?php endif; ?>
				<?php else: ?>
					<?php echo Html::_('jgrid.published', $item->published, $i, 'sermon_tags.', true, 'cb'); ?>
				<?php endif; ?>
		<?php else: ?>
			<?php echo Html::_('jgrid.published', $item->published, $i, 'sermon_tags.', false, 'cb'); ?>
		<?php endif; ?>
		</td>
		<td class="nowrap center hidden-phone">
			<?php echo $item->id; ?>
		</td>
	</tr>
<?php endforeach; ?>