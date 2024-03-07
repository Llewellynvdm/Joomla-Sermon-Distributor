<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    3rd September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace VDM\Joomla\Utilities\Component;


use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\Input\Input;
use Joomla\Registry\Registry;
use VDM\Joomla\Utilities\String\NamespaceHelper;


/**
 * Some component helper
 * 
 * @since  3.0.11
 */
abstract class Helper
{
	/**
	 * The current option
	 *
	 * @var    string|null
	 * @since   3.0.11
	 */
	public static ?string $option = null;

	/**
	 * The component manifest list cache
	 *
	 * @var    array
	 * @since   3.2.0
	 */
	public static array $manifest = [];

	/**
	 * The component params list cache
	 *
	 * @var    Registry[]
	 * @since   3.0.11
	 */
	protected static array $params = [];

	/**
	 * Gets the parameter object for the component
	 *
	 * @param   string|null     $option  The option for the component.
	 *
	 * @return  Registry     A Registry object.
	 * @see     Registry
	 * @since   3.0.11
	 */
	public static function getParams(?string $option = null): Registry
	{
		// check that we have an option
		if (empty($option))
		{
			$option = self::getOption();
		}

		// get global value
		if (!isset(self::$params[$option]) || !self::$params[$option] instanceof Registry)
		{
			self::$params[$option] = ComponentHelper::getParams($option);
		}

		return self::$params[$option];
	}

	/**
	 * Set the component option
	 *
	 * @param   string|null     $option  The option
	 *
	 * @return  void
	 * @since   3.2.0
	 */
	public static function setOption(?string $option): void
	{
		self::$option = $option;
	}

	/**
	 * Get the component option
	 *
	 * @param   string|null      $default  The default return value if none is found
	 *
	 * @return  string|null      A component option
	 * @since   3.0.11
	 */
	public static function getOption(?string $default = 'empty'): ?string
	{
		if (empty(self::$option))
		{
			// get the option from the url input
			self::$option = (new Input)->getString('option', null);
		}

		if (empty(self::$option))
		{
			$app = Factory::getApplication();

			// Check if the getInput method exists in the application object
			if (method_exists($app, 'getInput'))
			{
				// get the option from the application
				self::$option = $app->getInput()->getCmd('option', $default);
			}
			else
			{
				// Use the default value if getInput method does not exist
				self::$option = $default;
			}
		}

		return self::$option;
	}

	/**
	 * Gets the component code name
	 *
	 * @param   string|null    $option   The option for the component.
	 * @param   string|null    $default  The default return value if none is found
	 *
	 * @return  string|null    A component code name
	 * @since   3.0.11
	 */
	public static function getCode(?string $option = null, ?string $default = null): ?string
	{
		// check that we have an option
		if (empty($option))
		{
			$option = self::getOption();
		}
		// option with com_
		if (is_string($option) && strpos($option, 'com_') === 0)
		{
			return strtolower(trim(substr($option, 4)));
		}

		return $default;
	}

	/**
	 * Gets the component abstract helper class
	 *
	 * @param   string|null    $option   The option for the component.
	 * @param   string|null    $default  The default return value if none is found
	 *
	 * @return  string|null    A component helper name
	 *
	 * @since   3.0.11
	 */
	public static function get(?string $option = null, ?string $default = null): ?string
	{
		// check that we have an option
		// and get the code name from it
		if (($code_name = self::getCode($option, null)) !== null)
		{
			// we build the helper class name
			$helper_name = '\\' . \ucfirst($code_name) . 'Helper';

			// check if class exist
			if (class_exists($helper_name))
			{
				return $helper_name;
			}

			// try loading namespace
			if (($namespace = self::getNamespace($option)) !== null)
			{
				$name = \ucfirst($code_name) . 'Helper';
				$namespace_helper =  '\\' . $namespace . '\Administrator\Helper\\' . NamespaceHelper::safeSegment($name); // TODO target site or admin locations not just admin...
				if (class_exists($namespace_helper))
				{
					return $namespace_helper;
				}
			}
		}

		return $default;
	}

	/**
	 * Gets the component namespace if set
	 *
	 * @param   string|null    $option   The option for the component.
	 * @param   string|null    $default  The default return value if none is found
	 *
	 * @return  string|null    A component namespace
	 *
	 * @since   3.0.11
	 */
	public static function getNamespace(?string $option = null): ?string
	{
		$manifest = self::getManifest($option);

		return $manifest->namespace ?? null;
	}

	/**
	 * Gets the component abstract helper class
	 *
	 * @param   string|null    $option   The option for the component.
	 * @param   string|null    $default  The default return value if none is found
	 *
	 * @return  object|null    A component helper name
	 *
	 * @since   3.0.11
	 */
	public static function getManifest(?string $option = null): ?object
	{
		if ($option === null
			&& ($option = self::getOption($option)) === null)
		{
			return null;
		}

		// get global manifest_cache values
		if (!isset(self::$manifest[$option]))
		{
			$db = Factory::getDbo();
			$query = $db->getQuery(true);

			$query->select($db->quoteName('manifest_cache'))
				  ->from($db->quoteName('#__extensions'))
				  ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
				  ->where($db->quoteName('element') . ' LIKE ' . $db->quote($option));

			$db->setQuery($query);

			try {
				$manifest = $db->loadResult();
				self::$manifest[$option] = json_decode($manifest);
			} catch (\Exception $e) {
				// Handle the database error appropriately.
				self::$manifest[$option] = null;
			}
		}

		return self::$manifest[$option];
	}

	/**
	 * Check if the helper class of this component has a method
	 *
	 * @param   string       $method  The method name to search for
	 * @param   string|null  $option  The option for the component.
	 *
	 * @return  bool    true if method exist
	 *
	 * @since   3.0.11
	 */
	public static function methodExists(string $method, ?string $option = null): bool
	{
		// get the helper class
		return ($helper = self::get($option, null)) !== null &&
			method_exists($helper, $method);
	}

	/**
	 * Check if the helper class of this component has a method, and call it with the arguments
	 *
	 * @param   string        $method     The method name to search for
	 * @param   array         $arguments  The arguments for function.
	 * @param   string|null   $option     The option for the component.
	 *
	 * @return  mixed    return whatever the method returns or null
	 * @since   3.2.0
	 */
	public static function _(string $method, array $arguments = [], ?string $option = null)
	{
		// get the helper class
		if (($helper = self::get($option, null)) !== null &&
			method_exists($helper, $method))
		{
			// we know this is not ideal...
			// so we need to move these
			// functions to their own classes
			return call_user_func_array([$helper, $method],  $arguments);
		}

		return null;
	}

}

