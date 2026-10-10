<?php

/***[JCBGUI.power.licensing_template.485.$$$$]***/
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

namespace JoomSermon\Joomla\Abstraction;


use JoomSermon\Joomla\Interfaces\Activeregistryinterface;


/**
 * Active Storage Registry.
 * 
 * Don't use this beyond 10 dimensional depth for best performance.
 * 
 * @since 3.2.0
 */
abstract class ActiveRegistry implements Activeregistryinterface
{

/***[JCBGUI.power.main_class_code.485.$$$$]***/
	/**
	 * The registry array.
	 *
	 * @var    array
	 * @since 3.2.0
	 **/
	protected array $active = [];

	/**
	 * Base switch to add values as string or array
	 *
	 * @var    boolean
	 * @since 3.2.0
	 **/
	protected bool $addAsArray = false;

	/**
	 * Base switch to keep array values unique
	 *
	 * @var    boolean
	 * @since 3.2.2
	 **/
	protected bool $uniqueArray = false;

	/**
	 * Check if the registry has any content.
	 *
	 * @return bool  Returns true if the active array is not empty, false otherwise.
	 * @since 3.2.0
	 */
	public function isActive(): bool
	{
		return !empty($this->active);
	}

	/**
	 * Get all value from the active registry.
	 *
	 * @return array   The values or empty array.
	 * @since 3.2.0
	 */
	public function allActive(): array
	{
		return $this->active;
	}

	/**
	 * Sets a value into the registry using multiple keys.
	 *
	 * @param mixed   $value     The value to set.
	 * @param string  ...$keys   The keys to determine the location.
	 *
	 * @throws \InvalidArgumentException If any of the keys are not a number or string.
	 * @return void
	 * @since 3.2.0
	 */
	public function setActive($value, string ...$keys): void
	{
		if (!$this->validActiveKeys($keys))
		{
			throw new \InvalidArgumentException("Keys must only be strings or numbers to set any value.");
		}

		$array = &$this->active;

		foreach ($keys as $key)
		{
			if (!isset($array[$key]))
			{
				if (!is_array($array))
				{
					$path = '[' . implode('][', $keys) . ']';
					throw new \InvalidArgumentException("Attempted to use key '{$key}' on a non-array value: {$array}. Path: {$path} Value: {$value}");
				}

				$array[$key] = [];
			}
			$array = &$array[$key];
		}

		$array = $value;
	}

	/**
	 * Adds content into the registry. If a key exists,
	 * it either appends or concatenates based on the value's type.
	 *
	 * @param mixed       $value     The value to set.
	 * @param bool|null   $asArray   Determines if the new value should be treated as an array.
	 *                                Default is $addAsArray = false (if null) in base class.
	 *                                Override in child class allowed set class property $addAsArray = true.
	 * @param string      ...$keys   The keys to determine the location.
	 *
	 * @throws \InvalidArgumentException If any of the keys are not a number or string.
	 * @return void
	 * @since 3.2.0
	 */
	public function addActive($value, ?bool $asArray, string ...$keys): void
	{
		if (!$this->validActiveKeys($keys))
		{
			throw new \InvalidArgumentException("Keys must only be strings or numbers to add any value.");
		}

		// null fallback to class value
		if ($asArray === null)
		{
			$asArray = $this->addAsArray;
		}

		$array = &$this->active;

		foreach ($keys as $key)
		{
			if (!isset($array[$key]))
			{
				if (!is_array($array))
				{
					$path = '[' . implode('][', $keys) . ']';
					throw new \InvalidArgumentException("Attempted to use key '{$key}' on a non-array value: {$array}. Path: {$path} Value: {$value}");
				}

				$array[$key] = [];
			}
			$array = &$array[$key];
		}

		// add string
		if (!$asArray && $array === [])
		{
			$array = '';
		}

		// Handle the adding logic at the tip of the array
		if (is_array($array) || $asArray)
		{
			if (!is_array($array))
			{
				// Convert to array if it's not already an array
				$array = [$array];
			}

			if ($this->uniqueArray && in_array($value, $array))
			{
				// we do nothing
				return;
			}
			else
			{
				$array[] = $value;
			}
		}
		else
		{
			if (is_string($value) || is_numeric($value))
			{
				$array .= (string) $value;
			}
			else
			{
				$array = $value;
			}
		}
	}

	/**
	 * Retrieves a value (or sub-array) from the registry using multiple keys.
	 *
	 * @param mixed   $default     The default value if not set.
	 * @param string  ...$keys      The keys to determine the location.
	 *
	 * @throws \InvalidArgumentException If any of the keys are not a number or string.
	 * @return mixed The value or sub-array from the storage. Null if the location doesn't exist.
	 * @since 3.2.0
	 */
	public function getActive($default, string ...$keys)
	{
		if (!$this->validActiveKeys($keys))
		{
			throw new \InvalidArgumentException("Keys must only be strings or numbers to get any value.");
		}

		$array = $this->active;

		foreach ($keys as $key)
		{
			if (!isset($array[$key]))
			{
				return $default;
			}
			$array = $array[$key];
		}

		return $array;
	}

	/**
	 * Removes a value (or sub-array) from the registry using multiple keys.
	 *
	 * @param string ...$keys The keys to determine the location.
	 *
	 * @throws \InvalidArgumentException If any of the keys are not a number or string.
	 * @return void
	 * @since 3.2.0
	 */
	public function removeActive(string ...$keys): void
	{
		if (!$this->validActiveKeys($keys))
		{
			throw new \InvalidArgumentException("Keys must only be strings or numbers to remove any value.");
		}

		$array = &$this->active;
		$lastKey = array_pop($keys);

		foreach ($keys as $key)
		{
			if (!isset($array[$key]))
			{
				return;  // Exit early if the key doesn't exist
			}
			$array = &$array[$key];
		}

		unset($array[$lastKey]);
	}

	/**
	 * Checks the existence of a particular location in the registry using multiple keys.
	 *
	 * @param string ...$keys The keys to determine the location.
	 *
	 * @throws \InvalidArgumentException If any of the keys are not a number or string.
	 * @return bool True if the location exists, false otherwise.
	 * @since 3.2.0
	 */
	public function existsActive(string ...$keys): bool
	{
		if (!$this->validActiveKeys($keys))
		{
			throw new \InvalidArgumentException("Keys must only be strings or numbers to check if any value exist.");
		}

		$array = $this->active;

		foreach ($keys as $key)
		{
			if (!isset($array[$key]))
			{
				return false;
			}
			$array = $array[$key];
		}

		return true;
	}

	/**
	 * Checks that the keys are valid
	 *
	 * @param array  $keys The keys to determine the location.
	 *
	 * @return bool   False if any of the keys are not a number or string.
	 * @since 3.2.0
	 */
	protected function validActiveKeys(array $keys): bool
	{
		foreach ($keys as $key)
		{
			if ($key === '' || (!is_string($key) && !is_numeric($key)))
			{
				return false;
			}
		}

		return true;
	}/***[/JCBGUI$$$$]***/

}

