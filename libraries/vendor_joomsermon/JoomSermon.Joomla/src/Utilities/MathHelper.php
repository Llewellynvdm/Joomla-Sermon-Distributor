<?php

/***[JCBGUI.power.licensing_template.19.$$$$]***/
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
 * Basic Math Helper
 * 
 * @since  3.0.9
 */
abstract class MathHelper
{

/***[JCBGUI.power.main_class_code.19.$$$$]***/
	/**
	 * bc math wrapper (very basic not for accounting)
	 *
	 * @param   string   $type    The type bc math
	 * @param   int      $val1    The first value
	 * @param   int      $val2    The second value
	 * @param   int      $scale   The scale value
	 *
	 * @return string|int|null|bool
	 * 
	 * @since  3.0.9
	 */
	public static function bc($type, $val1, $val2, $scale = 0)
	{
		// Validate input
		if (!is_numeric($val1) || !is_numeric($val2))
		{
			return null;
		}

		// Build function name
		$function = 'bc' . $type;

		// Use the bcmath function if available
		if (is_callable($function))
		{
			return $function($val1, $val2, $scale);
		}

		// if function does not exist we use +-*/ operators (fallback - not ideal)
		switch ($type)
		{
			case 'mul':
				return (string) round($val1 * $val2, $scale);
			case 'div':
				if ($val2 == 0) return null; // Avoid division by zero
				return (string) round($val1 / $val2, $scale);
			case 'add':
				return (string) round($val1 + $val2, $scale);
			case 'sub':
				return (string) round($val1 - $val2, $scale);
			case 'pow':
				return (string) round(pow($val1, $val2), $scale);
			case 'comp':
				$diff = round($val1 - $val2, $scale);
				return ($diff > 0) ? 1 : (($diff < 0) ? -1 : 0);
		}

		return null;
	}

	/**
	 * Basic sum of an array with more precision
	 *
	 * @param   array   $array    The values to sum
	 * @param   int      $scale   The scale value
	 *
	 * @return float
	 * 
	 * @since  3.0.9
	 */
	public static function sum($array, $scale = 4)
	{
		// use the bcadd function if available
		if (function_exists('bcadd'))
		{
			// set the start value
			$value = 0.0;
			// loop the values and run bcadd
			foreach($array as $val)
			{
				$value = bcadd($value, (string) $val, $scale);
			}
			return $value;
		}
		// fall back on array sum
		return array_sum($array);
	}
/***[/JCBGUI$$$$]***/

}

