<?php

/***[JCBGUI.power.licensing_template.1036.$$$$]***/
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

namespace JoomSermon\Joomla\Abstraction;



/***[JCBGUI.power.head.1036.$$$$]***/
use Joomla\CMS\Application\CMSApplicationInterface as CMSApplication;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Date\Date;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\ContentHistory;
use Joomla\CMS\Table\ContentType;
use Joomla\CMS\Table\TableInterface;
use Joomla\CMS\User\User;
use Joomla\Database\DatabaseInterface as JoomlaDatabase;
use Joomla\Registry\Registry;/***[/JCBGUI$$$$]***/

use JoomSermon\Joomla\Utilities\Component\Helper;
use JoomSermon\Joomla\Interfaces\Database\VersioningInterface;
use JoomSermon\Joomla\Abstraction\Database;


/**
 * Versioning
 * 
 * @since 5.1.1
 */
abstract class Versioning extends Database implements VersioningInterface
{

/***[JCBGUI.power.main_class_code.1036.$$$$]***/
	/**
	 * CMS Application
	 *
	 * @var   CMSApplication
	 * @since 5.1.1
	 **/
	protected CMSApplication $app;

	/**
	 * Joomla History Class
	 *
	 * @var   ContentHistory
	 * @since 5.1.1
	 */
	protected ContentHistory $contentHistory;

	/**
	 * Joomla Content Type Class
	 *
	 * @var   ContentType
	 * @since 5.1.1
	 */
	protected ContentType $typeTable;

	/**
	 * Current component params
	 *
	 * @var   Registry
	 * @since 5.1.1
	 */
	protected Registry $params;

	/**
	 * Current user ID
	 *
	 * @var   int
	 * @since 5.1.1
	 */
	protected int $userId;

	/**
	 * Current component code name
	 *
	 * @var   string
	 * @since 5.1.1
	 */
	protected string $componentNamespace;

	/**
	 * The current entity
	 *
	 * @var    string|null
	 * @since  5.1.1
	 */
	protected ?string $entity;

	/**
	 * Switch to set the history
	 *
	 * @var    int
	 * @since  5.1.1
	 **/
	protected int $history;

	/**
	 * Number of max item versions to store in history
	 *
	 * @var    int
	 * @since  5.1.1
	 **/
	protected int $maxVersions;

	/**
	 * Class constructor.
	 *
	 * Initializes the component context by setting the application, database,
	 * content history tracking, and content type table instances. Also loads
	 * component-specific parameters like history tracking and version limits.
	 *
	 * @param  JoomlaDatabase|null  $db         Optional database object. Defaults to Joomla's factory DB.
	 * @param  CMSApplication|null  $app        Optional application object. Defaults to Factory::getApplication().
	 * @param  ContentHistory|null  $history    Optional content history table instance. Defaults to new ContentHistory.
	 * @param  ContentType|null     $typeTable  Optional content type table instance. Defaults to new ContentType.
	 *
	 * @throws \Exception  If the parent constructor or any dependency throws.
	 * @since  5.1.1
	 */
	public function __construct(?JoomlaDatabase $db = null, ?CMSApplication $app = null,
		?ContentHistory $history = null, ?ContentType $typeTable = null)
	{
		parent::__construct($db);

		$this->app = $app ?: Factory::getApplication();
		$this->contentHistory = $history ?: new ContentHistory($this->db);
		$this->typeTable = $typeTable ?: new ContentType($this->db);

		$user = $this->app->getIdentity();
		$this->userId = $user instanceof User ? (int) $user->id : 0;

		// set the component details
		$this->componentNamespace = Helper::getNamespace();
		$this->params = Helper::getParams();
		$this->history = $this->params->get('save_history', 0);
		$this->maxVersions = $this->params->get('history_limit', 0);
	}

	/**
	 * Switch to prevent/allow history from being set.
	 *
	 * @param   int|null    $trigger   toggle the history (0 = no, 1 = yes, null = default)
	 *
	 * @return  self
	 * @since   5.1.1
	 **/
	public function history(?int $trigger = null): self
	{
		$this->history = $trigger !== null ? $trigger : $this->params->get('save_history', 0);

		return $this;
	}

