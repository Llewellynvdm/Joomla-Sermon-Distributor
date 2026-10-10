<?php

/***[JCBGUI.power.licensing_template.764.$$$$]***/
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


use JoomSermon\Joomla\Interfaces\Data\SubformInterface as Subform;
use JoomSermon\Joomla\Interfaces\Data\MultiSubformInterface;


/**
 * CRUD the data of multi subform to another views (tables)
 * 
 * @since  3.2.2
 */
final class MultiSubform implements MultiSubformInterface
{

/***[JCBGUI.power.main_class_code.764.$$$$]***/
	/**
	 * The Subform Class.
	 *
	 * @var   Subform
	 * @since 3.2.2
	 */
	protected Subform $subform;

	/**
	 * Constructor.
	 *
	 * @param Subform     $subform   The Subform Class.
	 *
	 * @since 3.2.2
	 */
	public function __construct(Subform $subform)
	{
		$this->subform = $subform;
	}

	/**
	 * Get a subform items
	 *
	 * @param array   $getMap  The map to get the subfrom data
	 *
	 *     Example:
	 *        $getMap = [
	 *        	'_core' => [
	 *        		'table' =>'data',
	 *        		'linkValue' => $item->guid ?? '',
	 *        		'linkKey' => 'look',
	 *        		'field' => 'data',
	 *        		'get' => ['guid','email','image','mobile_phone','website','dateofbirth']
	 *        	],
	 *        	'countries' => [
	 *        		'table' =>'data_country',
	 *        		'linkValue' => 'data:guid', // coretable:fieldname
	 *        		'linkKey' => 'data',
	 *        		'get' => ['guid','country','currency']
	 *        	]
	 *        ];
	 *
	 * @return array|null   The subform
	 * @since 3.2.2
	 */
	public function get(array $getMap): ?array
	{
		// Validate the core map presence and structure
		if (!isset($getMap['_core']) || !is_array($getMap['_core']) || !$this->validGetMap($getMap['_core']))
		{
			return null;
		}

		// Initialize the core data
		$coreData = $this->getSubformData($getMap['_core']);

		// Return null if fetching core data fails
		if (null === $coreData)
		{
			return null;
		}
		$table = $getMap['_core']['table'];
		unset($getMap['_core']);

		// Recursively get data for all nested subforms
		return $this->getNestedSubforms($getMap, $coreData, $table);
	}

	/**
	 * Set a subform items
	 *
	 * @param mixed   $items    The list of items from the subform to set
	 * @param array   $setMap   The map to set the subfrom data
	 *
	 *     Example:
	 *        $items,
	 *        $setMap = [
	 *        	'_core' => [
	 *        		'table' => 'data',
	 *        		'indexKey' => 'guid',
	 *        		'linkKey' => 'look',
	 *        		'linkValue' => $data['guid'] ?? ''
	 *        	],
	 *        	'countries' => [
	 *        		'table' =>'data_country',
	 *        		'indexKey' => 'guid',
	 *        		'linkKey' => 'data',
	 *        		'linkValue' => 'data:guid' // coretable:fieldname
	 *        	]
	 *        ];
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function set(mixed $items, array $setMap): bool
	{
		// Validate the core map presence and structure
		if (!isset($setMap['_core']) || !is_array($setMap['_core']) || !$this->validSetMap($setMap['_core']))
		{
			return false;
		}

		// catch an empty set
		if (!is_array($items))
		{
			$items = []; // will delete all existing linked items :( not ideal, but real
		}
		else
		{
			// make sure the sub-subform:linkValue[data:guid]
			// is set with the needed key if possible
			// this ensures that new sub-subform data is correctly linked
			$this->prepLinkValue($items, $setMap);
		}

		// Save the core data
		if (!$this->setSubformData($items, $setMap['_core']))
		{
			return false;
		}
		$table = $setMap['_core']['table'];
		unset($setMap['_core']);

		// Recursively set data for all nested subforms
		return $this->setNestedSubforms($setMap, $items, $table);
	}

	/**
	 * Fetch data based on provided map configuration.
	 *
	 * @param array       $map       Map configuration
	 * @param array|null  $coreData  The core data to be appended with subform data
	 *
	 * @return array|null Fetched data or null on failure
	 * @since 3.2.2
	 */
	private function getSubformData(array $map, ?array $coreData = null): ?array
	{
		$map['linkValue'] = $this->setLinkValue($map['linkValue'], $coreData);

		if (empty($map['linkValue']) || strpos($map['linkValue'], ':') !== false)
		{
			return null;
		}

		return $this->subform->table($map['table'])->get(
			$map['linkValue'],
			$map['linkKey'],
			$map['field'],
			$map['get']
		);
	}

