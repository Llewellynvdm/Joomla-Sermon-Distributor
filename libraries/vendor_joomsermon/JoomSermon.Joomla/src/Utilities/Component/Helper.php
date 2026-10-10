<?php

/***[JCBGUI.power.licensing_template.23.$$$$]***/
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

namespace JoomSermon\Joomla\Utilities\Component;



/***[JCBGUI.power.head.23.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Database\DatabaseInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\String\NamespaceHelper;
use JoomSermon\Joomla\Utilities\StringHelper;


/**
 * Some component helper
 * 
 * @since  3.0.11
 */
abstract class Helper
{

/***[JCBGUI.power.main_class_code.23.$$$$]***/
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
	 * Sets a parameter value for the given target in the specified option's params.
	 * If no option is provided, it falls back to the default option.
	 *
	 * This method updates the parameters for a given extension in the database,
	 * only if the new value differs from the existing one.
	 *
	 * @param string      $target The parameter name to be updated.
	 * @param mixed       $value  The value to set for the parameter.
	 * @param string|null $option The optional extension element name. Defaults to null, which will use the default option.
	 *
	 * @return mixed The previous value of the parameter before it was updated.
	 * @since  5.0.3
	 */
	public static function setParams(string $target, $value, ?string $option = null)
	{
		// Ensure that an option is specified, defaulting to the system's option if not provided.
		if (empty($option))
		{
			$option = static::getOption();
		}

		// Retrieve current parameters for the specified option.
		$params = static::getParams($option);

		// Get the current value of the target parameter.
		$was = $params->get($target, null);

		// Only proceed if the new value differs from the current value.
		if ($was !== $value)
		{
			// Update the parameter value.
			$params->set($target, $value);

			// Obtain a database connection instance.
			$db = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true);

			// Build and execute the query to update the parameters in the database.
			$query->update('#__extensions AS a')
				  ->set('a.params = ' . $db->quote((string) $params))
				  ->where('a.element = ' . $db->quote((string) $option));

			$db->setQuery($query);
			$db->execute();
		}

		// Return the previous value of the parameter.
		return $was;
	}

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
			$option = static::getOption();
		}

		// get global value
		if (!isset(static::$params[$option]) || !static::$params[$option] instanceof Registry)
		{
			static::$params[$option] = ComponentHelper::getParams($option);
		}

		return static::$params[$option];
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
		static::$option = $option;
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
		if (empty(static::$option))
		{
			// get the option from the url input
			static::$option = (new Input)->getString('option', null);
		}

		if (empty(static::$option))
		{
			$app = Factory::getApplication();

			// Check if the getInput method exists in the application object
			if (method_exists($app, 'getInput'))
			{
				// get the option from the application
				static::$option = $app->getInput()->getCmd('option', $default);
			}
			else
			{
				// Use the default value if getInput method does not exist
				static::$option = $default;
			}
		}

		return static::$option;
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
			$option = static::getOption();
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
		if (($code_name = static::getCode($option, null)) !== null)
		{
			// we build the helper class name
			$helper_name = '\\' . \ucfirst($code_name) . 'Helper';

			// check if class exist
			if (class_exists($helper_name))
			{
				return $helper_name;
			}

			// try loading namespace
			if (($namespace = static::getNamespace($option)) !== null)
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
		$manifest = static::getManifest($option);

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
			&& ($option = static::getOption($option)) === null)
		{
			return null;
		}

		// get global manifest_cache values
		if (!isset(static::$manifest[$option]))
		{
			$db = Factory::getContainer()->get(DatabaseInterface::class);
			$query = $db->getQuery(true);

			$query->select($db->quoteName('manifest_cache'))
				  ->from($db->quoteName('#__extensions'))
				  ->where($db->quoteName('type') . ' = ' . $db->quote('component'))
				  ->where($db->quoteName('element') . ' LIKE ' . $db->quote($option));

			$db->setQuery($query);

			try {
				$manifest = $db->loadResult();
				static::$manifest[$option] = json_decode($manifest);
			} catch (\Exception $e) {
				// Handle the database error appropriately.
				static::$manifest[$option] = null;
			}
		}

		return static::$manifest[$option];
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
		return ($helper = static::get($option, null)) !== null &&
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
		if (($helper = static::get($option, null)) !== null &&
			method_exists($helper, $method))
		{
			// we know this is not ideal...
			// so we need to move these
			// functions to their own classes
			return call_user_func_array([$helper, $method],  $arguments);
		}

		return null;
	}

	/**
	 * Returns a Model object based on the specified type, prefix, and configuration.
	 *
	 * @param   string       $type     The model type to instantiate. Must not be empty.
	 * @param   string       $prefix   Prefix for the model class name. Optional, defaults to 'Administrator'.
	 * @param   string|null  $option   The component option. Optional, defaults to the component's option.
	 * @param   array        $config   Configuration array for the model. Optional, defaults to an empty array.
	 *
	 * @return  BaseDatabaseModel   The instantiated model object.
	 *
	 * @throws  \InvalidArgumentException  If the $type parameter is empty.
	 * @throws  \Exception                 For other errors that may occur during model creation.
	 *
	 * @since   5.0.3
	 */
	public static function getModel(string $type, string $prefix = 'Administrator',
		?string $option = null, array $config = []): BaseDatabaseModel
	{
		// Ensure the $type parameter is not empty
		if (empty($type))
		{
			throw new \InvalidArgumentException('The $type parameter cannot be empty when calling Component Helper getModel method.');
		}

		// Ensure the $option parameter is set, defaulting to the component's option if not provided
		if (empty($option))
		{
			$option = static::getOption();
		}

		// Normalize the model type name if the first character is not uppercase
		if (!ctype_upper($type[0]))
		{
			$type = StringHelper::safe($type, 'F');
		}

		// Normalize the prefix if it's not 'Site' or 'Administrator'
		if ($prefix !== 'Site' && $prefix !== 'Administrator')
		{
			$prefix = static::getPrefixFromModelPath($prefix);
		}

		// Instantiate and return the model using the MVCFactory
		return Factory::getApplication()
			->bootComponent($option)
			->getMVCFactory()
			->createModel($type, $prefix, $config);
	}

	/**
	 * Get the prefix from the model path
	 *
	 * @param   string  $path    The model path
	 *
	 * @return  string  The prefix value
	 * @since   5.0.3
	 */
	private static function getPrefixFromModelPath(string $path): string
	{
		// Check if $path starts with JPATH_ADMINISTRATOR path
		if (str_starts_with($path, JPATH_ADMINISTRATOR . '/components/'))
		{
			return 'Administrator';
		}
		// Check if $path starts with JPATH_SITE path
		elseif (str_starts_with($path, JPATH_SITE . '/components/'))
		{
			return 'Site';
		}
		return 'Administrator';
	}/***[/JCBGUI$$$$]***/

}

