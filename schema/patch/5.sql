ALTER TABLE `mpws_customer` ADD `SnapshotURL` VARCHAR(300) NOT NULL AFTER `Plugins`;
ALTER TABLE `mpws_customer` ADD `SitemapURL` VARCHAR(500) NOT NULL AFTER `SnapshotURL`;