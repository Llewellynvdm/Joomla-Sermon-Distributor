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

	@version		6.0.0
	@created		9th July, 2026
	@package		Sermon Distributor
	@subpackage		LangField.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Component\ComponentHelper;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Database\DatabaseInterface;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Lang Form Field class for the Sermondistributor component
 *
 * @since  1.6
 */
class LangField extends ListField
{
	/**
	 * The lang field type.
	 *
	 * @var        string
	 */
	public $type = 'Lang';

	/**
	 * Override to add new button
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   3.2
	 */
	protected function getInput()
	{
		// see if we should add buttons
		$set_button = $this->getAttribute('button');
		// get html
		$html = parent::getInput();
		// if true set button
		if ($set_button === 'true')
		{
			$button = array();
			$script = array();
			$button_code_name = $this->getAttribute('name');
			// get the input from url
			$app = Factory::getApplication();
			$jinput = method_exists($app, 'getInput') ? $app->getInput() : $app->input;
			// get the view name & id
			$values = $jinput->getArray(array(
				'id' => 'int',
				'view' => 'word'
			));
			// check if new item
			$ref = '';
			$refJ = '';
			if (!is_null($values['id']) && strlen($values['view']))
			{
				// only load referral if not new item.
				$ref = '&amp;ref=' . $values['view'] . '&amp;refid=' . $values['id'];
				$refJ = '&ref=' . $values['view'] . '&refid=' . $values['id'];
				// get the return value.
				$_uri = (string) Uri::getInstance();
				$_return = urlencode(base64_encode($_uri));
				// load return value.
				$ref .= '&amp;return=' . $_return;
				$refJ .= '&return=' . $_return;
			}
			// get button label
			$button_label = trim($button_code_name);
			$button_label = preg_replace('/_+/', ' ', $button_label);
			$button_label = preg_replace('/\s+/', ' ', $button_label);
			$button_label = preg_replace("/[^A-Za-z ]/", '', $button_label);
			$button_label = ucfirst(strtolower($button_label));
			// get user object
			$user = Factory::getApplication()->getIdentity();
			// only add if user allowed to create language
			if ($user->authorise('language.create', 'com_sermondistributor') && $app->isClient('administrator')) // TODO for now only in admin area.
			{
				// build Create button
				$button[] = '<a id="'.$button_code_name.'Create" class="btn btn-small btn-success hasTooltip" title="'.Text::sprintf('COM_SERMONDISTRIBUTOR_CREATE_NEW_S', $button_label).'" style="border-radius: 0px 4px 4px 0px;"
					href="index.php?option=com_sermondistributor&amp;view=language&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span></a>';
			}
			// only add if user allowed to edit language
			if ($user->authorise('language.edit', 'com_sermondistributor') && $app->isClient('administrator')) // TODO for now only in admin area.
			{
				// build edit button
				$button[] = '<a id="'.$button_code_name.'Edit" class="btn btn-small btn-outline-success button-select hasTooltip" title="'.Text::sprintf('COM_SERMONDISTRIBUTOR_EDIT_S', $button_label).'" style="display: none; border-radius: 0px 4px 4px 0px;" href="#" >
					<span class="icon-edit"></span></a>';
				// build script
				$script[] = "
					document.addEventListener('DOMContentLoaded', function() {
						let  ".$button_code_name."Field = document.getElementById('jform_".$button_code_name."');
						if (!".$button_code_name."Field) { return; }
						".$button_code_name."Field.addEventListener('change', function(e) {
							e.preventDefault();
							let ".$button_code_name."Value = this.value;
							".$button_code_name."Button(".$button_code_name."Value);
						});
						let ".$button_code_name."Value = ".$button_code_name."Field.value;
						".$button_code_name."Button(".$button_code_name."Value);
					});
					function ".$button_code_name."Button(value) {
						var createButton = document.getElementById('".$button_code_name."Create');
						var editButton = document.getElementById('".$button_code_name."Edit');
						if (value > 0) {
							// hide the create button
							createButton.style.display = 'none';
							// show edit button
							editButton.style.display = 'block';
							let url = 'index.php?option=com_sermondistributor&view=languages&task=language.edit&id='+value+'".$refJ."';
							editButton.setAttribute('href', url);
						} else {
							// show the create button
							createButton.style.display = 'block';
							// hide edit button
							editButton.style.display = 'none';
						}
					}";
			}
			// check if button was created for language field.
			if (is_array($button) && count($button) > 0)
			{
				// Load the needed script.
				$document = Factory::getApplication()->getDocument();
				$document->addScriptDeclaration(implode(' ',$script));
				// return the button attached to input field.
				return '<div class="input-group">' .$html . implode('',$button).'</div>';
			}
		}
		return $html;
	}

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array    An array of Html options.
	 * @since   1.6
	 */
	protected function getOptions()
	{
				$db = Factory::getContainer()->get(DatabaseInterface::class);
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.langtag','a.name'),array('langtag','languages_name')));
		$query->from($db->quoteName('#__sermondistributor_language', 'a'));
		$query->where($db->quoteName('a.published') . ' >= 1');
		$query->order('a.langtag ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		// add the main language
		$main_lang = trim(ComponentHelper::getParams('com_sermondistributor')->get('language', 'en-GB'));
		// make sure the main language is added
		$wasAdded = false;
		$options = array();
		if ($items)
		{
			$options[] = Html::_('select.option', '', 'Select an option');
			foreach($items as $item)
			{
				$item->langtag = trim($item->langtag);
				$options[] = Html::_('select.option', $item->langtag, $item->languages_name . ' (' .$item->langtag.')');
				if ($main_lang === $item->langtag)
				{
					$wasAdded = true;
				}
			}
		}
		// now add it if not already added (it must default to $main_lang)
		if (!$wasAdded)
		{
			if ('en-GB' === $main_lang)
			{
				$options[] = Html::_('select.option', $main_lang, 'English GB (' . $main_lang . ')');
			}
			else
			{
				$options[] = Html::_('select.option', $main_lang, 'Main Language (' . $main_lang . ')');
			}
		}
		return $options;
	}
}
