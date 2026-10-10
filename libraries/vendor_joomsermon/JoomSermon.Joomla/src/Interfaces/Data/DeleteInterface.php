<?php

/***[JCBGUI.power.licensing_template.750.$$$$]***/
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
 * Data Delete
 * 
 * @since 3.2.2
 */
interface DeleteInterface
{

/***[JCBGUI.power.main_class_code.750.$$$$]***/
	/**
	 * Set the current active table
	 *
	 * @param string|null $table The table that should be active
	 *
	 * @return self
	 * @since 3.2.2
	 */
	public function table(?string $table): self;

	/**
	 * Delete all items in the database that match these conditions
	 *
	 * @param   array    $conditions    Conditions by which to delete the data in database [array of arrays (key => value)]
	 *
	 * @return  bool
	 * @since   3.2.2
	 **/
	public function items(array $conditions): bool;

	/**
	 * Truncate a table
	 *
	 * @param   string|null   $table    The table that should be truncated
	 *
	 * @return  void
	 * @since   3.2.2
	 **/
	public function truncate(): void;

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	public function getTable(): string;/***[/JCBGUI$$$$]***/

}

