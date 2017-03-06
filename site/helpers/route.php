<?php
/*--------------------------------------------------------------------------------------------------------|  www.vdm.io  |------/
    __      __       _     _____                 _                                  _     __  __      _   _               _
    \ \    / /      | |   |  __ \               | |                                | |   |  \/  |    | | | |             | |
     \ \  / /_ _ ___| |_  | |  | | _____   _____| | ___  _ __  _ __ ___   ___ _ __ | |_  | \  / | ___| |_| |__   ___   __| |
      \ \/ / _` / __| __| | |  | |/ _ \ \ / / _ \ |/ _ \| '_ \| '_ ` _ \ / _ \ '_ \| __| | |\/| |/ _ \ __| '_ \ / _ \ / _` |
       \  / (_| \__ \ |_  | |__| |  __/\ V /  __/ | (_) | |_) | | | | | |  __/ | | | |_  | |  | |  __/ |_| | | | (_) | (_| |
        \/ \__,_|___/\__| |_____/ \___| \_/ \___|_|\___/| .__/|_| |_| |_|\___|_| |_|\__| |_|  |_|\___|\__|_| |_|\___/ \__,_|
                                                        | |                                                                 
                                                        |_| 				
/-------------------------------------------------------------------------------------------------------------------------------/

	@version		1.4.1
	@build			28th February, 2017
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		route.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 
	
	A sermon distributor that links to Dropbox. 
                                                             
/-----------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

// Component Helper
jimport('joomla.application.component.helper');
jimport('joomla.application.categories');

/**
 * Sermondistributor Route Helper
 **/
abstract class SermondistributorHelperRoute
{
	protected static $lookup;

