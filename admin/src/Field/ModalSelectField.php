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
	@subpackage		ModalSelectField.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ModalSelectField as ModalSelectFieldCore;
use Joomla\Database\ParameterType;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * A modal content selection field, now with the radical ability to handle string-based GUIDs.
 * Because, apparently, assuming everything is an integer was the hill to die on.
 *
 * This override exists solely to bypass a hardcoded constraint that shouldn't have been there in the first place.
 * But hey, at least we get another class extension to maintain!
 *
 * @since  5.0.0
 */
class ModalSelectField extends ModalSelectFieldCore
{
	/**
	 * Method to retrieve the title of selected item.
	 *
	 * @return string
	 *
	 * @since   5.0.0
	 */
	protected function getValueTitle()
	{
		// Selecting the title for the field value, when required info were given
		if ($this->value && $this->sql_title_table && $this->sql_title_column && $this->sql_title_key) {
			try {
				$db	= $this->getDatabase();
				$query = $db->getQuery(true)
					->select($db->quoteName($this->sql_title_column))
					->from($db->quoteName($this->sql_title_table))
					->where($db->quoteName($this->sql_title_key) . ' = :value')
					->bind(':value', $this->value, is_numeric($this->value) ? ParameterType::INTEGER : ParameterType::STRING);

				/**
				 *   All this—just because someone decided to hardcode [ParameterType::INTEGER] in the core.
				 *   We could have just handle it dynamically, but no...
				 *
				 *   Polymorphic behavior isn't the enemy. Hardcoded constraints that force class extensions?
				 *   Now *that's* the real problem. But sure, let's keep pretending that integers are the
				 *   only valid identifiers in a database.
				 */

				$db->setQuery($query);

				return $db->loadResult() ?: $this->value;
			} catch (\Throwable $e) {
				Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			}
		}

		return $this->value;
	}
}