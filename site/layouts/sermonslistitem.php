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
	@subpackage		sermonslistitem.php
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
<?php echo LayoutHelper::render('isnew', $displayData); ?> <a <?php if ($displayData->params->get($displayData->viewKey.'_sermons_download_count')): ?> data-uk-tooltip title="<?php echo Text::_('COM_SERMONDISTRIBUTOR_SERMON_DOWNLOAD_COUNT'); ?>: <?php echo 4; ?>" <?php endif; ?>href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
<?php if (('preacher' == $displayData->viewKey || 'category' == $displayData->viewKey) && $displayData->params->get($displayData->viewKey.'_sermons_series')): ?>
	<a href="<?php echo $displayData->series_link; ?>" data-uk-tooltip title="<?php echo $displayData->series_name; ?>"><?php echo $displayData->series_name; ?></a>
<?php endif ;?>
<?php if (('series' == $displayData->viewKey || 'category' == $displayData->viewKey) && $displayData->params->get($displayData->viewKey.'_sermons_preacher')): ?>
	<a href="<?php echo $displayData->preacher_link; ?>" data-uk-tooltip title="<?php echo $displayData->preacher_name; ?>"><?php echo $displayData->preacher_name; ?></a>
<?php endif ;?>
<?php if ('category' != $displayData->viewKey && $displayData->params->get($displayData->viewKey.'_sermons_category')): ?>
	<a href="mailto:<?php echo $displayData->category; ?>" data-uk-tooltip title="<?php echo $displayData->category; ?>"><?php echo $displayData->category; ?></a>
<?php endif ;?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_desc') && $displayData->short_description): ?>
	<?php echo $displayData->short_description; ?>
<?php elseif ($displayData->params->get($displayData->viewKey.'_sermons_desc') && $displayData->desc): ?>
	<?php echo $displayData->desc; ?>
<?php endif; ?>
<?php if ($displayData->params->get($displayData->viewKey.'_sermons_hits')): ?>
	<em><?php echo Text::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?></em>
<?php endif ;?>
