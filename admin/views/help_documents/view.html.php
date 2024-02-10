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
	@subpackage		view.html.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use VDM\Joomla\Utilities\ArrayHelper;
use VDM\Joomla\Utilities\StringHelper;

/**
 * Sermondistributor Html View class for the Help_documents
 */
class SermondistributorViewHelp_documents extends HtmlView
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

		// Assign data to the view
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->user = Factory::getUser();
		// Load the filter form from xml.
		$this->filterForm = $this->get('FilterForm');
		// Load the active filters.
		$this->activeFilters = $this->get('ActiveFilters');
		// Add the list ordering clause.
		$this->listOrder = $this->escape($this->state->get('list.ordering', 'a.id'));
		$this->listDirn = $this->escape($this->state->get('list.direction', 'DESC'));
		$this->saveOrder = $this->listOrder == 'a.ordering';
		// set the return here value
		$this->return_here = urlencode(base64_encode((string) Uri::getInstance()));
		// get global action permissions
		$this->canDo = SermondistributorHelper::getActions('help_document');
		$this->canEdit = $this->canDo->get('help_document.edit');
		$this->canState = $this->canDo->get('help_document.edit.state');
		$this->canCreate = $this->canDo->get('help_document.create');
		$this->canDelete = $this->canDo->get('help_document.delete');
		$this->canBatch = ($this->canDo->get('help_document.batch') && $this->canDo->get('core.batch'));

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
		JHtmlSidebar::setAction('index.php?option=com_sermondistributor&view=help_documents');
		ToolbarHelper::title(Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENTS'), 'support');
		FormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			ToolbarHelper::addNew('help_document.add');
		}

		// Only load if there are items
		if (ArrayHelper::check($this->items))
		{
			if ($this->canEdit)
			{
				ToolbarHelper::editList('help_document.edit');
			}

			if ($this->canState)
			{
				ToolbarHelper::publishList('help_documents.publish');
				ToolbarHelper::unpublishList('help_documents.unpublish');
				ToolbarHelper::archiveList('help_documents.archive');

				if ($this->canDo->get('core.admin'))
				{
					ToolbarHelper::checkin('help_documents.checkin');
				}
			}

			// Add a batch button
			if ($this->canBatch && $this->canCreate && $this->canEdit && $this->canState)
			{
				// Get the toolbar object instance
				$bar = Toolbar::getInstance('toolbar');
				// set the batch button name
				$title = Text::_('JTOOLBAR_BATCH');
				// Instantiate a new JLayoutFile instance and render the batch button
				$layout = new FileLayout('joomla.toolbar.batch');
				// add the button to the page
				$dhtml = $layout->render(array('title' => $title));
				$bar->appendButton('Custom', $dhtml, 'batch');
			}

			if ($this->state->get('filter.published') == -2 && ($this->canState && $this->canDelete))
			{
				ToolbarHelper::deleteList('', 'help_documents.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				ToolbarHelper::trash('help_documents.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('help_document.export'))
			{
				ToolbarHelper::custom('help_documents.exportData', 'download', '', 'COM_SERMONDISTRIBUTOR_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('help_document.import'))
		{
			ToolbarHelper::custom('help_documents.importData', 'upload', '', 'COM_SERMONDISTRIBUTOR_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('help_documents');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			ToolbarHelper::preferences('com_sermondistributor');
		}

		// Only load published batch if state and batch is allowed
		if ($this->canState && $this->canBatch)
		{
			JHtmlBatch_::addListSelection(
				Text::_('COM_SERMONDISTRIBUTOR_KEEP_ORIGINAL_STATE'),
				'batch[published]',
				Html::_('select.options', Html::_('jgrid.publishedOptions', array('all' => false)), 'value', 'text', '', true)
			);
		}

		// Only load access batch if create, edit and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			JHtmlBatch_::addListSelection(
				Text::_('COM_SERMONDISTRIBUTOR_KEEP_ORIGINAL_ACCESS'),
				'batch[access]',
				Html::_('select.options', Html::_('access.assetgroups'), 'value', 'text')
			);
		}

		// Only load Type batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Type Selection
			$this->typeOptions = FormHelper::loadFieldType('helpdocumentsfiltertype')->options;
			// We do some sanitation for Type filter
			if (ArrayHelper::check($this->typeOptions) &&
				isset($this->typeOptions[0]->value) &&
				!StringHelper::check($this->typeOptions[0]->value))
			{
				unset($this->typeOptions[0]);
			}
			// Type Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TYPE_LABEL').' -',
				'batch[type]',
				Html::_('select.options', $this->typeOptions, 'value', 'text')
			);
		}

		// Only load Location batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Location Selection
			$this->locationOptions = FormHelper::loadFieldType('helpdocumentsfilterlocation')->options;
			// We do some sanitation for Location filter
			if (ArrayHelper::check($this->locationOptions) &&
				isset($this->locationOptions[0]->value) &&
				!StringHelper::check($this->locationOptions[0]->value))
			{
				unset($this->locationOptions[0]);
			}
			// Location Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_LOCATION_LABEL').' -',
				'batch[location]',
				Html::_('select.options', $this->locationOptions, 'value', 'text')
			);
		}

		// Only load Admin View batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Admin View Selection
			$this->admin_viewOptions = FormHelper::loadFieldType('Adminviewfolderlist')->options;
			// We do some sanitation for Admin View filter
			if (ArrayHelper::check($this->admin_viewOptions) &&
				isset($this->admin_viewOptions[0]->value) &&
				!StringHelper::check($this->admin_viewOptions[0]->value))
			{
				unset($this->admin_viewOptions[0]);
			}
			// Admin View Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_ADMIN_VIEW_LABEL').' -',
				'batch[admin_view]',
				Html::_('select.options', $this->admin_viewOptions, 'value', 'text')
			);
		}

		// Only load Site View batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Site View Selection
			$this->site_viewOptions = FormHelper::loadFieldType('Siteviewfolderlist')->options;
			// We do some sanitation for Site View filter
			if (ArrayHelper::check($this->site_viewOptions) &&
				isset($this->site_viewOptions[0]->value) &&
				!StringHelper::check($this->site_viewOptions[0]->value))
			{
				unset($this->site_viewOptions[0]);
			}
			// Site View Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SITE_VIEW_LABEL').' -',
				'batch[site_view]',
				Html::_('select.options', $this->site_viewOptions, 'value', 'text')
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
			$this->document = Factory::getDocument();
		}
		$this->document->setTitle(Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENTS'));
		Html::_('stylesheet', "administrator/components/com_sermondistributor/assets/css/help_documents.css", ['version' => 'auto']);
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
			return StringHelper::html($var, $this->_charset, true);
		}
		// use the helper htmlEscape method instead.
		return StringHelper::html($var, $this->_charset);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array   Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => Text::_('JGRID_HEADING_ORDERING'),
			'a.published' => Text::_('JSTATUS'),
			'a.title' => Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TITLE_LABEL'),
			'a.type' => Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_TYPE_LABEL'),
			'a.location' => Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_LOCATION_LABEL'),
			'h.' => Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_ADMIN_VIEW_LABEL'),
			'i.' => Text::_('COM_SERMONDISTRIBUTOR_HELP_DOCUMENT_SITE_VIEW_LABEL'),
			'a.id' => Text::_('JGRID_HEADING_ID')
		);
	}

	/**
	 * Get the Document (helper method toward Joomla 4 and 5)
	 */
	public function getDocument()
	{
		$this->document ??= JFactory::getDocument();

		return $this->document;
	}
}
