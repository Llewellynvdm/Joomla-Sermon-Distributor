<?php

/***[JCBGUI.power.licensing_template.822.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Data;



/***[JCBGUI.power.head.822.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\CMS\User\User;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\Data\ItemsInterface as Items;
use JoomSermon\Joomla\Data\Guid;
use JoomSermon\Joomla\Sermondistributor\Utilities\UserHelper;
use JoomSermon\Joomla\Sermondistributor\Utilities\Exception\NoUserIdFoundException;
use JoomSermon\Joomla\Utilities\Component\Helper as Component;
use JoomSermon\Joomla\Interfaces\Data\GuidInterface;
use JoomSermon\Joomla\Interfaces\Data\SubformInterface;


/**
 * CRUD the user data of any sub-form to another view (table)
 * 
 * @since  5.0.2
 */
final class UsersSubform implements GuidInterface, SubformInterface
{

/***[JCBGUI.power.main_class_code.822.$$$$]***/
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
	 * The user properties
	 *
	 * @var    array
	 * @since 5.0.2
	 */
	protected array $user;

	/**
	 * The current active user
	 *
	 * @var    User
	 * @since 5.0.2
	 */
	protected User $identity;

	/**
	 * The active users
	 *
	 * @var    array
	 * @since 5.0.2
	 */
	protected array $activeUsers = [];

	/**
	 * Constructor.
	 *
	 * @param Items       $items   The items Class.
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

		$this->identity = Factory::getApplication()->getIdentity();

		// Retrieve the user properties
		$this->initializeUserProperties();
	}

	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return self
	 * @since  3.2.2
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
	 * @param array    $get        The array get:set of the keys of each row in the subform.
	 * @param bool     $multi      The switch to return a multiple set.
	 *
	 * @return array|null   The subform
	 * @since  3.2.2
	 */
	public function get(string $linkValue, string $linkKey, string $field, array $get, bool $multi = true): ?array
	{
		if (($items = $this->items->table($this->getTable())->get([$linkValue], $linkKey)) !== null)
		{
			return $this->converter(
				$this->getUsersDetails($items),
				$get,
				$field,
				$multi
			);
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
	 * @since  3.2.2
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
	 * @since   3.2.2
	 */
	public function getTable(): string
	{
		return $this->table;
	}

	/**
	 * Initializes the user properties.
	 *
	 * @return void
	 * @since  5.0.2
	 */
	private function initializeUserProperties(): void
	{
		$user = UserHelper::getUserById(0);

		// Populate user properties array excluding the 'id'
		foreach (get_object_vars($user) as $property => $value)
		{
			if ($property !== 'id')
			{
				$this->user[$property] = $property;
			}
		}
		$this->user['password2'] = 'password2';
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
	 * @since  3.2.2
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

				// $this->deleteUsers($inactiveIndexValues); (soon)
			}
		}
	}

	/**
	 * Get the users details found in the user table.
	 *
	 * @param array  $items  Array of objects or arrays to be filtered.
	 *
	 * @return array
	 * @since  5.0.2
	 */
	private function getUsersDetails(array $items): array
	{
		foreach ($items as $index => &$item)
		{
			$item = (array) $item;
			$this->getUserDetails($item);
		}

		return $items;
	}

