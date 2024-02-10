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

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;
use VDM\Joomla\Utilities\StringHelper;
use VDM\Joomla\Utilities\ArrayHelper as UtilitiesArrayHelper;

/**
 * Sermondistributor Component Base Controller
 */
class SermondistributorController extends BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param   boolean  $cachable   If true, the view output will be cached.
	 * @param   boolean  $urlparams  An array of safe URL parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return  JController  This object to support chaining.
	 *
	 */
	function display($cachable = false, $urlparams = false)
	{
		// set default view if not set
		$view          = $this->input->getCmd('view', 'preachers');
		$this->input->set('view', $view);
		$isEdit        = $this->checkEditView($view);
		$layout        = $this->input->get('layout', null, 'WORD');
		$id            = $this->input->getInt('id');
		// $cachable    = true; (TODO) working on a fix [gh-238](https://github.com/vdm-io/Joomla-Component-Builder/issues/238)

		// insure that the view is not cashable if edit view or if user is logged in
		$user = Factory::getUser();
		if ($user->get('id') || $isEdit)
		{
			$cachable = false;
		}

		// Check for edit form.
		if($isEdit)
		{
			if ($layout == 'edit' && !$this->checkEditId('com_sermondistributor.edit.'.$view, $id))
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
					// normal redirect back to the list default site view
					$this->setRedirect(Route::_('index.php?option=com_sermondistributor&view=preachers', false));
				}
				return false;
			}
		}

		// we may need to make this more dynamic in the future. (TODO)
		$safeurlparams = array(
			'catid' => 'INT',
			'id' => 'INT',
			'cid' => 'ARRAY',
			'year' => 'INT',
			'month' => 'INT',
			'limit' => 'UINT',
			'limitstart' => 'UINT',
			'showall' => 'INT',
			'return' => 'BASE64',
			'filter' => 'STRING',
			'filter_order' => 'CMD',
			'filter_order_Dir' => 'CMD',
			'filter-search' => 'STRING',
			'print' => 'BOOLEAN',
			'lang' => 'CMD',
			'Itemid' => 'INT');

		// should these not merge?
		if (UtilitiesArrayHelper::check($urlparams))
		{
			$safeurlparams = UtilitiesArrayHelper::merge(array($urlparams, $safeurlparams));
		}

		return parent::display($cachable, $safeurlparams);
	}

	protected function checkEditView($view)
	{
		if (StringHelper::check($view))
		{
			$views = array(

				);
			// check if this is a edit view
			if (in_array($view,$views))
			{
				return true;
			}
		}
		return false;
	}
}
