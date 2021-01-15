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
	@subpackage		script.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal');

/**
 * Script File of Sermondistributor Component
 */
class com_sermondistributorInstallerScript
{
	/**
	 * Constructor
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 */
	public function __construct(JAdapterInstance $parent) {}

	/**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function install(JAdapterInstance $parent) {}

	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 */
	public function uninstall(JAdapterInstance $parent)
	{
		// Get Application object
		$app = JFactory::getApplication();

		// Get The Database object
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Preacher alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.preacher') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$preacher_found = $db->getNumRows();
		// Now check if there were any rows
		if ($preacher_found)
		{
			// Since there are load the needed  preacher type ids
			$preacher_ids = $db->loadColumn();
			// Remove Preacher from the content type table
			$preacher_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.preacher') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($preacher_condition);
			$db->setQuery($query);
			// Execute the query to remove Preacher items
			$preacher_done = $db->execute();
			if ($preacher_done)
			{
				// If successfully remove Preacher add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.preacher) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Preacher items from the contentitem tag map table
			$preacher_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.preacher') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($preacher_condition);
			$db->setQuery($query);
			// Execute the query to remove Preacher items
			$preacher_done = $db->execute();
			if ($preacher_done)
			{
				// If successfully remove Preacher add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.preacher) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Preacher items from the ucm content table
			$preacher_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.preacher') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($preacher_condition);
			$db->setQuery($query);
			// Execute the query to remove Preacher items
			$preacher_done = $db->execute();
			if ($preacher_done)
			{
				// If successfully removed Preacher add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.preacher) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Preacher items are cleared from DB
			foreach ($preacher_ids as $preacher_id)
			{
				// Remove Preacher items from the ucm base table
				$preacher_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $preacher_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($preacher_condition);
				$db->setQuery($query);
				// Execute the query to remove Preacher items
				$db->execute();

				// Remove Preacher items from the ucm history table
				$preacher_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $preacher_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($preacher_condition);
				$db->setQuery($query);
				// Execute the query to remove Preacher items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Sermon alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$sermon_found = $db->getNumRows();
		// Now check if there were any rows
		if ($sermon_found)
		{
			// Since there are load the needed  sermon type ids
			$sermon_ids = $db->loadColumn();
			// Remove Sermon from the content type table
			$sermon_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($sermon_condition);
			$db->setQuery($query);
			// Execute the query to remove Sermon items
			$sermon_done = $db->execute();
			if ($sermon_done)
			{
				// If successfully remove Sermon add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Sermon items from the contentitem tag map table
			$sermon_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($sermon_condition);
			$db->setQuery($query);
			// Execute the query to remove Sermon items
			$sermon_done = $db->execute();
			if ($sermon_done)
			{
				// If successfully remove Sermon add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Sermon items from the ucm content table
			$sermon_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.sermon') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($sermon_condition);
			$db->setQuery($query);
			// Execute the query to remove Sermon items
			$sermon_done = $db->execute();
			if ($sermon_done)
			{
				// If successfully removed Sermon add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Sermon items are cleared from DB
			foreach ($sermon_ids as $sermon_id)
			{
				// Remove Sermon items from the ucm base table
				$sermon_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($sermon_condition);
				$db->setQuery($query);
				// Execute the query to remove Sermon items
				$db->execute();

				// Remove Sermon items from the ucm history table
				$sermon_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($sermon_condition);
				$db->setQuery($query);
				// Execute the query to remove Sermon items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Sermon catid alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon.category') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$sermon_catid_found = $db->getNumRows();
		// Now check if there were any rows
		if ($sermon_catid_found)
		{
			// Since there are load the needed  sermon_catid type ids
			$sermon_catid_ids = $db->loadColumn();
			// Remove Sermon catid from the content type table
			$sermon_catid_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon.category') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($sermon_catid_condition);
			$db->setQuery($query);
			// Execute the query to remove Sermon catid items
			$sermon_catid_done = $db->execute();
			if ($sermon_catid_done)
			{
				// If successfully remove Sermon catid add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon.category) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Sermon catid items from the contentitem tag map table
			$sermon_catid_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon.category') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($sermon_catid_condition);
			$db->setQuery($query);
			// Execute the query to remove Sermon catid items
			$sermon_catid_done = $db->execute();
			if ($sermon_catid_done)
			{
				// If successfully remove Sermon catid add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon.category) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Sermon catid items from the ucm content table
			$sermon_catid_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.sermon.category') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($sermon_catid_condition);
			$db->setQuery($query);
			// Execute the query to remove Sermon catid items
			$sermon_catid_done = $db->execute();
			if ($sermon_catid_done)
			{
				// If successfully removed Sermon catid add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.sermon.category) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Sermon catid items are cleared from DB
			foreach ($sermon_catid_ids as $sermon_catid_id)
			{
				// Remove Sermon catid items from the ucm base table
				$sermon_catid_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_catid_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($sermon_catid_condition);
				$db->setQuery($query);
				// Execute the query to remove Sermon catid items
				$db->execute();

				// Remove Sermon catid items from the ucm history table
				$sermon_catid_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $sermon_catid_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($sermon_catid_condition);
				$db->setQuery($query);
				// Execute the query to remove Sermon catid items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Series alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.series') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$series_found = $db->getNumRows();
		// Now check if there were any rows
		if ($series_found)
		{
			// Since there are load the needed  series type ids
			$series_ids = $db->loadColumn();
			// Remove Series from the content type table
			$series_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.series') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($series_condition);
			$db->setQuery($query);
			// Execute the query to remove Series items
			$series_done = $db->execute();
			if ($series_done)
			{
				// If successfully remove Series add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.series) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Series items from the contentitem tag map table
			$series_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.series') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($series_condition);
			$db->setQuery($query);
			// Execute the query to remove Series items
			$series_done = $db->execute();
			if ($series_done)
			{
				// If successfully remove Series add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.series) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Series items from the ucm content table
			$series_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.series') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($series_condition);
			$db->setQuery($query);
			// Execute the query to remove Series items
			$series_done = $db->execute();
			if ($series_done)
			{
				// If successfully removed Series add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.series) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Series items are cleared from DB
			foreach ($series_ids as $series_id)
			{
				// Remove Series items from the ucm base table
				$series_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $series_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($series_condition);
				$db->setQuery($query);
				// Execute the query to remove Series items
				$db->execute();

				// Remove Series items from the ucm history table
				$series_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $series_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($series_condition);
				$db->setQuery($query);
				// Execute the query to remove Series items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Statistic alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.statistic') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$statistic_found = $db->getNumRows();
		// Now check if there were any rows
		if ($statistic_found)
		{
			// Since there are load the needed  statistic type ids
			$statistic_ids = $db->loadColumn();
			// Remove Statistic from the content type table
			$statistic_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.statistic') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($statistic_condition);
			$db->setQuery($query);
			// Execute the query to remove Statistic items
			$statistic_done = $db->execute();
			if ($statistic_done)
			{
				// If successfully remove Statistic add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.statistic) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Statistic items from the contentitem tag map table
			$statistic_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.statistic') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($statistic_condition);
			$db->setQuery($query);
			// Execute the query to remove Statistic items
			$statistic_done = $db->execute();
			if ($statistic_done)
			{
				// If successfully remove Statistic add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.statistic) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Statistic items from the ucm content table
			$statistic_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.statistic') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($statistic_condition);
			$db->setQuery($query);
			// Execute the query to remove Statistic items
			$statistic_done = $db->execute();
			if ($statistic_done)
			{
				// If successfully removed Statistic add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.statistic) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Statistic items are cleared from DB
			foreach ($statistic_ids as $statistic_id)
			{
				// Remove Statistic items from the ucm base table
				$statistic_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $statistic_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($statistic_condition);
				$db->setQuery($query);
				// Execute the query to remove Statistic items
				$db->execute();

				// Remove Statistic items from the ucm history table
				$statistic_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $statistic_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($statistic_condition);
				$db->setQuery($query);
				// Execute the query to remove Statistic items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where External_source alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.external_source') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$external_source_found = $db->getNumRows();
		// Now check if there were any rows
		if ($external_source_found)
		{
			// Since there are load the needed  external_source type ids
			$external_source_ids = $db->loadColumn();
			// Remove External_source from the content type table
			$external_source_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.external_source') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($external_source_condition);
			$db->setQuery($query);
			// Execute the query to remove External_source items
			$external_source_done = $db->execute();
			if ($external_source_done)
			{
				// If successfully remove External_source add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.external_source) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove External_source items from the contentitem tag map table
			$external_source_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.external_source') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($external_source_condition);
			$db->setQuery($query);
			// Execute the query to remove External_source items
			$external_source_done = $db->execute();
			if ($external_source_done)
			{
				// If successfully remove External_source add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.external_source) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove External_source items from the ucm content table
			$external_source_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.external_source') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($external_source_condition);
			$db->setQuery($query);
			// Execute the query to remove External_source items
			$external_source_done = $db->execute();
			if ($external_source_done)
			{
				// If successfully removed External_source add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.external_source) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the External_source items are cleared from DB
			foreach ($external_source_ids as $external_source_id)
			{
				// Remove External_source items from the ucm base table
				$external_source_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $external_source_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($external_source_condition);
				$db->setQuery($query);
				// Execute the query to remove External_source items
				$db->execute();

				// Remove External_source items from the ucm history table
				$external_source_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $external_source_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($external_source_condition);
				$db->setQuery($query);
				// Execute the query to remove External_source items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Local_listing alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.local_listing') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$local_listing_found = $db->getNumRows();
		// Now check if there were any rows
		if ($local_listing_found)
		{
			// Since there are load the needed  local_listing type ids
			$local_listing_ids = $db->loadColumn();
			// Remove Local_listing from the content type table
			$local_listing_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.local_listing') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($local_listing_condition);
			$db->setQuery($query);
			// Execute the query to remove Local_listing items
			$local_listing_done = $db->execute();
			if ($local_listing_done)
			{
				// If successfully remove Local_listing add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.local_listing) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Local_listing items from the contentitem tag map table
			$local_listing_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.local_listing') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($local_listing_condition);
			$db->setQuery($query);
			// Execute the query to remove Local_listing items
			$local_listing_done = $db->execute();
			if ($local_listing_done)
			{
				// If successfully remove Local_listing add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.local_listing) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Local_listing items from the ucm content table
			$local_listing_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.local_listing') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($local_listing_condition);
			$db->setQuery($query);
			// Execute the query to remove Local_listing items
			$local_listing_done = $db->execute();
			if ($local_listing_done)
			{
				// If successfully removed Local_listing add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.local_listing) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Local_listing items are cleared from DB
			foreach ($local_listing_ids as $local_listing_id)
			{
				// Remove Local_listing items from the ucm base table
				$local_listing_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $local_listing_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($local_listing_condition);
				$db->setQuery($query);
				// Execute the query to remove Local_listing items
				$db->execute();

				// Remove Local_listing items from the ucm history table
				$local_listing_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $local_listing_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($local_listing_condition);
				$db->setQuery($query);
				// Execute the query to remove Local_listing items
				$db->execute();
			}
		}

		// Create a new query object.
		$query = $db->getQuery(true);
		// Select id from content type table
		$query->select($db->quoteName('type_id'));
		$query->from($db->quoteName('#__content_types'));
		// Where Help_document alias is found
		$query->where( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.help_document') );
		$db->setQuery($query);
		// Execute query to see if alias is found
		$db->execute();
		$help_document_found = $db->getNumRows();
		// Now check if there were any rows
		if ($help_document_found)
		{
			// Since there are load the needed  help_document type ids
			$help_document_ids = $db->loadColumn();
			// Remove Help_document from the content type table
			$help_document_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.help_document') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__content_types'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done)
			{
				// If successfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.help_document) type alias was removed from the <b>#__content_type</b> table'));
			}

			// Remove Help_document items from the contentitem tag map table
			$help_document_condition = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.help_document') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__contentitem_tag_map'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done)
			{
				// If successfully remove Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.help_document) type alias was removed from the <b>#__contentitem_tag_map</b> table'));
			}

			// Remove Help_document items from the ucm content table
			$help_document_condition = array( $db->quoteName('core_type_alias') . ' = ' . $db->quote('com_sermondistributor.help_document') );
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->delete($db->quoteName('#__ucm_content'));
			$query->where($help_document_condition);
			$db->setQuery($query);
			// Execute the query to remove Help_document items
			$help_document_done = $db->execute();
			if ($help_document_done)
			{
				// If successfully removed Help_document add queued success message.
				$app->enqueueMessage(JText::_('The (com_sermondistributor.help_document) type alias was removed from the <b>#__ucm_content</b> table'));
			}

			// Make sure that all the Help_document items are cleared from DB
			foreach ($help_document_ids as $help_document_id)
			{
				// Remove Help_document items from the ucm base table
				$help_document_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $help_document_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_base'));
				$query->where($help_document_condition);
				$db->setQuery($query);
				// Execute the query to remove Help_document items
				$db->execute();

				// Remove Help_document items from the ucm history table
				$help_document_condition = array( $db->quoteName('ucm_type_id') . ' = ' . $help_document_id);
				// Create a new query object.
				$query = $db->getQuery(true);
				$query->delete($db->quoteName('#__ucm_history'));
				$query->where($help_document_condition);
				$db->setQuery($query);
				// Execute the query to remove Help_document items
				$db->execute();
			}
		}

		// If All related items was removed queued success message.
		$app->enqueueMessage(JText::_('All related items was removed from the <b>#__ucm_base</b> table'));
		$app->enqueueMessage(JText::_('All related items was removed from the <b>#__ucm_history</b> table'));

		// Remove sermondistributor assets from the assets table
		$sermondistributor_condition = array( $db->quoteName('name') . ' LIKE ' . $db->quote('com_sermondistributor%') );

		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__assets'));
		$query->where($sermondistributor_condition);
		$db->setQuery($query);
		$help_document_done = $db->execute();
		if ($help_document_done)
		{
			// If successfully removed sermondistributor add queued success message.
			$app->enqueueMessage(JText::_('All related items was removed from the <b>#__assets</b> table'));
		}

		// Get the biggest rule column in the assets table at this point.
		$get_rule_length = "SELECT CHAR_LENGTH(`rules`) as rule_size FROM #__assets ORDER BY rule_size DESC LIMIT 1";
		$db->setQuery($get_rule_length);
		if ($db->execute())
		{
			$rule_length = $db->loadResult();
			// Check the size of the rules column
			if ($rule_length < 5120)
			{
				// Revert the assets table rules column back to the default
				$revert_rule = "ALTER TABLE `#__assets` CHANGE `rules` `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.';";
				$db->setQuery($revert_rule);
				$db->execute();
				$app->enqueueMessage(JText::_('Reverted the <b>#__assets</b> table rules column back to its default size of varchar(5120)'));
			}
			else
			{

				$app->enqueueMessage(JText::_('Could not revert the <b>#__assets</b> table rules column back to its default size of varchar(5120), since there is still one or more components that still requires the column to be larger.'));
			}
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor from the action_logs_extensions table
		$sermondistributor_action_logs_extensions = array( $db->quoteName('extension') . ' = ' . $db->quote('com_sermondistributor') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_logs_extensions'));
		$query->where($sermondistributor_action_logs_extensions);
		$db->setQuery($query);
		// Execute the query to remove Sermondistributor
		$sermondistributor_removed_done = $db->execute();
		if ($sermondistributor_removed_done)
		{
			// If successfully remove Sermondistributor add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor extension was removed from the <b>#__action_logs_extensions</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor Preacher from the action_log_config table
		$preacher_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.preacher') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($preacher_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_sermondistributor.preacher
		$preacher_action_log_config_done = $db->execute();
		if ($preacher_action_log_config_done)
		{
			// If successfully removed Sermondistributor Preacher add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor.preacher type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor Sermon from the action_log_config table
		$sermon_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.sermon') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($sermon_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_sermondistributor.sermon
		$sermon_action_log_config_done = $db->execute();
		if ($sermon_action_log_config_done)
		{
			// If successfully removed Sermondistributor Sermon add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor.sermon type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor Series from the action_log_config table
		$series_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.series') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($series_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_sermondistributor.series
		$series_action_log_config_done = $db->execute();
		if ($series_action_log_config_done)
		{
			// If successfully removed Sermondistributor Series add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor.series type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor Statistic from the action_log_config table
		$statistic_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.statistic') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($statistic_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_sermondistributor.statistic
		$statistic_action_log_config_done = $db->execute();
		if ($statistic_action_log_config_done)
		{
			// If successfully removed Sermondistributor Statistic add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor.statistic type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor External_source from the action_log_config table
		$external_source_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.external_source') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($external_source_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_sermondistributor.external_source
		$external_source_action_log_config_done = $db->execute();
		if ($external_source_action_log_config_done)
		{
			// If successfully removed Sermondistributor External_source add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor.external_source type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor Local_listing from the action_log_config table
		$local_listing_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.local_listing') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($local_listing_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_sermondistributor.local_listing
		$local_listing_action_log_config_done = $db->execute();
		if ($local_listing_action_log_config_done)
		{
			// If successfully removed Sermondistributor Local_listing add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor.local_listing type alias was removed from the <b>#__action_log_config</b> table'));
		}

		// Set db if not set already.
		if (!isset($db))
		{
			$db = JFactory::getDbo();
		}
		// Set app if not set already.
		if (!isset($app))
		{
			$app = JFactory::getApplication();
		}
		// Remove Sermondistributor Help_document from the action_log_config table
		$help_document_action_log_config = array( $db->quoteName('type_alias') . ' = '. $db->quote('com_sermondistributor.help_document') );
		// Create a new query object.
		$query = $db->getQuery(true);
		$query->delete($db->quoteName('#__action_log_config'));
		$query->where($help_document_action_log_config);
		$db->setQuery($query);
		// Execute the query to remove com_sermondistributor.help_document
		$help_document_action_log_config_done = $db->execute();
		if ($help_document_action_log_config_done)
		{
			// If successfully removed Sermondistributor Help_document add queued success message.
			$app->enqueueMessage(JText::_('The com_sermondistributor.help_document type alias was removed from the <b>#__action_log_config</b> table'));
		}
		// little notice as after service, in case of bad experience with component.
		echo '<h2>Did something go wrong? Are you disappointed?</h2>
		<p>Please let me know at <a href="mailto:joomla@vdm.io">joomla@vdm.io</a>.
		<br />We at Vast Development Method are committed to building extensions that performs proficiently! You can help us, really!
		<br />Send me your thoughts on improvements that is needed, trust me, I will be very grateful!
		<br />Visit us at <a href="https://www.vdm.io/" target="_blank">https://www.vdm.io/</a> today!</p>';
	}

	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function update(JAdapterInstance $parent){}

	/**
	 * Called before any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function preflight($type, JAdapterInstance $parent)
	{
		// get application
		$app = JFactory::getApplication();
		// is redundant or so it seems ...hmmm let me know if it works again
		if ($type === 'uninstall')
		{
			return true;
		}
		// the default for both install and update
		$jversion = new JVersion();
		if (!$jversion->isCompatible('3.8.0'))
		{
			$app->enqueueMessage('Please upgrade to at least Joomla! 3.8.0 before continuing!', 'error');
			return false;
		}
		// do any updates needed
		if ($type === 'update')
		{
		// load the helper class
		JLoader::register('SermondistributorHelper', JPATH_ADMINISTRATOR . '/components/com_sermondistributor/helpers/sermondistributor.php');
		// check the version of Sermon Distributor
		$manifest = SermondistributorHelper::manifest();
		if (isset($manifest->version) && strpos($manifest->version, '.') !== false)
		{
			$version = explode('.', $manifest->version);
			// Get a db connection.
			$db = JFactory::getDbo();
			if ($version[0] == 1 && $version[1] < 4)
			{
				// Create a new query object.
				$query = $db->getQuery(true);
				// update all manual and auto links in sermons
				$query->select($db->quoteName(array('id', 'manual_files')));
				$query->from($db->quoteName('#__sermondistributor_sermon'));
				$query->where($db->quoteName('source') . ' = 2');
				// Reset the query using our newly populated query object.
				$db->setQuery($query);
				$db->execute();
				if ($db->getNumRows())
				{
					$rows = $db->loadAssocList('id', 'manual_files');
					foreach ($rows as $id => $files)
					{
						if (SermondistributorHelper::checkJson($files))
						{
							$files = json_decode($files, true);
							if (SermondistributorHelper::checkArray($files))
							{
								foreach ($files as $nr => &$file)
								{
									$tmp = str_replace('VDM_pLeK_h0uEr/', '', $file);
									$new = strtolower($tmp);
									// now update the file
									$file = str_replace($tmp, $new, $file);
								}
							}
						}
						// only update if it was fixed
						if (SermondistributorHelper::checkArray($files))
						{
							$object = new stdClass();
							// make sure the files are set to json
							$object->manual_files = json_encode($files);
							$object->id = $id;
							JFactory::getDbo()->updateObject('#__sermondistributor_sermon', $object, 'id');
						}
					}
				}
				// do an update by moving config data to the new external source area.
				$this->comParams = JComponentHelper::getParams('com_sermondistributor');
				// the number of links
				$numbers = range(1, 4);
				// the types of links
				$types = array('auto','manual');
				// the update targets
				$this->updateTargetsU = array();
				$this->updateTargetsF = array();
				// get all listed targets bast on type
				foreach ($types as $type)
				{
					// now check if they are set
					foreach ($numbers as $number)
					{
						// set the number to string
						$numStr = SermondistributorHelper::safeString($number);
						// Get the url
						$url = $this->comParams->get($type.'dropbox'.$numStr, null);
						// only load those that are set
						if ($url && SermondistributorHelper::checkString($url))
						{
							if (!isset($this->updateTargetsU[$type]))
							{
								$this->updateTargetsU[$type] = array();
							}
							$this->updateTargetsU[$type][] = $url;
						}
						// Get the folders if set
						$folder = $this->comParams->get($type.'dropboxfolder'.$numStr, null);
						// only load those that are set
						if ($folder && SermondistributorHelper::checkString($folder))
						{
							if (!isset($this->updateTargetsF[$type]))
							{
								$this->updateTargetsF[$type] = array();
							}
							$this->updateTargetsF[$type][] = $folder;
						}
					}
				}
			}
			// target version less then or equal to 2.0.2
			if (count($version) == 3 && ($version[0] == 2 && $version[1] == 0 && $version[2] <= 3) ||  ($version[0] < 2))
			{
				// we need to make a database correction
				$fix_categories = array(
					'com_sermondistributor.sermons' => 'com_sermondistributor.sermon'
				);

					// targeted tables (to fix all places categories are mapped into Joomla)
					$fix_tables = array(
						'content_types' => array(
							'id' => 'type_id',
							'key' => 'type_alias',
							'suffix' => '.category'),
						'contentitem_tag_map' => array(
							'id' => 'type_id',
							'key' => 'type_alias',
							'suffix' => '.category'),
						'ucm_content' => array(
							'id' => 'core_content_id',
							'key' => 'core_type_alias',
							'suffix' => '.category'),
						'categories' => array(
							'id' => 'id',
							'key' => 'extension',
							'suffix' => '')
					);
					// the script that does the work
					foreach ($fix_categories as $fix => $category)
					{
						// loop over the targeted tables
						foreach ($fix_tables as $_table => $_update)
						{
							// Create a new query object.
							$query = $db->getQuery(true);
							// get all type_ids
							$query->select($db->quoteName($_update['id']));
							$query->from($db->quoteName('#__' . $_table));
							$query->where( $db->quoteName($_update['key']) . ' = ' . $db->quote($fix . $_update['suffix']));
							// Reset the query using our newly populated query object.
							$db->setQuery($query);
							$db->execute();
							if ($db->getNumRows())
							{

								// all these must be updated
								$ids = $db->loadColumn();
								// Fields to update.
								$fields = array(
									$db->quoteName($_update['key']) . ' = ' . $db->quote($category . $_update['suffix'])
								);
								// Conditions for which records should be updated.
								$conditions = array(
									$db->quoteName($_update['id']) . ' IN (' . implode(', ', $ids) . ')'
								);
								$query->update($db->quoteName('#__' . $_table))->set($fields)->where($conditions);
								$db->setQuery($query);
								$result = $db->execute();
								// on success
								if ($result)
								{
									$app->enqueueMessage("<p>Updated <b>#__$_table - " . $_update['key'] . "</b> from <b>$fix</b>" . $_update['suffix'] . " to <b>$category</b>" . $_update['suffix'] . "!</p>", 'Notice');
								}

							}
						}
					}
			}
		}
		}
		// do any install needed
		if ($type === 'install')
		{
		}
		// check if the PHPExcel stuff is still around
		if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_sermondistributor/helpers/PHPExcel.php'))
		{
			// We need to remove this old PHPExcel folder
			$this->removeFolder(JPATH_ADMINISTRATOR . '/components/com_sermondistributor/helpers/PHPExcel');
			// We need to remove this old PHPExcel file
			JFile::delete(JPATH_ADMINISTRATOR . '/components/com_sermondistributor/helpers/PHPExcel.php');
		}
		return true;
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $type  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $parent  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	public function postflight($type, JAdapterInstance $parent)
	{
		// get application
		$app = JFactory::getApplication();
		// We check if we have dynamic folders to copy
		$this->setDynamicF0ld3rs($app, $parent);
		// set the default component settings
		if ($type === 'install')
		{

			// Get The Database object
			$db = JFactory::getDbo();

			// Create the preacher content type object.
			$preacher = new stdClass();
			$preacher->type_title = 'Sermondistributor Preacher';
			$preacher->type_alias = 'com_sermondistributor.preacher';
			$preacher->table = '{"special": {"dbtable": "#__sermondistributor_preacher","key": "id","type": "Preacher","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$preacher->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","website":"website","email":"email","icon":"icon","alias":"alias"}}';
			$preacher->router = 'SermondistributorHelperRoute::getPreacherRoute';
			$preacher->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/preacher.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$preacher_Inserted = $db->insertObject('#__content_types', $preacher);

			// Create the sermon content type object.
			$sermon = new stdClass();
			$sermon->type_title = 'Sermondistributor Sermon';
			$sermon->type_alias = 'com_sermondistributor.sermon';
			$sermon->table = '{"special": {"dbtable": "#__sermondistributor_sermon","key": "id","type": "Sermon","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$sermon->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "catid","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","preacher":"preacher","series":"series","short_description":"short_description","link_type":"link_type","source":"source","local_files":"local_files","alias":"alias","description":"description","tags":"tags","icon":"icon","build":"build","manual_files":"manual_files","auto_sermons":"auto_sermons","url":"url","scripture":"scripture"}}';
			$sermon->router = 'SermondistributorHelperRoute::getSermonRoute';
			$sermon->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/sermon.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","auto_sermons"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","preacher","series","catid","link_type","source","build"],"displayLookup": [{"sourceColumn": "catid","targetTable": "#__categories","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$sermon_Inserted = $db->insertObject('#__content_types', $sermon);

			// Create the sermon category content type object.
			$sermon_category = new stdClass();
			$sermon_category->type_title = 'Sermondistributor Sermon Catid';
			$sermon_category->type_alias = 'com_sermondistributor.sermon.category';
			$sermon_category->table = '{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}';
			$sermon_category->field_mappings = '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}';
			$sermon_category->router = 'SermondistributorHelperRoute::getCategoryRoute';
			$sermon_category->content_history_options = '{"formFile":"administrator\/components\/com_categories\/models\/forms\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}';

			// Set the object into the content types table.
			$sermon_category_Inserted = $db->insertObject('#__content_types', $sermon_category);

			// Create the series content type object.
			$series = new stdClass();
			$series->type_title = 'Sermondistributor Series';
			$series->type_alias = 'com_sermondistributor.series';
			$series->table = '{"special": {"dbtable": "#__sermondistributor_series","key": "id","type": "Series","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$series->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","scripture":"scripture","icon":"icon","alias":"alias"}}';
			$series->router = 'SermondistributorHelperRoute::getSeriesRoute';
			$series->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/series.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$series_Inserted = $db->insertObject('#__content_types', $series);

			// Create the statistic content type object.
			$statistic = new stdClass();
			$statistic->type_title = 'Sermondistributor Statistic';
			$statistic->type_alias = 'com_sermondistributor.statistic';
			$statistic->table = '{"special": {"dbtable": "#__sermondistributor_statistic","key": "id","type": "Statistic","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$statistic->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "filename","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"filename":"filename","sermon":"sermon","preacher":"preacher","series":"series","counter":"counter"}}';
			$statistic->router = 'SermondistributorHelperRoute::getStatisticRoute';
			$statistic->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/statistic.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","sermon","preacher","series","counter"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$statistic_Inserted = $db->insertObject('#__content_types', $statistic);

			// Create the external_source content type object.
			$external_source = new stdClass();
			$external_source->type_title = 'Sermondistributor External_source';
			$external_source->type_alias = 'com_sermondistributor.external_source';
			$external_source->table = '{"special": {"dbtable": "#__sermondistributor_external_source","key": "id","type": "External_source","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$external_source->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "description","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"description":"description","externalsources":"externalsources","update_method":"update_method","filetypes":"filetypes","build":"build","not_required":"not_required","update_timer":"update_timer","dropboxoptions":"dropboxoptions","permissiontype":"permissiontype","oauthtoken":"oauthtoken"}}';
			$external_source->router = 'SermondistributorHelperRoute::getExternal_sourceRoute';
			$external_source->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/external_source.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","externalsources","update_method","build","not_required","update_timer","dropboxoptions"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Set the object into the content types table.
			$external_source_Inserted = $db->insertObject('#__content_types', $external_source);

			// Create the local_listing content type object.
			$local_listing = new stdClass();
			$local_listing->type_title = 'Sermondistributor Local_listing';
			$local_listing->type_alias = 'com_sermondistributor.local_listing';
			$local_listing->table = '{"special": {"dbtable": "#__sermondistributor_local_listing","key": "id","type": "Local_listing","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$local_listing->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","build":"build","size":"size","external_source":"external_source","key":"key","url":"url"}}';
			$local_listing->router = 'SermondistributorHelperRoute::getLocal_listingRoute';
			$local_listing->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/local_listing.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","build","size","external_source"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "external_source","targetTable": "#__sermondistributor_external_source","targetColumn": "id","displayColumn": "description"}]}';

			// Set the object into the content types table.
			$local_listing_Inserted = $db->insertObject('#__content_types', $local_listing);

			// Create the help_document content type object.
			$help_document = new stdClass();
			$help_document->type_title = 'Sermondistributor Help_document';
			$help_document->type_alias = 'com_sermondistributor.help_document';
			$help_document->table = '{"special": {"dbtable": "#__sermondistributor_help_document","key": "id","type": "Help_document","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","alias":"alias","content":"content","article":"article","url":"url","target":"target"}}';
			$help_document->router = 'SermondistributorHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","article","target"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

			// Set the object into the content types table.
			$help_document_Inserted = $db->insertObject('#__content_types', $help_document);


			// Install the global extenstion assets permission.
			$query = $db->getQuery(true);
			// Field to update.
			$fields = array(
				$db->quoteName('rules') . ' = ' . $db->quote('{"site.preachers.access":{"1":1},"site.preacher.access":{"1":1},"site.categories.access":{"1":1},"site.category.access":{"1":1},"site.serieslist.access":{"1":1},"site.series.access":{"1":1},"site.sermon.access":{"1":1}}'),
			);
			// Condition.
			$conditions = array(
				$db->quoteName('name') . ' = ' . $db->quote('com_sermondistributor')
			);
			$query->update($db->quoteName('#__assets'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$allDone = $db->execute();

			// Install the global extenstion params.
			$query = $db->getQuery(true);
			// Field to update.
			$fields = array(
				$db->quoteName('params') . ' = ' . $db->quote('{"autorName":"Llewellyn van der Merwe","autorEmail":"joomla@vdm.io","player":"1","add_to_button":"0","preachers_display":"2","preachers_list_style":"2","preachers_table_color":"0","preachers_icon":"1","preachers_desc":"1","preachers_sermon_count":"1","preachers_hits":"1","preachers_website":"1","preachers_email":"1","preacher_request_id":"0","preacher_display":"3","preacher_box_contrast":"1","preacher_list_style":"3","preacher_icon":"1","preacher_desc":"1","preacher_sermon_count":"1","preacher_hits":"1","preacher_email":"1","preacher_website":"1","preacher_sermons_display":"2","preacher_sermons_list_style":"2","preacher_sermons_table_color":"0","preacher_sermons_icon":"1","preacher_sermons_desc":"1","preacher_sermons_series":"1","preacher_sermons_category":"1","preacher_sermons_download_counter":"1","preacher_sermons_hits":"1","preacher_sermons_downloads":"1","preacher_sermons_open":"1","categories_display":"2","categories_list_style":"2","categories_table_color":"0","categories_icon":"1","categories_desc":"1","categories_sermon_count":"1","categories_hits":"1","category_display":"3","category_box_contrast":"1","category_list_style":"3","category_icon":"1","category_desc":"1","category_sermon_count":"1","category_hits":"1","category_sermons_display":"2","category_sermons_list_style":"1","category_sermons_table_color":"1","category_sermons_icon":"1","category_sermons_desc":"1","category_sermons_preacher":"1","category_sermons_series":"1","category_sermons_download_counter":"1","category_sermons_hits":"1","category_sermons_downloads":"1","category_sermons_open":"1","list_series_display":"2","list_series_list_style":"2","list_series_table_color":"0","list_series_icon":"1","list_series_desc":"1","list_series_sermon_count":"1","list_series_hits":"1","series_request_id":"0","series_display":"3","series_box_contrast":"1","series_list_style":"3","series_icon":"1","series_desc":"1","series_sermon_count":"1","series_hits":"1","series_sermons_display":"2","series_sermons_list_style":"1","series_sermons_table_color":"1","series_sermons_icon":"1","series_sermons_desc":"1","series_sermons_preacher":"1","series_sermons_category":"1","series_sermons_download_counter":"1","series_sermons_hits":"1","series_sermons_downloads":"1","series_sermons_open":"1","sermon_display":"1","sermon_box_contrast":"1","sermon_list_style":"1","sermon_icon":"1","sermon_desc":"1","sermon_preacher":"1","sermon_series":"1","sermon_category":"1","sermon_download_counter":"1","sermon_hits":"1","sermon_downloads":"1","max_execution_time":"500","check_in":"-1 day","save_history":"1","history_limit":"10","uikit_version":"2","uikit_load":"1","uikit_min":"","uikit_style":""}'),
			);
			// Condition.
			$conditions = array(
				$db->quoteName('element') . ' = ' . $db->quote('com_sermondistributor')
			);
			$query->update($db->quoteName('#__extensions'))->set($fields)->where($conditions);
			$db->setQuery($query);
			$allDone = $db->execute();

			// Get the biggest rule column in the assets table at this point.
			$get_rule_length = "SELECT CHAR_LENGTH(`rules`) as rule_size FROM #__assets ORDER BY rule_size DESC LIMIT 1";
			$db->setQuery($get_rule_length);
			if ($db->execute())
			{
				$rule_length = $db->loadResult();
				// Check the size of the rules column
				if ($rule_length <= 22240)
				{
					// Fix the assets table rules column size
					$fix_rules_size = "ALTER TABLE `#__assets` CHANGE `rules` `rules` TEXT NOT NULL COMMENT 'JSON encoded access control. Enlarged to TEXT by JCB';";
					$db->setQuery($fix_rules_size);
					$db->execute();
					$app->enqueueMessage(JText::_('The <b>#__assets</b> table rules column was resized to the TEXT datatype for the components possible large permission rules.'));
				}
			}
			echo '<a target="_blank" href="https://www.vdm.io/" title="Sermon Distributor">
				<img src="components/com_sermondistributor/assets/images/vdm-component.jpg"/>
				</a>';

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the sermondistributor action logs extensions object.
			$sermondistributor_action_logs_extensions = new stdClass();
			$sermondistributor_action_logs_extensions->extension = 'com_sermondistributor';

			// Set the object into the action logs extensions table.
			$sermondistributor_action_logs_extensions_Inserted = $db->insertObject('#__action_logs_extensions', $sermondistributor_action_logs_extensions);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the preacher action log config object.
			$preacher_action_log_config = new stdClass();
			$preacher_action_log_config->type_title = 'PREACHER';
			$preacher_action_log_config->type_alias = 'com_sermondistributor.preacher';
			$preacher_action_log_config->id_holder = 'id';
			$preacher_action_log_config->title_holder = 'name';
			$preacher_action_log_config->table_name = '#__sermondistributor_preacher';
			$preacher_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Set the object into the action log config table.
			$preacher_Inserted = $db->insertObject('#__action_log_config', $preacher_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the sermon action log config object.
			$sermon_action_log_config = new stdClass();
			$sermon_action_log_config->type_title = 'SERMON';
			$sermon_action_log_config->type_alias = 'com_sermondistributor.sermon';
			$sermon_action_log_config->id_holder = 'id';
			$sermon_action_log_config->title_holder = 'name';
			$sermon_action_log_config->table_name = '#__sermondistributor_sermon';
			$sermon_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Set the object into the action log config table.
			$sermon_Inserted = $db->insertObject('#__action_log_config', $sermon_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the series action log config object.
			$series_action_log_config = new stdClass();
			$series_action_log_config->type_title = 'SERIES';
			$series_action_log_config->type_alias = 'com_sermondistributor.series';
			$series_action_log_config->id_holder = 'id';
			$series_action_log_config->title_holder = 'name';
			$series_action_log_config->table_name = '#__sermondistributor_series';
			$series_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Set the object into the action log config table.
			$series_Inserted = $db->insertObject('#__action_log_config', $series_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the statistic action log config object.
			$statistic_action_log_config = new stdClass();
			$statistic_action_log_config->type_title = 'STATISTIC';
			$statistic_action_log_config->type_alias = 'com_sermondistributor.statistic';
			$statistic_action_log_config->id_holder = 'id';
			$statistic_action_log_config->title_holder = 'filename';
			$statistic_action_log_config->table_name = '#__sermondistributor_statistic';
			$statistic_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Set the object into the action log config table.
			$statistic_Inserted = $db->insertObject('#__action_log_config', $statistic_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the external_source action log config object.
			$external_source_action_log_config = new stdClass();
			$external_source_action_log_config->type_title = 'EXTERNAL_SOURCE';
			$external_source_action_log_config->type_alias = 'com_sermondistributor.external_source';
			$external_source_action_log_config->id_holder = 'id';
			$external_source_action_log_config->title_holder = 'description';
			$external_source_action_log_config->table_name = '#__sermondistributor_external_source';
			$external_source_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Set the object into the action log config table.
			$external_source_Inserted = $db->insertObject('#__action_log_config', $external_source_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the local_listing action log config object.
			$local_listing_action_log_config = new stdClass();
			$local_listing_action_log_config->type_title = 'LOCAL_LISTING';
			$local_listing_action_log_config->type_alias = 'com_sermondistributor.local_listing';
			$local_listing_action_log_config->id_holder = 'id';
			$local_listing_action_log_config->title_holder = 'name';
			$local_listing_action_log_config->table_name = '#__sermondistributor_local_listing';
			$local_listing_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Set the object into the action log config table.
			$local_listing_Inserted = $db->insertObject('#__action_log_config', $local_listing_action_log_config);

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the help_document action log config object.
			$help_document_action_log_config = new stdClass();
			$help_document_action_log_config->type_title = 'HELP_DOCUMENT';
			$help_document_action_log_config->type_alias = 'com_sermondistributor.help_document';
			$help_document_action_log_config->id_holder = 'id';
			$help_document_action_log_config->title_holder = 'title';
			$help_document_action_log_config->table_name = '#__sermondistributor_help_document';
			$help_document_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Set the object into the action log config table.
			$help_document_Inserted = $db->insertObject('#__action_log_config', $help_document_action_log_config);
		}
		// do any updates needed
		if ($type === 'update')
		{

			// Get The Database object
			$db = JFactory::getDbo();

			// Create the preacher content type object.
			$preacher = new stdClass();
			$preacher->type_title = 'Sermondistributor Preacher';
			$preacher->type_alias = 'com_sermondistributor.preacher';
			$preacher->table = '{"special": {"dbtable": "#__sermondistributor_preacher","key": "id","type": "Preacher","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$preacher->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","website":"website","email":"email","icon":"icon","alias":"alias"}}';
			$preacher->router = 'SermondistributorHelperRoute::getPreacherRoute';
			$preacher->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/preacher.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Check if preacher type is already in content_type DB.
			$preacher_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($preacher->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$preacher->type_id = $db->loadResult();
				$preacher_Updated = $db->updateObject('#__content_types', $preacher, 'type_id');
			}
			else
			{
				$preacher_Inserted = $db->insertObject('#__content_types', $preacher);
			}

			// Create the sermon content type object.
			$sermon = new stdClass();
			$sermon->type_title = 'Sermondistributor Sermon';
			$sermon->type_alias = 'com_sermondistributor.sermon';
			$sermon->table = '{"special": {"dbtable": "#__sermondistributor_sermon","key": "id","type": "Sermon","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$sermon->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "catid","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","preacher":"preacher","series":"series","short_description":"short_description","link_type":"link_type","source":"source","local_files":"local_files","alias":"alias","description":"description","tags":"tags","icon":"icon","build":"build","manual_files":"manual_files","auto_sermons":"auto_sermons","url":"url","scripture":"scripture"}}';
			$sermon->router = 'SermondistributorHelperRoute::getSermonRoute';
			$sermon->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/sermon.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","auto_sermons"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","preacher","series","catid","link_type","source","build"],"displayLookup": [{"sourceColumn": "catid","targetTable": "#__categories","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"}]}';

			// Check if sermon type is already in content_type DB.
			$sermon_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($sermon->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$sermon->type_id = $db->loadResult();
				$sermon_Updated = $db->updateObject('#__content_types', $sermon, 'type_id');
			}
			else
			{
				$sermon_Inserted = $db->insertObject('#__content_types', $sermon);
			}

			// Create the sermon category content type object.
			$sermon_category = new stdClass();
			$sermon_category->type_title = 'Sermondistributor Sermon Catid';
			$sermon_category->type_alias = 'com_sermondistributor.sermon.category';
			$sermon_category->table = '{"special":{"dbtable":"#__categories","key":"id","type":"Category","prefix":"JTable","config":"array()"},"common":{"dbtable":"#__ucm_content","key":"ucm_id","type":"Corecontent","prefix":"JTable","config":"array()"}}';
			$sermon_category->field_mappings = '{"common":{"core_content_item_id":"id","core_title":"title","core_state":"published","core_alias":"alias","core_created_time":"created_time","core_modified_time":"modified_time","core_body":"description", "core_hits":"hits","core_publish_up":"null","core_publish_down":"null","core_access":"access", "core_params":"params", "core_featured":"null", "core_metadata":"metadata", "core_language":"language", "core_images":"null", "core_urls":"null", "core_version":"version", "core_ordering":"null", "core_metakey":"metakey", "core_metadesc":"metadesc", "core_catid":"parent_id", "core_xreference":"null", "asset_id":"asset_id"}, "special":{"parent_id":"parent_id","lft":"lft","rgt":"rgt","level":"level","path":"path","extension":"extension","note":"note"}}';
			$sermon_category->router = 'SermondistributorHelperRoute::getCategoryRoute';
			$sermon_category->content_history_options = '{"formFile":"administrator\/components\/com_categories\/models\/forms\/category.xml", "hideFields":["asset_id","checked_out","checked_out_time","version","lft","rgt","level","path","extension"], "ignoreChanges":["modified_user_id", "modified_time", "checked_out", "checked_out_time", "version", "hits", "path"],"convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"created_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_user_id","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"parent_id","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"}]}';

			// Check if sermon category type is already in content_type DB.
			$sermon_category_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($sermon_category->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$sermon_category->type_id = $db->loadResult();
				$sermon_category_Updated = $db->updateObject('#__content_types', $sermon_category, 'type_id');
			}
			else
			{
				$sermon_category_Inserted = $db->insertObject('#__content_types', $sermon_category);
			}

			// Create the series content type object.
			$series = new stdClass();
			$series->type_title = 'Sermondistributor Series';
			$series->type_alias = 'com_sermondistributor.series';
			$series->table = '{"special": {"dbtable": "#__sermondistributor_series","key": "id","type": "Series","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$series->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","scripture":"scripture","icon":"icon","alias":"alias"}}';
			$series->router = 'SermondistributorHelperRoute::getSeriesRoute';
			$series->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/series.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Check if series type is already in content_type DB.
			$series_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($series->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$series->type_id = $db->loadResult();
				$series_Updated = $db->updateObject('#__content_types', $series, 'type_id');
			}
			else
			{
				$series_Inserted = $db->insertObject('#__content_types', $series);
			}

			// Create the statistic content type object.
			$statistic = new stdClass();
			$statistic->type_title = 'Sermondistributor Statistic';
			$statistic->type_alias = 'com_sermondistributor.statistic';
			$statistic->table = '{"special": {"dbtable": "#__sermondistributor_statistic","key": "id","type": "Statistic","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$statistic->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "filename","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"filename":"filename","sermon":"sermon","preacher":"preacher","series":"series","counter":"counter"}}';
			$statistic->router = 'SermondistributorHelperRoute::getStatisticRoute';
			$statistic->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/statistic.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","sermon","preacher","series","counter"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "id","displayColumn": "name"}]}';

			// Check if statistic type is already in content_type DB.
			$statistic_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($statistic->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$statistic->type_id = $db->loadResult();
				$statistic_Updated = $db->updateObject('#__content_types', $statistic, 'type_id');
			}
			else
			{
				$statistic_Inserted = $db->insertObject('#__content_types', $statistic);
			}

			// Create the external_source content type object.
			$external_source = new stdClass();
			$external_source->type_title = 'Sermondistributor External_source';
			$external_source->type_alias = 'com_sermondistributor.external_source';
			$external_source->table = '{"special": {"dbtable": "#__sermondistributor_external_source","key": "id","type": "External_source","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$external_source->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "description","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"description":"description","externalsources":"externalsources","update_method":"update_method","filetypes":"filetypes","build":"build","not_required":"not_required","update_timer":"update_timer","dropboxoptions":"dropboxoptions","permissiontype":"permissiontype","oauthtoken":"oauthtoken"}}';
			$external_source->router = 'SermondistributorHelperRoute::getExternal_sourceRoute';
			$external_source->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/external_source.xml","hideFields": ["asset_id","checked_out","checked_out_time","version","not_required"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","externalsources","update_method","build","not_required","update_timer","dropboxoptions"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}';

			// Check if external_source type is already in content_type DB.
			$external_source_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($external_source->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$external_source->type_id = $db->loadResult();
				$external_source_Updated = $db->updateObject('#__content_types', $external_source, 'type_id');
			}
			else
			{
				$external_source_Inserted = $db->insertObject('#__content_types', $external_source);
			}

			// Create the local_listing content type object.
			$local_listing = new stdClass();
			$local_listing->type_title = 'Sermondistributor Local_listing';
			$local_listing->type_alias = 'com_sermondistributor.local_listing';
			$local_listing->table = '{"special": {"dbtable": "#__sermondistributor_local_listing","key": "id","type": "Local_listing","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$local_listing->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "null","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","build":"build","size":"size","external_source":"external_source","key":"key","url":"url"}}';
			$local_listing->router = 'SermondistributorHelperRoute::getLocal_listingRoute';
			$local_listing->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/local_listing.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","build","size","external_source"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "external_source","targetTable": "#__sermondistributor_external_source","targetColumn": "id","displayColumn": "description"}]}';

			// Check if local_listing type is already in content_type DB.
			$local_listing_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($local_listing->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$local_listing->type_id = $db->loadResult();
				$local_listing_Updated = $db->updateObject('#__content_types', $local_listing, 'type_id');
			}
			else
			{
				$local_listing_Inserted = $db->insertObject('#__content_types', $local_listing);
			}

			// Create the help_document content type object.
			$help_document = new stdClass();
			$help_document->type_title = 'Sermondistributor Help_document';
			$help_document->type_alias = 'com_sermondistributor.help_document';
			$help_document->table = '{"special": {"dbtable": "#__sermondistributor_help_document","key": "id","type": "Help_document","prefix": "sermondistributorTable","config": "array()"},"common": {"dbtable": "#__ucm_content","key": "ucm_id","type": "Corecontent","prefix": "JTable","config": "array()"}}';
			$help_document->field_mappings = '{"common": {"core_content_item_id": "id","core_title": "title","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "content","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"title":"title","type":"type","groups":"groups","location":"location","admin_view":"admin_view","site_view":"site_view","alias":"alias","content":"content","article":"article","url":"url","target":"target"}}';
			$help_document->router = 'SermondistributorHelperRoute::getHelp_documentRoute';
			$help_document->content_history_options = '{"formFile": "administrator/components/com_sermondistributor/models/forms/help_document.xml","hideFields": ["asset_id","checked_out","checked_out_time","version"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","type","location","article","target"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "article","targetTable": "#__content","targetColumn": "id","displayColumn": "title"}]}';

			// Check if help_document type is already in content_type DB.
			$help_document_id = null;
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('type_id')));
			$query->from($db->quoteName('#__content_types'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($help_document->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$help_document->type_id = $db->loadResult();
				$help_document_Updated = $db->updateObject('#__content_types', $help_document, 'type_id');
			}
			else
			{
				$help_document_Inserted = $db->insertObject('#__content_types', $help_document);
			}



			// check if any links were found
			if ((isset($this->updateTargetsU) && SermondistributorHelper::checkArray($this->updateTargetsU)) || (isset($this->updateTargetsF) && SermondistributorHelper::checkArray($this->updateTargetsF)))
			{
				// Get a db connection.
				$db = JFactory::getDbo();

				// get the file types
				$dropbox_filetypes = $this->comParams->get("dropbox_filetypes", null);

				// some defaults
				$user = JFactory::getUser();
				$todayDate = JFactory::getDate()->toSql();

				// now store the old data to the new area
				if (isset($this->updateTargetsU) &&SermondistributorHelper::checkArray($this->updateTargetsU))
				{
					foreach ($this->updateTargetsU as $type => $urls)
					{
						$description = 'Config '. $type . ' url ';
						$buildOption = 1;
						if ('auto' == $type)
						{
							$buildOption = 2;
						}
						$urls = '"'.implode('", "', $urls).'"';
						$data = new stdClass();
						if (SermondistributorHelper::checkArray($dropbox_filetypes))
						{
							$data->filetypes = json_encode($dropbox_filetypes);
						}
						$data->externalsources = 1;
						$data->build = $buildOption;
						$data->description = $description;
						$data->update_method = 1;
						$data->update_timer = 0;
						$data->permissiontype = 'full';
						$data->created = $todayDate;
						$data->created_by = $user->id;
						$data->sharedurl = '{"tsharedurl":['.$urls.']}';
						// add to database
						if ($db->insertObject('#__sermondistributor_external_source', $data))
						{
							$aId = $db->insertid();
							// make sure the access of asset is set
							SermondistributorHelper::setAsset($aId,'external_source');
						}
					}
				}
				if (isset($this->updateTargetsF) && SermondistributorHelper::checkArray($this->updateTargetsF))
				{
					foreach ($this->updateTargetsF as $type => $folder)
					{
						$description = 'Config '. $type . ' folder ';
						$buildOption = 1;
						if ('auto' == $type)
						{
							$buildOption = 2;
						}
						$folder = '"'.implode('", "', $folder).'"';
						$data = new stdClass();
						if (SermondistributorHelper::checkArray($dropbox_filetypes))
						{
							$data->filetypes = json_encode($dropbox_filetypes);
						}
						$data->externalsources = 1;
						$data->build = $buildOption;
						$data->description = $description;
						$data->update_method = 1;
						$data->update_timer = 0;
						$data->permissiontype = 'full';
						$data->created = $todayDate;
						$data->created_by = $user->id;
						$data->folder = '{"tfolder":['.$folder.']}';
						// add to database
						if ($db->insertObject('#__sermondistributor_external_source', $data))
						{
							$aId = $db->insertid();
							// make sure the access of asset is set
							SermondistributorHelper::setAsset($aId,'external_source');
						}
					}
				}
				// Get Application object
				$app = JFactory::getApplication();
				$app->enqueueMessage('Your Dropbox integration has been moved, and can now be viewed at the new external source view. You will now need an APP token to update your local listing of the Dropbox files. Please review the Wiki tab when creating/editing the external source, or open an issue on github if you experience any more difficulties.', 'Info');
			}
			echo '<a target="_blank" href="https://www.vdm.io/" title="Sermon Distributor">
				<img src="components/com_sermondistributor/assets/images/vdm-component.jpg"/>
				</a>
				<h3>Upgrade to Version 2.0.5 Was Successful! Let us know if anything is not working as expected.</h3>';

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the sermondistributor action logs extensions object.
			$sermondistributor_action_logs_extensions = new stdClass();
			$sermondistributor_action_logs_extensions->extension = 'com_sermondistributor';

			// Check if sermondistributor action log extension is already in action logs extensions DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_logs_extensions'));
			$query->where($db->quoteName('extension') . ' LIKE '. $db->quote($sermondistributor_action_logs_extensions->extension));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the action logs extensions table if not found.
			if (!$db->getNumRows())
			{
				$sermondistributor_action_logs_extensions_Inserted = $db->insertObject('#__action_logs_extensions', $sermondistributor_action_logs_extensions);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the preacher action log config object.
			$preacher_action_log_config = new stdClass();
			$preacher_action_log_config->id = null;
			$preacher_action_log_config->type_title = 'PREACHER';
			$preacher_action_log_config->type_alias = 'com_sermondistributor.preacher';
			$preacher_action_log_config->id_holder = 'id';
			$preacher_action_log_config->title_holder = 'name';
			$preacher_action_log_config->table_name = '#__sermondistributor_preacher';
			$preacher_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Check if preacher action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($preacher_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$preacher_action_log_config->id = $db->loadResult();
				$preacher_action_log_config_Updated = $db->updateObject('#__action_log_config', $preacher_action_log_config, 'id');
			}
			else
			{
				$preacher_action_log_config_Inserted = $db->insertObject('#__action_log_config', $preacher_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the sermon action log config object.
			$sermon_action_log_config = new stdClass();
			$sermon_action_log_config->id = null;
			$sermon_action_log_config->type_title = 'SERMON';
			$sermon_action_log_config->type_alias = 'com_sermondistributor.sermon';
			$sermon_action_log_config->id_holder = 'id';
			$sermon_action_log_config->title_holder = 'name';
			$sermon_action_log_config->table_name = '#__sermondistributor_sermon';
			$sermon_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Check if sermon action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($sermon_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$sermon_action_log_config->id = $db->loadResult();
				$sermon_action_log_config_Updated = $db->updateObject('#__action_log_config', $sermon_action_log_config, 'id');
			}
			else
			{
				$sermon_action_log_config_Inserted = $db->insertObject('#__action_log_config', $sermon_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the series action log config object.
			$series_action_log_config = new stdClass();
			$series_action_log_config->id = null;
			$series_action_log_config->type_title = 'SERIES';
			$series_action_log_config->type_alias = 'com_sermondistributor.series';
			$series_action_log_config->id_holder = 'id';
			$series_action_log_config->title_holder = 'name';
			$series_action_log_config->table_name = '#__sermondistributor_series';
			$series_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Check if series action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($series_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$series_action_log_config->id = $db->loadResult();
				$series_action_log_config_Updated = $db->updateObject('#__action_log_config', $series_action_log_config, 'id');
			}
			else
			{
				$series_action_log_config_Inserted = $db->insertObject('#__action_log_config', $series_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the statistic action log config object.
			$statistic_action_log_config = new stdClass();
			$statistic_action_log_config->id = null;
			$statistic_action_log_config->type_title = 'STATISTIC';
			$statistic_action_log_config->type_alias = 'com_sermondistributor.statistic';
			$statistic_action_log_config->id_holder = 'id';
			$statistic_action_log_config->title_holder = 'filename';
			$statistic_action_log_config->table_name = '#__sermondistributor_statistic';
			$statistic_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Check if statistic action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($statistic_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$statistic_action_log_config->id = $db->loadResult();
				$statistic_action_log_config_Updated = $db->updateObject('#__action_log_config', $statistic_action_log_config, 'id');
			}
			else
			{
				$statistic_action_log_config_Inserted = $db->insertObject('#__action_log_config', $statistic_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the external_source action log config object.
			$external_source_action_log_config = new stdClass();
			$external_source_action_log_config->id = null;
			$external_source_action_log_config->type_title = 'EXTERNAL_SOURCE';
			$external_source_action_log_config->type_alias = 'com_sermondistributor.external_source';
			$external_source_action_log_config->id_holder = 'id';
			$external_source_action_log_config->title_holder = 'description';
			$external_source_action_log_config->table_name = '#__sermondistributor_external_source';
			$external_source_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Check if external_source action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($external_source_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$external_source_action_log_config->id = $db->loadResult();
				$external_source_action_log_config_Updated = $db->updateObject('#__action_log_config', $external_source_action_log_config, 'id');
			}
			else
			{
				$external_source_action_log_config_Inserted = $db->insertObject('#__action_log_config', $external_source_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the local_listing action log config object.
			$local_listing_action_log_config = new stdClass();
			$local_listing_action_log_config->id = null;
			$local_listing_action_log_config->type_title = 'LOCAL_LISTING';
			$local_listing_action_log_config->type_alias = 'com_sermondistributor.local_listing';
			$local_listing_action_log_config->id_holder = 'id';
			$local_listing_action_log_config->title_holder = 'name';
			$local_listing_action_log_config->table_name = '#__sermondistributor_local_listing';
			$local_listing_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Check if local_listing action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($local_listing_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$local_listing_action_log_config->id = $db->loadResult();
				$local_listing_action_log_config_Updated = $db->updateObject('#__action_log_config', $local_listing_action_log_config, 'id');
			}
			else
			{
				$local_listing_action_log_config_Inserted = $db->insertObject('#__action_log_config', $local_listing_action_log_config);
			}

			// Set db if not set already.
			if (!isset($db))
			{
				$db = JFactory::getDbo();
			}
			// Create the help_document action log config object.
			$help_document_action_log_config = new stdClass();
			$help_document_action_log_config->id = null;
			$help_document_action_log_config->type_title = 'HELP_DOCUMENT';
			$help_document_action_log_config->type_alias = 'com_sermondistributor.help_document';
			$help_document_action_log_config->id_holder = 'id';
			$help_document_action_log_config->title_holder = 'title';
			$help_document_action_log_config->table_name = '#__sermondistributor_help_document';
			$help_document_action_log_config->text_prefix = 'COM_SERMONDISTRIBUTOR';

			// Check if help_document action log config is already in action_log_config DB.
			$query = $db->getQuery(true);
			$query->select($db->quoteName(array('id')));
			$query->from($db->quoteName('#__action_log_config'));
			$query->where($db->quoteName('type_alias') . ' LIKE '. $db->quote($help_document_action_log_config->type_alias));
			$db->setQuery($query);
			$db->execute();

			// Set the object into the content types table.
			if ($db->getNumRows())
			{
				$help_document_action_log_config->id = $db->loadResult();
				$help_document_action_log_config_Updated = $db->updateObject('#__action_log_config', $help_document_action_log_config, 'id');
			}
			else
			{
				$help_document_action_log_config_Inserted = $db->insertObject('#__action_log_config', $help_document_action_log_config);
			}
		}
		return true;
	}

	/**
	 * Remove folders with files
	 * 
	 * @param   string   $dir     The path to folder to remove
	 * @param   boolean  $ignore  The folders and files to ignore and not remove
	 *
	 * @return  boolean   True in all is removed
	 * 
	 */
	protected function removeFolder($dir, $ignore = false)
	{
		if (JFolder::exists($dir))
		{
			$it = new RecursiveDirectoryIterator($dir);
			$it = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
			// remove ending /
			$dir = rtrim($dir, '/');
			// now loop the files & folders
			foreach ($it as $file)
			{
				if ('.' === $file->getBasename() || '..' ===  $file->getBasename()) continue;
				// set file dir
				$file_dir = $file->getPathname();
				// check if this is a dir or a file
				if ($file->isDir())
				{
					$keeper = false;
					if ($this->checkArray($ignore))
					{
						foreach ($ignore as $keep)
						{
							if (strpos($file_dir, $dir.'/'.$keep) !== false)
							{
								$keeper = true;
							}
						}
					}
					if ($keeper)
					{
						continue;
					}
					JFolder::delete($file_dir);
				}
				else
				{
					$keeper = false;
					if ($this->checkArray($ignore))
					{
						foreach ($ignore as $keep)
						{
							if (strpos($file_dir, $dir.'/'.$keep) !== false)
							{
								$keeper = true;
							}
						}
					}
					if ($keeper)
					{
						continue;
					}
					JFile::delete($file_dir);
				}
			}
			// delete the root folder if not ignore found
			if (!$this->checkArray($ignore))
			{
				return JFolder::delete($dir);
			}
			return true;
		}
		return false;
	}

	/**
	 * Check if have an array with a length
	 *
	 * @input	array   The array to check
	 *
	 * @returns bool/int  number of items in array on success
	 */
	protected function checkArray($array, $removeEmptyString = false)
	{
		if (isset($array) && is_array($array) && ($nr = count((array)$array)) > 0)
		{
			// also make sure the empty strings are removed
			if ($removeEmptyString)
			{
				foreach ($array as $key => $string)
				{
					if (empty($string))
					{
						unset($array[$key]);
					}
				}
				return $this->checkArray($array, false);
			}
			return $nr;
		}
		return false;
	}

	/**
	 * Method to set/copy dynamic folders into place (use with caution)
	 *
	 * @return void
	 */
	protected function setDynamicF0ld3rs($app, $parent)
	{
		// get the instalation path
		$installer = $parent->getParent();
		$installPath = $installer->getPath('source');
		// get all the folders
		$folders = JFolder::folders($installPath);
		// check if we have folders we may want to copy
		$doNotCopy = array('media','admin','site'); // Joomla already deals with these
		if (count((array) $folders) > 1)
		{
			foreach ($folders as $folder)
			{
				// Only copy if not a standard folders
				if (!in_array($folder, $doNotCopy))
				{
					// set the source path
					$src = $installPath.'/'.$folder;
					// set the destination path
					$dest = JPATH_ROOT.'/'.$folder;
					// now try to copy the folder
					if (!JFolder::copy($src, $dest, '', true))
					{
						$app->enqueueMessage('Could not copy '.$folder.' folder into place, please make sure destination is writable!', 'error');
					}
				}
			}
		}
	}
}
