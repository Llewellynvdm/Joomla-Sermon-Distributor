<?php

/***[JCBGUI.power.licensing_template.103.$$$$]***/
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

namespace JoomSermon\Joomla\Interfaces;


/**
 * Model Interface
 * 
 * @since 3.2.0
 */
interface ModelInterface
{

/***[JCBGUI.power.main_class_code.103.$$$$]***/
	/**
	 * Set the current active table
	 *
	 * @param string  $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(string $table): self;

	/**
	 * Model the value
	 *          Example: $this->value(value, 'value_key', 'table_name');
	 *
	 * @param   mixed          $value    The value to model
	 * @param   string         $field    The field key
	 * @param   string|null    $table    The table
	 *
	 * @return  mixed
	 * @since 3.2.0
	 */
	public function value($value, string $field, ?string $table = null);

	/**
	 * Model a value of multiple items
	 *          Example: $this->items(Array, 'value_key', 'table_name');
	 *
	 * @param   array|null    $items    The array of values
	 * @param   string        $field    The field key
	 * @param   string|null   $table    The table
	 *
	 * @return  array|null
	 * @since 3.2.0
	 */
	public function values(?array $items, string $field, ?string $table = null): ?array;

	/**
	 * Model the values of an item
	 *          Example: $this->item(Object, 'table_name');
	 *
	 * @param   object|null    $item      The item object
	 * @param   string|null    $table     The table
	 *
	 * @return  object|null
	 * @since 3.2.0
	 */
	public function item(?object $item, ?string $table = null): ?object;

	/**
	 * Model the values of multiple items
	 *          Example: $this->items(Array, 'table_name');
	 *
	 * @param   array|null    $items    The array of item objects
	 * @param   string|null    $table     The table
	 *
	 * @return  array|null
	 * @since 3.2.0
	 */
	public function items(?array $items = null, ?string $table = null): ?array;

	/**
	 * Model the values of an row
	 *          Example: $this->item(Array, 'table_name');
	 *
	 * @param   array|null     $item      The item array
	 * @param   string|null    $table     The table
	 *
	 * @return  array|null
	 * @since 3.2.0
	 */
	public function row(?array $item, ?string $table = null): ?array;

	/**
	 * Model the values of multiple rows
	 *          Example: $this->items(Array, 'table_name');
	 *
	 * @param   array|null     $items    The array of item array
	 * @param   string|null    $table    The table
	 *
	 * @return  array|null
	 * @since 3.2.0
	 */
	public function rows(?array $items = null, ?string $table = null): ?array;

	/**
	 * Get last modeled ID
	 *          Example: $this->last('table_name');
	 *
	 * @param   string|null     $table     The table
	 *
	 * @return  int|null
	 * @since 3.2.0
	 */
	public function last(?string $table = null): ?int;

	/**
	 * Set the current active table
	 *
	 * @param string   $tableName  The table name
	 *
	 * @return  void
	 * @since 3.2.2
	 */
	public function setTable(string $tableName): void;

	/**
	 * Set the switch to control the behaviour of empty values
	 *
	 * @param bool   $allowEmpty  The switch
	 *
	 * @return  void
	 * @since 3.2.2
	 */
	public function setAllowEmpty(bool $allowEmpty): void;/***[/JCBGUI$$$$]***/

}

