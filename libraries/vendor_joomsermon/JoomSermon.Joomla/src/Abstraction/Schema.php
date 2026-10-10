<?php

/***[JCBGUI.power.licensing_template.733.$$$$]***/
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

namespace JoomSermon\Joomla\Abstraction;



/***[JCBGUI.power.head.733.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\CMS\Version;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\TableInterface as Table;
use JoomSermon\Joomla\Interfaces\SchemaInterface;


/**
 * Schema Checking
 * 
 * @since 3.2.1
 */
abstract class Schema implements SchemaInterface
{

/***[JCBGUI.power.main_class_code.733.$$$$]***/
	/**
	 * The Table Class.
	 *
	 * @var   Table
	 * @since 3.2.1
	 */
	protected Table $table;

	/**
	 * The Database Class
	 *
	 * @since 3.2.1
	 */
	protected $db;

	/**
	 * The local tables
	 *
	 * @var   array
	 * @since 3.2.1
	 */
	private array $tables;

	/**
	 * The component table prefix
	 *
	 * @var   string
	 * @since 3.2.1
	 */
	private string $prefix;

	/**
	 * The field unique keys
	 *
	 * @var   array
	 * @since 3.2.1
	 */
	private array $uniqueKeys;

	/**
	 * The field keys
	 *
	 * @var   array
	 * @since 3.2.1
	 */
	private array $keys;

	/**
	 * The current table columns
	 *
	 * @var   array
	 * @since 3.2.1
	 */
	private array $columns;

	/**
	 * The success messages of the action
	 *
	 * @var   array
	 * @since 3.2.1
	 */
	private array $success;

	/**
	 * Current Joomla Version We are IN
	 *
	 * @var     int
	 * @since 3.2.1
	 **/
	protected $currentVersion;

	/**
	 * Current DB Version We are IN
	 *
	 * @var     string
	 * @since 5.0.4
	 **/
	protected string $dbVersion;

	/**
	 * Current DB Type We are IN
	 *
	 * @var     string
	 * @since 5.0.4
	 **/
	protected string $dbType;

	/**
	 * Constructor.
	 *
	 * @param Table   $table   The Table Class.
	 *
	 * @since 3.2.1
	 * @throws \Exception If the database fails
	 */
	public function __construct(Table $table)
	{
		$this->table = $table;

		try {
			// set the database object
			$this->db = Factory::getDbo();

			// current DB version
			$this->dbVersion = $this->db->getVersion();

			// current DB type
			$this->dbType = $this->db->getServerType();

			// get current website tables
			$this->tables = $this->db->getTableList();

			// set the component table prefix
			$this->prefix = $this->db->getPrefix() . $this->getCode();

			// set the current version
			$this->currentVersion = Version::MAJOR_VERSION;
		} catch (\Exception $e) {
			throw new \Exception("Error: failed to initialize schema class due to a database error.");
		}
	}

	/**
	 * Check and update database schema for missing fields or tables.
	 *
	 * @return array   The array of successful updates/actions, if empty no update/action was taken.
	 * @since  3.2.1
	 * @throws \Exception If there is an error during the update process.
	 */
	public function update(): array
	{
		try {
			$this->success = [
				"Success: scan of the component tables started."
			];
			foreach ($this->table->tables() as $table)
			{
				$this->uniqueKeys = [];
				$this->keys = [];

				if ($this->tableExists($table))
				{
					$this->updateSchema($table);
				}
				else
				{
					$this->createTable($table);
				}
			}
		} catch (\Exception $e) {
			throw new \Exception("Error: updating database schema. " . $e->getMessage());
		}

		if (count($this->success) == 1)
		{
			$this->success[] = "Success: scan of the component tables completed with no update needed.";
		}
		else
		{
			$this->success[] = "Success: scan of the component tables completed.";
		}

		return $this->success;
	}

	/**
	 * Get the targeted component code
	 *
	 * @return  string
	 * @since 3.2.1
	 */
	abstract protected function getCode(): string;

	/**
	 * Check if a table exists in the database.
	 *
	 * @param string $table The name of the table to check.
	 *
	 * @return bool True if table exists, False otherwise.
	 * @since  3.2.1
	 */
	protected function tableExists(string $table): bool
	{
		return in_array($this->getTable($table), $this->tables);
	}

