<?php
/*-------------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
 ____                                                  ____                 __               __               __
/\  _`\                                               /\  _`\   __         /\ \__         __/\ \             /\ \__
\ \,\L\_\     __   _ __    ___ ___     ___     ___    \ \ \/\ \/\_\    ____\ \ ,_\  _ __ /\_\ \ \____  __  __\ \ ,_\   ___   _ __
 \/_\__ \   /'__`\/\`'__\/' __` __`\  / __`\ /' _ `\   \ \ \ \ \/\ \  /',__\\ \ \/ /\`'__\/\ \ \ '__`\/\ \/\ \\ \ \/  / __`\/\`'__\
   /\ \L\ \/\  __/\ \ \/ /\ \/\ \/\ \/\ \L\ \/\ \/\ \   \ \ \_\ \ \ \/\__, `\\ \ \_\ \ \/ \ \ \ \ \L\ \ \ \_\ \\ \ \_/\ \L\ \ \ \/
   \ `\____\ \____\\ \_\ \ \_\ \_\ \_\ \____/\ \_\ \_\   \ \____/\ \_\/\____/ \ \__\\ \_\  \ \_\ \_,__/\ \____/ \ \__\ \____/\ \_\
    \/_____/\/____/ \/_/  \/_/\/_/\/_/\/___/  \/_/\/_/    \/___/  \/_/\/___/   \/__/ \/_/   \/_/\/___/  \/___/   \/__/\/___/  \/_/

/------------------------------------------------------------------------------------------------------------------------------------/

	@version		6.0.0
	@created		9th July, 2026
	@package		Sermon Distributor
	@subpackage		Sermon_languageController.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormFactoryInterface;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\Input\Input;
use Joomla\CMS\Versioning\VersionableControllerTrait;
use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Utilities\GuidHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermon_language Form Controller
 *
 * @since  1.6
 */
class Sermon_languageController extends FormController
{
	use VersionableControllerTrait;

	/**
	 * The prefix to use with controller messages.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $text_prefix = 'COM_SERMONDISTRIBUTOR_SERMON_LANGUAGE';

	/**
	 * Current or most recently performed task.
	 *
	 * @var    string
	 * @since  12.2
	 * @note   Replaces _task.
	 */
	protected $task;

	/**
	 * The URL view list variable.
	 *
	 * @var    string
	 * @since  1.6
	 */
	protected $view_list = 'sermon_languages';

	/**
	 * Referral value
	 *
	 * @var    string
	 * @since  5.0
	 */
	protected string $ref;

	/**
	 * Referral ID value
	 *
	 * @var    int
	 * @since  5.0
	 */
	protected int $refid;

/***[INSERTED$$$$]***//**412**/
	/**
	 * Method to edit an existing record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key
	 *                           (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if access level check and checkout passes, false otherwise.
	 *
	 * @since   1.6
	 */
	public function edit($key = null, $urlVar = null)
	{
		// for modal title key selection (unique key to do mapping)
		$titleKey = $this->input->get('titleKey', 'id', 'word');
		$guid = null;
		$value = null;

 		// Determine the name of the primary key for the data.
		if (empty($key))
		{
			$model = $this->getModel();
			$table = $model->getTable();
			$key = $table->getKeyName();
		}

		if ($titleKey === 'guid')
		{
			$guid = $this->input->get('guid', null, 'string');
		}

		if ($guid !== null && GuidHelper::valid($guid))
		{
			$value = GuidHelper::item($guid, 'sermon_language', 'a.' . $key, 'sermondistributor');
		}

		if ($value !== null)
		{
			$this->input->set($key, $value);
		}

		return parent::edit($key, $urlVar);
	}/***[/INSERTED$$$$]***/

