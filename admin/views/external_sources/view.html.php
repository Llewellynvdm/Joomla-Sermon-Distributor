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
	@subpackage		view.html.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Sermondistributor View class for the External_sources
 */
class SermondistributorViewExternal_sources extends JViewLegacy
{
	/**
	 * External_sources view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			SermondistributorHelper::addSubmenu('external_sources');
		}

		// Assign data to the view
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->user = JFactory::getUser();
		$this->listOrder = $this->escape($this->state->get('list.ordering'));
		$this->listDirn = $this->escape($this->state->get('list.direction'));
		$this->saveOrder = $this->listOrder == 'ordering';
		// set the return here value
		$this->return_here = urlencode(base64_encode((string) JUri::getInstance()));
		// get global action permissions
		$this->canDo = SermondistributorHelper::getActions('external_source');
		$this->canEdit = $this->canDo->get('external_source.edit');
		$this->canState = $this->canDo->get('external_source.edit.state');
		$this->canCreate = $this->canDo->get('external_source.create');
		$this->canDelete = $this->canDo->get('external_source.delete');
		$this->canBatch = $this->canDo->get('core.batch');

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
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors), 500);
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
		JToolBarHelper::title(JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCES'), 'puzzle');
		JHtmlSidebar::setAction('index.php?option=com_sermondistributor&view=external_sources');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('external_source.add');
		}

		// Only load if there are items
		if (SermondistributorHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('external_source.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('external_sources.publish');
				JToolBarHelper::unpublishList('external_sources.unpublish');
				JToolBarHelper::archiveList('external_sources.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('external_sources.checkin');
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
				JToolbarHelper::deleteList('', 'external_sources.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('external_sources.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('external_source.export'))
			{
				JToolBarHelper::custom('external_sources.exportData', 'download', '', 'COM_SERMONDISTRIBUTOR_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('external_source.import'))
		{
			JToolBarHelper::custom('external_sources.importData', 'upload', '', 'COM_SERMONDISTRIBUTOR_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$help_url = SermondistributorHelper::getHelpUrl('external_sources');
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

		// Set Externalsources Selection
		$this->externalsourcesOptions = $this->getTheExternalsourcesSelections();
		// We do some sanitation for Externalsources filter
		if (SermondistributorHelper::checkArray($this->externalsourcesOptions) &&
			isset($this->externalsourcesOptions[0]->value) &&
			!SermondistributorHelper::checkString($this->externalsourcesOptions[0]->value))
		{
			unset($this->externalsourcesOptions[0]);
		}
		// Only load Externalsources filter if it has values
		if (SermondistributorHelper::checkArray($this->externalsourcesOptions))
		{
			// Externalsources Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_EXTERNALSOURCES_LABEL').' -',
				'filter_externalsources',
				JHtml::_('select.options', $this->externalsourcesOptions, 'value', 'text', $this->state->get('filter.externalsources'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Externalsources Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_EXTERNALSOURCES_LABEL').' -',
					'batch[externalsources]',
					JHtml::_('select.options', $this->externalsourcesOptions, 'value', 'text')
				);
			}
		}

		// Set Update Method Selection
		$this->update_methodOptions = $this->getTheUpdate_methodSelections();
		// We do some sanitation for Update Method filter
		if (SermondistributorHelper::checkArray($this->update_methodOptions) &&
			isset($this->update_methodOptions[0]->value) &&
			!SermondistributorHelper::checkString($this->update_methodOptions[0]->value))
		{
			unset($this->update_methodOptions[0]);
		}
		// Only load Update Method filter if it has values
		if (SermondistributorHelper::checkArray($this->update_methodOptions))
		{
			// Update Method Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_UPDATE_METHOD_LABEL').' -',
				'filter_update_method',
				JHtml::_('select.options', $this->update_methodOptions, 'value', 'text', $this->state->get('filter.update_method'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Update Method Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_UPDATE_METHOD_LABEL').' -',
					'batch[update_method]',
					JHtml::_('select.options', $this->update_methodOptions, 'value', 'text')
				);
			}
		}

		// Set Build Selection
		$this->buildOptions = $this->getTheBuildSelections();
		// We do some sanitation for Build filter
		if (SermondistributorHelper::checkArray($this->buildOptions) &&
			isset($this->buildOptions[0]->value) &&
			!SermondistributorHelper::checkString($this->buildOptions[0]->value))
		{
			unset($this->buildOptions[0]);
		}
		// Only load Build filter if it has values
		if (SermondistributorHelper::checkArray($this->buildOptions))
		{
			// Build Filter
			JHtmlSidebar::addFilter(
				'- Select '.JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_BUILD_LABEL').' -',
				'filter_build',
				JHtml::_('select.options', $this->buildOptions, 'value', 'text', $this->state->get('filter.build'))
			);

			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Build Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_BUILD_LABEL').' -',
					'batch[build]',
					JHtml::_('select.options', $this->buildOptions, 'value', 'text')
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
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCES'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_sermondistributor/assets/css/external_sources.css", (SermondistributorHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.description' => JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_DESCRIPTION_LABEL'),
			'a.externalsources' => JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_EXTERNALSOURCES_LABEL'),
			'a.update_method' => JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_UPDATE_METHOD_LABEL'),
			'a.build' => JText::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_BUILD_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheExternalsourcesSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('externalsources'));
		$query->from($db->quoteName('#__sermondistributor_external_source'));
		$query->order($db->quoteName('externalsources') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $externalsources)
			{
				// Translate the externalsources selection
				$text = $model->selectionTranslation($externalsources,'externalsources');
				// Now add the externalsources and its text to the options array
				$_filter[] = JHtml::_('select.option', $externalsources, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheUpdate_methodSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('update_method'));
		$query->from($db->quoteName('#__sermondistributor_external_source'));
		$query->order($db->quoteName('update_method') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $update_method)
			{
				// Translate the update_method selection
				$text = $model->selectionTranslation($update_method,'update_method');
				// Now add the update_method and its text to the options array
				$_filter[] = JHtml::_('select.option', $update_method, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheBuildSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('build'));
		$query->from($db->quoteName('#__sermondistributor_external_source'));
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
