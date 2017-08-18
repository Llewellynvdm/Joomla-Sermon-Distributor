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

	@version		@update number 11 of this MVC
	@build			31st March, 2017
	@created		20th November, 2016
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
 * Sermondistributor View class for the Local_listings
 */
class SermondistributorViewLocal_listings extends JViewLegacy
{
	/**
	 * Local_listings view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			SermondistributorHelper::addSubmenu('local_listings');
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
		$this->canDo		= SermondistributorHelper::getActions('local_listing');
		$this->canEdit		= $this->canDo->get('local_listing.edit');
		$this->canState		= $this->canDo->get('local_listing.edit.state');
		$this->canCreate	= $this->canDo->get('local_listing.create');
		$this->canDelete	= $this->canDo->get('local_listing.delete');
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
		JToolBarHelper::title(JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTINGS'), 'link');
		JHtmlSidebar::setAction('index.php?option=com_sermondistributor&view=local_listings');
                JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
                {
			JToolBarHelper::addNew('local_listing.add');
		}

                // Only load if there are items
                if (SermondistributorHelper::checkArray($this->items))
		{
                        if ($this->canEdit)
                        {
                            JToolBarHelper::editList('local_listing.edit');
                        }

                        if ($this->canState)
                        {
                            JToolBarHelper::publishList('local_listings.publish');
                            JToolBarHelper::unpublishList('local_listings.unpublish');
                            JToolBarHelper::archiveList('local_listings.archive');

                            if ($this->canDo->get('core.admin'))
                            {
                                JToolBarHelper::checkin('local_listings.checkin');
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
                            JToolbarHelper::deleteList('', 'local_listings.delete', 'JTOOLBAR_EMPTY_TRASH');
                        }
                        elseif ($this->canState && $this->canDelete)
                        {
                                JToolbarHelper::trash('local_listings.trash');
                        }

			if ($this->canDo->get('core.export') && $this->canDo->get('local_listing.export'))
			{
				JToolBarHelper::custom('local_listings.exportData', 'download', '', 'COM_SERMONDISTRIBUTOR_EXPORT_DATA', true);
			}
                } 

		if ($this->canDo->get('core.import') && $this->canDo->get('local_listing.import'))
		{
			JToolBarHelper::custom('local_listings.importData', 'upload', '', 'COM_SERMONDISTRIBUTOR_IMPORT_DATA', false);
		}

                // set help url for this view if found
                $help_url = SermondistributorHelper::getHelpUrl('local_listings');
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

		// Set Build Selection
		$this->buildOptions = $this->getTheBuildSelections();
		if ($this->buildOptions)
		{
			// Build Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_BUILD_LABEL').' -',
				'filter_build',
				JHtml::_('select.options', $this->buildOptions, 'value', 'text', $this->state->get('filter.build'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Build Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_BUILD_LABEL').' -',
					'batch[build]',
					JHtml::_('select.options', $this->buildOptions, 'value', 'text')
				);
			}
		}

		// Set External Source Description Selection
		$this->external_sourceDescriptionOptions = JFormHelper::loadFieldType('Externalsource')->getOptions();
		if ($this->external_sourceDescriptionOptions)
		{
			// External Source Description Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_EXTERNAL_SOURCE_LABEL').' -',
				'filter_external_source',
				JHtml::_('select.options', $this->external_sourceDescriptionOptions, 'value', 'text', $this->state->get('filter.external_source'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// External Source Description Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_EXTERNAL_SOURCE_LABEL').' -',
					'batch[external_source]',
					JHtml::_('select.options', $this->external_sourceDescriptionOptions, 'value', 'text')
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
		$document->setTitle(JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTINGS'));
		$document->addStyleSheet(JURI::root() . "administrator/components/com_sermondistributor/assets/css/local_listings.css");
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
			'a.name' => JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_NAME_LABEL'),
			'a.build' => JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_BUILD_LABEL'),
			'a.size' => JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_SIZE_LABEL'),
			'g.description' => JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_EXTERNAL_SOURCE_LABEL'),
			'a.key' => JText::_('COM_SERMONDISTRIBUTOR_LOCAL_LISTING_KEY_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	} 

	protected function getTheBuildSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('build'));
		$query->from($db->quoteName('#__sermondistributor_local_listing'));
		$query->order($db->quoteName('build') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $build)
			{
				// Translate the build selection
				$text = $model->selectionTranslation($build,'build');
				// Now add the build and its text to the options array
				$_filter[] = JHtml::_('select.option', $build, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}
}
