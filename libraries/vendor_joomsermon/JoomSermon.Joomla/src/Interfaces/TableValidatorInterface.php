<?php

/***[JCBGUI.power.licensing_template.855.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2022
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Interfaces;


/**
 * The VDM Core Table Validator Interface
 */
interface TableValidatorInterface
{

/***[JCBGUI.power.main_class_code.855.$$$$]***/
	/**
	 * Returns the valid value based on datatype definition.
	 * If the value is valid, return it. If not, return the default value,
	 * NULL (if allowed), or an empty string if 'EMPTY' is set.
	 *
	 * @param mixed  $value  The value to validate.
	 * @param string $field  The field name.
	 * @param string $table  The table name.
	 *
	 * @return mixed Returns the valid value, or the default, NULL, or empty string based on validation.
	 * @since 5.3.0
	 */
	public function getValid($value, string $field, string $table);/***[/JCBGUI$$$$]***/

}

