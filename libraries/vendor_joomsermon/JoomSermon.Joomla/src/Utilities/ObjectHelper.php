<?php

/***[JCBGUI.power.licensing_template.18.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    3rd September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Utilities;


/**
 * Some object tricks
 * 
 * @since  3.0.9
 */
abstract class ObjectHelper
{

/***[JCBGUI.power.main_class_code.18.$$$$]***/
	/**
	 * Check if have an object with a length
	 *
	 * @input	object   The object to check
	 *
	 * @returns bool true on success
	 * 
	 * @since  3.0.9
	 */
	public static function check($object)
	{
		if (is_object($object))
		{
			return count((array) $object) > 0;
		}

		return false;
	}

	/**
	 * Checks if two objects are equal by comparing their properties and values.
	 *
	 * This method converts both input objects to
	 * associative arrays, optionally removes ignored keys,
	 * sorts the arrays by keys, and compares them.
	 *
	 * If the arrays are identical, the objects are considered equal.
	 *
	 * @param object|null  $obj1    The first object to compare.
	 * @param object|null  $obj2    The second object to compare.
	 * @param array|null   $ignore  Keys to ignore during comparison.
	 *
	 * @return bool  True if the objects are equal, false otherwise.
	 * @since  5.0.2
	 */
	public static function equal(?object $obj1, ?object $obj2, ?array $ignore = null): bool
	{
		// Return false if either is null
		if (is_null($obj1) || is_null($obj2))
		{
			return false;
		}

		// Convert objects to associative arrays
		$array1 = json_decode(json_encode($obj1), true);
		$array2 = json_decode(json_encode($obj2), true);

		// Remove ignored keys recursively
		if (!empty($ignore))
		{
			self::removeIgnoredKeys($array1, $ignore);
			self::removeIgnoredKeys($array2, $ignore);
		}

		// Sort both arrays by keys
		self::recursiveKsort($array1);
		self::recursiveKsort($array2);

		// Compare the sorted arrays
		return $array1 === $array2;
	}

	/**
	 * Recursively remove ignored keys from an array.
	 *
	 * @param array       $array   The array to modify (by reference).
	 * @param array       $ignore  The list of keys to ignore.
	 *
	 * @return void
	 * @since  5.1.1
	 */
	protected static function removeIgnoredKeys(array &$array, array $ignore): void
	{
		foreach ($array as $key => &$value)
		{
			if (in_array($key, $ignore, true))
			{
				unset($array[$key]);
			}
			elseif (is_array($value))
			{
				self::removeIgnoredKeys($value, $ignore);
			}
		}
	}

	/**
	 * Recursively sort an array by key.
	 *
	 * @param array  $array  The array to sort.
	 *
	 * @return void
	 * @since  5.0.2
	 */
	protected static function recursiveKsort(array &$array): void
	{
		ksort($array);

		foreach ($array as &$value)
		{
			if (is_array($value))
			{
				self::recursiveKsort($value);
			}
		}
	}
/***[/JCBGUI$$$$]***/

}

