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

	@version		2.1.x
	@created		22nd October, 2015
	@package		Sermon Distributor
	@subpackage		router.php
	@author			Llewellyn van der Merwe <https://www.vdm.io/>	
	@copyright		Copyright (C) 2015. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that links to Dropbox. 

/----------------------------------------------------------------------------------------------------------------------------------*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Routing class from com_sermondistributor
 *
 * @since  3.3
 */
class SermondistributorRouter extends JComponentRouterBase
{	
	/**
	 * Build the route for the com_sermondistributor component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 *
	 * @since   3.3
	 */
	public function build(&$query)
	{
		$segments = array();

		// Get a menu item based on Itemid or currently active
		$params = JComponentHelper::getParams('com_sermondistributor');
		
		if (empty($query['Itemid']))
		{
			$menuItem = $this->menu->getActive();
		}
		else
		{
			$menuItem = $this->menu->getItem($query['Itemid']);
		}

		$mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
		$mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

		if (isset($query['view']))
		{
			$view = $query['view'];

			if (empty($query['Itemid']) && !(isset($view) && isset($query['id']) && ($view === 'preachers' || $view === 'preacher' || $view === 'categories' || $view === 'category' || $view === 'serieslist' || $view === 'series' || $view === 'sermon' || $view === 'api')))
			{
				$segments[] = $query['view'];
			}

			unset($query['view']);
		}
		
		// Are we dealing with a item that is attached to a menu item?
		if (isset($view) && ($mView == $view) and (isset($query['id'])) and ($mId == (int) $query['id']))
		{
			unset($query['view']);
			unset($query['catid']);
			unset($query['id']);
			return $segments;
		}

		if (isset($view) && isset($query['id']) && ($view === 'preachers' || $view === 'preacher' || $view === 'categories' || $view === 'category' || $view === 'serieslist' || $view === 'series' || $view === 'sermon' || $view === 'api'))
		{
			if ($mId != (int) $query['id'] || $mView != $view)
			{
				if (($view === 'preachers' || $view === 'preacher' || $view === 'categories' || $view === 'category' || $view === 'serieslist' || $view === 'series' || $view === 'sermon' || $view === 'api'))
				{
					$segments[] = $view;
					$id = explode(':', $query['id']);
					if (count($id) == 2)
					{
						$segments[] = $id[1];
					}
					else
					{
						$segments[] = $id[0];
					}
				}
			}
			unset($query['id']);
		}
		
		$total = count($segments);

		for ($i = 0; $i < $total; $i++)
		{
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments; 
		
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 *
	 * @since   3.3
	 */
	public function parse(&$segments)
	{		
		$count = count($segments);
		$vars = array();
		
		// Handle View and Identifier
		switch($segments[0])
		{
			case 'preachers':
				$vars['view'] = 'preachers';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('preacher', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'preacher':
				$vars['view'] = 'preacher';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('preacher', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'categories':
				$vars['view'] = 'categories';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('categories', $segments[$count-1], 'alias', 'id', true);
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'category':
				$vars['view'] = 'category';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('category', $segments[$count-1], 'alias', 'id', true);
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'serieslist':
				$vars['view'] = 'serieslist';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('series', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'series':
				$vars['view'] = 'series';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('series', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'sermon':
				$vars['view'] = 'sermon';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('sermon', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
			case 'api':
				$vars['view'] = 'api';
				if (is_numeric($segments[$count-1]))
				{
					$vars['id'] = (int) $segments[$count-1];
				}
				elseif ($segments[$count-1])
				{
					$id = $this->getVar('external_source', $segments[$count-1], 'alias', 'id');
					if($id)
					{
						$vars['id'] = $id;
					}
				}
				break;
		}

		return $vars;
	} 

	protected function getVar($table, $where = null, $whereString = null, $what = null, $category = false, $operator = '=', $main = 'sermondistributor')
	{
		if(!$where || !$what || !$whereString)
		{
			return false;
		}
		// Get a db connection.
		$db = JFactory::getDbo();
		// Create a new query object.
		$query = $db->getQuery(true);

		$query->select($db->quoteName(array($what)));
		if ('categories' == $table || 'category' == $table || $category)
		{
			$getTable = '#__categories';
			$query->from($db->quoteName($getTable));
			// we need this to target the components categories (TODO will keep an eye on this)
			$query->where($db->quoteName('extension') . ' LIKE '. $db->quote((string)'com_' . $main . '%'));
		}
		else
		{
			// we must check if the table exist (TODO not ideal)
			$tables = $db->getTableList();
			$app = JFactory::getApplication();
			$prefix = $app->get('dbprefix');
			$check = $prefix.$main.'_'.$table;
			if (in_array($check, $tables))
			{
				$getTable = '#__'.$main.'_'.$table;
				$query->from($db->quoteName($getTable));
			}
			else
			{
				return false;
			}
		}
		if (is_numeric($where))
		{
			return false;
		}
		elseif ($this->checkString($where))
		{
			// we must first check if this table has the column
			$columns = $db->getTableColumns($getTable);
			if (isset($columns[$whereString]))
			{
				$query->where($db->quoteName($whereString) . ' '.$operator.' '. $db->quote((string)$where));
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
		$db->setQuery($query);
		$db->execute();
		if ($db->getNumRows())
		{
			return $db->loadResult();
		}
		return false;
	}
	
	protected function checkString($string)
	{
		if (isset($string) && is_string($string) && strlen($string) > 0)
		{
			return true;
		}
		return false;
	}
}

function SermondistributorBuildRoute(&$query)
{
	$router = new SermondistributorRouter;
	
	return $router->build($query);
}

function SermondistributorParseRoute($segments)
{
	$router = new SermondistributorRouter;

	return $router->parse($segments);
}