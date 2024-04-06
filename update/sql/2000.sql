UPDATE `settings` SET `value` = '{\"version\":\"20.0.0\", \"code\":\"2000\"}' WHERE `key` = 'product_info';
-- EXTENDED SEPARATOR --

alter table taxes modify value float null;
