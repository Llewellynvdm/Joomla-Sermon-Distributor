<?php

/***[JCBGUI.power.licensing_template.762.$$$$]***/
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

namespace JoomSermon\Joomla\Service;



/***[JCBGUI.power.head.762.$$$$]***/
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Sermondistributor\Table as DataTable;
use JoomSermon\Joomla\Sermondistributor\Table\Schema;
use JoomSermon\Joomla\Sermondistributor\Table\Validator;


/**
 * Table Service Provider
 * 
 * @since 3.2.2
 */
class Table implements ServiceProviderInterface
{

/***[JCBGUI.power.main_class_code.762.$$$$]***/
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 * @since 3.2.2
	 */
	public function register(Container $container)
	{
		$container->alias(DataTable::class, 'Table')
			->share('Table', [$this, 'getTable'], true);

		$container->alias(Schema::class, 'Table.Schema')
			->share('Table.Schema', [$this, 'getSchema'], true);

		$container->alias(Validator::class, 'Table.Validator')
			->share('Table.Validator', [$this, 'getValidator'], true);
	}

	/**
	 * Get The Sermondistributor Data Table Class.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  DataTable
	 * @since 3.2.2
	 */
	public function getTable(Container $container): DataTable
	{
		return new DataTable();
	}

	/**
	 * Get The Schema Class.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Schema
	 * @since 3.2.2
	 */
	public function getSchema(Container $container): Schema
	{
		return new Schema(
			$container->get('Table')
		);
	}

	/**
	 * Get The Validator Class.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Validator
	 * @since 3.2.2
	 */
	public function getValidator(Container $container): Validator
	{
		return new Validator(
			$container->get('Table')
		);
	}/***[/JCBGUI$$$$]***/

}

