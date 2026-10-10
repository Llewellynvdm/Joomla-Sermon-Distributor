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
	@subpackage		HtmlView.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace JoomSermon\Component\Sermondistributor\Administrator\View\Address;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Session\Session;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\User\User;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Document\Document;
use JoomSermon\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;
use JoomSermon\Joomla\Sermondistributor\Utilities\Permitted\Actions;
use JoomSermon\Joomla\Utilities\StringHelper;
use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\Input\Input;
use Joomla\Registry\Registry;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Address Html View class
 *
 * @since  1.6
 */
#[\AllowDynamicProperties]
class HtmlView extends BaseHtmlView
{
	/**
	 * The app class
	 *
	 * @var    CMSApplicationInterface
	 * @since  5.2.1
	 */
	public CMSApplicationInterface $app;

	/**
	 * The input class
	 *
	 * @var    Input
	 * @since  5.2.1
	 */
	public Input $input;

	/**
	 * The params registry
	 *
	 * @var    Registry
	 * @since  5.2.1
	 */
	public Registry $params;

	/**
	 * The item from the model
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $item;

	/**
	 * The state object
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $state;

	/**
	 * The form from the model
	 *
	 * @var    mixed
	 * @since  3.10.11
	 */
	public mixed $form;

	/**
	 * The toolbar object
	 *
	 * @var    Toolbar
	 * @since  3.10.11
	 */
	public Toolbar $toolbar;

	/**
	 * The styles url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $styles;

	/**
	 * The scripts url array
	 *
	 * @var    array
	 * @since  5.0.0
	 */
	protected array $scripts;

	/**
	 * The actions object
	 *
	 * @var    object
	 * @since  3.10.11
	 */
	public object $canDo;

	/**
	 * The origin referral view name
	 *
	 * @var    string|null
	 * @since  3.10.11
	 */
	public ?string $ref;

	/**
	 * The origin referral item id
	 *
	 * @var    int|null
	 * @since  3.10.11
	 */
	public ?int $refid;

	/**
	 * The referral url suffix values
	 *
	 * @var    string
	 * @since  3.10.11
	 */
	public string $referral;

	/**
	 * The modal state
	 *
	 * @var    bool
	 * @since  5.2.1
	 */
	public bool $isModal;

	/**
	 * Constructor
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @since   6.0.0
	 */
	public function __construct(array $config)
	{
		if (empty($config['option']))
		{
			$config['option'] = 'com_sermondistributor';
		}

		parent::__construct($config);

		// get application
		$this->app ??= Factory::getApplication();
		// get input
		$this->input ??= method_exists($this->app, 'getInput') ? $this->app->getInput() : $this->app->input;
		// set params
		$this->params ??= method_exists($this->app, 'getParams')
			? $this->app->getParams()
			: ComponentHelper::getParams('com_sermondistributor');

		$this->useCoreUI = true;
	}

	/**
	 * Address view display method
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 * @throws \Exception
	 * @since  1.6
	 */
	public function display($tpl = null): void
	{
		// Load module values
		$model = $this->getModel();
		$this->form ??= $model->getForm();
		$this->item = $model->getItem();
		$this->styles = $model->getStyles();
		$this->scripts = $model->getScripts();
		$this->state = $model->getState();

		// get the permitted actions the current user can do.
		$this->canDo = Actions::get('address', $this->item);

		// Set the return
		$this->setReturn();

		// Set the toolbar
		if ($this->getLayout() !== 'modal')
		{
			$this->isModal = false;
			$this->addToolbar();
		}
		else
		{
			$this->isModal = true;
			$this->addModalToolbar();
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new \Exception(implode("\n", $errors), 500);
		}

		// Set the html view document stuff
		$this->_prepareDocument();

		// Display the template
		parent::display($tpl);
	}

