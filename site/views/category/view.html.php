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
use Joomla\CMS\Toolbar\Toolbar;
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
 * Sermondistributor Html View class for the Category
 */
class SermondistributorViewCategory extends HtmlView
{
	// Overwriting JView display method
	function display($tpl = null)
	{
		// get combined params of both component and menu
		$this->app = Factory::getApplication();
		$this->params = $this->app->getParams();
		$this->menu = $this->app->getMenu()->getActive();
		// get the user object
		$this->user = Factory::getUser();
		// Initialise variables.
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->category = $this->get('Category');
		// add a hit to the category
		if ($this->hit($this->category->id))
		{
			$this->category->hits++;
		}
		
		// set some total defaults
		$this->sermonTotal = count($this->category->idCatidSermonB);
		$this->downloadTotal = 0;
		if ($this->sermonTotal > 0)
		{
			foreach ($this->category->idCatidSermonB as $sermon)
			{
				if (isset($sermon->idSermonStatisticC) && ArrayHelper::check($sermon->idSermonStatisticC))
				{
					foreach ($sermon->idSermonStatisticC as $download)
					{
						$this->downloadTotal += $download->counter;
					}
				}
			}
		}
		
		// set the FooTable style
		$style = $this->params->get('category_sermons_table_color', 0);
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

		// Set the toolbar
		$this->addToolBar();

		// Set the html view document stuff
		$this->_prepareDocument();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode(PHP_EOL, $errors), 500);
		}

		parent::display($tpl);
	}

	/**
	 * Increment the hit counter for the category.
	 *
	 * @param   integer  $pk  Primary key of the category to increment.
	 *
	 * @return  boolean  True if successful;
	 */
	public function hit($pk = 0)
	{
		if ($pk)
		{
			$db = Factory::getDbo();
			$query = $db->getQuery(true);

			// Fields to update.
			$fields = array(
			    $db->quoteName('hits') . ' = ' . $db->quoteName('hits') . ' + 1'
			);

			// Conditions for which records should be updated.
			$conditions = array(
			    $db->quoteName('id') . ' = ' . $pk
			);
			$query->update($db->quoteName('#__categories'))->set($fields)->where($conditions);

			$db->setQuery($query);
			return $db->execute();
		}
		return false;
	}


	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{

		// Only load jQuery if needed. (default is true)
		if ($this->params->get('add_jquery_framework', 1) == 1)
		{
			Html::_('jquery.framework');
		}
		// Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
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
				$uikitComp[] = 'data-uk-grid';

				// Get field uikit components needed in this view.
				$uikitFieldComp = $this->get('UikitComp');
				if (isset($uikitFieldComp) && ArrayHelper::check($uikitFieldComp))
				{
					if (isset($uikitComp) && ArrayHelper::check($uikitComp))
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

		// Add the CSS for Footable.
		Html::_('stylesheet', 'media/com_sermondistributor/footable-v2/css/footable.core.min.css', ['version' => 'auto']);

		// Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			Html::_('stylesheet', 'media/com_sermondistributor/footable-v2/css/footable.metro.min.css', ['version' => 'auto']);
		}
		// Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			Html::_('stylesheet', 'media/com_sermondistributor/footable-v2/css/footable.standalone.min.css', ['version' => 'auto']);
		}

		// Add the JavaScript for Footable
		Html::_('script', 'media/com_sermondistributor/footable-v2/js/footable.js', ['version' => 'auto']);
		Html::_('script', 'media/com_sermondistributor/footable-v2/js/footable.sort.js', ['version' => 'auto']);
		Html::_('script', 'media/com_sermondistributor/footable-v2/js/footable.filter.js', ['version' => 'auto']);
		Html::_('script', 'media/com_sermondistributor/footable-v2/js/footable.paginate.js', ['version' => 'auto']);
		// load the meta description
		if (isset($this->category->metadesc) && $this->category->metadesc)
		{
			$this->document->setDescription($this->category->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		// load the key words if set
		if (isset($this->category->metakey) && $this->category->metakey)
		{
			$this->document->setMetadata('keywords', $this->category->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		// check the robot params
		if (isset($this->category->robots) && $this->category->robots)
		{
			$this->document->setMetadata('robots', $this->category->robots);
		}
		elseif ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		// check if autor is to be set
		if (isset($this->category->created_by) && $this->params->get('MetaAuthor') == '1')
		{
			$this->document->setMetaData('author', $this->category->created_by);
		}
		// check if metadata is available
		if (isset($this->category->metadata) && $this->category->metadata)
		{
			$mdata = json_decode($this->category->metadata,true);
			foreach ($mdata as $k => $v)
			{
				if ($v)
				{
					$this->document->setMetadata($k, $v);
				}
			}
		}
		// add the document default css file
		Html::_('stylesheet', 'components/com_sermondistributor/assets/css/category.css', ['version' => 'auto']);
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{

		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('category');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
		}
		// now initiate the toolbar
		$this->toolbar = Toolbar::getInstance();
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
		return StringHelper::html($var, $this->_charset, $sorten, $length);
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
