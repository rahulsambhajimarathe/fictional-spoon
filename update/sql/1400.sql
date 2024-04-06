UPDATE `settings` SET `value` = '{\"version\":\"14.0.0\", \"code\":\"1400\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('cookie_consent', '{}');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('discord', '{}');

-- SEPARATOR --

alter table pages add open_in_new_tab tinyint default 1 null after position;

-- SEPARATOR --

update notifications set type='CONVERSIONS' where type = 'LATEST_CONVERSION';

-- SEPARATOR --

update track_conversions set type='conversions' where type = 'latest_conversion';

-- SEPARATOR --

update notifications set type='REVIEWS' where type = 'RANDOM_REVIEW';

-- SEPARATOR --

update track_conversions set type='reviews' where type = 'random_review';

-- SEPARATOR --

UPDATE `settings` SET `key` = 'notifications' WHERE `key` = 'socialproofo';
