<?php

/***[JCBGUI.power.licensing_template.124.$$$$]***/
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
use JoomSermon\Joomla\Interfaces\Database\LoadInterface;
use JoomSermon\Joomla\Abstraction\Database;


/**
 * Database Load
 * 
 * @since 3.2.0
 */
final class Load extends Database implements LoadInterface
{

/***[JCBGUI.power.main_class_code.124.$$$$]***/
	/**
	 * Load data rows as an array of associated arrays
	 *
	 * @param   array        $select   Array of selection keys
	 * @param   array        $tables   Array of tables to search
	 * @param   array|null   $where    Array of where key=>value match exist
	 * @param   array|null   $order    Array of how to order the data
	 * @param   int|null     $limit    Limit the number of values returned
	 *
	 * @return  array|null
	 * @since   3.2.0
	 **/
	public function rows(array $select, array $tables, ?array $where = null,
		?array $order = null, ?int $limit = null): ?array
	{
		// set key if found
		$key = $this->getKey($select);

		// check if we can get many rows
		if ($this->many($select, $tables, $where, $order, $limit))
		{
			// return associated arrays from the table records
			return $this->db->loadAssocList($key);
		}

		// data does not exist
		return null;
	}

	/**
	 * Load data rows as an array of objects
	 *
	 * @param   array        $select   Array of selection keys
	 * @param   array        $tables   Array of tables to search
	 * @param   array|null   $where    Array of where key=>value match exist
	 * @param   array|null   $order    Array of how to order the data
	 * @param   int|null     $limit    Limit the number of values returned
	 *
	 * @return  array|null
	 * @since   3.2.0
	 **/
	public function items(array $select, array $tables, ?array $where = null,
		?array $order = null, ?int $limit = null): ?array
	{
		// set key if found
		$key = $this->getKey($select);

		// check if we can get many rows
		if ($this->many($select, $tables, $where, $order, $limit))
		{
			// return associated arrays from the table records
			return $this->db->loadObjectList($key);
		}

		// data does not exist
		return null;
	}

	/**
	 * Load data row as an associated array
	 *
	 * @param   array        $select   Array of selection keys
	 * @param   array       $tables  Array of tables to search
	 * @param   array|null  $where   Array of where key=>value match exist
	 * @param   array|null  $order    Array of how to order the data
	 *
	 * @return  array|null
	 * @since   3.2.0
	 **/
	public function row(array $select, array $tables, ?array $where = null, ?array $order = null): ?array
	{
		// check if we can get one row
		if ($this->one($select, $tables, $where, $order))
		{
			return $this->db->loadAssoc();
		}

		// data does not exist
		return null;
	}

	/**
	 * Load data row as an object
	 *
	 * @param   array        $select   Array of selection keys
	 * @param   array       $tables  Array of tables to search
	 * @param   array|null  $where   Array of where key=>value match exist
	 * @param   array|null  $order    Array of how to order the data
	 *
	 * @return  object|null
	 * @since   3.2.0
	 **/
	public function item(array $select, array $tables, ?array $where = null, ?array $order = null): ?object
	{
		// check if we can get one row
		if ($this->one($select, $tables, $where, $order))
		{
			return $this->db->loadObject();
		}

		// data does not exist
		return null;
	}

	/**
	 * Get the max value based on a filtered result from a given table
	 *
	 * @param   string     $field     The field key
	 * @param   string     $tables    The tables
	 * @param   array      $filter    The filter keys
	 *
	 * @return  int|null
	 * @since   3.2.0
	 **/
	public function max($field, array $tables, array $filter): ?int
	{
		if (($tables = $this->normalizeTables($tables)) === null)
		{
			return null;
		}

		// get the query
		$query = $this->query(["all" => "MAX(`$field`)"], $tables, $filter);

		// Load the max number
		$this->db->setQuery($query);
		$this->db->execute();

		// check if we have values
		if ($this->db->getNumRows())
		{
			return (int) $this->db->loadResult();
		}

		// data does not exist
		return null;
	}

