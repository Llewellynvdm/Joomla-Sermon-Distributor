<?php

/***[JCBGUI.power.licensing_template.743.$$$$]***/
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

namespace JoomSermon\Joomla\Interfaces\Data;


/**
 * Data Update
 * 
 * @since 3.2.2
 */
interface UpdateInterface
{

/***[JCBGUI.power.main_class_code.743.$$$$]***/
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
	public function updateids(bool $reset = true): array;

	/**
	 * Set the current active table
	 *
	 * @param string|null $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(?string $table): self;

	/**
	 * Update a value to a given table
	 *          Example: $this->value(Value, 'value_key', 'GUID');
	 *
	 * @param   mixed     $value      The field value
	 * @param   string    $field      The field key
	 * @param   string    $keyValue   The key value
	 * @param   string    $key        The key name
	 *
	 * @return  bool
	 * @since 3.2.0
	 */
	public function value($value, string $field, string $keyValue, string $key = 'guid'): bool;

	/**
	 * Update single row with multiple values to a given table
	 *          Example: $this->item(Array);
	 *
	 * @param   array    $item   The item to save
	 * @param   string   $key    The key name
	 *
	 * @return  bool
	 * @since 3.2.0
	 */
	public function row(array $item, string $key = 'guid'): bool;

	/**
	 * Update multiple rows to a given table
	 *          Example: $this->items(Array);
	 *
	 * @param   array|null   $items  The items updated in database (array of arrays)
	 * @param   string       $key    The key name
	 *
	 * @return  bool
	 * @since 3.2.0
	 */
	public function rows(?array $items, string $key = 'guid'): bool;

	/**
	 * Update single item with multiple values to a given table
	 *          Example: $this->item(Object);
	 *
	 * @param   object    $item   The item to save
	 * @param   string    $key    The key name
	 *
	 * @return  bool
	 * @since 3.2.0
	 */
	public function item(object $item, string $key = 'guid'): bool;

	/**
	 * Update multiple items to a given table
	 *          Example: $this->items(Array);
	 *
	 * @param   array|null   $items  The items updated in database (array of objects)
	 * @param   string       $key    The key name
	 *
	 * @return  bool
	 * @since 3.2.0
	 */
	public function items(?array $items, string $key = 'guid'): bool;

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string;/***[/JCBGUI$$$$]***/

}

