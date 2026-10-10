<?php

/***[JCBGUI.power.licensing_template.489.$$$$]***/
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



/***[JCBGUI.power.head.489.$$$$]***/
use Joomla\Registry\Factory as FormatFactory;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\Registryinterface;
use JoomSermon\Joomla\Abstraction\ActiveRegistry;


/**
 * VDM Basic Registry.
 * 
 * Don't use this beyond 10 dimensional depth for best performance.
 * 
 * @since 3.2.0
 * @since 5.0.4 Joomla Registry Compatible
 */
abstract class Registry extends ActiveRegistry implements Registryinterface,  \JsonSerializable, \ArrayAccess, \IteratorAggregate, \Countable
{

/***[JCBGUI.power.main_class_code.489.$$$$]***/
	/**
	 * Path separator
	 *
	 * @var    string|null
	 * @since  3.2.0
	 */
	protected ?string $separator = '.';

	/**
	 * The name of the registry.
	 *
	 * @var   string|null
	 * @since 5.0.4
	 */
	protected ?string $name = null;

	/**
	 * Constructor.
	 *
	 * Initializes the Registry object with optional data.
	 *
	 * @param  mixed        $data      Optional data to load into the registry.
	 *                                    Can be an array, string, or object.
	 * @param  string|null  $separator The path separator, and empty string will flatten the registry.
	 * @since  5.0.4
	 */
	public function __construct($data = null, ?string $separator = null)
	{
		// we don't allow null on initialization (default is a dot)
		// so that all class inheritance can override the separator property
		// use an empty string if you want to flatten the registry
		if ($separator !== null)
		{
			$this->setSeparator($separator);
		}

		if ($data !== null)
		{
			if (is_array($data))
			{
				$this->loadArray($data);
			}
			elseif (is_string($data))
			{
				$this->loadString($data);
			}
			elseif (is_object($data))
			{
				$this->loadObject($data);
			}
		}
	}

	/**
	 * Magic method to get a value from the registry.
	 *
	 * Allows for accessing registry data using object property syntax.
	 *
	 * @param string $name The name of the property to get.
	 *
	 * @return mixed The value of the property, or null if not found.
	 * @since  5.0.4
	 */
	public function __get($name)
	{
		return $this->get($name);
	}

	/**
	 * Magic method to set a value in the registry.
	 *
	 * Allows for setting registry data using object property syntax.
	 *
	 * @param string $name  The name of the property to set.
	 * @param mixed  $value The value to set.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function __set($name, $value)
	{
		$this->set($name, $value);
	}

	/**
	 * Magic method to check if a property is set in the registry.
	 *
	 * Allows for using isset() on registry properties.
	 *
	 * @param string $name The name of the property to check.
	 *
	 * @return bool True if the property is set, false otherwise.
	 * @since  5.0.4
	 */
	public function __isset($name)
	{
		return $this->exists($name);
	}

	/**
	 * Magic method to unset a property in the registry.
	 *
	 * Allows for using unset() on registry properties.
	 *
	 * @param string $name The name of the property to unset.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function __unset($name)
	{
		$this->remove($name);
	}

	/**
	 * Magic method to clone the registry.
	 *
	 * Performs a deep copy of the registry data.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function __clone()
	{
		$this->active = unserialize(serialize($this->active));
	}

	/**
	 * Magic method to convert the registry to a string.
	 *
	 * Returns the registry data in JSON format.
	 *
	 * @return string The registry data in JSON format.
	 * @since  5.0.4
	 */
	public function __toString()
	{
		return $this->toString();
	}

	/**
	 * Loads data into the registry from a string using Joomla's format classes.
	 *
	 * @param string  $data     The data string to load.
	 * @param string  $format   The format of the data string. Supported formats: 'json', 'ini', 'xml', 'php'.
	 * @param  array  $options  Options used by the formatter
	 *
	 * @return self
	 * @throws \InvalidArgumentException If the format is not supported.
	 * @since  5.0.4
	 */
	public function loadString(string $data, string $format = 'JSON', array $options = []): self
	{
		// Load a string into the given namespace [or default namespace if not given]
		$object = FormatFactory::getFormat($format, $options)->stringToObject($data, $options);

		// Merge the object into the registry
		$this->loadObject($object);

		return $this;
	}

