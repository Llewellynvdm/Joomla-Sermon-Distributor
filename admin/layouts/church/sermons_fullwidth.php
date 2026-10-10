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
	@subpackage		sermons_fullwidth.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Utilities\StringHelper;
use JoomSermon\Joomla\Sermondistributor\Utilities\Permitted\Actions;
use JoomSermon\Joomla\Utilities\ArrayHelper;
use Joomla\CMS\User\UserFactoryInterface;

// No direct access to this file
defined('_JEXEC') or die;

$app = $displayData->app ?? Factory::getApplication();
$items = $displayData->vvvsermons;
$user = $displayData->user ?? $app->getIdentity();
$id = (int) ($displayData->item->id ?? 0);
// set the edit URL
$edit = "index.php?option=com_sermondistributor&view=sermons&task=sermon.edit";
// set a return value
$return = ($id) ? "index.php?option=com_sermondistributor&view=church&layout=edit&id=" . $id : "";
// check for a return value
// check for a return value
$jinput = $displayData->input ?? (method_exists($app, 'getInput') ? $app->getInput() : $app->input);
if ($_return = $jinput->get('return', null, 'base64'))
{
	$return .= "&return=" . $_return;
}
// get the GUID value
$guid = $displayData->item->guid ?? null;
// check if return value was set
if (StringHelper::check($return))
{
	// set the referral values
	$ref = $guid ? "&init_defaults=" . urlencode('{"church":"' . $guid . '"}') . "&return=" . urlencode(base64_encode($return)) : "&return=" . urlencode(base64_encode($return));
}
else
{
	$ref = $guid ? "&init_defaults=" . urlencode('{"church":"' . $guid . '"}') : "";
}
// set the create new URL
$new = "index.php?option=com_sermondistributor&view=sermons&task=sermon.add" . $ref;
// load the action object
$can = Actions::get('sermon');

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
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_CATEGORY_LABEL'); ?>
		</th>
		<th data-hide="phone">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_SERIES_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_CHURCH_LABEL'); ?>
		</th>
		<th data-hide="phone,tablet">
			<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_PREACHER_LABEL'); ?>
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
		$userChkOut = Factory::getContainer()->
			get(UserFactoryInterface::class)->
				loadUserById((int) ($item->checked_out ?? 0));
		$canDo = Actions::get('sermon', $item, 'sermons');
	?>
	<tr>
		<td>
			<?php if (!$displayData->isModal && $canDo->get('sermon.edit')): ?>
				<a href="<?php echo $edit; ?>&id=<?php echo $item->id; ?><?php echo $ref; ?>"><?php echo $displayData->sanitize($item->name); ?></a>
				<?php if ($item->checked_out): ?>
					<?php echo Html::_('jgrid.checkedout', $i, $userChkOut->name, $item->checked_out_time, 'sermons.', $canCheckin); ?>
				<?php endif; ?>
			<?php else: ?>
				<?php if (!$displayData->isModal): ?>
					<?php echo $displayData->sanitize($item->name); ?>
				<?php else: ?>
					<?php
						$link = "{$edit}&id={$item->id}";
						$dataId = $item->{$displayData->getModalTitleKey()} ?? 0;
						$itemHtml = '<a href="' . $displayData->sanitize($link, false) . '">' . $displayData->sanitize($item->name, false) . '</a>';
						$attribs = 'data-content-select data-content-type="com_sermondistributor.sermon"'
							. ' data-id="' . $displayData->sanitize($dataId, false) . '"'
							. ' data-title="' . $displayData->sanitize($item->name, false) . '"'
							. ' data-uri="' . $displayData->sanitize($link, false) . '"'
							. ' data-html="' . $displayData->sanitize($itemHtml, false) . '"';
					?>
					<a class="select-link" href="javascript:void(0)" <?php echo $attribs; ?>>
						<?php echo $displayData->sanitize($item->name); ?>
					</a>
				<?php endif; ?>
			<?php endif; ?>
		</td>
		<td>
			<?php if (!$displayData->isModal && $user->authorise('category.edit', 'com_sermondistributor.category.' . (int) ($item->category_id ?? 0))): ?>
				<a href="index.php?option=com_sermondistributor&view=categories&task=category.edit&id=<?php echo $item->category_id; ?><?php echo $ref; ?>"><?php echo $displayData->sanitize($item->category_name); ?></a>
			<?php else: ?>
				<?php echo $displayData->sanitize($item->category_name); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php if (!$displayData->isModal && $user->authorise('series.edit', 'com_sermondistributor.series.' . (int) ($item->series_id ?? 0))): ?>
				<a href="index.php?option=com_sermondistributor&view=series_list&task=series.edit&id=<?php echo $item->series_id; ?><?php echo $ref; ?>"><?php echo $displayData->sanitize($item->series_name); ?></a>
			<?php else: ?>
				<?php echo $displayData->sanitize($item->series_name); ?>
			<?php endif; ?>
		</td>
		<td>
			<?php echo $displayData->sanitize($item->church_name); ?>
		</td>
		<td>
			<?php if (!$displayData->isModal && $user->authorise('preacher.edit', 'com_sermondistributor.preacher.' . (int) ($item->preacher_id ?? 0))): ?>
				<a href="index.php?option=com_sermondistributor&view=preachers&task=preacher.edit&id=<?php echo $item->preacher_id; ?><?php echo $ref; ?>"><?php echo $displayData->sanitize($item->preacher_name); ?></a>
			<?php else: ?>
				<?php echo $displayData->sanitize($item->preacher_name); ?>
			<?php endif; ?>
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
		<td colspan="7">
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
