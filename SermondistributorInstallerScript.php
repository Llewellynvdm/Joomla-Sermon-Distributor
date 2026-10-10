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
	@subpackage		SermondistributorInstallerScript.php
	@author			Llewellyn van der Merwe <https://dev.vdm.io/>	
	@copyright		Copyright (C) 2026. All Rights Reserved
	@license		GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html 

	A sermon distributor that effectively manages sermons. 

/----------------------------------------------------------------------------------------------------------------------------------*/

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Installer\InstallerScriptInterface;
use Joomla\CMS\Log\Log;
use Joomla\CMS\Version;
use Joomla\CMS\HTML\HTMLHelper as Html;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Database\DatabaseInterface;
use JoomSermon\Joomla\Sermondistributor\PHPConfigurationChecker;
use JoomSermon\Joomla\Sermondistributor\Table\SchemaChecker;

// No direct access to this file
defined('_JEXEC') or die;

/**
 * Script File of Sermondistributor Component
 *
 * @since  3.6
 */
class Com_SermondistributorInstallerScript implements InstallerScriptInterface
{
	/**
	 * The CMS Application.
	 *
	 * @since 4.4.2
	 */
	protected $app;

	/**
	 * The database class.
	 *
	 * @since 4.4.2
	 */
	protected $db;

	/**
	 * The version number of the extension.
	 *
	 * @var    string
	 * @since  3.6
	 */
	protected $release;

	/**
	 * The table the parameters are stored in.
	 *
	 * @var    string
	 * @since  3.6
	 */
	protected $paramTable;

	/**
	 * The extension name. This should be set in the installer script.
	 *
	 * @var    string
	 * @since  3.6
	 */
	protected $extension;

	/**
	 * A list of files to be deleted
	 *
	 * @var    array
	 * @since  3.6
	 */
	protected $deleteFiles = [];

	/**
	 * A list of folders to be deleted
	 *
	 * @var    array
	 * @since  3.6
	 */
	protected $deleteFolders = [];

	/**
	 * A list of CLI script files to be copied to the cli directory
	 *
	 * @var    array
	 * @since  3.6
	 */
	protected $cliScriptFiles = [];

	/**
	 * Minimum PHP version required to install the extension
	 *
	 * @var    string
	 * @since  3.6
	 */
	protected $minimumPhp;

	/**
	 * Minimum Joomla! version required to install the extension
	 *
	 * @var    string
	 * @since  3.6
	 */
	protected $minimumJoomla;

	/**
	 * Extension script constructor.
	 *
	 * @since   3.0.0
	 */
	public function __construct()
	{
		$this->minimumJoomla = '4.3';
		$this->minimumPhp = JOOMLA_MINIMUM_PHP;
		$this->app ??= Factory::getApplication();
		$this->db = Factory::getContainer()->get(DatabaseInterface::class);

		// check if the files exist
		if (is_file(JPATH_ROOT . '/administrator/components/com_sermondistributor/sermondistributor.php'))
		{
			// remove Joomla 3 files
			$this->deleteFiles = [
				'/administrator/components/com_sermondistributor/sermondistributor.php',
				'/administrator/components/com_sermondistributor/controller.php',
				'/administrator/components/com_sermondistributor/script.php',
				'/components/com_sermondistributor/sermondistributor.php',
				'/components/com_sermondistributor/controller.php',
				'/components/com_sermondistributor/router.php',
			];
		}

		// check if the Folders exist
		if (is_dir(JPATH_ROOT . '/administrator/components/com_sermondistributor/modules'))
		{
			// remove Joomla 3 folder
			$this->deleteFolders = [
				'/administrator/components/com_sermondistributor/controllers',
				'/administrator/components/com_sermondistributor/helpers',
				'/administrator/components/com_sermondistributor/modules',
				'/administrator/components/com_sermondistributor/tables',
				'/administrator/components/com_sermondistributor/views',
				'/components/com_sermondistributor/controllers',
				'/components/com_sermondistributor/helpers',
				'/components/com_sermondistributor/modules',
				'/components/com_sermondistributor/views',
			];
		}
	}

	/**
	 * Function called after the extension is installed.
	 *
	 * @param   InstallerAdapter  $adapter  The adapter calling this method
	 *
	 * @return  boolean  True on success
	 * @since   4.2.0
	 */
	public function install(InstallerAdapter $adapter): bool {return true;}

	/**
	 * Function called after the extension is updated.
	 *
	 * @param   InstallerAdapter   $adapter   The adapter calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since   4.2.0
	 */
	public function update(InstallerAdapter $adapter): bool {return true;}

	/**
	 * Function called after the extension is uninstalled.
	 *
	 * @param   InstallerAdapter   $adapter  The adapter calling this method
	 *
	 * @return  boolean  True on success
	 * @since   4.2.0
	 */
	public function uninstall(InstallerAdapter $adapter): bool
	{
		// Remove Related Component Data.

		// Remove Sermon Data
		$this->removeViewData("com_sermondistributor.sermon");

		// Remove Preacher Data
		$this->removeViewData("com_sermondistributor.preacher");

		// Remove Church Data
		$this->removeViewData("com_sermondistributor.church");

		// Remove Category Data
		$this->removeViewData("com_sermondistributor.category");

		// Remove Tag Data
		$this->removeViewData("com_sermondistributor.tag");

		// Remove Address Data
		$this->removeViewData("com_sermondistributor.address");

		// Remove Language Data
		$this->removeViewData("com_sermondistributor.language");

		// Remove File Data
		$this->removeViewData("com_sermondistributor.file");

		// Remove Address type Data
		$this->removeViewData("com_sermondistributor.address_type");

		// Remove File type Data
		$this->removeViewData("com_sermondistributor.file_type");

		// Remove Region Data
		$this->removeViewData("com_sermondistributor.region");

		// Remove Subregion Data
		$this->removeViewData("com_sermondistributor.subregion");

		// Remove Country Data
		$this->removeViewData("com_sermondistributor.country");

		// Remove State Data
		$this->removeViewData("com_sermondistributor.state");

		// Remove City Data
		$this->removeViewData("com_sermondistributor.city");

		// Remove Timezone Data
		$this->removeViewData("com_sermondistributor.timezone");

		// Remove Sermon tag Data
		$this->removeViewData("com_sermondistributor.sermon_tag");

		// Remove Sermon language Data
		$this->removeViewData("com_sermondistributor.sermon_language");

		// Remove Preacher language Data
		$this->removeViewData("com_sermondistributor.preacher_language");

		// Remove Church elder Data
		$this->removeViewData("com_sermondistributor.church_elder");

		// Remove Asset Data.
		$this->removeAssetData();

		// Revert the assets table rules column back to the default.
		$this->removeDatabaseAssetsRulesFix();

		// Remove component from action logs extensions table.
		$this->removeActionLogsExtensions();

		// Remove Sermon from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.sermon');

		// Remove Preacher from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.preacher');

		// Remove Church from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.church');

		// Remove Category from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.category');

		// Remove Series from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.series');

		// Remove Tag from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.tag');

		// Remove Address from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.address');

		// Remove Language from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.language');

		// Remove File from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.file');

		// Remove Address_type from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.address_type');

		// Remove File_type from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.file_type');

		// Remove Region from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.region');

		// Remove Subregion from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.subregion');

		// Remove Country from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.country');

		// Remove State from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.state');

		// Remove City from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.city');

		// Remove Timezone from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.timezone');

		// Remove Sermon_tag from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.sermon_tag');

		// Remove Sermon_language from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.sermon_language');

		// Remove Preacher_language from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.preacher_language');

		// Remove Church_elder from action logs config table.
		$this->removeActionLogConfig('com_sermondistributor.church_elder');
		// little notice as after service, in case of bad experience with component.
		echo '<div style="background-color: #fff;" class="alert alert-info">
		<h2>Did something go wrong? Are you disappointed?</h2>
		<p>Please let me know at <a href="mailto:joomla@vdm.io">joomla@vdm.io</a>.
		<br />We at Vast Development Method are committed to building extensions that performs proficiently! You can help us, really!
		<br />Send me your thoughts on improvements that is needed, trust me, I will be very grateful!
		<br />Visit us at <a href="https://dev.vdm.io/" target="_blank">https://dev.vdm.io/</a> today!</p></div>';

		return true;
	}

