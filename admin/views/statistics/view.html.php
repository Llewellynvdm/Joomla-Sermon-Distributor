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
 * Sermondistributor View class for the Statistics
 */
class SermondistributorViewStatistics extends JViewLegacy
{
	/**
	 * Statistics view display method
	 * @return void
	 */
	function display($tpl = null)
	{
		if ($this->getLayout() !== 'modal')
		{
			// Include helper submenu
			SermondistributorHelper::addSubmenu('statistics');
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
		$this->canDo = SermondistributorHelper::getActions('statistic');
		$this->canEdit = $this->canDo->get('statistic.edit');
		$this->canState = $this->canDo->get('statistic.edit.state');
		$this->canCreate = $this->canDo->get('statistic.create');
		$this->canDelete = $this->canDo->get('statistic.delete');
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
		JToolBarHelper::title(JText::_('COM_SERMONDISTRIBUTOR_STATISTICS'), 'bars');
		JHtmlSidebar::setAction('index.php?option=com_sermondistributor&view=statistics');
		JFormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			JToolBarHelper::addNew('statistic.add');
		}

		// Only load if there are items
		if (SermondistributorHelper::checkArray($this->items))
		{
			if ($this->canEdit)
			{
				JToolBarHelper::editList('statistic.edit');
			}

			if ($this->canState)
			{
				JToolBarHelper::publishList('statistics.publish');
				JToolBarHelper::unpublishList('statistics.unpublish');
				JToolBarHelper::archiveList('statistics.archive');

				if ($this->canDo->get('core.admin'))
				{
					JToolBarHelper::checkin('statistics.checkin');
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
				JToolbarHelper::deleteList('', 'statistics.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				JToolbarHelper::trash('statistics.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('statistic.export'))
			{
				JToolBarHelper::custom('statistics.exportData', 'download', '', 'COM_SERMONDISTRIBUTOR_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('statistic.import'))
		{
			JToolBarHelper::custom('statistics.importData', 'upload', '', 'COM_SERMONDISTRIBUTOR_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$help_url = SermondistributorHelper::getHelpUrl('statistics');
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

		// Only load Sermon Name batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Sermon Name Selection
			$this->sermonNameOptions = JFormHelper::loadFieldType('Sermon')->options;
			// We do some sanitation for Sermon Name filter
			if (SermondistributorHelper::checkArray($this->sermonNameOptions) &&
				isset($this->sermonNameOptions[0]->value) &&
				!SermondistributorHelper::checkString($this->sermonNameOptions[0]->value))
			{
				unset($this->sermonNameOptions[0]);
			}
			// Sermon Name Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_SERMON_LABEL').' -',
				'batch[sermon]',
				JHtml::_('select.options', $this->sermonNameOptions, 'value', 'text')
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
				'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_PREACHER_LABEL').' -',
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
				'- Keep Original '.JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_SERIES_LABEL').' -',
				'batch[series]',
				JHtml::_('select.options', $this->seriesNameOptions, 'value', 'text')
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
		$this->document->setTitle(JText::_('COM_SERMONDISTRIBUTOR_STATISTICS'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_sermondistributor/assets/css/statistics.css", (SermondistributorHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
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
			'a.filename' => JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_FILENAME_LABEL'),
			'g.name' => JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_SERMON_LABEL'),
			'h.name' => JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_PREACHER_LABEL'),
			'i.name' => JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_SERIES_LABEL'),
			'a.counter' => JText::_('COM_SERMONDISTRIBUTOR_STATISTIC_COUNTER_LABEL'),
			'a.id' => JText::_('JGRID_HEADING_ID')
		);
	}
}
