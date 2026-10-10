<?php

/***[JCBGUI.power.licensing_template.1035.$$$$]***/
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

namespace JoomSermon\Joomla\Interfaces\Database;


/**
 * Database Versioning Interface
 * 
 * @since 5.1.1
 */
interface VersioningInterface
{

/***[JCBGUI.power.main_class_code.1035.$$$$]***/
	/**
	 * Switch to prevent/allow history from being set.
	 *
	 * @param   int|null    $trigger   toggle the history (0 = no, 1 = yes, null = default)
	 *
	 * @return  self
	 * @since   5.1.1
	 **/
	public function history(?int $trigger = null): self;/***[/JCBGUI$$$$]***/

}

