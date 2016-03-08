<?php
/*--------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
    __      __       _     _____                 _                                  _     __  __      _   _               _
    \ \    / /      | |   |  __ \               | |                                | |   |  \/  |    | | | |             | |
     \ \  / /_ _ ___| |_  | |  | | _____   _____| | ___  _ __  _ __ ___   ___ _ __ | |_  | \  / | ___| |_| |__   ___   __| |
      \ \/ / _` / __| __| | |  | |/ _ \ \ / / _ \ |/ _ \| '_ \| '_ ` _ \ / _ \ '_ \| __| | |\/| |/ _ \ __| '_ \ / _ \ / _` |
       \  / (_| \__ \ |_  | |__| |  __/\ V /  __/ | (_) | |_) | | | | | |  __/ | | | |_  | |  | |  __/ |_| | | | (_) | (_| |
        \/ \__,_|___/\__| |_____/ \___| \_/ \___|_|\___/| .__/|_| |_| |_|\___|_| |_|\__| |_|  |_|\___|\__|_| |_|\___/ \__,_|
                                                        | |                                                                 
                                                        |_| 				
/-------------------------------------------------------------------------------------------------------------------------------/

	@version		1.3.0
	@build			8th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		help_document.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

// import Joomla modelform library
jimport('joomla.application.component.modeladmin');

/**
 * Sermondistributor Help_document Model
 */
class SermondistributorModelHelp_document extends JModelAdmin
{    
	/**
	 * @var        string    The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_SERMONDISTRIBUTOR';
    
	/**
	 * The type alias for this content type.
	 *
	 * @var      string
	 * @since    3.2
	 */
	public $typeAlias = 'com_sermondistributor.help_document';

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type    $type    The table type to instantiate
	 * @param   string  $prefix  A prefix for the table class name. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  JTable  A database object
	 *
	 * @since   1.6
	 */
	public function getTable($type = 'help_document', $prefix = 'SermondistributorTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}
    
	/**
	 * Method to get a single record.
	 *
	 * @param   integer  $pk  The id of the primary key.
	 *
	 * @return  mixed  Object on success, false on failure.
	 *
	 * @since   1.6
	 */
	public function getItem($pk = null)
	{
		if ($item = parent::getItem($pk))
		{
			if (!empty($item->params))
			{
				// Convert the params field to an array.
				$registry = new Registry;
				$registry->loadString($item->params);
				$item->params = $registry->toArray();
			}

			if (!empty($item->metadata))
			{
				// Convert the metadata field to an array.
				$registry = new Registry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
			}

			if (!empty($item->groups))
			{
				// [Interpretation 3155] JSON Decode groups.
				$item->groups = json_decode($item->groups,true);
			}
			
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_sermondistributor.help_document');
			}
		}

		return $item;
	} 

	/**
	 * Method to get the record form.
	 *
	 * @param   array    $data      Data for the form.
	 * @param   boolean  $loadData  True if the form is to load its own data (default case), false if not.
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{		// [Interpretation 8735] Get the form.
		$form = $this->loadForm('com_sermondistributor.help_document', 'help_document', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// [Interpretation 8820] The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}
		// [Interpretation 8825] The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}

		$user = JFactory::getUser();

		// [Interpretation 8831] Check for existing item.
		// [Interpretation 8832] Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('help_document.edit.state', 'com_sermondistributor.help_document.' . (int) $id))
			|| ($id == 0 && !$user->authorise('help_document.edit.state', 'com_sermondistributor')))
		{
			// [Interpretation 8845] Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');
			// [Interpretation 8848] Disable fields while saving.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}
		// [Interpretation 8853] If this is a new item insure the greated by is set.
		if (0 == $id)
		{
			// [Interpretation 8856] Set the created_by to this user
			$form->setValue('created_by', null, $user->id);
		}
		// [Interpretation 8859] Modify the form based on Edit Creaded By access controls.
		if (!$user->authorise('core.edit.created_by', 'com_sermondistributor'))
		{
			// [Interpretation 8871] Disable fields for display.
			$form->setFieldAttribute('created_by', 'disabled', 'true');
			// [Interpretation 8873] Disable fields for display.
			$form->setFieldAttribute('created_by', 'readonly', 'true');
			// [Interpretation 8875] Disable fields while saving.
			$form->setFieldAttribute('created_by', 'filter', 'unset');
		}
		// [Interpretation 8878] Modify the form based on Edit Creaded Date access controls.
		if (!$user->authorise('core.edit.created', 'com_sermondistributor'))
		{
			// [Interpretation 8890] Disable fields for display.
			$form->setFieldAttribute('created', 'disabled', 'true');
			// [Interpretation 8892] Disable fields while saving.
			$form->setFieldAttribute('created', 'filter', 'unset');
		}
		// [Interpretation 8925] Only load these values if no id is found
		if (0 == $id)
		{
			// [Interpretation 8928] Set redirected field name
			$redirectedField = $jinput->get('ref', null, 'STRING');
			// [Interpretation 8930] Set redirected field value
			$redirectedValue = $jinput->get('refid', 0, 'INT');
			if (0 != $redirectedValue && $redirectedField)
			{
				// [Interpretation 8934] Now set the local-redirected field default value
				$form->setValue($redirectedField, null, $redirectedValue);
			}
		}

		return $form;
	}

	/**
	 * Method to get the script that have to be included on the form
	 *
	 * @return string	script files
	 */
	public function getScript()
	{
		return 'administrator/components/com_sermondistributor/models/forms/help_document.js';
	}
    
	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			if ($record->published != -2)
			{
				return;
			}

