<?php

/***[JCBGUI.power.licensing_template.745.$$$$]***/
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
 * Data Load Interface
 * 
 * @since 3.2.2
 */
interface LoadInterface
{

/***[JCBGUI.power.main_class_code.745.$$$$]***/
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
	 * Get a value from a given table
	 *          Example: $this->value(
	 *                        [
	 *                           'guid' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'
	 *                        ], 'value_key'
	 *                    );
	 *
	 * @param   array      $keys      The item keys
	 * @param   string     $field     The field key
	 *
	 * @return  mixed
	 * @since 2.0.1
	 */
	public function value(array $keys, string $field);

	/**
	 * Get a value from multiple rows from a given table
	 *          Example: $this->values(
	 *                        [
	 *                           'guid' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'
	 *                        ], 'value_key'
	 *                    );
	 *
	 * @param   array      $keys      The item keys
	 * @param   string     $field     The field key
	 *
	 * @return  array|null
	 * @since 3.2.2
	 */
	public function values(array $keys, string $field): ?array;

	/**
	 * Get values from a given table
	 *          Example: $this->item(
	 *                        [
	 *                           'guid' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'
	 *                        ]
	 *                    );
	 *
	 * @param   array    $keys      The item keys
	 *
	 * @return  object|null
	 * @since 2.0.1
	 */
	public function item(array $keys): ?object;
 
	/**
	 * Get values from a given table
	 *          Example: $this->items(
	 *                        [
	 *                           'guid' => [
	 *                              'operator' => 'IN',
	 *                              'value' => [''xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'', ''xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'']
	 *                           ]
	 *                        ]
	 *                    );
	 *          Example: $this->items($keys);
	 *
	 * @param   array    $keys    The item keys
	 *
	 * @return  array|null
	 * @since 2.0.1
	 */
	public function items(array $keys): ?array;

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string;/***[/JCBGUI$$$$]***/

}