	/**
	 * Get the user details found in the user table.
	 *
	 * @param array  $item  The user map array
	 *
	 * @return void
	 * @since  5.0.2
	 */
	private function getUserDetails(array &$item): void
	{
		// Validate the user_id and ensure it is numeric and greater than 0
		if (empty($item['user_id']) || !is_numeric($item['user_id']) || $item['user_id'] <= 0)
		{
			return;
		}

		// Retrieve the user by ID
		$user = UserHelper::getUserById((int)$item['user_id']);

		// Verify if the user exists and the ID matches
		if ($user && $user->id === (int) $item['user_id'])
		{
			// Iterate over public properties of the user object
			foreach (get_object_vars($user) as $property => $value)
			{
				// Avoid overwriting the id in the item
				if ($property !== 'id')
				{
					$item[$property] = $value;
				}
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
	 * @since  3.2.2
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
	 * @since  3.2.2
	 */
	private function process($items, string $indexKey, string $linkKey, string $linkValue): array
	{
		$items = is_array($items) ? $items : [];
		if ($items !== [] && !$this->isMultipleSets($items))
		{
			$items = [$items];
		}

		foreach ($items as $n => &$item)
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

			// create/update user
			$item['user_id'] = $this->setUserDetails(
				$item,
				$this->getActiveUsers(
					$linkKey,
					$linkValue
				)
			);

			// remove empty row (means no user linked)
			if ($item['user_id'] == 0)
			{
				unset($items[$n]);
			}
		}

		return array_values($items);
	}

	/**
	 * Get current active Users Linked to this entity
	 *
	 * @param string   $linkKey    The link key on which the items where linked in the child table.
	 * @param string   $linkValue  The value of the link key in child table.
	 *
	 * @return array   The IDs of all active users.
	 * @since  5.0.2
	 */
	private function getActiveUsers(string $linkKey, string $linkValue): array
	{
		if (isset($this->activeUsers[$linkKey . $linkValue]))
		{
			return $this->activeUsers[$linkKey . $linkValue];
		}

		if (($users = $this->items->table($this->getTable())->values([$linkValue], $linkKey, 'user_id')) !== null)
		{
			$this->activeUsers[$linkKey . $linkValue] = $users;
			return $users;
		}

		return [];
	}

	/**
	 * Handles setting user details and saving them.
	 *
	 * This function retrieves the user by ID, sets the user details, 
	 * and adds appropriate user groups before saving the user.
	 *
	 * @param array $item        The user details passed by reference.
	 * @param array $activeUsers The current active user linked to this entity.
	 *
	 * @return int The ID of the saved user, or 0 on failure.
	 * @since  5.0.2
	 */
	private function setUserDetails(array &$item, array $activeUsers): int
	{
		$user = $this->loadUser($item, $activeUsers);
		$details = $this->extractUserDetails($item, $user);

		if ($this->identity->id != $user->id)
		{
			$this->assignUserGroups($details, $user, $item);
		}

		return $this->saveUserDetails($details, $details['id'] ?? 0);
	}

	/**
	 * Load the user based on the user ID from the item array.
	 *
	 * @param array $item         The array containing user details.
	 * @param array $activeUsers  The current active user linked to this entity.
	 * 
	 * @return User|null The user object if found, null otherwise.
	 * @since  5.0.2
	 */
	private function loadUser(array $item, array $activeUsers): ?User
	{
		if (!isset($item['user_id']) || !is_numeric($item['user_id']) || $item['user_id'] <= 0)
		{
			return null;
		}

		// only allow update to linked users
		if (!in_array($item['user_id'], $activeUsers))
		{
			return null;
		}

		$user = UserHelper::getUserById((int) $item['user_id']);

		if ($user && $user->id == $item['user_id'])
		{
			return $user;
		}

		return null;
	}

	/**
	 * Extract user details from the item array and prepare them for saving.
	 *
	 * @param array     $item The array containing user details.
	 * @param User|null $user The user object if found, null otherwise.
	 * 
	 * @return array The prepared user details array.
	 * @since  5.0.2
	 */
	private function extractUserDetails(array &$item, ?User $user): array
	{
		$details = [];

		if ($user !== null)
		{
			$details['id'] = (int) $item['user_id'];
		}

		foreach ($this->user as $property)
		{
			if (isset($item[$property]))
			{
				$details[$property] = $item[$property];
				unset($item[$property]);
			}
		}

		return $details;
	}

	/**
	 * Assigns user groups based on existing groups and entity type.
	 *
	 * @param array     &$details The array to store user details including groups.
	 * @param User|null $user     The user object if found, null otherwise.
	 * @param array     $item     The array containing additional user details.
	 *
	 * @return void
	 * @since 5.0.2
	 */
	private function assignUserGroups(array &$details, ?User $user, array $item): void
	{
		$groups = $user !== null ? (array) $user->groups : [];

		if (!empty($item['entity_type']))
		{
			$global_entity_groups = Component::getParams()->get($item['entity_type'] . '_groups', []);
			foreach ($global_entity_groups as $group)
			{
				if (!in_array($group, $groups))
				{
					$groups[] = $group;
				}
			}
		}

		// Ensure $details['groups'] is an array if it exists, else default to an empty array
		$detailsGroups = isset($details['groups']) ? (array) $details['groups'] : [];

		// Merge the arrays and remove duplicates
		$mergedGroups = array_unique(array_merge($detailsGroups, $groups));

		// Only set $details['groups'] if the merged array is not empty
		if (!empty($mergedGroups))
		{
			$details['groups'] = $mergedGroups;
		}
		else
		{
			unset($details['groups']);
		}
	}

	/**
	 * Save the user details using UserHelper and handle exceptions.
	 *
	 * @param array $details The prepared user details array.
	 * @param int   $userId  The ID of the user being processed.
	 * 
	 * @return int The ID of the saved user, or 0 on failure.
	 * @since 5.0.2
	 */
	private function saveUserDetails(array $details, int $userId): int
	{
		try {
			return UserHelper::save($details, 0, ['useractivation' => 0, 'sendpassword' => 1, 'allowUserRegistration' => 1]);
		} catch (NoUserIdFoundException $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'error');
		} catch (\Exception $e) {
			Factory::getApplication()->enqueueMessage($e->getMessage(), 'warning');
			return $userId;
		}

		return 0;
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

