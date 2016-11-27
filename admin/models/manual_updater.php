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

	@version		1.4.0
	@build			27th November, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		manual_updater.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the Joomla modellist library
jimport('joomla.application.component.modellist');

/**
 * Sermondistributor Model for Manual_updater
 */
class SermondistributorModelManual_updater extends JModelList
{
        /**
	 * Model user data.
	 *
	 * @var        strings
	 */
        protected $user;
        protected $userId;
        protected $guest;
        protected $groups;
        protected $levels;
	protected $app;
	protected $input;
	protected $uikitComp;

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
                // Get the current user for authorisation checks
		$this->user		= JFactory::getUser();
		$this->userId		= $this->user->get('id');
		$this->guest		= $this->user->get('guest');
                $this->groups		= $this->user->get('groups');
                $this->authorisedGroups	= $this->user->getAuthorisedGroups();
		$this->levels		= $this->user->getAuthorisedViewLevels();
		$this->app		= JFactory::getApplication();
		$this->input		= $this->app->input;
		$this->initSet		= true; 
		// Make sure all records load, since no pagination allowed.
		$this->setState('list.limit', 0);
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_external_source as a
		$query->select($db->quoteName(
			array('a.id','a.description','a.externalsources','a.build','a.update_method','a.update_timer','a.filetypes','a.oauthtoken','a.permissiontype','a.dropboxoptions','a.sharedurl','a.folder'),
			array('id','description','externalsources','build','update_method','update_timer','filetypes','oauthtoken','permissiontype','dropboxoptions','sharedurl','folder')));
		$query->from($db->quoteName('#__sermondistributor_external_source', 'a'));
		$query->where('a.update_method = 1');
		$query->where('a.published = 1');
		$query->order('a.description ASC');

		// return the query object
		return $query;
	}

	/**
	 * Method to get an array of data items.
	 *
	 * @return  mixed  An array of data items on success, false on failure.
	 */
	public function getItems()
	{
                $user = JFactory::getUser();
                // check if this user has permission to access items
                if (!$user->authorise('manual_updater.access', 'com_sermondistributor'))
                {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::_('Not authorised!'), 'error');
			// redirect away if not a correct (TODO for now we go to default view)
			$app->redirect('index.php?option=com_sermondistributor');
			return false;
                } 
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = JComponentHelper::getParams('com_sermondistributor', true);

		// Get the basic encription.
		$basickey = SermondistributorHelper::getCryptKey('basic');
		// Get the encription object.
		$basic = new FOFEncryptAes($basickey, 128);

		// Convert the parameter fields into objects.
		foreach ($items as $nr => &$item)
		{
			// Always create a slug for sef URL's
			$item->slug = (isset($item->alias)) ? $item->id.':'.$item->alias : $item->id;
			if (SermondistributorHelper::checkString($item->filetypes))
			{
				// Decode filetypes
				$item->filetypes = json_decode($item->filetypes, true);
			}
			if (!empty($item->oauthtoken) && $basickey && !is_numeric($item->oauthtoken) && $item->oauthtoken === base64_encode(base64_decode($item->oauthtoken, true)))
			{
				// Decode oauthtoken
				$item->oauthtoken = rtrim($basic->decryptString($item->oauthtoken), "\0");
			}
		} 

		// return items
		return $items;
	}


	/**
	* Get the uikit needed components
	*
	* @return mixed  An array of objects on success.
	*
	*/
	public function getUikitComp()
	{
		if (isset($this->uikitComp) && SermondistributorHelper::checkArray($this->uikitComp))
		{
			return $this->uikitComp;
		}
		return false;
	}  
}