	/**
	 * Loads data into the registry from an object.
	 *
	 * @param object  $object  The data object to load.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function loadObject(object $object): self
	{
		// Convert the object to an array
		$array = $this->objectToArray($object);

		// Merge the array into the registry
		$this->loadArray($array);

		return $this;
	}

	/**
	 * Loads data into the registry from an array.
	 *
	 * The loaded data will be merged into the registry's existing data.
	 *
	 * @param array $array The array of data to load into the registry.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function loadArray(array $array): self
	{
		$this->active = $this->arrayMergeRecursive($this->active, $array);
		return $this;
	}

	/**
	 * Loads data into the registry from a file.
	 *
	 * @param string $path   The path to the file to load.
	 * @param string $format The format of the file. Supported formats: 'json', 'ini', 'xml', 'php'.
	 *
	 * @return self
	 * @throws \InvalidArgumentException If the file does not exist or is not readable.
	 * @throws \RuntimeException If the file cannot be read.
	 * @since  5.0.4
	 */
	public function loadFile(string $path, string $format = 'json'): self
	{
		if (!file_exists($path) || !is_readable($path))
		{
			throw new \InvalidArgumentException("File does not exist or is not readable: {$path}");
		}

		$data = file_get_contents($path);

		if ($data === false)
		{
			throw new \RuntimeException("Failed to read file: {$path}");
		}

		$this->loadString($data, $format);

		return $this;
	}

	/**
	 * Sets a value into the registry using multiple keys.
	 *
	 * @param  string  $path      Registry path (e.g. vdm.content.builder)
	 * @param  mixed   $value     Value of entry
	 *
	 * @throws \InvalidArgumentException If any of the path values are not a number or string.
	 * @return self
	 * @since  3.2.0
	 */
	public function set(string $path, $value): self
	{
		if (($keys = $this->getActiveKeys($path)) === null)
		{
			throw new \InvalidArgumentException("Path must only be strings or numbers to set any value.");
		}

		$this->setActive($value, ...$keys);

		return $this;
	}

	/**
	 * Adds content into the registry. If a key exists,
	 * it either appends or concatenates based on $asArray switch.
	 *
	 * @param  string      $path      Registry path (e.g. vdm.content.builder)
	 * @param  mixed       $value     Value of entry
	 * @param  bool|null   $asArray   Determines if the new value should be treated as an array.
	 *                                Default is $addAsArray = false (if null) in base class.
	 *                                Override in child class allowed set class property $addAsArray = true.
	 *
	 * @throws \InvalidArgumentException If any of the path values are not a number or string.
	 * @return self
	 * @since  3.2.0
	 */
	public function add(string $path, $value, ?bool $asArray = null): self
	{
		if (($keys = $this->getActiveKeys($path)) === null)
		{
			throw new \InvalidArgumentException("Path must only be strings or numbers to add any value.");
		}

		$this->addActive($value, $asArray, ...$keys);

		return $this;
	}

	/**
	 * Retrieves a value (or sub-array) from the registry using multiple keys.
	 *
	 * @param  string  $path     Registry path (e.g. vdm.content.builder)
	 * @param  mixed   $default  Optional default value, returned if the internal doesn't exist.
	 *
	 * @throws \InvalidArgumentException If any of the path values are not a number or string.
	 * @return mixed The value or sub-array from the storage. Null if the location doesn't exist.
	 * @since  3.2.0
	 */
	public function get(string $path, $default = null): mixed
	{
		if (($keys = $this->getActiveKeys($path)) === null)
		{
			throw new \InvalidArgumentException("Path must only be strings or numbers to get any value.");
		}

		return $this->getActive($default, ...$keys);
	}

	/**
	 * Removes a value (or sub-array) from the registry using multiple keys.
	 *
	 * @param  string  $path  Registry path (e.g. vdm.content.builder)
	 *
	 * @throws \InvalidArgumentException If any of the path values are not a number or string.
	 * @return self
	 * @since  3.2.0
	 */
	public function remove(string $path): self
	{
		if (($keys = $this->getActiveKeys($path)) === null)
		{
			throw new \InvalidArgumentException("Path must only be strings or numbers to remove any value.");
		}

		$this->removeActive(...$keys);

		return $this;
	}

	/**
	 * Checks the existence of a particular location in the registry using multiple keys.
	 *
	 * @param  string  $path  Registry path (e.g. vdm.content.builder)
	 *
	 * @throws \InvalidArgumentException If any of the path values are not a number or string.
	 * @return bool True if the location exists, false otherwise.
	 * @since  3.2.0
	 */
	public function exists(string $path): bool
	{
		if (($keys = $this->getActiveKeys($path)) === null)
		{
			throw new \InvalidArgumentException("Path must only be strings or numbers to check if any value exist.");
		}

		return $this->existsActive(...$keys);
	}

	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode(),
	 *                 which is a value of any type other than a resource.
	 * @since  5.0.4
	 */
	public function jsonSerialize(): mixed
	{
		return $this->active;
	}

	/**
	 * Count elements of the registry.
	 *
	 * @return int The number of elements in the registry.
	 * @since  5.0.4
	 */
	public function count(): int
	{
		return count($this->active);
	}

