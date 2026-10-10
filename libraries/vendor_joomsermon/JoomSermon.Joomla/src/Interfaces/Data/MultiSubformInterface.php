<?php

/***[JCBGUI.power.licensing_template.765.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2022
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\Interfaces\Data;


/**
 * Data Multi Subform Interface
 * 
 * @since 3.2.2
 */
interface MultiSubformInterface
{

/***[JCBGUI.power.main_class_code.765.$$$$]***/
	/**
	 * Get a subform items
	 *
	 * @param array   $getMap  The the map to get the subfrom data
	 *
	 *     Example:
	 *        $getMap = [
	 *        	'_core' => [
	 *        		'table' =>'data',
	 *        		'linkValue' => $item->guid ?? '',
	 *        		'linkKey' => 'look',
	 *        		'field' => 'data',
	 *        		'get' => ['guid','email','image','mobile_phone','website','dateofbirth']
	 *        	],
	 *        	'countries' => [
	 *        		'table' =>'data_country',
	 *        		'linkValue' => 'data:guid', // coretable:fieldname
	 *        		'linkKey' => 'data',
	 *        		'get' => ['guid','country','currency']
	 *        	]
	 *        ];
	 *
	 * @return array|null   The subform
	 * @since 3.2.2
	 */
	public function get(array $getMap): ?array;

	/**
	 * Set a subform items
	 *
	 * @param mixed   $items    The list of items from the subform to set
	 * @param array   $setMap   The the map to set the subfrom data
	 *
	 *     Example:
	 *        $items,
	 *        $setMap = [
	 *        	'_core' => [
	 *        		'table' =>'data',
	 *        		'indexKey' => 'guid',
	 *        		'linkKey' => 'look',
	 *        		'linkValue' => $data['guid'] ?? ''
	 *        	],
	 *        	'countries' => [
	 *        		'table' =>'data_country',
	 *        		'indexKey' => 'guid',
	 *        		'linkKey' => 'data',
	 *        		'linkValue' => 'data:guid' // coretable:fieldname
	 *        	]
	 *        ];
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function set(mixed $items, array $setMap): bool;/***[/JCBGUI$$$$]***/

}

