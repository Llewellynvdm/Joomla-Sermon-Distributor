<?php
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2022
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace VDM\Joomla\Utilities;


use Joomla\CMS\Form\FormHelper as JoomlaFormHelper;
use Joomla\CMS\Form\FormField;


/**
 * Form Helper
 * 
 * @since 3.2.0
 */
abstract class FormHelper
{
	/**
	 * get the field xml
	 *
	 * @param   array      $attributes   The array of attributes
	 * @param   array      $options      The options to apply to the XML element
	 *
	 * @return  \SimpleXMLElement|null
	 * @since 3.2.0
	 */
	public static function xml(array $attributes, ?array $options = null): ?\SimpleXMLElement
	{
		// make sure we have attributes and a type value
		if (ArrayHelper::check($attributes))
		{
			// start field xml
			$XML = new \SimpleXMLElement('<field/>');

			// load the attributes
			self::attributes($XML, $attributes);

			// check if we have options
			if (ArrayHelper::check($options))
			{
				// load the options
				self::options($XML, $options);
			}

			// return the field xml
			return $XML;
		}

		return null;
	}

	/**
	 * xmlAppend
	 *
	 * @param   \SimpleXMLElement   $xml      The XML element reference in which to inject a comment
	 * @param   mixed              $node     A SimpleXMLElement node to append to the XML element reference,
	 *                                         or a stdClass object containing a comment attribute to be injected
	 *                                         before the XML node and a fieldXML attribute containing a SimpleXMLElement
	 *
	 * @return  void
	 * @since 3.2.0
	 */
	public static function append(\SimpleXMLElement &$xml, $node)
	{
		if (!$node)
		{
			// element was not returned
			return;
		}
		switch (get_class($node))
		{
			case 'stdClass':
				if (property_exists($node, 'comment'))
				{
					self::comment($xml, $node->comment);
				}
				if (property_exists($node, 'fieldXML'))
				{
					self::append($xml, $node->fieldXML);
				}
				break;
			case 'SimpleXMLElement':
				$domXML = \dom_import_simplexml($xml);
				$domNode = \dom_import_simplexml($node);
				$domXML->appendChild($domXML->ownerDocument->importNode($domNode, true));
				$xml = \simplexml_import_dom($domXML);
				break;
		}
	}

	/**
	 * xmlComment
	 *
	 * @param   \SimpleXMLElement   $xml        The XML element reference in which to inject a comment
	 * @param   string             $comment    The comment to inject
	 *
	 * @return  void
	 * @since 3.2.0
	 */
	public static function comment(\SimpleXMLElement &$xml, string $comment)
	{
		$domXML = \dom_import_simplexml($xml);
		$domComment = new \DOMComment($comment);
		$nodeTarget = $domXML->ownerDocument->importNode($domComment, true);
		$domXML->appendChild($nodeTarget);
		$xml = \simplexml_import_dom($domXML);
	}

	/**
	 * xmlAddAttributes
	 *
	 * @param   \SimpleXMLElement   $xml          The XML element reference in which to inject a comment
	 * @param   array              $attributes   The attributes to apply to the XML element
	 *
	 * @return  void
	 * @since 3.2.0
	 */
	public static function attributes(\SimpleXMLElement &$xml, array $attributes = [])
	{
		foreach ($attributes as $key => $value)
		{
			$xml->addAttribute($key, $value);
		}
	}

	/**
	 * xmlAddOptions
	 *
	 * @param   \SimpleXMLElement   $xml          The XML element reference in which to inject a comment
	 * @param   array              $options      The options to apply to the XML element
	 *
	 * @return  void
	 * @since 3.2.0
	 */
	public static function options(\SimpleXMLElement &$xml, array $options = [])
	{
		foreach ($options as $key => $value)
		{
			$addOption = $xml->addChild('option');
			$addOption->addAttribute('value', $key);
			$addOption[] = $value;
		}
	}

	/**
	 * get the field object
	 *
	 * @param   array      $attributes   The array of attributes
	 * @param   string     $default      The default of the field
	 * @param   array      $options      The options to apply to the XML element
	 *
	 * @return  FormField|null
	 * @since 3.2.0
	 */
	public static function field(array $attributes, string $default = '', ?array $options = null): ?FormField
	{
		// make sure we have attributes and a type value
		if (ArrayHelper::check($attributes) && isset($attributes['type']))
		{
			// get field type
			if (($field = JoomlaFormHelper::loadFieldType($attributes['type'], true)) === false)
			{
				return null;
			}

			// get field xml
			$XML = self::xml($attributes, $options);

			// setup the field
			$field->setup($XML, $default);

			// return the field object
			return $field;
		}

		return null;
	}
}

