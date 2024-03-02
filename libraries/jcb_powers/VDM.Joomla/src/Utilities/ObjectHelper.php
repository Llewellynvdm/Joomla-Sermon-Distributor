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

namespace VDM\Joomla\Utilities;


/**
 * Some object tricks
 * 
 * @since  3.0.9
 */
abstract class ObjectHelper
{
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
	 * Compare two objects for equality based on their property values.
	 *
	 *   Note that this method works only for simple objects that don't
	 *   contain any nested objects or resource references. If you need
	 *   to compare more complex objects, you may need to use a
	 *   more advanced method such as serialization or reflection.
	 *
	 * @param object|null $obj1 The first object to compare.
	 * @param object|null $obj2 The second object to compare.
	 *
	 * @return bool True if the objects have the same key-value pairs and false otherwise.
	 */
	public static function equal(?object $obj1, ?object $obj2): bool
	{
		// if any is null we return false as that means there is a none object
		// we are not comparing null but objects
		// but we allow null as some objects while
		// not instantiate are still null
		if (is_null($obj1) || is_null($obj2))
		{
			return false;
		}

		// Convert the objects to arrays of their property values using get_object_vars.
		$array1 = get_object_vars($obj1);
		$array2 = get_object_vars($obj2);

		// Compare the arrays using array_diff_assoc to detect any differences.
		$diff1 = array_diff_assoc($array1, $array2);
		$diff2 = array_diff_assoc($array2, $array1);

		// If the arrays have the same key-value pairs, they will have no differences, so return true.
		return empty($diff1) && empty($diff2);
	}

}