	/**
	 * Update the schema of an existing table.
	 *
	 * @param string $table  The table to update.
	 *
	 * @return void
	 * @since  3.2.1
	 * @throws \Exception If there is an error while updating the schema.
	 */
	public function updateSchema(string $table): void
	{
		try {
			$existingColumns = $this->getExistingColumns($table);
			$expectedColumns = $this->table->fields($table, true);

			$missingColumns = array_diff($expectedColumns, $existingColumns);

			if (!empty($missingColumns))
			{
				$this->addMissingColumns($table, $missingColumns);
			}

			$this->checkColumnsDataType($table, $expectedColumns);

		} catch (\Exception $e) {
			throw new \Exception("Error: updating schema for $table table. " . $e->getMessage());
		}

		if (!empty($missingColumns))
		{
			$column_s = (count($missingColumns) == 1) ? 'column' : 'columns';
			$missingColumns = implode(', ', $missingColumns);
			$this->success[] = "Success: added missing ($missingColumns) $column_s to $table table.";
		}
	}

	/**
	 * Create a table with all necessary fields.
	 *
	 * @param string $table The name of the table to create.
	 *
	 * @return void
	 * @since  3.2.1
	 * @throws \Exception If there is an error creating the table.
	 */
	public function createTable(string $table): void
	{
		try {
			$columns = [];
			$fields = $this->table->fields($table, true);
			$createTable = 'CREATE TABLE IF NOT EXISTS ' . $this->db->quoteName($this->getTable($table));

			foreach ($fields as $field)
			{
				if (($def = $this->getColumnDefinition($table, $field)) !== null)
				{
					$columns[] = $def;
				}
			}

			$columnDefinitions = implode(', ', $columns);

			$keys = $this->getTableKeys();

			$createTableSql = "$createTable ($columnDefinitions, $keys)";

			$this->db->setQuery($createTableSql);
			$this->db->execute();
		} catch (\Exception $e) {
			throw new \Exception("Error: failed to create missing $table table. " . $e->getMessage());
		}

		$this->success[] = "Success: created missing  $table table.";
	}

	/**
	 * Fetch existing columns from a database table.
	 *
	 * @param string $table The name of the table.
	 *
	 * @return array An array of column names.
	 * @since  3.2.1
	 */
	protected function getExistingColumns(string $table): array
	{
		$this->columns = $this->db->getTableColumns($this->getTable($table), false);

		return array_keys($this->columns);
	}

	/**
	 * Add missing columns to a table.
	 *
	 * @param string $table   The table to update.
	 * @param array  $columns List of missing columns/fields.
	 *
	 * @return void
	 * @since  3.2.1
	 * @throws \Exception If there is an error adding columns.
	 */
	protected function addMissingColumns(string $table, array $columns): void
	{
		try {
			$query = $this->db->getQuery(true);
			$alterTable = 'ALTER TABLE ' . $this->db->quoteName($this->getTable($table)) . ' ';

			// Start an ALTER TABLE query
			$alterQueries = [];
			foreach ($columns as $column)
			{
				if (($def = $this->getColumnDefinition($table, $column)) !== null)
				{
					$alterQueries[] = " ADD " . $def;
				}
			}

			$this->db->setQuery($alterTable . implode(', ', $alterQueries));
			$this->db->execute();
		} catch (\Exception $e) {
			$column_s = (count($columns) == 1) ? 'column' : 'columns';
			$columns = implode(', ', $columns);
			throw new \Exception("Error: failed to add ($columns) $column_s to $table table. " . $e->getMessage());
		}
	}

	/**
	 * Validate and update the data type of existing fields/columns
	 *
	 * @param string $table    The table to update.
	 * @param array  $columns  List of columns/fields to check.
	 *
	 * @return void
	 * @since  3.2.1
	 */
	protected function checkColumnsDataType(string $table, array $columns): void
	{
		$requireUpdate = [];
		foreach ($columns as $column)
		{
			$current = $this->columns[$column] ?? null;
			if ($current === null || ($expected = $this->table->get($table, $column, 'db')) === null)
			{
				continue;
			}

			// check if the data type and size match
			if ($this->isDataTypeChangeSignificant($current->Type, $expected['type']))
			{
				$requireUpdate[$column] = [
					'column' => $column,
					'current' => $current->Type,
					'expected' => $expected['type']
				];
			}
			// check if update of default values is needed
			if ($this->checkDefault($table, $column))
			{
				$requireUpdate[$column] = [
					'column' => $column,
					'current' => $current->Type,
					'expected' => $expected['type']
				];
			}
			// check if update of null is needed
			if ($this->checkNull($table, $column))
			{
				$requireUpdate[$column] = [
					'column' => $column,
					'current' => $current->Type,
					'expected' => $expected['type']
				];
			}
		}

		if (!empty($requireUpdate))
		{
			$this->updateColumnsDataType($table, $requireUpdate);
		}
	}

