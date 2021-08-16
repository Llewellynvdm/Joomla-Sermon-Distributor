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
		// Load the filter form from xml.
		$this->filterForm = $this->get('FilterForm');
		// Load the active filters.
		$this->activeFilters = $this->get('ActiveFilters');
		// Add the list ordering clause.
		$this->listOrder = $this->escape($this->state->get('list.ordering', 'a.id'));
		$this->listDirn = $this->escape($this->state->get('list.direction', 'DESC'));
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

		// Only load published batch if state and batch is allowed
		if ($this->canState && $this->canBatch)
		{
			JHtmlBatch_::addListSelection(
				JText::_('COM_SERMONDISTRIBUTOR_KEEP_ORIGINAL_STATE'),
				'batch[published]',
				JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('all' => false)), 'value', 'text', '', true)
			);
		}

		// Only load access batch if create, edit and batch is allowed
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

		// Only load Preacher Name batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Preacher Name Selection
			$this->preacherNameOptions = JFormHelper::loadFieldType('Preachers')->options;
			// We do some sanitation for Preacher Name filter
			if (SermondistributorHelper::checkArray($this->preacherNameOptions) &&
				isset($this->preacherNameOptions[0]->value) &&
				!SermondistributorHelper::checkString($this->preacherNameOptions[0]->value))
			{
				unset($this->preacherNameOptions[0]);
			}
			// Preacher Name Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_PREACHER_LABEL').' -',
				'batch[preacher]',
				JHtml::_('select.options', $this->preacherNameOptions, 'value', 'text')
			);
		}

		// Only load Series Name batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Series Name Selection
			$this->seriesNameOptions = JFormHelper::loadFieldType('Series')->options;
			// We do some sanitation for Series Name filter
			if (SermondistributorHelper::checkArray($this->seriesNameOptions) &&
				isset($this->seriesNameOptions[0]->value) &&
				!SermondistributorHelper::checkString($this->seriesNameOptions[0]->value))
			{
				unset($this->seriesNameOptions[0]);
			}
			// Series Name Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_SERIES_LABEL').' -',
				'batch[series]',
				JHtml::_('select.options', $this->seriesNameOptions, 'value', 'text')
			);
		}

		// Only load Link Type batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Link Type Selection
			$this->link_typeOptions = JFormHelper::loadFieldType('sermonsfilterlinktype')->options;
			// We do some sanitation for Link Type filter
			if (SermondistributorHelper::checkArray($this->link_typeOptions) &&
				isset($this->link_typeOptions[0]->value) &&
				!SermondistributorHelper::checkString($this->link_typeOptions[0]->value))
			{
				unset($this->link_typeOptions[0]);
			}
			// Link Type Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_LINK_TYPE_LABEL').' -',
				'batch[link_type]',
				JHtml::_('select.options', $this->link_typeOptions, 'value', 'text')
			);
		}

		// Only load Source batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Source Selection
			$this->sourceOptions = JFormHelper::loadFieldType('sermonsfiltersource')->options;
			// We do some sanitation for Source filter
			if (SermondistributorHelper::checkArray($this->sourceOptions) &&
				isset($this->sourceOptions[0]->value) &&
				!SermondistributorHelper::checkString($this->sourceOptions[0]->value))
			{
				unset($this->sourceOptions[0]);
			}
			// Source Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_SERMON_SOURCE_LABEL').' -',
				'batch[source]',
				JHtml::_('select.options', $this->sourceOptions, 'value', 'text')
			);
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
			'a.ordering' => JText::_('JGRID_HEADING_ORDERING'),
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
}
