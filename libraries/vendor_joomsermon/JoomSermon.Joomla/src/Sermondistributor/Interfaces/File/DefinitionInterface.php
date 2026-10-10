<?php

/***[JCBGUI.power.licensing_template.1238.$$$$]***/
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

namespace JoomSermon\Joomla\Sermondistributor\Interfaces\File;


use JoomSermon\Joomla\Interfaces\File\DefinitionInterface as ExtendingDefinitionInterface;


/**
 * File Definition (Database + Filesystem Hybrid).
 * 
 * This class extends the behaviour of the original temporary-file Definition
 *    by supporting database-driven fields while preserving the interface and
 *    behaviour of the base Definition class.
 * 
 * @since  5.1.4
 */
interface DefinitionInterface extends ExtendingDefinitionInterface
{

/***[JCBGUI.power.main_class_code.1238.$$$$]***/
	/**
	 * Get file type identifier.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function fileType(): string;

	/**
	 * Get entity type.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function entityType(): string;

	/**
	 * Get entity reference.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function entity(): string;

	/**
	 * Get access level.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function access(): int;

	/**
	 * Get Globionic GUID.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function guid(): string;

	/**
	 * Get creator ID.
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function createdBy(): int;/***[/JCBGUI$$$$]***/

}

