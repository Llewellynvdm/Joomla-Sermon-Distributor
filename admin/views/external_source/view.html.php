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

	@version		2.0.x
	@build			3rd March, 2018
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
 * External_source View class
 */
class SermondistributorViewExternal_source extends JViewLegacy
{
	/**
	 * display method of View
	 * @return void
	 */
	public function display($tpl = null)
	{
		// Assign the variables
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');
		$this->state = $this->get('State');
		// get action permissions
		$this->canDo = SermondistributorHelper::getActions('external_source',$this->item);
		// get input
		$jinput = JFactory::getApplication()->input;
		$this->ref = $jinput->get('ref', 0, 'word');
		$this->refid = $jinput->get('refid', 0, 'int');
		$this->referral = '';
		if ($this->refid)
		{
			// return to the item that refered to this item
			$this->referral = '&ref='.(string)$this->ref.'&refid='.(int)$this->refid;
		}
		elseif($this->ref)
		{
			// return to the list view that refered to this item
			$this->referral = '&ref='.(string)$this->ref;
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
		JFactory::getApplication()->input->set('hidemainmenu', true);
		$user = JFactory::getUser();
		$userId	= $user->id;
		$isNew = $this->item->id == 0;

		JToolbarHelper::title( JText::_($isNew ? 'COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_NEW' : 'COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_EDIT'), 'pencil-2 article-add');
		// Built the actions for new and existing records.
		if ($this->refid || $this->ref)
		{
			if ($this->canDo->get('external_source.create') && $isNew)
			{
				// We can create the record.
				JToolBarHelper::save('external_source.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('external_source.edit'))
			{
				// We can save the record.
				JToolBarHelper::save('external_source.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// Do not creat but cancel.
				JToolBarHelper::cancel('external_source.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// We can close it.
				JToolBarHelper::cancel('external_source.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// For new records, check the create permission.
				if ($this->canDo->get('external_source.create'))
				{
					JToolBarHelper::apply('external_source.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('external_source.save', 'JTOOLBAR_SAVE');
					JToolBarHelper::custom('external_source.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				JToolBarHelper::cancel('external_source.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('external_source.edit'))
				{
					// We can save the new record
					JToolBarHelper::apply('external_source.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('external_source.save', 'JTOOLBAR_SAVE');
					// We can save this record, but check the create permission to see
					// if we can return to make a new one.
					if ($this->canDo->get('external_source.create'))
					{
						JToolBarHelper::custom('external_source.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
				$canVersion = ($this->canDo->get('core.version') && $this->canDo->get('external_source.version'));
				if ($this->state->params->get('save_history', 1) && $this->canDo->get('external_source.edit') && $canVersion)
				{
					JToolbarHelper::versions('com_sermondistributor.external_source', $this->item->id);
				}
				if ($this->canDo->get('external_source.create'))
				{
					JToolBarHelper::custom('external_source.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				}
				if ($this->canDo->get('external_source.clear_local_listing'))
				{
					// add Clear Local Listing button.
					JToolBarHelper::custom('external_source.clearLocalListing', 'delete', '', 'COM_SERMONDISTRIBUTOR_CLEAR_LOCAL_LISTING', false);
				}
				if ($this->canDo->get('external_source.reset_update_status'))
				{
					// add Reset Update Status button.
					JToolBarHelper::custom('external_source.resetUpdateStatus', 'undo-2', '', 'COM_SERMONDISTRIBUTOR_RESET_UPDATE_STATUS', false);
				}
				JToolBarHelper::cancel('external_source.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		JToolbarHelper::divider();
		// set help url for this view if found
		$help_url = SermondistributorHelper::getHelpUrl('external_source');
		if (SermondistributorHelper::checkString($help_url))
		{
			JToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $help_url);
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
			return SermondistributorHelper::htmlEscape($var, $this->_charset, true, 30);
		}
		// use the helper htmlEscape method instead.
		return SermondistributorHelper::htmlEscape($var, $this->_charset);
	}

	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument()
	{
		$isNew = ($this->item->id < 1);
		if (!isset($this->document))
		{
			$this->document = JFactory::getDocument();
		}
		$this->document->setTitle(JText::_($isNew ? 'COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_NEW' : 'COM_SERMONDISTRIBUTOR_EXTERNAL_SOURCE_EDIT'));
		$this->document->addStyleSheet(JURI::root() . "administrator/components/com_sermondistributor/assets/css/external_source.css", (SermondistributorHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/css');
		// Add Ajax Token
		$this->document->addScriptDeclaration("var token = '".JSession::getFormToken()."';"); 
		$this->document->addScript(JURI::root() . $this->script, (SermondistributorHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/javascript');
		$this->document->addScript(JURI::root() . "administrator/components/com_sermondistributor/views/external_source/submitbutton.js", (SermondistributorHelper::jVersion()->isCompatible('3.8.0')) ? array('version' => 'auto') : 'text/javascript'); 
		JText::script('view not acceptable. Error');
	}
}
