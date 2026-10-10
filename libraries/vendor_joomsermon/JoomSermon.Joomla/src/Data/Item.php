<?php

/***[JCBGUI.power.licensing_template.712.$$$$]***/
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

namespace JoomSermon\Joomla\Data;


use JoomSermon\Joomla\Interfaces\Data\LoadInterface as Load;
use JoomSermon\Joomla\Interfaces\Data\InsertInterface as Insert;
use JoomSermon\Joomla\Interfaces\Data\UpdateInterface as Update;
use JoomSermon\Joomla\Interfaces\Data\DeleteInterface as Delete;
use JoomSermon\Joomla\Interfaces\Database\LoadInterface as Database;
use JoomSermon\Joomla\Interfaces\Data\ItemInterface;


/**
 * Data Item
 * 
 * @since 3.2.2
 */
final class Item implements ItemInterface
{

/***[JCBGUI.power.main_class_code.712.$$$$]***/
	/**
	 * The Load Class.
	 *
	 * @var   Load
	 * @since 3.2.2
	 */
	protected Load $load;

	/**
	 * The Insert Class.
	 *
	 * @var   Insert
	 * @since 3.2.2
	 */
	protected Insert $insert;

	/**
	 * The Update Class.
	 *
	 * @var   Update
	 * @since 3.2.2
	 */
	protected Update $update;

	/**
	 * The Delete Class.
	 *
	 * @var   Delete
	 * @since 3.2.2
	 */
	protected Delete $delete;

	/**
	 * The Load Class.
	 *
	 * @var   Database
	 * @since 3.2.2
	 */
	protected Database $database;

	/**
	 * Table Name
	 *
	 * @var    string
	 * @since 3.2.1
	 */
	protected string $table;

	/**
	 * The Active Action
	 *
	 * @var    string|null
	 * @since 5.1.4
	 */
	protected ?string $activeAction = null;

	/**
	 * Constructor.
	 *
	 * @param Load        $load     The LoadInterface Class.
	 * @param Insert      $insert   The InsertInterface Class.
	 * @param Update      $update   The UpdateInterface Class.
	 * @param Delete      $delete   The UpdateInterface Class.
	 * @param Database    $database The Database Load Class.
	 * @param string|null $table    The table name.
	 *
	 * @since 3.2.2
	 */
	public function __construct(Load $load, Insert $insert, Update $update,
		Delete $delete, Database $database, ?string $table = null)
	{
		$this->load = $load;
		$this->insert = $insert;
		$this->update = $update;
		$this->delete = $delete;
		$this->database = $database;
		if ($table !== null)
		{
			$this->table = $table;
		}
	}

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
	public function id(): int
	{
		$action = $this->activeAction;

		if ($action !== 'insert' && $action !== 'update')
		{
			return 0;
		}

		$method = $action . 'ids';

		$ids = $this->{$action}->{$method}(true);

		if ($ids === [])
		{
			return 0;
		}

		return (int) reset($ids);
	}

	/**
	 * Set the current active table
	 *
	 * @param string  $table The table that should be active
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
	 * Get an item
	 *
	 * @param string   $value   The item key value
	 * @param string   $key     The item key
	 *
	 * @return object|null The item object or null
	 * @since 3.2.2
	 */
	public function get(string $value, string $key = 'guid'): ?object
	{
		$this->activeAction = null;

		return $this->load->table($this->getTable())->item([$key => $value]);
	}

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
	public function value(string $value, string $key = 'guid', string $get = 'id')
	{
		$this->activeAction = null;

		return $this->load->table($this->getTable())->value([$key => $value], $get);
	}

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
	public function set(object $item, string $key = 'guid', ?string $action = null): bool
	{
		$this->activeAction = null;

		if ($action !== null || (isset($item->{$key}) && ($action = $this->action($item->{$key}, $key)) !== null))
		{
			return method_exists($this, $action) ? $this->{$action}($item, $key) : false;
		}

		return false;
	}

	/**
	 * Delete an item
	 *
	 * @param string   $value   The item key value
	 * @param string   $key     The item key
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function delete(string $value, string $key = 'guid'): bool
	{
		$this->activeAction = null;

		return $this->delete->table($this->getTable())->items([$key => $value]);
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
	 * Insert a item
	 *
	 * @param object   $item  The item
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function insert(object $item): bool
	{
		$this->activeAction = 'insert';

		return $this->insert->table($this->getTable())->item($item);
	}

	/**
	 * Update a item
	 *
	 * @param object   $item  The item
	 * @param string   $key   The item key
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function update(object $item, string $key): bool
	{
		$this->activeAction = 'update';

		return $this->update->table($this->getTable())->item($item, $key);
	}

	/**
	 * Get loading action
	 *
	 * @param mixed  $value The key value the item
	 * @param string  $key   The item key
	 *
	 * @return string
	 * @since 3.2.2
	 */
	private function action($value, string $key): string
	{
		$id = $this->database->value(
			["a.id" => 'id'],
			["a" => $this->getTable()],
			["a.$key" => $value]
		);

		if ($id !== null && $id > 0)
		{
			return 'update';
		}

		return 'insert';
	}/***[/JCBGUI$$$$]***/

}

