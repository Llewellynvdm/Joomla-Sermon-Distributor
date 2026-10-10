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
	@subpackage		modalreturn.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Router\Route;

// No direct access to this file
defined('_JEXEC') or die;

/** @var \JoomSermon\Component\Sermondistributor\Administrator\View\Church\HtmlView $this */

$icon = 'icon-check';
$title_key = $this->item->guid ?? '';
$title_column = $this->item->name ?? '';
$data = [
	'contentType' => 'com_sermondistributor.church',
	'id' => $title_key,
	'title' => $title_column,
	'uri' => Route::_('index.php?option=com_sermondistributor&layout=modal&tmpl=component&id='. (int) ($this->item->id ?? 0))
];

// Add Content select script
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $this->getDocument()->getWebAssetManager();
$wa->useScript('modal-content-select');

// The data for Content select script
$this->getDocument()->addScriptOptions('content-select-on-load', $data, false);

?>

<div class="px-4 py-5 my-5 text-center">
    <span class="fa-8x mb-4 <?php echo $icon; ?>" aria-hidden="true"></span>
    <h1 class="display-5 fw-bold"><?php echo $this->sanitize($title_column, false); ?></h1>
    <div class="col-lg-6 mx-auto">
        <p class="lead mb-4">
            <?php echo $this->sanitize($title_column, false); ?>
        </p>
    </div>
</div>