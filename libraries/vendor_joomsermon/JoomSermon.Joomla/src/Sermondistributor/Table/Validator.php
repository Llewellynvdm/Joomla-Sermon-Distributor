<?php

/***[JCBGUI.power.licensing_template.847.$$$$]***/
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

namespace JoomSermon\Joomla\Sermondistributor\Table;


use JoomSermon\Joomla\Interfaces\TableInterface as Table;
use JoomSermon\Joomla\Interfaces\TableValidatorInterface;


/**
 * Table Value Validator
 * 
 * @since 5.3.0
 */
final class Validator implements TableValidatorInterface
{

/***[JCBGUI.power.main_class_code.847.$$$$]***/
	/**
	 * The Table Class.
	 *
	 * @var   Table
	 * @since 5.3.0
	 */
	protected Table $table;

	/**
	 *  A map of MySQL base types to their respective validation methods.
	 *
	 * @var   array
	 * @since 5.3.0
	 */
	protected array $validators = [];

	/**
	 *  A map of defaults for the respective datatypes.
	 *
	 * @var   array
	 * @since 5.3.0
	 */
	protected array $defaults = [];

	/**
	 *  Cache of the parsed datatype details
	 *
	 * @var   array
	 * @since 5.3.0
	 */
	protected array $datatypes = [];

	/**
	 * Constructor.
	 *
	 * @param Table   $table   The Table Class.
	 *
	 * @since 5.3.0
	 */
	public function __construct(Table $table)
	{
		$this->table = $table;

		// Register datatype validators (mapping MySQL types to handlers)
		$this->registerValidators();

		// Register datatype defaults
		$this->registerDefaults();
	}

	/**
	 * Returns the valid value based on datatype definition.
	 * If the value is valid, return it. If not, return the default value,
	 * NULL (if allowed), or an empty string if 'EMPTY' is set.
	 *
	 * @param mixed  $value  The value to validate.
	 * @param string $field  The field name.
	 * @param string $table  The table name.
	 *
	 * @return mixed Returns the valid value, or the default, NULL, or empty string based on validation.
	 * @since 5.3.0
	 */
	public function getValid($value, string $field, string $table)
	{
		// Get the database field definition
		if (($dbField = $this->getDatabaseField($field, $table)) === null)
		{
			return null; // not legal field or table
		}

		// Check if the value is valid for the field
		if ($this->validate($value, $dbField))
		{
			return $value;
		}

		// If invalid, return default, NULL (if allowed), or empty string
		return $this->getDefault($dbField, $value);
	}

	/**
	 * Validate if the given value is valid for the provided database field.
	 * This is a private method as `getValid()` will handle the actual logic.
	 *
	 * @param mixed  $value	The value to validate.
	 * @param array  $dbField  The database field details (type, default, null_switch, etc.).
	 *
	 * @return bool Returns true if the value is valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validate($value, array $dbField): bool
	{
		// Extract datatype and handle the validation
		$typeInfo = $this->parseDataType($dbField['type']);
		$baseType = $typeInfo['type'];
		
		// Use the appropriate validator if it exists
		if (isset($this->validators[$baseType]))
		{
			return call_user_func($this->validators[$baseType], $value, $typeInfo);
		}

		// If no validator exists, assume invalid
		return false;
	}

	/**
	 * Handle returning the default value, null, or empty string if validation fails.
	 *
	 * @param array  $dbField  The database field details.
	 * @param mixed  $value	The value to validate.
	 *
	 * @return mixed The default value, null, or empty string based on field settings.
	 * @since 5.3.0
	 */
	private function getDefault(array $dbField, $value)
	{
		// get default value from field db
		$db_default = isset($dbField['default']) ? $dbField['default'] : null;

		// If a default value is provided, return it
		if ($db_default !== null)
		{
			return strtoupper($db_default) === 'EMPTY' ? '' : $db_default;
		}

		// Check if NULL is allowed
		if (isset($dbField['null_switch']) && strtoupper($dbField['null_switch']) === 'NULL')
		{
			return null;
		}

		// Fallback to datatype default
		$typeInfo = $this->parseDataType($dbField['type']);
		return $this->defaults[$typeInfo['type']] ?? '';
	}

	/**
	 * Parse the data type from the database field and extract details like type, size, and precision.
	 *
	 * @param string $datatype The full MySQL datatype (e.g., VARCHAR(255)).
	 *
	 * @return array An array containing 'type', 'size', and other relevant info.
	 * @since 5.3.0
	 */
	private function parseDataType(string $datatype): array
	{
		if (isset($this->datatypes[$datatype]))
		{
			return $this->datatypes[$datatype];
		}

		$pattern = '/(?<type>\w+)(\((?<size>\d+)(,\s*(?<precision>\d+))?\))?/i';
		preg_match($pattern, $datatype, $matches);
		
		$result = [
			'type' => isset($matches['type']) ? strtolower($matches['type']) : strtolower($datatype),
			'size' => $matches['size'] ?? null,
			'precision' => $matches['precision'] ?? null,
		];

		return $this->datatypes[$datatype] = $result;
	}

