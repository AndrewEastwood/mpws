ALTER TABLE `mpws_tasks` CHANGE `Result` `Result` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL;
UPDATE  `mpws_patches` SET LastPatchNo=10