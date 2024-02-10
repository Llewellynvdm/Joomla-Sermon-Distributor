<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    3rd September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace VDM\Joomla\Utilities\String;


use VDM\Joomla\Utilities\StringHelper;


/**
 * Control the naming of a namespace helper
 * 
 * @since  3.0.9
 */
abstract class NamespaceHelper
{
	/**
	 * Making namespace safe
	 *
	 * @param  string   $string    The namespace string you would like to make safe
	 *
	 * @return string on success
	 * @since  3.0.9
	 */
	public static function safe(string $string): string
	{
		// Remove leading and trailing backslashes
		$string = trim($string, '\\');

		// Split the string into namespace segments
		$segments = explode('\\', $string);

		// make each segment safe
		$segments = array_map([self::class, 'safeSegment'], $segments);

		// Join the namespace segments back together
		return implode('\\', $segments);
	}

	/**
	 * Making one namespace segment safe
	 *
	 * @param  string   $string    The namespace segment string you would like to make safe
	 *
	 * @return string on success
	 * @since  3.0.9
	 */
	public static function safeSegment(string $string): string
	{
		// Check if segment starts with a number
		if (preg_match("/^\d/", $string))
		{
			// Extract the starting number(s)
			preg_match("/^\d+/", $string, $matches);

			if (isset($matches[0]))
			{
				$numberWord = StringHelper::numbers($matches[0]);
				$string = str_replace($matches[0], $numberWord, $string);
			}
		}

		// Transliterate string TODO: look again as this makes it lowercase
		// $segment = StringHelper::transliterate($segment);

		// Make sure segment only contains valid characters
		return preg_replace("/[^A-Za-z0-9]/", '', $string);
	}
}

