<?php

/***[JCBGUI.power.licensing_template.384.$$$$]***/
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



/***[JCBGUI.power.head.384.$$$$]***/
use Joomla\CMS\Date\Date;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Database\DefaultTrait;
use JoomSermon\Joomla\Interfaces\Database\UpdateInterface;
use JoomSermon\Joomla\Abstraction\Versioning;


/**
 * Database Update Class
 * 
 * @since 3.2.0
 */
final class Update extends Versioning implements UpdateInterface
{

/***[JCBGUI.power.main_class_code.384.$$$$]***/
	/**
	 * Default Switch
	 *
	 * @since 5.1.1
	 */
	use DefaultTrait;

	/**
	 * The updated multiple
	 *
	 * @var      bool
	 * @since  5.1.4
	 **/
	protected bool $multiple = false;

	/**
	 * The updated id tracker bucket
	 *
	 * @var      array
	 * @since  5.1.4
	 **/
	protected array $updateids = [];

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
	public function updateids(bool $reset = false): array
	{
		$ids = $this->updateids;

		if ($reset && $ids !== [])
		{
			$this->updateids = [];
		}

		return $ids;
	}

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
	public function rows(array $data, string $key, string $table, array $columns = []): bool
	{
		// set the update columns
		if ($data === [] || $key === '')
		{
			return false;
		}

		// reset update ids bucket
		$this->updateids = [];

		$this->multiple = true;

		// set the update values
		foreach ($data as $values)
		{
			if ($columns !== [])
			{
				// load only what is part of the columns set
				$row = [];
				foreach ($columns as $column => $key_)
				{
					if (isset($values[$key_]))
					{
						$row[$column] = $values[$key_];
					}
				}

				// update the row
				$this->row($row, $key, $table);
			}
			else
			{
				// update the row
				$this->row((array) $values, $key, $table);
			}
		}

		if ($this->updateids !== [])
		{
			$this->trackHistory($this->updateids);
		}

		// always reset the switch's
		$this->defaults()->history();
		$this->multiple = false;

		return true;
	}

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
	public function items(array $data, string $key, string $table, array $columns = []): bool
	{
		// set the update columns
		if ($data === [] || $key === '')
		{
			return false;
		}

		// reset update ids bucket
		$this->updateids = [];

		$this->multiple = true;

		// set the update values
		foreach ($data as $nr => $values)
		{
			if ($columns !== [])
			{
				// load only what is part of the columns set
				$row = [];
				foreach ($columns as $column => $key_)
				{
					if (isset($values->{$key_}))
					{
						$row[$column] = $values->{$key_};
					}
				}

				// update the row
				$this->row($row, $key, $table);
			}
			else
			{
				// update the row
				$this->row((array) $values, $key, $table);
			}
		}

		if ($this->updateids !== [])
		{
			$this->trackHistory($this->updateids);
		}

		// always reset the switch's
		$this->defaults()->history();
		$this->multiple = false;

		return true;
	}

