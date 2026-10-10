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
	@subpackage		AddressModel.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Table\Table;
use Joomla\CMS\UCM\UCMType;
use Joomla\CMS\Versioning\VersionableModelTrait;
use Joomla\CMS\User\User;
use Joomla\Registry\Registry;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;
use Joomla\Input\Input;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use Joomla\CMS\Helper\TagsHelper;
use JoomSermon\Joomla\Utilities\GuidHelper;
use JoomSermon\Joomla\Utilities\GetHelper;
use JoomSermon\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use Joomla\CMS\Access\Exception\NotAllowed;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor Address Admin Model
 *
 * @since  1.6
 */
class AddressModel extends AdminModel
{
	use VersionableModelTrait;

	/**
	 * The tab layout fields array.
	 *
	 * @var    array
	 * @since  3.0.0
	 */
	protected $tabLayoutFields = array(
		'details' => array(
			'left' => array(
				'line_one',
				'line_two',
				'type'
			),
			'right' => array(
				'country',
				'state',
				'city',
				'postal'
			),
			'above' => array(
				'preacher',
				'church'
			)
		)
	);

	/**
	 * The styles array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $styles = [
		'administrator/components/com_sermondistributor/assets/css/admin.css',
		'administrator/components/com_sermondistributor/assets/css/address.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'administrator/components/com_sermondistributor/assets/js/admin.js',
		'media/com_sermondistributor/js/address.js'
 	];

	/**
	 * @var     string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_SERMONDISTRIBUTOR';

	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_sermondistributor.address';

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  Table  A database object
	 * @since   3.0
	 * @throws  \Exception
	 */
	public function getTable($type = 'address', $prefix = 'Administrator', $config = [])
	{
		// get instance of the table
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			if (property_exists($item, 'metadata') && !is_array($item->metadata))
			{
				// Convert the metadata field to an array.
				$metadata       = new Registry($item->metadata);
				$item->metadata = $metadata->toArray();
			}

			// API reads use read permissions; administrator editing keeps its edit guard.
			if (!empty($item->id))
			{
				$app = Factory::getApplication();

				if ($app->isClient('api'))
				{
					$user = $app->getIdentity();
					if (!($user->authorise('address.access', 'com_sermondistributor.address.' . $item->id) && $user->authorise('address.access', 'com_sermondistributor')) || (!$user->authorise('core.options', 'com_sermondistributor') && !in_array((int) $item->access, $user->getAuthorisedViewLevels())))
					{
						throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
					}
				}
				elseif (!$this->allowEdit((array) $item))
				{
					$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'error');
					$app->redirect('index.php?option=com_sermondistributor');
					return false;
				}
			}
		}

		return $item;
	}

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 * @param   array    $options   Optional array of options for the form creation.
	 *
	 * @return  Form|boolean  A Form object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = [], $loadData = true, $options = ['control' => 'jform'])
	{
		// set load data option
		$options['load_data'] = $loadData;
		// check if xpath was set in options
		$xpath = false;
		if (isset($options['xpath']))
		{
			$xpath = $options['xpath'];
			unset($options['xpath']);
		}
		// check if clear form was set in options
		$clear = false;
		if (isset($options['clear']))
		{
			$clear = $options['clear'];
			unset($options['clear']);
		}

		// Get the form.
		$form = $this->loadForm('com_sermondistributor.address', 'address', $options, $clear, $xpath);

		if (empty($form))
		{
			return false;
		}

		$app = Factory::getApplication();

		$jinput = method_exists($app, 'getInput') ? $app->getInput() : $app->input;

		// The record being saved decides the permissions, so its id wins over the request.
		if (is_array($data) && isset($data['id']) && (int) $data['id'] > 0)
		{
			$id = (int) $data['id'];
		}
		// The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		elseif ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}
		// The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}

		$user = Factory::getApplication()->getIdentity();

		// Check for existing item.
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('address.edit.state', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.state', 'com_sermondistributor')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			// Disable fields while saving.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}
		// If this is a new item insure the greated by is set.
		if (0 == $id)
		{
			// Set the created_by to this user
			$form->setValue('created_by', null, $user->id);
		}
		// Modify the form based on Edit Creaded By access controls.
		if ($id != 0 && (!$user->authorise('address.edit.created_by', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.created_by', 'com_sermondistributor')))
		{
			if ($app->isClient('api'))
			{
				// Exclude protected metadata from API validation and binding.
				$form->removeField('created_by');
			}
			else
			{
				// Retain disabled metadata controls in administrator forms.
				$form->setFieldAttribute('created_by', 'disabled', 'true');
				$form->setFieldAttribute('created_by', 'readonly', 'true');
				$form->setFieldAttribute('created_by', 'filter', 'unset');
			}
		}
		// Modify the form based on Edit Creaded Date access controls.
		if ($id != 0 && (!$user->authorise('address.edit.created', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.created', 'com_sermondistributor')))
		{
			if ($app->isClient('api'))
			{
				// Exclude protected metadata from API validation and binding.
				$form->removeField('created');
			}
			else
			{
				// Retain disabled metadata controls in administrator forms.
				$form->setFieldAttribute('created', 'disabled', 'true');
				$form->setFieldAttribute('created', 'filter', 'unset');
			}
		}

		// Omitted PATCH metadata must not be filtered or rebound from storage.
		if ($app->isClient('api') && $jinput->getMethod() === 'PATCH')
		{
			$submittedApiData = $jinput->get('data', json_decode($jinput->json->getRaw(), true), 'array');
			foreach (['created', 'created_by'] as $metadataField)
			{
				if (!is_array($submittedApiData) || !array_key_exists($metadataField, $submittedApiData))
				{
					$form->removeField($metadataField);
				}
			}
		}
		// Modify the form based on Edit Access 'access' controls.
		if ($id != 0 && (!$user->authorise('address.edit.access', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.access', 'com_sermondistributor')))
		{
			// Disable fields for display.
			$form->setFieldAttribute('access', 'disabled', 'true');
			// Disable fields while saving.
			$form->setFieldAttribute('access', 'filter', 'unset');
		}
		// Modify the form based on Edit Line One access controls.
		if ($id != 0 && (!$user->authorise('address.edit.line_one', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.line_one', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('line_one', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('line_one', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('line_one'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('line_one', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('line_one', 'required', 'false');
			}
		}
		// Modify the form based on Edit Type access controls.
		if ($id != 0 && (!$user->authorise('address.edit.type', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.type', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('type', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('type', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('type'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('type', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('type', 'required', 'false');
			}
		}
		// Modify the form based on Edit Country access controls.
		if ($id != 0 && (!$user->authorise('address.edit.country', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.country', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('country', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('country', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('country'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('country', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('country', 'required', 'false');
			}
		}
		// Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('address.edit.state', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.state', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('state', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('state', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('state'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('state', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('state', 'required', 'false');
			}
		}
		// Modify the form based on Edit City access controls.
		if ($id != 0 && (!$user->authorise('address.edit.city', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.city', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('city', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('city', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('city'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('city', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('city', 'required', 'false');
			}
		}
		// Modify the form based on Edit Preacher access controls.
		if ($id != 0 && (!$user->authorise('address.edit.preacher', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.preacher', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('preacher', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('preacher', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('preacher'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('preacher', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('preacher', 'required', 'false');
			}
		}
		// Modify the form based on Edit Church access controls.
		if ($id != 0 && (!$user->authorise('address.edit.church', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.church', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('church', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('church', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('church'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('church', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('church', 'required', 'false');
			}
		}
		// Modify the form based on Edit Guid access controls.
		if ($id != 0 && (!$user->authorise('address.edit.guid', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.guid', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('guid', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('guid', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('guid'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('guid', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('guid', 'required', 'false');
			}
		}
		// Modify the form based on Edit Postal access controls.
		if ($id != 0 && (!$user->authorise('address.edit.postal', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.postal', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('postal', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('postal', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('postal'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('postal', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('postal', 'required', 'false');
			}
		}
		// Modify the form based on Edit Line Two access controls.
		if ($id != 0 && (!$user->authorise('address.edit.line_two', 'com_sermondistributor.address.' . (int) $id))
			|| ($id == 0 && !$user->authorise('address.edit.line_two', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('line_two', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('line_two', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('line_two'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('line_two', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('line_two', 'required', 'false');
			}
		}
		// Only load these values if no id is found
		if (0 == $id)
		{
			// Set redirected view name
			$redirectedView = $jinput->get('ref', null, 'STRING');
			// Set field name (or fall back to view name)
			$redirectedField = $jinput->get('field', $redirectedView, 'STRING');
			// Set redirected view id
			$redirectedId = $jinput->get('refid', 0, 'INT');
			// Set field id (or fall back to redirected view id)
			$redirectedValue = $jinput->get('field_id', $redirectedId, 'INT');
			if (0 != $redirectedValue && $redirectedField)
			{
				// Now set the local-redirected field default value
				$form->setValue($redirectedField, null, $redirectedValue);
			}
			$initDefaults = $jinput->get('init_defaults', null, 'STRING');
			if (!empty($initDefaults))
			{
				// Now check if this json values are valid
				$initDefaults = json_decode(urldecode($initDefaults), true);
				if (is_array($initDefaults))
				{
					foreach ($initDefaults as $field => $value)
					{
						$form->setValue($field, null, $value);
					}
				}
			}
		}
/***[INSERTED$$$$]***//**362**/
		// Only load the GUID if new item (or empty)
		if (0 == $id || !($val = $form->getValue('guid')))
		{
			$form->setValue('guid', null, GuidHelper::get());
		}
/***[/INSERTED$$$$]***/
		if ($app->isClient('api') && $jinput->getMethod() === 'PATCH')
		{
			$this->setState('jcb.api.patch.form', $form);
		}
		return $form;
	}

	/**
	 * Method to get the styles that have to be included on the view
	 *
	 * @return  array    styles files
	 * @since   4.3
	 */
	public function getStyles(): array
	{
		return $this->styles;
	}

	/**
	 * Method to set the styles that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setStyles(string $path): void
	{
		$this->styles[] = $path;
	}

	/**
	 * Method to get the script that have to be included on the view
	 *
	 * @return  array    script files
	 * @since   4.3
	 */
	public function getScripts(): array
	{
		return $this->scripts;
	}

	/**
	 * Method to set the script that have to be included on the view
	 *
	 * @return  void
	 * @since   4.3
	 */
	public function setScript(string $path): void
	{
		$this->scripts[] = $path;
	}

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (empty($record->id) || ($record->published != -2))
		{
			return false;
		}

		// The record has been set. Check the record permissions.
		return $this->getCurrentUser()->authorise('address.delete', 'com_sermondistributor.address.' . (int) $record->id);
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = $this->getCurrentUser();
		$recordId = $record->id ?? 0;

		if ($recordId)
		{
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('address.edit.state', 'com_sermondistributor.address.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// In the absence of better information, revert to the component permissions.
		return $user->authorise('address.edit.state', 'com_sermondistributor');
	}

	/**
	 * Method to check if you can edit an existing record.
	 *   We know this is a double access check (Controller already does an allowEdit check)
	 *   But when the item is directly accessed the controller is skipped (2025_).
	 *
	 * @param    array    $data   An array of input data.
	 * @param    string   $key    The name of the key for the primary key.
	 *
	 * @return   boolean  True if allowed to edit the record. Defaults to the permission set in the component.
	 * @since    2.5
	 */
	protected function allowEdit(array $data = [], string $key = 'id'): bool
	{
		// get user object.
		$user = $this->getCurrentUser();
		// get record id.
		$recordId = isset($data[$key]) ? (int) $data[$key] : 0;


		// Access check.
		$access = ($user->authorise('address.access', 'com_sermondistributor.address.' . (int) $recordId) && $user->authorise('address.access', 'com_sermondistributor'));
		if (!$access)
		{
			return false;
		}

		if ($recordId)
		{
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('address.edit', 'com_sermondistributor.address.' . (int) $recordId);
			if (!$permission)
			{
				if ($user->authorise('address.edit.own', 'com_sermondistributor.address.' . (int) $recordId))
				{
					// Now test the owner is the user.
					$ownerId = isset($data['created_by']) ? (int) $data['created_by'] : 0;
					if (empty($ownerId))
					{
						return false;
					}

					// If the owner matches 'me' then allow.
					if ($ownerId == $user->id)
					{
						if ($user->authorise('address.edit.own', 'com_sermondistributor'))
						{
							return true;
						}
					}
				}
				return false;
			}
		}
		// Since there is no permission, revert to the component permissions.
		return $user->authorise('address.edit', $this->option);
	}

	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   Table  $table  A Table object.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		$date = Factory::getDate();
		$user = $this->getCurrentUser();

		if (isset($table->name))
		{
			$table->name = \htmlspecialchars_decode($table->name, ENT_QUOTES);
		}

		if (isset($table->alias) && empty($table->alias))
		{
			$table->generateAlias();
		}

		if (empty($table->id))
		{
			$table->created = $date->toSql();
			// set the user
			if ($table->created_by == 0 || empty($table->created_by))
			{
				$table->created_by = $user->id;
			}
			// Set ordering to the last item if not set
			if (empty($table->ordering))
			{
				$db = $this->getDatabase();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__sermondistributor_address'));
				$db->setQuery($query);
				$max = $db->loadResult();

				$table->ordering = $max + 1;
			}
		}
		else
		{
			$table->modified = $date->toSql();
			$table->modified_by = $user->id;
		}

		if (!empty($table->id))
		{
			// Increment the items version number.
			$table->version++;
		}
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = Factory::getApplication()->getUserState('com_sermondistributor.edit.address.data', []);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		// run the per process of the data
		$this->preprocessData('com_sermondistributor.address', $data);

		return $data;
	}

	/**
	 * Method to get the unique fields of this table.
	 *
	 * @return  mixed  An array of field names, boolean false if none is set.
	 *
	 * @since   3.0
	 */
	protected function getUniqueFields()
	{
		return array('guid');
	}

	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs
	 * @since   12.2
	 */
	public function delete(&$pks)
	{
		if (!parent::delete($pks))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to change the published state of one or more records.
	 *
	 * @param   array    &$pks   A list of the primary keys to change.
	 * @param   integer  $value  The value of the published state.
	 *
	 * @return  boolean  True on success.
	 * @since   12.2
	 */
	public function publish(&$pks, $value = 1)
	{
		if (!parent::publish($pks, $value))
		{
			return false;
		}

		return true;
	}

	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function save($data)
	{
		$input    = Factory::getApplication()->getInput();
		$filter   = InputFilter::getInstance();

		// set the metadata to the Item Data
		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');

			$metadata = new Registry;
			$metadata->loadArray($data['metadata']);
			$data['metadata'] = (string) $metadata;
		}

		// The record keys, as every line below expects them: the primary key as an
		// integer that is never taken from the request (null from the API on create).
		$data['id'] = (int) ($data['id'] ?? 0);

		// The guid is the server's: an existing record keeps the guid it was stored
		// with, and the API never takes one from the request.
		if ($data['id'] > 0)
		{
			$data['guid'] = (string) GetHelper::var('address', $data['id'], 'id', 'guid', '=', 'sermondistributor');
		}
		elseif (Factory::getApplication()->isClient('api'))
		{
			$data['guid'] = '';
		}
		else
		{
			$data['guid'] = (string) ($data['guid'] ?? '');
		}

		// Set the guid while it is empty, not valid, or not unique in this table.
		while (!GuidHelper::valid($data['guid'], 'address', $data['id'], 'sermondistributor'))
		{
			$data['guid'] = (string) GuidHelper::get();
		}

		// Preserve omitted API PATCH values in their original storage representation.
		$jcbPatchStored = [];
		$jcbPatchInput = [];
		$jcbPatchId = $data['id'];

		if ($input->getMethod() === 'PATCH' && Factory::getApplication()->isClient('api') && $jcbPatchId > 0)
		{
			$jcbPatchSubmitted = $input->get('data', json_decode($input->json->getRaw(), true), 'array');
			$jcbPatchSubmitted = is_array($jcbPatchSubmitted) ? $jcbPatchSubmitted : [];
			// Joomla's API inserts an empty tags array after validation. It must not
			// clear omitted relationships or bypass the form's field permissions.
			$jcbPatchForm = $this->getState('jcb.api.patch.form');
			if (!array_key_exists('tags', $jcbPatchSubmitted)
				|| ($jcbPatchForm !== null && (!$jcbPatchForm->getField('tags')
					|| strtolower((string) $jcbPatchForm->getFieldAttribute('tags', 'filter', '')) === 'unset'
					|| in_array(strtolower((string) $jcbPatchForm->getFieldAttribute('tags', 'disabled', '')), ['true', '1'], true))))
			{
				unset($data['tags']);
			}

			$jcbPatchTable = $this->getTable();

			if (!$jcbPatchTable->load($jcbPatchId))
			{
				$this->setError(Text::_('JLIB_APPLICATION_ERROR_RECORD_LOAD'));

				return false;
			}

			foreach ($jcbPatchTable->getFields() as $jcbPatchField)
			{
				$jcbPatchName = $jcbPatchField->Field;

				if ($jcbPatchName !== $jcbPatchTable->getKeyName() && $jcbPatchName !== 'guid'
					&& !array_key_exists($jcbPatchName, $jcbPatchSubmitted)
					&& array_key_exists($jcbPatchName, $data))
				{
					$jcbPatchStored[$jcbPatchName] = $jcbPatchTable->{$jcbPatchName};
					$jcbPatchInput[$jcbPatchName] = serialize($data[$jcbPatchName]);
				}
			}
		}


/***[JCBGUI.admin_view.php_before_save.362.$$$$]***/

		// Set the GUID if empty or not valid
		if (empty($data['guid']) && $data['id'] > 0)
		{
			// get the existing one
			$data['guid'] = (string) GetHelper::var('address', $data['id'], 'id', 'guid');
		}

		// Set the GUID if empty or not valid
		while (!GuidHelper::valid($data['guid'], "address", $data['id']))
		{
			// must always be set
			$data['guid'] = (string) GuidHelper::get();
		}/***[/JCBGUI$$$$]***/


		// A copy or a custom derivation must follow the normal storage transforms.
		if ((int) $data['id'] !== $jcbPatchId)
		{
			$jcbPatchStored = [];
		}
		else
		{
			foreach ($jcbPatchStored as $jcbPatchName => $jcbPatchValue)
			{
				if (!array_key_exists($jcbPatchName, $data)
					|| serialize($data[$jcbPatchName]) !== $jcbPatchInput[$jcbPatchName])
				{
					unset($jcbPatchStored[$jcbPatchName]);
				}
			}
		}

		// Restore unchanged omitted columns without a lossy decode/encode round trip.
		foreach ($jcbPatchStored as $jcbPatchName => $jcbPatchValue)
		{
			if (array_key_exists($jcbPatchName, $data)
				&& serialize($data[$jcbPatchName]) === $jcbPatchInput[$jcbPatchName])
			{
				$data[$jcbPatchName] = $jcbPatchValue;
			}
		}

		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new Registry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		// Alter the unique field for save as copy
		if ($input->get('task') === 'save2copy')
		{
			// Automatic handling of other unique fields
			$uniqueFields = $this->getUniqueFields();
			if (UtilitiesArrayHelper::check($uniqueFields))
			{
				foreach ($uniqueFields as $uniqueField)
				{
					$data[$uniqueField] = $this->generateUnique($uniqueField,$data[$uniqueField]);
				}
			}
		}

		if (parent::save($data))
		{
			return true;
		}
		return false;
	}

	/**
	 * Method to generate a unique value.
	 *
	 * @param   string  $field name.
	 * @param   string  $value data.
	 *
	 * @return  string  New value.
	 * @since   3.0
	 */
	protected function generateUnique($field, $value)
	{
		// set field value unique
		$table = $this->getTable();

		while ($table->load([$field => $value]))
		{
			$value = StringHelper::increment($value);
		}

		return $value;
	}

	/**
	 * Method to change the title
	 *
	 * @param   string   $title   The title.
	 *
	 * @return	array  Contains the modified title and alias.
	 *
	 */
	protected function _generateNewTitle($title)
	{

		// Alter the title
		$table = $this->getTable();

		while ($table->load(['title' => $title]))
		{
			$title = StringHelper::increment($title);
		}

		return $title;
	}
}
