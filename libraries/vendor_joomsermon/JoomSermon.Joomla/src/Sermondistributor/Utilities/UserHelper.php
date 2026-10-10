<?php

/***[JCBGUI.power.licensing_template.821.$$$$]***/
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

namespace JoomSermon\Joomla\Sermondistributor\Utilities;



/***[JCBGUI.power.head.821.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Language\Text;
use Joomla\CMS\User\User;
use Joomla\CMS\User\UserHelper as JoomlaUserHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\Component\Helper as Component;
use JoomSermon\Joomla\Utilities\ArrayHelper;
use JoomSermon\Joomla\Utilities\StringHelper;
use JoomSermon\Joomla\Sermondistributor\Utilities\Exception\NoUserIdFoundException;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;


/**
 * Create & Update User [Save]
 * 
 * @since  5.0.2
 */
abstract class UserHelper
{

/***[JCBGUI.power.main_class_code.821.$$$$]***/
	/**
	 * Save user details by either creating a new user or updating an existing user.
	 *
	 * @param   array  $credentials  User credentials including 'name', 'username', 'email', 'password', and 'password2'.
	 * @param   int    $autologin    Flag to determine whether to auto-login the user after registration.
	 * @param   array  $params       Parameters for user activation, password sending, and user registration allowance.
	 * @param   int    $mode         Mode of registration: 1 = Site Registration, 0 = Admin Registration, 2 = Custom Helper Method.
	 *
	 * @return  int  User ID on success.
	 *
	 * @throws  \InvalidArgumentException  If required credentials are missing.
	 * @throws  \RuntimeException          If the user update or creation fails.
	 * @throws  NoUserIdFoundException     If the user is not found.
	 *
	 * @since   5.0.3
	 */
	public static function save(array $credentials, int $autologin = 0,
		array $params = ['useractivation' => 0, 'sendpassword' => 1], int $mode = 1): int
	{
		// can not continue without an email
		if (empty($credentials['email']))
		{
			throw new \InvalidArgumentException(Text::_('COM_SERMONDISTRIBUTOR_CAN_NOT_SAVE_USER_WITHOUT_EMAIL_VALUE'));
		}

		// Ensure the 'username' key exists in the credentials array, set to an empty string if not provided.
		$username = $credentials['username'] ?? $credentials['email'];

		// If the user's ID is set and valid, handle the update logic.
		if (!empty($credentials['id']) && $credentials['id'] > 0)
		{
			$userId = $credentials['id'];
			$email = $credentials['email'];

			// Fetch existing user by email and username.
			$existingEmailUserId = static::getUserIdByEmail($email);
			$existingUsernameId = static::getUserIdByUsername($username);

			// Validate that we aren't attempting to update other users or reuse another user's email/username.
			if (
				($existingEmailUserId && $existingEmailUserId != $userId) ||
				($existingUsernameId && $existingUsernameId != $userId) ||
				($existingEmailUserId && $existingUsernameId && $existingEmailUserId != $existingUsernameId)
			) {
				throw new NoUserIdFoundException(
					Text::sprintf(
						'User ID mismatch detected when trying to save %s (%s) credentials.',
						$username,
						$email
					)
				);
			}

			// Update the existing user.
			return static::update($credentials);
		}

		// Create a new user if no existing user is found.
		return static::create($credentials, $autologin, $params, $mode);
	}

