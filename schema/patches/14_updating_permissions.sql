ALTER TABLE `mpws_permissions` ADD `CanMaintain` BOOLEAN NOT NULL DEFAULT FALSE AFTER `CanAddUsers`;
UPDATE  `mpws_patches` SET LastPatchNo=14