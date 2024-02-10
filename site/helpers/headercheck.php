<?php
/*-------------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
 ____                                                  ____                 __               __               __
/\  _`\                                               /\  _`\   __         /\ \__         __/\ \             /\ \__
\ \,\L\_\     __   _ __    ___ ___     ___     ___    \ \ \/\ \/\_\    ____\ \ ,_\  _ __ /\_\ \ \____  __  __\ \ ,_\   ___   _ __
 \/_\__ \   /'__`\/\`'__\/' __` __`\  / __`\ /' _ `\   \ \ \ \ \/\ \  /',__\\ \ \/ /\`'__\/\ \ \ '__`\/\ \/\ \\ \ \/  / __`\/\`'__\
   /\ \L\ \/\  __/\ \ \/ /\ \/\ \/\ \/\ \L\ \/\ \/\ \   \ \ \_\ \ \ \/\__, `\\ \ \_\ \ \/ \ \ \ \ \L\ \ \ \_\ \\ \ \_/\ \L\ \ \ \/
   \ `\____\ \____\\ \_\ \ \_\ \_\ \_\ \____/\ \_\ \_\   \ \____/\ \_\/\____/ \ \__\\ \_\  \ \_\ \_,__/\ \____/ \ \__\ \____/\ \_\
    \/_____/\/____/ \/_/  \/_/\/_/\/_/\/___/  \/_/\/_/    \/___/  \/_/\/___/   \/__/ \/_/   \/_/\/___/  \/___/   \/__/\/___/  \/_/

/------------------------------------------------------------------------------------------------------------------------------------/

	@version		3.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		headercheck.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Factory;

class sermondistributorHeaderCheck
{
	protected $document = null;
	protected $app = null;

	function js_loaded($script_name)
	{
		// UIkit check point
		if (strpos($script_name,'uikit') !== false)
		{
			if (!$this->app)
			{
				$this->app = Factory::getApplication();
			}

			$getTemplateName = $this->app->getTemplate('template')->template;
			if (strpos($getTemplateName,'yoo') !== false)
			{
				return true;
			}
		}

		if (!$this->document)
		{
			$this->document = Factory::getDocument();
		}

		$head_data = $this->document->getHeadData();
		foreach (array_keys($head_data['scripts']) as $script)
		{
			if (stristr($script, $script_name))
			{
				return true;
			}
		}

		return false;
	}

	function css_loaded($script_name)
	{
		// UIkit check point
		if (strpos($script_name,'uikit') !== false)
		{
			if (!$this->app)
			{
				$this->app = Factory::getApplication();
			}

			$getTemplateName = $this->app->getTemplate('template')->template;
			if (strpos($getTemplateName,'yoo') !== false)
			{
				return true;
			}
		}

		if (!$this->document)
		{
			$this->document = Factory::getDocument();
		}

		$head_data = $this->document->getHeadData();
		foreach (array_keys($head_data['styleSheets']) as $script)
		{
			if (stristr($script, $script_name))
			{
				return true;
			}
		}

		return false;
	}
}