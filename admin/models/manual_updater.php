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
	@subpackage		manual_updater.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Utilities\ArrayHelper;
use VDM\Joomla\FOF\Encrypt\AES;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\JsonHelper;

/**
 * Sermondistributor List Model for Manual_updater
 */
class SermondistributorModelManual_updater extends ListModel
{
	/**
	 * Model user data.
	 *
	 * @var  strings
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
		$this->user = Factory::getUser();
		$this->userId = $this->user->get('id');
		$this->guest = $this->user->get('guest');
		$this->groups = $this->user->get('groups');
		$this->authorisedGroups    = $this->user->getAuthorisedGroups();
		$this->levels = $this->user->getAuthorisedViewLevels();
		$this->app = Factory::getApplication();
		$this->input = $this->app->input;
		$this->initSet = true;
		// Make sure all records load, since no pagination allowed.
		$this->setState('list.limit', 0);
		// Get a db connection.
		$db = Factory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Get from #__sermondistributor_external_source as a
		$query->select($db->quoteName(
			array('a.id','a.description','a.externalsources','a.build','a.update_method','a.update_timer','a.filetypes','a.oauthtoken','a.permissiontype','a.dropboxoptions','a.sharedurl','a.folder'),
			array('id','description','externalsources','build','update_method','update_timer','filetypes','oauthtoken','permissiontype','dropboxoptions','sharedurl','folder')));
		$query->from($db->quoteName('#__sermondistributor_external_source', 'a'));
		// Get where a.update_method is 1
		$query->where('a.update_method = 1');
		// Get where a.published is 1
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
		$user = Factory::getUser();
		// check if this user has permission to access items
		if (!$user->authorise('manual_updater.access', 'com_sermondistributor'))
		{
			$app = Factory::getApplication();
			$app->enqueueMessage(Text::_('Not authorised!'), 'error');
			// redirect away if not a correct (TODO for now we go to default view)
			$app->redirect('index.php?option=com_sermondistributor');
			return false;
		}
		// load parent items
		$items = parent::getItems();

		// Get the global params
		$globalParams = ComponentHelper::getParams('com_sermondistributor', true);

		// Get the basic encryption.
		$basickey = SermondistributorHelper::getCryptKey('basic');
		// Get the encryption object.
		$basic = new AES($basickey);

		// Insure all item fields are adapted where needed.
		if (UtilitiesArrayHelper::check($items))
		{
			foreach ($items as $nr => &$item)
			{
				// Always create a slug for sef URL's
				$item->slug = ($item->id ?? '0') . (isset($item->alias) ? ':' . $item->alias : '');
				// Check if we can decode filetypes
				if (isset($item->filetypes) && JsonHelper::check($item->filetypes))
				{
					// Decode filetypes
					$item->filetypes = json_decode($item->filetypes, true);
				}
				// Check if we can decode oauthtoken
				if (!empty($item->oauthtoken) && $basickey && !is_numeric($item->oauthtoken) && $item->oauthtoken === base64_encode(base64_decode($item->oauthtoken, true)))
				{
					// Decode oauthtoken
					$item->oauthtoken = rtrim($basic->decryptString($item->oauthtoken), "\0");
				}
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
		if (isset($this->uikitComp) && UtilitiesArrayHelper::check($this->uikitComp))
		{
			return $this->uikitComp;
		}
		return false;
	}
}
