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
	@build			26th February, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		view.html.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Sermondistributor View class for the Help_documents
 */
class SermondistributorViewHelp_documents extends JViewLegacy
{
	/**
	 * Help_documents view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			SermondistributorHelper::addSubmenu('help_documents');
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
                {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Assign data to the view
		$this->items 		= $this->get('Items');
		$this->pagination 	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->user 		= JFactory::getUser();
		$this->listOrder	= $this->escape($this->state->get('list.ordering'));
		$this->listDirn		= $this->escape($this->state->get('list.direction'));
		$this->saveOrder	= $this->listOrder == 'ordering';
                // get global action permissions
		$this->canDo		= SermondistributorHelper::getActions('help_document');
		$this->canEdit		= $this->canDo->get('help_document.edit');
		$this->canState		= $this->canDo->get('help_document.edit.state');
		$this->canCreate	= $this->canDo->get('help_document.create');
		$this->canDelete	= $this->canDo->get('help_document.delete');
		$this->canBatch	= $this->canDo->get('core.batch');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
                        // load the batch html
                        if ($this->canCreate && $this->canEdit && $this->canState)
                        {
                                $this->batchDisplay = JHtmlBatch_::render();
                        }
		}

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		JToolBarHelper::title(JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENTS'), 'support');
		JHtmlSidebar::setAction('index.php?option=com_sermondistributor&view=help_documents');
                JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
                {
			JToolBarHelper::addNew('help_document.add');
		}

                // Only load if there are items
                if (SermondistributorHelper::checkArray($this->items))
		{
                        if ($this->canEdit)
                        {
                            JToolBarHelper::editList('help_document.edit');
                        }

                        if ($this->canState)
                        {
                            JToolBarHelper::publishList('help_documents.publish');
                            JToolBarHelper::unpublishList('help_documents.unpublish');
                            JToolBarHelper::archiveList('help_documents.archive');

                            if ($this->canDo->get('core.admin'))
                            {
                                JToolBarHelper::checkin('help_documents.checkin');
                            }
                        }

                        // Add a batch button
                        if ($this->canBatch && $this->canCreate && $this->canEdit && $this->canState)
                        {
                                // Get the toolbar object instance
                                $bar = JToolBar::getInstance('toolbar');
                                // set the batch button name
                                $title = JText::_('JTOOLBAR_BATCH');
                                // Instantiate a new JLayoutFile instance and render the batch button
                                $layout = new JLayoutFile('joomla.toolbar.batch');
                                // add the button to the page
                                $dhtml = $layout->render(array('title' => $title));
                                $bar->appendButton('Custom', $dhtml, 'batch');
                        } 

                        if ($this->state->get('filter.published') == -2 && ($this->canState && $this->canDelete))
                        {
                            JToolbarHelper::deleteList('', 'help_documents.delete', 'JTOOLBAR_EMPTY_TRASH');
                        }
                        elseif ($this->canState && $this->canDelete)
                        {
                                JToolbarHelper::trash('help_documents.trash');
                        }

			if ($this->canDo->get('core.export') && $this->canDo->get('help_document.export'))
			{
				JToolBarHelper::custom('help_documents.exportData', 'download', '', 'COM_SERMONDISTRIBUTOR_EXPORT_DATA', true);
			}
                }

		if ($this->canDo->get('core.import') && $this->canDo->get('help_document.import'))
		{
			JToolBarHelper::custom('help_documents.importData', 'upload', '', 'COM_SERMONDISTRIBUTOR_IMPORT_DATA', false);
		}

                // set help url for this view if found
                $help_url = SermondistributorHelper::getHelpUrl('help_documents');
                if (SermondistributorHelper::checkString($help_url))
                {
                        JToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $help_url);
                }

                // add the options comp button
                if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
                {
                        JToolBarHelper::preferences('com_sermondistributor');
                }

                if ($this->canState)
                {
			JHtmlSidebar::addFilter(
				JText::_('JOPTION_SELECT_PUBLISHED'),
				'filter_published',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true)
			);
                        // only load if batch allowed
                        if ($this->canBatch)
                        {
                            JHtmlBatch_::addListSelection(
                                JText::_('COM_SERMONDISTRIBUTOR_KEEP_ORIGINAL_STATE'),
                                'batch[published]',
                                JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('all' => false)), 'value', 'text', '', true)
                            );
                        }
		}

		JHtmlSidebar::addFilter(
			JText::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			JHtmlBatch_::addListSelection(
                                JText::_('COM_SERMONDISTRIBUTOR_KEEP_ORIGINAL_ACCESS'),
                                'batch[access]',
                                JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text')
			);
                }  

		// [Interpretation 8459] Set Type Selection
		$this->typeOptions = $this->getTheTypeSelections();
		if ($this->typeOptions)
		{
			// [Interpretation 8463] Type Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TYPE_LABEL').' -',
				'filter_type',
				JHtml::_('select.options', $this->typeOptions, 'value', 'text', $this->state->get('filter.type'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 8472] Type Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TYPE_LABEL').' -',
					'batch[type]',
					JHtml::_('select.options', $this->typeOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 8459] Set Location Selection
		$this->locationOptions = $this->getTheLocationSelections();
		if ($this->locationOptions)
		{
			// [Interpretation 8463] Location Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_LOCATION_LABEL').' -',
				'filter_location',
				JHtml::_('select.options', $this->locationOptions, 'value', 'text', $this->state->get('filter.location'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 8472] Location Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_LOCATION_LABEL').' -',
					'batch[location]',
					JHtml::_('select.options', $this->locationOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 8459] Set Admin View Selection
		$this->admin_viewOptions = $this->getTheAdmin_viewSelections();
		if ($this->admin_viewOptions)
		{
			// [Interpretation 8463] Admin View Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_ADMIN_VIEW_LABEL').' -',
				'filter_admin_view',
				JHtml::_('select.options', $this->admin_viewOptions, 'value', 'text', $this->state->get('filter.admin_view'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 8472] Admin View Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_ADMIN_VIEW_LABEL').' -',
					'batch[admin_view]',
					JHtml::_('select.options', $this->admin_viewOptions, 'value', 'text')
				);
			}
		}

		// [Interpretation 8459] Set Site View Selection
		$this->site_viewOptions = $this->getTheSite_viewSelections();
		if ($this->site_viewOptions)
		{
			// [Interpretation 8463] Site View Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SITE_VIEW_LABEL').' -',
				'filter_site_view',
				JHtml::_('select.options', $this->site_viewOptions, 'value', 'text', $this->state->get('filter.site_view'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// [Interpretation 8472] Site View Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SITE_VIEW_LABEL').' -',
					'batch[site_view]',
					JHtml::_('select.options', $this->site_viewOptions, 'value', 'text')
				);
			}
		}
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENTS'));
		$document->addStyleSheet(JURI::root() . "administrator/components/com_sermondistributor/assets/css/help_documents.css");
	}

        /**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var)
	{
		if(strlen($var) > 50)
		{
                        // use the helper htmlEscape method instead and shorten the string
			return SermondistributorHelper::htmlEscape($var, $this->_charset, true);
		}
                // use the helper htmlEscape method instead.
		return SermondistributorHelper::htmlEscape($var, $this->_charset);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.sorting' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.title' => JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TITLE_LABEL'),
			'a.type' => JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TYPE_LABEL'),
			'a.location' => JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_LOCATION_LABEL'),
			'a.admin_view' => JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_ADMIN_VIEW_LABEL'),
			'a.site_view' => JText::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SITE_VIEW_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	} 

	public function getTheTypeSelections()
	{
		// [Interpretation 8335] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 8337] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 8339] Select the text.
		$query->select($db->quoteName('type'));
		$query->from($db->quoteName('#__sermondistributor_help_document'));
		$query->order($db->quoteName('type') . ' ASC');

		// [Interpretation 8343] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// [Interpretation 8351] get model
			$model = $this->getModel();
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $type)
			{
				// [Interpretation 8362] Translate the type selection
				$text = $model->selectionTranslation($type,'type');
				// [Interpretation 8364] Now add the type and its text to the options array
				$filter[] = JHtml::_('select.option', $type, JText::_($text));
			}
			return $filter;
		}
		return false;
	}

	public function getTheLocationSelections()
	{
		// [Interpretation 8335] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 8337] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 8339] Select the text.
		$query->select($db->quoteName('location'));
		$query->from($db->quoteName('#__sermondistributor_help_document'));
		$query->order($db->quoteName('location') . ' ASC');

		// [Interpretation 8343] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// [Interpretation 8351] get model
			$model = $this->getModel();
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $location)
			{
				// [Interpretation 8362] Translate the location selection
				$text = $model->selectionTranslation($location,'location');
				// [Interpretation 8364] Now add the location and its text to the options array
				$filter[] = JHtml::_('select.option', $location, JText::_($text));
			}
			return $filter;
		}
		return false;
	}

	public function getTheAdmin_viewSelections()
	{
		// [Interpretation 8335] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 8337] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 8339] Select the text.
		$query->select($db->quoteName('admin_view'));
		$query->from($db->quoteName('#__sermondistributor_help_document'));
		$query->order($db->quoteName('admin_view') . ' ASC');

		// [Interpretation 8343] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $admin_view)
			{
				// [Interpretation 8369] Now add the admin_view and its text to the options array
				$filter[] = JHtml::_('select.option', $admin_view, $admin_view);
			}
			return $filter;
		}
		return false;
	}

	public function getTheSite_viewSelections()
	{
		// [Interpretation 8335] Get a db connection.
		$db = JFactory::getDbo();

		// [Interpretation 8337] Create a new query object.
		$query = $db->getQuery(true);

		// [Interpretation 8339] Select the text.
		$query->select($db->quoteName('site_view'));
		$query->from($db->quoteName('#__sermondistributor_help_document'));
		$query->order($db->quoteName('site_view') . ' ASC');

		// [Interpretation 8343] Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			$results = array_unique($results);
			$filter = array();
			foreach ($results as $site_view)
			{
				// [Interpretation 8369] Now add the site_view and its text to the options array
				$filter[] = JHtml::_('select.option', $site_view, $site_view);
			}
			return $filter;
		}
		return false;
	}
}
