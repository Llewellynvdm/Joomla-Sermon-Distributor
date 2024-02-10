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
	@subpackage		controller.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;
use VDM\Joomla\Utilities\StringHelper;

/**
 * General Controller of Sermondistributor component
 */
class SermondistributorController extends BaseController
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 *
	 * @since   3.0
	 */
	public function __construct($config = [])
	{
		// set the default view
		$config['default_view'] = 'sermondistributor';

		parent::__construct($config);
	}

	/**
	 * display task
	 *
	 * @return void
	 */
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		$view      = $this->input->getCmd('view', 'sermondistributor');
		$data      = $this->getViewRelation($view);
		$layout    = $this->input->get('layout', null, 'WORD');
		$id        = $this->input->getInt('id');

		// Check for edit form.
		if(UtilitiesArrayHelper::check($data))
		{
			if ($data['edit'] && $layout == 'edit' && !$this->checkEditId('com_sermondistributor.edit.'.$data['view'], $id))
			{
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
				$this->setMessage($this->getError(), 'error');
				// check if item was opend from other then its own list view
				$ref     = $this->input->getCmd('ref', 0);
				$refid   = $this->input->getInt('refid', 0);
				// set redirect
				if ($refid > 0 && StringHelper::check($ref))
				{
					// redirect to item of ref
					$this->setRedirect(Route::_('index.php?option=com_sermondistributor&view='.(string)$ref.'&layout=edit&id='.(int)$refid, false));
				}
				elseif (StringHelper::check($ref))
				{

					// redirect to ref
					$this->setRedirect(Route::_('index.php?option=com_sermondistributor&view='.(string)$ref, false));
				}
				else
				{
					// normal redirect back to the list view
					$this->setRedirect(Route::_('index.php?option=com_sermondistributor&view='.$data['views'], false));
				}

				return false;
			}
		}

		return parent::display($cachable, $urlparams);
	}

	protected function getViewRelation($view)
	{
		// check the we have a value
		if (StringHelper::check($view))
		{
			// the view relationships
			$views = array(
				'preacher' => 'preachers',
				'sermon' => 'sermons',
				'series' => 'series_list',
				'statistic' => 'statistics',
				'external_source' => 'external_sources',
				'local_listing' => 'local_listings',
				'help_document' => 'help_documents'
					);
			// check if this is a list view
			if (in_array($view, $views))
			{
				// this is a list view
				return array('edit' => false, 'view' => array_search($view,$views), 'views' => $view);
			}
			// check if it is an edit view
			elseif (array_key_exists($view, $views))
			{
				// this is a edit view
				return array('edit' => true, 'view' => $view, 'views' => $views[$view]);
			}
		}
		return false;
	}
}
