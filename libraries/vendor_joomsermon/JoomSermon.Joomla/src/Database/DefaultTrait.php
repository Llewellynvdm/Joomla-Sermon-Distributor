<?php

/***[JCBGUI.power.licensing_template.1038.$$$$]***/
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

namespace JoomSermon\Joomla\Database;


/**
 * Database Default Trait
 * 
 * @since 5.1.1
 */
trait DefaultTrait
{

/***[JCBGUI.power.main_class_code.1038.$$$$]***/
	/**
	 * Switch to set the defaults
	 *
	 * @var    bool
	 * @since  3.2.0
	 **/
	protected bool $defaults = true;

	/**
	 * Switch to prevent/allow defaults from being added.
	 *
	 * @param   bool    $trigger      toggle the defaults
	 *
	 * @return  self
	 * @since   3.2.0
	 **/
	public function defaults(bool $trigger = true): self
	{
		$this->defaults = $trigger;

		return $this;
	}/***[/JCBGUI$$$$]***/

}

