<?php

/***[JCBGUI.power.licensing_template.824.$$$$]***/
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

namespace JoomSermon\Joomla\Interfaces\Data;


/**
 * Globally Unique Identifier Interface
 * 
 * @since 5.0.2
 */
interface GuidInterface
{

/***[JCBGUI.power.main_class_code.824.$$$$]***/
	/**
	 * Returns a GUIDv4 string.
	 * 
	 * This function uses the best cryptographically secure method
	 * available on the platform with a fallback to an older, less secure version.
	 *
	 * @param string $key The key to check and modify values.
	 *
	 * @return string A GUIDv4 string.
	 *
	 * @since 5.0.2
	 */
	public function getGuid(string $key): string;/***[/JCBGUI$$$$]***/

}

