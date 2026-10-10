<?php

/***[JCBGUI.power.licensing_template.742.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    3rd September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Data;


use JoomSermon\Joomla\Interfaces\Data\ItemsInterface as Items;
use JoomSermon\Joomla\Data\Guid;
use JoomSermon\Joomla\Interfaces\Data\GuidInterface;
use JoomSermon\Joomla\Interfaces\Data\SubformInterface;


/**
 * CRUD the data of any sub-form to another view (table)
 * 
 * @since  3.2.2
 */
final class Subform implements GuidInterface, SubformInterface
{

/***[JCBGUI.power.main_class_code.742.$$$$]***/
	/**
	 * The Globally Unique Identifier.
	 *
	 * @since 5.0.2
	 */
	use Guid;

	/**
	 * The Items Class.
	 *
	 * @var   Items
	 * @since 3.2.2
	 */
	protected Items $items;

	/**
	 * Table Name
	 *
	 * @var    string
	 * @since 3.2.1
	 */
	protected string $table;

	/**
	 * Constructor.
	 *
	 * @param Items       $items   The Items Class.
	 * @param string|null $table   The table name.
	 *
	 * @since 3.2.2
	 */
	public function __construct(Items $items, ?string $table = null)
	{
		$this->items = $items;
		if ($table !== null)
		{
			$this->table = $table;
		}
	}

	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(string $table): self
	{
		$this->table = $table;

		return $this;
	}

	/**
	 * Get a subform items
	 *
	 * @param string   $linkValue  The value of the link key in child table.
	 * @param string   $linkKey    The link key on which the items where linked in the child table.
	 * @param string   $field      The parent field name of the subform in the parent view.
	 * @param array    $get        The array SET of the keys of each row in the subform.
	 * @param bool     $multi      The switch to return a multiple set.
	 *
	 * @return array|null   The subform
	 * @since 3.2.2
	 */
	public function get(string $linkValue, string $linkKey, string $field, array $get, bool $multi = true): ?array
	{
		if (($items = $this->items->table($this->getTable())->get([$linkValue], $linkKey)) !== null)
		{
			return $this->converter($items, $get, $field, $multi);
		}
		return null;
	}

	/**
	 * Set a subform items
	 *
	 * @param mixed    $items      The list of items from the subform to set
	 * @param string   $indexKey   The index key on which the items should be observed as it relates to insert/update/delete.
	 * @param string   $linkKey    The link key on which the items where linked in the child table.
	 * @param string   $linkValue  The value of the link key in child table.
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function set(mixed $items, string $indexKey, string $linkKey, string $linkValue): bool
	{
		$items = $this->process($items, $indexKey, $linkKey, $linkValue);

		$this->purge($items, $indexKey, $linkKey, $linkValue);

		if (empty($items))
		{
			return true; // nothing to set (already purged)
		}

		return $this->items->table($this->getTable())->set(
			$items, $indexKey
		);
	}

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string
	{
		return $this->table;
	}

	/**
	 * Purge all items no longer in subform
	 *
	 * @param array    $items      The list of items to set.
	 * @param string   $indexKey   The index key on which the items should be observed as it relates to insert/update/delete
	 * @param string   $linkKey    The link key on which the items where linked in the child table.
	 * @param string   $linkValue  The value of the link key in child table.
	 *
	 * @return void
	 * @since 3.2.2
	 */
	private function purge(array $items, string $indexKey, string $linkKey, string $linkValue): void
	{
		// Get the current index values from the database
		$currentIndexValues = $this->items->table($this->getTable())->values([$linkValue], $linkKey, $indexKey);

		if ($currentIndexValues !== null)
		{
			// Check if the items array is empty
			if (empty($items))
			{
				// Set activeIndexValues to an empty array if items is empty
				$activeIndexValues = [];
			}
			else
			{
				// Extract the index values from the items array
				$activeIndexValues = array_values(array_map(function($item) use ($indexKey) {
					return $item[$indexKey] ?? null;
				}, $items));
			}

			// Find the index values that are no longer in the items array
			$inactiveIndexValues = array_diff($currentIndexValues, $activeIndexValues);

			// Delete the inactive index values
			if (!empty($inactiveIndexValues))
			{
				$this->items->table($this->getTable())->delete($inactiveIndexValues, $indexKey);
			}
		}
	}

	/**
	 * Filters the specified keys from an array of objects or arrays, converts them to arrays,
	 * and sets them by association with a specified key and an incrementing integer.
	 *
	 * @param array  $items  Array of objects or arrays to be filtered.
	 * @param array  $keySet Array of keys to retain in each item.
	 * @param string $field  The field prefix for the resulting associative array.
	 * @param bool   $multi  The switch to return a multiple set.
	 *
	 * @return array Array of filtered arrays set by association.
	 * @since 3.2.2
	 */
	private function converter(array $items, array $keySet, string $field, bool $multi): array
	{
		/**
		 * Filters keys for a single item and converts it to an array.
		 *
		 * @param object|array $item   The item to filter.
		 * @param array        $keySet The keys to retain.
		 *
		 * @return array The filtered array.
		 * @since 3.2.2
		 */
		$filterKeys = function ($item, array $keySet) {
			$filteredArray = [];
			foreach ($keySet as $key) {
				if (is_object($item) && property_exists($item, $key)) {
					$filteredArray[$key] = $item->{$key};
				} elseif (is_array($item) && array_key_exists($key, $item)) {
					$filteredArray[$key] = $item[$key];
				}
			}
			return $filteredArray;
		};

		$result = [];
		foreach ($items as $index => $item)
		{
			if (!$multi)
			{
				return $filterKeys($item, $keySet);
			}
			$filteredArray = $filterKeys($item, $keySet);
			$result[$field . $index] = $filteredArray;
		}

		return $result;
	}

	/**
	 * Processes an array of arrays based on the specified key.
	 *
	 * @param mixed    $items      Array of arrays to be processed.
	 * @param string   $indexKey   The index key on which the items should be observed as it relates to insert/update/delete
	 * @param string   $linkKey    The link key on which the items where linked in the child table.
	 * @param string   $linkValue  The value of the link key in child table.
	 *
	 * @return array  The processed array of arrays.
	 * @since 3.2.2
	 */
	private function process($items, string $indexKey, string $linkKey, string $linkValue): array
	{
		$items = is_array($items) ? $items : [];
		if ($items !== [] && !$this->isMultipleSets($items))
		{
			$items = [$items];
		}

		foreach ($items as &$item)
		{
			$value = $item[$indexKey] ?? '';
			switch ($indexKey) {
				case 'guid':
					if (empty($value))
					{
						// set INDEX
						$item[$indexKey] = $this->getGuid($indexKey);
					}
					break;
				case 'id':
					if (empty($value))
					{
						$item[$indexKey] = 0;
					}
					break;
				default:
					// No action for other keys if empty
					break;
			}
			// set LINK
			$item[$linkKey] = $linkValue;
		}

		return array_values($items);
	}

	/**
	 * Function to determine if the array consists of multiple data sets (arrays of arrays).
	 * 
	 * @param array $array The input array to be checked.
	 * 
	 * @return bool True if the array contains only arrays (multiple data sets), false otherwise.
	 * @since  5.0.2
	 */
	private function isMultipleSets(array $array): bool
	{
		foreach ($array as $element)
		{
			// As soon as we find a non-array element, return false
			if (!is_array($element))
			{
				return false;
			}
		}

		// If all elements are arrays, return true
		return true;
	}/***[/JCBGUI$$$$]***/

}

