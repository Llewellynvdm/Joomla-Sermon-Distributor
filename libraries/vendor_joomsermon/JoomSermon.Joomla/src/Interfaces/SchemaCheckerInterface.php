<?php

/***[JCBGUI.power.licensing_template.768.$$$$]***/
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
 * Schema Checker Interface
 * 
 * @since 3.2.2
 */
interface SchemaCheckerInterface
{

/***[JCBGUI.power.main_class_code.768.$$$$]***/
	/**
	 * Make sure that the database schema is up-to-date.
	 *
	 * @return void
	 * @since 3.2.2
	 */
	public function run(): void;/***[/JCBGUI$$$$]***/

}

