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
	@build			2nd July, 2016
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
 * Sermon View class
 */
class SermondistributorViewSermon extends JViewLegacy
{
	/**
	 * display method of View
	 * @return void
	 */
	public function display($tpl = null)
	{
		// Check for errors.
		if (count($errors = $this->get('Errors')))
                {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Assign the variables
		$this->form 		= $this->get('Form');
		$this->item 		= $this->get('Item');
		$this->script 		= $this->get('Script');
		$this->state		= $this->get('State');
                // get action permissions
		$this->canDo		= SermondistributorHelper::getActions('sermon',$this->item);
		// get input
		$jinput = JFactory::getApplication()->input;
		$this->ref 		= $jinput->get('ref', 0, 'word');
		$this->refid            = $jinput->get('refid', 0, 'int');
		$this->referral         = '';
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

		// [Interpretation 6144] Get Linked view data
		$this->vwbstastics		= $this->get('Vwbstastics');

		// Set the toolbar
		$this->addToolBar();

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

		JToolbarHelper::title( JText::_($isNew ? 'COM_SERMONDISTRIBUTOR_SERMON_NEW' : 'COM_SERMONDISTRIBUTOR_SERMON_EDIT'), 'pencil-2 article-add');
		// [Interpretation 10085] Built the actions for new and existing records.
		if ($this->refid || $this->ref)
		{
			if ($this->canDo->get('sermon.create') && $isNew)
			{
				// [Interpretation 10097] We can create the record.
				JToolBarHelper::save('sermon.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('sermon.edit'))
			{
				// [Interpretation 10109] We can save the record.
				JToolBarHelper::save('sermon.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// [Interpretation 10114] Do not creat but cancel.
				JToolBarHelper::cancel('sermon.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// [Interpretation 10119] We can close it.
				JToolBarHelper::cancel('sermon.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// [Interpretation 10127] For new records, check the create permission.
				if ($this->canDo->get('sermon.create'))
				{
					JToolBarHelper::apply('sermon.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('sermon.save', 'JTOOLBAR_SAVE');
					JToolBarHelper::custom('sermon.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				JToolBarHelper::cancel('sermon.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('sermon.edit'))
				{
					// [Interpretation 10154] We can save the new record
					JToolBarHelper::apply('sermon.apply', 'JTOOLBAR_APPLY');
					JToolBarHelper::save('sermon.save', 'JTOOLBAR_SAVE');
					// [Interpretation 10157] We can save this record, but check the create permission to see
					// [Interpretation 10158] if we can return to make a new one.
					if ($this->canDo->get('sermon.create'))
					{
						JToolBarHelper::custom('sermon.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
				$canVersion = ($this->canDo->get('core.version') && $this->canDo->get('sermon.version'));
				if ($this->state->params->get('save_history', 1) && $this->canDo->get('sermon.edit') && $canVersion)
				{
					JToolbarHelper::versions('com_sermondistributor.sermon', $this->item->id);
				}
				if ($this->canDo->get('sermon.create'))
				{
					JToolBarHelper::custom('sermon.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				}
				JToolBarHelper::cancel('sermon.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		JToolbarHelper::divider();
		// [Interpretation 10194] set help url for this view if found
		$help_url = SermondistributorHelper::getHelpUrl('sermon');
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
		$document = JFactory::getDocument();
		$document->setTitle(JText::_($isNew ? 'COM_SERMONDISTRIBUTOR_SERMON_NEW' : 'COM_SERMONDISTRIBUTOR_SERMON_EDIT'));
		$document->addStyleSheet(JURI::root() . "administrator/components/com_sermondistributor/assets/css/sermon.css"); 

		// [Interpretation 6186] Add the CSS for Footable.
		$document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.core.min.css');

		// [Interpretation 6188] Use the Metro Style
		if (!isset($this->fooTableStyle) || 0 == $this->fooTableStyle)
		{
			$document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.metro.min.css');
		}
		// [Interpretation 6193] Use the Legacy Style.
		elseif (isset($this->fooTableStyle) && 1 == $this->fooTableStyle)
		{
			$document->addStyleSheet(JURI::root() .'media/com_sermondistributor/footable/css/footable.standalone.min.css');
		}

		// [Interpretation 6198] Add the JavaScript for Footable
		$document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.js');
		$document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.sort.js');
		$document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.filter.js');
		$document->addScript(JURI::root() .'media/com_sermondistributor/footable/js/footable.paginate.js');

		$footable = "jQuery(document).ready(function() { jQuery(function () { jQuery('.footable').footable(); }); jQuery('.nav-tabs').on('click', 'li', function() { setTimeout(tableFix, 10); }); }); function tableFix() { jQuery('.footable').trigger('footable_resize'); }";
		$document->addScriptDeclaration($footable);

		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "administrator/components/com_sermondistributor/views/sermon/submitbutton.js"); 
		JText::script('view not acceptable. Error');
	}
}
