<?php

/***[JCBGUI.power.licensing_template.17.$$$$]***/
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



/***[JCBGUI.power.head.17.$$$$]***/
use Joomla\CMS\Factory;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\Component\Helper;
use JoomSermon\Joomla\Data\Factory as Data;


/**
 * Some easy get...
 * 
 * @since  3.0.9
 */
abstract class GetHelper
{

/***[JCBGUI.power.main_class_code.17.$$$$]***/
	/**
	 * Get a Variable 
	 *
	 * @param   string|null  $table        The table from which to get the variable
	 * @param   mixed        $where        The value where
	 * @param   string       $whereString  The target/field string where/name
	 * @param   string       $what         The return field
	 * @param   string       $operator     The operator between $whereString/field and $where/value
	 * @param   string       $main         The component in which the table is found
	 *
	 * @return  mixed string/int/float
	 * @since  3.0.9
	 *
	 * @deprecated 5.1  Use  Data::_('Data.Load')->table($table)->value(...)
	 */
	public static function var(?string $table = null, $where = null,
		string $whereString = 'user', string $what = 'id',
		string $operator = '=', ?string $main = null)
	{
		/**
		 * Resolve identity safely if no where value was provided.
		 */
		if ($where === null || $where === '')
		{
			$app = Factory::getApplication();

			// If the application does not support identity resolution, exit early
			if (!method_exists($app, 'getIdentity'))
			{
				return false;
			}

			$identity = $app->getIdentity();

			// Identity may legitimately be null (CLI, unauthenticated context)
			if ($identity === null)
			{
				return false;
			}

			$where = $identity->id;

			if ($where === null)
			{
				return false;
			}
		}

		/**
		 * Resolve component code safely.
		 */
		if ($main === null || $main === '')
		{
			$main = Helper::getCode();
		}

		// Get a database connection
		$db = Factory::getDbo();

		// Build query
		$query = $db->getQuery(true)
			->select($db->quoteName($what));

		// Resolve table
		if ($table === null || $table === '')
		{
			$query->from($db->quoteName('#__' . $main));
		}
		else
		{
			$query->from($db->quoteName('#__' . $main . '_' . $table));
		}

		// Apply where clause
		if (is_numeric($where))
		{
			$query->where(
				$db->quoteName($whereString) . ' ' . $operator . ' ' . (int) $where
			);
		}
		elseif (is_string($where))
		{
			$query->where(
				$db->quoteName($whereString) . ' ' . $operator . ' ' . $db->quote($where)
			);
		}
		else
		{
			return false;
		}

		// Execute query
		$db->setQuery($query);
		$db->execute();

		if ($db->getNumRows() > 0)
		{
			return $db->loadResult();
		}

		return false;
	}

	/**
	 * Get array of variables
	 *
	 * @param   string|null  $table        The table from which to get the variables
	 * @param   mixed        $where        The value where
	 * @param   string       $whereString  The target/field string where/name
	 * @param   string       $what         The return field
	 * @param   string       $operator     The operator between $whereString/field and $where/value
	 * @param   string       $main         The component in which the table is found
	 * @param   bool         $unique       The switch to return a unique array
	 *
	 * @return  array|null
	 * @since  3.0.9
	 *
	 * @deprecated 5.1  Use  Data::_('Data.Load')->table($table)->values(...)
	 */
	public static function vars(?string $table = null, $where = null,
		string $whereString = 'user', string $what = 'id', string $operator = 'IN',
		?string $main = null, bool $unique = true): ?array
	{
		if(empty($where))
		{
			$where = Factory::getApplication()->getIdentity()->id;
		}

		if($main === null)
		{
			$main = Helper::getCode();
		}

		if (!ArrayHelper::check($where) && $where > 0)
		{
			$where = [$where];
		}

		if (ArrayHelper::check($where))
		{
			// prep main <-- why? well if $main='' is empty then $table can be categories or users
			if (StringHelper::check($main))
			{
				$main = '_' . ltrim($main, '_');
			}

			// Get a db connection.
			$db = Factory::getDbo();

			// Create a new query object.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array($what)));

			if (empty($table))
			{
				$query->from($db->quoteName('#__' . $main));
			}
			else
			{
				$query->from($db->quoteName('#_' . $main . '_' . $table));
			}

			// add strings to array search
			if ('IN_STRINGS' === $operator || 'NOT IN_STRINGS' === $operator)
			{
				$query->where($db->quoteName($whereString) . ' ' . str_replace('_STRINGS', '', $operator) . ' ("' . implode('","', $where) . '")');
			}
			else
			{
				$query->where($db->quoteName($whereString) . ' ' . $operator . ' (' . implode(',', $where) . ')');
			}

			$db->setQuery($query);
			$db->execute();

			if ($db->getNumRows())
			{
				if ($unique)
				{
					return array_unique($db->loadColumn());
				}
				return $db->loadColumn();
			}
		}

		return null;
	}

	/**
	 * get all strings between two other strings
	 * 
	 * @param  string       $content    The content to search
	 * @param  string       $start      The starting value
	 * @param  string       $end        The ending value
	 *
	 * @return  array|null          On success
	 * @since  3.0.9
	 */
	public static function allBetween(string $content, string $start, string $end): ?array
	{
		// reset bucket
		$bucket = [];
		for ($i = 0; ; $i++)
		{
			// search for string
			$found = self::between($content, $start, $end);

			if (StringHelper::check($found))
			{
				// add to bucket
				$bucket[] = $found;

				// build removal string
				$remove = $start . $found . $end;

				// remove from content
				$content = str_replace($remove, '', $content);
			}
			else
			{
				break;
			}

			// safety catch
			if ($i == 500)
			{
				break;
			}
		}

		// only return unique array of values
		if (ArrayHelper::check($bucket))
		{
			return  array_unique($bucket);
		}

		return null;
	}

	/**
	 * get a string between two other strings
	 * 
	 * @param  string       $content    The content to search
	 * @param  string       $start      The starting value
	 * @param  string       $end        The ending value
	 * @param  string       $default    The default value if none found
	 *
	 * @return  string          On success / empty string on failure
	 * @since  3.0.9
	 */
	public static function between(string $content, string $start, string $end, string $default = ''): string
	{
		$array = explode($start, $content);
		if (isset($array[1]) && strpos($array[1], $end) !== false)
		{
			$array = explode($end, $array[1]);

			// return string found between
			return $array[0];
		}

		return $default;
	}
/***[/JCBGUI$$$$]***/

}