	/**
	 * Create a user and update the given table.
	 *
	 * @param   array  $credentials  User credentials including 'name', 'username', 'email', 'password', and 'password2'.
	 * @param   int    $autologin    Flag to determine whether to auto-login the user after registration.
	 * @param   array  $params       Parameters for user activation, password sending, and user registration allowance.
	 * @param   int    $mode         Mode of registration: 1 = Site Registration, 0 = Admin Registration, 2 = Custom Helper Method.
	 *
	 * @return  int User ID on success.
	 *
	 * @throws  \RuntimeException       If user creation fails.
	 * @throws  NoUserIdFoundException  If the user is not found.
	 *
	 * @since   5.0.3
	 */
	public static function create(array $credentials, int $autologin = 0,
		array $params = ['useractivation' => 0, 'sendpassword' => 1], int $mode = 1): int
	{
		$lang = Factory::getLanguage();
		$lang->load('com_users', JPATH_SITE, 'en-GB', true);

		// Handle custom registration mode
		if ($mode === 2 && method_exists(SermondistributorHelper::class, 'registerUser'))
		{
			$params['autologin'] = $autologin;
			$userId = SermondistributorHelper::registerUser($credentials, $params);

			if (is_numeric($userId))
			{
				return $userId;
			}

			throw new NoUserIdFoundException(Text::_('COM_SERMONDISTRIBUTOR_USER_CREATION_FAILED'));
		}

		// Check if we have params/config
		if (ArrayHelper::check($params))
		{
			// Make changes to user config
			foreach ($params as $param => $set)
			{
				// If you know of a better path, let me know
				$params[$param] = Component::setParams($param, $set, 'com_users');
			}
		}

		// Fallback to Site Registrations if mode is set to 2 but the method doesn't exist
		$mode = $mode === 2 ? 1 : $mode;

		// Load the appropriate user model
		$model = static::getModelByMode($mode);

		// Set default values for missing credentials
		$credentials['username'] = $credentials['username'] ?? $credentials['email'];

		// Prepare user data
		$data = static::prepareUserData($credentials, $mode);

		// Set form path (bug fix for Joomla)
		static::setFormPathForUserClass($mode);

		// Handle user creation
		$userId = $mode === 1 ? $model->register($data) : static::adminRegister($model, $data);

		// Check if we have params
		if (ArrayHelper::check($params))
		{
			// Change user params/config back
			foreach ($params as $param => $set)
			{
				// If you know of a better path, let me know
				Component::setParams($param, $set, 'com_users');
			}
		}

		if (!$userId)
		{
			$current_user = Factory::getApplication()->getIdentity();

			// only allow those with access to Users to ignore errors
			if ($current_user->authorise('core.manage', 'com_users'))
			{
				$userId = static::getUserIdByUsername($credentials['username']);
			}
		}

		if (is_numeric($userId) && $userId > 0)
		{
			// Handle post-registration processes
			return static::handlePostRegistration($userId, $autologin, $credentials);
		}

		$error_messages = '';
		if (method_exists($model, 'getError'))
		{
			$errors = $model->getError();
			if (!empty($errors))
			{
				if (is_array($errors))
				{
					$error_messages = '<br>' . implode('<br>', $errors);
				}
				elseif (is_string($errors))
				{
					$error_messages = '<br>' . $errors;
				}
			}
		}

		throw new NoUserIdFoundException(
			Text::sprintf('COM_SERMONDISTRIBUTOR_USER_S_S_CREATION_FAILEDS',
				(string) $credentials['username'],
				(string) $credentials['email'],
				$error_messages
			)
		);
	}

	/**
	 * Update user details.
	 *
	 * @param   array  $userDetails  Array containing user details to be updated.
	 *
	 * @return  int   Updated user ID on success.
	 *
	 * @throws  \RuntimeException  If user update fails.
	 *
	 * @since   5.0.3
	 */
	public static function update(array $userDetails): int
	{
		$lang = Factory::getLanguage();
		$lang->load('com_users', JPATH_ADMINISTRATOR, 'en-GB', true);

		$model = Component::getModel('User', 'Administrator', 'com_users');

		// Set default values for missing credentials
		$userDetails['username'] = $userDetails['username'] ?? $userDetails['email'];

		// Prepare user data for update
		$data = [
			'id' => $userDetails['id'],
			'username' => $userDetails['username'],
			'name' => $userDetails['name'],
			'email' => $userDetails['email'],
			'password' => $userDetails['password'] ?? null,
			'password2' => $userDetails['password2'] ?? null,
			'block' => 0
		];

		// set groups if found
		if (isset($userDetails['groups']) && ArrayHelper::check($userDetails['groups']))
		{
			$data['groups'] = $userDetails['groups'];
		}

		// Update the user
		if ($model->save($data))
		{
			return $userDetails['id'];
		}

		$error_messages = '';
		if (method_exists($model, 'getError'))
		{
			$errors = $model->getError();
			if (!empty($errors))
			{
				if (is_array($errors))
				{
					$error_messages = '<br>' . implode('<br>', $errors);
				}
				elseif (is_string($errors))
				{
					$error_messages = '<br>' . $errors;
				}
			}
		}

		throw new \RuntimeException(
			Text::sprintf('COM_SERMONDISTRIBUTOR_UPDATE_OF_USER_S_S_FAILEDS',
				(string) $userDetails['username'],
				(string) $userDetails['email'],
				(string) $error_messages
			)
		);
	}

	/**
	 * Method to get an instance of a user for the given id.
	 *
	 * @param   int  $id  The id
	 *
	 * @return  User
	 *
	 * @since   5.0.3
	 */
	public static function getUserById(int $id): User
	{
		 return new User($id);
	}

