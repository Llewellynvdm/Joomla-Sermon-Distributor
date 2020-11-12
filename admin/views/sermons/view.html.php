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
 * Sermondistributor View class for the Sermons
 */
class SermondistributorViewSermons extends JViewLegacy
{
	/**
	 * Sermons view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			SermondistributorHelper::addSubmenu('sermons');
		}

		// Assign data to the view
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->user = JFactory::getUser();
		// Add the list ordering clause.
		$this->listOrder = $this->escape($this->state->get('list.ordering', 'a.id'));
		$this->listDirn = $this->escape($this->state->get('list.direction', 'asc'));
		$this->saveOrder = $this->listOrder == 'a.ordering';
		// set the return here value
		$this->return_here = urlencode(base64_encode((string) JUri::getInstance()));
		// get global action permissions
		$this->canDo = SermondistributorHelper::getActions('sermon');
		$this->canEdit = $this->canDo->get('sermon.edit');
		$this->canState = $this->canDo->get('sermon.edit.state');
		$this->canCreate = $this->canDo->get('sermon.create');
		$this->canDelete = $this->canDo->get('sermon.delete');
		$this->canBatch = $this->canDo->get('core.batch');
		$this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

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
		JToolBarHelper::title(JText::_('COM_SERMONDISTRIBUTOR_SERMONS'), 'book');
		JHtmlSidebar::setAction('index.php?option=com_sermondistributor&view=sermons');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('sermon.add');
		}

		// Only load if there are items
		if (SermondistributorHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('sermon.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('sermons.publish');
				JToolBarHelper::unpublishList('sermons.unpublish');
				JToolBarHelper::archiveList('sermons.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('sermons.checkin');
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
				JToolbarHelper::deleteList('', 'sermons.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('sermons.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('sermon.export'))
			{
				JToolBarHelper::custom('sermons.exportData', 'download', '', 'COM_SERMONDISTRIBUTOR_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('sermon.import'))
		{
			JToolBarHelper::custom('sermons.importData', 'upload', '', 'COM_SERMONDISTRIBUTOR_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$help_url = SermondistributorHelper::getHelpUrl('sermons');
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

		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			JHtmlBatch_::addListSelection(
				JText::_('COM_SERMONDISTRIBUTOR_KEEP_ORIGINAL_ACCESS'),
				'batch[access]',
				JHtml::_('select.options', JHtml::_('access.assetgroups'), 'value', 'text')
			);
		}

		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Category Batch selection.
			JHtmlBatch_::addListSelection(
				JText::_('COM_SERMONDISTRIBUTOR_KEEP_ORIGINAL_CATEGORY'),
				'batch[category]',
				JHtml::_('select.options', JHtml::_('category.options', 'com_sermondistributor.sermon'), 'value', 'text')
			);
		}

		// Set Preacher Name Selection
		$this->preacherNameOptions = JFormHelper::loadFieldType('Preachers')->options;
		// We do some sanitation for Preacher Name filter
		if (SermondistributorHelper::checkArray($this->preacherNameOptions) &&
			isset($this->preacherNameOptions[0]->value) &&
			!SermondistributorHelper::checkString($this->preacherNameOptions[0]->value))
		{
			unset($this->preacherNameOptions[0]);
		}
		// Only load Preacher Name filter if it has values
		if (SermondistributorHelper::checkArray($this->preacherNameOptions))
		{
			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Preacher Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_PREACHER_LABEL').' -',
					'batch[preacher]',
					JHtml::_('select.options', $this->preacherNameOptions, 'value', 'text')
				);
			}
		}

		// Set Series Name Selection
		$this->seriesNameOptions = JFormHelper::loadFieldType('Series')->options;
		// We do some sanitation for Series Name filter
		if (SermondistributorHelper::checkArray($this->seriesNameOptions) &&
			isset($this->seriesNameOptions[0]->value) &&
			!SermondistributorHelper::checkString($this->seriesNameOptions[0]->value))
		{
			unset($this->seriesNameOptions[0]);
		}
		// Only load Series Name filter if it has values
		if (SermondistributorHelper::checkArray($this->seriesNameOptions))
		{
			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Series Name Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_SERIES_LABEL').' -',
					'batch[series]',
					JHtml::_('select.options', $this->seriesNameOptions, 'value', 'text')
				);
			}
		}

		// Set Link Type Selection
		$this->link_typeOptions = $this->getTheLink_typeSelections();
		// We do some sanitation for Link Type filter
		if (SermondistributorHelper::checkArray($this->link_typeOptions) &&
			isset($this->link_typeOptions[0]->value) &&
			!SermondistributorHelper::checkString($this->link_typeOptions[0]->value))
		{
			unset($this->link_typeOptions[0]);
		}
		// Only load Link Type filter if it has values
		if (SermondistributorHelper::checkArray($this->link_typeOptions))
		{
			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Link Type Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_LINK_TYPE_LABEL').' -',
					'batch[link_type]',
					JHtml::_('select.options', $this->link_typeOptions, 'value', 'text')
				);
			}
		}

		// Set Source Selection
		$this->sourceOptions = $this->getTheSourceSelections();
		// We do some sanitation for Source filter
		if (SermondistributorHelper::checkArray($this->sourceOptions) &&
			isset($this->sourceOptions[0]->value) &&
			!SermondistributorHelper::checkString($this->sourceOptions[0]->value))
		{
			unset($this->sourceOptions[0]);
		}
		// Only load Source filter if it has values
		if (SermondistributorHelper::checkArray($this->sourceOptions))
		{
			if ($this->canBatch && $this->canCreate && $this->canEdit)
			{
				// Source Batch Selection
				JHtmlBatch_::addListSelection(
					'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_SOURCE_LABEL').' -',
					'batch[source]',
					JHtml::_('select.options', $this->sourceOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_SERMONDISTRIBUTOR_SERMONS'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_sermondistributor/assets/css/sermons.css", (SermondistributorHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'ordering' => JText::_('JGRID_HEADING_ORDERING'),
			'a.published' => JText::_('JSTATUS'),
			'a.name' => JText::_('COM_SERMONDISTRIBUTOR_SERMON_NAME_LABEL'),
			'g.name' => JText::_('COM_SERMONDISTRIBUTOR_SERMON_PREACHER_LABEL'),
			'h.name' => JText::_('COM_SERMONDISTRIBUTOR_SERMON_SERIES_LABEL'),
			'a.short_description' => JText::_('COM_SERMONDISTRIBUTOR_SERMON_SHORT_DESCRIPTION_LABEL'),
			'category_title' => JText::_('COM_SERMONDISTRIBUTOR_SERMON_SERMONS_CATEGORIES'),
			'a.link_type' => JText::_('COM_SERMONDISTRIBUTOR_SERMON_LINK_TYPE_LABEL'),
			'a.source' => JText::_('COM_SERMONDISTRIBUTOR_SERMON_SOURCE_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}

	protected function getTheLink_typeSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('link_type'));
		$query->from($db->quoteName('#__sermondistributor_sermon'));
		$query->order($db->quoteName('link_type') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $link_type)
			{
				// Translate the link_type selection
				$text = $model->selectionTranslation($link_type,'link_type');
				// Now add the link_type and its text to the options array
				$_filter[] = JHtml::_('select.option', $link_type, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}

	protected function getTheSourceSelections()
	{
		// Get a db connection.
		$db = JFactory::getDbo();

		// Create a new query object.
		$query = $db->getQuery(true);

		// Select the text.
		$query->select($db->quoteName('source'));
		$query->from($db->quoteName('#__sermondistributor_sermon'));
		$query->order($db->quoteName('source') . ' ASC');

		// Reset the query using our newly populated query object.
		$db->setQuery($query);

		$results = $db->loadColumn();

		if ($results)
		{
			// get model
			$model = $this->getModel();
			$results = array_unique($results);
			$_filter = array();
			foreach ($results as $source)
			{
				// Translate the source selection
				$text = $model->selectionTranslation($source,'source');
				// Now add the source and its text to the options array
				$_filter[] = JHtml::_('select.option', $source, JText::_($text));
			}
			return $_filter;
		}
		return false;
	}
}
