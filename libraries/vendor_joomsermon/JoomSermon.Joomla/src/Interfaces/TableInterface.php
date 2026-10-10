<?php

/***[JCBGUI.power.licensing_template.137.$$$$]***/
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
 * The VDM Core Table Interface
 */
interface TableInterface
{

/***[JCBGUI.power.main_class_code.137.$$$$]***/
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
	 * @since 3.2.0
	 */
	public function get(?string $table = null, ?string $field = null, ?string $key = null);

	/**
	 * Get title field from an area/view/table
	 *
	 * @param   string   $table  The area
	 *
	 * @return  ?array
	 * @since 3.2.0
	 */
	public function title(string $table): ?array;

	/**
	 * Get title field name
	 *
	 * @param   string   $table  The area
	 *
	 * @return  string
	 * @since 3.2.0
	 */
	public function titleName(string $table): string;

	/**
	 * Get all tables
	 *
	 * @return  array
	 * @since 3.2.0
	 */
	public function tables(): array;

	/**
	 * Check if a table (and field) exist
	 *
	 * @param   string       $table  The area
	 * @param   string|null  $field  The area
	 *
	 * @return  bool
	 * @since 3.2.0
	 */
	public function exist(string $table, ?string $field = null): bool;

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
	public function fields(string $table, bool $default = false, bool $details = false): ?array;/***[/JCBGUI$$$$]***/

}

