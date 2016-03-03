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
	@build			3rd March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.php
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
 * Sermondistributor Sermon Model
 */
class SermondistributorModelSermon extends JModelAdmin
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
	public $typeAlias = 'com_sermondistributor.sermon';

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
	public function getTable($type = 'sermon', $prefix = 'SermondistributorTable', $config = array())
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

			if (!empty($item->local_files))
			{
				// [Interpretation 3155] JSON Decode local_files.
				$item->local_files = json_decode($item->local_files);
			}

			if (!empty($item->manual_files))
			{
				// [Interpretation 3155] JSON Decode manual_files.
				$item->manual_files = json_decode($item->manual_files);
			}
			
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_sermondistributor.sermon');
			}
		}
		$this->sermonvvvw = $item->id;

		return $item;
	}

	/**
	* Method to get list data.
	*
	* @return mixed  An array of data items on success, false on failure.
	*/
	public function getVwbstastics()
	{
		// [Interpretation 6229] Get the user object.
		$user = JFactory::getUser();
		// [Interpretation 6231] Create a new query object.
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);

		// [Interpretation 6234] Select some fields
		$query->select('a.*');

		// [Interpretation 6241] From the sermondistributor_statistic table
		$query->from($db->quoteName('#__sermondistributor_statistic', 'a'));

		// [Interpretation 6834] From the sermondistributor_sermon table.
		$query->select($db->quoteName('g.name','sermon_name'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_sermon', 'g') . ' ON (' . $db->quoteName('a.sermon') . ' = ' . $db->quoteName('g.id') . ')');

		// [Interpretation 6834] From the sermondistributor_preacher table.
		$query->select($db->quoteName('h.name','preacher_name'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_preacher', 'h') . ' ON (' . $db->quoteName('a.preacher') . ' = ' . $db->quoteName('h.id') . ')');

		// [Interpretation 6834] From the sermondistributor_series table.
		$query->select($db->quoteName('i.name','series_name'));
		$query->join('LEFT', $db->quoteName('#__sermondistributor_series', 'i') . ' ON (' . $db->quoteName('a.series') . ' = ' . $db->quoteName('i.id') . ')');

		// [Interpretation 6257] Filter by sermonvvvw global.
		$sermonvvvw = $this->sermonvvvw;
		if (is_numeric($sermonvvvw ))
		{
			$query->where('a.sermon = ' . (int) $sermonvvvw );
		}
		elseif (is_string($sermonvvvw))
		{
			$query->where('a.sermon = ' . $db->quote($sermonvvvw));
		}
		else
		{
			$query->where('a.sermon = -5');
		}

		// [Interpretation 6274] Join over the asset groups.
		$query->select('ag.title AS access_level');
		$query->join('LEFT', '#__viewlevels AS ag ON ag.id = a.access');
		// [Interpretation 6277] Filter by access level.
		if ($access = $this->getState('filter.access'))
		{
			$query->where('a.access = ' . (int) $access);
		}
		// [Interpretation 6282] Implement View Level Access
		if (!$user->authorise('core.options', 'com_sermondistributor'))
		{
			$groups = implode(',', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
		}

		// [Interpretation 6289] Order the results by ordering
		$query->order('a.ordering  ASC');

		// [Interpretation 6291] Load the items
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			$items = $db->loadObjectList();

			// [Interpretation 9801] set values to display correctly.
			if (SermondistributorHelper::checkArray($items))
			{
				// [Interpretation 9804] get user object.
				$user = JFactory::getUser();
				foreach ($items as $nr => &$item)
				{
					$access = ($user->authorise('statistic.access', 'com_sermondistributor.statistic.' . (int) $item->id) && $user->authorise('statistic.access', 'com_sermondistributor'));
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
	 *
	 * @return  mixed  A JForm object on success, false on failure
	 *
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{		// [Interpretation 8732] Get the form.
		$form = $this->loadForm('com_sermondistributor.sermon', 'sermon', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		$jinput = JFactory::getApplication()->input;

		// [Interpretation 8759] The front end calls this model and uses a_id to avoid id clashes so we need to check for that first.
		if ($jinput->get('a_id'))
		{
			$id = $jinput->get('a_id', 0, 'INT');
		}
		// [Interpretation 8764] The back end uses id so we use that the rest of the time and set it to 0 by default.
		else
		{
			$id = $jinput->get('id', 0, 'INT');
		}
		// [Interpretation 8769] Determine correct permissions to check.
		if ($this->getState('sermon.id'))
		{
			$id = $this->getState('sermon.id');

			$catid = 0;
			if (isset($this->getItem($id)->catid))
			{
				// [Interpretation 8776] set catagory id
				$catid = $this->getItem($id)->catid;

				// [Interpretation 8778] Existing record. Can only edit in selected categories.
				$form->setFieldAttribute('catid', 'action', 'core.edit');

				// [Interpretation 8780] Existing record. Can only edit own items in selected categories.
				$form->setFieldAttribute('catid', 'action', 'core.edit.own');
			}
		}
		else
		{
			// [Interpretation 8786] New record. Can only create in selected categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}

		$user = JFactory::getUser();

		// [Interpretation 8790] Check for existing item.
		// [Interpretation 8791] Modify the form based on Edit State access controls.
		if ($id != 0 && (!$user->authorise('sermon.edit.state', 'com_sermondistributor.sermon.' . (int) $id))
			|| (isset($catid) && $catid != 0 && !$user->authorise('core.edit.state', 'com_sermondistributor.sermons.category.' . (int) $catid))
			|| ($id == 0 && !$user->authorise('sermon.edit.state', 'com_sermondistributor')))
		{
			// [Interpretation 8806] Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('published', 'disabled', 'true');

			// [Interpretation 8809] Disable fields while saving.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('published', 'filter', 'unset');
		}
		// [Interpretation 8850] If this is a new item insure the greated by is set.
		if (0 == $id)
		{
			// [Interpretation 8853] Set the created_by to this user
			$form->setValue('created_by', null, $user->id);
		}
		// [Interpretation 8856] Modify the form based on Edit Creaded By access controls.
		if ($id != 0 && (!$user->authorise('sermon.edit.created_by', 'com_sermondistributor.sermon.' . (int) $id))
			|| ($id == 0 && !$user->authorise('sermon.edit.created_by', 'com_sermondistributor')))
		{
			// [Interpretation 8868] Disable fields for display.
			$form->setFieldAttribute('created_by', 'disabled', 'true');
			// [Interpretation 8870] Disable fields for display.
			$form->setFieldAttribute('created_by', 'readonly', 'true');
			// [Interpretation 8872] Disable fields while saving.
			$form->setFieldAttribute('created_by', 'filter', 'unset');
		}
		// [Interpretation 8875] Modify the form based on Edit Creaded Date access controls.
		if ($id != 0 && (!$user->authorise('sermon.edit.created', 'com_sermondistributor.sermon.' . (int) $id))
			|| ($id == 0 && !$user->authorise('sermon.edit.created', 'com_sermondistributor')))
		{
			// [Interpretation 8887] Disable fields for display.
			$form->setFieldAttribute('created', 'disabled', 'true');
			// [Interpretation 8889] Disable fields while saving.
			$form->setFieldAttribute('created', 'filter', 'unset');
		}
		// [Interpretation 8922] Only load these values if no id is found
		if (0 == $id)
		{
			// [Interpretation 8925] Set redirected field name
			$redirectedField = $jinput->get('ref', null, 'STRING');
			// [Interpretation 8927] Set redirected field value
			$redirectedValue = $jinput->get('refid', 0, 'INT');
			if (0 != $redirectedValue && $redirectedField)
			{
				// [Interpretation 8931] Now set the local-redirected field default value
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
		return 'administrator/components/com_sermondistributor/models/forms/sermon.js';
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
			$allow = $user->authorise('core.delete', 'com_sermondistributor.sermons.category.' . (int) $record->catid);

			if ($allow)
			{
				// [Interpretation 9024] The record has been set. Check the record permissions.
				return $user->authorise('sermon.delete', 'com_sermondistributor.sermon.' . (int) $record->id);
			}
			return $allow;
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
			// [Interpretation 9097] The record has been set. Check the record permissions.
			$permission = $user->authorise('sermon.edit.state', 'com_sermondistributor.sermon.' . (int) $recordId);
			if (!$permission && !is_null($permission))
			{
				return false;
			}
		}
		// [Interpretation 9113] Check against the category.
		if (!empty($record->catid))
		{
			$catpermission = $user->authorise('core.edit.state', 'com_sermondistributor.sermons.category.' . (int) $record->catid);
			if (!$catpermission && !is_null($catpermission))
			{
				return false;
			}
		}
		// [Interpretation 9124] In the absense of better information, revert to the component permissions.
		return $user->authorise('sermon.edit.state', 'com_sermondistributor');
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
		// [Interpretation 8965] Check specific edit permission then general edit permission.
		$user = JFactory::getUser();

		return $user->authorise('sermon.edit', 'com_sermondistributor.sermon.'. ((int) isset($data[$key]) ? $data[$key] : 0)) or $user->authorise('sermon.edit',  'com_sermondistributor');
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
					->from($db->quoteName('#__sermondistributor_sermon'));
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
		$data = JFactory::getApplication()->getUserState('com_sermondistributor.edit.sermon.data', array());

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
		// [Interpretation 7936] check if the not_required field is set
		if (SermondistributorHelper::checkString($data['not_required']))
		{
			$requiredFields = (array) explode(',',(string) $data['not_required']);
			$requiredFields = array_unique($requiredFields);
			// [Interpretation 7941] now change the required field attributes value
			foreach ($requiredFields as $requiredField)
			{
				// [Interpretation 7944] make sure there is a string value
				if (SermondistributorHelper::checkString($requiredField))
				{
					// [Interpretation 7947] change to false
					$form->setFieldAttribute($requiredField, 'required', 'false');
					// [Interpretation 7949] also clear the data set
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
		$this->canDo			= SermondistributorHelper::getActions('sermon');
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
			// [Interpretation 4148] Set some needed variables.
			$this->user 		= JFactory::getUser();
			$this->table 		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= SermondistributorHelper::getActions('sermon');
		}

		if (!$this->canDo->get('sermon.create') && !$this->canDo->get('sermon.batch'))
		{
			return false;
		}

		// [Interpretation 4168] get list of uniqe fields
		$uniqeFields = $this->getUniqeFields();
		// [Interpretation 4170] remove move_copy from array
		unset($values['move_copy']);

		// [Interpretation 4173] make sure published is set
		if (!isset($values['published']))
		{
			$values['published'] = 0;
		}
		elseif (isset($values['published']) && !$this->canDo->get('sermon.edit.state'))
		{
				$values['published'] = 0;
		}

		if (isset($values['category']) && (int) $values['category'] > 0 && !static::checkCategoryId($values['category']))
		{
			return false;
		}
		elseif (isset($values['category']) && (int) $values['category'] > 0)
		{
			// [Interpretation 4198] move the category value to correct field name
			$values['catid'] = $values['category'];
			unset($values['category']);
		}
		elseif (isset($values['category']))
		{
			unset($values['category']);
		}

		$newIds = array();

		// [Interpretation 4210] Parent exists so let's proceed
		while (!empty($pks))
		{
			// [Interpretation 4213] Pop the first ID off the stack
			$pk = array_shift($pks);

			$this->table->reset();

			// [Interpretation 4218] only allow copy if user may edit this item.

			if (!$this->user->authorise('sermon.edit', $contexts[$pk]))

			{

				// [Interpretation 4228] Not fatal error

				$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));

				continue;

			}

			// [Interpretation 4233] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [Interpretation 4238] Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// [Interpretation 4245] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			if (isset($values['catid']))
			{
				list($this->table->name, $this->table->alias) = $this->generateNewTitle($values['catid'], $this->table->alias, $this->table->name);
			}
			else
			{
				list($this->table->name, $this->table->alias) = $this->generateNewTitle($this->table->catid, $this->table->alias, $this->table->name);
			}

			// [Interpretation 4281] insert all set values
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

			// [Interpretation 4293] update all uniqe fields
			if (SermondistributorHelper::checkArray($uniqeFields))
			{
				foreach ($uniqeFields as $uniqeField)
				{
					$this->table->$uniqeField = $this->generateUniqe($uniqeField,$this->table->$uniqeField);
				}
			}

			// [Interpretation 4302] Reset the ID because we are making a copy
			$this->table->id = 0;

			// [Interpretation 4305] TODO: Deal with ordering?
			// [Interpretation 4306] $this->table->ordering	= 1;

			// [Interpretation 4308] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [Interpretation 4321] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}

			// [Interpretation 4329] Get the new item ID
			$newId = $this->table->get('id');

			// [Interpretation 4332] Add the new ID to the array
			$newIds[$pk] = $newId;
		}

		// [Interpretation 4336] Clean the cache
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
			// [Interpretation 3950] Set some needed variables.
			$this->user		= JFactory::getUser();
			$this->table		= $this->getTable();
			$this->tableClassName	= get_class($this->table);
			$this->contentType	= new JUcmType;
			$this->type		= $this->contentType->getTypeByTable($this->tableClassName);
			$this->canDo		= SermondistributorHelper::getActions('sermon');
		}

		if (!$this->canDo->get('sermon.edit') && !$this->canDo->get('sermon.batch'))
		{
			$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));
			return false;
		}

		// [Interpretation 3972] make sure published only updates if user has the permission.
		if (isset($values['published']) && !$this->canDo->get('sermon.edit.state'))
		{
			unset($values['published']);
		}
		// [Interpretation 3985] remove move_copy from array
		unset($values['move_copy']);

		if (isset($values['category']) && (int) $values['category'] > 0 && !static::checkCategoryId($values['category']))
		{
			return false;
		}
		elseif (isset($values['category']) && (int) $values['category'] > 0)
		{
			// [Interpretation 3996] move the category value to correct field name
			$values['catid'] = $values['category'];
			unset($values['category']);
		}
		elseif (isset($values['category']))
		{
			unset($values['category']);
		}


		// [Interpretation 4006] Parent exists so we proceed
		foreach ($pks as $pk)
		{
			if (!$this->user->authorise('sermon.edit', $contexts[$pk]))
			{
				$this->setError(JText::_('JLIB_APPLICATION_ERROR_BATCH_CANNOT_EDIT'));

				return false;
			}

			// [Interpretation 4023] Check that the row actually exists
			if (!$this->table->load($pk))
			{
				if ($error = $this->table->getError())
				{
					// [Interpretation 4028] Fatal error
					$this->setError($error);

					return false;
				}
				else
				{
					// [Interpretation 4035] Not fatal error
					$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_BATCH_MOVE_ROW_NOT_FOUND', $pk));
					continue;
				}
			}

			// [Interpretation 4041] insert all set values.
			if (SermondistributorHelper::checkArray($values))
			{
				foreach ($values as $key => $value)
				{
					// [Interpretation 4046] Do special action for access.
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


			// [Interpretation 4058] Check the row.
			if (!$this->table->check())
			{
				$this->setError($this->table->getError());

				return false;
			}

			if (!empty($this->type))
			{
				$this->createTagsHelper($this->tagsObserver, $this->type, $pk, $this->typeAlias, $this->table);
			}

			// [Interpretation 4071] Store the row.
			if (!$this->table->store())
			{
				$this->setError($this->table->getError());

				return false;
			}
		}

		// [Interpretation 4080] Clean the cache
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

		// [Interpretation 3265] Set the local_files string to JSON string.
		if (isset($data['local_files']))
		{
			$data['local_files'] = (string) json_encode($data['local_files']);
		}

		// [Interpretation 3265] Set the manual_files string to JSON string.
		if (isset($data['manual_files']))
		{
			$data['manual_files'] = (string) json_encode($data['manual_files']);
		}
        
		// Set the Params Items to data
		if (isset($data['params']) && is_array($data['params']))
		{
			$params = new JRegistry;
			$params->loadArray($data['params']);
			$data['params'] = (string) $params;
		}

		// [Interpretation 4362] Alter the name for save as copy
		if ($input->get('task') == 'save2copy')
		{
			$origTable = clone $this->getTable();
			$origTable->load($input->getInt('id'));

			if ($data['name'] == $origTable->name)
			{
				list($name, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['name']);
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

		// [Interpretation 4389] Automatic handling of alias for empty fields
		if (in_array($input->get('task'), array('apply', 'save', 'save2new')) && (int) $input->get('id') == 0)
		{
			if ($data['alias'] == null)
			{
				if (JFactory::getConfig()->get('unicodeslugs') == 1)
				{
					$data['alias'] = JFilterOutput::stringURLUnicodeSlug($data['name']);
				}
				else
				{
					$data['alias'] = JFilterOutput::stringURLSafe($data['name']);
				}

				$table = JTable::getInstance('sermon', 'sermondistributorTable');

				if ($table->load(array('alias' => $data['alias'], 'catid' => $data['catid'])) && ($table->id != $data['id'] || $data['id'] == 0))
				{
					$msg = JText::_('COM_SERMONDISTRIBUTOR_SERMON_SAVE_WARNING');
				}

				list($name, $alias) = $this->generateNewTitle($data['catid'], $data['alias'], $data['name']);
				$data['alias'] = $alias;

				if (isset($msg))
				{
					JFactory::getApplication()->enqueueMessage($msg, 'warning');
				}
			}
		}

		// [Interpretation 4428] Alter the uniqe field for save as copy
		if ($input->get('task') == 'save2copy')
		{
			// [Interpretation 4431] Automatic handling of other uniqe fields
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

		// [Interpretation 4462] Alter the title & alias
		$table = $this->getTable();

		while ($table->load(array('alias' => $alias)))
		{
			$title = JString::increment($title);
			$alias = JString::increment($alias, 'dash');
		}

		return array($title, $alias);
	}
}