	/**
	 * Generates a SQL snippet for defining a table column, incorporating column type,
	 *    default value, nullability, and auto-increment properties.
	 *
	 * @param string $table The table name to be used.
	 * @param string $field The field name in the table to generate SQL for.
	 *
	 * @return string|null The SQL snippet for the column definition.
	 * @since 3.2.1
	 * @throws \Exception If the schema details cannot be retrieved or the SQL statement cannot be constructed properly.
	 */
	protected function getColumnDefinition(string $table, string $field): ?string
	{
		try {
			// Retrieve the database schema details for the specified table and field
			if (($db = $this->table->get($table, $field, 'db')) === null)
			{
				return null;
			}

			// Prepare the column name
			$column_name = $this->db->quoteName($field);
			$db['name'] = $field;

			// Prepare the type and default value SQL statement
			$type = $db['type'] ??  'TEXT';
			$db_default = isset($db['default']) ? $db['default'] : null;
			$default = $this->getDefaultValue($type, $db_default);

			// Prepare the null switch, and auto increment statement
			$null_switch = !empty($db['null_switch']) ? ' ' . $db['null_switch'] : '';

			// Prepare the auto increment statement
			$auto_increment = !empty($db['auto_increment']) ? " AUTO_INCREMENT" : '';

			// If there's a default value, the column should not be nullable
			if ($default !== '')
			{
				$null_switch = '';
			}

			$this->setKeys($db);

			// Assemble the SQL snippet for the column definition
			return "{$column_name} {$type}{$null_switch}{$auto_increment}{$default}";
		} catch (\Exception $e) {
			throw new \Exception("Error: failed to generate column definition for ($table.$field). " . $e->getMessage());
		}
	}

	/**
	 * Check and Update the default values if needed, including existing data adjustments
	 *
	 * @param string $table   The table to update.
	 * @param string $column  The column/field to check.
	 *
	 * @return bool
	 * @since  3.2.1
	 */
	protected function checkDefault(string $table, string $column): bool
	{
		// Retrieve the expected column configuration
		$expected = $this->table->get($table, $column, 'db');

		// Skip updates if the column is auto_increment
		if (isset($expected['auto_increment']) && $expected['auto_increment'] === true)
		{
			return false;
		}

		// Retrieve the current column configuration
		$current = $this->columns[$column];

		// Determine the new default value based on the expected settings
		$type = $expected['type'] ??  'TEXT';
		$db_default = isset($expected['default']) ? $expected['default'] : null;
		$newDefault = $this->getDefaultValue($type, $db_default, true);

		// First, adjust existing rows to conform to the new default if necessary
		if (is_numeric($newDefault) && $this->adjustExistingDefaults($table, $column, $current->Default, $newDefault))
		{
			$this->success[] = "Success: updated the ($column) defaults in $table table.";

			return true;
		}

		if (isset($expected['default']) && is_string($expected['default']) && strtoupper($expected['default']) === 'EMPTY' &&
			isset($current->Default) && is_string($current->Default) && strpos($current->Default, 'EMPTY') !== false)
		{
			return true; // little fix
		}

		return false;
	}