	/**
	 * Update row in the database.
	 *
	 * Notes on ID tracking (critical for dependency + history workflows):
	 * - This method ALWAYS tracks the affected ID(s) in `$this->updateids`, even when history tracking is disabled.
	 * - ID resolution order (only fall back when the previous fails):
	 *   1) Use `id` from the provided dataset (if present).
	 *   2) Use `guid` from the provided dataset and resolve to ID(s).
	 *   3) Resolve ID(s) via the UPDATE WHERE clause (based on `$key` and its value).
	 *
	 * Multi-row safety:
	 * - If the WHERE clause matches more than one row, all matching IDs are tracked.
	 *
	 * @param   array   $data   Dataset to update in database (key => value).
	 * @param   string  $key    Dataset key column to use in updating the values in the Database.
	 * @param   string  $table  The table where the data is being updated.
	 *
	 * @return  bool
	 * @since   3.2.0
	 **/
	public function row(array $data, string $key, string $table): bool
	{
		// basic validation
		if ($data === [] || $key === '')
		{
			return false;
		}

		if (!$this->multiple)
		{
			// reset update ids bucket
			$this->updateids = [];
		}

		// set history vars
		$this->entity = $this->getTableEntityName($table);
		$table = $this->getTable($table);

		// extract identifier values from payload (id/guid/key value)
		[$keyValue, $id, $guid] = $this->extractUpdateIdentifiers($data, $key);

		// must have a WHERE key value
		if ($keyValue === null)
		{
			return false;
		}

		// build the UPDATE query (keep original structure + quoting behaviour)
		$query = $this->db->createQuery();
		$query->update($this->db->quoteName($table));

		foreach ($data as $column => $value)
		{
			// do not set the key column; it is used in WHERE
			if ($column === $key)
			{
				continue;
			}

			$query->set($this->db->quoteName($column) . ' = ' . $this->quote($value));
		}

		// apply modified defaults exactly when needed
		$this->applyUpdateDefaults($query, $data);

		// build WHERE clause once (used for UPDATE and (if needed) fallback SELECT)
		$where = $this->db->quoteName($key) . ' = ' . $this->quote($keyValue);
		$query->where($where);

		$resolvedIds = $this->resolveUpdateIds($id, $guid, $table, $where);

		// execute the update
		$this->db->setQuery($query);
		$result = $this->db->execute();

		if ($result && $resolvedIds !== [])
		{
			$this->updateids = array_values(
				array_unique(
					array_merge($this->updateids, $resolvedIds)
				)
			);

			if (!$this->multiple)
			{
				$this->trackHistory($resolvedIds);
			}
		}

		if (!$this->multiple)
		{
			// always reset the switch's
			$this->defaults()->history();
		}

		return (bool) $result;
	}

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
	public function item(object $data, string $key, string $table): bool
	{
		// convert to an array
		return $this->row((array) get_object_vars($data), $key, $table);
	}

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
	public function column(mixed $value, string $key, string $table): bool
	{
		// Ensure valid input
		if ($key === '' || $table === '')
		{
			return false;
		}

		// Get a query object
		$query = $this->db->createQuery();

		// Prepare the update statement
		$query->update($this->db->quoteName($this->getTable($table)))
		      ->set($this->db->quoteName($key) . ' = ' . $this->quote($value));

		// Apply the query
		$this->db->setQuery($query);

		return $this->db->execute();
	}

	/**
	 * Extract update identifiers from the dataset.
	 *
	 * Identifier resolution inputs:
	 * - `$keyValue` is always required to build the WHERE clause.
	 * - `$id` and `$guid` are optional and are used to avoid the fallback WHERE lookup.
	 *
	 * @param   array   $data  The update dataset.
	 * @param   string  $key   The WHERE key column name.
	 *
	 * @return  array{0:mixed,1:?int,2:?string}  Key value, id, guid.
	 * @since   5.1.4
	 */
	protected function extractUpdateIdentifiers(array $data, string $key): array
	{
		$keyValue = null;
		$id = null;
		$guid = null;

		foreach ($data as $column => $value)
		{
			if (empty($value))
			{
				continue;
			}

			if ($column === $key)
			{
				$keyValue = $value;
				continue;
			}

			// capture identifiers if present
			if ($column === 'id')
			{
				$id = (int) $value;
			}
			elseif ($column === 'guid')
			{
				$guid = (string) $value;
			}
		}

		return [$keyValue, $id, $guid];
	}

	/**
	 * Apply Joomla update defaults (modified / modified_by) if enabled and missing.
	 *
	 * This preserves the original behaviour:
	 * - Only applied when `$this->defaults` is enabled.
	 * - Only applied when the caller did not provide the columns already.
	 *
	 * @param   object  $query  The update query object.
	 * @param   array   $data   The update dataset.
	 *
	 * @return  void
	 * @since   5.1.4
	 */
	protected function applyUpdateDefaults($query, array $data): void
	{
		if (!$this->defaults)
		{
			return;
		}

		$add_modified = !isset($data['modified']);
		$add_modified_by = !isset($data['modified_by']);

		if ($add_modified)
		{
			$query->set(
				$this->db->quoteName('modified') . ' = ' . $this->quote((new Date())->toSql())
			);
		}

		if ($add_modified_by)
		{
			$query->set(
				$this->db->quoteName('modified_by') . ' = ' . (int) $this->userId
			);
		}
	}

