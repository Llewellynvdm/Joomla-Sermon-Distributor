<?php

/***[JCBGUI.power.licensing_template.770.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Utilities;


/**
 * Class Helper for JCB Powers
 * 
 * @since  3.2.2
 */
abstract class ClassHelper
{

/***[JCBGUI.power.main_class_code.770.$$$$]***/
	/**
	 * Ensures that a class in the namespace is available.
	 * If the class is not already loaded, it attempts to load it via the specified autoloader.
	 *
	 * @param string  $className       The fully qualified name of the class to check.
	 * @param string  $component       The component name where the autoloader resides.
	 * @param string  $autoloaderPath  The path to the autoloader file within the component.
	 *
	 * @return bool True if the class exists or was successfully loaded, false otherwise.
	 * @since 3.2.2
	 */
	public static function exists(string $className, string $component, string $autoloaderPath): bool
	{
		if (!class_exists($className, true))
		{
			// Construct the path to the autoloader file
			$autoloaderFile = JPATH_ADMINISTRATOR . '/components/com_' . $component . '/' . $autoloaderPath;

			if (file_exists($autoloaderFile))
			{
				require_once $autoloaderFile;
			}

			// Check again if the class now exists after requiring the autoloader
			if (!class_exists($className, true))
			{
				return false;
			}
		}
		return true;
	}
/***[/JCBGUI$$$$]***/

}

