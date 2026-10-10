<?php

/***[JCBGUI.power.licensing_template.877.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Data\Migrator;



/***[JCBGUI.power.head.877.$$$$]***/
use Joomla\Registry\Registry;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Data\Items;
use JoomSermon\Joomla\Database\Load;
use JoomSermon\Joomla\Database\Update;
use JoomSermon\Joomla\Data\Guid as TraitGuid;
use JoomSermon\Joomla\Utilities\GetHelper;


/**
 * Migrator To Globally Unique Identifier
 * 
 * @since  5.0.4
 */
final class Guid
{

/***[JCBGUI.power.main_class_code.877.$$$$]***/
	/**
	 * The Globally Unique Identifier.
	 *
	 * @since 5.0.4
	 */
	use TraitGuid;

	/**
	 * The Items Class.
	 *
	 * @var   Items
	 * @since 5.0.4
	 */
	protected Items $items;

	/**
	 * The Load Class.
	 *
	 * @var   Load
	 * @since 5.0.4
	 */
	protected Load $load;

	/**
	 * The Update Class.
	 *
	 * @var   Update
	 * @since 5.0.4
	 */
	protected Update $update;

	/**
	 * Cache for storing GUIDs to minimize redundant database queries.
	 *
	 * @var array
	 * @since 5.0.4
	 */
	protected array $guidCache = [];

	/**
	 * Table Name
	 *
	 * @var    string
	 * @since 5.0.4
	 */
	protected string $table;

	/**
	 * Cache all success messages.
	 *
	 * @var array
	 * @since 5.0.4
	 */
	private array $success = [];

	/**
	 * Constructor.
	 *
	 * @param Items    $items    The Items Class.
	 * @param Load     $load     The Load Class.
	 * @param Update   $update   The Update Class.
	 *
	 * @since 5.0.4
	 */
	public function __construct(Items $items, Load $load, Update $update)
	{
		$this->items = $items;
		$this->load = $load;
		$this->update = $update;
	}

	/**
	 * Processes the configuration to migrate IDs to GUIDs.
	 *
	 * @param array $config Configuration array defining table and column mappings.
	 *
	 * @return array of success messages
	 * @since 5.0.4
	 */
	public function process(array $config): array
	{
		try {
			$size = count($config);
			$this->success = [
				"Success: scan to migrate linked IDs to linked GUIDs has started on {$size} field areas."
			];

			foreach ($config as $mapping)
			{
				$this->processMapping($mapping);
			}
		} catch (\Exception $e) {
			throw new \Exception("Error: migrating linked IDs to linked GUIDs. " . $e->getMessage());
		}

		if (count($this->success) == 1)
		{
			$this->success[] = "Success: migration completed and all linked IDs are now migrated to linked GUIDs (on previous run).";
		}
		else
		{
			$this->success[] = "Success: migration completed and all linked IDs are now migrated to linked GUIDs.";
		}

		return $this->success;
	}

	/**
	 * Processes a single mapping based on its type.
	 *
	 * @param array $mapping Configuration for the current table and column.
	 *
	 * @return void
	 * @since 5.0.4
	 */
	private function processMapping(array $mapping): void
	{
		if ($mapping['valueType'] == 1)
		{
			$this->processBasicValue($mapping);
		}
		elseif ($mapping['valueType'] == 2)
		{
			$this->processSubformValue($mapping);
		}
		elseif ($mapping['valueType'] == 3)
		{
			$this->processSubSubformValue($mapping);
		}
		elseif ($mapping['valueType'] == 4)
		{
			$this->processDashboardValue($mapping);
		}
		elseif ($mapping['valueType'] == 5)
		{
			$this->processFieldValue($mapping);
		}
	}

