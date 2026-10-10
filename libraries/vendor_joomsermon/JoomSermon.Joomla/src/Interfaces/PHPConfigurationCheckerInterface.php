<?php

/***[JCBGUI.power.licensing_template.723.$$$$]***/
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
 * PHP Configuration Checker
 * 
 * @since 5.0.2
 */
interface PHPConfigurationCheckerInterface
{

/***[JCBGUI.power.main_class_code.723.$$$$]***/
	/**
	 * Check that the required configurations are set for PHP
	 *
	 * @return void
	 * @since  5.0.2
	 **/
	public function run(): void;/***[/JCBGUI$$$$]***/

}

