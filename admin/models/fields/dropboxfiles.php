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

	@version		1.3.1
	@build			8th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		dropboxfiles.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Dropboxfiles Form Field class for the Sermondistributor component
 */
class JFormFieldDropboxfiles extends JFormFieldList
{
	/**
	 * The dropboxfiles field type.
	 *
	 * @var		string
	 */
	public $type = 'dropboxfiles'; 
	/**
	 * Override to add new button
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// [Interpretation 6860] see if we should add buttons
		$setButton = $this->getAttribute('button');
		// [Interpretation 6862] get html
		$html = parent::getInput();
		// [Interpretation 6864] if true set button
		if ($setButton === 'true')
		{
			$user = JFactory::getUser();
			// [Interpretation 6868] only add if user allowed to create 
			if ($user->authorise('core.create', 'com_sermondistributor'))
			{
				// [Interpretation 6886] get the input from url
				$jinput = JFactory::getApplication()->input;
				// [Interpretation 6888] get the view name & id
				$values = $jinput->getArray(array(
					'id' => 'int',
					'view' => 'word'
				));
				// [Interpretation 6893] check if new item
				$ref = '';
				if (!is_null($values['id']) && strlen($values['view']))
				{
					// [Interpretation 6897] only load referal if not new item.
					$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				}
				// [Interpretation 6900] build the button
				$button = '<a class="btn btn-small btn-success"
					href="index.php?option=com_sermondistributor&amp;view=&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span>' . JText::_('COM_SERMONDISTRIBUTOR_NEW') . '</a>';
				// [Interpretation 6904] return the button attached to input field
				return $html . $button;
			}
		}
		return $html;
	}

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array		An array of JHtml options.
	 */
	public function getOptions()
	{
		// set the default
		$options[] = JHtml::_('select.option', '', JText::_('The local listing of the Manual Dropbox folder is empty.'));
		$links = SermondistributorHelper::getDropboxLink('manual',2);
		if (SermondistributorHelper::checkArray($links))
		{
			$options = array();
			foreach ($links as $file => $link)
			{
				$name = substr($file, strrpos($file, '/') + 1);
				$options[] = JHtml::_('select.option', $file, $name);
			}
		}
		return $options;
	}
}