	/**
	 * Processes basic values in a table and replaces IDs with GUIDs.
	 *
	 * @param array $mapping Configuration for the current table and column.
	 *
	 * @return void
	 * @since 5.0.4
	 */
	private function processBasicValue(array $mapping): void
	{
		$table = $mapping['table'];
		$column = $mapping['column'];
		$linkedTable = $mapping['linkedTable'];
		$linkedColumn = $mapping['linkedColumn'];
		$isArray = $mapping['array'];

		$update = false;

		$rows = $this->load->rows(["a.{$column}" => $column, 'a.id' => 'id'], ['a' => $table]) ?? [];

		foreach ($rows as $row)
		{
			$parentId = $row['id'];
			$value = $row[$column] ?? null;

			$hasUpdate = false;
			$updatedValue = null;

			if (empty($value))
			{
				continue;
			}

			if ($isArray)
			{
				$updatedValue = (is_array($value))
					? $this->processArray($value, $linkedTable, $linkedColumn, $hasUpdate)
					:  $this->processJson($value, $linkedTable, $linkedColumn, $hasUpdate);
			}
			elseif (is_numeric($value))
			{
				$guid = $this->getItemGuid($linkedTable, $linkedColumn, $value);
				if ($guid !== null)
				{
					$updatedValue = $guid;
					$hasUpdate = true;
				}
			}

			if (!$hasUpdate || $updatedValue === null)
			{
				continue; // Skip if no GUID updated or returned
			}

			if ($this->updateValue($table, $column, $updatedValue, $parentId))
			{
				$update = true;
			}
		}

		if ($update)
		{
			$this->success[] = "Success: migrated {$column}:field in {$table}:table to GUIDs from {$linkedTable}:table.";
		}
	}

	/**
	 * Processes subform values in a table and replaces IDs with GUIDs.
	 *
	 * @param array $mapping Configuration for the current table and subform column/field.
	 *
	 * @return void
	 * @since 5.0.4
	 */
	private function processSubformValue(array $mapping): void
	{
		$table = $mapping['table'];
		$column = $mapping['column'];
		$field = $mapping['field'];
		$linkedTable = $mapping['linkedTable'];
		$linkedColumn = $mapping['linkedColumn'];
		$isArray = $mapping['array'];

		$update = false;

		$rows = $this->load->rows(["a.{$column}" => $column, 'a.id' => 'id'], ['a' => $table]) ?? [];

		foreach ($rows as $row)
		{
			$parentId = $row['id'];
			$jsonData = $row[$column] ?? null;

			if (empty($jsonData))
			{
				continue;
			}

			$registry = new Registry($jsonData);
			$subformData = $registry->toArray();

			$hasUpdate = false;

			foreach ($subformData as &$item)
			{
				if (!empty($item[$field]))
				{
					if ($isArray)
					{
						$item[$field] = (is_array($item[$field]))
							? $this->processArray($item[$field], $linkedTable, $linkedColumn, $hasUpdate)
							:  $this->processJson($item[$field], $linkedTable, $linkedColumn, $hasUpdate);
					}
					elseif (is_numeric($item[$field]))
					{
						$guid = $this->getItemGuid($linkedTable, $linkedColumn, $item[$field]);
						if ($guid !== null)
						{
							$item[$field] = $guid;
							$hasUpdate = true;
						}
					}
				}
			}

			if (!$hasUpdate)
			{
				continue; // Skip if no GUID updated
			}

			$updatedJson = (string) new Registry($subformData);

			if ($this->updateValue($table, $column, $updatedJson, $parentId))
			{
				$update = true;
			}
		}

		if ($update)
		{
			$this->success[] = "Success: migrated {$column}->{$field}:field in {$table}:table to GUIDs from {$linkedTable}:table.";
		}
	}

