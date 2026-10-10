<?php

/***[JCBGUI.power.licensing_template.756.$$$$]***/
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



/***[JCBGUI.power.head.756.$$$$]***/
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Model\Load;
use JoomSermon\Joomla\Model\Upsert;


/**
 * Model Service Provider
 * 
 * @since 3.2.0
 */
class Model implements ServiceProviderInterface
{

/***[JCBGUI.power.main_class_code.756.$$$$]***/
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  void
	 * @since 3.2.0
	 */
	public function register(Container $container)
	{
		$container->alias(Load::class, 'Model.Load')
			->share('Model.Load', [$this, 'getLoad'], true);

		$container->alias(Upsert::class, 'Model.Upsert')
			->share('Model.Upsert', [$this, 'getUpsert'], true);
	}

	/**
	 * Get The Load Class.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Load
	 * @since 3.2.0
	 */
	public function getLoad(Container $container): Load
	{
		return new Load(
			$container->get('Table')
		);
	}

	/**
	 * Get The Upsert Class.
	 *
	 * @param   Container  $container  The DI container.
	 *
	 * @return  Upsert
	 * @since 3.2.0
	 */
	public function getUpsert(Container $container): Upsert
	{
		return new Upsert(
			$container->get('Table')
		);
	}/***[/JCBGUI$$$$]***/

}

