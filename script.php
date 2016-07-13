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

	@version		1.3.3
	@build			13th July, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		script.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');
jimport('joomla.installer.installer');
jimport('joomla.installer.helper');

/**
 * Script File of Sermondistributor Component
 */
class com_sermondistributorInstallerScript
{
	/**
	 * method to install the component
	 *
	 *
	 * @return void
	 */
	function install($parent)
	{

	}

	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstall($parent)
	{
		// [Interpretation 3540] Get Application object
		$app = JFactory::getApplication();

		// [Interpretation 3542] Get The Database object
		$db = JFactory::getDbo();

		// [Interpretation 3551] Create a new query object.
		$query = $db->getQuery(true);
		// [Interpretation 3553] Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// [Interpretation 3556] Where Preacher alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.preacher') );
		$db->setQuery($query);
		// [Interpretation 3559] Execute query to see if alias is found
		$db->execute();
		$preacher_found = $db->getNumRows();
		// [Interpretation 3562] Now check if there were any rows
		if ($preacher_found)
		{
			// [Interpretation 3565] Since there are load the needed  preacher type ids
			$preacher_ids = $db->loadColumn();
			// [Interpretation 3569] Remove Preacher from the content type table
			$preacher_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.preacher') );
			// [Interpretation 3571] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($preacher_condition);
			$db->setQuery($query);
			// [Interpretation 3576] Execute the query to remove Preacher items
			$preacher_done = $db->execute();
			if ($preacher_done);
			{
				// [Interpretation 3580] If succesfully remove Preacher add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.preacher) type alias was removed from the <b>#__content_type</b> table'));
			}

			// [Interpretation 3586] Remove Preacher items from the contentitem tag map table
			$preacher_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.preacher') );
			// [Interpretation 3588] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($preacher_condition);
			$db->setQuery($query);
			// [Interpretation 3593] Execute the query to remove Preacher items
			$preacher_done = $db->execute();
			if ($preacher_done);
			{
				// [Interpretation 3597] If succesfully remove Preacher add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.preacher) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// [Interpretation 3603] Remove Preacher items from the ucm content table
			$preacher_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.preacher') );
			// [Interpretation 3605] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($preacher_condition);
			$db->setQuery($query);
			// [Interpretation 3610] Execute the query to remove Preacher items
			$preacher_done = $db->execute();
			if ($preacher_done);
			{
				// [Interpretation 3614] If succesfully remove Preacher add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.preacher) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// [Interpretation 3620] Make sure that all the Preacher items are cleared from DB
			foreach ($preacher_ids as $preacher_id)
			{
				// [Interpretation 3625] Remove Preacher items from the ucm base table
				$preacher_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $preacher_id);
				// [Interpretation 3627] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($preacher_condition);
				$db->setQuery($query);
				// [Interpretation 3632] Execute the query to remove Preacher items
				$db->execute();

				// [Interpretation 3636] Remove Preacher items from the ucm history table
				$preacher_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $preacher_id);
				// [Interpretation 3638] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($preacher_condition);
				$db->setQuery($query);
				// [Interpretation 3643] Execute the query to remove Preacher items
				$db->execute();
			}
		}

		// [Interpretation 3551] Create a new query object.
		$query = $db->getQuery(true);
		// [Interpretation 3553] Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// [Interpretation 3556] Where Sermon alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon') );
		$db->setQuery($query);
		// [Interpretation 3559] Execute query to see if alias is found
		$db->execute();
		$sermon_found = $db->getNumRows();
		// [Interpretation 3562] Now check if there were any rows
		if ($sermon_found)
		{
			// [Interpretation 3565] Since there are load the needed  sermon type ids
			$sermon_ids = $db->loadColumn();
			// [Interpretation 3569] Remove Sermon from the content type table
			$sermon_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon') );
			// [Interpretation 3571] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($sermon_condition);
			$db->setQuery($query);
			// [Interpretation 3576] Execute the query to remove Sermon items
			$sermon_done = $db->execute();
			if ($sermon_done);
			{
				// [Interpretation 3580] If succesfully remove Sermon add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon) type alias was removed from the <b>#__content_type</b> table'));
			}

			// [Interpretation 3586] Remove Sermon items from the contentitem tag map table
			$sermon_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon') );
			// [Interpretation 3588] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($sermon_condition);
			$db->setQuery($query);
			// [Interpretation 3593] Execute the query to remove Sermon items
			$sermon_done = $db->execute();
			if ($sermon_done);
			{
				// [Interpretation 3597] If succesfully remove Sermon add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// [Interpretation 3603] Remove Sermon items from the ucm content table
			$sermon_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.sermon') );
			// [Interpretation 3605] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($sermon_condition);
			$db->setQuery($query);
			// [Interpretation 3610] Execute the query to remove Sermon items
			$sermon_done = $db->execute();
			if ($sermon_done);
			{
				// [Interpretation 3614] If succesfully remove Sermon add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// [Interpretation 3620] Make sure that all the Sermon items are cleared from DB
			foreach ($sermon_ids as $sermon_id)
			{
				// [Interpretation 3625] Remove Sermon items from the ucm base table
				$sermon_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_id);
				// [Interpretation 3627] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($sermon_condition);
				$db->setQuery($query);
				// [Interpretation 3632] Execute the query to remove Sermon items
				$db->execute();

				// [Interpretation 3636] Remove Sermon items from the ucm history table
				$sermon_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_id);
				// [Interpretation 3638] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($sermon_condition);
				$db->setQuery($query);
				// [Interpretation 3643] Execute the query to remove Sermon items
				$db->execute();
			}
		}

		// [Interpretation 3551] Create a new query object.
		$query = $db->getQuery(true);
		// [Interpretation 3553] Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// [Interpretation 3556] Where Sermon catid alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermons.category') );
		$db->setQuery($query);
		// [Interpretation 3559] Execute query to see if alias is found
		$db->execute();
		$sermon_catid_found = $db->getNumRows();
		// [Interpretation 3562] Now check if there were any rows
		if ($sermon_catid_found)
		{
			// [Interpretation 3565] Since there are load the needed  sermon_catid type ids
			$sermon_catid_ids = $db->loadColumn();
			// [Interpretation 3569] Remove Sermon catid from the content type table
			$sermon_catid_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermons.category') );
			// [Interpretation 3571] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($sermon_catid_condition);
			$db->setQuery($query);
			// [Interpretation 3576] Execute the query to remove Sermon catid items
			$sermon_catid_done = $db->execute();
			if ($sermon_catid_done);
			{
				// [Interpretation 3580] If succesfully remove Sermon catid add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermons.category) type alias was removed from the <b>#__content_type</b> table'));
			}

			// [Interpretation 3586] Remove Sermon catid items from the contentitem tag map table
			$sermon_catid_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermons.category') );
			// [Interpretation 3588] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($sermon_catid_condition);
			$db->setQuery($query);
			// [Interpretation 3593] Execute the query to remove Sermon catid items
			$sermon_catid_done = $db->execute();
			if ($sermon_catid_done);
			{
				// [Interpretation 3597] If succesfully remove Sermon catid add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermons.category) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// [Interpretation 3603] Remove Sermon catid items from the ucm content table
			$sermon_catid_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.sermons.category') );
			// [Interpretation 3605] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($sermon_catid_condition);
			$db->setQuery($query);
			// [Interpretation 3610] Execute the query to remove Sermon catid items
			$sermon_catid_done = $db->execute();
			if ($sermon_catid_done);
			{
				// [Interpretation 3614] If succesfully remove Sermon catid add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermons.category) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// [Interpretation 3620] Make sure that all the Sermon catid items are cleared from DB
			foreach ($sermon_catid_ids as $sermon_catid_id)
			{
				// [Interpretation 3625] Remove Sermon catid items from the ucm base table
				$sermon_catid_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_catid_id);
				// [Interpretation 3627] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($sermon_catid_condition);
				$db->setQuery($query);
				// [Interpretation 3632] Execute the query to remove Sermon catid items
				$db->execute();

				// [Interpretation 3636] Remove Sermon catid items from the ucm history table
				$sermon_catid_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_catid_id);
				// [Interpretation 3638] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($sermon_catid_condition);
				$db->setQuery($query);
				// [Interpretation 3643] Execute the query to remove Sermon catid items
				$db->execute();
			}
		}

		// [Interpretation 3551] Create a new query object.
		$query = $db->getQuery(true);
		// [Interpretation 3553] Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// [Interpretation 3556] Where Series alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.series') );
		$db->setQuery($query);
		// [Interpretation 3559] Execute query to see if alias is found
		$db->execute();
		$series_found = $db->getNumRows();
		// [Interpretation 3562] Now check if there were any rows
		if ($series_found)
		{
			// [Interpretation 3565] Since there are load the needed  series type ids
			$series_ids = $db->loadColumn();
			// [Interpretation 3569] Remove Series from the content type table
			$series_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.series') );
			// [Interpretation 3571] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($series_condition);
			$db->setQuery($query);
			// [Interpretation 3576] Execute the query to remove Series items
			$series_done = $db->execute();
			if ($series_done);
			{
				// [Interpretation 3580] If succesfully remove Series add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.series) type alias was removed from the <b>#__content_type</b> table'));
			}

			// [Interpretation 3586] Remove Series items from the contentitem tag map table
			$series_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.series') );
			// [Interpretation 3588] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($series_condition);
			$db->setQuery($query);
			// [Interpretation 3593] Execute the query to remove Series items
			$series_done = $db->execute();
			if ($series_done);
			{
				// [Interpretation 3597] If succesfully remove Series add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.series) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// [Interpretation 3603] Remove Series items from the ucm content table
			$series_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.series') );
			// [Interpretation 3605] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($series_condition);
			$db->setQuery($query);
			// [Interpretation 3610] Execute the query to remove Series items
			$series_done = $db->execute();
			if ($series_done);
			{
				// [Interpretation 3614] If succesfully remove Series add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.series) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// [Interpretation 3620] Make sure that all the Series items are cleared from DB
			foreach ($series_ids as $series_id)
			{
				// [Interpretation 3625] Remove Series items from the ucm base table
				$series_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $series_id);
				// [Interpretation 3627] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($series_condition);
				$db->setQuery($query);
				// [Interpretation 3632] Execute the query to remove Series items
				$db->execute();

				// [Interpretation 3636] Remove Series items from the ucm history table
				$series_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $series_id);
				// [Interpretation 3638] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($series_condition);
				$db->setQuery($query);
				// [Interpretation 3643] Execute the query to remove Series items
				$db->execute();
			}
		}

		// [Interpretation 3551] Create a new query object.
		$query = $db->getQuery(true);
		// [Interpretation 3553] Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// [Interpretation 3556] Where Statistic alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.statistic') );
		$db->setQuery($query);
		// [Interpretation 3559] Execute query to see if alias is found
		$db->execute();
		$statistic_found = $db->getNumRows();
		// [Interpretation 3562] Now check if there were any rows
		if ($statistic_found)
		{
			// [Interpretation 3565] Since there are load the needed  statistic type ids
			$statistic_ids = $db->loadColumn();
			// [Interpretation 3569] Remove Statistic from the content type table
			$statistic_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.statistic') );
			// [Interpretation 3571] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($statistic_condition);
			$db->setQuery($query);
			// [Interpretation 3576] Execute the query to remove Statistic items
			$statistic_done = $db->execute();
			if ($statistic_done);
			{
				// [Interpretation 3580] If succesfully remove Statistic add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.statistic) type alias was removed from the <b>#__content_type</b> table'));
			}

			// [Interpretation 3586] Remove Statistic items from the contentitem tag map table
			$statistic_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.statistic') );
			// [Interpretation 3588] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($statistic_condition);
			$db->setQuery($query);
			// [Interpretation 3593] Execute the query to remove Statistic items
			$statistic_done = $db->execute();
			if ($statistic_done);
			{
				// [Interpretation 3597] If succesfully remove Statistic add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.statistic) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// [Interpretation 3603] Remove Statistic items from the ucm content table
			$statistic_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.statistic') );
			// [Interpretation 3605] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($statistic_condition);
			$db->setQuery($query);
			// [Interpretation 3610] Execute the query to remove Statistic items
			$statistic_done = $db->execute();
			if ($statistic_done);
			{
				// [Interpretation 3614] If succesfully remove Statistic add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.statistic) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// [Interpretation 3620] Make sure that all the Statistic items are cleared from DB
			foreach ($statistic_ids as $statistic_id)
			{
				// [Interpretation 3625] Remove Statistic items from the ucm base table
				$statistic_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $statistic_id);
				// [Interpretation 3627] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($statistic_condition);
				$db->setQuery($query);
				// [Interpretation 3632] Execute the query to remove Statistic items
				$db->execute();

				// [Interpretation 3636] Remove Statistic items from the ucm history table
				$statistic_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $statistic_id);
				// [Interpretation 3638] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($statistic_condition);
				$db->setQuery($query);
				// [Interpretation 3643] Execute the query to remove Statistic items
				$db->execute();
			}
		}

		// [Interpretation 3551] Create a new query object.
		$query = $db->getQuery(true);
		// [Interpretation 3553] Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// [Interpretation 3556] Where Help_document alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.help_document') );
		$db->setQuery($query);
		// [Interpretation 3559] Execute query to see if alias is found
		$db->execute();
		$help_document_found = $db->getNumRows();
		// [Interpretation 3562] Now check if there were any rows
		if ($help_document_found)
		{
			// [Interpretation 3565] Since there are load the needed  help_document type ids
			$help_document_ids = $db->loadColumn();
			// [Interpretation 3569] Remove Help_document from the content type table
			$help_document_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.help_document') );
			// [Interpretation 3571] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// [Interpretation 3576] Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done);
			{
				// [Interpretation 3580] If succesfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.help_document) type alias was removed from the <b>#__content_type</b> table'));
			}

			// [Interpretation 3586] Remove Help_document items from the contentitem tag map table
			$help_document_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.help_document') );
			// [Interpretation 3588] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// [Interpretation 3593] Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done);
			{
				// [Interpretation 3597] If succesfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.help_document) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// [Interpretation 3603] Remove Help_document items from the ucm content table
			$help_document_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.help_document') );
			// [Interpretation 3605] Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// [Interpretation 3610] Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done);
			{
				// [Interpretation 3614] If succesfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.help_document) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// [Interpretation 3620] Make sure that all the Help_document items are cleared from DB
			foreach ($help_document_ids as $help_document_id)
			{
				// [Interpretation 3625] Remove Help_document items from the ucm base table
				$help_document_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $help_document_id);
				// [Interpretation 3627] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($help_document_condition);
				$db->setQuery($query);
				// [Interpretation 3632] Execute the query to remove Help_document items
				$db->execute();

				// [Interpretation 3636] Remove Help_document items from the ucm history table
				$help_document_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $help_document_id);
				// [Interpretation 3638] Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($help_document_condition);
				$db->setQuery($query);
				// [Interpretation 3643] Execute the query to remove Help_document items
				$db->execute();
			}
		}

		// [Interpretation 3651] If All related items was removed queued success message.
		$app->enqueueMessage(JText::_('All related items was removed from the <b>#__ucm_base</b> table'));
		$app->enqueueMessage(JText::_('All related items was removed from the <b>#__ucm_history</b> table'));

		// [Interpretation 3656] Remove sermondistributor assets from the assets table
		$sermondistributor_condition = array( $db->quoteName('name') . ' LIKE ' . $db->quote('com_sermondistributor%') );

		// [Interpretation 3658] Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__assets'));
		$query->where($sermondistributor_condition);
		$db->setQuery($query);
		$help_document_done = $db->execute();
		if ($help_document_done);
		{
			// [Interpretation 3666] If succesfully remove sermondistributor add queued success message.
			$app->enqueueMessage(JText::_('All related items was removed from the <b>#__assets</b> table'));
		}

		// little notice as after service, in case of bad experience with component.
		echo '<h2>Did something go wrong? Are you disappointed?</h2>
		<p>Please let me know at <a href="mailto:llewellyn@vdm.io">llewellyn@vdm.io</a>.
		<br />We at Vast Development Method are committed to building extensions that performs proficiently! You can help us, really!
		<br />Send me your thoughts on improvements that is needed, trust me, I will be very grateful!
		<br />Visit us at <a href="https://www.vdm.io/" target="_blank">https://www.vdm.io/</a> today!</p>';
	}

	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function update($parent)
	{
		
	}

	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflight($type, $parent)
	{
		if ($type == 'uninstall')
		{        	
			return true;
		}
		
		$app = JFactory::getApplication();
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.4.1'))
		{
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.4.1 before continuing!', 'error');
			return false;
		}
	}

	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflight($type, $parent)
	{
		// set the default component settings
		if ($type == 'install')
		{

			// [Interpretation 3716] Get The Database object
			$db = JFactory::getDbo();

			// [Interpretation 3723] Create the preacher content type object.
			$preacher = new stdClass();
			$preacher->type_title = 'Sermondistributor Preacher';
			$preacher->type_alias = 'com_sermondistributor.preacher';
			$preacher->table = '{"special": {"dbtable": "#__sermondistributor_preacher","key": "id","type": "Preacher","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$preacher->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","alias":"alias","email":"email","website":"website","icon":"icon"}}';
			$preacher->router = 'SermondistributorHelperRoute::getPreacherRoute';
			$preacher->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/preacher.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// [Interpretation 3741] Set the object into the content types table.
			$preacher_Inserted = $db->insertObject('#__content_types', $preacher);

			// [Interpretation 3723] Create the sermon content type object.
			$sermon = new stdClass();
			$sermon->type_title = 'Sermondistributor Sermon';
			$sermon->type_alias = 'com_sermondistributor.sermon';
			$sermon->table = '{"special": {"dbtable": "#__sermondistributor_sermon","key": "id","type": "Sermon","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$sermon->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "catid","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","preacher":"preacher","series":"series","short_description":"short_description","link_type":"link_type","source":"source","icon":"icon","tags":"tags","local_files":"local_files","description":"description","alias":"alias","not_required":"not_required","manual_files":"manual_files","scripture":"scripture","url":"url","build":"build","auto_sermons":"auto_sermons"}}';
			$sermon->router = 'SermondistributorHelperRoute::getSermonRoute';
			$sermon->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/sermon.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required","auto_sermons"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","preacher","series","catid","link_type","source","not_required","build"],"displayLookup": [{"sourceColumn": "catid","targetTable": "#__categories","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "local_files","targetTable": "","targetColumn": "","displayColumn": ""},{"sourceColumn": "manual_files","targetTable": "","targetColumn": "","displayColumn": ""}]}';

			// [Interpretation 3741] Set the object into the content types table.
			$sermon_Inserted = $db->insertObject('#__content_types', $sermon);

			// [Interpretation 3723] Create the sermon catagory content type object.
			$sermon_catagory = new stdClass();
			$sermon_catagory->type_title = 'Sermondistributor Sermon Catid';
			$sermon_catagory->type_alias = 'com_sermondistributor.sermons.category';
			$sermon_catagory->table = '{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}';
			$sermon_catagory->field_mappings = '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}';
			$sermon_catagory->router = 'SermondistributorHelperRoute::getCategoryRoute';
			$sermon_catagory->content_history_options = '{"formFile":"administrator\/components\/com_categories\/models\/forms\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}';

			// [Interpretation 3741] Set the object into the content types table.
			$sermon_catagory_Inserted = $db->insertObject('#__content_types', $sermon_catagory);

			// [Interpretation 3723] Create the series content type object.
			$series = new stdClass();
			$series->type_title = 'Sermondistributor Series';
			$series->type_alias = 'com_sermondistributor.series';
			$series->table = '{"special": {"dbtable": "#__sermondistributor_series","key": "id","type": "Series","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$series->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","alias":"alias","icon":"icon","scripture":"scripture"}}';
			$series->router = 'SermondistributorHelperRoute::getSeriesRoute';
			$series->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/series.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// [Interpretation 3741] Set the object into the content types table.
			$series_Inserted = $db->insertObject('#__content_types', $series);

			// [Interpretation 3723] Create the statistic content type object.
			$statistic = new stdClass();
			$statistic->type_title = 'Sermondistributor Statistic';
			$statistic->type_alias = 'com_sermondistributor.statistic';
			$statistic->table = '{"special": {"dbtable": "#__sermondistributor_statistic","key": "id","type": "Statistic","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$statistic->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "filename","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"filename":"filename","sermon":"sermon","preacher":"preacher","series":"series","counter":"counter"}}';
			$statistic->router = 'SermondistributorHelperRoute::getStatisticRoute';
			$statistic->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/statistic.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","sermon","preacher","series","counter"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"}]}';

