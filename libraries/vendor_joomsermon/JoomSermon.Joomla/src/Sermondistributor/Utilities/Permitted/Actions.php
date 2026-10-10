<?php

/***[JCBGUI.power.licensing_template.1268.$$$$]***/
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

namespace JoomSermon\Joomla\Sermondistributor\Utilities\Permitted;



/***[JCBGUI.power.head.1268.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\CMS\Access\Access;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\GetHelper;
use JoomSermon\Joomla\Utilities\StringHelper;


/**
 * Permitted Action helper class.
 * 
 * Provides structured permissions checks for component, list, and item scopes for a user.
 * 
 * @since  5.1.4
 */
abstract class Actions
{

/***[JCBGUI.power.main_class_code.1268.$$$$]***/
	/**
	 * Get the permitted actions of a user.
	 *
	 * @param  string   $view        The related view name
	 * @param  ?object  $record      The item to act upon
	 * @param  ?string  $views       The related list view name
	 * @param  mixed    $target      Only get this permission (like edit, create, delete)
	 * @param  string   $component   The target component
	 * @param  object   $user        The user whose permissions we are loading
	 *
	 * @return Registry  Registry of permission/authorised actions.
	 * @since  5.1.4
	 */
	public static function get(
		string $view,
		?object &$record = null,
		?string $views = null,
		$target = null,
		string $component = 'sermondistributor',
		$user = null
	): Registry {
		$user = self::resolveUser($user);
		$result = new Registry(null, '');

		$view  = self::safe($view);
		$views = self::safe($views, true);

		$actions = self::loadActions($component);

		if (empty($actions))
		{
			return $result;
		}

		if (self::hasRecordId($record) && !isset($record->created_by))
		{
			$record->created_by = self::resolveCreatedBy($component, $view, (int) $record->id);
		}

		$targets = self::normalizeTargets($target);
		$componentActions = self::componentActions();

		foreach ($actions as $action)
		{
			self::processAction(
				$action->name ?? '',
				$result,
				$user,
				$component,
				$view,
				$views,
				$record,
				$targets,
				$componentActions
			);
		}

		return $result;
	}

	/**
	 * Ensure the user object is a valid Joomla User.
	 *
	 * @param  mixed  $user
	 *
	 * @return User
	 * @since  5.1.4
	 */
	protected static function resolveUser($user): User
	{
		if (!$user instanceof User)
		{
			$user = Factory::getApplication()->getIdentity();
		}

		return $user;
	}

	/**
	 * Safe string normalization.
	 *
	 * @param  mixed    $value
	 * @param  boolean  $allowNull
	 *
	 * @return string|null
	 * @since  5.1.4
	 */
	protected static function safe($value, bool $allowNull = false): ?string
	{
		if (StringHelper::check($value) && $allowNull)
		{
			return null;
		}

		return StringHelper::safe($value);
	}

	/**
	 * Load all ACL actions from the component's access.xml.
	 *
	 * @param  string  $component
	 *
	 * @return array
	 * @since  5.1.4
	 */
	protected static function loadActions(string $component): array
	{
		return Access::getActionsFromFile(
			JPATH_ADMINISTRATOR . '/components/com_' . $component . '/access.xml',
			"/access/section[@name='component']/"
		) ?: [];
	}

	/**
	 * Ensure we have a valid record with id.
	 *
	 * @param  mixed  $record
	 *
	 * @return boolean
	 * @since  5.1.4
	 */
	protected static function hasRecordId(&$record): bool
	{
		return (is_object($record) && isset($record->id) && (int) $record->id > 0);
	}

	/**
	 * Retrieve the created_by field for a given record if missing.
	 *
	 * @param  string  $component
	 * @param  string  $view
	 * @param  int     $id
	 *
	 * @return int|null
	 * @since  5.1.4
	 */
	protected static function resolveCreatedBy(string $component, string $view, int $id): ?int
	{
		return GetHelper::var($view, $id, 'id', 'created_by', '=', $component);
	}

	/**
	 * Normalize target(s) into array.
	 *
	 * @param  mixed  $target
	 *
	 * @return array
	 * @since  5.1.4
	 */
	protected static function normalizeTargets($target): array
	{
		if (!$target)
		{
			return [];
		}

		if (is_string($target))
		{
			return [$target];
		}

		return is_array($target) ? $target : [];
	}