	/**
	 * Method override to check if you can add a new record.
	 *
	 * @param   array  $data  An array of input data.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowAdd($data = [])
	{
		// Get user object.
		$user = $this->app->getIdentity();
		// Access check.
		$access = $user->authorise('sermon_language.access', 'com_sermondistributor');
		if (!$access)
		{
			return false;
		}

		// In the absence of better information, revert to the component permissions.
		return $user->authorise('sermon_language.create', $this->option);
	}

	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param   array   $data  An array of input data.
	 * @param   string  $key   The name of the key for the primary key.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	protected function allowEdit($data = [], $key = 'id')
	{
		// get user object.
		$user = $this->app->getIdentity();
		// get record id.
		$recordId = isset($data[$key]) ? (int) $data[$key] : 0;


		// Access check.
		$access = ($user->authorise('sermon_language.access', 'com_sermondistributor.sermon_language.' . (int) $recordId) && $user->authorise('sermon_language.access', 'com_sermondistributor'));
		if (!$access)
		{
			return false;
		}

		if ($recordId)
		{
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('sermon_language.edit', 'com_sermondistributor.sermon_language.' . (int) $recordId);
			if (!$permission)
			{
				if ($user->authorise('sermon_language.edit.own', 'com_sermondistributor.sermon_language.' . (int) $recordId))
				{
					// Now test the owner is the user.
					// The owner comes from the stored record, never from $data.
					$record = $this->getModel()->getItem($recordId);

					if (empty($record))
					{
						return false;
					}
					$ownerId = (int) $record->created_by;

					// If the owner matches 'me' then allow.
					if ($ownerId == $user->id)
					{
						if ($user->authorise('sermon_language.edit.own', 'com_sermondistributor'))
						{
							return true;
						}
					}
				}
				return false;
			}
		}
		// Since there is no permission, revert to the component permissions.
		return $user->authorise('sermon_language.edit', $this->option);
	}

	/**
	 * Gets the URL arguments to append to an item redirect.
	 *
	 * @param   integer  $recordId  The primary key id for the item.
	 * @param   string   $urlVar    The name of the URL variable for the id.
	 *
	 * @return  string  The arguments to append to the redirect URL.
	 *
	 * @since   1.6
	 */
	protected function getRedirectToItemAppend($recordId = null, $urlVar = 'id')
	{
		// get int-defaults (to int new items with default values dynamically)
		$init_defaults = $this->input->get('init_defaults', null, 'STRING');

		// get the referral options (old method use init_defaults or return instead see parent)
		$ref = $this->input->get('ref', 0, 'string');
		$refid = $this->input->get('refid', 0, 'int');

		// get redirect info.
		$append = parent::getRedirectToItemAppend($recordId, $urlVar);

		// set int-defaults
		if (!empty($init_defaults))
		{
			$append = '&init_defaults='. (string) $init_defaults . $append;
		}

		// set the referral options
		if ($refid && $ref)
		{
			$append = '&ref=' . (string) $ref . '&refid='. (int) $refid . $append;
		}
		elseif ($ref)
		{
			$append = '&ref='. (string) $ref . $append;
		}

		return $append;
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 *
	 * @since   12.2
	 */
	public function cancel($key = null)
	{
		// get the referral options
		$this->ref = $this->input->get('ref', 0, 'word');
		$this->refid = $this->input->get('refid', 0, 'int');

		// Check if there is a return value
		$return = $this->input->get('return', null, 'base64');

		$cancel = parent::cancel($key);

		if (!is_null($return) && Uri::isInternal(base64_decode($return)))
		{
			$redirect = base64_decode($return);

			// Redirect to the return value.
			$this->setRedirect(
				Route::_(
					$redirect, false
				)
			);
		}
		elseif ($this->refid && $this->ref)
		{
			$redirect = '&view=' . (string)$this->ref . '&layout=edit&id=' . (int)$this->refid;

			// Redirect to the item screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		elseif ($this->ref)
		{
			$redirect = '&view='.(string)$this->ref;

			// Redirect to the list screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		// When editing in modal then redirect to modalreturn layout
		elseif ($cancel && $this->input->get('layout') === 'modal')
		{
			$id = $this->input->get('id');
			$return = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($id)
				. '&layout=modalreturn&from-task=cancel';

			$this->setRedirect(Route::_($return, false));
		}
		return $cancel;
	}

	/**
	 * Method to save a record.
	 *
	 * @param   string  $key     The name of the primary key of the URL variable.
	 * @param   string  $urlVar  The name of the URL variable if different from the primary key (sometimes required to avoid router collisions).
	 *
	 * @return  boolean  True if successful, false otherwise.
	 *
	 * @since   12.2
	 */
	public function save($key = null, $urlVar = null)
	{
		// get the referral options
		$this->ref = $this->input->get('ref', 0, 'word');
		$this->refid = $this->input->get('refid', 0, 'int');

		// Check if there is a return value
		$return = $this->input->get('return', null, 'base64');
		$canReturn = (!is_null($return) && Uri::isInternal(base64_decode($return)));

		if ($this->ref || $this->refid || $canReturn)
		{
			// to make sure the item is checkedin on redirect
			$this->task = 'save';
		}

		$saved = parent::save($key, $urlVar);

		// This is not needed since parent save already does this
		// Due to the ref and refid implementation we need to add this
		if ($canReturn)
		{
			$redirect = base64_decode($return);

			// Redirect to the return value.
			$this->setRedirect(
				Route::_(
					$redirect, false
				)
			);
		}
		elseif ($this->refid && $this->ref)
		{
			$redirect = '&view=' . (string) $this->ref . '&layout=edit&id=' . (int) $this->refid;

			// Redirect to the item screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		elseif ($this->ref)
		{
			$redirect = '&view=' . (string) $this->ref;

			// Redirect to the list screen.
			$this->setRedirect(
				Route::_(
					'index.php?option=' . $this->option . $redirect, false
				)
			);
		}
		return $saved;
	}

	/**
	 * Function that allows child controller access to model data
	 * after the data has been saved.
	 *
	 * @param   BaseDatabaseModel  $model     The data model object.
	 * @param   array              $validData  The validated data.
	 *
	 * @return  void
	 *
	 * @since   11.1
	 */
	protected function postSaveHook(BaseDatabaseModel $model, $validData = [])
	{
		if ($this->input->get('layout') === 'modal' && $this->task === 'save')
		{
			// When editing in modal then redirect to modalreturn layout
			$id = $model->getState('sermon_language.id', '');
			$return = 'index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($id)
				. '&layout=modalreturn&from-task=save';

			$this->setRedirect(Route::_($return, false));
		}
		return;
	}
}