	/**
	 * Count the number of items based on filter result from a given table
	 *
	 * @param   string     $tables    The table
	 * @param   array      $filter    The filter keys
	 *
	 * @return  int|null
	 * @since   3.2.0
	 **/
	public function count(array $tables, array $filter): ?int
	{
		if (($tables = $this->normalizeTables($tables)) === null)
		{
			return null;
		}

		// get the query
		$query = $this->query(["all" => 'COUNT(*)'], $tables, $filter);

		// Load the max number
		$this->db->setQuery($query);
		$this->db->execute();

		// check if we have values
		if ($this->db->getNumRows())
		{
			return (int) $this->db->loadResult();
		}

		// data does not exist
		return null;
	}

	/**
	 * Load one value from a row
	 *
	 * @param   array        $select   Array of selection keys
	 * @param   array       $tables  Array of tables to search
	 * @param   array|null  $where   Array of where key=>value match exist
	 * @param   array|null  $order    Array of how to order the data
	 *
	 * @return  mixed
	 * @since   3.2.0
	 **/
	public function value(array $select, array $tables, ?array $where = null, ?array $order = null)
	{
		// check if we can get one value
		if ($this->one($select, $tables, $where, $order))
		{
			return $this->db->loadResult();
		}

		// data does not exist
		return null;
	}

	/**
	 * Load values from multiple rows
	 *
	 * @param   array        $select   Array of selection keys
	 * @param   array        $tables   Array of tables to search
	 * @param   array|null   $where    Array of where key=>value match exist
	 * @param   array|null   $order    Array of how to order the data
	 * @param   int|null     $limit    Limit the number of values returned
	 *
	 * @return  array|null
	 * @since   3.2.2
	 **/
	public function values(array $select, array $tables, ?array $where = null,
		?array $order = null, ?int $limit = null): ?array
	{
		// check if we can get many rows
		if ($this->many($select, $tables, $where, $order, $limit))
		{
			return $this->db->loadColumn();
		}

		// data does not exist
		return null;
	}

	/**
	 * Load many
	 *
	 * @param   array        $select   Array of selection keys
	 * @param   array        $tables   Array of tables to search
	 * @param   array|null   $where    Array of where key=>value match exist
	 * @param   array|null   $order    Array of how to order the data
	 * @param   int|null     $limit    Limit the number of values returned
	 *
	 * @return  bool
	 * @since   3.2.0
	 **/
	protected function many(array $select, array $tables, ?array $where = null,
		?array $order = null, ?int $limit = null): bool
	{
		if (($tables = $this->normalizeTables($tables)) === null)
		{
			return false;
		}

		// get the query
		$query = $this->query($select, $tables, $where, $order, $limit);

		// Load the items
		$this->db->setQuery($query);
		$this->db->execute();

		// check if we have values
		if ($this->db->getNumRows())
		{
			return true;
		}

		// data does not exist
		return false;
	}

	/**
	 * Load one
	 *
	 * @param   array       $select  Array of selection keys
	 * @param   array       $tables  Array of tables to search
	 * @param   array|null  $where   Array of where key=>value match exist
	 * @param   array|null  $order   Array of how to order the data
	 *
	 * @return  bool
	 * @since   3.2.0
	 **/
	protected function one(array $select, array $tables, ?array $where = null, ?array $order = null): bool
	{
		if (($tables = $this->normalizeTables($tables)) === null)
		{
			return false;
		}

		// get the query
		$query = $this->query($select, $tables, $where, $order);

		// Load the item
		$this->db->setQuery($query, 0, 1);
		$this->db->execute();

		// check if we have values
		if ($this->db->getNumRows())
		{
			return true;
		}

		// data does not exist
		return false;
	}

	/**
	 * Get the query object.
	 *
	 * @param   array        $select   Array of selection keys.
	 * @param   array        $tables   Array of tables to search.
	 * @param   array|null   $where    Array of where key=>value match exist.
	 * @param   array|null   $order    Array of how to order the data.
	 * @param   int|null     $limit    Limit the number of values returned.
	 *
	 * @return  object|null  The query object (DatabaseQuery).
	 * @since   3.2.0
	 */
	protected function query(array $select, array $tables, ?array $where = null,
		?array $order = null, ?int $limit = null): ?object
	{
		$query = $this->db->createQuery();

		$this->applySelect($query, $select);
		$this->applyFromAndJoins($query, $tables);
		$this->applyWhere($query, $where);
		$this->applyOrder($query, $order);
		$this->applyLimit($query, $limit);

		return $query;
	}

