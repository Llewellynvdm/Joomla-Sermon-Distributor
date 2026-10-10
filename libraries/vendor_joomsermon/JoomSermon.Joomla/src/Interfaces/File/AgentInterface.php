<?php

/***[JCBGUI.power.licensing_template.1266.$$$$]***/
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


use JoomSermon\Joomla\Interfaces\File\DefinitionInterface as Definition;
use JoomSermon\Joomla\Interfaces\File\TypeDefinitionInterface as Type;


/**
 * A Stateless file Agent responsible for:
 * 
 *   - Receiving uploaded files
 *   - Renaming them using deterministic GUID identity
 *   - Moving them into final filesystem locations
 *   - Returning file objects
 *   - Deleting files on demand
 * 
 * @since  5.1.4
 */
interface AgentInterface
{

/***[JCBGUI.power.main_class_code.1266.$$$$]***/
	/**
	 * Bind a file-type definition to the drop operation.
	 *
	 * A file type **must** be assigned before calling `get()`.
	 *
	 * @param  Type  $type  Upload blueprint.
	 *
	 * @return self
	 * @since  5.1.4
	 */
	public function type(Type $type): self;

	/**
	 * Get (upload), rename, and finalize file(s).
	 *
	 * Workflow:
	 *
	 * 1) Validate configuration
	 * 2) Retrieve uploaded stream
	 * 3) Generate deterministic GUID name
	 * 4) Move into target directory
	 * 6) Model result and return object
	 *
	 * @return Definition  Generated file details.
	 *
	 * @throws \RuntimeException  If no type was defined.
	 * @throws \RuntimeException  If upload fails.
	 *
	 * @since  5.1.4
	 */
	public function get(): Definition;

	/**
	 * Delete a file from disk.
	 *
	 * This is intentionally shallow:
	 * - No permissions
	 * - No ownership checks
	 * - No logging
	 *
	 * The caller owns safety.
	 *
	 * @param  string  $path  Absolute file path.
	 *
	 * @return bool  TRUE if removed, FALSE if missing or failed.
	 * @since  5.1.4
	 */
	public function delete(string $path): bool;/***[/JCBGUI$$$$]***/

}