	/**
	 * Set data based on provided map configuration.
	 *
	 * @param array       $items     The list of items from the subform to set
	 * @param array       $map       The map to set the subfrom data
	 * @param array|null  $coreData  The core data to be appended with subform data
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function setSubformData(array $items, array $map, ?array $coreData = null): bool
	{
		$map['linkValue'] = $this->setLinkValue($map['linkValue'], $coreData);

		if (empty($map['linkValue']) || strpos($map['linkValue'], ':') !== false)
		{
			return false;
		}

		return $this->subform->table($map['table'])->set(
			$items,
			$map['indexKey'],
			$map['linkKey'],
			$map['linkValue']
		);
	}

	/**
	 * Set the linked value if needed, and posible.
	 *
	 * @param string      $linkValue   The current linkValue
	 * @param array|null  $data        The already found data as table => dataSet[field] => value
	 *
	 * @return string|null The actual linkValue
	 * @since 3.2.2
	 */
	private function setLinkValue(string $linkValue, ?array $data = null): ?string
	{
		if ($data !== null && strpos($linkValue, ':') !== false)
		{
			[$table, $field] = explode(':', $linkValue);
			$linkValue = $data[$table][$field] ?? null;
		}

		return $linkValue;
	}

	/**
	 * Recursively process additional subform data.
	 *
	 * @param array  $getMap       The nested map of data to process
	 * @param array  $subformData  The core subform data
	 * @param string $table        The core table
	 *
	 * @return array The core data with nested subforms included
	 * @since 3.2.2
	 */
	private function getNestedSubforms(array $getMap, array $subformData, string $table): array
	{
		foreach ($subformData as &$subform)
		{
			$subform = $this->processGetSubform($getMap, $subform, $table);
		}

		return $subformData;
	}

	/**
	 * Recursively process additional subform data.
	 *
	 * @param array  $setMap       The nested map of data to process
	 * @param array  $subformData  The core subform data
	 * @param string $table        The core table
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function setNestedSubforms(array $setMap, array $subformData, string $table): bool
	{
		$status = true;
		foreach ($subformData as $subform)
		{
			if (!$this->processSetSubform($setMap, $subform, $table))
			{
				$status = false;
			}
		}

		return $status;
	}

	/**
	 * Process each subform entry based on the map.
	 *
	 * @param array  $getMap    Mapping data for processing subforms
	 * @param array  $subform   A single subform entry
	 * @param string $table     The table name used for linking values
	 * 
	 * @return array Updated subform
	 * @since 3.2.2
	 */
	private function processGetSubform(array $getMap, array $subform, string $table): array
	{
		foreach ($getMap as $key => $map)
		{
			if (!is_array($map) || isset($subform[$key]))
			{
				continue;
			}

			$this->processGetMap($subform, $map, $key, $table);
		}

		return $subform;
	}

	/**
	 * Process each subform entry based on the map.
	 *
	 * @param array  $setMap    Mapping data for processing subforms
	 * @param array  $subform   A single subform entry
	 * @param string $table     The table name used for linking values
	 * 
	 * @return bool
	 * @since 3.2.2
	 */
	private function processSetSubform(array $setMap, array $subform, string $table): bool
	{
		$status = true;
		foreach ($setMap as $key => $map)
		{
			if (!is_array($map) || !isset($subform[$key]))
			{
				continue;
			}

			if (!$this->processSetMap($subform, $map, $key, $table))
			{
				$status = false;
			}
		}

		return $status;
	}

	/**
	 * Process a given map by either fetching nested subforms or handling them directly.
	 *
	 * @param array  &$subform Reference to subform data
	 * @param array  $map      Map configuration for subform processing
	 * @param string $key      Key associated with the map
	 * @param string $table    Core table name for linking values
	 *
	 * @return void
	 * @since 3.2.2
	 */
	private function processGetMap(array &$subform, array $map, string $key, string $table): void
	{
		if (isset($map['_core']))
		{
			$this->handleCoreGetMap($subform, $map, $key, $table);
		}
		else
		{
			$this->handleRegularGetMap($subform, $map, $key, $table);
		}
	}

	/**
	 * Process a given map by either setting nested subforms or handling them directly.
	 *
	 * @param array  $subform  Subform data
	 * @param array  $map      Map configuration for subform processing
	 * @param string $key      Key associated with the map
	 * @param string $table    Core table name for linking values
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function processSetMap(array $subform, array $map, string $key, string $table): bool
	{
		if (isset($map['_core']))
		{
			return $this->handleCoreSetMap($subform, $map, $key, $table);
		}

		return $this->handleRegularSetMap($subform, $map, $key, $table);
	}

	/**
	 * Handle the processing of '_core' maps in a subform.
	 *
	 * @param array  &$subform Reference to subform data
	 * @param array  $map      Map configuration for core subform processing
	 * @param string $key      Key associated with the map
	 * @param string $table    Core table name for linking values
	 *
	 * @return void
	 * @since 3.2.2
	 */
	private function handleCoreGetMap(array &$subform, array $map, string $key, string $table): void
	{
		if (is_array($map['_core']) && $this->validGetMap($map['_core']))
		{
			$map['_core']['linkValue'] = $this->setLinkValue($map['_core']['linkValue'], [$table => $subform]);

			$subCoreData = $this->get($map);
			if ($subCoreData !== null)
			{
				$subform[$key] = $subCoreData;
			}
		}
	}

