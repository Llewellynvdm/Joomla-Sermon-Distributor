<?php

/***[JCBGUI.power.licensing_template.1233.$$$$]***/
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
 * File Handler Interface
 * 
 * @since  5.1.4
 */
interface HandlerInterface
{

/***[JCBGUI.power.main_class_code.1233.$$$$]***/
	/**
	 * Set the $useStreams property to use streams for file handling
	 *
	 * @param   bool  $useStreams  True to use streams, false otherwise.
	 *
	 * @return  self  Returns the current instance to allow for method chaining.
	 * @since   5.0.3
	 */
	public function setUseStreams(bool $useStreams): self;

	/**
	 * Set the $allowUnsafe property to allow or disallow unsafe file uploads.
	 *
	 * @param   bool  $allowUnsafe  True to allow unsafe file uploads, false otherwise.
	 *
	 * @return  self  Returns the current instance to allow for method chaining.
	 * @since   5.0.3
	 */
	public function setAllowUnsafe(bool $allowUnsafe): self;

	/**
	 * Set the $safeFileOptions property to define options for file safety checks.
	 *
	 * @param   array  $safeFileOptions  An array of options for InputFilter::isSafeFile.
	 *
	 * @return  self  Returns the current instance to allow for method chaining.
	 * @since   5.0.3
	 */
	public function setSafeFileOptions(array $safeFileOptions): self;

	/**
	 * Set the $enqueueError property to control error reporting behavior.
	 *
	 * @param   bool  $enqueueError  True to enqueue error messages, false to store them in the internal error array.
	 *
	 * @return  self  Returns the current instance to allow for method chaining.
	 * @since   5.0.3
	 */
	public function setEnqueueError(bool $enqueueError): self;

	/**
	 * Set the $legalFormats property to define legal file formats.
	 *
	 * @param   array  $legalFormats  An array of allowed file formats (e.g., ['jpg', 'png']).
	 *
	 * @return  self  Returns the current instance to allow for method chaining.
	 * @since   5.0.3
	 */
	public function setLegalFormats(array $legalFormats): self;

	/**
	 * Get a file from the input based on field name and file type, then process it.
	 *
	 * @param   string	   $field   The input field name for the file upload.
	 * @param   string	   $type	The type of file (e.g., 'image', 'document').
	 * @param   string|null  $filter  The filter to apply when uploading the file.
	 * @param   string|null  $path	The directory path where the file should be saved.
	 *
	 * @return  array|null   File details or false on failure.
	 * @since   3.0.11
	 */
	public function getFile(string $field, string $type, ?string $filter = null, ?string $path = null): ?array;

	/**
	 * Remove a previously uploaded file.
	 *
	 * This method uses the same internal removal mechanism as the uploader,
	 * ensuring consistent cleanup logic across the system.
	 *
	 * It accepts either:
	 * - a full filesystem path
	 * - or a filename resolvable by Path::clean
	 *
	 * @param  string  $path  Absolute or relative file path.
	 *
	 * @return bool  TRUE on success, FALSE on failure or missing file.
	 *
	 * @since  5.1.4
	 */
	public function removeFile(string $path): bool;

	/**
	 * Get the error messages as a string.
	 *
	 * @param  bool   $toString  The option to return errors as a string
	 *
	 * @return  string|array  Returns the error messages as a single concatenated string.
	 * @since   5.0.3
	 */
	public function getErrors(bool $toString = true): string|array;/***[/JCBGUI$$$$]***/

}

