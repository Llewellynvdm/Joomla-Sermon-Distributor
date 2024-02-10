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
 * Sermondistributor Html View class for the External_sources
 */
class SermondistributorViewExternal_sources extends HtmlView
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
		$this->canDo = SermondistributorHelper::getActions('external_source');
		$this->canEdit = $this->canDo->get('external_source.edit');
		$this->canState = $this->canDo->get('external_source.edit.state');
		$this->canCreate = $this->canDo->get('external_source.create');
		$this->canDelete = $this->canDo->get('external_source.delete');
		$this->canBatch = ($this->canDo->get('external_source.batch') && $this->canDo->get('core.batch'));

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
		JHtmlSidebar::setAction('index.php?option=com_sermondistributor&view=external_sources');
		ToolbarHelper::title(Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCES'), 'puzzle');
		FormHelper::addFieldPath(JPATH_COMPONENT . '/models/fields');

		if ($this->canCreate)
		{
			ToolbarHelper::addNew('external_source.add');
		}

		// Only load if there are items
		if (ArrayHelper::check($this->items))
		{
			if ($this->canEdit)
			{
				ToolbarHelper::editList('external_source.edit');
			}

			if ($this->canState)
			{
				ToolbarHelper::publishList('external_sources.publish');
				ToolbarHelper::unpublishList('external_sources.unpublish');
				ToolbarHelper::archiveList('external_sources.archive');

				if ($this->canDo->get('core.admin'))
				{
					ToolbarHelper::checkin('external_sources.checkin');
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
				ToolbarHelper::deleteList('', 'external_sources.delete', 'JTOOLBAR_EMPTY_TRASH');
			}
			elseif ($this->canState && $this->canDelete)
			{
				ToolbarHelper::trash('external_sources.trash');
			}

			if ($this->canDo->get('core.export') && $this->canDo->get('external_source.export'))
			{
				ToolbarHelper::custom('external_sources.exportData', 'download', '', 'COM_SERMONDISTRIBUTOR_EXPORT_DATA', true);
			}
		}

		if ($this->canDo->get('core.import') && $this->canDo->get('external_source.import'))
		{
			ToolbarHelper::custom('external_sources.importData', 'upload', '', 'COM_SERMONDISTRIBUTOR_IMPORT_DATA', false);
		}

		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('external_sources');
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

		// Only load Externalsources batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Externalsources Selection
			$this->externalsourcesOptions = FormHelper::loadFieldType('externalsourcesfilterexternalsources')->options;
			// We do some sanitation for Externalsources filter
			if (ArrayHelper::check($this->externalsourcesOptions) &&
				isset($this->externalsourcesOptions[0]->value) &&
				!StringHelper::check($this->externalsourcesOptions[0]->value))
			{
				unset($this->externalsourcesOptions[0]);
			}
			// Externalsources Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_EXTERNALSOURCES_LABEL').' -',
				'batch[externalsources]',
				Html::_('select.options', $this->externalsourcesOptions, 'value', 'text')
			);
		}

		// Only load Update Method batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Update Method Selection
			$this->update_methodOptions = FormHelper::loadFieldType('externalsourcesfilterupdatemethod')->options;
			// We do some sanitation for Update Method filter
			if (ArrayHelper::check($this->update_methodOptions) &&
				isset($this->update_methodOptions[0]->value) &&
				!StringHelper::check($this->update_methodOptions[0]->value))
			{
				unset($this->update_methodOptions[0]);
			}
			// Update Method Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_UPDATE_METHOD_LABEL').' -',
				'batch[update_method]',
				Html::_('select.options', $this->update_methodOptions, 'value', 'text')
			);
		}

		// Only load Build batch if create, edit, and batch is allowed
		if ($this->canBatch && $this->canCreate && $this->canEdit)
		{
			// Set Build Selection
			$this->buildOptions = FormHelper::loadFieldType('externalsourcesfilterbuild')->options;
			// We do some sanitation for Build filter
			if (ArrayHelper::check($this->buildOptions) &&
				isset($this->buildOptions[0]->value) &&
				!StringHelper::check($this->buildOptions[0]->value))
			{
				unset($this->buildOptions[0]);
			}
			// Build Batch Selection
			JHtmlBatch_::addListSelection(
				'- Keep Original '.Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_BUILD_LABEL').' -',
				'batch[build]',
				Html::_('select.options', $this->buildOptions, 'value', 'text')
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
		$this->document->setTitle(Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCES'));
		Html::_('stylesheet', "administrator/components/com_sermondistributor/assets/css/external_sources.css", ['version' => 'auto']);
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
			'a.description' => Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_DESCRIPTION_LABEL'),
			'a.externalsources' => Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_EXTERNALSOURCES_LABEL'),
			'a.update_method' => Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_UPDATE_METHOD_LABEL'),
			'a.build' => Text::_('COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_BUILD_LABEL'),
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
