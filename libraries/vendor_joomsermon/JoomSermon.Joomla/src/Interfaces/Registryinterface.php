<?php

/***[JCBGUI.power.licensing_template.490.$$$$]***/
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

namespace JoomSermon\Joomla\Interfaces;


use JoomSermon\Joomla\Interfaces\Activeregistryinterface;


/**
 * The Registry Interface
 * 
 * @since 3.2.0
 * @since 5.0.4 Joomla Registry Compatible
 */
interface Registryinterface extends Activeregistryinterface
{

/***[JCBGUI.power.main_class_code.490.$$$$]***/
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
	public function __get($name);

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
	public function __set($name, $value);

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
	public function __isset($name);

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
	public function __unset($name);

	/**
	 * Magic method to clone the registry.
	 *
	 * Performs a deep copy of the registry data.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function __clone();

	/**
	 * Magic method to convert the registry to a string.
	 *
	 * Returns the registry data in JSON format.
	 *
	 * @return string The registry data in JSON format.
	 * @since  5.0.4
	 */
	public function __toString();

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
	public function loadString(string $data, string $format = 'JSON', array $options = []): self;

	/**
	 * Loads data into the registry from an object.
	 *
	 * @param object  $object   The data object to load.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function loadObject(object $object): self;

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
	public function loadArray(array $array): self;

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
	public function loadFile(string $path, string $format = 'json'): self;

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
	public function set(string $path, $value): self;

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
	public function add(string $path, $value, ?bool $asArray = null): self;

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
	public function get(string $path, $default = null): mixed;

	/**
	 * Removes a value (or sub-array) from the registry using multiple keys.
	 *
	 * @param  string  $path  Registry path (e.g. vdm.content.builder)
	 *
	 * @throws \InvalidArgumentException If any of the path values are not a number or string.
	 * @return self
	 * @since  3.2.0
	 */
	public function remove(string $path): self;

	/**
	 * Checks the existence of a particular location in the registry using multiple keys.
	 *
	 * @param  string  $path  Registry path (e.g. vdm.content.builder)
	 *
	 * @throws \InvalidArgumentException If any of the path values are not a number or string.
	 * @return bool True if the location exists, false otherwise.
	 * @since  3.2.0
	 */
	public function exists(string $path): bool;

	/**
	 * Specify data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode(),
	 *                 which is a value of any type other than a resource.
	 * @since  5.0.4
	 */
	public function jsonSerialize(): mixed;

	/**
	 * Count elements of the registry.
	 *
	 * @return int The number of elements in the registry.
	 * @since  5.0.4
	 */
	public function count(): int;

	/**
	 * Whether a given offset exists in the registry.
	 *
	 * @param mixed $offset An offset to check for.
	 *
	 * @return bool True if the offset exists, false otherwise.
	 * @since  5.0.4
	 */
	public function offsetExists(mixed $offset): bool;

	/**
	 * Retrieve the value at a given offset.
	 *
	 * @param mixed $offset The offset to retrieve.
	 *
	 * @return mixed The value at the specified offset.
	 * @since  5.0.4
	 */
	public function offsetGet(mixed $offset): mixed;

	/**
	 * Set the value at a given offset.
	 *
	 * @param mixed $offset The offset to assign the value to.
	 * @param mixed $value  The value to set.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function offsetSet(mixed $offset, mixed $value): void;

	/**
	 * Unset the value at a given offset.
	 *
	 * @param mixed $offset The offset to unset.
	 *
	 * @return void
	 * @since  5.0.4
	 */
	public function offsetUnset(mixed $offset): void;

	/**
	 * Retrieve an external iterator for the registry.
	 *
	 * @return \Traversable An instance of an object implementing Iterator or Traversable.
	 * @since  5.0.4
	 */
	public function getIterator(): \Traversable;

	/**
	 * Get the registry data as an associative array.
	 *
	 * @return array The registry data.
	 * @since  5.0.4
	 */
	public function toArray(): array;

	/**
	 * Get the registry data as an object.
	 *
	 * @return object The registry data converted to an object.
	 * @since  5.0.4
	 */
	public function toObject();

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
	public function toString(string $format = 'JSON', array $options = []): string;

	/**
	 * Flattens the registry data into a one-dimensional array.
	 *
	 * @param string|null $separator  The separator for the key names.
	 * @param bool        $full       True to include the full path as keys.
	 *
	 * @return array The flattened data array.
	 * @since 5.0.4
	 */
	public function flatten(?string $separator = null, bool $full = false): array;

	/**
	 * Sets a default value if not already set.
	 *
	 * @param string $path The registry path (e.g., 'vdm.content.builder').
	 * @param mixed  $default The default value to set if the path does not exist.
	 *
	 * @return mixed The value of the path after the method call.
	 * @since  5.0.4
	 */
	public function def(string $path, $default);

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
	public function merge(Registryinterface $source): self;

	/**
	 * Clears all data from the registry.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function clear(): self;

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
	public function extract(string $path, $default = null, ?string $separator = null): self;

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
	public function append(string $path, $value): self;

	/**
	 * Gets the name of the registry.
	 *
	 * @return string|null The name of the registry.
	 * @since  5.0.4
	 */
	public function getName(): ?string;

	/**
	 * Sets the name of the registry.
	 *
	 * @param string|null $name The name to set.
	 *
	 * @return self
	 * @since  5.0.4
	 */
	public function setName(?string $name): self;

	/**
	 * Sets a separator value
	 *
	 * @param string|null   $value     The value to set.
	 *
	 * @return self
	 * @since  3.2.0
	 */
	public function setSeparator(?string $value): self;

	/**
	 * Gets the current path separator used in registry paths.
	 *
	 * @return string|null The path separator.
	 * @since  5.0.4
	 */
	public function getSeparator(): ?string;/***[/JCBGUI$$$$]***/

}

