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
	@subpackage		CountryModel.php
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
use JoomSermon\Joomla\Utilities\GuidHelper;
use JoomSermon\Joomla\Utilities\GetHelper;
use Joomla\CMS\Access\Exception\NotAllowed;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor Country Admin Model
 *
 * @since  1.6
 */
class CountryModel extends AdminModel
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
				'iso3',
				'iso2',
				'subregion',
				'capital',
				'native',
				'nationality',
				'currency_name',
				'codethree',
				'symbol'
			),
			'right' => array(
				'numeric_code',
				'phonecode',
				'tld',
				'emoji',
				'emojiu',
				'latitude',
				'longitude',
				'timezones'
			),
			'above' => array(
				'name',
				'wikidataid'
			)
		),
		'states' => array(
			'fullwidth' => array(
				'states'
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
		'administrator/components/com_sermondistributor/assets/css/country.css'
 	];

	/**
	 * The scripts array.
	 *
	 * @var    array
	 * @since  4.3
	 */
	protected array $scripts = [
		'administrator/components/com_sermondistributor/assets/js/admin.js',
		'media/com_sermondistributor/js/country.js'
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
	public $typeAlias = 'com_sermondistributor.country';

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
	public function getTable($type = 'country', $prefix = 'Administrator', $config = [])
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
					if (!($user->authorise('country.access', 'com_sermondistributor.country.' . $item->id) && $user->authorise('country.access', 'com_sermondistributor')) || (!$user->authorise('core.options', 'com_sermondistributor') && !in_array((int) $item->access, $user->getAuthorisedViewLevels())))
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
			$getMap =  [
					'_core' => [
						'table' =>'state',
						'linkValue' => $item->guid ?? '',
						'linkKey' => 'country',
						'field' => 'state',
						'get' => ['guid','name','type','latitude','longitude','iso2','fips_code','wikidataid']
					],
					'cities' => [
						'table' =>'city',
						'linkValue' => 'state:guid', // coretable:fieldname
						'linkKey' => 'state',
						'get' => ['guid','name','latitude','longitude','wikidataid']
					]
				];
			$item->states = DataFactory::_('Data.MultiSubform')->get($getMap);
			$item->timezones = DataFactory::_('Data.Subform')->table('timezone')->get($item->guid ?? '' ,'country', 'timezones', ['guid','timezone_name','timezone_identifier','gmt_offset_name','gmt_offset_sec','abbreviation']);
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
		$form = $this->loadForm('com_sermondistributor.country', 'country', $options, $clear, $xpath);

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
		if ($id != 0 && (!$user->authorise('country.edit.state', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.state', 'com_sermondistributor')))
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
		if (!$user->authorise('core.edit.created_by', 'com_sermondistributor'))
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
		if (!$user->authorise('core.edit.created', 'com_sermondistributor'))
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
		if ($id != 0 && (!$user->authorise('country.edit.name', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.name', 'com_sermondistributor')))
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
		// Modify the from the form based on Name access controls.
		if ($id != 0 && (!$user->authorise('country.access.name', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.access.name', 'com_sermondistributor')))
		{
			// Remove the field
			$form->removeField('name');
		}
		// Modify the form based on View Name access controls.
		if ($id != 0 && (!$user->authorise('country.view.name', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.view.name', 'com_sermondistributor')))
		{
			// Make the field hidded.
			$form->setFieldAttribute('name', 'type', 'hidden');
			// If there is no value continue.
			if (!($val = $form->getValue('name')))
			{
				// Disable fields while saving.
				$form->setFieldAttribute('name', 'filter', 'unset');
				// Disable fields while saving.
				$form->setFieldAttribute('name', 'required', 'false');
				// Make sure
				$form->setValue('name', null, '');
			}
			elseif (UtilitiesArrayHelper::check($val))
			{
				// We have to unset then (TODO)
				// Hiddend field can not handel array value
				// Even if we convert to json we get an error
				$form->removeField('name');
			}
		}
		// Modify the form based on Edit Subregion access controls.
		if ($id != 0 && (!$user->authorise('country.edit.subregion', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.subregion', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('subregion', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('subregion', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('subregion'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('subregion', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('subregion', 'required', 'false');
			}
		}
		// Modify the form based on Edit Capital access controls.
		if ($id != 0 && (!$user->authorise('country.edit.capital', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.capital', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('capital', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('capital', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('capital'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('capital', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('capital', 'required', 'false');
			}
		}
		// Modify the form based on Edit Currency Name access controls.
		if ($id != 0 && (!$user->authorise('country.edit.currency_name', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.currency_name', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('currency_name', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('currency_name', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('currency_name'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('currency_name', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('currency_name', 'required', 'false');
			}
		}
		// Modify the form based on Edit Latitude access controls.
		if ($id != 0 && (!$user->authorise('country.edit.latitude', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.latitude', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('latitude', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('latitude', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('latitude'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('latitude', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('latitude', 'required', 'false');
			}
		}
		// Modify the form based on Edit Emojiu access controls.
		if ($id != 0 && (!$user->authorise('country.edit.emojiu', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.emojiu', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('emojiu', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('emojiu', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('emojiu'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('emojiu', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('emojiu', 'required', 'false');
			}
		}
		// Modify the form based on Edit Emoji access controls.
		if ($id != 0 && (!$user->authorise('country.edit.emoji', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.emoji', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('emoji', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('emoji', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('emoji'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('emoji', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('emoji', 'required', 'false');
			}
		}
		// Modify the form based on Edit Tld access controls.
		if ($id != 0 && (!$user->authorise('country.edit.tld', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.tld', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('tld', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('tld', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('tld'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('tld', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('tld', 'required', 'false');
			}
		}
		// Modify the form based on Edit Isotwo access controls.
		if ($id != 0 && (!$user->authorise('country.edit.iso2', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.iso2', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('iso2', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('iso2', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('iso2'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('iso2', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('iso2', 'required', 'false');
			}
		}
		// Modify the form based on Edit States access controls.
		if ($id != 0 && (!$user->authorise('country.edit.states', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.states', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('states', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('states', 'readonly', 'true');
			// Disable the buttons form being clickable.
			$class = $form->getFieldAttribute('states', 'class', '');
			$form->setFieldAttribute('states', 'class', $class . ' disabled no-click');
			// If there is no value continue.
			if (!$form->getValue('states'))
			{
				// Remove the field
				$form->removeField('states');
			}
		}
		// Modify the form based on Edit Codethree access controls.
		if ($id != 0 && (!$user->authorise('country.edit.codethree', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.codethree', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('codethree', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('codethree', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('codethree'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('codethree', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('codethree', 'required', 'false');
			}
		}
		// Modify the form based on Edit Nationality access controls.
		if ($id != 0 && (!$user->authorise('country.edit.nationality', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.nationality', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('nationality', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('nationality', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('nationality'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('nationality', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('nationality', 'required', 'false');
			}
		}
		// Modify the form based on Edit Native access controls.
		if ($id != 0 && (!$user->authorise('country.edit.native', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.native', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('native', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('native', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('native'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('native', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('native', 'required', 'false');
			}
		}
		// Modify the form based on Edit Longitude access controls.
		if ($id != 0 && (!$user->authorise('country.edit.longitude', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.longitude', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('longitude', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('longitude', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('longitude'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('longitude', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('longitude', 'required', 'false');
			}
		}
		// Modify the form based on Edit Symbol access controls.
		if ($id != 0 && (!$user->authorise('country.edit.symbol', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.symbol', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('symbol', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('symbol', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('symbol'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('symbol', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('symbol', 'required', 'false');
			}
		}
		// Modify the form based on Edit Timezones access controls.
		if ($id != 0 && (!$user->authorise('country.edit.timezones', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.timezones', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('timezones', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('timezones', 'readonly', 'true');
			// Disable the buttons form being clickable.
			$class = $form->getFieldAttribute('timezones', 'class', '');
			$form->setFieldAttribute('timezones', 'class', $class . ' disabled no-click');
			// If there is no value continue.
			if (!$form->getValue('timezones'))
			{
				// Remove the field
				$form->removeField('timezones');
			}
		}
		// Modify the form based on Edit Numeric Code access controls.
		if ($id != 0 && (!$user->authorise('country.edit.numeric_code', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.numeric_code', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('numeric_code', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('numeric_code', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('numeric_code'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('numeric_code', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('numeric_code', 'required', 'false');
			}
		}
		// Modify the form based on Edit Wikidataid access controls.
		if ($id != 0 && (!$user->authorise('country.edit.wikidataid', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.wikidataid', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('wikidataid', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('wikidataid', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('wikidataid'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('wikidataid', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('wikidataid', 'required', 'false');
			}
		}
		// Modify the form based on Edit Phonecode access controls.
		if ($id != 0 && (!$user->authorise('country.edit.phonecode', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.phonecode', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('phonecode', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('phonecode', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('phonecode'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('phonecode', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('phonecode', 'required', 'false');
			}
		}
		// Modify the form based on Edit Isothree access controls.
		if ($id != 0 && (!$user->authorise('country.edit.iso3', 'com_sermondistributor.country.' . (int) $id))
			|| ($id == 0 && !$user->authorise('country.edit.iso3', 'com_sermondistributor')))
		{
			// Disable field on display.
			$form->setFieldAttribute('iso3', 'disabled', 'true');
			// Make field readonly on display.
			$form->setFieldAttribute('iso3', 'readonly', 'true');
			// If there is no value continue.
			if (!$form->getValue('iso3'))
			{
				// Disable field while saving.
				$form->setFieldAttribute('iso3', 'filter', 'unset');
				// Disable field while saving.
				$form->setFieldAttribute('iso3', 'required', 'false');
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
		return $this->getCurrentUser()->authorise('country.delete', 'com_sermondistributor.country.' . (int) $record->id);
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
			$permission = $user->authorise('country.edit.state', 'com_sermondistributor.country.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// In the absence of better information, revert to the component permissions.
		return $user->authorise('country.edit.state', 'com_sermondistributor');
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
		$access = ($user->authorise('country.access', 'com_sermondistributor.country.' . (int) $recordId) && $user->authorise('country.access', 'com_sermondistributor'));
		if (!$access)
		{
			return false;
		}

		if ($recordId)
		{
			// The record has been set. Check the record permissions.
			$permission = $user->authorise('country.edit', 'com_sermondistributor.country.' . (int) $recordId);
			if (!$permission)
			{
				if ($user->authorise('country.edit.own', 'com_sermondistributor.country.' . (int) $recordId))
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
						if ($user->authorise('country.edit.own', 'com_sermondistributor'))
						{
							return true;
						}
					}
				}
				return false;
			}
		}
		// Since there is no permission, revert to the component permissions.
		return $user->authorise('country.edit', $this->option);
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
					->from($db->quoteName('#__sermondistributor_country'));
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
		$data = Factory::getApplication()->getUserState('com_sermondistributor.edit.country.data', []);

		if (empty($data))
		{
			$data = $this->getItem();
		}

		// run the per process of the data
		$this->preprocessData('com_sermondistributor.country', $data);

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
		return array('codethree', 'guid');
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
			$data['guid'] = (string) GetHelper::var('country', $data['id'], 'id', 'guid', '=', 'sermondistributor');
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
		while (!GuidHelper::valid($data['guid'], 'country', $data['id'], 'sermondistributor'))
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


/***[JCBGUI.admin_view.php_before_save.299.$$$$]***/

		// Set the GUID if empty or not valid
		if (empty($data['guid']) && $data['id'] > 0)
		{
			// get the existing one
			$data['guid'] = (string) GetHelper::var('country', $data['id'], 'id', 'guid');
		}

		// Set the GUID if empty or not valid
		while (!GuidHelper::valid($data['guid'], "country", $data['id']))
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

		if (array_key_exists('states', $jcbPatchStored)
			&& (!array_key_exists('states', $data) || serialize($data['states']) !== $jcbPatchInput['states']))
		{
			unset($jcbPatchStored['states']);
		}

		if (array_key_exists('timezones', $jcbPatchStored)
			&& (!array_key_exists('timezones', $data) || serialize($data['timezones']) !== $jcbPatchInput['timezones']))
		{
			unset($jcbPatchStored['timezones']);
		}

		if (!array_key_exists('states', $jcbPatchStored) || !array_key_exists('timezones', $jcbPatchStored))
		{

			if (array_key_exists('states', $jcbPatchStored)
				&& (!array_key_exists('states', $data) || serialize($data['states']) !== $jcbPatchInput['states']))
			{
				unset($jcbPatchStored['states']);
			}
			if (!array_key_exists('states', $jcbPatchStored))
			{
				if (isset($data['states']))
				{
					$setMap =  [
						'_core' => [
							'table' =>'state',
							'indexKey' => 'guid',
							'linkKey' => 'country',
							'linkValue' => $data['guid'] ?? ''
						],
						'cities' => [
							'table' =>'city',
							'indexKey' => 'guid',
							'linkKey' => 'state',
							'linkValue' => 'state:guid' // coretable:fieldname
						]
					];
					DataFactory::_('Data.MultiSubform')->set($data['states'] ?? [],  $setMap);
				}
			}

			if (array_key_exists('timezones', $jcbPatchStored)
				&& (!array_key_exists('timezones', $data) || serialize($data['timezones']) !== $jcbPatchInput['timezones']))
			{
				unset($jcbPatchStored['timezones']);
			}
			if (!array_key_exists('timezones', $jcbPatchStored))
			{
				if (isset($data['timezones']))
				{
					DataFactory::_('Data.Subform')->table('timezone')->set($data['timezones'] ?? [], 'guid', 'country', $data['guid'] ?? '');
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