	/**
	 * Function called before extension installation/update/removal procedure commences.
	 *
	 * @param   string            $type     The type of change (install or discover_install, update, uninstall)
	 * @param   InstallerAdapter  $adapter  The adapter calling this method
	 *
	 * @return  boolean  True on success
	 * @since   4.2.0
	 */
	public function preflight(string $type, InstallerAdapter $adapter): bool
	{
		// Check for the minimum PHP version before continuing
		if (!empty($this->minimumPhp) && version_compare(PHP_VERSION, $this->minimumPhp, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_PHP', $this->minimumPhp), Log::WARNING, 'jerror');

			return false;
		}

		// Check for the minimum Joomla version before continuing
		if (!empty($this->minimumJoomla) && version_compare(JVERSION, $this->minimumJoomla, '<'))
		{
			Log::add(Text::sprintf('JLIB_INSTALLER_MINIMUM_JOOMLA', $this->minimumJoomla), Log::WARNING, 'jerror');

			return false;
		}

		// Extension manifest file version
		$this->extension = $adapter->getName();
		$this->release   = $adapter->getManifest()->version;

		// do any updates needed
		if ($type === 'update')
		{
/***[INSERTED$$$$]***//**387**/
			// Check that the PHP configurations are sufficient 
			if ($this->classExists(PHPConfigurationChecker::class))
			{
				(new PHPConfigurationChecker())->run();
			}/***[/INSERTED$$$$]***/
/***[INSERTED$$$$]***//**442**/
			// all things to clear out
			$removeFolders = [];
			$removeFolders[] = JPATH_LIBRARIES . '/vendor_sermondistributor/TrueChristianChurch.Joomla';
			$removeFolders[] = JPATH_LIBRARIES . '/vendor_sermondistributor/TrueChristianChurch.Joomla.SermonDistributor';
			$removeFolders[] = JPATH_LIBRARIES . '/vendor_sermondistributor/TrueChristianChurch.Joomla.FOF';
			$removeFolders[] = JPATH_LIBRARIES . '/vendor_sermondistributor/TrueChristianSermon.Joomla/src/SermonDistributor';

			foreach ($removeFolders as $folder)
			{
				$this->removeFolder($folder);
			}/***[/INSERTED$$$$]***/
		}

		// do any install needed
		if ($type === 'install')
		{
/***[INSERTED$$$$]***//**387**/
			// Check that the PHP configurations are sufficient 
			if ($this->classExists(PHPConfigurationChecker::class))
			{
				(new PHPConfigurationChecker())->run();
			}/***[/INSERTED$$$$]***/
		}

		return true;
	}

	/**
	 * Function called after extension installation/update/removal procedure commences.
	 *
	 * @param   string            $type     The type of change (install or discover_install, update, uninstall)
	 * @param   InstallerAdapter  $adapter  The adapter calling this method
	 *
	 * @return  boolean  True on success
	 * @since   4.2.0
	 */
	public function postflight(string $type, InstallerAdapter $adapter): bool
	{
		// We check if we have dynamic folders to copy
		$this->moveFolders($adapter);

		// set the default component settings
		if ($type === 'install')
		{

			// Install Sermon Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Sermon',
				// typeAlias
				'com_sermondistributor.sermon',
				// table
				'{"special": {"dbtable": "#__sermondistributor_sermon","key": "id","type": "SermonTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","category":"category","series":"series","church":"church","preacher":"preacher","guid":"guid","alias":"alias","description":"description"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/sermon.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "category","targetTable": "#__sermondistributor_category","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "languages","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"},{"sourceColumn": "tags","targetTable": "#__sermondistributor_tag","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Preacher Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Preacher',
				// typeAlias
				'com_sermondistributor.preacher',
				// table
				'{"special": {"dbtable": "#__sermondistributor_preacher","key": "id","type": "PreacherTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","church":"church","guid":"guid","phone":"phone","email":"email","contactname":"contactname","alias":"alias"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/preacher.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "languages","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"}]}'
			);
			// Install Church Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Church',
				// typeAlias
				'com_sermondistributor.church',
				// table
				'{"special": {"dbtable": "#__sermondistributor_church","key": "id","type": "ChurchTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","guid":"guid","alias":"alias","description":"description","website":"website","phone":"phone","email":"email","contactname":"contactname"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/church.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "elders","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Category Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Category',
				// typeAlias
				'com_sermondistributor.category',
				// table
				'{"special": {"dbtable": "#__sermondistributor_category","key": "id","type": "CategoryTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","parent_guid":"parent_guid","guid":"guid","lft":"lft","rgt":"rgt","alias":"alias"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/category.xml","hideFields": ["asset_id","checked_out","checked_out_time","lft","rgt"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","published"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "parent_guid","targetTable": "#__sermondistributor_category","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Tag Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Tag',
				// typeAlias
				'com_sermondistributor.tag',
				// table
				'{"special": {"dbtable": "#__sermondistributor_tag","key": "id","type": "TagTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","parent_guid":"parent_guid","guid":"guid","lft":"lft","rgt":"rgt","alias":"alias"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/tag.xml","hideFields": ["asset_id","checked_out","checked_out_time","lft","rgt"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","published"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "parent_guid","targetTable": "#__sermondistributor_tag","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Address Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Address',
				// typeAlias
				'com_sermondistributor.address',
				// table
				'{"special": {"dbtable": "#__sermondistributor_address","key": "id","type": "AddressTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "line_one","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"line_one":"line_one","type":"type","country":"country","state":"state","city":"city","preacher":"preacher","church":"church","guid":"guid","postal":"postal","line_two":"line_two"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/address.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "type","targetTable": "#__sermondistributor_address_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__sermondistributor_country","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "state","targetTable": "#__sermondistributor_state","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "city","targetTable": "#__sermondistributor_city","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Language Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Language',
				// typeAlias
				'com_sermondistributor.language',
				// table
				'{"special": {"dbtable": "#__sermondistributor_language","key": "id","type": "LanguageTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","langtag":"langtag"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/language.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Install File Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor File',
				// typeAlias
				'com_sermondistributor.file',
				// table
				'{"special": {"dbtable": "#__sermondistributor_file","key": "id","type": "FileTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","file_type":"file_type","size":"size","entity_type":"entity_type","guid":"guid","entity":"entity","file_path":"file_path","extension":"extension","mime":"mime"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/file.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","size"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Address type Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Address_type',
				// typeAlias
				'com_sermondistributor.address_type',
				// table
				'{"special": {"dbtable": "#__sermondistributor_address_type","key": "id","type": "Address_typeTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/address_type.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Install File type Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor File_type',
				// typeAlias
				'com_sermondistributor.file_type',
				// table
				'{"special": {"dbtable": "#__sermondistributor_file_type","key": "id","type": "File_typeTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","type":"type","path":"path","download_access":"download_access","quantity":"quantity","guid":"guid","target":"target","file_formats":"file_formats","document_formats":"document_formats","media_formats":"media_formats","image_formats":"image_formats"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/file_type.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","type","download_access","quantity"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Install Region Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Region',
				// typeAlias
				'com_sermondistributor.region',
				// table
				'{"special": {"dbtable": "#__sermondistributor_region","key": "id","type": "RegionTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","guid":"guid","wikidataid":"wikidataid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/region.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Install Subregion Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Subregion',
				// typeAlias
				'com_sermondistributor.subregion',
				// table
				'{"special": {"dbtable": "#__sermondistributor_subregion","key": "id","type": "SubregionTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","region":"region","guid":"guid","wikidataid":"wikidataid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/subregion.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "region","targetTable": "#__sermondistributor_region","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Country Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Country',
				// typeAlias
				'com_sermondistributor.country',
				// table
				'{"special": {"dbtable": "#__sermondistributor_country","key": "id","type": "CountryTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","subregion":"subregion","capital":"capital","currency_name":"currency_name","latitude":"latitude","emojiu":"emojiu","emoji":"emoji","tld":"tld","iso2":"iso2","codethree":"codethree","nationality":"nationality","native":"native","longitude":"longitude","symbol":"symbol","numeric_code":"numeric_code","wikidataid":"wikidataid","phonecode":"phonecode","guid":"guid","iso3":"iso3"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/country.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","numeric_code"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "subregion","targetTable": "#__sermondistributor_subregion","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "capital","targetTable": "#__sermondistributor_city","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install State Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor State',
				// typeAlias
				'com_sermondistributor.state',
				// table
				'{"special": {"dbtable": "#__sermondistributor_state","key": "id","type": "StateTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","country":"country","type":"type","guid":"guid","wikidataid":"wikidataid","fips_code":"fips_code","iso2":"iso2","longitude":"longitude","latitude":"latitude"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/state.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__sermondistributor_country","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install City Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor City',
				// typeAlias
				'com_sermondistributor.city',
				// table
				'{"special": {"dbtable": "#__sermondistributor_city","key": "id","type": "CityTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","state":"state","latitude":"latitude","longitude":"longitude","guid":"guid","wikidataid":"wikidataid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/city.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "state","targetTable": "#__sermondistributor_state","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Timezone Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Timezone',
				// typeAlias
				'com_sermondistributor.timezone',
				// table
				'{"special": {"dbtable": "#__sermondistributor_timezone","key": "id","type": "TimezoneTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "timezone_name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"timezone_name":"timezone_name","timezone_identifier":"timezone_identifier","gmt_offset_name":"gmt_offset_name","country":"country","guid":"guid","gmt_offset_sec":"gmt_offset_sec","abbreviation":"abbreviation"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/timezone.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__sermondistributor_country","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Sermon tag Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Sermon_tag',
				// typeAlias
				'com_sermondistributor.sermon_tag',
				// table
				'{"special": {"dbtable": "#__sermondistributor_sermon_tag","key": "id","type": "Sermon_tagTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "tag","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"tag":"tag","sermon":"sermon","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/sermon_tag.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "tag","targetTable": "#__sermondistributor_tag","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Sermon language Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Sermon_language',
				// typeAlias
				'com_sermondistributor.sermon_language',
				// table
				'{"special": {"dbtable": "#__sermondistributor_sermon_language","key": "id","type": "Sermon_languageTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "language","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"language":"language","sermon":"sermon","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/sermon_language.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "language","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Preacher language Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Preacher_language',
				// typeAlias
				'com_sermondistributor.preacher_language',
				// table
				'{"special": {"dbtable": "#__sermondistributor_preacher_language","key": "id","type": "Preacher_languageTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "language","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"language":"language","preacher":"preacher","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/preacher_language.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "language","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Install Church elder Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Church_elder',
				// typeAlias
				'com_sermondistributor.church_elder',
				// table
				'{"special": {"dbtable": "#__sermondistributor_church_elder","key": "id","type": "Church_elderTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "elder","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"elder":"elder","church":"church","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/church_elder.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "elder","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"}]}'
			);


			// Fix the assets table rules column size.
			$this->setDatabaseAssetsRulesFix(63520, "TEXT");
			// Install the global extension params.
			$this->setExtensionsParams(
				'{"autorName":"Llewellyn van der Merwe","autorEmail":"joomla@vdm.io","check_in":"-1 day","save_history":"1","history_limit":"10"}'
			);


/***[INSERTED$$$$]***//**388**/
			// Check that the database is up-to date
			if ($this->classExists(SchemaChecker::class))
			{
				(new SchemaChecker())->run();
			}/***[/INSERTED$$$$]***/

			echo '<div style="background-color: #fff;" class="alert alert-info"><a target="_blank" href="https://dev.vdm.io/" title="Sermon Distributor">
				<img src="components/com_sermondistributor/assets/images/vdm-component.jpg"/>
				</a></div>';

			// Add component to the action logs extensions table.
			$this->setActionLogsExtensions();

			// Add Sermon to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERMON',
				// typeAlias
				'com_sermondistributor.sermon',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_sermon',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Preacher to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'PREACHER',
				// typeAlias
				'com_sermondistributor.preacher',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_preacher',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Church to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CHURCH',
				// typeAlias
				'com_sermondistributor.church',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_church',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Category to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CATEGORY',
				// typeAlias
				'com_sermondistributor.category',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_category',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Series to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERIES',
				// typeAlias
				'com_sermondistributor.series',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_series',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Tag to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'TAG',
				// typeAlias
				'com_sermondistributor.tag',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_tag',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Address to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'ADDRESS',
				// typeAlias
				'com_sermondistributor.address',
				// idHolder
				'id',
				// titleHolder
				'line_one',
				// tableName
				'#__sermondistributor_address',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Language to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'LANGUAGE',
				// typeAlias
				'com_sermondistributor.language',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_language',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add File to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'FILE',
				// typeAlias
				'com_sermondistributor.file',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_file',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Address_type to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'ADDRESS_TYPE',
				// typeAlias
				'com_sermondistributor.address_type',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_address_type',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add File_type to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'FILE_TYPE',
				// typeAlias
				'com_sermondistributor.file_type',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_file_type',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Region to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'REGION',
				// typeAlias
				'com_sermondistributor.region',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_region',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Subregion to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SUBREGION',
				// typeAlias
				'com_sermondistributor.subregion',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_subregion',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Country to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'COUNTRY',
				// typeAlias
				'com_sermondistributor.country',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_country',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add State to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'STATE',
				// typeAlias
				'com_sermondistributor.state',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_state',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add City to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CITY',
				// typeAlias
				'com_sermondistributor.city',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_city',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Timezone to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'TIMEZONE',
				// typeAlias
				'com_sermondistributor.timezone',
				// idHolder
				'id',
				// titleHolder
				'timezone_name',
				// tableName
				'#__sermondistributor_timezone',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Sermon_tag to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERMON_TAG',
				// typeAlias
				'com_sermondistributor.sermon_tag',
				// idHolder
				'id',
				// titleHolder
				'tag',
				// tableName
				'#__sermondistributor_sermon_tag',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Sermon_language to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERMON_LANGUAGE',
				// typeAlias
				'com_sermondistributor.sermon_language',
				// idHolder
				'id',
				// titleHolder
				'language',
				// tableName
				'#__sermondistributor_sermon_language',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Preacher_language to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'PREACHER_LANGUAGE',
				// typeAlias
				'com_sermondistributor.preacher_language',
				// idHolder
				'id',
				// titleHolder
				'language',
				// tableName
				'#__sermondistributor_preacher_language',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add Church_elder to the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CHURCH_ELDER',
				// typeAlias
				'com_sermondistributor.church_elder',
				// idHolder
				'id',
				// titleHolder
				'elder',
				// tableName
				'#__sermondistributor_church_elder',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);
		}

		// do any updates needed
		if ($type === 'update')
		{

			// Update Sermon Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Sermon',
				// typeAlias
				'com_sermondistributor.sermon',
				// table
				'{"special": {"dbtable": "#__sermondistributor_sermon","key": "id","type": "SermonTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "description","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","category":"category","series":"series","church":"church","preacher":"preacher","guid":"guid","alias":"alias","description":"description"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/sermon.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "category","targetTable": "#__sermondistributor_category","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "series","targetTable": "#__sermondistributor_series","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "languages","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"},{"sourceColumn": "tags","targetTable": "#__sermondistributor_tag","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Preacher Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Preacher',
				// typeAlias
				'com_sermondistributor.preacher',
				// table
				'{"special": {"dbtable": "#__sermondistributor_preacher","key": "id","type": "PreacherTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","church":"church","guid":"guid","phone":"phone","email":"email","contactname":"contactname","alias":"alias"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/preacher.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "languages","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"}]}'
			);
			// Update Church Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Church',
				// typeAlias
				'com_sermondistributor.church',
				// table
				'{"special": {"dbtable": "#__sermondistributor_church","key": "id","type": "ChurchTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","guid":"guid","alias":"alias","description":"description","website":"website","phone":"phone","email":"email","contactname":"contactname"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/church.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "elders","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Category Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Category',
				// typeAlias
				'com_sermondistributor.category',
				// table
				'{"special": {"dbtable": "#__sermondistributor_category","key": "id","type": "CategoryTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","parent_guid":"parent_guid","guid":"guid","lft":"lft","rgt":"rgt","alias":"alias"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/category.xml","hideFields": ["asset_id","checked_out","checked_out_time","lft","rgt"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","published"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "parent_guid","targetTable": "#__sermondistributor_category","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Tag Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Tag',
				// typeAlias
				'com_sermondistributor.tag',
				// table
				'{"special": {"dbtable": "#__sermondistributor_tag","key": "id","type": "TagTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "alias","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","parent_guid":"parent_guid","guid":"guid","lft":"lft","rgt":"rgt","alias":"alias"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/tag.xml","hideFields": ["asset_id","checked_out","checked_out_time","lft","rgt"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","published"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "parent_guid","targetTable": "#__sermondistributor_tag","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Address Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Address',
				// typeAlias
				'com_sermondistributor.address',
				// table
				'{"special": {"dbtable": "#__sermondistributor_address","key": "id","type": "AddressTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "line_one","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"line_one":"line_one","type":"type","country":"country","state":"state","city":"city","preacher":"preacher","church":"church","guid":"guid","postal":"postal","line_two":"line_two"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/address.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "type","targetTable": "#__sermondistributor_address_type","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__sermondistributor_country","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "state","targetTable": "#__sermondistributor_state","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "city","targetTable": "#__sermondistributor_city","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Language Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Language',
				// typeAlias
				'com_sermondistributor.language',
				// table
				'{"special": {"dbtable": "#__sermondistributor_language","key": "id","type": "LanguageTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","langtag":"langtag"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/language.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Update File Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor File',
				// typeAlias
				'com_sermondistributor.file',
				// table
				'{"special": {"dbtable": "#__sermondistributor_file","key": "id","type": "FileTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","file_type":"file_type","size":"size","entity_type":"entity_type","guid":"guid","entity":"entity","file_path":"file_path","extension":"extension","mime":"mime"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/file.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","size"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "file_type","targetTable": "#__sermondistributor_file_type","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Address type Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Address_type',
				// typeAlias
				'com_sermondistributor.address_type',
				// table
				'{"special": {"dbtable": "#__sermondistributor_address_type","key": "id","type": "Address_typeTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","description":"description","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/address_type.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Update File type Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor File_type',
				// typeAlias
				'com_sermondistributor.file_type',
				// table
				'{"special": {"dbtable": "#__sermondistributor_file_type","key": "id","type": "File_typeTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","type":"type","path":"path","download_access":"download_access","quantity":"quantity","guid":"guid","target":"target","file_formats":"file_formats","document_formats":"document_formats","media_formats":"media_formats","image_formats":"image_formats"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/file_type.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","type","download_access","quantity"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Update Region Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Region',
				// typeAlias
				'com_sermondistributor.region',
				// table
				'{"special": {"dbtable": "#__sermondistributor_region","key": "id","type": "RegionTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","guid":"guid","wikidataid":"wikidataid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/region.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"}]}'
			);
			// Update Subregion Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Subregion',
				// typeAlias
				'com_sermondistributor.subregion',
				// table
				'{"special": {"dbtable": "#__sermondistributor_subregion","key": "id","type": "SubregionTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","region":"region","guid":"guid","wikidataid":"wikidataid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/subregion.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "region","targetTable": "#__sermondistributor_region","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Country Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Country',
				// typeAlias
				'com_sermondistributor.country',
				// table
				'{"special": {"dbtable": "#__sermondistributor_country","key": "id","type": "CountryTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","subregion":"subregion","capital":"capital","currency_name":"currency_name","latitude":"latitude","emojiu":"emojiu","emoji":"emoji","tld":"tld","iso2":"iso2","codethree":"codethree","nationality":"nationality","native":"native","longitude":"longitude","symbol":"symbol","numeric_code":"numeric_code","wikidataid":"wikidataid","phonecode":"phonecode","guid":"guid","iso3":"iso3"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/country.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits","numeric_code"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "subregion","targetTable": "#__sermondistributor_subregion","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "capital","targetTable": "#__sermondistributor_city","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update State Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor State',
				// typeAlias
				'com_sermondistributor.state',
				// table
				'{"special": {"dbtable": "#__sermondistributor_state","key": "id","type": "StateTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","country":"country","type":"type","guid":"guid","wikidataid":"wikidataid","fips_code":"fips_code","iso2":"iso2","longitude":"longitude","latitude":"latitude"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/state.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__sermondistributor_country","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update City Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor City',
				// typeAlias
				'com_sermondistributor.city',
				// table
				'{"special": {"dbtable": "#__sermondistributor_city","key": "id","type": "CityTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"name":"name","state":"state","latitude":"latitude","longitude":"longitude","guid":"guid","wikidataid":"wikidataid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/city.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "state","targetTable": "#__sermondistributor_state","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Timezone Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Timezone',
				// typeAlias
				'com_sermondistributor.timezone',
				// table
				'{"special": {"dbtable": "#__sermondistributor_timezone","key": "id","type": "TimezoneTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "timezone_name","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "metadata","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "metakey","core_metadesc": "metadesc","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"timezone_name":"timezone_name","timezone_identifier":"timezone_identifier","gmt_offset_name":"gmt_offset_name","country":"country","guid":"guid","gmt_offset_sec":"gmt_offset_sec","abbreviation":"abbreviation"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/timezone.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "country","targetTable": "#__sermondistributor_country","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Sermon tag Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Sermon_tag',
				// typeAlias
				'com_sermondistributor.sermon_tag',
				// table
				'{"special": {"dbtable": "#__sermondistributor_sermon_tag","key": "id","type": "Sermon_tagTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "tag","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"tag":"tag","sermon":"sermon","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/sermon_tag.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "tag","targetTable": "#__sermondistributor_tag","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Sermon language Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Sermon_language',
				// typeAlias
				'com_sermondistributor.sermon_language',
				// table
				'{"special": {"dbtable": "#__sermondistributor_sermon_language","key": "id","type": "Sermon_languageTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "language","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"language":"language","sermon":"sermon","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/sermon_language.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "language","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"},{"sourceColumn": "sermon","targetTable": "#__sermondistributor_sermon","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Preacher language Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Preacher_language',
				// typeAlias
				'com_sermondistributor.preacher_language',
				// table
				'{"special": {"dbtable": "#__sermondistributor_preacher_language","key": "id","type": "Preacher_languageTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "language","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"language":"language","preacher":"preacher","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/preacher_language.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "language","targetTable": "#__sermondistributor_language","targetColumn": "langtag","displayColumn": "name"},{"sourceColumn": "preacher","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"}]}'
			);
			// Update Church elder Content Types.
			$this->setContentType(
				// typeTitle
				'Sermondistributor Church_elder',
				// typeAlias
				'com_sermondistributor.church_elder',
				// table
				'{"special": {"dbtable": "#__sermondistributor_church_elder","key": "id","type": "Church_elderTable","prefix": "JoomSermon\Component\Sermondistributor\Administrator\Table"}}',
				// rules
				'',
				// fieldMappings
				'{"common": {"core_content_item_id": "id","core_title": "elder","core_state": "published","core_alias": "null","core_created_time": "created","core_modified_time": "modified","core_body": "null","core_hits": "hits","core_publish_up": "null","core_publish_down": "null","core_access": "access","core_params": "params","core_featured": "null","core_metadata": "null","core_language": "null","core_images": "null","core_urls": "null","core_version": "version","core_ordering": "ordering","core_metakey": "null","core_metadesc": "null","core_catid": "null","core_xreference": "null","asset_id": "asset_id"},"special": {"elder":"elder","church":"church","guid":"guid"}}',
				// router
				'',
				// contentHistoryOptions
				'{"formFile": "administrator/components/com_sermondistributor/forms/church_elder.xml","hideFields": ["asset_id","checked_out","checked_out_time"],"ignoreChanges": ["modified_by","modified","checked_out","checked_out_time","version","hits"],"convertToInt": ["published","ordering","version","hits"],"displayLookup": [{"sourceColumn": "created_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "access","targetTable": "#__viewlevels","targetColumn": "id","displayColumn": "title"},{"sourceColumn": "modified_by","targetTable": "#__users","targetColumn": "id","displayColumn": "name"},{"sourceColumn": "elder","targetTable": "#__sermondistributor_preacher","targetColumn": "guid","displayColumn": "name"},{"sourceColumn": "church","targetTable": "#__sermondistributor_church","targetColumn": "guid","displayColumn": "name"}]}'
			);



