<?php

/***[JCBGUI.power.licensing_template.732.$$$$]***/
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

namespace JoomSermon\Joomla\Sermondistributor\Table;


use JoomSermon\Joomla\Sermondistributor\Table;
use JoomSermon\Joomla\Interfaces\SchemaInterface;
use JoomSermon\Joomla\Abstraction\Schema as ExtendingSchema;


/**
 * Sermondistributor Tables Schema
 * 
 * @since 3.2.1
 */
final class Schema extends ExtendingSchema implements SchemaInterface
{

/***[JCBGUI.power.main_class_code.732.$$$$]***/
	/**
	 * Constructor.
	 *
	 * @param Table   $table   The Table Class.
	 *
	 * @since 3.2.1
	 */
	public function __construct(?Table $table = null)
	{
		$table ??= new Table;

		parent::__construct($table);
	}

	/**
	 * Get the targeted component code
	 *
	 * @return  string
	 * @since 3.2.1
	 */
	protected function getCode(): string
	{
		return 'sermondistributor';
	}/***[/JCBGUI$$$$]***/

}