	/**
	 * Resolve the affected ID(s) for an UPDATE operation.
	 *
	 * Resolution order (only fall back when the previous fails):
	 * 1) Use the provided `$id` if present and valid (>0).
	 * 2) Resolve by `$guid` if provided (returns one or multiple IDs).
	 * 3) Resolve by the WHERE clause (returns one or multiple IDs).
	 *
	 * @param   int|null     $id     The entity ID if provided.
	 * @param   string|null  $guid   The entity GUID if provided.
	 * @param   string       $table  The full table name.
	 * @param   string       $where  The WHERE clause used by the UPDATE.
	 *
	 * @return  array<int>  The resolved ID(s).
	 * @since   5.1.4
	 */
	protected function resolveUpdateIds(?int $id, ?string $guid, string $table, string $where): array
	{
		// 1) Direct ID
		if (!empty($id) && $id > 0)
		{
			return [$id];
		}

		// 2) GUID -> ID(s)
		if (!empty($guid))
		{
			$ids = $this->lookupIdsByGuid($guid, $table);

			if ($ids !== [])
			{
				return $ids;
			}
		}

		// 3) WHERE clause -> ID(s)
		return $this->lookupIdsByWhere($where, $table);
	}

	/**
	 * Lookup ID(s) by GUID.
	 *
	 * @param   string  $guid   The entity GUID.
	 * @param   string  $table  The full table name.
	 *
	 * @return  array<int>  Matching ID(s), empty array if none found.
	 * @since   5.1.4
	 */
	protected function lookupIdsByGuid(string $guid, string $table): array
	{
		try
		{
			$query = $this->db->createQuery()
				->select($this->db->quoteName('id'))
				->from($this->db->quoteName($table))
				->where($this->db->quoteName('guid') . ' = ' . $this->quote($guid));

			$this->db->setQuery($query);
			$this->db->execute();

			if ($this->db->getNumRows())
			{
				return $this->db->loadColumn();
			}
		}
		catch (\Throwable $e)
		{
			// Silently ignore all errors
		}

		return [];
	}

	/**
	 * Lookup ID(s) by an UPDATE WHERE clause.
	 *
	 * This is the final fallback and must only be used when:
	 * - no valid `$id` was provided, and
	 * - no `$guid` was provided or it could not be resolved.
	 *
	 * @param   string  $where  The WHERE clause string.
	 * @param   string  $table  The full table name.
	 *
	 * @return  array<int>  Matching ID(s), empty array if none found.
	 * @since   5.1.4
	 */
	protected function lookupIdsByWhere(string $where, string $table): array
	{
		if ($where === '')
		{
			return [];
		}

		try
		{
			$query = $this->db->createQuery()
				->select($this->db->quoteName('id'))
				->from($this->db->quoteName($table))
				->where($where);

			$this->db->setQuery($query);
			$this->db->execute();

			if ($this->db->getNumRows())
			{
				return $this->db->loadColumn();
			}
		}
		catch (\Throwable $e)
		{
			// Silently ignore all errors
		}

		return [];
	}

	/**
	 * Apply history tracking for updated IDs.
	 *
	 * History is optional.
	 * - If history tracking is enabled and entity context exists, history is recorded.
	 *
	 * @param   array<int>  $ids  The affected IDs.
	 *
	 * @return  void
	 * @since   5.1.4
	 */
	protected function trackHistory(array $ids): void
	{
		if (!$this->history || empty($this->entity) || $ids === [])
		{
			return;
		}

		try
		{
			// Use the most efficient history call available
			if (count($ids) === 1)
			{
				$this->setHistory((int) $ids[0]);
				return;
			}

			$this->setMultipleHistory($ids);
		}
		catch (\Throwable $e)
		{
			// Silently ignore all errors
		}
	}/***[/JCBGUI$$$$]***/

}