	/**
	* @param int The route of the Sermon
	*/
	public static function getSermonRoute($id = 0, $catid = 0)
	{
		if ($id > 0)
		{
			// Initialize the needel array.
			$needles = array(
				'sermon'  => array((int) $id)
			);
			// Create the link
			$link = 'index.php?option=com_sermondistributor&view=sermon&id='. $id;
		}
		else
		{
			// Initialize the needel array.
			$needles = array();
			//Create the link but don't add the id.
			$link = 'index.php?option=com_sermondistributor&view=sermon';
		}
		if ($catid > 1)
		{
			$categories = JCategories::getInstance('sermondistributor.sermons');
			$category = $categories->get($catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles, 'sermon'))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	* @param int The route of the Preachers
	*/
	public static function getPreachersRoute($id = 0, $catid = 0)
	{
		if ($id > 0)
		{
			// Initialize the needel array.
			$needles = array(
				'preachers'  => array((int) $id)
			);
			// Create the link
			$link = 'index.php?option=com_sermondistributor&view=preachers&id='. $id;
		}
		else
		{
			// Initialize the needel array.
			$needles = array();
			//Create the link but don't add the id.
			$link = 'index.php?option=com_sermondistributor&view=preachers';
		}
		if ($catid > 1)
		{
			$categories = JCategories::getInstance('sermondistributor.preachers');
			$category = $categories->get($catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	* @param int The route of the Preacher
	*/
	public static function getPreacherRoute($id = 0, $catid = 0)
	{
		if ($id > 0)
		{
			// Initialize the needel array.
			$needles = array(
				'preacher'  => array((int) $id)
			);
			// Create the link
			$link = 'index.php?option=com_sermondistributor&view=preacher&id='. $id;
		}
		else
		{
			// Initialize the needel array.
			$needles = array();
			//Create the link but don't add the id.
			$link = 'index.php?option=com_sermondistributor&view=preacher';
		}
		if ($catid > 1)
		{
			$categories = JCategories::getInstance('sermondistributor.preacher');
			$category = $categories->get($catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles, 'preacher'))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	* @param int The route of the Categories
	*/
	public static function getCategoriesRoute($id = 0)
	{
		if ($id > 0)
		{
			// Initialize the needel array.
			$needles = array(
				'categories'  => array((int) $id)
			);
			// Create the link
			$link = 'index.php?option=com_sermondistributor&view=categories&id='. $id;
		}
		else
		{
			// Initialize the needel array.
			$needles = array();
			//Create the link but don't add the id.
			$link = 'index.php?option=com_sermondistributor&view=categories';
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	* @param int The route of the Category
	*/
	public static function getCategoryRoute($id = 0)
	{
		if ($id > 0)
		{
			// Initialize the needel array.
			$needles = array(
				'category'  => array((int) $id)
			);
			// Create the link
			$link = 'index.php?option=com_sermondistributor&view=category&id='. $id;
		}
		else
		{
			// Initialize the needel array.
			$needles = array();
			//Create the link but don't add the id.
			$link = 'index.php?option=com_sermondistributor&view=category';
		}

		if ($item = self::_findItem($needles, 'category'))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	* @param int The route of the Serieslist
	*/
	public static function getSerieslistRoute($id = 0, $catid = 0)
	{
		if ($id > 0)
		{
			// Initialize the needel array.
			$needles = array(
				'serieslist'  => array((int) $id)
			);
			// Create the link
			$link = 'index.php?option=com_sermondistributor&view=serieslist&id='. $id;
		}
		else
		{
			// Initialize the needel array.
			$needles = array();
			//Create the link but don't add the id.
			$link = 'index.php?option=com_sermondistributor&view=serieslist';
		}
		if ($catid > 1)
		{
			$categories = JCategories::getInstance('sermondistributor.serieslist');
			$category = $categories->get($catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	* @param int The route of the Series
	*/
	public static function getSeriesRoute($id = 0, $catid = 0)
	{
		if ($id > 0)
		{
			// Initialize the needel array.
			$needles = array(
				'series'  => array((int) $id)
			);
			// Create the link
			$link = 'index.php?option=com_sermondistributor&view=series&id='. $id;
		}
		else
		{
			// Initialize the needel array.
			$needles = array();
			//Create the link but don't add the id.
			$link = 'index.php?option=com_sermondistributor&view=series';
		}
		if ($catid > 1)
		{
			$categories = JCategories::getInstance('sermondistributor.series');
			$category = $categories->get($catid);
			if ($category)
			{
				$needles['category'] = array_reverse($category->getPath());
				$needles['categories'] = $needles['category'];
				$link .= '&catid='.$catid;
			}
		}

		if ($item = self::_findItem($needles, 'series'))
		{
			$link .= '&Itemid='.$item;
		}

		return $link;
	}

	/**
	 * Get the URL route for sermondistributor category from a category ID and language
	 *
	 * @param   mixed    $catid     The id of the items's category either an integer id or a instance of JCategoryNode
	 * @param   mixed    $language  The id of the language being used.
	 *
	 * @return  string  The link to the contact
	 *
	 * @since   1.5
	 */
	public static function getCategoryRoute_keep_for_later($catid, $language = 0)
	{
		if ($catid instanceof JCategoryNode)
		{
			$id = $catid->id;			
			$category = $catid;			 
		}
		else
		{			
			throw new Exception('First parameter must be JCategoryNode');			
		}
	
		$views = array(
			"com_sermondistributor.sermons" => "sermon");
		$view = $views[$category->extension];
       
		if ($id < 1 || !($category instanceof JCategoryNode))
		{
			$link = '';
		}
		else
		{
			//Create the link
			$link = 'index.php?option=com_sermondistributor&view='.$view.'&category='.$category->slug;
			
			$needles = array(
					$view => array($id),
					'category' => array($id)
			);
	
			if ($language && $language != "*" && JLanguageMultilang::isEnabled())
			{
				$db		= JFactory::getDbo();
				$query	= $db->getQuery(true)
					->select('a.sef AS sef')
					->select('a.lang_code AS lang_code')
					->from('#__languages AS a');
	
				$db->setQuery($query);
				$langs = $db->loadObjectList();
				foreach ($langs as $lang)
				{
					if ($language == $lang->lang_code)
					{
						$link .= '&lang='.$lang->sef;
						$needles['language'] = $language;
					}
				}
			}
	
			if ($item = self::_findItem($needles,'category'))
			{

				$link .= '&Itemid='.$item;				
			}
			else
			{
				if ($category)
				{
					$catids = array_reverse($category->getPath());
					$needles = array(
							'category' => $catids
					);
					if ($item = self::_findItem($needles,'category'))
					{
						$link .= '&Itemid='.$item;
					}
					elseif ($item = self::_findItem(null, 'category'))
					{
						$link .= '&Itemid='.$item;
					}
				}
			}
		}
		return $link;
	}	
	
	protected static function _findItem($needles = null,$type = null)
	{
		$app      = JFactory::getApplication();
		$menus    = $app->getMenu('site');
		$language = isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language]))
		{
			self::$lookup[$language] = array();

			$component  = JComponentHelper::getComponent('com_sermondistributor');

			$attributes = array('component_id');
			$values     = array($component->id);

			if ($language != '*')
			{
				$attributes[] = 'language';
				$values[]     = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];

					if (!isset(self::$lookup[$language][$view]))
					{
						self::$lookup[$language][$view] = array();
					}

					if (isset($item->query['id']))
					{
						/**
						 * Here it will become a bit tricky
						 * language != * can override existing entries
						 * language == * cannot override existing entries
						 */
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*')
						{
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
						}
					}
				}
			}
		}

		if ($needles)
		{
			foreach ($needles as $view => $ids)
			{
				if (isset(self::$lookup[$language][$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$language][$view][(int) $id]))
						{
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}
		}
		
		if ($type)
		{
			// Check if the global menu item has been set.
			$params = JComponentHelper::getParams('com_sermondistributor');
			if ($item = $params->get($type.'_menu', 0))
			{
				return $item;
			}
		}

		// Check if the active menuitem matches the requested language
		$active = $menus->getActive();

		if ($active
			&& $active->component == 'com_sermondistributor'
			&& ($language == '*' || in_array($active->language, array('*', $language)) || !JLanguageMultilang::isEnabled()))
		{
			return $active->id;
		}

		// If not found, return language specific home link
		$default = $menus->getDefault($language);

		return !empty($default->id) ? $default->id : null;
	}
}
