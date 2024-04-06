<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Models\User;

class Pixel extends Controller {

    public function index() {
        $seconds_to_cache = settings()->notifications->pixel_cache;
        header('Content-Type: application/javascript');
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $seconds_to_cache) . ' GMT');
        header('Pragma: cache');
        header('Cache-Control: max-age=' . $seconds_to_cache);

        /* Check against bots */
        $CrawlerDetect = new \Jaybizzle\CrawlerDetect\CrawlerDetect();

        if($CrawlerDetect->isCrawler()) {
            die("console.log('" . settings()->main->title . " (" . SITE_URL. "): Bot detected.')");
        }

        $pixel_key = isset($this->params[0]) ? query_clean($this->params[0]) : false;
        $date = \Altum\Date::$date;

        /* Get the details of the campaign from the database */
        $campaign = (new \Altum\Models\Campaign())->get_campaign_by_pixel_key($pixel_key);

        /* Make sure the campaign has access */
        if(!$campaign) {
            die("console.log('" . settings()->main->title . " (" . SITE_URL. "): No campaign found for this pixel.')");
        }

        if(!$campaign->is_enabled) {
            die("console.log('" . settings()->main->title . " (" . SITE_URL. "): Campaign disabled.')");
        }

        /* Make sure to get the user data and confirm the user is ok */
        $user = (new \Altum\Models\User())->get_user_by_user_id($campaign->user_id);

        if(!$user) {
            die("console.log('" . settings()->main->title . " (" . SITE_URL. "): Campaign owner not found.')");
        }

        if($user->status != 1) {
            die("console.log('" . settings()->main->title . " (" . SITE_URL. "): Campaign owner is disabled.')");
        }

        /* Process the plan of the user */
        (new User())->process_user_plan_expiration_by_user($user);

        /* Make sure that the user didnt exceed the current plan */
        if($user->plan_settings->notifications_impressions_limit != -1 && $user->current_month_notifications_impressions >= $user->plan_settings->notifications_impressions_limit) {
            die("console.log('" . settings()->main->title . " (" . SITE_URL. "): Notification impressions limit exceeded.')");
        }

        /* Set the default language depending on the user */
        \Altum\Language::set_by_name($user->language);

        /* Get default settings for the notifications */
        $notifications_config = \Altum\Notification::get_config();

        /* Find all the campaigns for the domain */
        $domain = query_clean(parse_url(trim(query_clean($_SERVER['HTTP_REFERER'])))['host']);

        /* Remove www. from the host */
        $prefix = 'www.';

        if(mb_substr($domain, 0, mb_strlen($prefix)) == $prefix) {
            $domain = mb_substr($domain, mb_strlen($prefix));
        }

        /* Get all the available notifications for this campaign */
        $notifications_result = database()->query("
            SELECT `notifications`.*
            FROM 
                `notifications`
            WHERE 
                `notifications`.`user_id` = {$user->user_id} AND
                `notifications`.`campaign_id` = {$campaign->campaign_id} AND
                `notifications`.`is_enabled` = 1 
        ");

        /* Loop over everything, get extra data if needed and return for the view to use */
        $notifications = [];

        while($notification = $notifications_result->fetch_object()) {

            $notification->settings = json_decode($notification->settings ?? '');

            /* Default notification settings merging */
            $notification->settings = (object) array_merge((array) $notifications_config[$notification->type], (array) $notification->settings ?? '');

            /* Get the custom branding details */
            $notification->branding = json_decode($campaign->branding);

            /* Extra details and data gathering if needed */
            switch($notification->type) {
                case 'CONVERSIONS':

                    $order_by = ($notification->settings->order ?? 'descending') == 'descending' ? '`datetime` DESC' : 'RAND()';

                    $result = database()->query("
                        SELECT
                            `data`, `location`, `datetime`
                        FROM
                            `track_conversions`
                        WHERE
                            `notification_id` = {$notification->notification_id}
                        ORDER BY
                            {$order_by}
                        LIMIT 
                            {$notification->settings->conversions_count}
                    ");

                    /* If we do not have any conversions */
                    if(!$result->num_rows) {
                        /* Default value for the person, if the name is not found later */
                        $notification->title = $notification->settings->title;
                        $notification->description = $notification->settings->description;

                        $notifications[] = $notification;
                    } else {

                        $i = 0;

                        /* Save the original value for the delay */
                        $notification->settings->display_trigger_value_original = $notification->settings->display_trigger_value;

                        while($conversion = $result->fetch_object()) {
                            /* Default value for the person, if the name is not found later */
                            $notification->title = $notification->settings->title;
                            $notification->description = $notification->settings->description;
                            $notification->image = $notification->settings->image;
                            $notification->image_alt = $notification->settings->image_alt;
                            $notification->url = $notification->settings->url;

                            /* Decode the conversion data */
                            $conversion->data = json_decode($conversion->data, true);

                            if($conversion->data) {
                                /* Try to get the location data parsed if possible */
                                $location = json_decode($conversion->location, true) ?? [];
                                $conversion->data = array_merge($location, $conversion->data);

                                foreach(['title', 'description', 'image', 'url'] as $key) {
                                    /* Get all available variables from the conversion who */
                                    preg_match_all(
                                        '/{([a-zA-Z0-9_\-\.]+)}+/',
                                        $notification->settings->{$key},
                                        $matches
                                    );

                                    foreach($matches[1] as $value) {
                                        $notification->{$key} = str_replace(
                                            '{' . $value . '}',
                                            htmlspecialchars($conversion->data[$value] ?? '', ENT_QUOTES, 'UTF-8'),
                                            $notification->{$key}
                                        );
                                    }
                                }


                                /* Set the date of the conversion */
                                $notification->last_action_date = $conversion->datetime;

                                /* Change the delay of the notifications if needed */
                                if($notification->settings->display_trigger == 'delay') {

                                    $notification->settings->display_trigger_value = $i == 0 ?
                                            $notification->settings->display_trigger_value :
                                            $notification->settings->display_trigger_value_original + ($i * $notification->settings->in_between_delay);
                                }

                                /* Hackish workaround */
                                $notification_settings = clone $notification->settings;
                                $notification_to_add = clone $notification;
                                $notification_to_add->settings = $notification_settings;

                                /* Add to the notifications array */
                                $notifications[] = $notification_to_add;

                                $i++;
                            }
                        }

                    }

                    break;

                case 'CONVERSIONS_COUNTER':

                    $date_start = (new \DateTime())->modify('-' . $notification->settings->last_activity . ' hour')->format('Y-m-d H:i:s');

                    $notification->counter = database()->query("
                        SELECT
                            COUNT(`id`) AS `total`
                        FROM
                            `track_conversions`
                        WHERE
                            `notification_id` = {$notification->notification_id}
                        AND (`datetime` BETWEEN '{$date_start}' AND '{$date}')
                    ")->fetch_object()->total;

                    break;

                case 'LIVE_COUNTER':

                    $date_start = (new \DateTime())->modify('-' . $notification->settings->last_activity . ' minute')->format('Y-m-d H:i:s');

                    $notification->counter = database()->query("
                        SELECT
                            COUNT(DISTINCT `ip_binary`) AS `total`
                        FROM
                            `track_logs`
                        WHERE
                            `user_id` = {$user->user_id}
                        AND `domain` = '{$domain}'
                        AND (`datetime` BETWEEN '{$date_start}' AND '{$date}')
                    ")->fetch_object()->total;

                    break;

                case 'REVIEWS':

                    $order_by = ($notification->settings->order ?? 'descending') == 'descending' ? '`datetime` DESC' : 'RAND()';

                    $result = database()->query("
                        SELECT
                            `data`
                        FROM
                            `track_conversions`
                        WHERE
                            `notification_id` = {$notification->notification_id}
                        ORDER BY
                            {$order_by}
                        LIMIT {$notification->settings->reviews_count}
                    ");

                    /* If we do not have any added reviews */
                    if(!$result->num_rows) {
                        $notifications[] = $notification;
                    } else {

                        $i = 0;

                        /* Save the original value for the delay */
                        $notification->settings->display_trigger_value_original = $notification->settings->display_trigger_value;

                        while($review = $result->fetch_object()) {

                            /* Decode the data */
                            $review->data = json_decode($review->data, true);

                            if($review->data) {
                                $notification->settings->title = $review->data['title'];
                                $notification->settings->description = $review->data['description'];
                                $notification->settings->image = !empty($review->data['image']) ? $review->data['image'] : $notification->settings->image;
                                $notification->settings->stars = (int) $review->data['stars'];

                                /* Change the delay of the notifications if needed */
                                if($notification->settings->display_trigger == 'delay') {

                                    $notification->settings->display_trigger_value = $i == 0 ?
                                        $notification->settings->display_trigger_value :
                                        $notification->settings->display_trigger_value_original + ($i * $notification->settings->in_between_delay);
                                }

                                /* Hackish */
                                $notification_settings = clone $notification->settings;
                                $notification_to_add = clone $notification;
                                $notification_to_add->settings = $notification_settings;

                                /* Add to the notifications array */
                                $notifications[] = $notification_to_add;

                                $i++;
                            }
                        }

                    }

                    break;
            }


            /* Conversions / Reviews adds the notifications by itself */
            if(!in_array($notification->type, ['CONVERSIONS', 'REVIEWS'])) {
                $notifications[] = $notification;
            }
        }

        /* Main View */
        $data = [
            'notifications'         => $notifications,
            'pixel_key'             => $pixel_key,
            'campaign'              => $campaign,
            'user'                  => $user
        ];

        $view = new \Altum\View('pixel/index', (array) $this);

        echo $view->run($data);

    }

}
