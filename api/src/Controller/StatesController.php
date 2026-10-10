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
	@subpackage		StatesController.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Api\Controller;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\MVC\Controller\ApiController;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * States Api Controller
 *
 * The read-only list resource of the states view. The item resource
 * of the state view carries the create, update and delete tasks.
 *
 * @since  4.0.0
 */
class StatesController extends ApiController
{
	/**
	 * The content type of the item.
	 *
	 * @var    string
	 * @since  4.0.0
	 */
	protected $contentType = 'states';

	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  3.0
	 */
	protected $default_view = 'states';

	/**
	 * Method to get a model object, loading it if required.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  \Joomla\CMS\MVC\Model\BaseDatabaseModel|boolean  Model object on success; otherwise false on failure.
	 *
	 * @since   4.0.0
	 */
	public function getModel($name = '', $prefix = '', $config = [])
	{
		// The controller role selects its explicit native model.
		$name = 'states';

		// The API carries no request state for the model, as the form controller does not:
		// the id a save sets must never be replaced by a later read of the request.
		if (!array_key_exists('ignore_request', $config))
		{
			$config['ignore_request'] = true;
		}

		return parent::getModel($name, $prefix, $config);
	}

	/**
	 * Basic display of a list view
	 *
	 * @return  static  A \JControllerLegacy object to support chaining.
	 *
	 * @since   4.0.0
	 */
	public function displayList()
	{
		// Map the request filters onto the list model state.
		$filters = $this->input->get('filter', [], 'array');
		$this->modelState->set('filter.search', $this->cleanFilter($filters['search'] ?? ''));
		$this->modelState->set('filter.published', $this->cleanFilter($filters['published'] ?? ''));

		if (isset($filters['access']))
		{
			$this->modelState->set('filter.access', $this->cleanFilter($filters['access']));
		}

		if (isset($filters['country']))
		{
			$this->modelState->set('filter.country', $this->cleanFilter($filters['country']));
		}

		// Map the requested ordering onto the list model state.
		$list = $this->input->get('list', [], 'array');
		$ordering = [
			'id' => 'a.id',
			'published' => 'a.published',
			'ordering' => 'a.ordering',
			'created_by' => 'a.created_by',
			'modified_by' => 'a.modified_by',
			'access' => 'a.access',
			'name' => 'a.name',
			'country' => 'g.name',
			'type' => 'a.type',
		];

		if (isset($list['ordering'], $ordering[$list['ordering']]))
		{
			$this->modelState->set('list.ordering', $ordering[$list['ordering']]);
		}

		if (isset($list['direction']) && in_array(strtolower((string) $list['direction']), ['asc', 'desc'], true))
		{
			$this->modelState->set('list.direction', strtolower((string) $list['direction']));
		}

		return parent::displayList();
	}

	/**
	 * The list resource does not serve one item.
	 *
	 * @param   integer  $id  The primary key to display.
	 *
	 * @return  static  A \JControllerLegacy object to support chaining.
	 *
	 * @throws  \RuntimeException
	 * @since   4.0.0
	 */
	public function displayItem($id = null)
	{
		throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 405);
	}

	/**
	 * The list resource is read-only.
	 *
	 * @return  void
	 *
	 * @throws  \RuntimeException
	 * @since   4.0.0
	 */
	public function add()
	{
		throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 405);
	}

	/**
	 * The list resource is read-only.
	 *
	 * @return  static  A \JControllerLegacy object to support chaining.
	 *
	 * @throws  \RuntimeException
	 * @since   4.0.0
	 */
	public function edit()
	{
		throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 405);
	}

	/**
	 * The list resource is read-only.
	 *
	 * @param   integer  $id  The primary key to delete item.
	 *
	 * @return  void
	 *
	 * @throws  \RuntimeException
	 * @since   4.0.0
	 */
	public function delete($id = null)
	{
		throw new \RuntimeException(Text::_('JLIB_APPLICATION_ERROR_ACCESS_FORBIDDEN'), 405);
	}

	/**
	 * Clean one request filter value, or each value of a multi select filter.
	 *
	 * @param   mixed  $value  The request value.
	 *
	 * @return  mixed  The clean string, or the array of clean strings.
	 *
	 * @since   4.0.0
	 */
	protected function cleanFilter($value)
	{
		$filter = InputFilter::getInstance();

		if (is_array($value))
		{
			$clean = [];

			foreach ($value as $one)
			{
				$clean[] = $filter->clean($one, 'STRING');
			}

			return $clean;
		}

		return $filter->clean($value, 'STRING');
	}
}
