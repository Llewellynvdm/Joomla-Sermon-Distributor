<?php

/***[JCBGUI.power.licensing_template.126.$$$$]***/
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



/***[JCBGUI.power.head.126.$$$$]***/
use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface as JoomlaDatabase;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\Component\Helper;
use JoomSermon\Joomla\Database\QuoteTrait;


/**
 * Database
 * 
 * @since 3.2.0
 */
abstract class Database
{

/***[JCBGUI.power.main_class_code.126.$$$$]***/
	/**
	 * Function to quote values
	 *
	 * @since 5.1.1
	 */
	use QuoteTrait;

	/**
	 * Database object to query local DB
	 *
	 * @var JoomlaDatabase
	 * @since 3.2.0
	 */
	protected JoomlaDatabase $db;

	/**
	 * Current component code name
	 *
	 * @var     string
	 * @since 5.1.1
	 */
	protected string $componentCode;

	/**
	 * Core Component Table Name
	 *
	 * @var   string
	 * @since 3.2.0
	 */
	protected string $table;

	/**
	 * Constructor
	 *
	 * @throws \Exception
	 * @since 3.2.0
	 */
	public function __construct(?JoomlaDatabase $db = null)
	{
		$this->db = $db ?: Factory::getContainer()->get(JoomlaDatabase::class);

		$this->componentCode = Helper::getCode();
		$this->table = '#__' . $this->componentCode;
	}

	/**
	 * Set a table name, adding the
	 *     core component as needed
	 *
	 * @param   string  $table   The table string
	 *
	 * @return  string
	 * @since   3.2.0
	 **/
	protected function getTable(string $table): string
	{
		if (strpos($table, '#__') === false)
		{
			return $this->table . '_' . $table;
		}

		return $table;
	}/***[/JCBGUI$$$$]***/

}

