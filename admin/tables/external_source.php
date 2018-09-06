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

	@version		2.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		external_source.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

/**
 * External_sources Table class
 */
class SermondistributorTableExternal_source extends JTable
{
	/**
	 * Ensure the params and metadata in json encoded in the bind method
	 *
	 * @var    array
	 * @since  3.3
	 */
	protected $_jsonEncode = array('params', 'metadata');
    
	/**
	 * Constructor
	 *
	 * @param object Database connector object
	 */
	function __construct(&$db) 
	{
		parent::__construct('#__sermondistributor_external_source', 'id', $db);

		// Adding History Options
		JTableObserverContenthistory::createObserver($this, array('typeAlias' => 'com_sermondistributor.external_source')); 
	}	
 
	public function bind($array, $ignore = '')
	{
    
		if (isset($array['params']) && is_array($array['params']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
        
		// Bind the rules. 
		if (isset($array['rules']) && is_array($array['rules']))
		{ 
			$rules = new JAccessRules($array['rules']); 
			$this->setRules($rules); 
		}
		return parent::bind($array, $ignore);
	}
    
	/**
	 * Overload the store method for the External_source table.
	 *
	 * @param   boolean	Toggle whether null values should be updated.
	 * @return  boolean  True on success, false on failure.
	 * @since   1.6
	 */
	public function store($updateNulls = false)
	{
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		if ($this->id)
		{
			// Existing item
			$this->modified		= $date->toSql();
			$this->modified_by	= $user->get('id');
		}
		else
		{
			// New external_source. A external_source created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}
			if (empty($this->created_by))
			{
				$this->created_by = $user->get('id');
			}
		}
		
		if (isset($this->alias))
		{
			// Verify that the alias is unique
			$table = JTable::getInstance('external_source', 'SermondistributorTable');

			if ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
			{
				$this->setError(JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_ERROR_UNIQUE_ALIAS'));
				return false;
			}
		}
		
		if (isset($this->url))
		{
			// Convert IDN urls to punycode
			$this->url = JStringPunycode::urlToPunycode($this->url);
		}
		if (isset($this->website))
		{
			// Convert IDN urls to punycode
			$this->website = JStringPunycode::urlToPunycode($this->website);
		}

		return parent::store($updateNulls);
	}
    
	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return  boolean  True on success.
	 */
	public function check()
	{
		if (isset($this->alias))
		{
			// Generate a valid alias
			$this->generateAlias();
            
			$table = JTable::getInstance('external_source', 'sermondistributorTable');

			while ($table->load(array('alias' => $this->alias)) && ($table->id != $this->id || $this->id == 0))
			{
				$this->alias = JString::increment($this->alias, 'dash');
			}
		}
		
		/*
		 * Clean up keywords -- eliminate extra spaces between phrases
		 * and cr (\r) and lf (\n) characters from string.
		 * Only process if not empty.
 		 */
		if (!empty($this->metakey))
		{
			// Array of characters to remove.
			$bad_characters = array("\n", "\r", "\"", "<", ">");

			// Remove bad characters.
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey);

			// Create array using commas as delimiter.
			$keys = explode(',', $after_clean);
			$clean_keys = array();

			foreach ($keys as $key)
			{
				// Ignore blank keywords.
				if (trim($key))
				{
					$clean_keys[] = trim($key);
				}
			}

			// Put array back together delimited by ", "
			$this->metakey = implode(", ", $clean_keys);
		}

		// Clean up description -- eliminate quotes and <> brackets
		if (!empty($this->metadesc))
		{
			// Only process if not empty
			$bad_characters = array("\"", "<", ">");
			$this->metadesc = JString::str_ireplace($bad_characters, "", $this->metadesc);
		}

		// If we don't have any access rules set at this point just use an empty JAccessRules class
		if (!$this->getRules())
		{
			$rules = $this->getDefaultAssetValues('com_sermondistributor.external_source.'.$this->id);
			$this->setRules($rules);
		}
        
		// Set ordering
		if ($this->published < 0)
		{
			// Set ordering to 0 if state is archived or trashed
			$this->ordering = 0;
		}

		return true;
	}

	/**
	 * Gets the default asset values for a component.
	 *
	 * @param   $string  $component  The component asset name to search for
	 *
	 * @return  JAccessRules  The JAccessRules object for the asset
	 */
	protected function getDefaultAssetValues($component, $try = true)
	{
		// Need to find the asset id by the name of the component.
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select($db->quoteName('id'))
			->from($db->quoteName('#__assets'))
			->where($db->quoteName('name') . ' = ' . $db->quote($component));
		$db->setQuery($query);
		$db->execute();
		if ($db->loadRowList())
		{
			// asset alread set so use saved rules
			$assetId = (int) $db->loadResult();
			return JAccess::getAssetRules($assetId);
		}
		// try again
		elseif ($try)
		{
			$try = explode('.',$component);
			$result =  $this->getDefaultAssetValues($try[0], false);
			if ($result instanceof JAccessRules)
			{
				if (isset($try[1]))
				{
					$_result = (string) $result;
					$_result = json_decode($_result);
					foreach ($_result as $name => &$rule)
					{
						$v = explode('.', $name);
						if ($try[1] !== $v[0])
						{
							// remove since it is not part of this view
							unset($_result->$name);
						}
						else
						{
							// clear the value since we inherit
							$rule = array();
						}
					}
					// check if there are any view values remaining
					if (count( (array) $_result))
					{
						$_result = json_encode($_result);
						$_result = array($_result);
						// Instantiate and return the JAccessRules object for the asset rules.
						$rules = new JAccessRules;
						$rules->mergeCollection($_result);

						return $rules;
					}
				}
				return $result;
			}
		}
		return JAccess::getAssetRules(0);
	}

	/**
	 * Method to compute the default name of the asset.
	 * The default name is in the form 'table_name.id'
	 * where id is the value of the primary key of the table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetName()
	{
		$k = $this->_tbl_key;
		return 'com_sermondistributor.external_source.'.(int) $this->$k;
	}

	/**
	 * Method to return the title to use for the asset table.
	 *
	 * @return	string
	 * @since	2.5
	 */
	protected function _getAssetTitle()
	{
		if (isset($this->title))
		{
			return $this->title;
		}
		return '';
	}

	/**
	 * Get the parent asset id for the record
	 *
	 * @return	int
	 * @since	2.5
	 */
	protected function _getAssetParentId(JTable $table = NULL, $id = NULL) 
	{
		$asset = JTable::getInstance('Asset');
		$asset->loadByName('com_sermondistributor');

		return $asset->id;
	}

	/**
	 * This view does not actually have an alias
	 *
	 * @return  bool
	 */
	public function generateAlias()
	{
		return false;
	}

}
