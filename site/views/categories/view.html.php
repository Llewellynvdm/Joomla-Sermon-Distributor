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

	@version		1.3.3
	@build			13th July, 2016
	@created		22nd October, 2015
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
 * Sermondistributor View class for the Categories
 */
class SermondistributorViewCategories extends JViewLegacy
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get combined params of both component and menu
		$this->app = JFactory::getApplication();
		$this->params = $this->app->getParams();
		$this->menu = $this->app->getMenu()->getActive();
		// get the user object
		$this->user = JFactory::getUser();
		// [Interpretation 2442] Initialise variables.
		$this->items	= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// [Interpretation 2471] Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
		// do a quick build of all the links
		if ($this->items)
		{
			foreach ($this->items as $nr => &$item)
			{
				$item->link = JRoute::_(SermondistributorHelperRoute::getCategoryRoute($item->slug));
			}
		}
		// set the FooTable style
		$style = $this->params->get('categories_table_color', 0);
		if (5 == $style)
		{
			$this->fooTableStyle = 1;
		}
		elseif ($style <= 4)
		{
			$this->fooTableStyle = 0;
		}
		else
		{
			$this->fooTableStyle = 2;
		}

		// [Interpretation 2488] Set the toolbar
		$this->addToolBar();

		// [Interpretation 2490] set the document
		$this->_prepareDocument();

		parent::display($tpl);
	}

        /**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// [Interpretation 2925] always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// [Interpretation 2927] Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// [Interpretation 2929] Initialize the header checker.
		$HeaderCheck = new HeaderCheck;

		// [Interpretation 2934] Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// [Interpretation 2936] Set script size.
		$size = $this->params->get('uikit_min');
		// [Interpretation 2938] Set css style.
		$style = $this->params->get('uikit_style');

		// [Interpretation 2941] The uikit css.
		if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addStyleSheet(JURI::root(true) .'/media/com_sermondistributor/uikit/css/uikit'.$style.$size.'.css');
		}
		// [Interpretation 2946] The uikit js.
		if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addScript(JURI::root(true) .'/media/com_sermondistributor/uikit/js/uikit'.$size.'.js');
		}

		// [Interpretation 2955] Load the script to find all uikit components needed.
		if ($uikit != 2)
		{
			// [Interpretation 2958] Set the default uikit components in this view.
			$uikitComp = array();
			$uikitComp[] = 'data-uk-tooltip';
			$uikitComp[] = 'data-uk-grid';
		}

		// [Interpretation 2983] Load the needed uikit components in this view.
		if ($uikit != 2 && isset($uikitComp) && SermondistributorHelper::checkArray($uikitComp))
		{
			// [Interpretation 2986] load just in case.
			jimport('joomla.filesystem.file');
			// [Interpretation 2988] loading...
			foreach ($uikitComp as $class)
			{
				foreach (SermondistributorHelper::$uk_components[$class] as $name)
				{
					// [Interpretation 2993] check if the CSS file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit/css/components/'.$name.$style.$size.'.css'))
					{
						// [Interpretation 2996] load the css.
						$this->document->addStyleSheet(JURI::root(true) .'/media/com_sermondistributor/uikit/css/components/'.$name.$style.$size.'.css');
					}
					// [Interpretation 2999] check if the JavaScript file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit/js/components/'.$name.$size.'.js'))
					{
						// [Interpretation 3002] load the js.
						$this->document->addScript(JURI::root(true) .'/media/com_sermondistributor/uikit/js/components/'.$name.$size.'.js');
					}
				}
			}
		}  

		// [Interpretation 6186] Add the CSS for Footable.
		$this->document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.core.min.css');

		// [Interpretation 6188] Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.metro.min.css');
		}
		// [Interpretation 6193] Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.standalone.min.css');
		}

		// [Interpretation 6198] Add the JavaScript for Footable
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.js');
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.sort.js');
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.filter.js');
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.paginate.js'); 
		// [Interpretation 2883] load the meta description
		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		// [Interpretation 2888] load the key words if set
		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		// [Interpretation 2893] check the robot params
		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		} 
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_sermondistributor/assets/css/categories.css'); 
        }

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// adding the joomla toolbar to the front
		JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');
		
		// set help url for this view if found
		$help_url = SermondistributorHelper::getHelpUrl('categories');
		if (SermondistributorHelper::checkString($help_url))
		{
			JToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $help_url);
		}
		// now initiate the toolbar
		$this->toolbar = JToolbar::getInstance();
	}

        /**
	 * Escapes a value for output in a view script.
	 *
	 * @param   mixed  $var  The output to escape.
	 *
	 * @return  mixed  The escaped value.
	 */
	public function escape($var, $sorten = false, $length = 40)
	{
                // use the helper htmlEscape method instead.
		return SermondistributorHelper::htmlEscape($var, $this->_charset, $sorten, $length);
	}
}
