<?php

/***[JCBGUI.power.licensing_template.753.$$$$]***/
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

namespace JoomSermon\Joomla\Data;


use JoomSermon\Joomla\Interfaces\Data\LoadInterface as Load;
use JoomSermon\Joomla\Interfaces\Data\InsertInterface as Insert;
use JoomSermon\Joomla\Interfaces\Data\UpdateInterface as Update;
use JoomSermon\Joomla\Interfaces\Data\DeleteInterface as Delete;
use JoomSermon\Joomla\Interfaces\Database\LoadInterface as Database;
use JoomSermon\Joomla\Data\Guid;
use JoomSermon\Joomla\Interfaces\Data\ItemsInterface;


/**
 * Data Items
 * 
 * @since 3.2.2
 */
final class Items implements ItemsInterface
{

/***[JCBGUI.power.main_class_code.753.$$$$]***/
	/**
	 * The Globally Unique Identifier.
	 *
	 * @since 5.1.2
	 */
	use Guid;

	/**
	 * The LoadInterface Class.
	 *
	 * @var   Load
	 * @since 3.2.2
	 */
	protected Load $load;

	/**
	 * The InsertInterface Class.
	 *
	 * @var   Insert
	 * @since 3.2.2
	 */
	protected Insert $insert;

	/**
	 * The UpdateInterface Class.
	 *
	 * @var   Update
	 * @since 3.2.2
	 */
	protected Update $update;

	/**
	 * The DeleteInterface Class.
	 *
	 * @var   Delete
	 * @since 3.2.2
	 */
	protected Delete $delete;

	/**
	 * The Load Class.
	 *
	 * @var   Database
	 * @since 3.2.2
	 */
	protected Database $database;

	/**
	 * Table Name
	 *
	 * @var    string
	 * @since 3.2.1
	 */
	protected string $table;

	/**
	 * Constructor.
	 *
	 * @param Load        $load       The LoadInterface Class.
	 * @param Insert      $insert     The InsertInterface Class.
	 * @param Update      $update     The UpdateInterface Class.
	 * @param Delete      $delete     The DeleteInterface Class.
	 * @param Database    $database   The Database Load Class.
	 * @param string|null $table      The table name.
	 *
	 * @since 3.2.2
	 */
	public function __construct(Load $load, Insert $insert, Update $update, Delete $delete,
		Database $database, ?string $table = null)
	{
		$this->load = $load;
		$this->insert = $insert;
		$this->update = $update;
		$this->delete = $delete;
		$this->database = $database;
		if ($table !== null)
		{
			$this->table = $table;
		}
	}

	/**
	 * Get the IDs affected by the most recent actions batch.
	 *
	 * This method returns the complete set of entity IDs affected by the most
	 * recent persistence operations, regardless of whether the underlying
	 * action was an INSERT, UPDATE, or a mixture of both.
	 *
	 * Behavioral notes:
	 * - IDs from INSERT and UPDATE operations are merged into a single set.
	 * - The internal ID buckets for both operations are reset immediately
	 *   after retrieval to prevent cross-contamination between batches.
	 * - Duplicate IDs are removed while preserving their original order.
	 * - The returned IDs represent *all* entities affected during the
	 *   most recent execution cycle.
	 *
	 * @return  array<int|string>  The affected entity IDs.
	 *
	 * @since   5.1.4
	 */
	public function ids(): array
	{
		$insertIds = $this->insert->insertids(true);
		$updateIds = $this->update->updateids(true);

		if ($insertIds === [] && $updateIds === [])
		{
			return [];
		}

		return array_values(
			array_unique(
				array_merge($insertIds, $updateIds)
			)
		);
	}

	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(string $table): self
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * Get list of items
	 *
	 * @param array     $values    The ids of the items
	 * @param string    $key       The key of the values
	 *
	 * @return array|null The array of item objects or null
	 * @since 3.2.2
	 */
	public function get(array $values, string $key = 'guid'): ?array
	{
		return $this->load->table($this->getTable())->items([
			$key => [
				'operator' => 'IN',
				'value' => array_values($values)
			]
		]);
	}

	/**
	 * Get the values
	 *
	 * @param array   $values    The list of values (to search by).
	 * @param string  $key       The key on which the values being searched.
	 * @param string  $get       The key of the values we want back
	 *
	 * @return array|null   The array of found values.
	 * @since 3.2.2
	 */
	public function values(array $values, string $key = 'guid', string $get = 'id'): ?array
	{
		// Perform the database query
		return $this->load->table($this->getTable())->values([
			$key => [
				'operator' => 'IN',
				'value' => array_values($values)
			]
		], $get);
	}

