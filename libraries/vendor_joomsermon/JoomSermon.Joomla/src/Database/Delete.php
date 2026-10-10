<?php

/***[JCBGUI.power.licensing_template.463.$$$$]***/
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


use JoomSermon\Joomla\Utilities\ArrayHelper;
use JoomSermon\Joomla\Interfaces\Database\DeleteInterface;
use JoomSermon\Joomla\Abstraction\Database;


/**
 * Database Delete Class
 * 
 * @since 3.2.0
 */
final class Delete extends Database implements DeleteInterface
{

/***[JCBGUI.power.main_class_code.463.$$$$]***/
	/**
	 * Delete all items in the database that match these conditions
	 *
	 * @param   array    $conditions    Conditions by which to delete the data in database [array of arrays (key => value)]
	 * @param   string   $table         The table where the data is being deleted
	 *
	 * @return  bool
	 * @since   3.2.2
	 **/
	public function items(array $conditions, string $table): bool
	{
		// set the update columns
		if ($conditions === [])
		{
			return false;
		}

		// get a query object
		$query = $this->db->createQuery();

		// start the conditions bucket
		$_conditions = [];
		foreach ($conditions as $key => $value)
		{
			if (ArrayHelper::check($value))
			{
				if (isset($value['value']) && isset($value['operator']))
				{
					// check if value needs to be quoted
					$quote = $value['quote'] ?? true;
					if (!$quote)
					{
						if (ArrayHelper::check($value['value']))
						{
							// add the where by array
							$_conditions[] = $this->db->quoteName($key)
								. ' ' . $value['operator']
								. ' ' . ' (' .
								implode(',', $value['value'])
								. ')';
						}
						else
						{
							// add the conditions
							$_conditions[] = $this->db->quoteName($key)
								. ' ' . $value['operator']
								. ' ' . $value['value'];
						}
					}
					else
					{
						if (ArrayHelper::check($value['value']))
						{
							// add the where by array
							$_conditions[] = $this->db->quoteName($key)
								. ' ' . $value['operator']
								. ' ' . ' (' .
								implode(',', array_map(fn($val) => $this->quote($val), $value['value']))
								. ')';
						}
						else
						{
							// add the conditions
							$_conditions[] = $this->db->quoteName($key)
								. ' ' . $value['operator']
								. ' ' . $this->quote($value['value']);
						}
					}
				}
				else
				{
					// we should through an exception
					// for security we just return false for now
					return false;
				}
			}
			else
			{
				// add default condition
				$_conditions[] = $this->db->quoteName($key) . ' = ' . $this->quote($value);
			}
		}

		// set the query targets
		$query->delete($this->db->quoteName($this->getTable($table)));
		$query->where($_conditions);

		$this->db->setQuery($query);

		return $this->db->execute();
	}

	/**
	 * Truncate a table
	 *
	 * @param   string   $table    The table that should be truncated
	 *
	 * @return  void
	 * @since   3.2.2
	 **/
	public function truncate(string $table): void
	{
		$this->db->truncateTable($this->getTable($table));
	}/***[/JCBGUI$$$$]***/

}

