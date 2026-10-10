<?php

/***[JCBGUI.power.licensing_template.728.$$$$]***/
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
 * Data Item Interface
 * 
 * @since 3.2.2
 */
interface ItemInterface
{

/***[JCBGUI.power.main_class_code.728.$$$$]***/
	/**
	 * Get the first ID of the most recent action.
	 *
	 * This method returns the first resolved entity ID from the most recent
	 * INSERT or UPDATE action. If no IDs are available or the active action
	 * is not supported, 0 is returned.
	 *
	 * Behavioral notes:
	 * - Only INSERT and UPDATE actions are supported.
	 * - The internal ID bucket of the active action is reset after retrieval.
	 * - The returned ID represents the first affected entity in the batch.
	 *
	 * @return  int  The entity ID, or 0 if unavailable.
	 *
	 * @since   5.1.4
	 */
	public function id(): int;

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
	 * Get an item
	 *
	 * @param string       $value   The item key value
	 * @param string       $key     The item key
	 *
	 * @return object|null The item object or null
	 * @since 3.2.2
	 */
	public function get(string $value, string $key = 'guid'): ?object;

	/**
	 * Get the value
	 *
	 * @param string   $value   The item key value
	 * @param string   $key     The item key
	 * @param string   $get     The key of the values we want back
	 *
	 * @return mixed
	 * @since 3.2.2
	 */
	public function value(string $value, string $key = 'guid', string $get = 'id');

	/**
	 * Set an item
	 *
	 * @param object       $item    The item
	 * @param string       $key     The item key
	 * @param string|null  $action  The action to load power
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function set(object $item, string $key = 'guid', ?string $action = null): bool;

	/**
	 * Delete an item
	 *
	 * @param string    $value   The item key value
	 * @param string    $key     The item key
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function delete(string $value, string $key = 'guid'): bool;

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string;/***[/JCBGUI$$$$]***/

}

