<?php
/*----------------------------------------------------------------------------------|  www.vdm.io  |----/
				Vast Development Method 
/-------------------------------------------------------------------------------------------------------/

	@version		1.2.9
	@build			1st December, 2015
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		dropboxfiles.php
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
		// [7691] see if we should add buttons
		$setButton = $this->getAttribute('button');
		// [7693] get html
		$html = parent::getInput();
		// [7695] if true set button
		if ($setButton === 'true')
		{
			$user = JFactory::getUser();
			// [7699] only add if user allowed to create 
			if ($user->authorise('core.create', 'com_sermondistributor'))
			{
				// [7717] get the input from url
				$jinput = JFactory::getApplication()->input;
				// [7719] get the view name & id
				$values = $jinput->getArray(array(
					'id' => 'int',
					'view' => 'word'
				));
				// [7724] check if new item
				$ref = '';
				if (!is_null($values['id']) && strlen($values['view']))
				{
					// [7728] only load referal if not new item.
					$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				}
				// [7731] build the button
				$button = '<a class="btn btn-small btn-success"
					href="index.php?option=com_sermondistributor&amp;view=&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span>' . JText::_('COM_SERMONDISTRIBUTOR_NEW') . '</a>';
				// [7735] return the button attached to input field
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
