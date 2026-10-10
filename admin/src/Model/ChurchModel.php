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
	@subpackage		ChurchModel.php
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
use JoomSermon\Joomla\Data\Factory as DataFactory;
use JoomSermon\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use JoomSermon\Joomla\Utilities\ObjectHelper;
use JoomSermon\Joomla\Utilities\GuidHelper;
use JoomSermon\Joomla\Utilities\GetHelper;
use JoomSermon\Joomla\Utilities\Component\Helper;
use Joomla\CMS\Access\Exception\NotAllowed;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor Church Admin Model
 *
 * @since  1.6
 */
class ChurchModel extends AdminModel
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
				'contactname',
				'email',
				'phone',
				'website'
			),
			'right' => array(
				'elders',
				'description'
			),
			'above' => array(
				'name',
				'alias'
			)
		),
		'media' => array(
			'fullwidth' => array(
				'file_type',
				'note_file_vdm_uploader',
				'note_file_vdm_display'
			)
		),
		'address' => array(
			'fullwidth' => array(
				'addresses'
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
		'administrator/components/com_sermondistributor/assets/css/church.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'administrator/components/com_sermondistributor/assets/js/admin.js',
		'media/com_sermondistributor/js/church.js'
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
	public $typeAlias = 'com_sermondistributor.church';

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
	public function getTable($type = 'church', $prefix = 'Administrator', $config = [])
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
					if (!($user->authorise('church.access', 'com_sermondistributor.church.' . $item->id) && $user->authorise('church.access', 'com_sermondistributor')) || (!$user->authorise('core.options', 'com_sermondistributor') && !in_array((int) $item->access, $user->getAuthorisedViewLevels())))
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
			$item->addresses = DataFactory::_('Data.Subform')->table('address')->get($item->guid ?? '' ,'church', 'addresses', ['guid','type','line_one','line_two','country','state','city','postal']);
			$item->elders = DataFactory::_('Data.Items')->table('church_elder')->values([$item->guid], 'church', 'elder');
		}
		$this->churchvvvv = $item->guid;
		$this->churchvvvw = $item->guid;

		return $item;
	}

	/**
	 * Method to get list data.
	 *
	 * @return mixed  An array of data items on success, false on failure.
	 */
	public function getVvvsermons()
	{
		// Get the user object.
		$user = Factory::getApplication()->getIdentity();
		// Create a new query object.
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the sermondistributor_sermon table
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));

		// From the sermondistributor_category table.
		$query->select($db->quoteName(['g.name','g.id'],['category_name','category_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_category', 'g') . ' ON (' . $db->quoteName('a.category') . ' = ' . $db->quoteName('g.guid') . ')');

		// From the sermondistributor_series table.
		$query->select($db->quoteName(['h.name','h.id'],['series_name','series_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_series', 'h') . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('h.guid') . ')');

		// From the sermondistributor_church table.
		$query->select($db->quoteName(['i.name','i.id'],['church_name','church_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_church', 'i') . ' ON (' . $db->quoteName('a.church') . ' = ' . $db->quoteName('i.guid') . ')');

		// From the sermondistributor_preacher table.
		$query->select($db->quoteName(['j.name','j.id'],['preacher_name','preacher_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_preacher', 'j') . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('j.guid') . ')');

		// Filter by churchvvvv global.
		$churchvvvv = $this->churchvvvv;
		if (is_numeric($churchvvvv ))
		{
			$query->where('a.church = ' . (int) $churchvvvv );
		}
		elseif (is_string($churchvvvv))
		{
			$query->where('a.church = ' . $db->quote($churchvvvv));
		}
		else
		{
			$query->where('a.church = -5');
		}

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// Filter by access level.
		$_access = $this->getState('filter.access');
		if ($_access && is_numeric($_access))
		{
			$query->where('a.access = ' . (int) $_access);
		}
		elseif (UtilitiesArrayHelper::check($_access))
		{
			// Secure the array for the query
			$_access = ArrayHelper::toInteger($_access);
			// Filter by the Access Array.
			$query->where('a.access IN (' . implode(',', $_access) . ')');
		}
		// Implement View Level Access
		if (!$user->authorise('core.options', 'com_sermondistributor'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// Order the results by ordering
		$query->order('a.published  ASC');
		$query->order('a.ordering  ASC');

		// Load the items
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// Set values to display correctly.
			if (UtilitiesArrayHelper::check($items))
			{
				// Get the user object if not set.
				if (!isset($user) || !ObjectHelper::check($user))
				{
					$user = $this->getCurrentUser();
				}
				foreach ($items as $nr => &$item)
				{
					// Remove items the user can't access.
					$access = ($user->authorise('sermon.access', 'com_sermondistributor.sermon.' . (int) $item->id) && $user->authorise('sermon.access', 'com_sermondistributor'));
					if (!$access)
					{
						unset($items[$nr]);
						continue;
					}

				}
			}
			return $items;
		}
		return false;
	}

	/**
	 * Method to get list data.
	 *
	 * @return mixed  An array of data items on success, false on failure.
	 */
	public function getVvwpreachers()
	{
		// Get the user object.
		$user = Factory::getApplication()->getIdentity();
		// Create a new query object.
		$db = $this->getDatabase();
		$query = $db->getQuery(true);

		// Select some fields
		$query->select('a.*');

		// From the sermondistributor_preacher table
		$query->from($db->quoteName('#__sermondistributor_preacher', 'a'));

		// From the sermondistributor_church table.
		$query->select($db->quoteName(['g.name','g.id'],['church_name','church_id']));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_church', 'g') . ' ON (' . $db->quoteName('a.church') . ' = ' . $db->quoteName('g.guid') . ')');

		// Filter by churchvvvw global.
		$churchvvvw = $this->churchvvvw;
		if (is_numeric($churchvvvw ))
		{
			$query->where('a.church = ' . (int) $churchvvvw );
		}
		elseif (is_string($churchvvvw))
		{
			$query->where('a.church = ' . $db->quote($churchvvvw));
		}
		else
		{
			$query->where('a.church = -5');
		}

		// Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// Filter by access level.
		$_access = $this->getState('filter.access');
		if ($_access && is_numeric($_access))
		{
			$query->where('a.access = ' . (int) $_access);
		}
		elseif (UtilitiesArrayHelper::check($_access))
		{
			// Secure the array for the query
			$_access = ArrayHelper::toInteger($_access);
			// Filter by the Access Array.
			$query->where('a.access IN (' . implode(',', $_access) . ')');
		}
		// Implement View Level Access
		if (!$user->authorise('core.options', 'com_sermondistributor'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// Order the results by ordering
		$query->order('a.published  ASC');
		$query->order('a.ordering  ASC');

		// Load the items
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// Set values to display correctly.
			if (UtilitiesArrayHelper::check($items))
			{
				// Get the user object if not set.
				if (!isset($user) || !ObjectHelper::check($user))
				{
					$user = $this->getCurrentUser();
				}
				foreach ($items as $nr => &$item)
				{
					// Remove items the user can't access.
					$access = ($user->authorise('preacher.access', 'com_sermondistributor.preacher.' . (int) $item->id) && $user->authorise('preacher.access', 'com_sermondistributor'));
					if (!$access)
					{
						unset($items[$nr]);
						continue;
					}

				}
			}
			return $items;
		}
		return false;
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
		$form = $this->loadForm('com_sermondistributor.church', 'church', $options, $clear, $xpath);

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
		if ($id != 0 && (!$user->authorise('church.edit.state', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.state', 'com_sermondistributor')))
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
		if ($id != 0 && (!$user->authorise('church.edit.created_by', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.created_by', 'com_sermondistributor')))
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
		if ($id != 0 && (!$user->authorise('church.edit.created', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.created', 'com_sermondistributor')))
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
		// Modify the form based on Edit Name access controls.
		if ($id != 0 && (!$user->authorise('church.edit.name', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.name', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('name', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('name', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('name'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('name', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('name', 'required', 'false');
			}
		}
		// Modify the form based on Edit Guid access controls.
		if ($id != 0 && (!$user->authorise('church.edit.guid', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.guid', 'com_sermondistributor')))
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
		// Modify the form based on Edit File Type access controls.
		if ($id != 0 && (!$user->authorise('church.edit.file_type', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.file_type', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('file_type', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('file_type', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('file_type'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('file_type', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('file_type', 'required', 'false');
			}
		}
		// Modify the form based on Edit Addresses access controls.
		if ($id != 0 && (!$user->authorise('church.edit.addresses', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.addresses', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('addresses', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('addresses', 'readonly', 'true');
			// Disable the buttons form being clickable.
			$class = $form->getFieldAttribute('addresses', 'class', '');
			$form->setFieldAttribute('addresses', 'class', $class . ' disabled no-click');
			// If there is no value continue.
			if (!$form->getValue('addresses'))
			{
				// Remove the field
				$form->removeField('addresses');
			}
		}
		// Modify the form based on Edit Description access controls.
		if ($id != 0 && (!$user->authorise('church.edit.description', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.description', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('description', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('description', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('description'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('description', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('description', 'required', 'false');
			}
		}
		// Modify the form based on Edit Elders access controls.
		if ($id != 0 && (!$user->authorise('church.edit.elders', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.elders', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('elders', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('elders', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('elders'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('elders', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('elders', 'required', 'false');
			}
		}
		// Modify the form based on Edit Website access controls.
		if ($id != 0 && (!$user->authorise('church.edit.website', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.website', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('website', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('website', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('website'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('website', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('website', 'required', 'false');
			}
		}
		// Modify the form based on Edit Phone access controls.
		if ($id != 0 && (!$user->authorise('church.edit.phone', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.phone', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('phone', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('phone', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('phone'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('phone', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('phone', 'required', 'false');
			}
		}
		// Modify the form based on Edit Email access controls.
		if ($id != 0 && (!$user->authorise('church.edit.email', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.email', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('email', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('email', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('email'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('email', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('email', 'required', 'false');
			}
		}
		// Modify the form based on Edit Contactname access controls.
		if ($id != 0 && (!$user->authorise('church.edit.contactname', 'com_sermondistributor.church.' . (int) $id))
			|| ($id == 0 && !$user->authorise('church.edit.contactname', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('contactname', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('contactname', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('contactname'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('contactname', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('contactname', 'required', 'false');
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
		return $this->getCurrentUser()->authorise('church.delete', 'com_sermondistributor.church.' . (int) $record->id);
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
			$permission = $user->authorise('church.edit.state', 'com_sermondistributor.church.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// In the absence of better information, revert to the component permissions.
		return $user->authorise('church.edit.state', 'com_sermondistributor');
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
		$access = ($user->authorise('church.access', 'com_sermondistributor.church.' . (int) $recordId) && $user->authorise('church.access', 'com_sermondistributor'));
		if (!$access)
		{
			return false;
		}

		if ($recordId)
		{
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('church.edit', 'com_sermondistributor.church.' . (int) $recordId);
			if (!$permission)
			{
				if ($user->authorise('church.edit.own', 'com_sermondistributor.church.' . (int) $recordId))
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
						if ($user->authorise('church.edit.own', 'com_sermondistributor'))
						{
							return true;
						}
					}
				}
				return false;
			}
		}
		// Since there is no permission, revert to the component permissions.
		return $user->authorise('church.edit', $this->option);
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
					->from($db->quoteName('#__sermondistributor_church'));
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
		$data = Factory::getApplication()->getUserState('com_sermondistributor.edit.church.data', []);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		// run the per process of the data
		$this->preprocessData('com_sermondistributor.church', $data);

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
			$data['guid'] = (string) GetHelper::var('church', $data['id'], 'id', 'guid', '=', 'sermondistributor');
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
		while (!GuidHelper::valid($data['guid'], 'church', $data['id'], 'sermondistributor'))
		{
			$data['guid'] = (string) GuidHelper::get();
		}

		// A new record without an alias gets one from its title when the table checks it.
		if ($data['id'] === 0 && !isset($data['alias']))
		{
			$data['alias'] = '';
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


/***[JCBGUI.admin_view.php_before_save.363.$$$$]***/

		// Set the GUID if empty or not valid
		if (empty($data['guid']) && $data['id'] > 0)
		{
			// get the existing one
			$data['guid'] = (string) GetHelper::var('church', $data['id'], 'id', 'guid');
		}

		// Set the GUID if empty or not valid
		while (!GuidHelper::valid($data['guid'], "church", $data['id']))
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

		if (array_key_exists('addresses', $jcbPatchStored)
			&& (!array_key_exists('addresses', $data) || serialize($data['addresses']) !== $jcbPatchInput['addresses']))
		{
			unset($jcbPatchStored['addresses']);
		}

		if (array_key_exists('elders', $jcbPatchStored)
			&& (!array_key_exists('elders', $data) || serialize($data['elders']) !== $jcbPatchInput['elders']))
		{
			unset($jcbPatchStored['elders']);
		}

		if (!array_key_exists('addresses', $jcbPatchStored) || !array_key_exists('elders', $jcbPatchStored))
		{

			if (array_key_exists('addresses', $jcbPatchStored)
				&& (!array_key_exists('addresses', $data) || serialize($data['addresses']) !== $jcbPatchInput['addresses']))
			{
				unset($jcbPatchStored['addresses']);
			}
			if (!array_key_exists('addresses', $jcbPatchStored))
			{
				if (isset($data['addresses']))
				{
					DataFactory::_('Data.Subform')->table('address')->set($data['addresses'] ?? [], 'guid', 'church', $data['guid'] ?? '');
				}
			}

			if (array_key_exists('elders', $jcbPatchStored)
				&& (!array_key_exists('elders', $data) || serialize($data['elders']) !== $jcbPatchInput['elders']))
			{
				unset($jcbPatchStored['elders']);
			}
			if (!array_key_exists('elders', $jcbPatchStored))
			{
				if (!empty($data['elders']) && is_array($data['elders']))
				{
					$elderSub = [];
					$churchGuid = $data['guid'] ?? 'error';
				
					// Retrieve existing elder records for this church
					$existing = DataFactory::_('Data.Items')
						->table('church_elder')
						->get([$churchGuid], 'church');
				
					// Index existing records by elder for quick lookup
					$existingMap = [];
					if (!empty($existing))
					{
						foreach ($existing as $row)
						{
							if (!empty($row->elder))
							{
								$existingMap[$row->elder] = $row->guid ?? '';
							}
						}
					}
				
					// get max allowed number of items
					$max = Helper::getParams()->get('max_elders', 5);
					$m = 0;
				
					// Build subform data entries
					foreach ($data['elders'] as $n => $elder)
					{
						if (!is_string($elder) || $elder === '')
						{
							continue; // Skip invalid entries
						}
				
						if ($m >= $max)
						{
							break;
						}
				
						$elderSub["elder{$n}"] = [
							'guid'     => $existingMap[$elder] ?? '',
							'elder' => $elder
						];
				
						$m++;
					}
				
					DataFactory::_('Data.Subform')
						->table('church_elder')
						->set($elderSub, 'guid', 'church', $churchGuid);
						unset($data['elders']);
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

		// Alter the name for save as copy
		if ($input->get('task') === 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['name'] == $origTable->name)
			{
				list($name, $alias) = $this->_generateNewTitle($data['alias'], $data['name']);
				$data['name'] = $name;
				$data['alias'] = $alias;
			}
			else
			{
				if ($data['alias'] == $origTable->alias)
				{
					$data['alias'] = '';
				}
			}

			$data['published'] = 0;
		}

		// Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (int) $input->get('id') == 0)
		{
			if ($data['alias'] == null || empty($data['alias']))
			{
				if (Factory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = OutputFilter::stringURLUnicodeSlug($data['name']);
				}
				else
				{
					$data['alias'] = OutputFilter::stringURLSafe($data['name']);
				}

				$table = clone $this->getTable();

				if ($table->load(array('alias' => $data['alias'])) && ($table->id != $data['id'] || $data['id'] == 0))
				{
					$msg = Text::_('COM_SERMONDISTRIBUTOR_CHURCH_SAVE_WARNING');
				}

				$data['alias'] = $this->_generateNewTitle($data['alias']);

				if (isset($msg))
				{
					Factory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
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
	 * Method to change the title/s & alias.
	 *
	 * @param   string         $alias        The alias.
	 * @param   string/array   $title        The title.
	 *
	 * @return	array/string  Contains the modified title/s and/or alias.
	 *
	 */
	protected function _generateNewTitle($alias, $title = null)
	{

		// Alter the title/s & alias
		$table = $this->getTable();

		while ($table->load(['alias' => $alias]))
		{
			// Check if this is an array of titles
			if (UtilitiesArrayHelper::check($title))
			{
				foreach($title as $nr => &$_title)
				{
					$_title = StringHelper::increment($_title);
				}
			}
			// Make sure we have a title
			elseif ($title)
			{
				$title = StringHelper::increment($title);
			}
			$alias = StringHelper::increment($alias, 'dash');
		}
		// Check if this is an array of titles
		if (UtilitiesArrayHelper::check($title))
		{
			$title[] = $alias;
			return $title;
		}
		// Make sure we have a title
		elseif ($title)
		{
			return array($title, $alias);
		}
		// We only had an alias
		return $alias;
	}
}
