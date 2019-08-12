/*
 * Adding the parent id
 */
ALTER TABLE `tenants`
	ADD `parent_id` MEDIUMINT(9) UNSIGNED;

ALTER TABLE `tenants`
	ADD FOREIGN KEY (`parent_id`) REFERENCES `tenants`(`id`) ON DELETE SET NULL;