			// [Interpretation 3741] Set the object into the content types table.
			$statistic_Inserted = $db->insertObject('#__content_types', $statistic);

			// [Interpretation 3723] Create the help_document content type object.
			$help_document = new stdClass();
			$help_document->type_title = 'Sermondistributor Help_document';
			$help_document->type_alias = 'com_sermondistributor.help_document';
			$help_document->table = '{"special": {"dbtable": "#__sermondistributor_help_document","key": "id","type": "Help_document","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","target":"target","content":"content","alias":"alias","article":"article","url":"url","not_required":"not_required"}}';
			$help_document->router = 'SermondistributorHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","target","article","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

			// [Interpretation 3741] Set the object into the content types table.
			$help_document_Inserted = $db->insertObject('#__content_types', $help_document);


			// [Interpretation 3777] Install the global extenstion params.
			$query = $db->getQuery(true);

			// [Interpretation 3785] Field to update.
			$fields = array(
				$db->quoteName('params') . ' = ' . $db->quote('{"autorName":"Llewellyn van der Merwe","autorEmail":"llewellyn@vdm.io","player":"1","add_to_dropbox":"0","dropbox_filetypes":"zero","manual_link_update_method":"1","manual_dropbox_timer":"60","auto_link_update_method":"1","auto_dropbox_timer":"60","preacher_state":"1","series_state":"1","sermon_state":"1","auto_link_type":"1","link_encryption":")$KCGiB3BEfDf6kzEWrFnHex5uTJxlQG","preachers_display":"2","preachers_list_style":"2","preachers_table_color":"0","preachers_icon":"1","preachers_desc":"1","preachers_sermon_count":"1","preachers_hits":"1","preachers_website":"1","preachers_email":"1","preacher_request_id":"zero","preacher_display":"3","preacher_box_contrast":"1","preacher_list_style":"3","preacher_icon":"1","preacher_desc":"1","preacher_sermon_count":"1","preacher_hits":"1","preacher_email":"1","preacher_website":"1","preacher_sermons_display":"2","preacher_sermons_list_style":"2","preacher_sermons_table_color":"0","preacher_sermons_icon":"1","preacher_sermons_desc":"1","preacher_sermons_series":"1","preacher_sermons_category":"1","preacher_sermons_download_counter":"1","preacher_sermons_hits":"1","preacher_sermons_downloads":"1","preacher_sermons_open":"1","categories_display":"2","categories_list_style":"2","categories_table_color":"0","categories_icon":"1","categories_desc":"1","categories_sermon_count":"1","categories_hits":"1","category_display":"3","category_box_contrast":"1","category_list_style":"3","category_icon":"1","category_desc":"1","category_sermon_count":"1","category_hits":"1","category_sermons_display":"2","category_sermons_list_style":"1","category_sermons_table_color":"1","category_sermons_icon":"1","category_sermons_desc":"1","category_sermons_preacher":"1","category_sermons_series":"1","category_sermons_download_counter":"1","category_sermons_hits":"1","category_sermons_downloads":"1","category_sermons_open":"1","list_series_display":"2","list_series_list_style":"2","list_series_table_color":"0","list_series_icon":"1","list_series_desc":"1","list_series_sermon_count":"1","list_series_hits":"1","series_request_id":"zero","series_display":"3","series_box_contrast":"1","series_list_style":"3","series_icon":"1","series_desc":"1","series_sermon_count":"1","series_hits":"1","series_sermons_display":"2","series_sermons_list_style":"1","series_sermons_table_color":"1","series_sermons_icon":"1","series_sermons_desc":"1","series_sermons_preacher":"1","series_sermons_category":"1","series_sermons_download_counter":"1","series_sermons_hits":"1","series_sermons_downloads":"1","series_sermons_open":"1","sermon_display":"1","sermon_box_contrast":"one","sermon_list_style":"1","sermon_icon":"1","sermon_desc":"1","sermon_preacher":"1","sermon_series":"1","sermon_series":"1","sermon_category":"1","sermon_download_counter":"1","sermon_hits":"1","sermon_downloads":"1","check_in":"-1 day","save_history":"1","history_limit":"10","uikit_load":"1","uikit_min":"","uikit_style":""}'),
			);

