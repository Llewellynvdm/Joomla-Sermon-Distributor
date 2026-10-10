<?php

/***[JCBGUI.power.licensing_template.754.$$$$]***/
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
 * Data Items Interface
 * 
 * @since 3.2.2
 */
interface ItemsInterface
{

/***[JCBGUI.power.main_class_code.754.$$$$]***/
	/**
	 * Get the IDs affected by the most recent actions batch.
	 *
	 * This method returns the complete set of entity IDs affected by the most
	 * recent persistence operations, regardless of whether the underlying
	 * action was an INSERT, UPDATE, or a mixture of both.
	 *
	 * Behavioral notes:
	 * - IDs from INSERT and UPDATE operations are merged into a single set.
	 * - The internal ID buckets for both operations are reset immediately
	 *   after retrieval to prevent cross-contamination between batches.
	 * - Duplicate IDs are removed while preserving their original order.
	 * - The returned IDs represent *all* entities affected during the
	 *   most recent execution cycle.
	 *
	 * @return  array<int|string>  The affected entity IDs.
	 *
	 * @since   5.1.4
	 */
	public function ids(): array;

	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(string $table): self;

	/**
	 * Get list of items
	 *
	 * @param array     $values    The ids of the items
	 * @param string    $key       The key of the values
	 *
	 * @return array|null The item object or null
	 * @since 3.2.2
	 */
	public function get(array $values, string $key = 'guid'): ?array;

	/**
	 * Get the values
	 *
	 * @param array   $values    The list of values (to search by).
	 * @param string  $key       The key on which the values being searched.
	 * @param string  $get       The key of the values we want back
	 *
	 * @return array|null The array of found values.
	 * @since 3.2.2
	 */
	public function values(array $values, string $key = 'guid', string $get = 'id'): ?array;

	/**
	 * Set items
	 *
	 * @param array     $items  The list of items
	 * @param string    $key    The key on which the items should be set
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function set(array $items, string $key = 'guid'): bool;

	/**
	 * Delete items
	 *
	 * @param array    $values  The item key value
	 * @param string   $key     The item key
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function delete(array $values, string $key = 'guid'): bool;

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string;/***[/JCBGUI$$$$]***/

}

