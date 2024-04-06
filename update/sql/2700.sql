UPDATE `settings` SET `value` = '{\"version\":\"27.0.0\", \"code\":\"2700\"}' WHERE `key` = 'product_info';

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('languages', '{}');

-- SEPARATOR --

INSERT INTO `settings` (`key`, `value`) VALUES ('content', '{"blog_is_enabled":true,"blog_share_is_enabled":true,"blog_categories_widget_is_enabled":true,"blog_popular_widget_is_enabled":true,"blog_views_is_enabled":true,"pages_is_enabled":true,"pages_share_is_enabled":true,"pages_popular_widget_is_enabled":true,"pages_views_is_enabled":true}');
