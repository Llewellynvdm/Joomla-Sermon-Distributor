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
	@subpackage		default_vdm.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Language\Text;
use JoomSermon\Joomla\Utilities\ArrayHelper;

// No direct access to this file
defined('_JEXEC') or die;

?>
<div class="com-sermondistributor-dashboard-details">
	<div class="com-sermondistributor-dashboard-details__image mb-4">
		<img
			class="img-fluid w-100"
			alt="<?php echo Text::_('COM_SERMONDISTRIBUTOR'); ?>"
			src="components/com_sermondistributor/assets/images/vdm-component.jpg"
			loading="lazy"
			decoding="async"
		>
	</div>
	<ul class="list-group list-group-flush mb-4">
		<li class="list-group-item d-flex flex-wrap justify-content-between gap-2">
			<span>
				<strong><?php echo Text::_('COM_SERMONDISTRIBUTOR_VERSION'); ?>:</strong>
				<?php echo $this->manifest->version; ?>
			</span>
			<span class="update-notice" id="component-update-notice"></span>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_SERMONDISTRIBUTOR_DATE'); ?>:</strong>
			<?php echo $this->manifest->creationDate; ?>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_SERMONDISTRIBUTOR_AUTHOR'); ?>:</strong>
			<a href="mailto:<?php echo $this->manifest->authorEmail; ?>">
				<?php echo $this->manifest->author; ?>
			</a>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_SERMONDISTRIBUTOR_WEBSITE'); ?>:</strong>
			<a
				href="<?php echo $this->manifest->authorUrl; ?>"
				target="_blank"
				rel="noopener noreferrer"
			>
				<?php echo $this->manifest->authorUrl; ?>
			</a>
		</li>
		<li class="list-group-item">
			<strong><?php echo Text::_('COM_SERMONDISTRIBUTOR_LICENSE'); ?>:</strong>
			<?php echo $this->manifest->license; ?>
		</li>
		<li class="list-group-item">
			<strong><?php echo $this->manifest->copyright; ?></strong>
		</li>
	</ul>
	<?php if (ArrayHelper::check($this->contributors)) : ?>
		<div class="com-sermondistributor-dashboard-details__contributors mt-4">
			<h3 class="h5 mb-3">
				<?php if (count($this->contributors) > 1) : ?>
					<?php echo Text::_('COM_SERMONDISTRIBUTOR_CONTRIBUTORS'); ?>
				<?php else : ?>
					<?php echo Text::_('COM_SERMONDISTRIBUTOR_CONTRIBUTOR'); ?>
				<?php endif; ?>
			</h3>
			<ul class="list-group list-group-flush">
				<?php foreach ($this->contributors as $contributor) : ?>
					<li class="list-group-item">
						<strong><?php echo $contributor['title']; ?>:</strong>
						<?php echo $contributor['name']; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
</div>