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

	@version		2.1.x
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

use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Filesystem\File;

/**
 * Sermondistributor Html View class for the Preacher
 */
class SermondistributorViewPreacher extends HtmlView
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
		// Initialise variables.
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->preacher = $this->get('Preacher');
		$this->numberdownloads = $this->get('NumberDownloads');
		$this->numbersermons = $this->get('NumberSermons');
		// add a hit to the preacher
		if ($this->hit($this->preacher->id))
		{
			$this->preacher->hits++;
		}
		// set some total defaults
		$this->sermonTotal = count($this->numbersermons);
		$this->downloadTotal = 0;
		if (isset($this->numberdownloads) && SermondistributorHelper::checkArray($this->numberdownloads))
		{
			foreach ($this->numberdownloads as $download)
			{
				$this->downloadTotal += $download->counter;
			}
		}
		// set the FooTable style
		$style = $this->params->get('preacher_sermons_table_color', 0);
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

		// set the document
		$this->_prepareDocument();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode(PHP_EOL, $errors), 500);
		}

		parent::display($tpl);
	}


	 /**
	 * Increment the hit counter for the preacher.
	 *
	 * @param   integer  $pk  Primary key of the preacher to increment.
	 *
	 * @return  boolean  True if successful;
	 */
	public function hit($pk = 0)
	{
		if ($pk)
		{
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

			// Fields to update.
			$fields = array(
			    $db->quoteName('hits') . ' = ' . $db->quoteName('hits') . ' + 1'
			);

			// Conditions for which records should be updated.
			$conditions = array(
			    $db->quoteName('id') . ' = ' . $pk
			);
			$query->update($db->quoteName('#__sermondistributor_preacher'))->set($fields)->where($conditions);

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

		// always make sure jquery is loaded.
		JHtml::_('jquery.framework');
		// Load the header checker class.
		require_once( JPATH_COMPONENT_SITE.'/helpers/headercheck.php' );
		// Initialize the header checker.
		$HeaderCheck = new sermondistributorHeaderCheck;

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
				JHtml::_('stylesheet', 'media/com_sermondistributor/uikit-v2/css/uikit'.$style.$size.'.css', ['version' => 'auto']);
			}
			// The uikit js.
			if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
			{
				JHtml::_('script', 'media/com_sermondistributor/uikit-v2/js/uikit'.$size.'.js', ['version' => 'auto']);
			}

			// Load the script to find all uikit components needed.
			if ($uikit != 2)
			{
				// Set the default uikit components in this view.
				$uikitComp = array();
				$uikitComp[] = 'data-uk-grid';
				$uikitComp[] = 'data-uk-tooltip';

				// Get field uikit components needed in this view.
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

			// Load the needed uikit components in this view.
			if ($uikit != 2 && isset($uikitComp) && SermondistributorHelper::checkArray($uikitComp))
			{
				// load just in case.
				jimport('joomla.filesystem.file');
				// loading...
				foreach ($uikitComp as $class)
				{
					foreach (SermondistributorHelper::$uk_components[$class] as $name)
					{
						// check if the CSS file exists.
						if (File::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit-v2/css/components/'.$name.$style.$size.'.css'))
						{
							// load the css.
							JHtml::_('stylesheet', 'media/com_sermondistributor/uikit-v2/css/components/'.$name.$style.$size.'.css', ['version' => 'auto']);
						}
						// check if the JavaScript file exists.
						if (File::exists(JPATH_ROOT.'/media/com_sermondistributor/uikit-v2/js/components/'.$name.$size.'.js'))
						{
							// load the js.
							JHtml::_('script', 'media/com_sermondistributor/uikit-v2/js/components/'.$name.$size.'.js', ['version' => 'auto'], ['type' => 'text/javascript', 'async' => 'async']);
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
				JHtml::_('stylesheet', 'media/com_sermondistributor/uikit-v3/css/uikit'.$size.'.css', ['version' => 'auto']);
			}
			// The uikit js.
			if ((!$HeaderCheck->js_loaded('uikit.min') || $uikit == 1) && $uikit != 2 && $uikit != 3)
			{
				JHtml::_('script', 'media/com_sermondistributor/uikit-v3/js/uikit'.$size.'.js', ['version' => 'auto']);
				JHtml::_('script', 'media/com_sermondistributor/uikit-v3/js/uikit-icons'.$size.'.js', ['version' => 'auto']);
			}
		}

		// Add the CSS for Footable.
		JHtml::_('stylesheet', 'media/com_sermondistributor/footable-v2/css/footable.core.min.css', ['version' => 'auto']);

		// Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			JHtml::_('stylesheet', 'media/com_sermondistributor/footable-v2/css/footable.metro.min.css', ['version' => 'auto']);
		}
		// Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			JHtml::_('stylesheet', 'media/com_sermondistributor/footable-v2/css/footable.standalone.min.css', ['version' => 'auto']);
		}

		// Add the JavaScript for Footable
		JHtml::_('script', 'media/com_sermondistributor/footable-v2/js/footable.js', ['version' => 'auto']);
		JHtml::_('script', 'media/com_sermondistributor/footable-v2/js/footable.sort.js', ['version' => 'auto']);
		JHtml::_('script', 'media/com_sermondistributor/footable-v2/js/footable.filter.js', ['version' => 'auto']);
		JHtml::_('script', 'media/com_sermondistributor/footable-v2/js/footable.paginate.js', ['version' => 'auto']);
		// load the meta description
		if (isset($this->preacher->metadesc) && $this->preacher->metadesc)
		{
			$this->document->setDescription($this->preacher->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}
		// load the key words if set
		if (isset($this->preacher->metakey) && $this->preacher->metakey)
		{
			$this->document->setMetadata('keywords', $this->preacher->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		// check the robot params
		if (isset($this->preacher->robots) && $this->preacher->robots)
		{
			$this->document->setMetadata('robots', $this->preacher->robots);
		}
		elseif ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
		// check if autor is to be set
		if (isset($this->preacher->created_by) && $this->params->get('MetaAuthor') == '1')
		{
			$this->document->setMetaData('author', $this->preacher->created_by);
		}
		// check if metadata is available
		if (isset($this->preacher->metadata) && $this->preacher->metadata)
		{
			$mdata = json_decode($this->preacher->metadata,true);
			foreach ($mdata as $k => $v)
			{
				if ($v)
				{
					$this->document->setMetadata($k, $v);
				}
			}
		}
		// add the document default css file
		$this->document->addStyleSheet(JURI::root(true) .'/components/com_sermondistributor/assets/css/preacher.css', (SermondistributorHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
	}

	/**
	 * Setting the toolbar
	 */
	protected function addToolBar()
	{
		
		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('preacher');
		if (SermondistributorHelper::checkString($this->help_url))
		{
			JToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
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