/***[INSERTED$$$$]***//**388**/
			// Check that the database is up-to date
			if ($this->classExists(SchemaChecker::class))
			{
				(new SchemaChecker())->run();
			}/***[/INSERTED$$$$]***/

			echo '<div style="background-color: #fff;" class="alert alert-info"><a target="_blank" href="https://dev.vdm.io/" title="Sermon Distributor">
				<img src="components/com_sermondistributor/assets/images/vdm-component.jpg"/>
				</a>
				<h3>Upgrade to Version 6.0.0 Was Successful! Let us know if anything is not working as expected.</h3></div>';

			// Add/Update component in the action logs extensions table.
			$this->setActionLogsExtensions();

			// Add/Update Sermon in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERMON',
				// typeAlias
				'com_sermondistributor.sermon',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_sermon',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Preacher in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'PREACHER',
				// typeAlias
				'com_sermondistributor.preacher',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_preacher',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Church in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CHURCH',
				// typeAlias
				'com_sermondistributor.church',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_church',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Category in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CATEGORY',
				// typeAlias
				'com_sermondistributor.category',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_category',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Series in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERIES',
				// typeAlias
				'com_sermondistributor.series',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_series',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Tag in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'TAG',
				// typeAlias
				'com_sermondistributor.tag',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_tag',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Address in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'ADDRESS',
				// typeAlias
				'com_sermondistributor.address',
				// idHolder
				'id',
				// titleHolder
				'line_one',
				// tableName
				'#__sermondistributor_address',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Language in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'LANGUAGE',
				// typeAlias
				'com_sermondistributor.language',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_language',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update File in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'FILE',
				// typeAlias
				'com_sermondistributor.file',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_file',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Address_type in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'ADDRESS_TYPE',
				// typeAlias
				'com_sermondistributor.address_type',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_address_type',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update File_type in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'FILE_TYPE',
				// typeAlias
				'com_sermondistributor.file_type',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_file_type',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Region in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'REGION',
				// typeAlias
				'com_sermondistributor.region',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_region',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Subregion in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SUBREGION',
				// typeAlias
				'com_sermondistributor.subregion',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_subregion',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Country in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'COUNTRY',
				// typeAlias
				'com_sermondistributor.country',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_country',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update State in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'STATE',
				// typeAlias
				'com_sermondistributor.state',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_state',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update City in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CITY',
				// typeAlias
				'com_sermondistributor.city',
				// idHolder
				'id',
				// titleHolder
				'name',
				// tableName
				'#__sermondistributor_city',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Timezone in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'TIMEZONE',
				// typeAlias
				'com_sermondistributor.timezone',
				// idHolder
				'id',
				// titleHolder
				'timezone_name',
				// tableName
				'#__sermondistributor_timezone',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Sermon_tag in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERMON_TAG',
				// typeAlias
				'com_sermondistributor.sermon_tag',
				// idHolder
				'id',
				// titleHolder
				'tag',
				// tableName
				'#__sermondistributor_sermon_tag',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Sermon_language in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'SERMON_LANGUAGE',
				// typeAlias
				'com_sermondistributor.sermon_language',
				// idHolder
				'id',
				// titleHolder
				'language',
				// tableName
				'#__sermondistributor_sermon_language',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Preacher_language in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'PREACHER_LANGUAGE',
				// typeAlias
				'com_sermondistributor.preacher_language',
				// idHolder
				'id',
				// titleHolder
				'language',
				// tableName
				'#__sermondistributor_preacher_language',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);

			// Add/Update Church_elder in the action logs config table.
			$this->setActionLogConfig(
				// typeTitle
				'CHURCH_ELDER',
				// typeAlias
				'com_sermondistributor.church_elder',
				// idHolder
				'id',
				// titleHolder
				'elder',
				// tableName
				'#__sermondistributor_church_elder',
				// textPrefix
				'COM_SERMONDISTRIBUTOR'
			);
		}

		// move CLI files
		$this->moveCliFiles();

		// remove old files and folders
		$this->removeFiles();

		return true;
	}

	/**
	 * Remove folders with files (with ignore options)
	 *
	 * @param   string	    $dir	 The path to the folder to remove.
	 * @param   array|null  $ignore  The folders and files to ignore and not remove.
	 *
	 * @return  bool   True if all specified files/folders are removed, false otherwise.
	 * @since   3.2.2
	 */
	protected function removeFolder(string $dir, ?array $ignore = null): bool
	{
		if (!is_dir($dir))
		{
			return false;
		}

		$it = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
		$it = new \RecursiveIteratorIterator($it, \RecursiveIteratorIterator::CHILD_FIRST);

		// Remove trailing slash
		$dir = rtrim($dir, '/');

		foreach ($it as $file)
		{
			$filePath = $file->getPathname();
			$relativePath = str_replace($dir . '/', '', $filePath);

			if ($ignore !== null && in_array($relativePath, $ignore, true))
			{
				continue;
			}

			if ($file->isDir())
			{
				Folder::delete($filePath);
			}
			else
			{
				File::delete($filePath);
			}
		}

		// Delete the root folder if there are no ignored files/folders left
		if ($ignore === null || $this->isDirEmpty($dir, $ignore))
		{
			return Folder::delete($dir);
		}

		return true;
	}

	/**
	 * Check if a directory is empty considering ignored files/folders.
	 *
	 * @param   string  $dir	 The path to the folder to check.
	 * @param   array   $ignore  The folders and files to ignore.
	 *
	 * @return  bool    True if the directory is empty or contains only ignored items, false otherwise.
     * @since   3.2.1
	 */
	protected function isDirEmpty(string $dir, array $ignore): bool
	{
		$it = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS);
		foreach ($it as $file)
		{
			$relativePath = str_replace($dir . '/', '', $file->getPathname());
			if (!in_array($relativePath, $ignore, true))
			{
				return false;
			}
		}
		return true;
	}

	/**
	 * Remove the files and folders in the given array from
	 *
	 * @return  void
	 * @since   3.6
	 */
	protected function removeFiles()
	{
		if (!empty($this->deleteFiles))
		{
			foreach ($this->deleteFiles as $file)
			{
				if (is_file(JPATH_ROOT . $file) && !File::delete(JPATH_ROOT . $file))
				{
					echo Text::sprintf('JLIB_INSTALLER_ERROR_FILE_FOLDER', $file) . '<br>';
				}
			}
		}

		if (!empty($this->deleteFolders))
		{
			foreach ($this->deleteFolders as $folder)
			{
				if (is_dir(JPATH_ROOT . $folder) && !Folder::delete(JPATH_ROOT . $folder))
				{
					echo Text::sprintf('JLIB_INSTALLER_ERROR_FILE_FOLDER', $folder) . '<br>';
				}
			}
		}
	}

	/**
	 * Moves the CLI scripts into the CLI folder in the CMS
	 *
	 * @return  void
	 * @since   3.6
	 */
	protected function moveCliFiles()
	{
		if (!empty($this->cliScriptFiles))
		{
			foreach ($this->cliScriptFiles as $file)
			{
				$name = basename($file);

				if (file_exists(JPATH_ROOT . $file) && !File::move(JPATH_ROOT . $file, JPATH_ROOT . '/cli/' . $name))
				{
					echo Text::sprintf('JLIB_INSTALLER_FILE_ERROR_MOVE', $name);
				}
			}
		}
	}

	/**
	 * Set content type integration
	 *
	 * @param   string   $typeTitle
	 * @param   string   $typeAlias
	 * @param   string   $table
	 * @param   string   $rules
	 * @param   string   $fieldMappings
	 * @param   string   $router
	 * @param   string   $contentHistoryOptions
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function setContentType(
		string $typeTitle,
		string $typeAlias,
		string $table,
		string $rules,
		string $fieldMappings,
		string $router,
		string $contentHistoryOptions): void
	{
		// Create the content type object.
		$content = new stdClass();
		$content->type_title = $typeTitle;
		$content->type_alias = $typeAlias;
		$content->table = $table;
		$content->rules = $rules;
		$content->field_mappings = $fieldMappings;
		$content->router = $router;
		$content->content_history_options = $contentHistoryOptions;

		// Check if content type is already in content_type DB.
		$query = $this->db->getQuery(true);
		$query->select($this->db->quoteName(array('type_id')));
		$query->from($this->db->quoteName('#__content_types'));
		$query->where($this->db->quoteName('type_alias') . ' LIKE '. $this->db->quote($content->type_alias));

		$this->db->setQuery($query);
		$this->db->execute();

		// Check if the type alias is already in the content types table.
		if ($this->db->getNumRows())
		{
			$content->type_id = $this->db->loadResult();
			if ($this->db->updateObject('#__content_types', $content, 'type_id'))
			{
				// If its successfully update.
				$this->app->enqueueMessage(
					Text::sprintf('The (%s) was found in the <b>#__content_types</b> table, and updated.', $content->type_alias)
				);
			}
		}
		elseif ($this->db->insertObject('#__content_types', $content))
		{
			// If its successfully added.
			$this->app->enqueueMessage(
				Text::sprintf('The (%s) was added to the <b>#__content_types</b> table.', $content->type_alias)
			);
		}
	}

	/**
	 * Set action log config integration
	 *
	 * @param   string   $typeTitle
	 * @param   string   $typeAlias
	 * @param   string   $idHolder
	 * @param   string   $titleHolder
	 * @param   string   $tableName
	 * @param   string   $textPrefix
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function setActionLogConfig(
		string $typeTitle,
		string $typeAlias,
		string $idHolder,
		string $titleHolder,
		string $tableName,
		string $textPrefix): void
	{
		// Create the content action log config object.
		$content = new stdClass();
		$content->type_title = $typeTitle;
		$content->type_alias = $typeAlias;
		$content->id_holder = $idHolder;
		$content->title_holder = $titleHolder;
		$content->table_name = $tableName;
		$content->text_prefix = $textPrefix;

		// Check if the action log config is already in action_log_config DB.
		$query = $this->db->getQuery(true);
		$query->select($this->db->quoteName(['id']));
		$query->from($this->db->quoteName('#__action_log_config'));
		$query->where($this->db->quoteName('type_alias') . ' LIKE '. $this->db->quote($content->type_alias));

		$this->db->setQuery($query);
		$this->db->execute();

		// Check if the type alias is already in the action log config table.
		if ($this->db->getNumRows())
		{
			$content->id = $this->db->loadResult();
			if ($this->db->updateObject('#__action_log_config', $content, 'id'))
			{
				// If its successfully update.
				$this->app->enqueueMessage(
					Text::sprintf('The (%s) was found in the <b>#__action_log_config</b> table, and updated.', $content->type_alias)
				);
			}
		}
		elseif ($this->db->insertObject('#__action_log_config', $content))
		{
			// If its successfully added.
			$this->app->enqueueMessage(
				Text::sprintf('The (%s) was added to the <b>#__action_log_config</b> table.', $content->type_alias)
			);
		}
	}

	/**
	 * Set action logs extensions integration
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function setActionLogsExtensions(): void
	{
		// Create the extension action logs object.
		$data = new stdClass();
		$data->extension = 'com_sermondistributor';

		// Check if sermondistributor action log extension is already in action logs extensions DB.
		$query = $this->db->getQuery(true);
		$query->select($this->db->quoteName(['id']));
		$query->from($this->db->quoteName('#__action_logs_extensions'));
		$query->where($this->db->quoteName('extension') . ' = '. $this->db->quote($data->extension));

		$this->db->setQuery($query);
		$this->db->execute();

		// Set the object into the action logs extensions table if not found.
		if ($this->db->getNumRows())
		{
			// If its already set don't set it again.
			$this->app->enqueueMessage(
				Text::_('The (com_sermondistributor) is already in the <b>#__action_logs_extensions</b> table.')
			);
		}
		elseif ($this->db->insertObject('#__action_logs_extensions', $data))
		{
			// give a success message
			$this->app->enqueueMessage(
				Text::_('The (com_sermondistributor) was successfully added to the <b>#__action_logs_extensions</b> table.')
			);
		}
	}

	/**
	 * Set global extension assets permission of this component
	 *   (on install only)
	 *
	 * @param   string   $rules   The component rules
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function setAssetsRules(string $rules): void
	{
		// Condition.
		$conditions = [
			$this->db->quoteName('name') . ' = ' . $this->db->quote('com_sermondistributor')
		];

		// Field to update.
		$fields = [
			$this->db->quoteName('rules') . ' = ' . $this->db->quote($rules),
		];

		$query = $this->db->getQuery(true);
		$query->update(
			$this->db->quoteName('#__assets')
		)->set($fields)->where($conditions);

		$this->db->setQuery($query);

		$done = $this->db->execute();
		if ($done)
		{
			// give a success message
			$this->app->enqueueMessage(
				Text::_('The (com_sermondistributor) rules was successfully added to the <b>#__assets</b> table.')
			);
		}
	}

	/**
	 * Set global extension params of this component
	 *   (on install only)
	 *
	 * @param   string   $params   The component rules
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function setExtensionsParams(string $params): void
	{
		// Condition.
		$conditions = [
			$this->db->quoteName('element') . ' = ' . $this->db->quote('com_sermondistributor')
		];

		// Field to update.
		$fields = [
			$this->db->quoteName('params') . ' = ' . $this->db->quote($params),
		];

		$query = $this->db->getQuery(true);
		$query->update(
			$this->db->quoteName('#__extensions')
		)->set($fields)->where($conditions);

		$this->db->setQuery($query);

		$done = $this->db->execute();
		if ($done)
		{
			// give a success message
			$this->app->enqueueMessage(
				Text::_('The (com_sermondistributor) params was successfully added to the <b>#__extensions</b> table.')
			);
		}
	}

	/**
	 * Set database fix (if needed)
	 *  => WHY DO WE NEED AN ASSET TABLE FIX?
	 *   https://git.vdm.dev/joomla/Component-Builder/issues/616#issuecomment-12085
	 *   https://www.mysqltutorial.org/mysql-varchar/
	 *   https://stackoverflow.com/a/15227917/1429677
	 *   https://forums.mysql.com/read.php?24,105964,105964
	 *
	 * @param   int     $accessWorseCase   This is the max rules column size com_sermondistributor would needs.
	 * @param   string  $dataType          This datatype we will change the rules column to if it to small.
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function setDatabaseAssetsRulesFix(int $accessWorseCase, string $dataType): void
	{
		// Get the biggest rule column in the assets table at this point.
		$length = "SELECT CHAR_LENGTH(`rules`) as rule_size FROM #__assets ORDER BY rule_size DESC LIMIT 1";
		$this->db->setQuery($length);
		if ($this->db->execute())
		{
			$rule_length = $this->db->loadResult();
			// Check the size of the rules column
			if ($rule_length <= $accessWorseCase)
			{
				// Fix the assets table rules column size
				$fix = "ALTER TABLE `#__assets` CHANGE `rules` `rules` {$dataType} NOT NULL COMMENT 'JSON encoded access control. Enlarged to {$dataType} by Sermondistributor';";
				$this->db->setQuery($fix);

				$done = $this->db->execute();
				if ($done)
				{
					$this->app->enqueueMessage(
						Text::sprintf('The <b>#__assets</b> table rules column was resized to the %s datatype for the components possible large permission rules.', $dataType)
					);
				}
			}
		}
	}

	/**
	 * Remove remnant data related to this view
	 *
	 * @param   string   $context   The view context
	 * @param   bool     $fields    The switch to also remove related field data
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeViewData(string $context, bool $fields = false): void
	{
		$this->removeContentTypes($context);
		$this->removeViewHistory($context);
		$this->removeUcmContent($context); // this might be obsolete...
		$this->removeContentItemTagMap($context);
		$this->removeActionLogConfig($context);

		if ($fields)
		{
			$this->removeFields($context);
			$this->removeFieldsGroups($context);
		}
	}

	/**
	 * Remove content types related to this view
	 *
	 * @param   string   $context   The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeContentTypes(string $context): void
	{
		// Create a new query object.
		$query = $this->db->getQuery(true);

		// Select id from content type table
		$query->select($this->db->quoteName('type_id'));
		$query->from($this->db->quoteName('#__content_types'));

		// Where Item alias is found
		$query->where($this->db->quoteName('type_alias') . ' = '. $this->db->quote($context));
		$this->db->setQuery($query);

		// Execute query to see if alias is found
		$this->db->execute();
		$found = $this->db->getNumRows();

		// Now check if there were any rows
		if ($found)
		{
			// Since there are load the needed  item type ids
			$ids = $this->db->loadColumn();

			// Remove Item from the content type table
			$condition = [
				$this->db->quoteName('type_alias') . ' = '. $this->db->quote($context)
			];

			// Create a new query object.
			$query = $this->db->getQuery(true);
			$query->delete($this->db->quoteName('#__content_types'));
			$query->where($condition);
			$this->db->setQuery($query);

			// Execute the query to remove Item items
			$done = $this->db->execute();
			if ($done)
			{
				// If successfully remove Item add queued success message.
				$this->app->enqueueMessage(
					Text::sprintf('The (%s) type alias was removed from the <b>#__content_type</b> table.', $context)
				);
			}

			// Make sure that all the items are cleared from DB
			$this->removeUcmBase($ids);
		}
	}

	/**
	 * Remove fields related to this view
	 *
	 * @param   string   $context   The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeFields(string $context): void
	{
		// Create a new query object.
		$query = $this->db->getQuery(true);

		// Select ids from fields
		$query->select($this->db->quoteName('id'));
		$query->from($this->db->quoteName('#__fields'));

		// Where context is found
		$query->where(
			$this->db->quoteName('context') . ' = '. $this->db->quote($context)
		);
		$this->db->setQuery($query);

		// Execute query to see if context is found
		$this->db->execute();
		$found = $this->db->getNumRows();

		// Now check if there were any rows
		if ($found)
		{
			// Since there are load the needed  release_check field ids
			$ids = $this->db->loadColumn();

			// Create a new query object.
			$query = $this->db->getQuery(true);

			// Remove context from the field table
			$condition = [
				$this->db->quoteName('context') . ' = '. $this->db->quote($context)
			];

			$query->delete($this->db->quoteName('#__fields'));
			$query->where($condition);

			$this->db->setQuery($query);

			// Execute the query to remove release_check items
			$done = $this->db->execute();
			if ($done)
			{
				// If successfully remove context add queued success message.
				$this->app->enqueueMessage(
					Text::sprintf('The fields with context (%s) was removed from the <b>#__fields</b> table.', $context)
				);
			}

			// Make sure that all the field values are cleared from DB
			$this->removeFieldsValues($context, $ids);
		}
	}

	/**
	 * Remove fields values related to fields
	 *
	 * @param   string   $context   The view context
	 * @param   array    $ids       The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeFieldsValues(string $context, array $ids): void
	{
		$condition = [
			$this->db->quoteName('field_id') . ' IN ('. implode(',', $ids) .')'
		];

		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->delete($this->db->quoteName('#__fields_values'));
		$query->where($condition);
		$this->db->setQuery($query);

		// Execute the query to remove field values
		$done = $this->db->execute();
		if ($done)
		{
			// If successfully remove release_check add queued success message.
			$this->app->enqueueMessage(
				Text::sprintf('The fields values for (%s) was removed from the <b>#__fields_values</b> table.', $context)
			);
		}
	}

	/**
	 * Remove fields groups related to fields
	 *
	 * @param   string   $context   The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeFieldsGroups(string $context): void
	{
		// Create a new query object.
		$query = $this->db->getQuery(true);

		// Select ids from fields
		$query->select($this->db->quoteName('id'));
		$query->from($this->db->quoteName('#__fields_groups'));

		// Where context is found
		$query->where(
			$this->db->quoteName('context') . ' = '. $this->db->quote($context)
		);
		$this->db->setQuery($query);

		// Execute query to see if context is found
		$this->db->execute();
		$found = $this->db->getNumRows();

		// Now check if there were any rows
		if ($found)
		{
			// Create a new query object.
			$query = $this->db->getQuery(true);

			// Remove context from the field table
			$condition = [
				$this->db->quoteName('context') . ' = '. $this->db->quote($context)
			];

			$query->delete($this->db->quoteName('#__fields_groups'));
			$query->where($condition);

			$this->db->setQuery($query);

			// Execute the query to remove release_check items
			$done = $this->db->execute();
			if ($done)
			{
				// If successfully remove context add queued success message.
				$this->app->enqueueMessage(
					Text::sprintf('The fields with context (%s) was removed from the <b>#__fields_groups</b> table.', $context)
				);
			}
		}
	}

	/**
	 * Remove history related to this view
	 *
	 * @param   string   $context   The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeViewHistory(string $context): void
	{
		// Remove Item items from the ucm content table
		$condition = [
			$this->db->quoteName('item_id') . ' LIKE ' . $this->db->quote($context . '.%')
		];

		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->delete($this->db->quoteName('#__history'));
		$query->where($condition);
		$this->db->setQuery($query);

		// Execute the query to remove Item items
		$done = $this->db->execute();
		if ($done)
		{
			// If successfully removed Items add queued success message.
			$this->app->enqueueMessage(
				Text::sprintf('The (%s) items were removed from the <b>#__history</b> table.', $context)
			);
		}
	}

	/**
	 * Remove ucm base values related to these IDs
	 *
	 * @param   array   $ids   The type ids
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeUcmBase(array $ids): void
	{
		// Make sure that all the items are cleared from DB
		foreach ($ids as $type_id)
		{
			// Remove Item items from the ucm base table
			$condition = [
				$this->db->quoteName('ucm_type_id') . ' = ' . $type_id
			];

			// Create a new query object.
			$query = $this->db->getQuery(true);
			$query->delete($this->db->quoteName('#__ucm_base'));
			$query->where($condition);
			$this->db->setQuery($query);

			// Execute the query to remove Item items
			$this->db->execute();
		}

		$this->app->enqueueMessage(
			Text::_('All related items was removed from the <b>#__ucm_base</b> table.')
		);
	}

	/**
	 * Remove ucm content values related to this view
	 *
	 * @param   string   $context   The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeUcmContent(string $context): void
	{
		// Remove Item items from the ucm content table
		$condition = [
			$this->db->quoteName('core_type_alias') . ' = ' . $this->db->quote($context)
		];

		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->delete($this->db->quoteName('#__ucm_content'));
		$query->where($condition);
		$this->db->setQuery($query);

		// Execute the query to remove Item items
		$done = $this->db->execute();
		if ($done)
		{
			// If successfully removed Item add queued success message.
			$this->app->enqueueMessage(
				Text::sprintf('The (%s) type alias was removed from the <b>#__ucm_content</b> table.', $context)
			);
		}
	}

	/**
	 * Remove content item tag map related to this view
	 *
	 * @param   string   $context   The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeContentItemTagMap(string $context): void
	{
		// Create a new query object.
		$query = $this->db->getQuery(true);

		// Remove Item items from the contentitem tag map table
		$condition = [
			$this->db->quoteName('type_alias') . ' = '. $this->db->quote($context)
		];

		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->delete($this->db->quoteName('#__contentitem_tag_map'));
		$query->where($condition);
		$this->db->setQuery($query);

		// Execute the query to remove Item items
		$done = $this->db->execute();
		if ($done)
		{
			// If successfully remove Item add queued success message.
			$this->app->enqueueMessage(
				Text::sprintf('The (%s) type alias was removed from the <b>#__contentitem_tag_map</b> table.', $context)
			);
		}
	}

	/**
	 * Remove action log config related to this view
	 *
	 * @param   string   $context   The view context
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeActionLogConfig(string $context): void
	{
		// Remove sermondistributor view from the action_log_config table
		$condition = [
			$this->db->quoteName('type_alias') . ' = '. $this->db->quote($context)
		];

		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->delete($this->db->quoteName('#__action_log_config'));
		$query->where($condition);
		$this->db->setQuery($query);

		// Execute the query to remove com_sermondistributor.view
		$done = $this->db->execute();
		if ($done)
		{
			// If successfully removed sermondistributor view add queued success message.
			$this->app->enqueueMessage(
				Text::sprintf('The (%s) type alias was removed from the <b>#__action_log_config</b> table.', $context)
			);
		}
	}

	/**
	 * Remove Asset Table Integrated
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeAssetData(): void
	{
		// Remove sermondistributor assets from the assets table
		$condition = [
			$this->db->quoteName('name') . ' LIKE ' . $this->db->quote('com_sermondistributor.%')
		];

		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->delete($this->db->quoteName('#__assets'));
		$query->where($condition);
		$this->db->setQuery($query);
		$done = $this->db->execute();
		if ($done)
		{
			// If successfully removed sermondistributor add queued success message.
			$this->app->enqueueMessage(
				Text::_('All related (com_sermondistributor) items was removed from the <b>#__assets</b> table.')
			);
		}
	}

	/**
	 * Remove action logs extensions integrated
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeActionLogsExtensions(): void
	{
		// Remove sermondistributor from the action_logs_extensions table
		$extension = [
			$this->db->quoteName('extension') . ' = ' . $this->db->quote('com_sermondistributor')
		];

		// Create a new query object.
		$query = $this->db->getQuery(true);
		$query->delete($this->db->quoteName('#__action_logs_extensions'));
		$query->where($extension);
		$this->db->setQuery($query);

		// Execute the query to remove sermondistributor
		$done = $this->db->execute();
		if ($done)
		{
			// If successfully remove sermondistributor add queued success message.
			$this->app->enqueueMessage(
				Text::_('The (com_sermondistributor) extension was removed from the <b>#__action_logs_extensions</b> table.')
			);
		}
	}

	/**
	 * Remove remove database fix (if possible)
	 *
	 * @return void
	 * @since  4.4.2
	 */
	protected function removeDatabaseAssetsRulesFix(): void
	{
		// Get the biggest rule column in the assets table at this point.
		$length = "SELECT CHAR_LENGTH(`rules`) as rule_size FROM #__assets ORDER BY rule_size DESC LIMIT 1";
		$this->db->setQuery($length);
		if ($this->db->execute())
		{
			$rule_length = $this->db->loadResult();
			// Check the size of the rules column
			if ($rule_length < 5120)
			{
				// Revert the assets table rules column back to the default
				$revert_rule = "ALTER TABLE `#__assets` CHANGE `rules` `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.';";
				$this->db->setQuery($revert_rule);
				$this->db->execute();

				$this->app->enqueueMessage(
					Text::_('Reverted the <b>#__assets</b> table rules column back to its default size of varchar(5120).')
				);
			}
			else
			{
				$this->app->enqueueMessage(
					Text::_('Could not revert the <b>#__assets</b> table rules column back to its default size of varchar(5120), since there is still one or more components that still requires the column to be larger.')
				);
			}
		}
	}

	/**
	 * Ensures that a class in the namespace is available.
	 * If the class is not already loaded, it attempts to load it via the specified autoloader.
	 *
	 * @param string  $className   The fully qualified name of the class to check.
	 *
	 * @return bool True if the class exists or was successfully loaded, false otherwise.
	 * @since  4.0.1
	 */
	protected function classExists(string $className): bool
	{
		if (class_exists($className, true))
		{
			return true;
		}

		// Autoloaders to check
		$autoloaders = [
			__DIR__ . '/SermondistributorInstallerPowerloader.php',
			JPATH_ADMINISTRATOR . '/components/com_sermondistributor/src/Helper/PowerloaderHelper.php'
		];

		foreach ($autoloaders as $autoloader)
		{
			if (file_exists($autoloader))
			{
				require_once $autoloader;

				if (class_exists($className, true))
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * Method to move folders into place.
	 *
	 * @param   InstallerAdapter  $adapter  The adapter calling this method
	 *
	 * @return void
	 * @since 4.4.2
	 */
	protected function moveFolders(InstallerAdapter $adapter): void
	{
		// get the installation path
		$installer = $adapter->getParent();
		$installPath = $installer->getPath('source');
		// get all the folders
		$folders = Folder::folders($installPath);
		// check if we have folders we may want to copy
		$doNotCopy = ['media','admin','site']; // Joomla already deals with these
		if (count((array) $folders) > 1)
		{
			foreach ($folders as $folder)
			{
				// Only copy if not a standard folders
				if (!in_array($folder, $doNotCopy))
				{
					// set the source path
					$src = $installPath.'/'.$folder;
					// set the destination path
					$dest = JPATH_ROOT.'/'.$folder;
					// now try to copy the folder
					if (!Folder::copy($src, $dest, '', true))
					{
						$this->app->enqueueMessage('Could not copy '.$folder.' folder into place, please make sure destination is writable!', 'error');
					}
				}
			}
		}
	}
}
