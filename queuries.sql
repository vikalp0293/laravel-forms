ALTER TABLE `m_destinations` ADD `description` TEXT NULL AFTER `name`, ADD `file` TEXT NULL AFTER `description`;
ALTER TABLE `m_destinations` ADD `thumbnail` TEXT NULL AFTER `file`;