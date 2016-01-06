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

	@version		1.3.0
	@build			6th January, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		categorieslistitem.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file

defined('JPATH_BASE') or die('Restricted access');



?>
<a <?php if ($displayData->params->get('categories_sermon_count')): ?> data-uk-tooltip title="<?php echo JText::_('COM_SERMONDISTRIBUTOR_SERMON_COUNT'); ?>: <?php echo count($displayData->idSeriesSermonB); ?>" <?php endif; ?>href="<?php echo $displayData->link; ?>"><?php echo $displayData->name; ?></a>
<?php if ($displayData->params->get('categories_desc') && $displayData->desc): ?>
	<?php echo $displayData->desc; ?>
<?php endif; ?>
<?php if ($displayData->params->get('categories_hits')): ?>
	<em><?php echo JText::_('COM_SERMONDISTRIBUTOR_HITS'); ?>: <?php echo $displayData->hits; ?></em>
<?php endif ;?>
