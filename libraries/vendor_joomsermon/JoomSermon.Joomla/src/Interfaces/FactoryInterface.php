<?php

/***[JCBGUI.power.licensing_template.135.$$$$]***/
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



/***[JCBGUI.power.head.135.$$$$]***/
use Joomla\DI\Container;/***[/JCBGUI$$$$]***/



/**
 * The Container Factory Interface
 * 
 * @since 0.0.0
 */
interface FactoryInterface
{

/***[JCBGUI.power.main_class_code.135.$$$$]***/
	/**
	 * Get any class from the container
	 *
	 * @param   string  $key  The container class key
	 *
	 * @return  Mixed
	 * @since 0.0.0
	 */
	public static function _(string $key);

	/**
	 * Get the global container
	 *
	 * @return  Container
	 * @since 0.0.0
	 */
	public static function getContainer(): Container;/***[/JCBGUI$$$$]***/

}