	/**
	 * Set items
	 *
	 * @param array     $items  The list of items
	 * @param string    $key    The key on which the items should be set
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function set(array $items, string $key = 'guid'): bool
	{
		if (($sets = $this->sort($items, $key)) !== null)
		{
			foreach ($sets as $action => $items)
			{
				$this->{$action}($items, $key);
			}
			return true;
		}

		return false;
	}

	/**
	 * Delete items
	 *
	 * @param array    $values  The item key value
	 * @param string   $key     The item key
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function delete(array $values, string $key = 'guid'): bool
	{
		return $this->delete->table($this->getTable())->items([$key => ['operator' => 'IN', 'value' => $values]]);
	}

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string
	{
		return $this->table;
	}

	/**
	 * Insert a item
	 *
	 * @param array   $items  The item
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function insert(array $items): bool
	{
		return $this->insert->table($this->getTable())->rows($items);
	}

	/**
	 * Update a item
	 *
	 * @param object   $item  The item
	 * @param string   $key   The item key
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function update(array $items, string $key): bool
	{
		return $this->update->table($this->getTable())->rows($items, $key);
	}

	/**
	 * Sort items between insert and update.
	 *
	 * @param array  $items The list of items.
	 * @param string $key   The key on which the items should be sorted.
	 *
	 * @return array|null The sorted sets.
	 * @since 3.2.2
	 */
	private function sort(array $items, string $key): ?array
	{
		// Extract relevant items based on the key.
		$values = $this->extractValues($items, $key);
		if ($values === null)
		{
			$sets = [];
			$insert = [];
			foreach ($items as $item)
			{
				$row = is_array($item) ? $item : (array) $item;
				$insert[] = $this->normalizeGuid($row);
			}
			$sets['insert'] = $insert;

			return $sets;
		}

		$sets = [
			'insert' => [],
			'update' => []
		];

		// Check for existing items.
		$existingItems = $this->database->values(
			["a.$key" => $key],
			["a" => $this->getTable()],
			["a.$key" => ['operator' => 'IN', 'value' => $values]]
		);

		if ($existingItems !== null)
		{
			$sets['update'] = $this->extractSet($items, $existingItems, $key) ?? [];
			$sets['insert'] = $this->extractSet($items, $existingItems, $key, true) ?? [];
		}
		else
		{
			$insert = [];
			foreach ($items as $item)
			{
				$row = is_array($item) ? $item : (array) $item;
				$insert[] = $this->normalizeGuid($row);
			}
			$sets['insert'] = $insert;
		}

		// If either set is empty, remove it from the result.
		$sets = array_filter($sets);

		return !empty($sets) ? $sets : null;
	}

	/**
	 * Extracts values for a given key from an array of items.
	 * Items can be either arrays or objects.
	 *
	 * @param array $items Array of items (arrays or objects)
	 * @param string $key The key to extract values for
	 *
	 * @return array|null Extracted values
	 * @since 3.2.2
	 */
	private function extractValues(array $items, string $key): ?array
	{
		$result = [];

		foreach ($items as $item)
		{
			if (is_array($item) && !empty($item[$key]))
			{
				$result[] = $item[$key];
			}
			elseif (is_object($item) && !empty($item->{$key}))
			{
				$result[] = $item->{$key};
			}
		}

		return ($result === []) ? null : $result;
	}

	/**
	 * Extracts items from an array of items based on a set.
	 * Items can be either arrays or objects.
	 *
	 * @param array  $items   Array of items (arrays or objects)
	 * @param array  $set	 The set to match values against
	 * @param string $key	 The key of the set values
	 * @param bool   $inverse Whether to extract items not in the set
	 *
	 * @return array|null Extracted values
	 * @since 3.2.2
	 */
	private function extractSet(array $items, array $set, string $key, bool $inverse = false): ?array
	{
		$result = [];

		foreach ($items as $item)
		{
			$value = is_array($item) ? ($item[$key] ?? null) : ($item->{$key} ?? null);

			if ($value !== null)
			{
				$inSet = in_array($value, $set);
				if (($inSet && !$inverse) || (!$inSet && $inverse))
				{
					$row = is_array($item) ? $item : (array) $item;
					if ($inverse)
					{
						$row = $this->normalizeGuid($row);
					}
					$result[] = $row;
				}
			}
		}

		return empty($result) ? null : $result;
	}

	/**
	 * Normalize the row item
	 *
	 * @param array  $item   Items array
	 *
	 * @return array
	 * @since  5.1.2
	 */
	private function normalizeGuid(array $item): array
	{
		if (isset($item['guid']) && $item['guid'] === '')
		{
			$item['guid'] = $this->getGuid('guid');
		}
		return $item;
	}

	/**
	 * Checks if the GUID value is unique and does not already exist.
	 *
	 * @param string $guid The GUID value to check.
	 * @param string $key  The key to check and modify values.
	 *
	 * @return string The unique GUID value.
	 *
	 * @since 5.0.2
	 */
	protected function checkGuid(string $guid, string $key): string
	{
		// Check that the GUID does not already exist
		if ($this->table($this->getTable())->values([$guid], $key))
		{
			return $this->getGuid($key);
		}

		return $guid;
	}/***[/JCBGUI$$$$]***/

}