	/**
	 * List of component-only actions.
	 *
	 * @return array
	 * @since  5.1.4
	 */
	protected static function componentActions(): array
	{
		return ['core.admin', 'core.manage', 'core.options', 'core.export'];
	}

	/**
	 * Process a single ACL action and append to Registry.
	 *
	 * @param  string    $actionName
	 * @param  Registry  $result
	 * @param  User      $user
	 * @param  string    $component
	 * @param  string    $view
	 * @param  string    $views
	 * @param  object    $record
	 * @param  array     $targets
	 * @param  array     $componentActions
	 *
	 * @return void
	 * @since  5.1.4
	 */
	protected static function processAction(
		string $actionName,
		Registry $result,
		User $user,
		string $component,
		?string $view,
		?string $views,
		?object &$record,
		array $targets,
		array $componentActions
	): void {
		// Target filtering
		if (!empty($targets) && self::filterActions($view, $actionName, $targets))
		{
			return;
		}

		$permission = false;
		$catpermission = false;
		$fallback = true;
		$area = 'comp';

		$isComponentAction = in_array($actionName, $componentActions, true);
		$isCoreAction = str_contains($actionName, 'core.');
		$isViewAction = str_contains($actionName, $view . '.');

		if (self::hasRecordId($record) && !$isComponentAction && ($isCoreAction || $isViewAction))
		{
			$area = 'item';
			$itemAsset = self::assetItem($component, $view, (int) $record->id);
			$permission = $user->authorise($actionName, $itemAsset);

			if (!$permission && self::isEditAction($actionName, $view) && self::isOwnRecord($record, $user))
			{
				self::handleOwnEditItem($user, $result, $actionName, $component, $view, (int) $record->id, $fallback);
			}
			elseif ($fallback && $views && self::hasCategory($record))
			{
				$area = 'category';
				$catpermission = self::handleCategoryScope($user, $result, $actionName, $component, $views, $view, $record, $fallback);
			}
		}

		if ($fallback)
		{
			self::finalizeFallback($result, $actionName, $user, $component, $area, $permission, $catpermission);
		}
	}

	/**
	 * Handle item-level own-edit logic.
	 *
	 * @param  User      $user
	 * @param  Registry  $result
	 * @param  string    $actionName
	 * @param  string    $component
	 * @param  string    $view
	 * @param  int       $id
	 * @param  boolean  &$fallback
	 *
	 * @return void
	 * @since  5.1.4
	 */
	protected static function handleOwnEditItem(User $user, Registry $result, string $actionName, string $component, string $view, int $id, bool &$fallback): void
	{
		$core = self::coreOf($actionName);

		if (
			$user->authorise($core . '.edit.own', self::assetItem($component, $view, $id))
			&& $user->authorise($core . '.edit.own', self::assetComponent($component))
		) {
			$result->set($actionName, true);
		} else {
			$result->set($actionName, false);
		}

		$fallback = false;
	}

	/**
	 * Handle category-scope permission logic.
	 *
	 * @param  User      $user
	 * @param  Registry  $result
	 * @param  string    $actionName
	 * @param  string    $component
	 * @param  string    $views
	 * @param  string    $view
	 * @param  object    $record
	 * @param  boolean  &$fallback
	 *
	 * @return boolean
	 * @since  5.1.4
	 */
	protected static function handleCategoryScope(
		User $user,
		Registry $result,
		string $actionName,
		string $component,
		string $views,
		string $view,
		object $record,
		bool &$fallback
	): bool {
		$categoryAction = self::categoryActionName($actionName, $view);
		$catAsset = self::assetCategory($component, $views, (int) $record->catid);
		$catpermission = $user->authorise($categoryAction, $catAsset);

		if (!$catpermission && self::isEditAction($actionName, $view) && self::isOwnRecord($record, $user))
		{
			$core = self::coreOf($actionName);

			if (
				$user->authorise('core.edit.own', $catAsset)
				&& $user->authorise($core . '.edit.own', self::assetComponent($component))
			) {
				$result->set($actionName, true);
			} else {
				$result->set($actionName, false);
			}

			$fallback = false;
		}

		return $catpermission;
	}