	/**
	 * Check and Update the null value if needed, including existing data adjustments
	 *
	 * @param string $table   The table to update.
	 * @param string $column  The column/field to check.
	 *
	 * @return bool
	 * @since  3.2.2
	 */
	protected function checkNull(string $table, string $column): bool
	{
		// Retrieve the expected column configuration
		$expected = $this->table->get($table, $column, 'db');

		// Skip updates if the null_switch is not set
		if (!isset($expected['null_switch']))
		{
			return false;
		}

		// Retrieve the current column configuration
		$current = $this->columns[$column];

		// Skip updates if the Null is not set
		if (!isset($current->Null))
		{
			return false;
		}

		// set the expected NULL
		$expected_null = 'NO';
		if ($expected['null_switch'] === "NULL")
		{
			$expected_null = 'YES';
		}

		// set the current NULL
		$current_null = $current->Null;

		// Prepare the type and default value SQL statement
		$type = $expected['type'] ??  'TEXT';
		$db_default = isset($expected['default']) ? $expected['default'] : null;
		$default = $this->getDefaultValue($type, $db_default, true);

		// check the null values if they match
		if ($current_null !== $expected_null && $current_null === 'NO' && empty($default))
		{
			$this->success[] = "Success: updated the ($column) null state in $table table.";

			return true;
		}

		return false;
	}

	/**
	 * Update the data type of the given fields.
	 *
	 * @param string $table   The table to update.
	 * @param array  $columns List of columns/fields that must be updated.
	 *
	 * @return void
	 * @since  3.2.1
	 */
	protected function updateColumnsDataType(string $table, array $columns): void
	{
		$alterTable = 'ALTER TABLE ' . $this->db->quoteName($this->getTable($table));
		foreach ($columns as $column => $types)
		{
			if (($def = $this->getColumnDefinition($table, $column)) === null)
			{
				continue;
			}

			$dbField = $this->db->quoteName($column);
			$alterQuery = "$alterTable CHANGE $dbField ". $def;

			if ($this->updateColumnDataType($alterQuery, $table, $column))
			{
				$current = $types['current'] ?? 'error';
				$expected = $types['expected'] ?? 'error';
				$this->success[] = "Success: updated ($column) column datatype $current to $expected in $table table.";
			}
		}
	}

	/**
	 * Add the component name to get the full table name.
	 *
	 * @param string $table The table name.
	 *
	 * @return void
	 * @since  3.2.1
	 */
	protected function getTable(string $table): string
	{
		return $this->prefix . '_' . $table;
	}

	/**
	 * Determines if the change in data type between two definitions is significant.
	 *
	 * This function checks if there's a significant difference between the current
	 * data type and the expected data type that would require updating the database schema.
	 * It ignores display width for numeric types where MySQL considers these attributes
	 * irrelevant for storage but considers size and other modifiers for types like VARCHAR.
	 *
	 * @param string  $currentType    The current data type from the database schema.
	 * @param string  $expectedType   The expected data type to validate against.
	 *
	 * @return bool  Returns true if the data type change is significant, otherwise false.
	 * @since  3.2.1
	 */
	protected function isDataTypeChangeSignificant(string $currentType, string $expectedType): bool
	{
		// Normalize both input types to lowercase and remove extra spaces for comparison
		$currentType = strtolower(trim($currentType));
		$expectedType = strtolower(trim($expectedType));

		// Regex to extract the base data type and numeric parameters (size and precision) with named groups
		$typePattern = '/^(?<datatype>\w+)(\((?<params>\s*\d+\s*(,\s*\d+\s*)?)\))?/';

		// Match types and parameters
		preg_match($typePattern, $currentType, $currentMatches);
		preg_match($typePattern, $expectedType, $expectedMatches);

		// Compare base types (datatype without size/precision)
		if ($currentMatches['datatype'] !== $expectedMatches['datatype'])
		{
			return true; // Base types differ
		}

		// Define numeric types where display width is irrelevant but precision (for DECIMAL) matters
		$sizeIrrelevantTypes = [
			'int', 'tinyint', 'smallint', 'mediumint', 'bigint', 
			'float', 'double' // Numeric types where display width is irrelevant
		];

		// Handle DECIMAL and NUMERIC types explicitly (precision and scale are relevant)
		if (in_array($currentMatches['datatype'], ['decimal', 'numeric']))
		{
			// Extract precision and scale (if present)
			if ($currentMatches['params'] !== $expectedMatches['params'])
			{
				return true; // Precision or scale has changed
			}
		}

		// Check if the type is in the list of size-irrelevant types
		if (in_array($currentMatches['datatype'], $sizeIrrelevantTypes))
		{
			// Remove irrelevant parts like display width and "unsigned" for size-irrelevant types, including extra spaces
			$currentBaseType = preg_replace('/\s*\(\s*\d+(\s*,\s*\d+)?\s*\)\s*|\s*unsigned\s*/', '', $currentType);
			$expectedBaseType = preg_replace('/\s*\(\s*\d+(\s*,\s*\d+)?\s*\)\s*|\s*unsigned\s*/', '', $expectedType);

			// Compare base types after normalization
			return $currentBaseType !== $expectedBaseType;
		}

		// For types where size is relevant (e.g., VARCHAR, CHAR, etc.), compare the full definitions
		// Normalize size parameters by removing extra spaces around commas, e.g., "decimal(5 , 2)" -> "decimal(5,2)"
		$normalizedCurrentType = preg_replace('/\s*,\s*/', ',', $currentType);
		$normalizedExpectedType = preg_replace('/\s*,\s*/', ',', $expectedType);

		// Perform a full comparison for types where size matters
		return $normalizedCurrentType !== $normalizedExpectedType;
	}

