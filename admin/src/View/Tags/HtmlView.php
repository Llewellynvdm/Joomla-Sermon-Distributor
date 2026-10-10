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

	@version		6.0.0
	@created		9th July, 2026
	@package		Sermon Distributor
	@subpackage		HtmlView.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\View\Tags;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Document\Document;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Sermondistributor\Utilities\Permitted\Actions;
use JoomSermon\Joomla\Utilities\StringHelper;
use Joomla\CMS\Toolbar\Button\DropdownButton;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor Html View class for the Tags
 *
 * @since  1.6
 */
#[\AllowDynamicProperties]
class HtmlView extends BaseHtmlView
{
	/**
	 * The items from the model
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $items;

	/**
	 * The state object
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $state;

	/**
	 * The styles url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $styles;

	/**
	 * The scripts url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $scripts;

	/**
	 * The actions object
	 *
	 * @var    object
	 * @since  3.10.11
	 */
	public object $canDo;

	/**
	 * The return here base64 url
	 *
	 * @var    string
	 * @since  3.10.11
	 */
	public string $return_here;

	/**
	 * The title key used in modal
	 *
	 * @var    string
	 * @since  5.2.1
	 */
	public string $modalTitleKey;

	/**
	 * The modal state
	 *
	 * @var    bool
	 * @since  5.2.1
	 */
	public bool $isModal;

	/**
	 * The empty state
	 *
	 * @var    bool
	 * @since  5.2.1
	 */
	protected bool $isEmptyState;

	/**
	 * The user object.
	 *
	 * @var    User
	 * @since  3.10.11
	 */
	public User $user;

	/**
	 * The Can Edit permission
	 *
	 * @var    ?bool
	 * @since  5.2.1
	 */
	public ?bool $canEdit = null;

	/**
	 * The Can Edit State permission
	 *
	 * @var    ?bool
	 * @since  5.2.1
	 */
	public ?bool $canState = null;

	/**
	 * The Can Create permission
	 *
	 * @var    ?bool
	 * @since  5.2.1
	 */
	public ?bool $canCreate = null;

	/**
	 * The Can Delete permission
	 *
	 * @var    ?bool
	 * @since  5.2.1
	 */
	public ?bool $canDelete = null;

	/**
	 * The Can Batch permission
	 *
	 * @var    ?bool
	 * @since  5.2.1
	 */
	public ?bool $canBatch = null;

	/**
	 * Tags view display method
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since  1.6
	 */
	public function display($tpl = null): void
	{
		// Load module values
		$model = $this->getModel();
		$this->items = $model->getItems();
		$this->pagination = $model->getPagination();
		$this->state = $model->getState();
		$this->isEmptyState = $model->getIsEmptyState();
		$this->styles = $model->getStyles();
		$this->scripts = $model->getScripts();
		$this->user ??= $this->getCurrentUser();
		// Load the filter form from xml for searchtools.
		$this->filterForm = $model->getFilterForm();
		// Load the active filters for searchtools.
		$this->activeFilters = $model->getActiveFilters();
		// Add the list ordering clause.
		$this->listOrder = $this->sanitize($this->state->get('list.ordering', 'a.id'));
		$this->listDirn = $this->sanitize($this->state->get('list.direction', 'DESC'));
		$this->saveOrder = $this->listOrder == 'a.ordering';
		// set the return here value
		$this->return_here = urlencode(base64_encode((string) Uri::getInstance()));
		// get the permitted actions the current user can do
		$this->canDo = Actions::get('tag');
		$this->canEdit = $this->canDo->get('tag.edit');
		$this->canState = $this->canDo->get('tag.edit.state');
		$this->canCreate = $this->canDo->get('tag.create');
		$this->canDelete = $this->canDo->get('tag.delete');
		$this->canBatch = ($this->canDo->get('tag.batch') && $this->canDo->get('core.batch'));

		// If we don't have items we load the empty state
		if (is_array($this->items) && !count((array) $this->items) && $this->isEmptyState)
		{
			$this->setLayout('emptystate');
		}

		// We don't need toolbar in the modal window.
		$this->isModal = true;
		if ($this->getLayout() !== 'modal')
		{
			$this->isModal = false;
			$this->addToolbar();
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);
		}

