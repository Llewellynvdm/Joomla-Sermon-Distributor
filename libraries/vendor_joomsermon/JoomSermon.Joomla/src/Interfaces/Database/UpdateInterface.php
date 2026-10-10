<?php

/***[JCBGUI.power.licensing_template.383.$$$$]***/
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

namespace JoomSermon\Joomla\Interfaces\Database;


use JoomSermon\Joomla\Interfaces\Database\DefaultInterface;
use JoomSermon\Joomla\Interfaces\Database\VersioningInterface;


/**
 * Database Update Interface
 * 
 * @since 3.2.0
 */
interface UpdateInterface extends DefaultInterface, VersioningInterface
{

/***[JCBGUI.power.main_class_code.383.$$$$]***/
	/**
	 * Get the IDs affected by the most recent UPDATE batch.
	 *
	 * This method returns the ordered list of entity IDs that were affected
	 * by the last UPDATE operation or batch of UPDATE operations.
	 *
	 * Behavioral notes:
	 * - IDs are resolved deterministically (ID, GUID, or WHERE-clause fallback).
	 * - The order of IDs reflects the order in which they were resolved.
	 * - IDs may represent one or many rows, depending on the UPDATE scope.
	 * - When `$reset` is enabled, the internal update ID bucket is cleared
	 *   after the values are retrieved.
	 *
	 * @param   bool  $reset  Whether to reset the internal update ID bucket
	 *                        after retrieval.
	 *
	 * @return  array<int|string>  The affected entity IDs.
	 *
	 * @since   5.1.4
	 */
	public function updateids(bool $reset = false): array;

	/**
	 * Update rows in the database (with remapping and filtering columns option)
	 *
	 * @param   array    $data      Dataset to update in database [array of arrays (key => value)]
	 * @param   string   $key       Dataset key column to use in updating the values in the Database
	 * @param   string   $table     The table where the data is being updated
	 * @param   array    $columns   Data columns for remapping and filtering
	 *
	 * @return  bool
	 * @since   3.2.0
	 **/
	public function rows(array $data, string $key, string $table, array $columns = []): bool;

	/**
	 * Update items in the database (with remapping and filtering columns option)
	 *
	 * @param   array    $data      Data to updated in database (array of objects)
	 * @param   string   $key       Dataset key column to use in updating the values in the Database
	 * @param   string   $table     The table where the data is being update
	 * @param   array    $columns   Data columns for remapping and filtering
	 *
	 * @return  bool
	 * @since   3.2.0
	 **/
	public function items(array $data, string $key, string $table, array $columns = []): bool;

	/**
	 * Update row in the database
	 *
	 * @param   array    $data      Dataset to update in database (key => value)
	 * @param   string   $key       Dataset key column to use in updating the values in the Database
	 * @param   string   $table     The table where the data is being updated
	 *
	 * @return  bool
	 * @since   3.2.0
	 **/
	public function row(array $data, string $key, string $table): bool;

	/**
	 * Update item in the database
	 *
	 * @param   object   $data      Dataset to update in database (key => value)
	 * @param   string   $key       Dataset key column to use in updating the values in the Database
	 * @param   string   $table     The table where the data is being updated
	 *
	 * @return  bool
	 * @since   3.2.0
	 **/
	public function item(object $data, string $key, string $table): bool;

	/**
	 * Update a single column value for all rows in the table
	 *
	 * @param   mixed   $value   The value to assign to the column
	 * @param   string  $key     Dataset key column to use in updating the values in the Database
	 * @param   string  $table   The table where the update should be applied
	 *
	 * @return  bool  True on success, false on failure
	 * @since   5.1.1
	 */
	public function column(mixed $value, string $key, string $table): bool;/***[/JCBGUI$$$$]***/

}

