<?php

/***[JCBGUI.power.licensing_template.1231.$$$$]***/
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
 * File Type Definition Interface
 * 
 * This class represents an **immutable file handling blueprint** used by the file
 * intake pipeline. It defines:
 * 
 * - The HTML input field responsible for uploads
 * - Allowed file formats
 * - Target filesystem location
 * - Filter engine rule
 * - Image crop definitions (if applicable)
 * 
 * Instances of this class are designed to be:
 * 
 * - Safe to reuse
 * - Immutable after construction
 * - Free of any database concerns
 * - Serializable-friendly
 * 
 * It replaces the older Type database architecture with a deterministic,
 * configuration-based model.
 * 
 * @since  5.1.4
 */
interface TypeDefinitionInterface
{

/***[JCBGUI.power.main_class_code.1231.$$$$]***/
	/**
	 * Get the upload field name.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function field(): string;

	/**
	 * Get the defined file type.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function type(): string;

	/**
	 * Get the upload filter mode.
	 *
	 * @return string|null
	 * @since  5.1.4
	 */
	public function filter(): ?string;

	/**
	 * Get the target filesystem path.
	 *
	 * @return string
	 * @since  5.1.4
	 */
	public function path(): string;

	/**
	 * Get the allowed file extensions.
	 *
	 * @return array<int,string>
	 * @since  5.1.4
	 */
	public function formats(): array;

	/**
	 * Export the full type definition as an associative array.
	 *
	 * This returns the exact structure used by the constructor,
	 * ensuring it can be safely stored, serialized, or rebuilt.
	 *
	 * @return array  The full configuration map.
	 * @since  5.1.4
	 */
	public function toArray(): array;/***[/JCBGUI$$$$]***/

}

