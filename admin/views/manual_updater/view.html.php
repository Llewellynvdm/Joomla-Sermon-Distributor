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
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Filesystem\File;
use VDM\Joomla\Utilities\ArrayHelper;
use VDM\Joomla\Utilities\StringHelper;

/**
 * Sermondistributor Html View class for the Manual_updater
 */
class SermondistributorViewManual_updater extends HtmlView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get component params
		$this->params = ComponentHelper::getParams('com_sermondistributor');
		// get the application
		$this->app = Factory::getApplication();
		// get the user object
		$this->user    = Factory::getUser();
		// get global action permissions
		$this->canDo = SermondistributorHelper::getActions('manual_updater');
		// Initialise variables.
		$this->items = $this->get('Items');

		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			// add the tool bar
			$this->addToolBar();
		}

		// set the document
		$this->setDocument();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode(PHP_EOL, $errors), 500);
		}

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function setDocument()
	{

		// Only load jQuery if needed. (default is true)
		if ($this->params->get('add_jquery_framework', 1) == 1)
		{
			Html::_('jquery.framework');
		}
		// Load the header checker class.
		require_once( JPATH_COMPONENT_ADMINISTRATOR.'/helpers/headercheck.php' );
		// Initialize the header checker.
		$HeaderCheck = new sermondistributorHeaderCheck();

		// Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// Set script size.
		$size = $this->params->get('uikit_min');

		// Load uikit version.
		$this->uikitVersion = $this->params->get('uikit_version', 2);

		// Use Uikit Version 2
		if (2 == $this->uikitVersion)
		{
			// Set css style.
			$style = $this->params->get('uikit_style');

			// The uikit css.
			if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
			{
				Html::_('stylesheet', 'media/com_sermondistributor/uikit-v2/css/uikit'.$style.$size.'.css', ['version' => 'auto']);
			}
			// The uikit js.
			if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
			{
				Html::_('script', 'media/com_sermondistributor/uikit-v2/js/uikit'.$size.'.js', ['version' => 'auto']);
			}

			// Load the script to find all uikit components needed.
			if ($uikit != 2)
			{
				// Set the default uikit components in this view.
				$uikitComp = [];
				$uikitComp[] = 'uk-form';
			}

			// Load the needed uikit components in this view.
			if ($uikit != 2 && isset($uikitComp) && ArrayHelper::check($uikitComp))
			{
				// loading...
				foreach ($uikitComp as $class)
				{
					foreach (SermondistributorHelper::$uk_components[$class] as $name)
					{
						// check if the CSS file exists.
						if (File::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit-v2/css/components/'.$name.$style.$size.'.css'))
						{
							// load the css.
							Html::_('stylesheet', 'media/com_sermondistributor/uikit-v2/css/components/'.$name.$style.$size.'.css', ['version' => 'auto']);
						}
						// check if the JavaScript file exists.
						if (File::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit-v2/js/components/'.$name.$size.'.js'))
						{
							// load the js.
							Html::_('script', 'media/com_sermondistributor/uikit-v2/js/components/'.$name.$size.'.js', ['version' => 'auto'], ['type' => 'text/javascript', 'async' => 'async']);
						}
					}
				}
			}
		}
		// Use Uikit Version 3
		elseif (3 == $this->uikitVersion)
		{
			// The uikit css.
			if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
			{
				Html::_('stylesheet', 'media/com_sermondistributor/uikit-v3/css/uikit'.$size.'.css', ['version' => 'auto']);
			}
			// The uikit js.
			if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
			{
				Html::_('script', 'media/com_sermondistributor/uikit-v3/js/uikit'.$size.'.js', ['version' => 'auto']);
				Html::_('script', 'media/com_sermondistributor/uikit-v3/js/uikit-icons'.$size.'.js', ['version' => 'auto']);
			}
		}
		// add the document default css file
		Html::_('stylesheet', 'administrator/components/com_sermondistributor/assets/css/manual_updater.css', ['version' => 'auto']);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// hide the main menu
		$this->app->input->set('hidemainmenu', true);
		// add title to the page
		ToolbarHelper::title(Text::_('COM_SERMONDISTRIBUTOR_MANUAL_UPDATER'),'cogs');
		// add cpanel button
		ToolbarHelper::custom('manual_updater.dashboard', 'grid-2', '', 'COM_SERMONDISTRIBUTOR_DASH', false);
		if ($this->canDo->get('manual_updater.external_sources'))
		{
			// add External Sources button.
			ToolbarHelper::custom('manual_updater.gotoExternalSources', 'puzzle custom-button-gotoexternalsources', '', 'COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCES', false);
		}

		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('manual_updater');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
		}

		// add the options comp button
		if ($this->canDo->get('core.admin') || $this->canDo->get('core.options'))
		{
			ToolbarHelper::preferences('com_sermondistributor');
		}
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
		// use the helper htmlEscape method instead.
		return StringHelper::html($var, $this->_charset);
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
