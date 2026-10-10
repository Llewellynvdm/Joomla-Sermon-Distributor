<?php

/***[JCBGUI.power.licensing_template.823.$$$$]***/
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


/**
 * Globally Unique Identifier
 * 
 * @since  5.0.2
 */
trait Guid
{

/***[JCBGUI.power.main_class_code.823.$$$$]***/
	/**
	 * Returns a GUIDv4 string.
	 * 
	 * This function uses the best cryptographically secure method
	 * available on the platform with a fallback to an older, less secure version.
	 *
	 * @param string $key The key to check and modify values.
	 *
	 * @return string A GUIDv4 string.
	 *
	 * @since 5.0.2
	 */
	public function getGuid(string $key): string
	{
		// Windows: Use com_create_guid if available
		if (function_exists('com_create_guid'))
		{
			$guid = trim(\com_create_guid(), '{}');
			return $this->checkGuid($guid, $key);
		}

		// Unix-based systems: Use openssl_random_pseudo_bytes if available
		if (function_exists('random_bytes'))
		{
			try {
				$data = random_bytes(16);
			} catch (\Exception $e) {
				// Handle the error appropriately (logging, throwing, etc.)
				return $this->fallbackGuid($key);
			}

			// Set the version to 0100 and the bits 6-7 to 10 as per RFC 4122
			$data[6] = chr(ord($data[6]) & 0x0f | 0x40);
			$data[8] = chr(ord($data[8]) & 0x3f | 0x80);

			$guid = vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
			return $this->checkGuid($guid, $key);
		}

		// Fallback to older methods if secure methods are not available
		return $this->fallbackGuid($key);
	}

	/**
	 * Validate the Globally Unique Identifier
	 *
	 * @param string $guid
	 *
	 * @return bool
	 * @since  5.0.4
	 */
	public static function validateGuid($guid)
	{
		// check if we have a string
		if (!empty($guid) && is_string($guid))
		{
			return preg_match("/^(\{)?[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}(?(1)\})$/i", $guid);
		}
		return false;
	}

	/**
	 * Checks if the GUID value is unique and does not already exist.
	 *
	 * @param string $guid The GUID value to check.
	 * @param string $key  The key to check and modify values.
	 *
	 * @return string The unique GUID value.
	 *
	 * @since 5.0.2
	 */
	protected function checkGuid(string $guid, string $key): string
	{
		// Check that the GUID does not already exist
		if ($this->items->table($this->getTable())->values([$guid], $key))
		{
			return $this->getGuid($key);
		}

		return $guid;
	}

	/**
	 * Generates a fallback GUIDv4 using less secure methods.
	 *
	 * @param string $key The key to check and modify values.
	 *
	 * @return string A GUIDv4 string.
	 *
	 * @since 5.0.2
	 */
	private function fallbackGuid(string $key): string
	{
		$charid = strtolower(md5(uniqid(random_int(0, PHP_INT_MAX), true)));
		$guidv4 = sprintf(
			'%s-%s-%s-%s-%s',
			substr($charid,  0, 8),
			substr($charid,  8, 4),
			substr($charid, 12, 4),
			substr($charid, 16, 4),
			substr($charid, 20, 12)
		);

		return $this->checkGuid($guidv4, $key);
	}/***[/JCBGUI$$$$]***/

}

