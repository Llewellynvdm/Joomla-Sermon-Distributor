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

	@version		1.3.0
	@build			7th March, 2016
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		preachers.php
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
 * Preachers Form Field class for the Sermondistributor component
 */
class JFormFieldPreachers extends JFormFieldList
{
	/**
	 * The preachers field type.
	 *
	 * @var		string
	 */
	public $type = 'preachers'; 
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
			// [Interpretation 6868] only add if user allowed to create preacher
			if ($user->authorise('preacher.create', 'com_sermondistributor'))
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
					href="index.php?option=com_sermondistributor&amp;view=preacher&amp;layout=edit'.$ref.'" >
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
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.name'),array('id','preacher_name')));
		$query->from($db->quoteName('#__sermondistributor_preacher', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.name ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			$options[] = JHtml::_('select.option', '', 'Select a preacher');
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->preacher_name);
			}
		}
		return $options;
	}
}
