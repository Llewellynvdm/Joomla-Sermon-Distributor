<?php

/***[JCBGUI.power.licensing_template.1232.$$$$]***/
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

namespace JoomSermon\Joomla\Interfaces\File;


/**
 * File Definition Interface
 * 
 * This class represents a **single physical file** on disk and exposes
 * a consistent and immutable API for downstream usage.
 * 
 * It is not:
 * - a database record
 * - a file manager
 * - a transport layer
 * 
 * It **is**:
 * - a file identity
 * - a metadata container
 * - a filesystem reference
 * 
 * Instances of this class are constructed exclusively from upload
 * results produced by the a File Agent class.
 * 
 * @since  5.1.4
 */
interface DefinitionInterface
{

/***[JCBGUI.power.main_class_code.1232.$$$$]***/
	/**
	 * Get original client filename.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function name(): string;

	/**
	 * Get filesystem filename.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function fileName(): string;

	/**
	 * Get file extension.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function extension(): string;

	/**
	 * Get random naming fragment (if used).
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function random(): string;

	/**
	 * Get file size (bytes).
	 *
	 * @return int
	 * @since  5.1.4
	 */
	public function size(): int;

	/**
	 * Get MIME type.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function mime(): string;

	/**
	 * Get absolute file path.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function filePath(): string;

	/**
	 * Export file information as array.
	 *
	 * @return array<string,mixed>
	 * @since  5.1.4
	 */
	public function toArray(): array;/***[/JCBGUI$$$$]***/

}