	/**
	 * Retrieve the user ID by username.
	 *
	 * @param   string  $username  The username to check.
	 *
	 * @return  int|null  The user ID if the user exists, null otherwise.
	 *
	 * @since   5.0.3
	 */
	public static function getUserIdByUsername(string $username): ?int
	{
		$userId = JoomlaUserHelper::getUserId($username);
		return $userId ?: null;
	}

	/**
	 * Retrieve the user ID by email.
	 *
	 * @param   string  $email  The email address to check.
	 *
	 * @return  int|null  The user ID if the user exists, null otherwise.
	 *
	 * @since   5.0.3
	 */
	public static function getUserIdByEmail(string $email): ?int
	{
		// Initialise some variables
		$db = Factory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__users'))
			->where($db->quoteName('email') . ' = :email')
			->bind(':email', $email)
			->setLimit(1);
		$db->setQuery($query);

		$userId = $db->loadResult();
		return $userId ?: null;
	}

	/**
	 * Load the correct user model based on the registration mode.
	 *
	 * @param   int  $mode  The registration mode.
	 *
	 * @return  BaseDatabaseModel  The appropriate user model.
	 *
	 * @since   5.0.3
	 */
	protected static function getModelByMode(int $mode): BaseDatabaseModel
	{
		if ($mode === 1)
		{
			return Component::getModel('Registration', 'Site', 'com_users');
		}

		return Component::getModel('User', 'Administrator', 'com_users');
	}

	/**
	 * Prepare user data array for registration or update.
	 *
	 * @param   array  $credentials  User credentials.
	 * @param   int    $mode         The registration mode.
	 *
	 * @return  array  The prepared user data array.
	 *
	 * @since   5.0.3
	 */
	protected static function prepareUserData(array $credentials, int $mode)
	{
		$data = [
			'username' => $credentials['username'],
			'name' => $credentials['name'],
			'block' => 0
		];

		if ($mode === 1)
		{
			$data['email1'] = $credentials['email'];
		}
		else
		{
			$data['email'] = $credentials['email'];
			$data['registerDate'] = Factory::getDate()->toSql();
		}

		if ($mode === 1 && empty($credentials['password']))
		{
			$credentials['password'] = StringHelper::random(10);
			$credentials['password2'] = $credentials['password'];
		}

		if (!empty($credentials['password']) && !empty($credentials['password2']))
		{
			$data['password1'] = $credentials['password'];
			$data['password2'] = $credentials['password2'];
		}

		if ($mode === 0 && isset($credentials['groups']) && ArrayHelper::check($credentials['groups']))
		{
			$data['groups'] = $credentials['groups'];
		}

		return $data;
	}

	/**
	 * Handle the registration process for admin mode.
	 *
	 * @param   BaseDatabaseModel  $model  The user model.
	 * @param   array              $data   The user data.
	 *
	 * @return  int  The ID of the created user.
	 *
	 * @since   5.0.3
	 */
	private static function adminRegister(BaseDatabaseModel $model, array $data): int
	{
		$model->save($data);

		return $model->getState('user.id', 0);
	}

	/**
	 * Handle post-registration processes like auto-login.
	 *
	 * @param   int    $userId      The ID of the created user.
	 * @param   int    $autologin   Flag to determine whether to auto-login the user.
	 * @param   array  $credentials The user credentials.
	 *
	 * @return  int The user ID on success.
	 *
	 * @since   5.0.3
	 */
	private static function handlePostRegistration(int $userId, int $autologin, array $credentials): int
	{
		// make sure user is it the correct groups
		if ($userId > 0 && !empty($credentials['groups']))
		{
			try
			{
				JoomlaUserHelper::setUserGroups($userId, $credentials['groups']);
			}
			catch (\Exception $e)
			{
				// we might need say something
			}
		}

		if ($autologin && !empty($credentials['password']))
		{
			try
			{
				Factory::getApplication()->login($credentials);
			}
			catch (\Exception $e)
			{
				// we might need to redirect here?
			}
		}

		return $userId;
	}

	/**
	 * Address bug on \Joomla\CMS\MVC\Model\FormBehaviorTrait Line 76
	 *   The use of JPATH_COMPONENT cause it to load the
	 *   active component forms and fields, which breaks the registration model.
	 *
	 * @param int  $mode
	 *
	 * @since 5.0.3
	 */
	private static function setFormPathForUserClass(int $mode): void
	{
		if ($mode == 1) // 1 = use of the Registration Model
		{
			// Get the form.
			Form::addFormPath(JPATH_ROOT . '/components/com_users/forms');
		}
	}/***[/JCBGUI$$$$]***/

}

