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

	@version		2.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		localfiles.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// import the list field type
jimport('joomla.form.helper');
JFormHelper::loadFieldClass('list');

/**
 * Localfiles Form Field class for the Sermondistributor component
 */
class JFormFieldLocalfiles extends JFormFieldList
{
	/**
	 * The localfiles field type.
	 *
	 * @var		string
	 */
	public $type = 'localfiles';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return	array    An array of JHtml options.
	 */
	protected function getOptions()
	{
		// get local folder
		$localfolder = JComponentHelper::getParams('com_sermondistributor')->get('localfolder', JPATH_ROOT.'/images');
		// set the default
		$options[] = JHtml::_('select.option', '', JText::sprintf('COM_SERMONDISTRIBUTOR_PLEASE_ADD_FILES_TO_S',$localfolder));
		// inport all needed classes
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		// setup the folder if it does not exist
		if (!JFolder::exists($localfolder))
		{
			JFolder::create($localfolder);
		}
		// now check if there are files in the folder
		if ($files = JFolder::files($localfolder))
		{
			$options = array();
			foreach ($files as $file)
			{
				$options[] = JHtml::_('select.option', $file, $file);
			}
		}
		return $options;
	}
}
