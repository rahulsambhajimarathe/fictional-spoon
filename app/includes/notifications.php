<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$pro_notifications = \Altum\Plugin::is_active('pro-notifications') && file_exists(\Altum\Plugin::get('pro-notifications')->path . 'pro_notifications.php') ? include \Altum\Plugin::get('pro-notifications')->path . 'pro_notifications.php' : [];

/* Current available type of notifications and its defaults */
return array_merge(
    [
        'INFORMATIONAL' => [
            'type' => 'default',
            'title' => l('notification.informational.title_default'),
            'description' => l('notification.informational.description_default'),
            'image' => l('notification.informational.image_default'),
            'image_alt' => '',
            'url'   => '',
            'url_new_tab' => true,

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',

            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'COUPON' => [
            'type' => 'default',
            'title' => l('notification.coupon.title_default'),
            'description' => l('notification.coupon.description_default'),
            'image' => l('notification.coupon.image_default'),
            'image_alt' => '',
            'coupon_code' => l('notification.coupon.coupon_code_default'),
            'button_url'   => '',
            'url_new_tab' => true,
            'button_text'  => l('notification.coupon.button_text_default'),
            'footer_text'  => l('notification.coupon.footer_text_default'),

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'button_background_color' => '#000',
            'button_color' => '#fff',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'LIVE_COUNTER' => [
            'type' => 'default',
            'description' => l('notification.live_counter.description_default'),
            'last_activity' => 15,
            'url'   => '',
            'url_new_tab' => true,

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',

            'direction' => l('direction'),
            'display_trigger_value' => 2,
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_minimum_activity' => 0,
            'display_frequency' => 'all_time',
            'display_close_button' => true,
            'display_branding' => true,
            'display_mobile' => true,
            'display_desktop' => true,

            'number_color' => '#fff',
            'number_background_color' => '#000',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
            'pulse_background_color' => '#17bf21',
        ],

        'EMAIL_COLLECTOR' => [
            'type' => 'default',
            'title' => l('notification.email_collector.title_default'),
            'description' => l('notification.email_collector.description_default'),
            'name_placeholder' => l('notification.email_collector.name_placeholder_default'),
            'email_placeholder' => l('notification.email_collector.email_placeholder_default'),
            'button_text' => l('notification.email_collector.button_text_default'),
            'show_agreement' => false,
            'agreement_text' => l('notification.email_collector.agreement_text_default'),
            'agreement_url' => '',
            'thank_you_url' => '',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'button_background_color' => '#272727',
            'button_color' => '#fff',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',

            'data_send_is_enabled' => 0,
            'data_send_webhook' => '',
            'data_send_email' => '',
        ],

        'CONVERSIONS' => [
            'type' => 'default',
            'title' => l('notification.conversions.title_default'),
            'description' => l('notification.conversions.description_default'),
            'image' => l('notification.conversions.image_default'),
            'image_alt' => '',
            'url'   => '',
            'url_new_tab' => true,
            'conversions_count' => 1,
            'in_between_delay' => 3,
            'order' => 'descending',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,


            'direction' => l('direction'),
            'display_minimum_activity' => 0,
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'title_color' => '#000',
            'date_color' => '#808080',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',

            'data_trigger_auto' => false,
            'data_triggers_auto' => []
        ],

        'CONVERSIONS_COUNTER' => [
            'type' => 'default',
            'title' => l('notification.conversions_counter.title_default'),
            'last_activity' => 2,
            'url' => '',
            'url_new_tab' => true,

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',

            'direction' => l('direction'),
            'display_trigger_value' => 2,
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_minimum_activity' => 0,
            'display_frequency' => 'all_time',
            'display_close_button' => false,
            'display_branding' => true,
            'display_mobile' => true,
            'display_desktop' => true,

            'number_color' => '#fff',
            'number_background_color' => '#000',
            'title_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',

            'data_trigger_auto' => false,
            'data_triggers_auto' => []
        ],

        'VIDEO' => [
            'type' => 'default',
            'title' => l('notification.video.title_default'),
            'video' => '',
            'button_url'   => '',
            'button_text'  => l('notification.video.button_text_default'),
            'url_new_tab' => '',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'title_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'button_background_color' => '#000',
            'button_color' => '#fff',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'SOCIAL_SHARE' => [
            'type' => 'default',
            'title' => l('notification.social_share.title_default'),
            'description' => l('notification.social_share.description_default'),
            'share_url'   => '',
            'share_facebook' => true,
            'share_twitter' => true,
            'share_linkedin' => true,
            'share_pinterest' => true,
            'share_reddit' => true,

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'REVIEWS' => [
            'type' => 'default',
            'url'   => '',
            'url_new_tab' => true,
            'reviews_count' => 1,
            'title' => l('notification.reviews.title_default'),
            'description' => l('notification.reviews.description_default'),
            'image' => l('notification.reviews.image_default'),
            'image_alt' => '',
            'stars' => 5,
            'in_between_delay' => 3,
            'order' => 'random',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => false,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'EMOJI_FEEDBACK' => [
            'type' => 'default',
            'title' => l('notification.emoji_feedback.title_default'),
            'show_angry' => true,
            'show_sad' => true,
            'show_neutral' => true,
            'show_happy' => true,
            'show_excited' => true,
            'thank_you_url' => '',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => false,
            'display_branding' => true,

            'title_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'COOKIE_NOTIFICATION' => [
            'type' => 'default',
            'description' => l('notification.cookie_notification.description_default'),
            'image' => l('notification.cookie_notification.image_default'),
            'image_alt' => '',
            'url_text' => l('notification.cookie_notification.url_text_default'),
            'url' => '',
            'url_new_tab' => true,
            'button_text'  => l('notification.cookie_notification.button_text_default'),

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'button_background_color' => '#000',
            'button_color' => '#fff',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'SCORE_FEEDBACK' => [
            'type' => 'default',
            'title' => l('notification.score_feedback.title_default'),
            'description' => l('notification.score_feedback.description_default'),
            'thank_you_url' => '',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => false,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'button_background_color' => '#000',
            'button_color' => '#fff',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],

        'REQUEST_COLLECTOR' => [
            'type' => 'default',
            'title' => l('notification.request_collector.title_default'),
            'description' => l('notification.request_collector.description_default'),
            'image' => l('notification.request_collector.image_default'),
            'image_alt' => '',
            'content_title' => l('notification.request_collector.content_title_default'),
            'content_description' => l('notification.request_collector.content_description_default'),
            'input_placeholder' => l('notification.request_collector.input_placeholder_default'),
            'button_text' => l('notification.request_collector.button_text_default'),
            'show_agreement' => false,
            'agreement_text' => l('notification.request_collector.agreement_text_default'),
            'agreement_url' => '',
            'thank_you_url' => '',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => false,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'content_title_color' => '#000',
            'content_description_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'button_background_color' => '#000',
            'button_color' => '#fff',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',

            'data_send_is_enabled' => 0,
            'data_send_webhook' => '',
            'data_send_email' => '',
        ],

        'COUNTDOWN_COLLECTOR' => [
            'type' => 'default',
            'title' => l('notification.countdown_collector.title_default'),
            'description' => l('notification.countdown_collector.description_default'),
            'content_title' => l('notification.countdown_collector.content_title_default'),
            'input_placeholder' => l('notification.countdown_collector.input_placeholder_default'),
            'button_text' => l('notification.countdown_collector.button_text_default'),
            'end_date' => (new \DateTime())->modify('+5 hours')->format('Y-m-d H:i:s'),
            'show_agreement' => false,
            'agreement_text' => l('notification.countdown_collector.agreement_text_default'),
            'agreement_url' => '',
            'thank_you_url' => '',

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => false,
            'display_branding' => true,

            'title_color' => '#000',
            'description_color' => '#000',
            'content_title_color' => '#000',
            'time_color' => '#fff',
            'time_background_color' => '#000',
            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',
            'button_background_color' => '#000',
            'button_color' => '#fff',
            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',

            'data_send_is_enabled' => 0,
            'data_send_webhook' => '',
            'data_send_email' => '',
        ],

        'CUSTOM_HTML' => [
            'type' => 'default',
            'html' => l('notification.custom_html.html_default'),

            'trigger_all_pages' => true,
            'triggers' => [],
            'display_trigger' => 'delay',
            'display_trigger_value' => 2,
            'display_frequency' => 'all_time',
            'display_mobile' => true,
            'display_desktop' => true,

            'direction' => l('direction'),
            'display_duration' => 5,
            'display_position' => 'bottom_left',
            'display_close_button' => true,
            'display_branding' => true,

            'background_color' => '#fff',
            'background_pattern' => false,
            'background_pattern_svg' => '',

            'border_radius' => 'rounded',
            'border_color' => '#000',
            'border_width' => 0,
            'shadow'        => true,

            'on_animation' => 'fadeIn',
            'off_animation' => 'fadeOut',
            'animation' => '',
            'animation_interval' => '3',
            'font' => 'inherit',
            'close_button_color' => '#808080',
        ],
    ],
    $pro_notifications
);