	/**
	 * Retrieve the database field structure for the specified field and table.
	 *
	 * @param string $field  The field name.
	 * @param string $table  The table name.
	 *
	 * @return array The database field details, including type, default, null_switch, etc.
	 * @since 5.3.0
	 */
	private function getDatabaseField(string $field, string $table): array
	{
		// Retrieval of field db details.
		return $this->table->get($table, $field, 'db');
	}

	/**
	 * Register validators for MySQL data types.
	 *
	 * @return void
	 * @since 5.3.0
	 */
	private function registerValidators(): void
	{
		$this->validators = [
			'int' => [$this, 'validateInteger'],
			'tinyint' => [$this, 'validateInteger'],
			'smallint' => [$this, 'validateInteger'],
			'mediumint' => [$this, 'validateInteger'],
			'bigint' => [$this, 'validateInteger'],
			'varchar' => [$this, 'validateString'],
			'char' => [$this, 'validateString'],
			'text' => [$this, 'validateText'],
			'tinytext' => [$this, 'validateText'],
			'mediumtext' => [$this, 'validateText'],
			'longtext' => [$this, 'validateText'],
			'decimal' => [$this, 'validateDecimal'],
			'float' => [$this, 'validateFloat'],
			'double' => [$this, 'validateFloat'],
			'date' => [$this, 'validateDate'],
			'datetime' => [$this, 'validateDate'],
			'timestamp' => [$this, 'validateDate'],
			'time' => [$this, 'validateDate'],
			'json' => [$this, 'validateJson'],
			'blob' => [$this, 'validateBlob'],
			'tinyblob' => [$this, 'validateBlob'],
			'mediumblob' => [$this, 'validateBlob'],
			'longblob' => [$this, 'validateBlob'],
		];
	}

	/**
	 * Register default values for MySQL data types.
	 *
	 * @return void
	 * @since 5.3.0
	 */
	private function registerDefaults(): void
	{
		$this->defaults = [
			'int' => 0,
			'tinyint' => 0,
			'smallint' => 0,
			'mediumint' => 0,
			'bigint' => 0,
			'varchar' => '',
			'char' => '',
			'text' => '',
			'tinytext' => '',
			'mediumtext' => '',
			'longtext' => '',
			'decimal' => 0.0,
			'float' => 0.0,
			'double' => 0.0,
			'date' => '0000-00-00',
			'datetime' => '0000-00-00 00:00:00',
			'timestamp' => '0000-00-00 00:00:00',
			'time' => '00:00:00',
			'json' => '{}',
			'blob' => '',
			'tinyblob' => '',
			'mediumblob' => '',
			'longblob' => '',
		];
	}

	// ----------------- Validation Methods -----------------

	/**
	 * Validate integer types (including tinyint, smallint, mediumint, etc.).
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateInteger($value, array $typeInfo): bool
	{
		if (!is_numeric($value))
		{
			return false;
		}

		$value = (int)$value;
		if (isset($typeInfo['unsigned']) && $typeInfo['unsigned'] && $value < 0)
		{
			return false;
		}

		return true;
	}

	/**
	 * Validate string types like VARCHAR and CHAR.
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateString($value, array $typeInfo): bool
	{
		if (!is_string($value))
		{
			return false;
		}

		// Check if the length exceeds the allowed size
		if ($typeInfo['size'] !== null && strlen($value) > (int)$typeInfo['size'])
		{
			return false;
		}

		return true;
	}

	/**
	 * Validate text types like TEXT, TINYTEXT, MEDIUMTEXT, LONGTEXT.
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateText($value, array $typeInfo): bool
	{
		return is_string($value);
	}

	/**
	 * Validate float, double, and decimal types.
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateFloat($value, array $typeInfo): bool
	{
		return is_numeric($value);
	}

	/**
	 * Validate decimal types (numeric precision and scale).
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateDecimal($value, array $typeInfo): bool
	{
		return is_numeric($value);
	}

	/**
	 * Validate date, datetime, timestamp, and time types.
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateDate($value, array $typeInfo): bool
	{
		$formats = [
			'date' => 'Y-m-d',
			'datetime' => 'Y-m-d H:i:s',
			'timestamp' => 'Y-m-d H:i:s',
			'time' => 'H:i:s',
		];

		if (!isset($formats[$typeInfo['type']]))
		{
			return false;
		}

		$dateTime = \DateTime::createFromFormat($formats[$typeInfo['type']], $value);
		return $dateTime && $dateTime->format($formats[$typeInfo['type']]) === $value;
	}

	/**
	 * Validate JSON types.
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateJson($value, array $typeInfo): bool
	{
		json_decode($value);
		return json_last_error() === JSON_ERROR_NONE;
	}

	/**
	 * Validate BLOB types (including TINYBLOB, MEDIUMBLOB, LONGBLOB).
	 *
	 * @param mixed $value	The value to validate.
	 * @param array $typeInfo The parsed data type information.
	 *
	 * @return bool True if valid, false otherwise.
	 * @since 5.3.0
	 */
	private function validateBlob($value, array $typeInfo): bool
	{
		return is_string($value) || is_resource($value);
	}/***[/JCBGUI$$$$]***/

}

