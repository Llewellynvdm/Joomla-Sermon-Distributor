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
	@subpackage		MaxlengthRule.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Rule;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormRule;
use Joomla\Registry\Registry;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Component\ComponentHelper;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Form Rule (Maxlength) class for the Joomla Platform.
 *
 * @since  3.5
 */
class MaxlengthRule extends FormRule
{

/***[JCBGUI.validation_rule.php.12.$$$$]***/
	/**
	 * Validates a string value against the field's required state and maximum length.
	 *
	 * This method determines whether a field value is valid by checking:
	 * - Whether the field is marked as required.
	 * - Whether an empty value is allowed.
	 * - Whether the normalized length (whitespace removed) exceeds the configured maximum.
	 *
	 * The maximum length is read from the field's `maxlength` attribute and defaults to 2000
	 * characters when not specified.
	 *
	 * All whitespace is removed before length validation using UTF-8 safe processing.
	 *
	 * @param  \SimpleXMLElement  $element  The `<field>` definition containing attributes such as `required` and `maxlength`.
	 * @param  mixed              $value    The value being validated; expected to be castable to string.
	 * @param  string|null        $group    The name group (fieldset or repeatable context), if any.
	 * @param  Registry|null      $input    Optional registry containing the full form data set.
	 * @param  Form|null          $form     The form object owning this field.
	 *
	 * @return bool  True if the value passes validation, false otherwise.
	 *
	 * @since  5.1.4
	 */
	public function test(\SimpleXMLElement $element, $value, $group = null, ?Registry $input = null, ?Form $form = null)
	{
		// If the field is empty and not required, the field is valid.
		$required = ((string) $element['required'] === 'true' || (string) $element['required'] === 'required');

		if (!$required && empty($value)) {
			return true;
		}

		$length = $element['maxlength'] ?? 2000;

		// Normalize whitespace and trim
		$string = trim(preg_replace('/\s+/u', '', $value));

		if (mb_strlen($string) > $length)
		{
			return false;
		}

		return true;
	}/***[/JCBGUI$$$$]***/

}
