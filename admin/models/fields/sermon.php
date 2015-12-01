<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		sermon.php
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

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Sermon Form Field class for the Sermondistributor component
 */
class JFormFieldSermon extends JFormFieldList
{
	/**
	 * The sermon field type.
	 *
	 * @var		string
	 */
	public $type = 'sermon'; 
	/**
	 * Override to add new button
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// [7684] see if we should add buttons
		$setButton = $this->getAttribute('button');
		// [7686] get html
		$html = parent::getInput();
		// [7688] if true set button
		if ($setButton === 'true')
		{
			$user = JFactory::getUser();
			// [7692] only add if user allowed to create sermon
			if ($user->authorise('sermon.create', 'com_sermondistributor'))
			{
				// [7710] get the input from url
				$jinput = JFactory::getApplication()->input;
				// [7712] get the view name & id
				$values = $jinput->getArray(array(
					'id' => 'int',
					'view' => 'word'
				));
				// [7717] check if new item
				$ref = '';
				if (!is_null($values['id']) && strlen($values['view']))
				{
					// [7721] only load referal if not new item.
					$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				}
				// [7724] build the button
				$button = '<a class="btn btn-small btn-success"
					href="index.php?option=com_sermondistributor&amp;view=sermon&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span>' . JText::_('COM_SERMONDISTRIBUTOR_NEW') . '</a>';
				// [7728] return the button attached to input field
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
		$query->select($db->quoteName(array('a.id','a.name'),array('id','sermon_name')));
		$query->from($db->quoteName('#__sermondistributor_sermon', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.name ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			$options[] = JHtml::_('select.option', '', 'Select a sermon');
			foreach($items as $item)
			{
				$options[] = JHtml::_('select.option', $item->id, $item->sermon_name);
			}
		}
		return $options;
	}
}
