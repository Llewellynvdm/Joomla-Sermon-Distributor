<?php

/***[JCBGUI.power.licensing_template.767.$$$$]***/
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

namespace JoomSermon\Joomla\Abstraction;



/***[JCBGUI.power.head.767.$$$$]***/
use Joomla\CMS\Factory;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Interfaces\SchemaInterface as Schema;
use JoomSermon\Joomla\Interfaces\TableInterface as Table;
use JoomSermon\Joomla\Utilities\ClassHelper;
use JoomSermon\Joomla\Interfaces\SchemaCheckerInterface;


/**
 * Schema Checker
 * 
 * @since 3.2.2
 */
abstract class SchemaChecker implements SchemaCheckerInterface
{

/***[JCBGUI.power.main_class_code.767.$$$$]***/
	/**
	 * The Table Class.
	 *
	 * @var   Table|null
	 * @since 3.2.2
	 */
	protected ?Table $table;

	/**
	 * The Schema Class.
	 *
	 * @var   Schema|null
	 * @since 3.2.2
	 */
	protected ?Schema $schema;

	/**
	 * Application object.
	 *
	 * @since 3.2.2
	 **/
	protected  $app;

	/**
	 * Constructor.
	 *
	 * @param Schema|null                    $schema   The Schema Class.
	 * @param Table|null                     $table    The Table Class.
	 * @param                                      $app      The app object.
	 *
	 * @throws \Exception
	 * @since 3.2.2
	 */
	public function __construct(?Schema $schema = null, ?Table $table = null, $app = null)
	{
		$this->schema = $schema;
		$this->table = $table;
		$this->app = $app ?: Factory::getApplication();

		// Validate classes are set
		// Since this class is often called from outside a container
		$this->initializeInstances();
		// I don't care! I have more important thing to do, maybe later... (last updated in 1983 ;)
	}

	/**
	 * Make sure that the database schema is up-to-date.
	 *
	 * @return void
	 * @since 3.2.2
	 */
	public function run(): void
	{
		if ($this->schema === null)
		{
			$this->app->enqueueMessage('We failed to find/load the Schema class', 'warning');
			return;
		}

		// try to load the update the tables with the schema class
		try
		{
			$messages = $this->schema->update();
		}
		catch (\Exception $e)
		{
			$this->app->enqueueMessage($e->getMessage(), 'warning');
			return;
		}

		foreach ($messages as $message)
		{
			$this->app->enqueueMessage($message, 'message');
		}
	}

	/**
	 * Initialize the needed class instances if needed
	 *
	 * @return void
	 * @since 3.2.2
	 */
	protected function initializeInstances(): void
	{
		if ($this->schema !== null)
		{
			return;
		}

		if ($this->table === null)
		{
			$this->setTableInstance();
		}

		$this->setSchemaInstance();
	}

	/**
	 * set the schema class instance
	 *
	 * @return void
	 * @since 3.2.2
	 */
	protected function setSchemaInstance(): void
	{
		// make sure the class is loaded
		if (ClassHelper::exists(
			$this->getSchemaClass(), $this->getCode(), $this->getPowerPath()
		))
		{
			// instantiate the schema class
			$this->schema = new ($this->getSchemaClass())($this->table);
		}
	}

	/**
	 * set the table class instance
	 *
	 * @return void
	 * @since 3.2.2
	 */
	protected function setTableInstance(): void
	{
		// make sure the class is loaded
		if (ClassHelper::exists(
			$this->getTableClass(), $this->getCode(), $this->getPowerPath()
		))
		{
			// instantiate the table class
			$this->table = new ($this->getTableClass())();
		}
	}

	/**
	 * Get the targeted component code
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	abstract protected function getCode(): string;

	/**
	 * Get the targeted component power path
	 *
	 * @return  string
	 * @since 3.2.2
	 */
	abstract protected function getPowerPath(): string;

	/**
	 * Get the fully qualified name of the schema class.
	 *
	 * @return string
	 * @since 3.2.2
	 */
	abstract protected function getSchemaClass(): string;

	/**
	 * Get the fully qualified name of the table class.
	 *
	 * @return string
	 * @since 3.2.2
	 */
	abstract protected function getTableClass(): string;/***[/JCBGUI$$$$]***/

}

