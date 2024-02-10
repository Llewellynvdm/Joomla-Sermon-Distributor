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
	@subpackage		preacherslistitem.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('JPATH_BASE') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;



?>
<a <?php if ($displayData->params->get('preachers_sermon_count')): ?> data-uk-tooltip title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>: <?php echo count($displayData->idPreacherSermonB); ?>" <?php endif; ?>href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
<?php if ($displayData->params->get('preachers_website')): ?>
	<a href="<?php echo $displayData->website; ?>" target="_blank" data-uk-tooltip title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_GO_TO_WEBSITE_OF'); ?> <?php echo $displayData->name; ?>"><?php echo $displayData->website; ?></a>
<?php endif ;?>
<?php if ($displayData->params->get('preachers_email')): ?>
	<a href="mailto:<?php echo $displayData->email; ?>" data-uk-tooltip title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_SEND_EMAIL_TO'); ?> <?php echo $displayData->name; ?>"><?php echo $displayData->email; ?></a>
<?php endif ;?>
<?php if ($displayData->params->get('preachers_desc') && $displayData->desc): ?>
	<?php echo $displayData->desc; ?>
<?php endif; ?>
<?php if ($displayData->params->get('preachers_hits')): ?>
	<em><?php echo Text::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?></em>
<?php endif ;?>