	/**
	 * Processes sub-subform values in a table and replaces IDs with GUIDs.
	 *
	 * @param array $mapping Configuration for the current table and subform column/field.
	 *
	 * @return void
	 * @since 5.0.4
	 */
	private function processSubSubformValue(array $mapping): void
	{
		$table = $mapping['table'];
		$column = $mapping['column'];
		$sub = $mapping['sub'];
		$field = $mapping['field'];
		$linkedTable = $mapping['linkedTable'];
		$linkedColumn = $mapping['linkedColumn'];
		$isArray = $mapping['array'];

		$update = false;

		$rows = $this->load->rows(["a.{$column}" => $column, 'a.id' => 'id'], ['a' => $table]) ?? [];

		foreach ($rows as $row)
		{
			$parentId = $row['id'];
			$jsonData = $row[$column] ?? null;

			if (empty($jsonData))
			{
				continue;
			}

			$registry = new Registry($jsonData);
			$subformData = $registry->toArray();

			$hasUpdate = false;

			foreach ($subformData as &$item)
			{
				if (isset($item[$sub]) && is_array($item[$sub]))
				{
					foreach ($item[$sub] as &$subItem)
					{
						if (!empty($subItem[$field]))
						{
							if ($isArray)
							{
								$subItem[$field] = (is_array($subItem[$field]))
									? $this->processArray($subItem[$field], $linkedTable, $linkedColumn, $hasUpdate)
									:  $this->processJson($subItem[$field], $linkedTable, $linkedColumn, $hasUpdate);
							}
							elseif (is_numeric($subItem[$field]))
							{
								$guid = $this->getItemGuid($linkedTable, $linkedColumn, $subItem[$field]);
								if ($guid !== null)
								{
									$subItem[$field] = $guid;
									$hasUpdate = true;
								}
							}
						}
					}
				}
			}

			if (!$hasUpdate)
			{
				continue; // Skip if no GUID updated
			}

			$updatedJson = (string) new Registry($subformData);

			if ($this->updateValue($table, $column, $updatedJson, $parentId))
			{
				$update = true;
			}
		}

		if ($update)
		{
			$this->success[] = "Success: migrated {$column}->{$sub}->{$field}:field in {$table}:table to GUIDs from {$linkedTable}:table.";
		}
	}

	/**
	 * Processes dashboard values in a table and replaces IDs with GUIDs.
	 *
	 * @param array $mapping Configuration for the current table and column.
	 *
	 * @return void
	 * @since  5.1.1
	 */
	private function processDashboardValue(array $mapping): void
	{
		$table = $mapping['table'];
		$column = $mapping['column'];
		$linkedTables = $mapping['linkedTables'];
		$linkedColumn = $mapping['linkedColumn'];
		$isArray = $mapping['array'];

		$update = false;

		$rows = $this->load->rows(["a.{$column}" => $column, 'a.id' => 'id'], ['a' => $table]) ?? [];

		foreach ($rows as $row)
		{
			$parentId = $row['id'];
			$value = $row[$column] ?? null;

			$hasUpdate = false;
			$updatedValue = null;
			$targetKey = null;

			if (empty($value))
			{
				continue;
			}

			if (strpos($value, '_') !== false)
			{
				[$targetKey, $identifier] = explode('_', $value, 2);
				$linkedTable = $linkedTables[$targetKey] ?? null;
				$guid = null;
				if ($linkedTable !== null)
				{
					$guid = $this->getItemGuid($linkedTable, $linkedColumn, $identifier);
				}

				if ($guid !== null)
				{
					$updatedValue = $targetKey . '_' . $guid;
					$hasUpdate = true;
				}
			}

			if (!$hasUpdate || $updatedValue === null)
			{
				continue; // Skip if no GUID updated or returned
			}

			if ($this->updateValue($table, $column, $updatedValue, $parentId))
			{
				$update = true;
			}
		}

		if ($update)
		{
			$this->success[] = "Success: migrated {$column}:field in {$table}:table to GUIDs from {$linkedTable}:table.";
		}
	}

