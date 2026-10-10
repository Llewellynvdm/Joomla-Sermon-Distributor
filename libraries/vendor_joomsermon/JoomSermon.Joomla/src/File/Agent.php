<?php

/***[JCBGUI.power.licensing_template.1228.$$$$]***/
/**
 * @package    Joomla.Component.Builder
 *
 * @created    4th September, 2020
 * @author     Llewellyn van der Merwe <https://dev.vdm.io>
 * @git        Joomla Component Builder <https://git.vdm.dev/joomla/Component-Builder>
 * @copyright  Copyright (C) 2015 Vast Development Method. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
/***[/JCBGUI$$$$]***/

namespace JoomSermon\Joomla\File;



/***[JCBGUI.power.head.1228.$$$$]***/
use Joomla\Filesystem\File;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\File\HandlerInterface as Handler;
use JoomSermon\Joomla\File\Definition;
use JoomSermon\Joomla\Interfaces\File\TypeDefinitionInterface as Type;
use JoomSermon\Joomla\Utilities\MimeHelper;
use JoomSermon\Joomla\Interfaces\File\AgentInterface;


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
class Agent implements AgentInterface
{

/***[JCBGUI.power.main_class_code.1228.$$$$]***/
	/**
	 * Upload and validation engine.
	 *
	 * @var   Handler
	 * @since 5.1.4
	 */
	protected Handler $handler;

	/**
	 * Active file-type definition.
	 *
	 * @var   Type|null
	 * @since 5.1.4
	 */
	protected ?Type $type = null;

	/**
	 * Constructor.
	 *
	 * @param  Handler  $handler  Upload validation and intake engine.
	 *
	 * @since  5.1.4
	 */
	public function __construct(Handler $handler)
	{
		$this->handler = $handler;
	}

	/**
	 * Bind a file-type definition to the drop operation.
	 *
	 * A file type **must** be assigned before calling `upload()`.
	 *
	 * @param  Type  $type  Upload blueprint.
	 *
	 * @return self
	 * @since  5.1.4
	 */
	public function type(Type $type): self
	{
		$this->type = $type;

		return $this;
	}

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
	public function get(): Definition
	{
		if ($this->type === null)
		{
			throw new \RuntimeException('Agent::get() called without File Type Definition loaded.');
		}

		$details = $this->handler
			->setEnqueueError(false)
			->setLegalFormats($this->type->formats())
			->getFile(
				$this->type->field(),
				$this->type->type(),
				$this->type->filter(),
				$this->type->path()
			);

		if ($details === null)
		{
			throw new \RuntimeException($this->handler->getErrors());
		}

		// reset the file type
		$this->type = null;

		return new Definition($details);
	}

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
	public function delete(string $path): bool
	{
		return $this->handler->removeFile($path);
	}/***[/JCBGUI$$$$]***/

}