			$user = JFactory::getUser();
			// [Interpretation 9056] The record has been set. Check the record permissions.
			return $user->authorise('help_document.delete', 'com_sermondistributor.help_document.' . (int) $record->id);
		}
		return false;
	}

	/**
	 * Method to test whether a record can have its state edited.
	 *
	 * @param   object  $record  A record object.
	 *
	 * @return  boolean  True if allowed to change the state of the record. Defaults to the permission set in the component.
	 *
	 * @since   1.6
	 */
	protected function canEditState($record)
	{
		$user = JFactory::getUser();
		$recordId	= (!empty($record->id)) ? $record->id : 0;

		if ($recordId)
		{
			// [Interpretation 9143] The record has been set. Check the record permissions.
			$permission = $user->authorise('help_document.edit.state', 'com_sermondistributor.help_document.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// [Interpretation 9160] In the absense of better information, revert to the component permissions.
		return $user->authorise('help_document.edit.state', 'com_sermondistributor');
	}
    
	/**
	 * Method override to check if you can edit an existing record.
	 *
	 * @param	array	$data	An array of input data.
	 * @param	string	$key	The name of the key for the primary key.
	 *
	 * @return	boolean
	 * @since	2.5
	 */
	protected function allowEdit($data = array(), $key = 'id')
	{
		// [Interpretation 8968] Check specific edit permission then general edit permission.
		$user = JFactory::getUser();

		return $user->authorise('help_document.edit', 'com_sermondistributor.help_document.'. ((int) isset($data[$key]) ? $data[$key] : 0)) or $user->authorise('help_document.edit',  'com_sermondistributor');
	}
    
	/**
	 * Prepare and sanitise the table data prior to saving.
	 *
	 * @param   JTable  $table  A JTable object.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function prepareTable($table)
	{
		$date = JFactory::getDate();
		$user = JFactory::getUser();
		
		if (isset($table->name))
		{
			$table->name = htmlspecialchars_decode($table->name, ENT_QUOTES);
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
				$db = JFactory::getDbo();
				$query = $db->getQuery(true)
					->select('MAX(ordering)')
					->from($db->quoteName('#__sermondistributor_help_document'));
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
	 *
	 * @since   1.6
	 */
	protected function loadFormData() 
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_sermondistributor.edit.help_document.data', array());

		if (empty($data))
		{
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	* Method to validate the form data.
	*
	* @param   JForm   $form   The form to validate against.
	* @param   array   $data   The data to validate.
	* @param   string  $group  The name of the field group to validate.
	*
	* @return  mixed  Array of filtered data if valid, false otherwise.
	*
	* @see     JFormRule
	* @see     JFilterInput
	* @since   12.2
	*/
	public function validate($form, $data, $group = null)
	{
		// [Interpretation 7939] check if the not_required field is set
		if (SermondistributorHelper::checkString($data['not_required']))
		{
			$requiredFields = (array) explode(',',(string) $data['not_required']);
			$requiredFields = array_unique($requiredFields);
			// [Interpretation 7944] now change the required field attributes value
			foreach ($requiredFields as $requiredField)
			{
				// [Interpretation 7947] make sure there is a string value
				if (SermondistributorHelper::checkString($requiredField))
				{
					// [Interpretation 7950] change to false
					$form->setFieldAttribute($requiredField, 'required', 'false');
					// [Interpretation 7952] also clear the data set
					$data[$requiredField] = '';
				}
			}
		}
		return parent::validate($form, $data, $group);
	} 

	/**
	 * Method to get the unique fields of this table.
	 *
	 * @return  mixed  An array of field names, boolean false if none is set.
	 *
	 * @since   3.0
	 */
	protected function getUniqeFields()
	{
		return false;
	}
	
	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
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
	 * Method to perform batch operations on an item or a set of items.
	 *
	 * @param   array  $commands  An array of commands to perform.
	 * @param   array  $pks       An array of item ids.
	 * @param   array  $contexts  An array of item contexts.
	 *
	 * @return  boolean  Returns true on success, false on failure.
	 *
	 * @since   12.2
	 */
	public function batch($commands, $pks, $contexts)
	{
		// Sanitize ids.
		$pks = array_unique($pks);
		JArrayHelper::toInteger($pks);

		// Remove any values of zero.
		if (array_search(0, $pks, true))
		{
			unset($pks[array_search(0, $pks, true)]);
		}

		if (empty($pks))
		{
			$this->setError(JText::_('JGLOBAL_NO_ITEM_SELECTED'));
			return false;
		}

		$done = false;

		// Set some needed variables.
		$this->user			= JFactory::getUser();
		$this->table			= $this->getTable();
		$this->tableClassName		= get_class($this->table);
		$this->contentType		= new JUcmType;
		$this->type			= $this->contentType->getTypeByTable($this->tableClassName);
		$this->canDo			= SermondistributorHelper::getActions('help_document');
		$this->batchSet			= true;

		if (!$this->canDo->get('core.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));
			return false;
		}
        
		if ($this->type == false)
		{
			$type = new JUcmType;
			$this->type = $type->getTypeByAlias($this->typeAlias);
		}

		$this->tagsObserver = $this->table->getObserverOfClass('JTableObserverTags');

		if (!empty($commands['move_copy']))
		{
			$cmd = JArrayHelper::getValue($commands, 'move_copy', 'c');

			if ($cmd == 'c')
			{
				$result = $this->batchCopy($commands, $pks, $contexts);

				if (is_array($result))
				{
					foreach ($result as $old => $new)
					{
						$contexts[$new] = $contexts[$old];
					}
					$pks = array_values($result);
				}
				else
				{
					return false;
				}
			}
			elseif ($cmd == 'm' && !$this->batchMove($commands, $pks, $contexts))
			{
				return false;
			}

			$done = true;
		}

		if (!$done)
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_INSUFFICIENT_BATCH_INFORMATION'));

			return false;
		}

		// Clear the cache
		$this->cleanCache();

		return true;
	}

	/**
	 * Batch copy items to a new category or current.
	 *
	 * @param   integer  $values    The new values.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  mixed  An array of new IDs on success, boolean false on failure.
	 *
	 * @since	12.2
	 */
	protected function batchCopy($values, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// [Interpretation 4151] Set some needed variables.
			$this->user 		= JFactory::getUser();
			$this->table 		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= SermondistributorHelper::getActions('help_document');
		}

		if (!$this->canDo->get('help_document.create') && !$this->canDo->get('help_document.batch'))
		{
			return false;
		}

		// [Interpretation 4171] get list of uniqe fields
		$uniqeFields = $this->getUniqeFields();
		// [Interpretation 4173] remove move_copy from array
		unset($values['move_copy']);

		// [Interpretation 4176] make sure published is set
		if (!isset($values['published']))
		{
			$values['published'] = 0;
		}
		elseif (isset($values['published']) && !$this->canDo->get('help_document.edit.state'))
		{
				$values['published'] = 0;
		}

		$newIds = array();

		// [Interpretation 4213] Parent exists so let's proceed
		while (!empty($pks))
		{
			// [Interpretation 4216] Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// [Interpretation 4221] only allow copy if user may edit this item.

			if (!$this->user->authorise('help_document.edit', $contexts[$pk]))

			{

				// [Interpretation 4231] Not fatal error

				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));

				continue;

			}

			// [Interpretation 4236] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [Interpretation 4241] Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// [Interpretation 4248] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			list($this->table->title, $this->table->alias) = $this->_generateNewTitle($this->table->alias, $this->table->title);

			// [Interpretation 4284] insert all set values
			if (SermondistributorHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					if (strlen($value) > 0 && isset($this->table->$key))
					{
						$this->table->$key = $value;
					}
				}
			}

			// [Interpretation 4296] update all uniqe fields
			if (SermondistributorHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$this->table->$uniqeField = $this->generateUniqe($uniqeField,$this->table->$uniqeField);
				}
			}

			// [Interpretation 4305] Reset the ID because we are making a copy
			$this->table->id = 0;

			// [Interpretation 4308] TODO: Deal with ordering?
			// [Interpretation 4309] $this->table->ordering	= 1;

			// [Interpretation 4311] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [Interpretation 4324] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}

			// [Interpretation 4332] Get the new item ID
			$newId = $this->table->get('id');

			// [Interpretation 4335] Add the new ID to the array
			$newIds[$pk] = $newId;
		}

		// [Interpretation 4339] Clean the cache
		$this->cleanCache();

		return $newIds;
	} 

	/**
	 * Batch move items to a new category
	 *
	 * @param   integer  $value     The new category ID.
	 * @param   array    $pks       An array of row IDs.
	 * @param   array    $contexts  An array of item contexts.
	 *
	 * @return  boolean  True if successful, false otherwise and internal error is set.
	 *
	 * @since	12.2
	 */
	protected function batchMove($values, $pks, $contexts)
	{
		if (empty($this->batchSet))
		{
			// [Interpretation 3953] Set some needed variables.
			$this->user		= JFactory::getUser();
			$this->table		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= SermondistributorHelper::getActions('help_document');
		}

		if (!$this->canDo->get('help_document.edit') && !$this->canDo->get('help_document.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
			return false;
		}

		// [Interpretation 3975] make sure published only updates if user has the permission.
		if (isset($values['published']) && !$this->canDo->get('help_document.edit.state'))
		{
			unset($values['published']);
		}
		// [Interpretation 3988] remove move_copy from array
		unset($values['move_copy']);

		// [Interpretation 4009] Parent exists so we proceed
		foreach ($pks as $pk)
		{
			if (!$this->user->authorise('help_document.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			// [Interpretation 4026] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [Interpretation 4031] Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// [Interpretation 4038] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// [Interpretation 4044] insert all set values.
			if (SermondistributorHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					// [Interpretation 4049] Do special action for access.
					if ('access' == $key && strlen($value) > 0)
					{
						$this->table->$key = $value;
					}
					elseif (strlen($value) > 0 && isset($this->table->$key))
					{
						$this->table->$key = $value;
					}
				}
			}


			// [Interpretation 4061] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [Interpretation 4074] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}
		}

		// [Interpretation 4083] Clean the cache
		$this->cleanCache();

		return true;
	}
	
	/**
	 * Method to save the form data.
	 *
	 * @param   array  $data  The form data.
	 *
	 * @return  boolean  True on success.
	 *
	 * @since   1.6
	 */
	public function save($data)
	{
		$input	= JFactory::getApplication()->input;
		$filter	= JFilterInput::getInstance();
        
		// set the metadata to the Item Data
		if (isset($data['metadata']) && isset($data['metadata']['author']))
		{
			$data['metadata']['author'] = $filter->clean($data['metadata']['author'], 'TRIM');
            
			$metadata = new JRegistry;
			$metadata->loadArray($data['metadata']);
			$data['metadata'] = (string) $metadata;
		} 

		// [Interpretation 3265] Set the groups string to JSON string.
		if (isset($data['groups']))
		{
			$data['groups'] = (string) json_encode($data['groups']);
		}
        
		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new JRegistry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		// [Interpretation 4365] Alter the title for save as copy
		if ($input->get('task') == 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['title'] == $origTable->title)
			{
				list($title, $alias) = $this->_generateNewTitle($data['alias'], $data['title']);
				$data['title'] = $title;
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

		// [Interpretation 4392] Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (int) $input->get('id') == 0)
		{
			if ($data['alias'] == null)
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['title']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['title']);
				}

				$table = JTable::getInstance('help_document', 'sermondistributorTable');

				if ($table->load(array('alias' => $data['alias'])) && ($table->id != $data['id'] || $data['id'] == 0))
				{
					$msg = JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SAVE_WARNING');
				}

				list($title, $alias) = $this->_generateNewTitle($data['alias'], $data['title']);
				$data['alias'] = $alias;

				if (isset($msg))
				{
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		// [Interpretation 4431] Alter the uniqe field for save as copy
		if ($input->get('task') == 'save2copy')
		{
			// [Interpretation 4434] Automatic handling of other uniqe fields
			$uniqeFields = $this->getUniqeFields();
			if (SermondistributorHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$data[$uniqeField] = $this->generateUniqe($uniqeField,$data[$uniqeField]);
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
	 * Method to generate a uniqe value.
	 *
	 * @param   string  $field name.
	 * @param   string  $value data.
	 *
	 * @return  string  New value.
	 *
	 * @since   3.0
	 */
	protected function generateUniqe($field,$value)
	{

		// set field value uniqe 
		$table = $this->getTable();

		while ($table->load(array($field => $value)))
		{
			$value = JString::increment($value);
		}

		return $value;
	}

	/**
	* Method to change the title & alias.
	*
	* @param   string   $alias        The alias.
	* @param   string   $title        The title.
	*
	* @return	array  Contains the modified title and alias.
	*
	*/
	protected function _generateNewTitle($alias, $title)
	{

		// [Interpretation 4465] Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
			$title = JString::increment($title);
			$alias = JString::increment($alias, 'dash');
		}

		return array($title, $alias);
	}
}
