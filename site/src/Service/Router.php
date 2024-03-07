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

	@version		4.0.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		Router.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/
namespace TrueChristianChurch\Component\Sermondistributor\Site\Service;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Application\SiteApplication;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterView;
use Joomla\CMS\Component\Router\RouterViewConfiguration;
use Joomla\CMS\Component\Router\Rules\MenuRules;
use Joomla\CMS\Component\Router\Rules\NomenuRules;
use Joomla\CMS\Component\Router\Rules\StandardRules;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\Database\DatabaseInterface;
use Joomla\Database\ParameterType;
use Joomla\Registry\Registry;
use TrueChristianChurch\Component\Sermondistributor\Administrator\Helper\SermondistributorHelper;

// No direct access to this file
\defined('_JEXEC') or die;

/**
 * Router class for the Sermon Distributor Component
 *
 * @since  3.10
 */
class Router extends RouterView
{
	/**
	 * Flag to remove IDs
	 *
	 * @var    boolean
	 * @since  4.0.0
	 */
	protected $noIDs = false;

	/**
	 * The category factory
	 *
	 * @var    CategoryFactoryInterface
	 * @since  4.0.0
	 */
	private $categoryFactory;

	/**
	 * The category cache
	 *
	 * @var    array
	 * @since  4.0.0
	 */
	private $categoryCache = [];

	/**
	 * The db
	 *
	 * @var    DatabaseInterface
	 * @since  4.0.0
	 */
	private $db;

	/**
	 * The component params
	 *
	 * @var    Registry
	 * @since  4.0.0
	 */
	private $params;

	/**
	 * Sermondistributor Component router constructor
	 *
	 * @param   SiteApplication           $app               The application object
	 * @param   AbstractMenu              $menu              The menu object to work with
	 * @param   CategoryFactoryInterface  $categoryFactory   The category object
	 * @param   DatabaseInterface         $db                The database object
	 *
	 * @since   4.0.0
	 */
	public function __construct(
		SiteApplication $app,
		AbstractMenu $menu,
		CategoryFactoryInterface $categoryFactory,
		DatabaseInterface $db)
	{
		$this->categoryFactory = $categoryFactory;
		$this->db              = $db;
		$this->params          = ComponentHelper::getParams('com_sermondistributor');
		$this->noIDs           = (bool) $this->params->get('sef_ids', false);

		// Add the (preachers:view) router configuration
		$preachers = new RouterViewConfiguration('preachers');
		$this->registerView($preachers);

		// Add the (categories:view) router configuration
		$categories = new RouterViewConfiguration('categories');
		$this->registerView($categories);

		// Add the (serieslist:view) router configuration
		$serieslist = new RouterViewConfiguration('serieslist');
		$this->registerView($serieslist);

		// Add the (preacher:view) router configuration
		$preacher = new RouterViewConfiguration('preacher');
		$preacher->setKey('id')->setParent($preachers);
		$this->registerView($preacher);

		// Add the (category:view) router configuration
		$category = new RouterViewConfiguration('category');
		$category->setKey('id')->setParent($categories);
		$this->registerView($category);

		// Add the (series:view) router configuration
		$series = new RouterViewConfiguration('series');
		$series->setKey('id')->setParent($serieslist);
		$this->registerView($series);

		// Add the (sermon:view) router configuration
		$sermon = new RouterViewConfiguration('sermon');
		$sermon->setKey('id')->setParent($preacher, 'preacher')->setParent($series, 'series')->setParent($category, 'catid');
		$this->registerView($sermon);

		parent::__construct($app, $menu);

		$this->attachRule(new MenuRules($this));
		$this->attachRule(new StandardRules($this));
		$this->attachRule(new NomenuRules($this));
	}

	/**
	 * Method to get the segment(s) for sermon
	 *
	 * @param   string  $segment  Segment of the article to retrieve the ID for
	 * @param   array   $query    The request that is parsed right now
	 *
	 * @return  mixed   The id of this item or false
	 * @since   4.4.0
	 */
	public function getSermonId($segment, $query)
	{
		if ($this->noIDs)
		{
			$dbquery = $this->db->getQuery(true);
			$dbquery->select($this->db->quoteName('id'))
				->from($this->db->quoteName('#__sermondistributor_sermon'))
				->where(
					[
						$this->db->quoteName('alias') . ' = :alias'
					]
				)
				->bind(':alias', $segment);
			$this->db->setQuery($dbquery);

			return (int) $this->db->loadResult();
		}

		return (int) $segment;
	}

	/**
	 * Method to get the segment(s) for sermon
	 *
	 * @param   string  $id     ID of the contact to retrieve the segments for
	 * @param   array   $query  The request that is built right now
	 *
	 * @return  array|string  The segments of this item
	 * @since   4.4.0
	 */
	public function getSermonSegment($id, $query)
	{
		if (strpos($id, ':') === false)
		{
			$id = (int) $id;
			$dbquery = $this->db->getQuery(true);
			$dbquery->select($this->db->quoteName('alias'))
				->from($this->db->quoteName('#__sermondistributor_sermon'))
				->where($this->db->quoteName('id') . ' = :id')
				->bind(':id', $id, ParameterType::INTEGER);
			$this->db->setQuery($dbquery);

			$id .= ':' . $this->db->loadResult();
		}

		if ($this->noIDs)
		{
			list($void, $segment) = explode(':', $id, 2);

			return [$void => $segment];
		}

		return [(int) $id => $id];
	}
}
