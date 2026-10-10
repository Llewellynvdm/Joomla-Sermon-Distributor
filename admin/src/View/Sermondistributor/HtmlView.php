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
namespace JoomSermon\Component\Sermondistributor\Administrator\View\Sermondistributor;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Document\Document;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Utilities\StringHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor View class
 *
 * @since  1.6
 */
#[\AllowDynamicProperties]
class HtmlView extends BaseHtmlView
{
	/**
	 * @var array<string> List of icon identifiers to render in the dashboard view.
	 * @since 1.6
	 */
	public array $icons = [];

	/**
	 * @var array<string> List of CSS file URLs to be added to the page.
	 * @since 4.3
	 */
	public array $styles = [];

	/**
	 * @var array<string> List of JavaScript file URLs to be included on the page.
	 * @since 4.3
	 */
	public array $scripts = [];

	/**
	 * @var array<int, object> List of contributor objects fetched via the helper.
	 * @since 1.6
	 */
	public array $contributors = [];

	/**
	 * @var object|null The manifest metadata of the component as returned by `ComponentbuilderHelper::manifest()`.
	 * @since 1.6
	 */
	public $manifest = null;

	/**
	 * @var string|null Markdown content of the component's wiki page.
	 * @since 1.6
	 */
	public ?string $wiki = null;

	/**
	 * @var string|null The rendered or raw README markdown of the component.
	 * @since 1.6
	 */
	public ?string $readme = null;

	/**
	 * @var string|null The current version of the component.
	 * @since 1.6
	 */
	public ?string $version = null;

	/**
	 * @var string|null Help URL for the component dashboard view, if available.
	 * @since 1.6
	 */
	public ?string $help_url = null;

	/**
	 * View display method
	 *
	 * @return void
	 * @throws \Exception
	 * @since   1.6
	 */
	function display($tpl = null): void
	{
		// Assign data to the view
		$this->icons          = $this->get('Icons');
		$this->styles         = $this->get('Styles');
		$this->scripts        = $this->get('Scripts');
		$this->contributors   = SermondistributorHelper::getContributors();

		// get the manifest details of the component
		$this->manifest = SermondistributorHelper::manifest();

		// Set the toolbar
		$this->addToolBar();

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
	 * @since   1.6
	 */
	protected function addToolbar(): void
	{
		$canDo = SermondistributorHelper::getActions('sermondistributor');
		ToolbarHelper::title(Text::_('COM_SERMONDISTRIBUTOR_DASHBOARD'), 'grid-2');

		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('sermondistributor');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
		}

		if ($canDo->get('core.admin') || $canDo->get('core.options'))
		{
			ToolbarHelper::preferences('com_sermondistributor');
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
		// set page title
		$this->getDocument()->setTitle(Text::_('COM_SERMONDISTRIBUTOR_DASHBOARD'));
		/** \Joomla\CMS\WebAsset\WebAssetManager $wa */
		$wa = $this->getDocument()->getWebAssetManager();
		// Register the inline script with properly encoded JSON
		$wa->addInlineScript(
			'var manifest = ' . json_encode($this->manifest, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ';'
		);
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
}