		// Set the html view document stuff
		$this->_prepareDocument();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 * @throws  \Exception
	 * @since   1.6
	 */
	protected function addToolbar(): void
	{
		ToolbarHelper::title(Text::_('COM_SERMONDISTRIBUTOR_TAGS'), 'tag');
		/** @var  Toolbar $toolbar */
		$toolbar = $this->getDocument()->getToolbar();
		if ($this->canCreate)
		{
			$toolbar->addNew('tag.add');
		}

		// Only load if there are items
		if (!$this->isEmptyState)
		{
			/** @var  DropdownButton $dropdown */
			$dropdown = $toolbar->dropdownButton('status-group')
				->text('JTOOLBAR_CHANGE_STATUS')
				->toggleSplit(false)
				->icon('icon-ellipsis-h')
				->buttonClass('btn btn-action')
				->listCheck(true);

			$childBar = $dropdown->getChildToolbar();

			if ($this->canEdit)
			{
				$childBar->edit('tag.edit')->listCheck(true);
			}

			if ($this->canState)
			{
				$childBar->publish('tags.publish')->listCheck(true);
				$childBar->unpublish('tags.unpublish')->listCheck(true);
				$childBar->archive('tags.archive')->listCheck(true);

				if ($this->canDo->get('core.admin'))
				{
					$childBar->checkin('tags.checkin')->listCheck(true);
				}

				if ($this->state->get('filter.published') == -2 && $this->canDelete)
				{
					$toolbar->delete('tags.delete', 'JTOOLBAR_DELETE_FROM_TRASH')
						->message('JGLOBAL_CONFIRM_DELETE')
						->listCheck(true);
				}
				elseif ($this->canDelete)
				{
					$childBar->trash('tags.trash')->listCheck(true);
				}
			}
		}

		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('tags');
		if (StringHelper::check($this->help_url))
		{
			$toolbar->help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			$toolbar->preferences('com_sermondistributor');
		}
	}

	/**
	 * Prepare some document related stuff.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function _prepareDocument(): void
	{
		// Load jQuery
		Html::_('jquery.framework');
		// add styles
		foreach ($this->styles as $style)
		{
			Html::_('stylesheet', $style, ['version' => 'auto']);
		}
		// add scripts
		foreach ($this->scripts as $script)
		{
			Html::_('script', $script, ['version' => 'auto']);
		}
	}



	/**
	 * Sanitises a value to plain text for output in a view script.
	 *
	 * @param   mixed  $var     The output to escape.
	 * @param   bool   $shorten The switch to shorten.
	 * @param   int    $length  The shorting length.
	 *
	 * @return  mixed  The value as plain text.
	 * @since   1.6
	 */
	public function sanitize($var, bool $shorten = true, int $length = 50)
	{
		if (!is_string($var))
		{
			return $var;
		}

		return StringHelper::sanitize($var, $this->_charset ?? 'UTF-8', $shorten, $length);
	}

	/**
	 * Get the modal data/title key
	 *
	 * @return  string  The key value.
	 * @since   5.2.1
	 */
	public function getModalTitleKey(): string
	{
		return $this->modalTitleKey ?? 'id';
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array   containing the field name to sort by as the key and display text as value
	 * @since   1.6
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => Text::_('JGRID_HEADING_ORDERING'),
			'a.published' => Text::_('JSTATUS'),
			'a.name' => Text::_('COM_SERMONDISTRIBUTOR_TAG_NAME_LABEL'),
			'g.name' => Text::_('COM_SERMONDISTRIBUTOR_TAG_PARENT_GUID_LABEL'),
			'a.id' => Text::_('JGRID_HEADING_ID')
		);
	}
}
