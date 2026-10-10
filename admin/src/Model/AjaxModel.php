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

	@version		6.0.0
	@created		9th July, 2026
	@package		Sermon Distributor
	@subpackage		AjaxModel.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\Model;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\User\User;
use Joomla\Utilities\ArrayHelper;
use Joomla\Input\Input;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Utilities\GuidHelper;
use JoomSermon\Joomla\Sermondistributor\File\Factory as FileFactory;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Sermondistributor Ajax List Model
 *
 * @since  1.6
 */
class AjaxModel extends ListModel
{
	/**
	 * The component params.
	 *
	 * @var   Registry
	 * @since 3.2.0
	 */
	protected Registry $app_params;

	/**
	 * The application object.
	 *
	 * @var   CMSApplicationInterface  The application instance.
	 * @since 3.2.0
	 */
	protected CMSApplicationInterface $app;

	/**
	 * Constructor
	 *
	 * @param   array                 $config   An array of configuration options (name, state, dbo, table_path, ignore_request).
	 * @param   ?MVCFactoryInterface  $factory  The factory.
	 *
	 * @since   1.6
	 * @throws  \Exception
	 */
	public function __construct($config = [], ?MVCFactoryInterface $factory = null)
	{
		parent::__construct($config, $factory);

		$this->app_params = ComponentHelper::getParams('com_sermondistributor');
		$this->app ??= Factory::getApplication();
	}

	// Used in file_type

/***[JCBGUI.admin_view.php_ajaxmethod.364.$$$$]***/
	/**
	 * Get the file type details, if it exists.
	 *
	 * @param string $guid    The file type guid
	 * @param string $target  The target entity name
	 *
	 * @return array
	 * @since 5.0.2
	 */
	public function getFileTypeDetails(string $guid, string $target): array
	{
		if (GuidHelper::valid($guid))
		{
			try
			{
				$target = base64_decode($target);
				$type = FileFactory::_('File.Type')->get($guid, $target);
			}
			catch (\Exception $error)
			{
				return ['error' => $error->getMessage()];
			}

			if ($type !== null)
			{
				return ['data' => $type];
			}
		}

		return ['error' => Text::_('COM_SERMONDISTRIBUTOR_FILE_TYPE_DETAILS_COULD_NOT_BE_FOUND')];
	}

	/**
	 * Upload a file, of a given file type and link it to an entity.
	 *
	 * @param string $guid    The file type guid
	 * @param string $entity  The entity guid
	 * @param string $target  The target entity name
	 *
	 * @return array
	 * @since 5.0.2
	 */
	public function uploadFile(string $guid, string $entity, string $target): array
	{
		if (GuidHelper::valid($guid)
			&& GuidHelper::valid($entity))
		{
			try
			{
				$target = base64_decode($target);
				FileFactory::_('File.Manager')->upload($guid, $entity, $target);
			}
			catch (\Exception $error)
			{
				return ['error' => $error->getMessage()];
			}

			return ['success' => Text::_('COM_SERMONDISTRIBUTOR_THE_FILE_WAS_SUCCESSFULLY_UPLOADED')];
		}

		return ['error' => Text::_('COM_SERMONDISTRIBUTOR_THE_FILE_FAILED_TO_UPLOAD')];
	}

	/**
	 * Delete a file of a given entity.
	 *
	 * @param string $guid    The file guid
	 *
	 * @return array
	 * @since 5.0.2
	 */
	public function deleteFile(string $guid): array
	{
		if (GuidHelper::valid($guid))
		{
			try
			{
				FileFactory::_('File.Manager')->delete($guid);
			}
			catch (\Exception $error)
			{
				return ['error' => $error->getMessage()];
			}

			return ['success' => Text::_('COM_SERMONDISTRIBUTOR_THE_FILE_WAS_SUCCESSFULLY_DELETED')];
		}

		return ['error' => Text::_('COM_SERMONDISTRIBUTOR_THE_FILE_COULD_NOT_BE_DELETED')];
	}

	/**
	 * Load the display of the files linked this entity.
	 *
	 * @param string $entity  The entity guid
	 * @param string $target  The target entity name
	 *
	 * @return array
	 * @since 5.0.2
	 */
	public function displayFiles(string $entity, string $target): array
	{
		if (GuidHelper::valid($entity))
		{
			$display = null;

			try
			{
				$target = base64_decode($target);
				$data = FileFactory::_('File.Display')->get($entity, $target);

				if ($data !== null)
				{
					$displayData =  ['data' => $data, 'entity' => $entity, 'target' => $target];
					$display = LayoutHelper::render('demolookfiledisplay', $displayData);
				}
				else
				{
					return ['data' => '<b>' . Text::sprintf('COM_SERMONDISTRIBUTOR_NO_FILES_LINKED_TO_S', $target) . '</b>'];
				}
			}
			catch (\Exception $error)
			{
				return ['error' => $error->getMessage()];
			}

			if (!empty($display))
			{
				return ['data' => $display];
			}
		}

		return ['error' => Text::_('COM_SERMONDISTRIBUTOR_THE_FILE_DISPLAY_COULD_NOT_BE_LOADED')];
	}/***[/JCBGUI$$$$]***/

}