	/**
	 * Apply SELECT clause to the query.
	 *
	 * Supports auto-aliasing and intelligent prefixing.
	 *
	 * @param   object  $query   The query object.
	 * @param   array   $select  The select definition.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	protected function applySelect(object $query, array $select): void
	{
		// Handle 'all' separately first
		if (isset($select['all']))
		{
			if (ArrayHelper::check($select['all']))
			{
				foreach ($select['all'] as $selectAll)
				{
					$query->select($selectAll);
				}
			}
			elseif (is_string($select['all']))
			{
				$query->select($select['all']);
			}

			unset($select['all']);
		}

		// Normalize the select array to ensure key=>alias pairs
		$normalized = $this->normalizeSelectArray($select);

		if (!ArrayHelper::check($normalized))
		{
			return;
		}

		// Quote and apply to query
		$query->select(
			$this->db->quoteName(
				array_keys($normalized),
				array_values($normalized)
			)
		);
	}

	/**
	 * Apply FROM and JOIN clauses.
	 *
	 * @param   object  $query   The query object.
	 * @param   array   $tables  The table definitions.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	protected function applyFromAndJoins(object $query, array $tables): void
	{
		$query->from($this->db->quoteName($this->getTable($tables['a']), 'a'));
		unset($tables['a']);

		if (ArrayHelper::check($tables))
		{
			foreach ($tables as $as => $details)
			{
				$table_name = $details['name'] ?? null;
				$join_on = $details['join_on'] ?? null;
				$as_on = $details['as_on'] ?? null;
				$join = strtoupper($details['join'] ?? 'LEFT');

				if (empty($table_name) || empty($join_on) || empty($as_on))
				{
					continue;
				}

				// basic join for now :)
				$query->join(
					$join,
					$this->db->quoteName($this->getTable($table_name), $as)
					. ' ON (' . $this->db->quoteName($join_on)
					. ' = ' . $this->db->quoteName($as_on) . ')'
				);
			}
		}
	}

	/**
	 * Apply WHERE clauses.
	 *
	 * @param   object      $query  The query object.
	 * @param   array|null  $where  Where clause array.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	protected function applyWhere(object $query, ?array $where): void
	{
		$where = $this->normalizeKeys($where ?? []);
		if (!ArrayHelper::check($where))
		{
			return;
		}

		foreach ($where as $key => $condition)
		{
			$this->handleWhereCondition($query, $key, $condition);
		}
	}

	/**
	 * Apply ORDER BY clause.
	 *
	 * @param   object      $query  The query object.
	 * @param   array|null  $order  Order by clause.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	protected function applyOrder(object $query, ?array $order): void
	{
		$order = $this->normalizeKeys($order ?? []);
		if (ArrayHelper::check($order))
		{
			foreach ($order as $key => $direction)
			{
				$query->order($this->db->quoteName($key) . ' ' . $direction);
			}
		}
	}

	/**
	 * Apply LIMIT clause.
	 *
	 * @param   object    $query  The query object.
	 * @param   int|null  $limit  Number of records to limit.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	protected function applyLimit(object $query, ?int $limit): void
	{
		if (is_numeric($limit))
		{
			$query->setLimit($limit);
		}
	}

	/**
	 * Get the key from the selection array.
	 *
	 * This function retrieves a key from the provided selection array.
	 * The key is removed from the array after being retrieved.
	 *
	 * @param   array   $select   Array of selection keys.
	 *
	 * @return  string|null   The key, or null if no key is found.
	 * @since   3.2.2
	 **/
	protected function getKey(array &$select): ?string
	{
		$key = null;

		// Check for 'key' first and ensure it's a string.
		if (isset($select['key']) && is_string($select['key']))
		{
			$key = $select['key'];
			unset($select['key']); // Remove 'key' from the array.
		}

		return $key;
	}