	/**
	 * Processes field values in a table and replaces IDs with GUIDs.
	 *
	 * @param array $mapping Configuration for the current table and column.
	 *
	 * @return void
	 * @since  5.1.1
	 */
	private function processFieldValue(array $mapping): void
	{
		$table = $mapping['table'];
		$column = $mapping['column'];
		$linkedTable = $mapping['linkedTable'];
		$linkedColumn = $mapping['linkedColumn'];

		$update = false;

		$rows = $this->load->rows(["a.{$column}" => $column, 'a.xml' => 'xml', 'a.id' => 'id'], ['a' => $table]) ?? [];

		foreach ($rows as $row)
		{
			$parentId = $row['id'];
			$value = $row[$column] ?? null;

			if (empty($value))
			{
				continue;
			}

			$guid = is_numeric($value) ? $this->getItemGuid($linkedTable, $linkedColumn, $value) : null;

			$hasValidGuid = $guid !== null;
			$updatedValue = $hasValidGuid ? $guid : $value;

			// Subform update logic for hardcoded GUIDs of the field types that has fields to update
			$requiresSubformUpdate = in_array(
				$updatedValue,
				[
					'7139f2c8-a70a-46a6-bbe3-4eefe54ca515', // global subform field type
					'05bf68d4-52f9-4705-8ae7-cba137fce0ad' // global repeatable field type (should not be used actually for J4+)
				],
				true
			);

        		$fields = null;
			if ($requiresSubformUpdate) 
			{
				$fields = $this->getSubfromFields($row['xml']);
			}

			if (!$hasValidGuid && !$requiresSubformUpdate || ($requiresSubformUpdate && $fields === null))
			{
				continue; // Skip if no GUID updated or returned
			}

			$row[$column] = $updatedValue;

			if (($fields !== null && $this->updateSubformValue($table, $row, $fields)) || $this->updateValue($table, $column, $updatedValue, $parentId))
			{
				$update = true;
			}
		}

		if ($update)
		{
			$this->success[] = "Success: migrated {$column}:field in {$table}:table to GUIDs from {$linkedTable}:table.";
		}
	}

	/**
	 * Retrieves or creates a GUID for a given linked table and ID (ITEM).
	 *
	 * @param string $table  The linked table name.
	 * @param string $column The column name in the linked table.
	 * @param mixed  $value  The value to check or convert.
	 *
	 * @return string|null The GUID for the given value, or null if skipped.
	 * @throws \Exception If the value is invalid.
	 * @since 5.0.4
	 */
	private function getItemGuid(string $table, string $column, $value): ?string
	{
		if (is_numeric($value))
		{
			// Check if already in cache
			$cacheKey = "$table:$column:$value";

			if (isset($this->guidCache[$cacheKey]))
			{
				return $this->guidCache[$cacheKey];
			}

			// Retrieve GUID from database
			$guid = $this->load->value(['a.guid' => 'guid'], ['a' => $table], ["a.{$column}" => $value]);

			if (!$this->validateGuid($guid))
			{
				// Create a new GUID
				$this->setTable($table);
				$guid = $this->getGuid('guid');
				$this->updateValue($table, 'guid', $guid, $value);
			}

			// Cache the GUID
			$this->guidCache[$cacheKey] = $guid;

			return $guid;
		}

		// Check if the value is already a GUID
		if ($this->validateGuid($value))
		{
			return null; // Skip, already a GUID
		}

		// convert to visible result
		$value_printed = var_export($value, true);

		// Raise an exception for invalid values
		throw new \Exception("Invalid value detected: ({$table}:table)->({$column}:column)->({$value_printed}:value). Must be either an integer or a valid GUID.");
	}

	/**
	 * Update the subform field.
	 *
	 * @param string $table   The table name.
	 * @param array  $row     The field row values
	 * @param array  $fields  The fields to update
	 *
	 * @return bool
	 * @since  5.1.1
	 */
	private function updateSubformValue($table, $row, array $fields): bool
	{
		$xml = json_decode($row['xml']);
		$xml = str_replace($fields['id'], $fields['guid'], $xml);
		$row['xml'] = json_encode($xml);
		return $this->update->row($row, 'id', $table);
	}

