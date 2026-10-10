<?php

/***[JCBGUI.power.licensing_template.1069.$$$$]***/
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

namespace JoomSermon\Joomla\Database;


/**
 * Database Quote Trait
 * 
 * @since 5.1.1
 */
trait QuoteTrait
{

/***[JCBGUI.power.main_class_code.1069.$$$$]***/
	/**
	 * Date format to return
	 *
	 * @var   string
	 * @since 5.0.2
	 */
	protected string $dateFormat = 'Y-m-d H:i:s';

	/**
	 * Safely quote a value for database use, preserving data integrity.
	 *
	 * - Native ints/floats passed as-is
	 * - Clean integer strings are cast to int
	 * - Clean float strings are cast to float
	 * - Scientific notation is quoted to preserve original form
	 * - Leading-zero integers are quoted
	 * - Dates are formatted and quoted
	 * - Booleans are converted to TRUE/FALSE
	 * - Null is converted to NULL
	 * - All else is quoted with Joomla's db quote
	 *
	 * @param   mixed  $value  The value to quote.
	 *
	 * @return  mixed
	 * @since   3.2.0
	 */
	protected function quote($value)
	{
		// NULL handling
		if ($value === null)
		{
			return 'NULL';
		}

		// DateTime handling
		if ($value instanceof \DateTimeInterface)
		{
			return $this->db->quote($value->format($this->getDateFormat()));
		}

		// Native numeric types
		if (is_int($value) || is_float($value))
		{
			return $value;
		}

		// Stringified numeric values
		if (is_string($value) && is_numeric($value))
		{
			// Case 1: Leading-zero integers like "007"
			if ($value[0] === '0' && strlen($value) > 1 && ctype_digit($value))
			{
				return $this->db->quote($value);
			}

			// Case 2: Scientific notation - preserve exact format
			if (stripos($value, 'e') !== false)
			{
				return $this->db->quote($value);
			}

			// Case 3: Decimal float string (not scientific)
			if (str_contains($value, '.'))
			{
				return (float) $value;
			}

			// Case 4: Pure integer string
			if (ctype_digit($value))
			{
				return (int) $value;
			}
		}

		// Boolean handling
		if (is_bool($value))
		{
			return $value ? 'TRUE' : 'FALSE';
		}

		// Everything else
		return $this->db->quote($value);
	}

	/**
	 * Get the date format used for SQL dumps.
	 *
	 * This format is used when quoting DateTimeInterface values
	 * to ensure consistent formatting in INSERT statements.
	 *
	 * @return  string  The SQL-compatible date format.
	 * @since   5.0.2
	 */
	protected function getDateFormat(): string
	{
		return $this->dateFormat;
	}/***[/JCBGUI$$$$]***/

}