	/**
	 * Set the redirection details.
	 *
	 * @return  void
	 * @since   5.1.4
	 */
	protected function setReturn(): void
	{
		// This [ref,refid] will be removed in JCB.v7, use only [return]
		$this->ref = $this->input->getWord('ref', null);
		$this->refid = $this->input->getInt('refid', null);
		$this->referral = '';
		if (!empty($this->refid) && !empty($this->ref))
		{
			// return to the item that referred to this item
			$this->referral = '&ref=' . (string) $this->ref . '&refid=' . (int) $this->refid;
		}
		elseif (!empty($this->ref))
		{
			// return to the list view that referred to this item
			$this->referral = '&ref=' . (string) $this->ref;
		}

		$return = $this->input->getBase64('return', null);
		if (!empty($return))
		{
			$this->referral .= '&return=' . (string) $return;
		}
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 * @throws  \Exception
	 * @since   1.6
	 */
	protected function addToolbar(): void
	{
		$this->input->set('hidemainmenu', true);
		$user = $this->getCurrentUser();
		$userId = $user->id;
		$isNew = $this->item->id == 0;

		ToolbarHelper::title( Text::_($isNew ? 'COM_SERMONDISTRIBUTOR_ADDRESS_NEW' : 'COM_SERMONDISTRIBUTOR_ADDRESS_EDIT'), 'pencil-2 article-add');
		// Built the actions for new and existing records.
		if (StringHelper::check($this->referral))
		{
			if ($this->canDo->get('address.create') && $isNew)
			{
				// We can create the record.
				ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('address.edit'))
			{
				// We can save the record.
				ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// Do not create but cancel.
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// We can close it.
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// For new records, check the create permission.
				if ($this->canDo->get('address.create'))
				{
					ToolbarHelper::apply('address.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
					ToolbarHelper::custom('address.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('address.edit'))
				{
					// We can save the new record
					ToolbarHelper::apply('address.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
					// We can save this record, but check the create permission to see
					// if we can return to make a new one.
					if ($this->canDo->get('address.create'))
					{
						ToolbarHelper::custom('address.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
					}
				}
				$canVersion = ($this->canDo->get('core.version') && $this->canDo->get('address.version'));
				if ($this->state->params->get('save_history', 1) && $this->canDo->get('address.edit') && $canVersion)
				{
					ToolbarHelper::versions('com_sermondistributor.address', $this->item->id);
				}
				if ($this->canDo->get('address.create'))
				{
					ToolbarHelper::custom('address.save2copy', 'save-copy.png', 'save-copy_f2.png', 'JTOOLBAR_SAVE_AS_COPY', false);
				}
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		ToolbarHelper::divider();
		ToolbarHelper::inlinehelp();
		// set help url for this view if found
		$this->help_url = SermondistributorHelper::getHelpUrl('address');
		if (StringHelper::check($this->help_url))
		{
			ToolbarHelper::help('COM_SERMONDISTRIBUTOR_HELP_MANAGER', false, $this->help_url);
		}
	}

	/**
	 * Add the modal toolbar.
	 *
	 * @return  void
	 * @throws  \Exception
	 * @since   5.0.0
	 */
	protected function addModalToolbar()
	{
		$this->input->set('hidemainmenu', true);
		$user = $this->getCurrentUser();
		$userId = $user->id;
		$isNew = $this->item->id == 0;

		ToolbarHelper::title( Text::_($isNew ? 'COM_SERMONDISTRIBUTOR_ADDRESS_NEW' : 'COM_SERMONDISTRIBUTOR_ADDRESS_EDIT'), 'pencil-2 article-add');
		// Built the actions for new and existing records.
		if (StringHelper::check($this->referral))
		{
			if ($this->canDo->get('address.create') && $isNew)
			{
				// We can create the record.
				ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
			}
			elseif ($this->canDo->get('address.edit'))
			{
				// We can save the record.
				ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
			}
			if ($isNew)
			{
				// Do not create but cancel.
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				// We can close it.
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CLOSE');
			}
		}
		else
		{
			if ($isNew)
			{
				// For new records, check the create permission.
				if ($this->canDo->get('address.create'))
				{
					ToolbarHelper::apply('address.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
					ToolbarHelper::custom('address.save2new', 'save-new.png', 'save-new_f2.png', 'JTOOLBAR_SAVE_AND_NEW', false);
				};
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CANCEL');
			}
			else
			{
				if ($this->canDo->get('address.edit'))
				{
					// We can save the new record
					ToolbarHelper::apply('address.apply', 'JTOOLBAR_APPLY');
					ToolbarHelper::save('address.save', 'JTOOLBAR_SAVE');
				}
				ToolbarHelper::cancel('address.cancel', 'JTOOLBAR_CLOSE');
			}
		}
	}

	/**
	 * Prepare some document related stuff.
	 *
	 * @return  void
	 * @since   1.6
	 */
	protected function _prepareDocument(): void
	{
		// Load jQuery
		Html::_('jquery.framework');
		$isNew = ($this->item->id < 1);
		// add styles
		foreach ($this->styles as $style)
		{
			Html::_('stylesheet', $style, ['version' => 'auto']);
		}
		// add scripts
		foreach ($this->scripts as $script)
		{
			Html::_('script', $script, ['version' => 'auto']);
		}
	}



	/**
	 * Sanitises a value to plain text for output in a view script.
	 *
	 * @param   mixed  $var     The output to escape.
	 * @param   bool   $shorten The switch to shorten.
	 * @param   int    $length  The shorting length.
	 *
	 * @return  mixed  The value as plain text.
	 * @since   1.6
	 */
	public function sanitize($var, bool $shorten = true, int $length = 30)
	{
		if (!is_string($var))
		{
			return $var;
		}

		return StringHelper::sanitize($var, $this->_charset ?? 'UTF-8', $shorten, $length);
	}
}
