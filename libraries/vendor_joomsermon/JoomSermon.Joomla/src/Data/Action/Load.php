<?php

/***[JCBGUI.power.licensing_template.746.$$$$]***/
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

namespace JoomSermon\Joomla\Data\Action;


use JoomSermon\Joomla\Interfaces\ModelInterface as Model;
use JoomSermon\Joomla\Interfaces\Database\LoadInterface as Database;
use JoomSermon\Joomla\Interfaces\Data\LoadInterface;


/**
 * Data Load (GUID)
 * 
 * @since 3.2.2
 */
class Load implements LoadInterface
{

/***[JCBGUI.power.main_class_code.746.$$$$]***/
	/**
	 * Model Load
	 *
	 * @var    Model
	 * @since 2.0.1
	 */
	protected Model $model;

	/**
	 * Database Load
	 *
	 * @var    Database
	 * @since 2.0.1
	 */
	protected Database $load;

	/**
	 * Table Name
	 *
	 * @var    string
	 * @since 3.2.1
	 */
	protected string $table;

	/**
	 * Constructor
	 *
	 * @param Model       $model     The model object.
	 * @param Database    $load      The database object.
	 * @param string|null $table     The table name.
	 *
	 * @since 2.0.1
	 */
	public function __construct(Model $model, Database $load, ?string $table = null)
	{
		$this->model = $model;
		$this->load = $load;
		if ($table !== null)
		{
			$this->table = $table;
		}
	}

	/**
	 * Set the current active table
	 *
	 * @param string|null $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(?string $table): self
	{
		if ($table !== null)
		{
			$this->table = $table;
		}

		return $this;
	}

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
	public function value(array $keys, string $field)
	{
		return $this->model->value(
			$this->load->value(
				["a.{$field}" => $field],
				['a' => $this->getTable()],
				$this->prefix($keys)
			),
			$field,
			$this->getTable()
		);
	}

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
	public function values(array $keys, string $field): ?array
	{
		return $this->model->values(
			$this->load->values(
				["a.{$field}" => $field],
				['a' => $this->getTable()],
				$this->prefix($keys)
			),
			$field,
			$this->getTable()
		);
	}

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
	public function item(array $keys): ?object
	{
		return $this->model->item(
			$this->load->item(
				['all' => 'a.*'],
				['a' => $this->getTable()],
				$this->prefix($keys)
			),
			$this->getTable()
		);
	}
 
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
	 *          Example: $this->items($ids);
	 *
	 * @param   array    $keys    The item keys
	 *
	 * @return  array|null
	 * @since 2.0.1
	 */
	public function items(array $keys): ?array
	{
		return $this->model->items(
			$this->load->items(
				['all' => 'a.*'], ['a' => $this->getTable()], $this->prefix($keys)
			),
			$this->getTable()
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
	 * Add prefix to the keys
	 *
	 * @param   array    $keys The query keys
	 *
	 * @return  array
	 * @since 2.0.1
	 */
	private function prefix(array &$keys): array
	{
		// update the key values
		$bucket = [];
		foreach ($keys as $k => $v)
		{
			$bucket['a.' . $k] = $v;
		}
		return $bucket;
	}/***[/JCBGUI$$$$]***/

}