	/**
	 * Whether a given offset exists in the registry.
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return bool True if the offset exists, false otherwise.
	 * @since  5.0.4
	 */
	public function offsetExists(mixed $offset): bool
	{
		if (!is_string($offset))
		{
			return false;
		}
		return $this->exists($offset);
	}

	/**
	 * Retrieve the value at a given offset.
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed The value at the specified offset.
	 * @since  5.0.4
	 */
	public function offsetGet(mixed $offset): mixed
	{
		if (!is_string($offset))
		{
			return null;
		}
		return $this->get($offset);
	}

	/**
	 * Set the value at a given offset.
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function offsetSet(mixed $offset, mixed $value): void
	{
		if (!is_string($offset))
		{
			return;
		}
		$this->set($offset, $value);
	}

	/**
	 * Unset the value at a given offset.
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function offsetUnset(mixed $offset): void
	{
		if (!is_string($offset))
		{
			return;
		}
		$this->remove($offset);
	}

	/**
	 * Retrieve an external iterator for the registry.
	 *
	 * @return \Traversable An instance of an object implementing Iterator or Traversable.
	 * @since  5.0.4
	 */
	public function getIterator(): \Traversable
	{
		return new \ArrayIterator($this->active);
	}

	/**
	 * Get the registry data as an associative array.
	 *
	 * @return array The registry data.
	 * @since  5.0.4
	 */
	public function toArray(): array
	{
		return $this->active;
	}

	/**
	 * Get the registry data as an object.
	 *
	 * @return object The registry data converted to an object.
	 * @since  5.0.4
	 */
	public function toObject()
	{
		return $this->arrayToObject($this->active);
	}

	/**
	 * Converts the registry data to a string in the specified format.
	 *
	 * @param string $format  The format to output the string in. Supported formats: 'json', 'ini', 'xml', 'php'.
	 * @param array  $options Options used by the formatter.
	 *
	 * @return string The registry data in the specified format.
	 *
	 * @throws \InvalidArgumentException If the format is not supported.
	 * @since  5.0.4
	 */
	public function toString(string $format = 'JSON', array $options = []): string
	{
		// Convert the internal array to an object
		$object = $this->arrayToObject($this->active);

		return FormatFactory::getFormat($format, $options)->objectToString($object, $options);
	}

	/**
	 * Flattens the registry data into a one-dimensional array.
	 *
	 * @param string|null $separator  The separator for the key names.
	 * @param bool        $full       True to include the full path as keys.
	 *
	 * @return array The flattened data array.
	 * @since 5.0.4
	 */
	public function flatten(?string $separator = null, bool $full = false): array
	{
		// we use default separator
		if ($separator === null)
		{
			$separator = $this->separator;
		}

		return $this->flattenArray($this->active, $separator, $full);
	}

	/**
	 * Sets a default value if not already set.
	 *
	 * @param string $path The registry path (e.g., 'vdm.content.builder').
	 * @param mixed  $default The default value to set if the path does not exist.
	 *
	 * @return mixed The value of the path after the method call.
	 * @since  5.0.4
	 */
	public function def(string $path, $default)
	{
		if (!$this->exists($path))
		{
			$this->set($path, $default);
			return $default;
		}
		return $this->get($path);
	}

	/**
	 * Merges another registry into this one.
	 *
	 * The data from the source registry will be merged into this registry,
	 * overwriting any existing values with the same keys.
	 *
	 * @param Registryinterface $source The registry to merge with this one.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function merge(Registryinterface $source): self
	{
		$this->active = $this->arrayMergeRecursive($this->active, $source->toArray());
		return $this;
	}

	/**
	 * Clears all data from the registry.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function clear(): self
	{
		$this->active = [];
		return $this;
	}

	/**
	 * Extracts a subset of the registry data based on a given path.
	 *
	 * @param string      $path      The registry path to extract.
	 * @param mixed       $default   Optional default value, returned if the path does not exist.
	 * @param string|null $separator The path separator.
	 *
	 * @return self   A new Registry instance with the extracted data.
	 * @since  5.0.4
	 */
	public function extract(string $path, $default = null, ?string $separator = null): self
	{
		$originalSeparator = $this->getSeparator();
		if ($separator !== null)
		{
			$this->setSeparator($separator);
		}

		$data = $this->get($path, $default);

		if ($separator !== null)
		{
			$this->setSeparator($originalSeparator);
		}

		$newRegistry = new static();

		if ($data !== $default)
		{
			if (is_array($data))
			{
				$newRegistry->loadArray($data);
			}
			else
			{
				$newRegistry->set('value', $data);
			}
		}

		return $newRegistry;
	}