	/**
	 * Normalize mixed-format table definitions to a consistent structure.
	 *
	 * Supported formats:
	 * - ['a' => 'table']
	 * - ['a' => 'table', 'b' => ['name' => 'table2', 'join_on' => 'a.id', 'as_on' => 'b.entity']]
	 * - ['a.table', 'b.table2.id.entity']
	 * - ['a:table', 'b:table2:id:entity']
	 * - ['table']
	 * - ['table', 'table2.id.entity']
	 * - ['table', 'table2:id:entity']
	 *
	 * @param   array  $tables  The raw input
	 *
	 * @return  array|null  Normalized ['alias' => 'table'] and join mappings, or null if 'a' is missing
	 * @since   5.1.1
	 */
	protected function normalizeTables(array $tables): ?array
	{
		if (empty($tables))
		{
			return null;
		}

		$normalized = [];

		foreach ($tables as $key => $value)
		{
			if (is_int($key))
			{
				$this->parseVariousSyntax($value, $normalized);
			}
			else
			{
				$this->parseAssocSyntax($key, $value, $normalized);
			}
		}

		return isset($normalized['a']) ? $normalized : null;
	}

	/**
	 * Normalize all Keys in array by ensuring:
	 * - All keys are fully qualified (add "a." if missing)
	 *
	 * @param   array  $data  The raw date array
	 *
	 * @return  array  Normalized array with 'table.column' => $value
	 * @since   5.1.1
	 */
	private function normalizeKeys(array $data): array
	{
		$normalized = [];

		foreach ($data as $key => $value)
		{
			// If indexed array (no alias), we ignore this row
			if (is_int($key))
			{
				continue;
			}
			else
			{
				$column = $this->normalizeColumn('a', $key);
			}

			$normalized[$column] = $value;
		}

		return $normalized;
	}

	/**
	 * Normalize SELECT array by ensuring:
	 * - All keys are fully qualified (add "a." if missing)
	 * - All values are aliases (either provided or extracted from key)
	 *
	 * @param   array  $select  The raw select array
	 *
	 * @return  array  Normalized array with 'table.column' => 'alias'
	 * @since   5.1.1
	 */
	private function normalizeSelectArray(array $select): array
	{
		$normalized = [];

		foreach ($select as $key => $value)
		{
			// If indexed array (no alias), use the value as key
			if (is_int($key))
			{
				$column = $this->normalizeColumn('a', $value);
				$alias  = $this->extractAlias($column);
			}
			else
			{
				$column = $this->normalizeColumn('a', $key);
				$alias  = is_string($value) && $value !== '' ? $value : $this->extractAlias($column);
			}

			$normalized[$column] = $alias;
		}

		return $normalized;
	}

	/**
	 * Extracts the alias from a column name.
	 * (e.g., "a.id" → "id", "b.user_name" → "user_name", "name" → "name")
	 *
	 * @param   string  $column  Fully-qualified column name
	 *
	 * @return  string  Alias
	 * @since   5.1.1
	 */
	private function extractAlias(string $column): string
	{
		$parts = explode('.', $column);
		return end($parts);
	}