			// [Interpretation 3789] Condition.
			$conditions = array(
				$db->quoteName('element') . ' = ' . $db->quote('com_sermondistributor')
			);

			$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$allDone = $db->execute();
			echo '<a target="_blank" href="https://www.vdm.io/" title="Sermon Distributor">
				<img src="components/com_sermondistributor/assets/images/component-300.jpg"/>
				</a>';
		}
		// do any updates needed
		if ($type == 'update')
		{

			// [Interpretation 3716] Get The Database object
			$db = JFactory::getDbo();

			// [Interpretation 3723] Create the preacher content type object.
			$preacher = new stdClass();
			$preacher->type_title = 'Sermondistributor Preacher';
			$preacher->type_alias = 'com_sermondistributor.preacher';
			$preacher->table = '{"special": {"dbtable": "#__sermondistributor_preacher","key": "id","type": "Preacher","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$preacher->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","alias":"alias","email":"email","website":"website","icon":"icon"}}';
			$preacher->router = 'SermondistributorHelperRoute::getPreacherRoute';
			$preacher->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/preacher.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// [Interpretation 3732] Check if preacher type is already in content_type DB.
			$preacher_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($preacher->type_alias));
			$db->setQuery($query);
			$db->execute();

			// [Interpretation 3741] Set the object into the content types table.
			if ($db->getNumRows())
			{
				$preacher->type_id = $db->loadResult();
				$preacher_Updated = $db->updateObject('#__content_types', $preacher, 'type_id');
			}
			else
			{
				$preacher_Inserted = $db->insertObject('#__content_types', $preacher);
			}

			// [Interpretation 3723] Create the sermon content type object.
			$sermon = new stdClass();
			$sermon->type_title = 'Sermondistributor Sermon';
			$sermon->type_alias = 'com_sermondistributor.sermon';
			$sermon->table = '{"special": {"dbtable": "#__sermondistributor_sermon","key": "id","type": "Sermon","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$sermon->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "catid","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","preacher":"preacher","series":"series","short_description":"short_description","link_type":"link_type","source":"source","icon":"icon","tags":"tags","local_files":"local_files","description":"description","alias":"alias","not_required":"not_required","manual_files":"manual_files","scripture":"scripture","url":"url","build":"build","auto_sermons":"auto_sermons"}}';
			$sermon->router = 'SermondistributorHelperRoute::getSermonRoute';
			$sermon->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/sermon.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required","auto_sermons"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","preacher","series","catid","link_type","source","not_required","build"],"displayLookup": [{"sourceColumn": "catid","targetTable": "#__categories","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "local_files","targetTable": "","targetColumn": "","displayColumn": ""},{"sourceColumn": "manual_files","targetTable": "","targetColumn": "","displayColumn": ""}]}';

			// [Interpretation 3732] Check if sermon type is already in content_type DB.
			$sermon_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($sermon->type_alias));
			$db->setQuery($query);
			$db->execute();

			// [Interpretation 3741] Set the object into the content types table.
			if ($db->getNumRows())
			{
				$sermon->type_id = $db->loadResult();
				$sermon_Updated = $db->updateObject('#__content_types', $sermon, 'type_id');
			}
			else
			{
				$sermon_Inserted = $db->insertObject('#__content_types', $sermon);
			}

			// [Interpretation 3723] Create the sermon catagory content type object.
			$sermon_catagory = new stdClass();
			$sermon_catagory->type_title = 'Sermondistributor Sermon Catid';
			$sermon_catagory->type_alias = 'com_sermondistributor.sermons.category';
			$sermon_catagory->table = '{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}';
			$sermon_catagory->field_mappings = '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}';
			$sermon_catagory->router = 'SermondistributorHelperRoute::getCategoryRoute';
			$sermon_catagory->content_history_options = '{"formFile":"administrator\/components\/com_categories\/models\/forms\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}';

			// [Interpretation 3732] Check if sermon catagory type is already in content_type DB.
			$sermon_catagory_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($sermon_catagory->type_alias));
			$db->setQuery($query);
			$db->execute();

			// [Interpretation 3741] Set the object into the content types table.
			if ($db->getNumRows())
			{
				$sermon_catagory->type_id = $db->loadResult();
				$sermon_catagory_Updated = $db->updateObject('#__content_types', $sermon_catagory, 'type_id');
			}
			else
			{
				$sermon_catagory_Inserted = $db->insertObject('#__content_types', $sermon_catagory);
			}

			// [Interpretation 3723] Create the series content type object.
			$series = new stdClass();
			$series->type_title = 'Sermondistributor Series';
			$series->type_alias = 'com_sermondistributor.series';
			$series->table = '{"special": {"dbtable": "#__sermondistributor_series","key": "id","type": "Series","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$series->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","alias":"alias","icon":"icon","scripture":"scripture"}}';
			$series->router = 'SermondistributorHelperRoute::getSeriesRoute';
			$series->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/series.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// [Interpretation 3732] Check if series type is already in content_type DB.
			$series_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($series->type_alias));
			$db->setQuery($query);
			$db->execute();

			// [Interpretation 3741] Set the object into the content types table.
			if ($db->getNumRows())
			{
				$series->type_id = $db->loadResult();
				$series_Updated = $db->updateObject('#__content_types', $series, 'type_id');
			}
			else
			{
				$series_Inserted = $db->insertObject('#__content_types', $series);
			}

			// [Interpretation 3723] Create the statistic content type object.
			$statistic = new stdClass();
			$statistic->type_title = 'Sermondistributor Statistic';
			$statistic->type_alias = 'com_sermondistributor.statistic';
			$statistic->table = '{"special": {"dbtable": "#__sermondistributor_statistic","key": "id","type": "Statistic","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$statistic->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "filename","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"filename":"filename","sermon":"sermon","preacher":"preacher","series":"series","counter":"counter"}}';
			$statistic->router = 'SermondistributorHelperRoute::getStatisticRoute';
			$statistic->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/statistic.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","sermon","preacher","series","counter"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"}]}';

			// [Interpretation 3732] Check if statistic type is already in content_type DB.
			$statistic_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($statistic->type_alias));
			$db->setQuery($query);
			$db->execute();

			// [Interpretation 3741] Set the object into the content types table.
			if ($db->getNumRows())
			{
				$statistic->type_id = $db->loadResult();
				$statistic_Updated = $db->updateObject('#__content_types', $statistic, 'type_id');
			}
			else
			{
				$statistic_Inserted = $db->insertObject('#__content_types', $statistic);
			}

			// [Interpretation 3723] Create the help_document content type object.
			$help_document = new stdClass();
			$help_document->type_title = 'Sermondistributor Help_document';
			$help_document->type_alias = 'com_sermondistributor.help_document';
			$help_document->table = '{"special": {"dbtable": "#__sermondistributor_help_document","key": "id","type": "Help_document","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","target":"target","content":"content","alias":"alias","article":"article","url":"url","not_required":"not_required"}}';
			$help_document->router = 'SermondistributorHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","target","article","not_required"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

			// [Interpretation 3732] Check if help_document type is already in content_type DB.
			$help_document_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($help_document->type_alias));
			$db->setQuery($query);
			$db->execute();

			// [Interpretation 3741] Set the object into the content types table.
			if ($db->getNumRows())
			{
				$help_document->type_id = $db->loadResult();
				$help_document_Updated = $db->updateObject('#__content_types', $help_document, 'type_id');
			}
			else
			{
				$help_document_Inserted = $db->insertObject('#__content_types', $help_document);
			}


			echo '<a target="_blank" href="https://www.vdm.io/" title="Sermon Distributor">
				<img src="components/com_sermondistributor/assets/images/component-300.jpg"/>
				</a>
				<h3>Upgrade to Version 1.3.3 Was Successful! Let us know if anything is not working as expected.</h3>';
		}
	}
}
