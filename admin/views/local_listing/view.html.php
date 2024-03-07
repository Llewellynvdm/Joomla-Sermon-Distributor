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
use VDM\Joomla\Utilities\StringHelper;

/**
 * Local_listing Html View class
 */
class SermondistributorViewLocal_listing extends HtmlView
{
	/**
	 * display method of View
	 * @return void
	 */
	public function display($tpl = null)
	{
		// set params
		$this->params = ComponentHelper::getParams('com_sermondistributor');
		// Assign the variables
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');
		$this->state = $this->get('State');
		// get action permissions
		$this->canDo = SermondistributorHelper::getActions('local_listing', $this->item);
		// get input
		$jinput = Factory::getApplication()->input;
		$this->ref = $jinput->get('ref', 0, 'word');
		$this->refid = $jinput->get('refid', 0, 'int');
		$return = $jinput->get('return', null, 'base64');
		// set the referral string
		$this->referral = '';
		if ($this->refid && $this->ref)
		{
			// return to the item that referred to this item
			$this->referral = '&ref=' . (string)$this->ref . '&refid=' . (int)$this->refid;
		}
		elseif($this->ref)
		{
			// return to the list view that referred to this item
			$this->referral = '&ref=' . (string)$this->ref;
		}
		// check return value
		if (!is_null($return))
		{
			// add the return value
			$this->referral .= '&return=' . (string)$return;
		}

		// Set the toolbar
		$this->addToolBar();

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
		Factory::getApplication()->input->set('hidemainmenu', true);
		$user = Factory::getUser();
		$userId	= $user->id;
		$isNew = $this->item->id == 0;

		ToolbarHelper::title( Text::_($isNew ? 'COM_SERMONDISTRIBUTOR_LOCAL_LISTING_NEW' : 'COM_SERMONDISTRIBUTOR_LOCAL_LISTING_EDIT'), 'pencil-2 article-add');
		// Built the actions for new and existing records.
		if (StringHelper::check($this->referral))
		{
			if ($this->canDo->get('local_listing.create') && $isNew)
			{
				// We can create the record.
				ToolbarHelper::save('local_listing.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('local_listing.edit'))
			{
				// We can save the record.
				ToolbarHelper::save('local_listing.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// Do not creat but cancel.
				ToolbarHelper::cancel('local_listing.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// We can close it.
				ToolbarHelper::cancel('local_listing.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// For new records, check the create permission.
				if ($this->canDo->get('local_listing.create'))
				{
					ToolbarHelper::apply('local_listing.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('local_listing.save', 'JTOOLBAR_SAVE');
					ToolbarHelper::custom('local_listing.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				ToolbarHelper::cancel('local_listing.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('local_listing.edit'))
				{
					// We can save the new record
					ToolbarHelper::apply('local_listing.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('local_listing.save', 'JTOOLBAR_SAVE');
					// We can save this record, but check the create permission to see
					// if we can return to make a new one.
					if ($this->canDo->get('local_listing.create'))
					{
						ToolbarHelper::custom('local_listing.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
				$canVersion = ($this->canDo->get('core.version') && $this->canDo->get('local_listing.version'));
				if ($this->state->params->get('save_history', 1) && $this->canDo->get('local_listing.edit') && $canVersion)
				{
					ToolbarHelper::versions('com_sermondistributor.local_listing', $this->item->id);
				}
				if ($this->canDo->get('local_listing.create'))
				{
					ToolbarHelper::custom('local_listing.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				}
				ToolbarHelper::cancel('local_listing.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		ToolbarHelper::divider();
		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('local_listing');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
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
		if(strlen($var) > 30)
		{
			// use the helper htmlEscape method instead and shorten the string
			return StringHelper::html($var, $this->_charset, true, 30);
		}
		// use the helper htmlEscape method instead.
		return StringHelper::html($var, $this->_charset);
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$isNew = ($this->item->id < 1);
		$this->getDocument()->setTitle(Text::_($isNew ? 'COM_SERMONDISTRIBUTOR_LOCAL_LISTING_NEW' : 'COM_SERMONDISTRIBUTOR_LOCAL_LISTING_EDIT'));
		Html::_('stylesheet', "administrator/components/com_sermondistributor/assets/css/local_listing.css", ['version' => 'auto']);
		// Add Ajax Token
		$this->getDocument()->addScriptDeclaration("var token = '" . Session::getFormToken() . "';");
		Html::_('script', $this->script, ['version' => 'auto']);
		Html::_('script', "administrator/components/com_sermondistributor/views/local_listing/submitbutton.js", ['version' => 'auto']);
		// add JavaScripts
		Html::_('script', 'media/com_sermondistributor/uikit/js/uikit.min.js', ['version' => 'auto']);
		// add the style sheets
		Html::_('stylesheet', 'media/com_sermondistributor/uikit/css/uikit.gradient.min.css', ['version' => 'auto']);
		Text::script('view not acceptable. Error');
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