	/**
	 * Updates existing rows in a column to a new default value
	 *
	 * @param string $table           The table to update.
	 * @param string $column          The column to update.
	 * @param mixed  $currentDefault  Current default value.
	 * @param mixed  $newDefault      The new default value to be set.
	 *
	 * @return bool True if update was successful, false if no update was needed.
	 * @since  3.2.1
	 * @throws \Exception If there is an error updating column defaults.
	 */
	protected function adjustExistingDefaults(string $table, string $column, $currentDefault, $newDefault): bool
	{
		// Determine if adjustment is needed based on new and current defaults
		if ($newDefault !== $currentDefault)
		{
			try {
				// Format the new default for SQL use
				$sqlDefault = $this->db->quote($newDefault);

				$updateTable = 'UPDATE ' . $this->db->quoteName($this->getTable($table));
				$dbField = $this->db->quoteName($column);

				if (isset($this->columns[$column]))
				{
					$fieldType = strtoupper($this->columns[$column]->Type);

					// If the field is numeric, avoid comparing with empty string
					if (strpos($fieldType, 'INT') !== false ||
						strpos($fieldType, 'FLOAT') !== false ||
						strpos($fieldType, 'DOUBLE') !== false ||
						strpos($fieldType, 'DECIMAL') !== false)
					{
						$whereCondition = "$dbField IS NULL OR $dbField = 0";
					}
					else
					{
						// Default condition for non-numeric fields
						$whereCondition = "$dbField IS NULL OR $dbField = ''";
					}

					// Update SQL to set new default on existing rows where the default is currently the old default
					$sql = $updateTable . " SET $dbField = $sqlDefault WHERE $whereCondition";

					// Execute the update
					$this->db->setQuery($sql);
					return $this->db->execute();
				}
				else
				{
					throw new \Exception("Error: Column $column does not exist in table $table.");
				}
			}
			catch (\Exception $e)
			{
				throw new \Exception("Error: failed to update ($column) column defaults in $table table. " . $e->getMessage());
			}
		}
		return false;
	}

	/**
	 * Update the data type of the given field.
	 *
	 * @param string $updateString  The SQL command to update the column data type
	 * @param string $table         The table to update.
	 * @param string $field         Column/field that must be updated.
	 *
	 * @return bool  true on succes
	 * @since  3.2.1
	 * @throws \Exception If there is an error adding columns.
	 */
	protected function updateColumnDataType(string $updateString, string $table, string $field): bool
	{
		try {
			$this->db->setQuery($updateString);
			return $this->db->execute();
		} catch (\Exception $e) {
			throw new \Exception("Error: failed to update the datatype of ($field) column in $table table. " . $e->getMessage());
		}
	}

	/**
	 * Key all needed keys for this table
	 *
	 * @return string of keys
	 * @since  3.2.1
	 */
	protected function getTableKeys(): string
	{
		$keys = [];
		$keys[] = 'PRIMARY KEY  (`id`)'; // TODO (we may want this to be dynamicly set)

		if (!empty($this->uniqueKeys))
		{
			$keys[] = implode(', ', $this->uniqueKeys);
		}

		if (!empty($this->keys))
		{
			$keys[] = implode(', ', $this->keys);
		}

		return implode(', ', $keys);
	}