	/**
	 * get the subfrom fields.
	 *
	 * @param string $xml  The field xml
	 *
	 * @return array|null
	 * @since  5.1.1
	 */
	private function getSubfromFields(string $xml): ?array
	{
		$xml = json_decode($xml);
		$field_string = GetHelper::between(
			$xml, 'fields="', '"'
		);

		if (($fields = $this->stringToIntArray($field_string)) === [])
		{
			return null;
		}

		$bucket = [];
		$update = false;
		foreach ($fields as $field)
		{
			if (($guid = $this->getItemGuid('field', 'id', $field)) !== null)
			{
				$bucket[] = $guid;
				$update = true;
			}
			elseif ($this->validateGuid($field))
			{
				$bucket[] = $field;
			}
		}

		// only update if we have all values
		if ($update && count($fields) === count($bucket))
		{
			return [
				'guid' => 'fields="' . implode(',', $bucket) . '"',
				'id' => 'fields="' . $field_string . '"'
			];
		}

		return null;
	}

	/**
	 * Convert a comma-separated string to an array of integers.
	 *
	 * @param string $input Comma-separated string of values.
	 *
	 * @return int[] Cleaned array of integers.
	 * @since  5.1.1
	 */
	private function stringToIntArray(string $input): array
	{
		return array_values(array_filter(
			array_map(
				static fn($value) => is_numeric(trim($value)) ? (int) trim($value) : null,
				explode(',', $input)
			),
			static fn($val) => $val !== null
		));
	}

	/**
	 * Processes an json-array of basic values and replaces them with GUIDs.
	 *
	 * @param string $values	  JSON string containing the IDs.
	 * @param string $linkedTable The linked table name.
	 * @param string $linkedColumn The linked column name.
	 * @param string $hasUpdate   The switch to manage updates.
	 *
	 * @return string JSON string with updated GUIDs.
	 * @since 5.0.4
	 */
	private function processJson(string $values, string $linkedTable, string $linkedColumn, bool &$hasUpdate): string
	{
		$array = json_decode($values, true);
		$bucket = [];
		foreach ($array as $key => $value)
		{
			if (!empty($value))
			{
				$val = $this->getItemGuid($linkedTable, $linkedColumn, $value);
				if ($val !== null)
				{
					$bucket[$key] = $val;
					$hasUpdate = true;
				}
			}
		}

		return json_encode($bucket);
	}

	/**
	 * Processes an array values and replaces them with GUIDs.
	 *
	 * @param array  $values	   Array of IDs from the subform field.
	 * @param string $linkedTable  The linked table name.
	 * @param string $linkedColumn The linked column name.
	 * @param string $hasUpdate   The switch to manage updates.
	 *
	 * @return array The updated array with GUIDs.
	 * @since 5.0.4
	 */
	private function processArray(array $values, string $linkedTable, string $linkedColumn, bool &$hasUpdate): array
	{
		$bucket = [];
		foreach ($values as $key => $value)
		{
			if (!empty($value))
			{
				$val = $this->getItemGuid($linkedTable, $linkedColumn, $value);
				if ($val !== null)
				{
					$bucket[$key] = $val;
					$hasUpdate = true;
				}
			}
		}

		return $bucket;
	}

	/**
	 * Updates a value in the database.
	 *
	 * @param string $table  The table name.
	 * @param string $column The column to update.
	 * @param string $value  The updated value.
	 * @param int	$id	 The ID of the row to update.
	 *
	 * @return bool
	 * @since 5.0.4
	 */
	private function updateValue(string $table, string $column, string $value, int $id): bool
	{
		return $this->update->row(['id' => $id, $column => $value], 'id', $table);
	}

	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return void
	 * @since  5.0.4
	 */
	private function setTable(string $table): void
	{
		$this->table = $table;
	}

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since   5.0.4
	 */
	private function getTable(): string
	{
		return $this->table;
	}/***[/JCBGUI$$$$]***/

}