	/**
	 * Handle the processing of '_core' maps in a subform.
	 *
	 * @param array  $subform  Subform data
	 * @param array  $map      Map configuration for core subform processing
	 * @param string $key      Key associated with the map
	 * @param string $table    Core table name for linking values
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function handleCoreSetMap(array $subform, array $map, string $key, string $table): bool
	{
		if (is_array($map['_core']) && $this->validGetMap($map['_core']))
		{
			$map['_core']['linkValue'] = $this->setLinkValue($map['_core']['linkValue'], [$table => $subform]);

			return $this->set($subform[$key], $map);
		}

		return false;
	}

	/**
	 * Handle the processing of regular maps in a subform.
	 *
	 * @param array   &$subform Reference to subform data
	 * @param array   $map      Map configuration for regular subform processing
	 * @param string  $key      Key associated with the map
	 * @param string  $table    Core table name for linking values
	 *
	 * @return void
	 * @since 3.2.2
	 */
	private function handleRegularGetMap(array &$subform, array $map, string $key, string $table): void
	{
		$map['field'] = $key;
		if ($this->validGetMap($map))
		{
			$subformData = $this->getSubformData($map, [$table => $subform]);
			if ($subformData !== null)
			{
				$subform[$key] = $subformData;
			}
		}
	}

	/**
	 * Handle the processing of regular maps in a subform.
	 *
	 * @param array   $subform  Subform data
	 * @param array   $map      Map configuration for regular subform processing
	 * @param string  $key      Key associated with the map
	 * @param string  $table    Core table name for linking values
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	private function handleRegularSetMap(array $subform, array $map, string $key, string $table): bool
	{
		if ($this->validSetMap($map))
		{
			// will delete all existing linked items [IF EMPTY] :( not ideal, but real
			$data = (empty($subform[$key]) || !is_array($subform[$key])) ? [] : $subform[$key];

			return $this->setSubformData($data, $map, [$table => $subform]);
		}

		return false;
	}

	/**
	 * Validate the get map configuration for fetching subform data.
	 * Ensures all required keys are present and have valid values.
	 *
	 * @param array  $map  The map configuration to validate.
	 *
	 * @return bool  Returns true if the map is valid, false otherwise.
	 * @since 3.2.2
	 */
	private function validGetMap(array $map): bool
	{
		// List of required keys with their expected types or validation functions
		$requiredKeys = [
			'table' => 'is_string',
			'linkValue' => 'is_string',
			'linkKey' => 'is_string',
			'field' => 'is_string',
			'get' => 'is_array'
		];

		// Iterate through each required key and validate
		foreach ($requiredKeys as $key => $validator)
		{
			if (empty($map[$key]) || !$validator($map[$key]))
			{
				return false; // Key missing or validation failed
			}
		}

		return true; // All checks passed
	}

	/**
	 * Validate the set map configuration for fetching subform data.
	 * Ensures all required keys are present and have valid values.
	 *
	 * @param array  $map  The map configuration to validate.
	 *
	 * @return bool  Returns true if the map is valid, false otherwise.
	 * @since 3.2.2
	 */
	private function validSetMap(array $map): bool
	{
		// List of required keys with their expected types or validation functions
		$requiredKeys = [
			'table' => 'is_string',
			'indexKey' => 'is_string',
			'linkKey' => 'is_string',
			'linkValue' => 'is_string'
		];

		// Iterate through each required key and validate
		foreach ($requiredKeys as $key => $validator)
		{
			if (empty($map[$key]) || !$validator($map[$key]))
			{
				return false; // Key missing or validation failed
			}
		}

		return true; // All checks passed
	}

	/**
	 * Prepare the linkValue needed by the sub-subform
	 *
	 * @param array  $subform   The subform data
	 * @param array  $setMap    Mapping data for processing subforms
	 * 
	 * @return void
	 * @since  5.0.3
	 */
	private function prepLinkValue(array &$subform, array $setMap): void
	{
		$code_table = null;
		foreach ($setMap as $key => $map)
		{
			if ($key === '_core')
			{
				$code_table = $map['table'] ?? null;
				continue;
			}

			if (strpos($map['linkValue'], ':') !== false)
			{
				[$table, $field] = explode(':', $map['linkValue']);
				if ($code_table !== null &&
					'guid' === $field &&
					$table === $code_table)
				{
					foreach ($subform as &$row)
					{
						if (empty($row['guid']))
						{
							$row['guid'] = $this->subform->table($table)->getGuid($field);
						}
					}
				}
			}
		}
	}/***[/JCBGUI$$$$]***/

}