	/**
	 * Appends content into the registry.
	 *
	 * If a key exists, the value will be appended to the existing value.
	 *
	 * @param string $path  The registry path (e.g., 'vdm.content.builder').
	 * @param mixed  $value The value to append.
	 *
	 * @return self
	 * @since 5.0.4
	 */
	public function append(string $path, $value): self
	{
		return $this->add($path, $value, false);
	}

	/**
	 * Gets the name of the registry.
	 *
	 * @return string|null The name of the registry.
	 * @since  5.0.4
	 */
	public function getName(): ?string
	{
		return $this->name;
	}

	/**
	 * Sets the name of the registry.
	 *
	 * @param string|null $name The name to set.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function setName(?string $name): self
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * Sets a separator value
	 *
	 * @param string|null   $value     The value to set.
	 *
	 * @return self
	 * @since  3.2.0
	 */
	public function setSeparator(?string $value): self
	{
		$this->separator = $value;

		return $this;
	}

	/**
	 * Gets the current path separator used in registry paths.
	 *
	 * @return string|null The path separator.
	 * @since  5.0.4
	 */
	public function getSeparator(): ?string
	{
		return $this->separator;
	}

	/**
	 * Recursively converts an array to an object.
	 *
	 * This method is used to convert the internal array data into an object
	 * structure suitable for serialization or other operations that require objects.
	 *
	 * @param mixed $data The data to convert.
	 *
	 * @return mixed The converted object, or the original data if not an array.
	 * @since  5.0.4
	 */
	protected function arrayToObject($data)
	{
		if (is_array($data))
		{
			$object = new \stdClass();
			foreach ($data as $key => $value)
			{
				// Handle numeric keys for object properties
				if (is_numeric($key))
				{
					$key = 'item' . $key;
				}
				$object->{$key} = $this->arrayToObject($value);
			}
			return $object;
		}
		else
		{
			return $data;
		}
	}

	/**
	 * Recursively converts an object to an array.
	 *
	 * This method is used to convert data loaded from formats that produce objects
	 * (e.g., JSON, XML) into an array structure for internal storage.
	 *
	 * @param mixed $data The data to convert.
	 *
	 * @return mixed The converted array, or the original data if not an object.
	 * @since  5.0.4
	 */
	protected function objectToArray($data)
	{
		return json_decode(json_encode($data), true);
	}

	/**
	 * Recursively merges two arrays.
	 *
	 * This method merges the elements of two arrays together so that the values of one
	 * are appended to the end of the previous one. It preserves numeric keys.
	 *
	 * @param array $array1 The array to merge into.
	 * @param array $array2 The array to merge from.
	 *
	 * @return array The merged array.
	 * @since  5.0.4
	 */
	protected function arrayMergeRecursive(array $array1, array $array2): array
	{
		foreach ($array2 as $key => $value)
		{
			// If the value is an array and the key exists in both arrays, merge recursively
			if (is_array($value) && isset($array1[$key]) && is_array($array1[$key]))
			{
				$array1[$key] = $this->arrayMergeRecursive($array1[$key], $value);
			}
			else
			{
				// Otherwise, replace or set the value
				$array1[$key] = $value;
			}
		}
		return $array1;
	}

	/**
	 * Helper function to recursively flatten the array.
	 *
	 * @param array  $array       The array to flatten.
	 * @param string $separator   The separator for the key names.
	 * @param bool   $full        True to include the full path as keys.
	 * @param array  $flattened   The flattened array (used internally for recursion).
	 * @param string $path        The current path (used internally for recursion).
	 *
	 * @return array The flattened array.
	 * @since  5.0.4
	 */
	protected function flattenArray(array $array, string $separator, bool $full, array $flattened = [], string $path = ''): array
	{
		foreach ($array as $key => $value)
		{
			if ($full)
			{
				$newPath = $path === '' ? $key : $path . $separator . $key;
			}
			else
			{
				$newPath = $key;
			}

			if (is_array($value))
			{
				$flattened = $this->flattenArray($value, $separator, $full, $flattened, $newPath);
			}
			else
			{
				$flattened[$newPath] = $value;
			}
		}
		return $flattened;
	}

	/**
	 * Get that the active keys from a path
	 *
	 * @param string  $path   The path to determine the location registry.
	 *
	 * @return array|null      The valid array of keys
	 * @since  3.2.0
	 */
	protected function getActiveKeys(string $path): ?array
	{
		// empty path no allowed
		if ($path === '')
		{
			return null;
		}

		// Flatten the path
		if ($this->separator === null || $this->separator === '')
		{
			return [$path];
		}

		$keys = array_values(array_filter(explode($this->separator, $path), 'strlen'));

		if (empty($keys))
		{
			return null;
		}

		return $keys;
	}/***[/JCBGUI$$$$]***/

}

