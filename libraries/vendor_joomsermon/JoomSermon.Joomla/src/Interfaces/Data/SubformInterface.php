<?php

/***[JCBGUI.power.licensing_template.760.$$$$]***/
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
 * Data Subform Interface
 * 
 * @since 3.2.2
 */
interface SubformInterface
{

/***[JCBGUI.power.main_class_code.760.$$$$]***/
	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(string $table): self;

	/**
	 * Get a subform items
	 *
	 * @param string   $linkValue  The value of the link key in child table.
	 * @param string   $linkKey    The link key on which the items where linked in the child table.
	 * @param string   $field      The parent field name of the subform in the parent view.
	 * @param array    $get        The array SET of the keys of each row in the subform.
	 * @param bool     $multi      The switch to return a multiple set.
	 *
	 * @return array|null   The subform
	 * @since 3.2.2
	 */
	public function get(string $linkValue, string $linkKey, string $field, array $get, bool $multi = true): ?array;

	/**
	 * Set a subform items
	 *
	 * @param mixed    $items      The list of items from the subform to set
	 * @param string   $indexKey   The index key on which the items should be observed as it relates to insert/update/delete.
	 * @param string   $linkKey    The link key on which the items where linked in the child table.
	 * @param string   $linkValue  The value of the link key in child table.
	 *
	 * @return bool
	 * @since 3.2.2
	 */
	public function set(mixed $items, string $indexKey, string $linkKey, string $linkValue): bool;

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string;/***[/JCBGUI$$$$]***/

}

