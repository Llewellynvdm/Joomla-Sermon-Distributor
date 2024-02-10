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

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		import.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Filter\OutputFilter;
use Joomla\CMS\Installer\InstallerHelper;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;

/***
 * Sermondistributor Import Base Database Model
 */
class SermondistributorModelImport extends BaseDatabaseModel
{
	// set uploading values
	protected $use_streams = false;
	protected $allow_unsafe = false;
	protected $safeFileOptions = [];

	/**
	 * @var object JTable object
	 */
	protected $_table = null;

	/**
	 * @var object JTable object
	 */
	protected $_url = null;

	/**
	 * Model context string.
	 *
	 * @var        string
	 */
	protected $_context = 'com_sermondistributor.import';

	/**
	 * Import Settings
	 */
	protected $getType   = NULL;
	protected $dataType  = NULL;

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return  void
	 *
	 */
	protected function populateState()
	{
		$app = Factory::getApplication('administrator');

		$this->setState('message', $app->getUserState('com_sermondistributor.message'));
		$app->setUserState('com_sermondistributor.message', '');

		// Recall the 'Import from Directory' path.
		$path = $app->getUserStateFromRequest($this->_context . '.import_directory', 'import_directory', $app->get('tmp_path'));
		$this->setState('import.directory', $path);
		parent::populateState();
	}

	/**
	 * Import an spreadsheet from either folder, url or upload.
	 *
	 * @return  boolean result of import
	 *
	 */
	public function import()
	{
		$this->setState('action', 'import');
		$app = Factory::getApplication();
		$session = Factory::getSession();
		$package = null;
		$continue = false;
		// get import type
		$this->getType = $app->input->getString('gettype', NULL);
		// get import type
		$this->dataType = $session->get('dataType_VDM_IMPORTINTO', NULL);

		if ($package === null)
		{
			switch ($this->getType)
			{
				case 'folder':
					// Remember the 'Import from Directory' path.
					$app->getUserStateFromRequest($this->_context . '.import_directory', 'import_directory');
					$package = $this->_getPackageFromFolder();
					break;

				case 'upload':
					$package = $this->_getPackageFromUpload();
					break;

				case 'url':
					$package = $this->_getPackageFromUrl();
					break;

				case 'continue':
					$continue   = true;
					$package    = $session->get('package', null);
					$package    = json_decode($package, true);
					// clear session
					$session->clear('package');
					$session->clear('dataType');
					$session->clear('hasPackage');
					break;

				default:
					$app->setUserState('com_sermondistributor.message', Text::_('COM_SERMONDISTRIBUTOR_IMPORT_NO_IMPORT_TYPE_FOUND'));

					return false;
					break;
			}
		}
		// Was the package valid?
		if (!$package || !$package['type'])
		{
			if (in_array($this->getType, array('upload', 'url')))
			{
				$this->remove($package['packagename']);
			}

			$app->setUserState('com_sermondistributor.message', Text::_('COM_SERMONDISTRIBUTOR_IMPORT_UNABLE_TO_FIND_IMPORT_PACKAGE'));
			return false;
		}

		// first link data to table headers
		if(!$continue){
			$package = json_encode($package);
			$session->set('package', $package);
			$session->set('dataType', $this->dataType);
			$session->set('hasPackage', true);
			return true;
		}

		// set the data
		$headerList = json_decode($session->get($this->dataType.'_VDM_IMPORTHEADERS', false), true);
		if (!$this->setData($package,$this->dataType,$headerList))
		{
			// There was an error importing the package
			$msg = Text::_('COM_SERMONDISTRIBUTOR_IMPORT_ERROR');
			$back = $session->get('backto_VDM_IMPORT', NULL);
			if ($back)
			{
				$app->setUserState('com_sermondistributor.redirect_url', 'index.php?option=com_sermondistributor&view='.$back);
				$session->clear('backto_VDM_IMPORT');
			}
			$result = false;
		}
		else
		{
			// Package imported sucessfully
			$msg = Text::sprintf('COM_SERMONDISTRIBUTOR_IMPORT_SUCCESS', $package['packagename']);
			$back = $session->get('backto_VDM_IMPORT', NULL);
			if ($back)
			{
				$app->setUserState('com_sermondistributor.redirect_url', 'index.php?option=com_sermondistributor&view='.$back);
				$session->clear('backto_VDM_IMPORT');
			}
			$result = true;
		}

		// Set some model state values
		$app->enqueueMessage($msg);

		// remove file after import
		$this->remove($package['packagename']);
		$session->clear($this->getType.'_VDM_IMPORTHEADERS');

		return $result;
	}

	/**
	 * Works out an importation spreadsheet from a HTTP upload
	 *
	 * @return spreadsheet definition or false on failure
	 */
	protected function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$app = Factory::getApplication();
		$input = $app->input;

