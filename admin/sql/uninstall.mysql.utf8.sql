DROP TABLE IF EXISTS `#__sermondistributor_preacher`;
DROP TABLE IF EXISTS `#__sermondistributor_sermon`;
DROP TABLE IF EXISTS `#__sermondistributor_series`;
DROP TABLE IF EXISTS `#__sermondistributor_statistic`;
DROP TABLE IF EXISTS `#__sermondistributor_external_source`;
DROP TABLE IF EXISTS `#__sermondistributor_local_listing`;
DROP TABLE IF EXISTS `#__sermondistributor_help_document`;


--
-- Always insure this column rules is reversed to Joomla defaults on uninstall. (as on 1st Dec 2020)
--
ALTER TABLE `#__assets` CHANGE `rules` `rules` varchar(5120) NOT NULL COMMENT 'JSON encoded access control.';

--
-- Always insure this column name is reversed to Joomla defaults on uninstall. (as on 1st Dec 2020).
--
ALTER TABLE `#__assets` CHANGE `name` `name` VARCHAR(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'The unique name for the asset.';