	/**
	 * Final fallback rule: global override or block.
	 *
	 * @param  Registry  $result
	 * @param  string    $actionName
	 * @param  User      $user
	 * @param  string    $component
	 * @param  string    $area
	 * @param  boolean   $permission
	 * @param  boolean   $catpermission
	 *
	 * @return void
	 * @since  5.1.4
	 */
	protected static function finalizeFallback(
		Registry $result,
		string $actionName,
		User $user,
		string $component,
		string $area,
		bool $permission,
		bool $catpermission
	): void {
		if (($area === 'item' && !$permission) || ($area === 'category' && !$catpermission))
		{
			$result->set($actionName, false);
			return;
		}

		// Finally remember the global settings have the final say. (even if item allow)
		// The local item permissions can block, but it can't open and override of global permissions.
		// Since items are created by users and global permissions is set by system admin.
		$result->set($actionName, $user->authorise($actionName, self::assetComponent($component)));
	}

	/**
	 * Filter action targets.
	 *
	 * @param  string  $view
	 * @param  string  $action
	 * @param  array   $targets
	 *
	 * @return boolean
	 * @since  5.1.4
	 */
	protected static function filterActions(string $view, string $action, array $targets): bool
	{
		foreach ($targets as $target)
		{
			if (str_contains($action, $view . '.' . $target) || str_contains($action, 'core.' . $target))
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Check if record is owned by current user.
	 *
	 * @param  object  $record
	 * @param  User    $user
	 *
	 * @return boolean
	 * @since  5.1.4
	 */
	protected static function isOwnRecord(object $record, User $user): bool
	{
		return isset($record->created_by) && (int) $record->created_by === (int) $user->id;
	}

	/**
	 * Check if record has category.
	 *
	 * @param  object  $record
	 *
	 * @return boolean
	 * @since  5.1.4
	 */
	protected static function hasCategory(object $record): bool
	{
		return isset($record->catid) && (int) $record->catid > 0;
	}

	/**
	 * Check if action is edit.
	 *
	 * @param  string  $actionName
	 * @param  string  $view
	 *
	 * @return boolean
	 * @since  5.1.4
	 */
	protected static function isEditAction(string $actionName, string $view): bool
	{
		return ($actionName === 'core.edit' || $actionName === $view . '.edit');
	}

	/**
	 * Extract core segment.
	 *
	 * @param  string  $actionName
	 *
	 * @return string
	 * @since  5.1.4
	 */
	protected static function coreOf(string $actionName): string
	{
		return explode('.', $actionName)[0] ?? 'core';
	}

	/**
	 * Convert <view>.* to core.* for category ACL consistency.
	 *
	 * @param  string  $actionName
	 * @param  string  $view
	 *
	 * @return string
	 * @since  5.1.4
	 */
	protected static function categoryActionName(string $actionName, string $view): string
	{
		if (str_contains($actionName, $view) && !str_contains($actionName, 'core.'))
		{
			$parts = explode('.', $actionName);
			$parts[0] = 'core';
			return implode('.', $parts);
		}
		return $actionName;
	}

	/**
	 * Build component asset name.
	 *
	 * @param  string  $component
	 *
	 * @return string
	 * @since  5.1.4
	 */
	protected static function assetComponent(string $component): string
	{
		return 'com_' . $component;
	}

	/**
	 * Build item asset name.
	 *
	 * @param  string  $component
	 * @param  string  $view
	 * @param  int     $id
	 *
	 * @return string
	 * @since  5.1.4
	 */
	protected static function assetItem(string $component, string $view, int $id): string
	{
		return 'com_' . $component . '.' . $view . '.' . $id;
	}

	/**
	 * Build category asset name.
	 *
	 * @param  string  $component
	 * @param  string  $views
	 * @param  int     $catid
	 *
	 * @return string
	 * @since  5.1.4
	 */
	protected static function assetCategory(string $component, string $views, int $catid): string
	{
		return 'com_' . $component . '.' . $views . '.category.' . $catid;
	}/***[/JCBGUI$$$$]***/

}

