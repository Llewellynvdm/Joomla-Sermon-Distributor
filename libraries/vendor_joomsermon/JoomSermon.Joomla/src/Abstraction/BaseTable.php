<?php

/***[JCBGUI.power.licensing_template.435.$$$$]***/
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


use JoomSermon\Joomla\Interfaces\TableInterface;


/**
 * Base Table
 * 
 * @since 3.2.0
 */
abstract class BaseTable implements TableInterface
{

/***[JCBGUI.power.main_class_code.435.$$$$]***/
	/**
	 * All areas/views/tables with their field details
	 *
	 * @var     array
	 * @since 3.2.0
	 **/
	protected array $tables;

	/**
	 * All default fields
	 *
	 * @var     array
	 * @since 3.2.1
	 **/
	protected array $defaults = [
		'id' => [
			'order' => -1,
			'name' => 'id',
			'label' => 'ID',
			'type' => 'text',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT(11)',
				'default' => 'EMPTY',
				'auto_increment' => true,
				'primary_key' => true,
				'null_switch' => 'NOT NULL'
			]
		],
		'asset_id' => [
			'name' => 'asset_id',
			'label' => NULL,
			'type' => NULL,
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT(10) unsigned',
				'default' => '0',
				'null_switch' => 'NULL',
				'comment' => 'FK to the #__assets table.'
			]
		],
		'ordering' => [
			'name' => 'ordering',
			'label' => 'Ordering',
			'type' => 'number',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT(11)',
				'default' => '0',
				'null_switch' => 'NULL'
			]
		],
		'published' => [
			'name' => 'published',
			'label' => 'Status',
			'type' => 'list',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'TINYINT(3)',
				'default' => '1',
				'null_switch' => 'NULL',
				'key' => true,
				'key_name' => 'state'
			]
		],
		'modified_by' => [
			'name' => 'modified_by',
			'label' => 'Modified by',
			'type' => 'user',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT unsigned',
				'null_switch' => 'NULL',
				'key' => true,
				'key_name' => 'modifiedby'
			]
		],
		'modified' => [
			'name' => 'modified',
			'label' => 'Modified',
			'type' => 'calendar',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'DATETIME',
				'null_switch' => 'NULL'
			]
		],
		'created_by' => [
			'name' => 'created_by',
			'label' => 'Created by',
			'type' => 'user',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT unsigned',
				'default' => '0',
				'null_switch' => 'NULL',
				'key' => true,
				'key_name' => 'createdby'
			]
		],
		'created' => [
			'name' => 'created',
			'label' => 'Created',
			'type' => 'calendar',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'DATETIME',
				'default' => 'CURRENT_TIMESTAMP',
				'null_switch' => 'NULL'
			]
		],
		'checked_out' => [
			'name' => 'checked_out',
			'label' => NULL,
			'type' => NULL,
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT unsigned',
				'null_switch' => 'NULL',
				'key' => true,
				'key_name' => 'checkout'
			]
		],
		'checked_out_time' => [
			'name' => 'checked_out_time',
			'label' => NULL,
			'type' => NULL,
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'DATETIME',
				'null_switch' => 'NULL'
			]
		],
		'hits' => [
			'name' => 'hits',
			'label' => 'Hits',
			'type' => 'number',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT(10) unsigned',
				'default' => '0',
				'null_switch' => 'NULL'
			]
		],
		'version' => [
			'name' => 'version',
			'label' => 'Version',
			'type' => 'text',
			'title' => false,
			'list' => NULL,
			'store' => NULL,
			'tab_name' => NULL,
			'db' => [
				'type' => 'INT(10) unsigned',
				'default' => '1',
				'null_switch' => 'NULL'
			]
		],
		'params' => [
			'name' => 'params',
			'label' => NULL,
			'type' => NULL,
			'title' => false,
			'list' => NULL,
			'store' => 'json',
			'tab_name' => NULL,
			'db' => [
				'type' => 'TEXT',
				'default' => 'EMPTY',
				'null_switch' => 'NULL'
			]
		]
	];

	/**
	 * Get any value from a item/field/column of an area/view/table
	 *          Example: $this->get('table_name', 'field_name', 'value_key');
	 * Get an item/field/column of an area/view/table
	 *          Example: $this->get('table_name', 'field_name');
	 * Get all items/fields/columns of an area/view/table
	 *          Example: $this->get('table_name');
	 * Get all areas/views/tables with all their item/field/column details
	 *          Example: $this->get('All');
	 *          Example: $this->get();
	 *
	 * @param   string|null  $table  The table
	 * @param   string|null  $field  The field
	 * @param   string|null  $key    The value key
	 *
	 * @return  mixed
	 * @since 3.2.1
	 */
	public function get(?string $table = null, ?string $field = null, ?string $key = null)
	{
		// Return specific value
		if ($table && $field && $key)
		{
			return $this->tables[$table][$field][$key] ?? $this->getDefaultKey($field, $key);
		}

		// Return field within table
		if ($table && $field)
		{
			return $this->tables[$table][$field] ?? $this->getDefault($field);
		}

		// Return all fields in a table or all tables if 'All' is passed
		if ($table)
		{
			if (strtoupper($table) === 'ALL')
			{
				return $this->tables;
			}

			return $this->tables[$table] ?? null;
		}

		// Return all tables
		return $this->tables;
	}

	/**
	 * Get title field from an area/view/table
	 *
	 * @param   string   $table  The area
	 *
	 * @return  ?array
	 * @since 3.2.0
	 */
	public function title(string $table): ?array
	{
		// return the title item/field/column of an area/view/table 
		if (($table = $this->get($table)) !== null)
		{
			foreach ($table as $item)
			{
				if ($item['title'])
				{
					return $item;
				}
			}
		}

		// none found
		return null;
	}

	/**
	 * Get title field name
	 *
	 * @param   string   $table  The area
	 *
	 * @return  string
	 * @since 3.2.0
	 */
	public function titleName(string $table): string
	{
		// return the title name of an area/view/table
		if (($field = $this->title($table)) !== null)
		{
			return $field['name'];
		}

		// none found default to ID
		return 'id';
	}

	/**
	 * Get all tables
	 *
	 * @return  array
	 * @since 3.2.0
	 */
	public function tables(): array
	{
		// return all areas/views/tables
		return array_keys($this->tables);
	}

	/**
	 * Check if a table (and field) exist
	 *
	 * @param   string       $table  The area
	 * @param   string|null  $field  The area
	 *
	 * @return  bool
	 * @since 3.2.0
	 */
	public function exist(string $table, ?string $field = null): bool
	{
		if (isset($this->tables[$table]))
		{
			// if we have a field
			if (is_string($field))
			{
				if (isset($this->tables[$table][$field]))
				{
					return true;
				}
			}
			else
			{
				return true;
			}
		}

		return $this->isDefault($field);
	}

	/**
	 * Get all fields of an area/view/table
	 *
	 * @param   string  $table     The area
	 * @param   bool    $default   Add the default fields
	 * @param   bool    $details   Add/Leave fields the details
	 *
	 * @return  array|null   On success an array of fields
	 * @since 3.2.0
	 */
	public function fields(string $table, bool $default = false, bool $details = false): ?array
	{
		// Retrieve fields from the specified table
		$fields = $this->get($table);

		if ($fields === null)
		{
			return null;
		}

		// Determine the fields output based on the $default and $details flags
		if ($details)
		{
			return $default ? $this->addDefaultDetails($fields) : $fields;
		}

		$fieldKeys = array_keys($fields);

		return $default ? $this->addDefault($fieldKeys) : $fieldKeys;
	}

	/**
	 * Add the default fields
	 *
	 * @param   array  $fields   The table dynamic fields
	 *
	 * @return  array   Fields (with defaults added)
	 * @since 3.2.0
	 */
	protected function addDefault(array $fields): array
	{
		// add default fields
		foreach ($this->defaults as $default)
		{
			if (in_array($default['name'], $fields))
			{
				continue;
			}

			// used just for loading the fields
			$order = $default['order'] ?? 1;
			unset($default['order']);

			if ($order < 0)
			{
				array_unshift($fields, $default['name']);
			}
			else
			{
				$fields[] = $default['name'];
			}
		}

		return $fields;
	}

	/**
	 * Add the default fields
	 *
	 * @param   array  $fields   The table dynamic fields
	 *
	 * @return  array   Fields (with defaults details added)
	 * @since 3.2.0
	 */
	protected function addDefaultDetails(array $fields): array
	{
		// add default fields
		foreach ($this->defaults as $default)
		{
			// remove ordering for now
			unset($default['order']);

			if (!isset($fields[$default['name']]))
			{
				$fields[$default['name']] = $default;
			}
		}

		return $fields;
	}

	/**
	 * Check if the field is a default field
	 *
	 * @param   string  $field  The field to check
	 *
	 * @return  bool   True if a default field
	 * @since 3.2.0
	 */
	protected function isDefault(string $field): bool
	{
		return isset($this->defaults[$field]);
	}

	/**
	 * Get a default field
	 *
	 * @param   string  $field  The field to check
	 *
	 * @return  array|null   True if a default field
	 * @since 3.2.0
	 */
	protected function getDefault(string $field): ?array
	{
		return $this->defaults[$field] ?? null;
	}

	/**
	 * Get a default field property
	 *
	 * @param   string  $field   The field to check
	 * @param   string  $key     The field key/property to check
	 *
	 * @return  mixed   String value if a default field property exist
	 * @since 3.2.0
	 */
	protected function getDefaultKey(string $field, string $key)
	{
		return $this->defaults[$field][$key] ?? null;
	}/***[/JCBGUI$$$$]***/

}