	/**
	 * Function to set the view keys
	 *
	 * @param string $column The field column database array values
	 *
	 * @return void
	 * @since  3.2.1
	 */
	protected function setKeys(array $column): void
	{
		$this->setUniqueKey($column);
		$this->setKey($column);
	}

	/**
	 * Function to set the unique key
	 *
	 * @param string $column The field column database array values
	 *
	 * @return void
	 * @since  3.2.1
	 */
	protected function setUniqueKey(array $column): void
	{
		if (isset($column['unique_key']) && $column['unique_key'])
		{
			$key = $column['unique_key_name'] ?? $column['name'];
			$this->uniqueKeys[] = "UNIQUE KEY `idx_" . $key . "` (`" . $column['name'] . "`)";
		}
	}

	/**
	 * Function to set the key
	 *
	 * @param string $column The field column database array values
	 *
	 * @return void
	 * @since  3.2.1
	 */
	protected function setKey(array $column): void
	{
		if (isset($column['key']) && $column['key'])
		{
			$key = $column['key_name'] ?? $column['name'];
			$this->keys[] = "KEY `idx_" . $key . "` (`" . $column['name'] . "`)";
		}
	}

	/**
	 * Adjusts the default value SQL fragment for a database field based on its type and specific rules.
	 *
	 * If the field is of type DATETIME and the Joomla version is not 3, it sets the default to CURRENT_TIMESTAMP
	 * if not explicitly specified otherwise. For all other types it handles defaults by either leaving them unset or applying
	 * the provided default, properly quoted for SQL safety. When a 'EMPTY' default is specified, it returns no default at all. (:)
	 *
	 * @param string	   $type          The type of the database field (e.g., 'DATETIME').
	 * @param string|null  $defaultValue  Optional default value for the field, null if not provided.
	 * @param bool         $pure          Optional to add the 'DEFAULT' string or not.
	 *
	 * @return string      The SQL fragment to set the default value for a field.
	 * @since 3.2.1
	 * @throws \RuntimeException If the database unsupported
	 */
	protected function getDefaultValue(string $type, ?string $defaultValue, bool $pure = false): string
	{
		if ($defaultValue === null || strtoupper($defaultValue) === 'EMPTY')
		{
			return '';
		}

		// Logic to handle DATETIME default values based on database type
		if (strtoupper($type) === 'DATETIME')
		{
			if ($this->dbType === 'mysql')
			{
				// MySQL-specific logic
				if (version_compare($this->dbVersion, '5.6', '>='))
				{
					return $pure ? "CURRENT_TIMESTAMP" : " DEFAULT CURRENT_TIMESTAMP";
				}
				else
				{
					return $pure ? "'0000-00-00 00:00:00'" : " DEFAULT '0000-00-00 00:00:00'";
				}
			}
			elseif ($this->dbType === 'pgsql')
			{
				// PostgreSQL supports CURRENT_TIMESTAMP universally
				return $pure ? "CURRENT_TIMESTAMP" : " DEFAULT CURRENT_TIMESTAMP";
			}
			else
			{
				// Unsupported database type (at this point... we can grow this area)
				throw new \RuntimeException("Unsupported database type: {$dbType}");
			}
		}

		// Apply and quote the default value
		$sql_default = $this->quote($defaultValue);
		return $pure ? $defaultValue : " DEFAULT $sql_default";
	}

	/**
	 * Set a value based on data type
	 *
	 * @param   mixed  $value   The value to set
	 *
	 * @return  mixed
	 * @since   3.2.0
	 **/
	protected function quote($value)
	{
		if ($value === null) // hmm the null does pose an issue (will keep an eye on this)
		{
			return 'NULL';
		}

		if (is_numeric($value))
		{
			if (filter_var($value, FILTER_VALIDATE_INT))
			{
				return (int) $value;
			}
			elseif (filter_var($value, FILTER_VALIDATE_FLOAT))
			{
				return (float) $value;
			}
		}
		elseif (is_bool($value)) // not sure if this will work well (but its correct)
		{
			return $value ? 'TRUE' : 'FALSE';
		}
		// For date and datetime values
		elseif ($value instanceof \DateTime)
		{
			return $this->db->quote($value->format('Y-m-d H:i:s'));
		}

		// For other data types, just escape it
		return $this->db->quote($value);
	}/***[/JCBGUI$$$$]***/

}