	/**
	 * Save a history record for a stored item.
	 *
	 * @param int    $id      The ID of the record
	 *
	 * @return bool True if saved, false if skipped or failed
	 * @since  5.1.1
	 */
	protected function setHistory(int $id): bool
	{
		$tableClass = $this->getTableClass();

		if ($tableClass === null)
		{
			return false;
		}

		/** @var TableInterface $table */
		$table = new $tableClass($this->db);

		if (!$table->load($id))
		{
			return false;
		}

		// set the type alias
		$type_alias = 'com_' . $this->componentCode . '.' . $this->entity;

		$item = (object) $table->getProperties();
		unset($item->typeAlias, $item->tagsHelper);

		// Required: item_id, version_data, editor_user_id
		$this->contentHistory->reset();
		$this->contentHistory->version_id = null;
		$this->contentHistory->item_id = $type_alias . '.' . $id;
		$this->contentHistory->version_note = '';
		$this->contentHistory->version_data = json_encode($item);
		$this->contentHistory->editor_user_id = $this->userId;
		$this->contentHistory->save_date = (new Date())->toSql();

		// Don't save if hash already exists and same version note
		$this->typeTable->load(['type_alias' => $type_alias]);
		$this->contentHistory->sha1_hash = $this->contentHistory->getSha1($item, $this->typeTable);

		if ($this->contentHistory->getHashMatch())
		{
			return true;
		}

		$result = $this->contentHistory->store();

		$max_versions_context = $this->params->get('history_limit_' . $this->entity, 0);

		if ($max_versions_context)
		{
			$this->contentHistory->deleteOldVersions($max_versions_context);
		}
		elseif ($this->maxVersions)
		{
			$this->contentHistory->deleteOldVersions($this->maxVersions);
		}

		return $result;
	}

	/**
	 * Save multiple version records for already stored items.
	 *
	 * @param int[]    $ids     Array of IDs
	 * @param string   $entity  Table entity name
	 *
	 * @return int Number of successful version saves
	 * @since  5.1.1
	 */
	protected function setMultipleHistory(array $ids): int
	{
		$tableClass = $this->getTableClass();

		if ($tableClass === null)
		{
			return 0;
		}

		/** @var TableInterface $table */
		$table = new $tableClass($this->db);

		// set some var needed in loop
		$date = (new Date())->toSql();
		$max_versions_context = $this->params->get('history_limit_' . $this->entity, 0);
		$type_alias = 'com_' . $this->componentCode . '.' . $this->entity;
		$this->typeTable->load(['type_alias' => $type_alias]);
		$count = 0;

		foreach ($ids as $id)
		{
			$id = (int) $id;
			if ($id <= 0)
			{
				continue;
			}

			if (!$table->load($id))
			{
				continue;
			}

			$item = (object) $table->getProperties();
			unset($item->typeAlias, $item->tagsHelper);

			$this->contentHistory->reset();
			$this->contentHistory->version_id = null;
			$this->contentHistory->item_id = $type_alias  . '.' . $id;
			$this->contentHistory->version_note = '';
			$this->contentHistory->version_data = json_encode($item);
			$this->contentHistory->editor_user_id = $this->userId;
			$this->contentHistory->save_date = $date;

			// Don't save if hash already exists and same version note
			$this->contentHistory->sha1_hash = $this->contentHistory->getSha1($item, $this->typeTable);

			if ($this->contentHistory->getHashMatch())
			{
				continue;
			}

			$result = $this->contentHistory->store();

			if ($max_versions_context)
			{
				$this->contentHistory->deleteOldVersions($max_versions_context);
			}
			elseif ($this->maxVersions)
			{
				$this->contentHistory->deleteOldVersions($this->maxVersions);
			}

			if ($result)
			{
				++$count;
			}
		}

		return $count;
	}

	/**
	 * Get the fully qualified class name for a table if it exists.
	 *
	 * This method first extracts the base table name using `getTableName`.
	 * If the extraction fails (e.g., wrong component prefix), it returns null.
	 * If successful, it constructs the FQCN in the format:
	 *   \Namespace\Component\ComponentName\Administrator\Table\TableNameTable
	 *
	 * The table name is converted to PascalCase and suffixed with `Table`.
	 * The constructed class name is verified with `class_exists`.
	 *
	 * @return string|null  The fully qualified class name, or null if it does not exist.
	 * @since  5.1.1
	 */
	protected function getTableClass(): ?string
	{
		if (empty($this->entity))
		{
			return null;
		}

		$tableClass = ucfirst($this->entity) . 'Table';

		$class = $this->componentNamespace . '\\Administrator\\Table\\' . $tableClass;
		if (!class_exists($class))
		{
			return null;
		}

		return $class;
	}

	/**
	 * Extract the actual table name by removing the component prefix.
	 *
	 * This method checks whether the given table name includes the component-specific prefix,
	 * which usually starts with `#__` followed by the component name and an underscore (e.g., `#__mycomponent_`).
	 * If it matches this instance's component prefix stored in `$this->table`, the prefix is stripped and the short table name is returned.
	 * If the prefix is different (implying a foreign component), `null` is returned.
	 * If no prefix is present, the original value is returned unchanged.
	 *
	 * @param  string  $table  The full or short table name.
	 *
	 * @return string|null  The stripped table name, original if no prefix is found, or null if not removable.
	 * @since  5.1.1
	 */
	protected function getTableEntityName(string $table): ?string
	{
		if (strpos($table, '#__') === false)
		{
			return $table;
		}

		if (empty($this->table))
		{
			return null;
		}

		$prefix = $this->table . '_';
		if (str_starts_with($table, $prefix))
		{
			return substr($table, strlen($prefix));
		}

		return null;
	}/***[/JCBGUI$$$$]***/

}