		// Do not change the filter type 'raw'. We need this to let files containing PHP code to upload. See JInputFiles::get.
		$userfile = $input->files->get('import_package', null, 'raw');

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads'))
		{
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_WARNIMPORTFILE'), 'warning');
			return false;
		}

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile))
		{
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_NO_FILE_SELECTED'), 'warning');
			return false;
		}

		// Check if there was a problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1)
		{
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_WARNIMPORTUPLOADERROR'), 'warning');
			return false;
		}

		// Build the appropriate paths
		$config = Factory::getConfig();
		$tmp_dest = $config->get('tmp_path') . '/' . $userfile['name'];
		$tmp_src = $userfile['tmp_name'];

		// Move uploaded file
		$p_file = File::upload($tmp_src, $tmp_dest, $this->use_streams, $this->allow_unsafe, $this->safeFileOptions);

		// Was the package downloaded?
		if (!$p_file)
		{
			$session = Factory::getSession();
			$session->clear('package');
			$session->clear('dataType');
			$session->clear('hasPackage');
			// was not uploaded
			return false;
		}

		// check that this is a valid spreadsheet
		$package = $this->check($userfile['name']);

		return $package;
	}

	/**
	 * Import an spreadsheet from a directory
	 *
	 * @return  array  Spreadsheet details or false on failure
	 *
	 */
	protected function _getPackageFromFolder()
	{
		$app = Factory::getApplication();
		$input = $app->input;

		// Get the path to the package to import
		$p_dir = $input->getString('import_directory');
		$p_dir = Path::clean($p_dir);
		// Did you give us a valid path?
		if (!file_exists($p_dir))
		{
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_PLEASE_ENTER_A_PACKAGE_DIRECTORY'), 'warning');
			return false;
		}

		// Detect the package type
		$type = $this->getType;

		// Did you give us a valid package?
		if (!$type)
		{
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_PATH_DOES_NOT_HAVE_A_VALID_PACKAGE'), 'warning');
		}

		// check the extention
		if(!$this->checkExtension($p_dir))
		{
			// set error message
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_DOES_NOT_HAVE_A_VALID_FILE_TYPE'), 'warning');
			return false;
		}

		$package['packagename'] = null;
		$package['dir'] = $p_dir;
		$package['type'] = $type;

		return $package;
	}

	/**
	 * Import an spreadsheet from a URL
	 *
	 * @return  Package details or false on failure
	 *
	 */
	protected function _getPackageFromUrl()
	{
		$app = Factory::getApplication();
		$input = $app->input;

		// Get the URL of the package to import
		$url = $input->getString('import_url');

		// Did you give us a URL?
		if (!$url)
		{
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_ENTER_A_URL'), 'warning');
			return false;
		}

		// Download the package at the URL given
		$p_file = InstallerHelper::downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file)
		{
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_INVALID_URL'), 'warning');
			return false;
		}

		// check that this is a valid spreadsheet
		$package = $this->check($p_file);

		return $package;
	}

	/**
	 * Check a file and verifies it as a spreadsheet file
	 * Supports .csv .xlsx .xls and .ods
	 *
	 * @param   string  $p_filename  The uploaded package filename or import directory
	 *
	 * @return  array  of elements
	 *
	 */
	protected function check($archivename)
	{
		$app = Factory::getApplication();
		// Clean the name
		$archivename = Path::clean($archivename);

		// check the extention
		if(!$this->checkExtension($archivename))
		{
			// Cleanup the import files
			$this->remove($archivename);
			$app->enqueueMessage(Text::_('COM_SERMONDISTRIBUTOR_IMPORT_MSG_DOES_NOT_HAVE_A_VALID_FILE_TYPE'), 'warning');
			return false;
		}

		$config = Factory::getConfig();
		// set Package Name
		$check['packagename'] = $archivename;

		// set directory
		$check['dir'] = $config->get('tmp_path'). '/' .$archivename;

		// set type
		$check['type'] = $this->getType;

		return $check;
	}

	/**
	 * Check the extension
	 *
	 * @param   string  $file    Name of the uploaded file
	 *
	 * @return  boolean  True on success
	 *
	 */
	protected function checkExtension($file)
	{
		// check the extention
		switch(strtolower(pathinfo($file, PATHINFO_EXTENSION)))
		{
			case 'xls':
			case 'ods':
			case 'csv':
			return true;
			break;
		}
		return false;
	}

	/**
	 * Clean up temporary uploaded spreadsheet
	 *
	 * @param   string  $package    Name of the uploaded spreadsheet file
	 *
	 * @return  boolean  True on success
	 *
	 */
	protected function remove($package)
	{
		jimport('joomla.filesystem.file');

		$config = Factory::getConfig();
		$package = $config->get('tmp_path'). '/' .$package;

		// Is the package file a valid file?
		if (is_file($package))
		{
			File::delete($package);
		}
		elseif (is_file(Path::clean($package)))
		{
			// It might also be just a base filename
			File::delete(Path::clean($package));
		}
	}

	/**
	* Set the data from the spreadsheet to the database
	*
	* @param string  $package Paths to the uploaded package file
	*
	* @return  boolean false on failure
	*
	**/
	protected function setData($package,$table,$target_headers)
	{
		if (UtilitiesArrayHelper::check($target_headers))
		{
			// make sure the file is loaded
			SermondistributorHelper::composerAutoload('phpspreadsheet');
			$jinput = Factory::getApplication()->input;
			foreach($target_headers as $header)
			{
				if (($column = $jinput->getString($header, false)) !== false ||
					($column = $jinput->getString(strtolower($header), false)) !== false)
				{
					$data['target_headers'][$header] = $column;
				}
				else
				{
					$data['target_headers'][$header] = null;
				}
			}
			// set the data
			if(isset($package['dir']))
			{
				$inputFileType = IOFactory::identify($package['dir']);
				$excelReader = IOFactory::createReader($inputFileType);
				$excelReader->setReadDataOnly(true);
				$excelObj = $excelReader->load($package['dir']);
				$data['array'] = $excelObj->getActiveSheet()->toArray(null, true,true,true);
				$excelObj->disconnectWorksheets();
				unset($excelObj);
				return $this->save($data, $table);
			}
		}
		return false;
	}

	/**
	* Save the data from the file to the database
	*
	* @param string  $package Paths to the uploaded package file
	*
	* @return  boolean false on failure
	*
	**/
	protected function save($data,$table)
	{
		// import the data if there is any
		if(UtilitiesArrayHelper::check($data['array']))
		{
			// get user object
			$user		= Factory::getUser();
			// remove header if it has headers
			$id_key	= $data['target_headers']['id'];
			$published_key	= $data['target_headers']['published'];
			$ordering_key	= $data['target_headers']['ordering'];
			// get the first array set
			$firstSet = reset($data['array']);

			// check if first array is a header array and remove if true
			if($firstSet[$id_key] == 'id' || $firstSet[$published_key] == 'published' || $firstSet[$ordering_key] == 'ordering')
			{
				array_shift($data['array']);
			}
			
			// make sure there is still values in array and that it was not only headers
			if(UtilitiesArrayHelper::check($data['array']) && $user->authorise($table.'.import', 'com_sermondistributor') && $user->authorise('core.import', 'com_sermondistributor'))
			{
				// set target.
				$target	= array_flip($data['target_headers']);
				// Get a db connection.
				$db = Factory::getDbo();
				// set some defaults
				$todayDate		= Factory::getDate()->toSql();
				// get global action permissions
				$canDo			= SermondistributorHelper::getActions($table);
				$canEdit		= $canDo->get('core.edit');
				$canState		= $canDo->get('core.edit.state');
				$canCreate		= $canDo->get('core.create');
				$hasAlias		= $this->getAliasesUsed($table);
				// prosses the data
				foreach($data['array'] as $row)
				{
					$found = false;
					if (isset($row[$id_key]) && is_numeric($row[$id_key]) && $row[$id_key] > 0)
					{
						// raw items import & update!
						$query = $db->getQuery(true);
						$query
							->select('version')
							->from($db->quoteName('#__sermondistributor_'.$table))
							->where($db->quoteName('id') . ' = '. $db->quote($row[$id_key]));
						// Reset the query using our newly populated query object.
						$db->setQuery($query);
						$db->execute();
						$found = $db->getNumRows();
					}
					
					if($found && $canEdit)
					{
						// update item
						$id		= $row[$id_key];
						$version	= $db->loadResult();
						// reset all buckets
						$query		= $db->getQuery(true);
						$fields	= array();
						// Fields to update.
						foreach($row as $key => $cell)
						{
							// ignore column
							if ('IGNORE' == $target[$key])
							{
								continue;
							}
							// update modified
							if ('modified_by' == $target[$key])
							{
								continue;
							}
							// update modified
							if ('modified' == $target[$key])
							{
								continue;
							}
							// update version
							if ('version' == $target[$key])
							{
								$cell = (int) $version + 1;
							}
							// verify publish authority
							if ('published' == $target[$key] && !$canState)
							{
								continue;
							}
							// set to update array
							if(in_array($key, $data['target_headers']) && is_numeric($cell))
							{
								$fields[] = $db->quoteName($target[$key]) . ' = ' . $cell;
							}
							elseif(in_array($key, $data['target_headers']) && is_string($cell))
							{
								$fields[] = $db->quoteName($target[$key]) . ' = ' . $db->quote($cell);
							}
							elseif(in_array($key, $data['target_headers']) && is_null($cell))
							{
								// if import data is null then set empty
								$fields[] = $db->quoteName($target[$key]) . " = ''";
							}
						}
						// load the defaults
						$fields[]	= $db->quoteName('modified_by') . ' = ' . $db->quote($user->id);
						$fields[]	= $db->quoteName('modified') . ' = ' . $db->quote($todayDate);
						// Conditions for which records should be updated.
						$conditions = array(
							$db->quoteName('id') . ' = ' . $id
						);
						
						$query->update($db->quoteName('#__sermondistributor_'.$table))->set($fields)->where($conditions);
						$db->setQuery($query);
						$db->execute();
					}
					elseif ($canCreate)
					{
						// insert item
						$query = $db->getQuery(true);
						// reset all buckets
						$columns	= array();
						$values	= array();
						$version	= false;
						// Insert columns. Insert values.
						foreach($row as $key => $cell)
						{
							// ignore column
							if ('IGNORE' == $target[$key])
							{
								continue;
							}
							// remove id
							if ('id' == $target[$key])
							{
								continue;
							}
							// update created
							if ('created_by' == $target[$key])
							{
								continue;
							}
							// update created
							if ('created' == $target[$key])
							{
								continue;
							}
							// Make sure the alias is incremented
							if ('alias' == $target[$key])
							{
								$cell = $this->getAlias($cell,$table);
							}
							// update version
							if ('version' == $target[$key])
							{
								$cell = 1;
								$version = true;
							}
							// set to insert array
							if(in_array($key, $data['target_headers']) && is_numeric($cell))
							{
								$columns[]	= $target[$key];
								$values[]	= $cell;
							}
							elseif(in_array($key, $data['target_headers']) && is_string($cell))
							{
								$columns[]	= $target[$key];
								$values[]	= $db->quote($cell);
							}
							elseif(in_array($key, $data['target_headers']) && is_null($cell))
							{
								// if import data is null then set empty
								$columns[]	= $target[$key];
								$values[]	= "''";
							}
						}
						// load the defaults
						$columns[]	= 'created_by';
						$values[]	= $db->quote($user->id);
						$columns[]	= 'created';
						$values[]	= $db->quote($todayDate);
						if (!$version)
						{
							$columns[]	= 'version';
							$values[]	= 1;
						}
						// Prepare the insert query.
						$query
							->insert($db->quoteName('#__sermondistributor_'.$table))
							->columns($db->quoteName($columns))
							->values(implode(',', $values));
						// Set the query using our newly populated query object and execute it.
						$db->setQuery($query);
						$done = $db->execute();
						if ($done)
						{
							$aId = $db->insertid();
							// make sure the access of asset is set
							SermondistributorHelper::setAsset($aId,$table);
						}
					}
					else
					{
						return false;
					}
				}
				return true;
			}
		}
		return false;
	}

	protected function getAlias($name,$type = false)
	{
		// sanitize the name to an alias
		if (Factory::getConfig()->get('unicodeslugs') == 1)
		{
			$alias = OutputFilter::stringURLUnicodeSlug($name);
		}
		else
		{
			$alias = OutputFilter::stringURLSafe($name);
		}
		// must be a uniqe alias
		if ($type)
		{
			return $this->getUniqe($alias,'alias',$type);
		}
		return $alias;
	}

	/**
	 * Method to generate a uniqe value.
	 *
	 * @param   string  $field name.
	 * @param   string  $value data.
	 * @param   string  $type table.
	 *
	 * @return  string  New value.
	 */
	protected function getUniqe($value,$field,$type)
	{
		// insure the filed is always uniqe
		while (isset($this->uniqeValueArray[$type][$field][$value]))
		{
			$value = StringHelper::increment($value, 'dash');
		}
		$this->uniqeValueArray[$type][$field][$value] = $value;
		return $value;
	}

	protected function getAliasesUsed($table)
	{
		// Get a db connection.
		$db = Factory::getDbo();
		// first we check if there is a alias column
		$columns = $db->getTableColumns('#__sermondistributor_'.$table);
		if(isset($columns['alias'])){
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('alias')));
			$query->from($db->quoteName('#__sermondistributor_'.$table));
			$db->setQuery($query);
			$db->execute();
			if ($db->getNumRows())
			{
				$aliases = $db->loadColumn();
				foreach($aliases as $alias)
				{
					$this->uniqeValueArray[$table]['alias'][$alias] = $alias;
				}
			}
			return true;
		}
		return false;
	}
}
