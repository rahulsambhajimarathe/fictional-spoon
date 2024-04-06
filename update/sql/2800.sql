UPDATE `settings` SET `value` = '{\"version\":\"28.0.0\", \"code\":\"2800\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT IGNORE INTO `settings` (`key`, `value`) VALUES ('languages', '{}');