	/**
	 * Handle a single where condition.
	 *
	 * @param   object     $query     The query object.
	 * @param   string     $column    The column name.
	 * @param   mixed      $condition The condition value or config array.
	 * @param   int        $counter   The depth counter.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function handleWhereCondition(object $query, string $column, $condition, int $counter = 0): void
	{
		if (ArrayHelper::check($condition))
		{
			if (!isset($condition['value'], $condition['operator']))
			{
				// allow only one step down, so one column can different where mapping
				if ($counter === 0)
				{
					$counter++;
					foreach ($condition as $column_condition)
					{
						$this->handleWhereCondition($query, $column, $column_condition, $counter);
					}
				}
				return;
			}

			$this->handleAdvancedCondition(
				$query,
				$this->db->quoteName($column),
				$condition['value'],
				$condition['operator'],
				$condition['quote'] ?? true
			);
		}
		else
		{
			// Simple key = value clause
			$query->where($this->db->quoteName($column) . ' = ' . $this->quote($condition));
		}
	}

	/**
	 * Handle advanced (operator-based) where conditions.
	 *
	 * @param   object        $query     The query object.
	 * @param   string        $column    The quoted column name.
	 * @param   mixed         $value     The value to compare.
	 * @param   string        $operator  The SQL operator to use.
	 * @param   bool          $quote     Whether to quote the value(s).
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function handleAdvancedCondition(
		object $query,
		string $column,
		$value,
		string $operator,
		bool $quote = true
	): void
	{
		if (ArrayHelper::check($value))
		{
			$this->handleArrayCondition($query, $column, $value, $operator, $quote);
		}
		else
		{
			$this->handleScalarCondition($query, $column, $value, $operator, $quote);
		}
	}

	/**
	 * Handle an array-based condition, e.g., IN (...) or NOT IN (...).
	 *
	 * @param   object     $query     The query object.
	 * @param   string     $column    The quoted column name.
	 * @param   array      $values    The array of values.
	 * @param   string     $operator  The SQL operator (e.g., IN, NOT IN).
	 * @param   bool       $quote     Whether to quote the values.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function handleArrayCondition(
		object $query,
		string $column,
		array $values,
		string $operator,
		bool $quote = true
	): void
	{
		$list = $quote
			? implode(',', array_map(fn($v) => $this->quote($v), $values))
			: implode(',', $values);

		$query->where("{$column} {$operator} ({$list})");
	}

	/**
	 * Handle a scalar value condition.
	 *
	 * @param   object     $query     The query object.
	 * @param   string     $column    The quoted column name.
	 * @param   mixed      $value     The value to compare.
	 * @param   string     $operator  The SQL operator (e.g., =, !=, >).
	 * @param   bool       $quote     Whether to quote the value.
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function handleScalarCondition(
		object $query,
		string $column,
		$value,
		string $operator,
		bool $quote = true
	): void
	{
		$formatted = $quote ? $this->quote($value) : $value;
		$query->where("{$column} {$operator} {$formatted}");
	}

	/**
	 * Parse various short syntaxes: colon, pipe, dot, or fallback flat value.
	 *
	 * @param   string        $entry       The raw string entry
	 * @param   array         &$normalized The normalized output reference
	 * @param   string|null   $alias       Optional override alias
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function parseVariousSyntax(string $entry, array &$normalized, ?string $alias = null): void
	{
		$entry = trim($entry);

		if ($entry === '')
		{
			return;
		}

		if (strpos($entry, ':') !== false)
		{
			$this->parseColonSyntax($alias ? "{$alias}:{$entry}" : $entry, $normalized);
			return;
		}

		if (strpos($entry, '|') !== false)
		{
			$this->parsePipeSyntax($alias ? "{$alias}|{$entry}" : $entry, $normalized);
			return;
		}

		if (strpos($entry, '.') !== false)
		{
			$this->parseDotSyntax($alias ? "{$alias}.{$entry}" : $entry, $normalized);
			return;
		}

		// Default: flat table name
		if (!empty($alias))
		{
			if ($alias === 'a') // stop infinite recursion
			{
				$this->addTableEntry($alias, $entry, $normalized);
			}
		}
		else
		{
			$this->parseFlatTable($entry, $normalized);
		}
	}

	/**
	 * Parse colon syntax such as "a:table", "b:table:join_on:as_on", or "table:join_on:as_on"
	 *
	 * @param   string  $entry       The colon-delimited string
	 * @param   array   &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function parseColonSyntax(string $entry, array &$normalized): void
	{
		$this->parseArrayEntry(explode(':', $entry), $normalized);
	}

	/**
	 * Parse colon syntax such as "a|table", "b|table|join_on|as_on", or "table|join_on:as_on"
	 *
	 * @param   string  $entry       The pipe-delimited string
	 * @param   array   &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function parsePipeSyntax(string $entry, array &$normalized): void
	{
		$this->parseArrayEntry(explode('|', $entry), $normalized);
	}

	/**
	 * Parse dot syntax such as "a.table", "b.table2.id.entity", "table.join_on.as_on"
	 *
	 * @param   string  $entry       The dot-delimited string
	 * @param   array   &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function parseDotSyntax(string $entry, array &$normalized): void
	{
		$this->parseArrayEntry(explode('.', $entry), $normalized);
	}

	/**
	 * Combine the entry parts int the corret format
	 *
	 * @param   string  $parts       The parts of the entry
	 * @param   array   &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function parseArrayEntry(array $parts, array &$normalized): void
	{
		$count = count($parts);

		if ($count === 2)
		{
			[$alias, $table] = $parts;
			$this->addTableEntry($alias, $table, $normalized);
			return;
		}

		if ($count === 3)
		{
			$alias = chr(97 + count($normalized));
			[$table, $join_on, $as_on] = $parts;
			$this->addJoinTableEntry($alias, $table, $join_on, $as_on, null, $normalized);
			return;
		}

		if ($count === 4)
		{
			[$alias, $table, $join_on, $as_on] = $parts;
			$this->addJoinTableEntry($alias, $table, $join_on, $as_on, null, $normalized);
			return;
		}

		if ($count === 5)
		{
			[$alias, $table, $join_on, $as_on, $join] = $parts;
			$this->addJoinTableEntry($alias, $table, $join_on, $as_on, $join, $normalized);
			return;
		}
		// silently ignore malformed input
	}

	/**
	 * Parse flat entry like "table" with automatic aliasing
	 *
	 * @param   string  $table       The table name
	 * @param   array   &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function parseFlatTable(string $table, array &$normalized): void
	{
		$alias = chr(97 + count($normalized));
		$this->addTableEntry($alias, $table, $normalized);
	}

	/**
	 * Parse associative array entry, either a raw string or a join structure
	 *
	 * @param   string         $alias       Table alias
	 * @param   string|array   $value       The table definition or join array
	 * @param   array          &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function parseAssocSyntax(string $alias, $value, array &$normalized): void
	{
		if (is_array($value))
		{
			$this->addJoinTableEntry(
				$alias,
				$value['name'] ?? '',
				$value['join_on'] ?? '',
				$value['as_on'] ?? '',
				$value['join'] ?? null,
				$normalized
			);
		}
		else
		{
			$this->addTableEntry($alias, $value, $normalized);
		}
	}

	/**
	 * Add a given set of entries to the normalized array
	 *
	 * @param   string     $alias       Table alias
	 * @param   string     $table       Table name
	 * @param   array      &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function addTableEntry(string $alias, string $table, array &$normalized): void
	{
		$alias = trim($alias);
		$table = trim($table);

		if ($alias === 'a' && $table !== '')
		{
			if (isset($normalized[$alias]))
			{
				return;
			}

			$normalized[$alias] = $table;
			return;
		}

		$this->parseVariousSyntax($table, $normalized, $alias);
	}

	/**
	 * Add a given set of entries to the normalized array
	 *
	 * @param   string       $alias       Table alias
	 * @param   string       $table       Table name
	 * @param   string       $joinOn      The join on column name
	 * @param   string       $asOn        The as on column name
	 * @param   string|null  $join        The join type
	 * @param   array        &$normalized The normalized output reference
	 *
	 * @return  void
	 * @since   5.1.1
	 */
	private function addJoinTableEntry(
		string $alias,
		string $table,
		string $joinOn,
		string $asOn,
		?string $join,
		array &$normalized
	): void
	{
		$alias     = trim($alias);
		$tableName = trim($table);
		$joinOn    = trim($joinOn);
		$asOn      = trim($asOn);

		if (
			$alias === '' || $alias === 'a' ||
			$table === '' || $joinOn === '' || $asOn === ''
		) {
			return;
		}

		if (isset($normalized[$alias]))
		{
			return;
		}

		if ($join !== null)
		{
			$join = trim($join);
		}

		$normalized[$alias] = [
			'name'    => $table,
			'join_on' => $this->normalizeColumn('a', $joinOn),
			'as_on'   => $this->normalizeColumn($alias, $asOn),
			'join'   => $join
		];
	}

	/**
	 * Add table alias to column if not already present.
	 *
	 * @param   string  $alias   The table alias
	 * @param   string  $column  The column name
	 *
	 * @return  string
	 * @since   5.1.1
	 */
	private function normalizeColumn(string $alias, string $column): string
	{
		return (strpos($column, '.') !== false)
			? $column
			: "{$alias}.{$column}";
	}/***[/JCBGUI$$$$]***/

}

