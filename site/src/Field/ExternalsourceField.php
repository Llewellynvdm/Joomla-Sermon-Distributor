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

	@version		4.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		ExternalsourceField.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace TrueChristianChurch\Component\Sermondistributor\Site\Field;

use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\ListField;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Component\ComponentHelper;
use TrueChristianChurch\Component\Sermondistributor\Site\Helper\SermondistributorHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Externalsource Form Field class for the Sermondistributor component
 *
 * @since  1.6
 */
class ExternalsourceField extends ListField
{
	/**
	 * The externalsource field type.
	 *
	 * @var        string
	 */
	public $type = 'Externalsource';

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
			$jinput = $app->input;
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
				$_uri = (string) \Joomla\CMS\Uri\Uri::getInstance();
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
			// only add if user allowed to create external_source
			if ($user->authorise('external_source.create', 'com_sermondistributor') && $app->isClient('administrator')) // TODO for now only in admin area.
			{
				// build Create button
				$button[] = '<a id="'.$button_code_name.'Create" class="btn btn-small btn-success hasTooltip" title="'.Text::sprintf('COM_SERMONDISTRIBUTOR_CREATE_NEW_S', $button_label).'" style="border-radius: 0px 4px 4px 0px;"
					href="index.php?option=com_sermondistributor&amp;view=external_source&amp;layout=edit'.$ref.'" >
					<span class="icon-new icon-white"></span></a>';
			}
			// only add if user allowed to edit external_source
			if ($user->authorise('external_source.edit', 'com_sermondistributor') && $app->isClient('administrator')) // TODO for now only in admin area.
			{
				// build edit button
				$button[] = '<a id="'.$button_code_name.'Edit" class="btn btn-small hasTooltip" title="'.Text::sprintf('COM_SERMONDISTRIBUTOR_EDIT_S', $button_label).'" style="display: none; border-radius: 0px 4px 4px 0px;" href="#" >
					<span class="icon-edit"></span></a>';
				// build script
				$script[] = "
					document.addEventListener('DOMContentLoaded', function() {
						document.getElementById('jform_".$button_code_name."').addEventListener('change', function(e) {
							e.preventDefault();
							let ".$button_code_name."Value = this.value;
							".$button_code_name."Button(".$button_code_name."Value);
						});
						let ".$button_code_name."Value = document.getElementById('jform_".$button_code_name."').value;
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
							let url = 'index.php?option=com_sermondistributor&view=external_sources&task=external_source.edit&id='+value+'".$refJ."';
							editButton.setAttribute('href', url);
						} else {
							// show the create button
							createButton.style.display = 'block';
							// hide edit button
							editButton.style.display = 'none';
						}
					}";
			}
			// check if button was created for external_source field.
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
		$db = Factory::getDBO();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('a.id','a.description','a.externalsources','a.update_method'),array('id','external_source_description','externalsources','update_method')));
		$query->from($db->quoteName('#__sermondistributor_external_source', 'a'));
		$query->where($db->quoteName('a.published') . ' = 1');
		$query->order('a.description ASC');
		$db->setQuery((string)$query);
		$items = $db->loadObjectList();
		$options = array();
		if ($items)
		{
			$model = SermondistributorHelper::getModel('external_sources', JPATH_COMPONENT_ADMINISTRATOR);
			$options[] = Html::_('select.option', '', 'Select an option');
			foreach($items as $item)
			{
				$options[] = Html::_('select.option', $item->id, $item->external_source_description. '  (' . Text::_($model->selectionTranslation($item->externalsources,'externalsources')). ' ' . Text::_($model->selectionTranslation($item->update_method,'update_method')). ')');
			}
		}
		return $options;
	}
}
