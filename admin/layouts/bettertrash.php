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

	@version		2.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		batchselection.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html

	A sermon distributor that links to Dropbox.

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('JPATH_BASE') or die;

?>
<?php if ($displayData->state->get('filter.published') == -2 && ($displayData->canState && $displayData->canDelete)) : ?>
    <script>
        jQuery("#toolbar-delete button").toggleClass("btn-danger");
        function emptyTrash() {
            if (document.adminForm.boxchecked.value == 0) {
                document.adminForm.elements['checkall-toggle'].checked=1;
                Joomla.checkAll(document.adminForm.elements['checkall-toggle']);
                if(confirm('<?= JText::_("Are you sure you want to delete? Confirming will permanently delete the selected item(s)!") ?>')) {
                    Joomla.submitbutton('<?= $displayData->get("name") ?>.delete');
                } else {
                    document.adminForm.elements['checkall-toggle'].checked=0;
                    Joomla.checkAll(document.adminForm.elements['checkall-toggle']);
                }
            } else {
                if (confirm('<?= JText::_("Are you sure you want to delete? Confirming will permanently delete the selected item(s)!") ?>')) {
                    Joomla.submitbutton('<?= $displayData->get("name") ?>.delete');
                };
            }
            return false;
        }
        function exitTrash() {
            document.adminForm.filter_published.selectedIndex = 0;
            document.adminForm.submit();
            return false;
        }
    </script>
    <div class="alert alert-error">
        <h4 class="alert-heading"><span class="icon-trash"></span> <?= JText::_("Trashed items") ?></h4><p><?= JText::_("You are currently viewing the trashed items.") ?></p>
        <button onclick="emptyTrash();" class="btn btn-small btn-danger"><span class="icon-delete" aria-hidden="true"></span> <?= JText::_("Empty trash") ?></button>
        <button onclick="exitTrash();" class="btn btn-small"><span class="icon-back" aria-hidden="true"></span> <?= JText::_("Exit trash") ?></button>
    </div>
<?php endif; ?>
