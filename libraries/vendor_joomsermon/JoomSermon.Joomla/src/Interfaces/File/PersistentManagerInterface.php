<?php

/***[JCBGUI.power.licensing_template.1234.$$$$]***/
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


use JoomSermon\Joomla\Sermondistributor\Interfaces\File\DefinitionInterface as File;


/**
 * Persistent File Manager Interface
 * 
 * @since  5.1.4
 */
interface PersistentManagerInterface
{

/***[JCBGUI.power.main_class_code.1234.$$$$]***/
	/**
	 * Upload a file, of a given file type and link it to an entity.
	 *
	 * @param string $guid    The file type guid
	 * @param string $entity  The entity guid
	 * @param string $target  The target entity name
	 *
	 * @return void
	 * @throws \InvalidArgumentException If the file type is not valid.
	 * @throws \RuntimeException If there is an error during file upload.
	 * @since 5.0.2
	 */
	public function upload(string $guid, string $entity, string $target): void;

	/**
	 * Get the file definition
	 *
	 * @param string $guid The file guid
	 *
	 * @return File|null
	 * @since 5.1.4
	 */
	public function definition(string $guid): ?File;

	/**
	 * Get the file definition as array
	 *
	 * @param string $guid The file guid
	 *
	 * @return array|null
	 * @since 5.0.2
	 *
	 * @deprecated 5.1.4 Use $this->definition(...); 
	 * @removal x.2  (means 4.2, 5.2 , 6.2 if JCB)
	 */
	public function download(string $guid): ?array;

	/**
	 * Delete a file.
	 *
	 * @param string $guid  The file guid
	 *
	 * @return void
	 * @since 5.0.2
	 */
	public function delete(string $guid): void;

	/**
	 * Set the current active table
	 *
	 * @param string $table The table that should be active
	 *
	 * @return self
	 * @since  5.0.2
	 */
	public function table(string $table): self;

	/**
	 * Get the current active table
	 *
	 * @return  string
	 * @since   5.0.2
	 */
	public function getTable(): string;/***[/JCBGUI$$$$]***/

}

