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
	@subpackage		default_main.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Language\Text;

// No direct access to this file
defined('_JEXEC') or die;

?>
<?php if (isset($this->icons['main']) && is_array($this->icons['main']) && !empty($this->icons['main'])) : ?>
    <div class="dashboard-icons" role="list">
		<?php foreach ($this->icons['main'] as $icon) : ?>
            <div class="dashboard-icon-item" role="listitem">
                <a class="dashboard-icon-link" href="<?php echo $icon->url; ?>">
					<span class="dashboard-icon-image">
						<img
                            alt="<?php echo $icon->alt; ?>"
                            src="components/com_sermondistributor/assets/images/icons/<?php echo $icon->image; ?>"
                            loading="lazy"
                            decoding="async"
                        >
					</span>
                    <span class="dashboard-icon-title">
						<?php echo Text::_($icon->name); ?>
					</span>
                </a>
            </div>
		<?php endforeach; ?>
    </div>
<?php else : ?>
    <div class="alert alert-danger">
        <h4 class="alert-heading">
			<?php echo Text::_("Permission denied, or not correctly set"); ?>
        </h4>
        <div>
			<?php echo Text::_("Please notify your System Administrator if result is unexpected."); ?>
        </div>
    </div>
<?php endif; ?>