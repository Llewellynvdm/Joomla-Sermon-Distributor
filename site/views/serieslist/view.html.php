<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		view.html.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  ____  _____  _____  __  __  __      __       ___  _____  __  __  ____  _____  _  _  ____  _  _  ____ 
 (_  _)(  _  )(  _  )(  \/  )(  )    /__\     / __)(  _  )(  \/  )(  _ \(  _  )( \( )( ___)( \( )(_  _)
.-_)(   )(_)(  )(_)(  )    (  )(__  /(__)\   ( (__  )(_)(  )    (  )___/ )(_)(  )  (  )__)  )  (   )(  
\____) (_____)(_____)(_/\/\_)(____)(__)(__)   \___)(_____)(_/\/\_)(__)  (_____)(_)\_)(____)(_)\_) (__) 

/------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import Joomla view library
jimport('joomla.application.component.view');

/**
 * Sermondistributor View class for the Serieslist
 */
class SermondistributorViewSerieslist extends JViewLegacy
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
		// [3035] Initialise variables.
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');

		// [3053] Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseWarning(500, implode("\n", $errors));
			return false;
		}
		// do a quick build of all the links
		if ($this->items)
		{
			foreach ($this->items as $nr => &$item)
			{
				$item->link = JRoute::_(SermondistributorHelperRoute::getSeriesRoute($item->slug));
			}
		}
		// set the FooTable style
		$style = $this->params->get('list_series_table_color', 0);
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

		// [3070] Set the toolbar
		$this->addToolBar();

		// [3072] set the document
		$this->_prepareDocument();

		parent::display($tpl);
	}

        /**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// [3422] always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// [3424] Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// [3426] Initialize the header checker.
		$HeaderCheck = new HeaderCheck;

		// [3431] Load uikit options.
		$uikit = $this->params->get('uikit_load');
		// [3433] Set script size.
		$size = $this->params->get('uikit_min');
		// [3435] Set css style.
		$style = $this->params->get('uikit_style');

		// [3438] The uikit css.
		if ((!$HeaderCheck->css_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addStyleSheet(JURI::root(true) .'/media/com_sermondistributor/uikit/css/uikit'.$style.$size.'.css');
		}
		// [3443] The uikit js.
		if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
		{
			$this->document->addScript(JURI::root(true) .'/media/com_sermondistributor/uikit/js/uikit'.$size.'.js');
		}

		// [3452] Load the script to find all uikit components needed.
		if ($uikit != 2)
		{
			// [3455] Set the default uikit components in this view.
			$uikitComp = array();
			$uikitComp[] = 'data-uk-tooltip';
			$uikitComp[] = 'data-uk-grid';

			// [3464] Get field uikit components needed in this view.
			$uikitFieldComp = $this->get('UikitComp');
			if (isset($uikitFieldComp) && SermondistributorHelper::checkArray($uikitFieldComp))
			{
				if (isset($uikitComp) && SermondistributorHelper::checkArray($uikitComp))
				{
					$uikitComp = array_merge($uikitComp, $uikitFieldComp);
					$uikitComp = array_unique($uikitComp);
				}
				else
				{
					$uikitComp = $uikitFieldComp;
				}
			}
		}

		// [3480] Load the needed uikit components in this view.
		if ($uikit != 2 && isset($uikitComp) && SermondistributorHelper::checkArray($uikitComp))
		{
			// [3483] load just in case.
			jimport('joomla.filesystem.file');
			// [3485] loading...
			foreach ($uikitComp as $class)
			{
				foreach (SermondistributorHelper::$uk_components[$class] as $name)
				{
					// [3490] check if the CSS file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit/css/components/'.$name.$style.$size.'.css'))
					{
						// [3493] load the css.
						$this->document->addStyleSheet(JURI::root(true) .'/media/com_sermondistributor/uikit/css/components/'.$name.$style.$size.'.css');
					}
					// [3496] check if the JavaScript file exists.
					if (JFile::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit/js/components/'.$name.$size.'.js'))
					{
						// [3499] load the js.
						$this->document->addScript(JURI::root(true) .'/media/com_sermondistributor/uikit/js/components/'.$name.$size.'.js');
					}
				}
			}
		}  

		// [6514] Add the CSS for Footable.
		$this->document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.core.min.css');

		// [6516] Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.metro.min.css');
		}
		// [6521] Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			$this->document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.standalone.min.css');
		}

		// [6526] Add the JavaScript for Footable
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.js');
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.sort.js');
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.filter.js');
		$this->document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.paginate.js'); 
		// [3380] load the meta description
		if ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		// [3385] load the key words if set
		if ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		// [3390] check the robot params
		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		} 
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_sermondistributor/assets/css/serieslist.css'); 
        }

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		// adding the joomla toolbar to the front
		JLoader::register('JToolbarHelper', JPATH_ADMINISTRATOR.'/includes/toolbar.php');
		
		// set help url for this view if found
		$help_url = SermondistributorHelper::getHelpUrl('serieslist');
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
