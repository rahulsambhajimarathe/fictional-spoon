<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;
use Altum\Date;
use Altum\Title;

class Notification extends Controller {
    public $notification_id;
    public $notification;
    public $campaign;

    public function index() {

        \Altum\Authentication::guard();

        require_once APP_PATH . 'includes/notifications_background_patterns.php';

        $this->notification_id = isset($this->params[0]) ? (int) query_clean($this->params[0]) : null;
        $method = isset($this->params[1]) && in_array($this->params[1], ['settings', 'statistics', 'data']) ? $this->params[1] : 'settings';

        /* Make sure the notification exists and is accessible to the user */
        $this->notification = db()->where('notification_id', $this->notification_id)->where('user_id', $this->user->user_id)->getOne('notifications');

        if(!$this->notification) {
            redirect('dashboard');
        }

        /* Get the associated campaign */
        $this->campaign = db()->where('campaign_id', $this->notification->campaign_id)->getOne('campaigns');
        $this->campaign->branding = json_decode($this->campaign->branding);

        /* Set the branding, if needed, based on the campaign */
        if($this->campaign->branding && $this->campaign->branding->name && $this->campaign->branding->url) {
            $this->notification->branding = $this->campaign->branding;
        }

        /* Get the settings of the notification */
        $this->notification->settings = json_decode($this->notification->settings ?? '');

        switch($this->notification->type)  {
            case 'INFORMATIONAL':
            case 'COUPON':
            case 'LIVE_COUNTER':
            case 'VIDEO':
            case 'SOCIAL_SHARE':
            case 'EMOJI_FEEDBACK':
            case 'COOKIE_NOTIFICATION':
            case 'SCORE_FEEDBACK':
            case 'INFORMATIONAL_BAR':
            case 'IMAGE':
            case 'COUPON_BAR':
            case 'BUTTON_BAR':
            case 'BUTTON_MODAL':
            case 'ENGAGEMENT_LINKS':
            case 'WHATSAPP_CHAT':
            case 'CUSTOM_HTML':
            case 'CONTACT_US':

                $this->notification->settings->enabled_methods = ['statistics', 'settings'];
                $this->notification->settings->enabled_settings_tabs = ['basic', 'display', 'customize', 'triggers'];

                break;

            case 'EMAIL_COLLECTOR':
            case 'CONVERSIONS':
            case 'CONVERSIONS_COUNTER':
            case 'REQUEST_COLLECTOR':
            case 'COUNTDOWN_COLLECTOR':
            case 'COLLECTOR_BAR':
            case 'COLLECTOR_MODAL':
            case 'COLLECTOR_TWO_MODAL':
            case 'TEXT_FEEDBACK':

                $this->notification->settings->enabled_methods = ['statistics', 'settings', 'data'];
                $this->notification->settings->enabled_settings_tabs = ['basic', 'display', 'customize', 'triggers', 'data'];

            break;

            case 'REVIEWS':

                $this->notification->settings->enabled_methods = ['statistics', 'settings', 'data'];
                $this->notification->settings->enabled_settings_tabs = ['basic', 'display', 'customize', 'triggers'];

            break;
        }

        /* Prepare the menu View */
        $data = [
            'notification' => $this->notification,
            'method' => $method
        ];

        $view = new \Altum\View('notification/menu', (array) $this);

        $this->add_view_content('method_menu', $view->run($data));

        /* Handle code for different parts of the page */
        switch($method) {
            case 'settings':

                /* Handle form submission */
                $this->process_settings_post();

                /* Prepare the method View */
                $data = [
                    'notification'  => $this->notification,
                    'method'        => $method
                ];

                $view = new \Altum\View('notification/' . $method . '.method', (array) $this);

                $this->add_view_content('method', $view->run($data));

                break;


            case 'statistics':

                $action = isset($this->params[2]) && in_array($this->params[2], ['reset']) ? $this->params[2] : null;

                if($action) {
                    switch($action) {
                        case 'reset':

                            if(empty($_POST)) {
                                redirect('campaigns');
                            }

                            /* Team checks */
                            if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.notifications')) {
                                Alerts::add_info(l('global.info_message.team_no_access'));
                                redirect('notification/' . $this->notification->notification_id . '/statistics');
                            }

                            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

                            if(!\Altum\Csrf::check()) {
                                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
                                redirect('notification/' . $this->notification->notification_id . '/statistics');
                            }

                            $datetime = \Altum\Date::get_start_end_dates_new($_POST['start_date'], $_POST['end_date']);

                            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                                /* Clear statistics data */
                                database()->query("DELETE FROM `track_notifications` WHERE `notification_id` = {$this->notification->notification_id} AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')");

                                /* Set a nice success message */
                                Alerts::add_success(l('global.success_message.update2'));

                                redirect('notification/' . $this->notification->notification_id . '/statistics');

                            }

                            redirect('notification/' . $this->notification->notification_id . '/statistics');

                            break;
                    }
                }

                $datetime = \Altum\Date::get_start_end_dates_new();

                /* Query for the statistics of the notification */
                $logs = [];
                $logs_chart = [];
                $logs_total = [
                    'impression'   => 0,
                    'hover'        => 0,
                    'click'        => 0,
                    'ctr'          => 0,
                ];

                /* Logs for the charts */
                $logs_result = database()->query("
                    SELECT
                         `type`,
                         COUNT(`id`) AS `total`,
                         DATE_FORMAT(`datetime`, '{$datetime['query_date_format']}') AS `formatted_date`
                    FROM
                         `track_notifications`
                    WHERE
                        `notification_id` = {$this->notification->notification_id}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY
                        `formatted_date`,
                        `type`
                    ORDER BY
                        `formatted_date`
                ");

                /* Generate the raw chart data and save logs for later usage */
                while($row = $logs_result->fetch_object()) {
                    $logs[] = $row;

                    $row->formatted_date = $datetime['process']($row->formatted_date);

                    /* Handle if the date key is not already set */
                    if(!array_key_exists($row->formatted_date, $logs_chart)) {
                        $logs_chart[$row->formatted_date] = [
                            'impression'        => 0,
                            'hover'             => 0,
                            'click'             => 0,
                            'form_submission'   => 0,
                            'feedback_emoji_angry'    => 0,
                            'feedback_emoji_sad'      => 0,
                            'feedback_emoji_neutral'  => 0,
                            'feedback_emoji_happy'    => 0,
                            'feedback_emoji_excited'  => 0,
                            'feedback_score_1'  => 0,
                            'feedback_score_2'  => 0,
                            'feedback_score_3'  => 0,
                            'feedback_score_4'  => 0,
                            'feedback_score_5'  => 0,
                        ];
                    }

                    $logs_chart[$row->formatted_date][$row->type] = $row->total;

                    /* Count totals */
                    if(in_array($row->type, ['impression', 'hover', 'click'])) {
                        $logs_total[$row->type] += $row->total;
                    }
                }

                /* Calculate CTR */
                $logs_total['ctr'] = $logs_total['impression'] && $logs_total['click'] ? ($logs_total['click'] / $logs_total['impression']) * 100 : 0;

                $logs_chart = get_chart_data($logs_chart);

                /* Get most accessed urls and their type of notification */
                $top_pages_result = database()->query("
                    SELECT 
                        DISTINCT `url`, 
                        `type`, 
                        COUNT(`id`) AS `pageviews`
                    FROM 
                        `track_notifications`
                    WHERE
                        `notification_id` = {$this->notification->notification_id}
                        AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                    GROUP BY 
                        `url`, 
                         `type` 
                    ORDER BY 
                        `pageviews` DESC 
                    LIMIT 25
                ");

                /* Prepare the method View */
                $data = [
                    'notification' => $this->notification,
                    'method'=> $method,
                    'logs' => $logs,
                    'logs_chart' => $logs_chart,
                    'logs_total' => $logs_total,
                    'top_pages_result' => $top_pages_result,
                    'datetime' => $datetime,
                ];

                $view = new \Altum\View('notification/' . $method . '.method', (array) $this);

                $this->add_view_content('method', $view->run($data));

                break;


            case 'data':

                $datetime = \Altum\Date::get_start_end_dates_new();

                /* Prepare the paginator */
                $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `track_conversions` WHERE `notification_id` = {$this->notification->notification_id} AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')")->fetch_object()->total ?? 0;
                $paginator = (new \Altum\Paginator($total_rows, 50, $_GET['page'] ?? 1, url('notification/' . $this->notification->notification_id . '/data?start_date=' . $datetime['start_date'] . '&end_date=' .$datetime['end_date'] . '&page=%d')));

                /* Get the data from the database */
                $conversions = [];

                $conversions_result = database()->query("SELECT `id`, `notification_id`, `type`, `data`, `location`, `url`, `datetime` FROM `track_conversions` WHERE `notification_id` = {$this->notification->notification_id} AND (`datetime` BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}') ORDER BY `id` DESC {$paginator->get_sql_limit()}");

                while($row = $conversions_result->fetch_object()) $conversions[] = $row;

                /* Prepare the pagination view */
                $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

                /* Handle JSON Export request */
                if(isset($_GET['json'])) {
                    header('Content-disposition: attachment; filename=data.json');
                    header('Content-type: application/json');

                    /* Prepare the json */
                    $conversions_json = [];

                    foreach($conversions as $row) {
                        $row->data = json_decode($row->data);
                        $row->location = json_decode($row->location);

                        $conversions_json[] = $row;
                    }

                    echo json_encode($conversions_json);

                    die();
                }

                /* Custom Data Import Modal */
                $modal = $this->notification->type == 'REVIEWS' ? 'data.create_review_data_modal.method' : 'data.create_data_modal.method';
                $data = ['notification' => $this->notification];
                $view = new \Altum\View('notification/data/' . $modal, (array) $this);
                \Altum\Event::add_content($view->run($data), 'modals');

                /* Prepare the method View */
                $data = [
                    'notification'      => $this->notification,
                    'method'            => $method,
                    'conversions'       => $conversions,
                    'pagination'        => $pagination,
                    'datetime'          => $datetime
                ];

                $view = new \Altum\View('notification/' . $method . '.method', (array) $this);

                $this->add_view_content('method', $view->run($data));

                break;
        }

        /* Prepare the View */
        $data = [
            'notification' => $this->notification,
            'campaign' => $this->campaign,
            'method' => $method
        ];

        $view = new \Altum\View('notification/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

        /* Set a custom title */
        Title::set(sprintf(l('notification.title'), $this->notification->name));

    }

    private function process_settings_post() {

        /* Handle the update of the notification */
        if(!empty($_POST)) {
            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Team checks */
            if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.notifications')) {
                Alerts::add_info(l('global.info_message.team_no_access'));
                redirect('notification/' . $this->notification_id);
            }

            $_POST['name'] = input_clean($_POST['name']);
            $_POST['trigger_all_pages'] = (int) isset($_POST['trigger_all_pages']);
            $_POST['display_trigger'] = in_array($_POST['display_trigger'], [
                'delay',
                'exit_intent',
                'scroll',
                'click',
                'hover',
            ]) ? $_POST['display_trigger'] : 'delay';
            $_POST['display_trigger_value'] = in_array($_POST['display_trigger'], ['delay', 'exit_intent', 'scroll']) ? (int) $_POST['display_trigger_value'] : input_clean($_POST['display_trigger_value']);
            $_POST['display_frequency'] = in_array($_POST['display_frequency'], [
                'all_time',
                'once_per_session',
                'once_per_browser',
            ]) ? $_POST['display_frequency'] : 'all_time';
            $_POST['display_mobile'] = (int) isset($_POST['display_mobile']);
            $_POST['display_desktop'] = (int) isset($_POST['display_desktop']);

            $_POST['direction'] = in_array($_POST['direction'], ['rtl', 'ltr']) ? $_POST['direction'] : 'ltr';

            $_POST['display_duration'] = (int) $_POST['display_duration'];
            $_POST['display_position'] = in_array($_POST['display_position'], [
                'top_left',
                'top_center',
                'top_right',
                'middle_left',
                'middle_center',
                'middle_right',
                'bottom_left',
                'bottom_center',
                'bottom_right',
                'top',
                'bottom',
                'top_floating',
                'bottom_floating'
            ]) ? $_POST['display_position'] : 'bottom_left';
            $_POST['display_close_button'] = (int) isset($_POST['display_close_button']);
            $_POST['display_branding'] = (int) isset($_POST['display_branding']);

            $_POST['shadow'] = (int) isset($_POST['shadow']);
            $_POST['border_width'] = (int) ($_POST['border_width'] >= 0 && $_POST['border_width'] <= 5 ? $_POST['border_width'] : 0);
            $_POST['on_animation'] = in_array($_POST['on_animation'], [
                'fadeIn',
                'slideInUp',
                'slideInDown',
                'zoomIn',
                'bounceIn',
            ]) ? $_POST['on_animation'] : 'fadeIn';
            $_POST['off_animation'] = in_array($_POST['off_animation'], [
                'fadeOut',
                'slideOutUp',
                'slideOutDown',
                'zoomOut',
                'bounceOut',
            ]) ? $_POST['off_animation'] : 'fadeOut';
            $_POST['animation'] = in_array($_POST['animation'], [
                '',
                'heartbeat',
                'bounce',
                'flash',
                'pulse',
            ]) ? $_POST['animation'] : '';
            $_POST['animation_interval'] = (int) $_POST['animation_interval'];
            $_POST['font'] = in_array($_POST['font'], [
                'inherit',
                'Arial',
                'Verdana',
                'Helvetica',
                'Tahoma',
                'Trebuchet MS',
                'Times New Roman',
                'Georgia',
                'Garamond',
                'Courier New',
                'Monaco',
                'Comic Sans MS',
                'Courier',
                'Lucida Console',
                'Impact'
            ]) ? $_POST['font'] : 'inherit';

            switch($this->notification->type) {

                case 'INFORMATIONAL':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    break;

                case 'COUPON':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['button_url'] = get_url($_POST['button_url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['footer_text'] = mb_substr(query_clean($_POST['footer_text']), 0, 256);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    break;

                case 'LIVE_COUNTER':

                    /* Clean some posted variables */
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['last_activity'] = (int) $_POST['last_activity'];
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['display_minimum_activity'] = (int) $_POST['display_minimum_activity'];
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    break;

                case 'EMAIL_COLLECTOR' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['name_placeholder'] = query_clean($_POST['name_placeholder']);
                    $_POST['email_placeholder'] = query_clean($_POST['email_placeholder']);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['show_agreement'] = (int) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = mb_substr(query_clean($_POST['agreement_text']), 0, 256);
                    $_POST['agreement_url'] = get_url($_POST['agreement_url']);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    $_POST['data_send_is_enabled'] = (int) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = mb_substr(query_clean($_POST['data_send_webhook']), 0, 2048);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? mb_substr(query_clean($_POST['data_send_email']), 0, 320) : '';

                    break;

                case 'CONVERSIONS':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['conversions_count'] = (int) $_POST['conversions_count'] < 1 ? 1 : (int) $_POST['conversions_count'];
                    $_POST['in_between_delay'] = (int) $_POST['in_between_delay'] < 1 ? 0 : (int) $_POST['in_between_delay'];
                    $_POST['order'] = in_array($_POST['order'], ['descending', 'random']) ? $_POST['order'] : 'descending';
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_trigger_auto'] = (int) isset($_POST['data_trigger_auto']);

                    break;

                case 'CONVERSIONS_COUNTER':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['last_activity'] = (int) $_POST['last_activity'];
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['display_minimum_activity'] = (int) $_POST['display_minimum_activity'];
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_trigger_auto'] = (int) isset($_POST['data_trigger_auto']);

                    break;

                case 'VIDEO':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['video'] = query_clean($_POST['video']);
                    $_POST['button_url'] = get_url($_POST['button_url']);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);

                    $_POST['video'] = preg_replace("/\s*[a-zA-Z\/\/:\.]*(youtube|youtube-nocookie).com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i","https://www.youtube.com/embed/$2", $_POST['video']);

                    /* Make sure the video link has the proper format */
                    if(!preg_match('/^(https|http):\/\/(?:www\.)?(youtube|youtube-nocookie).com\/embed\/[a-zA-Z0-9\-_]+/', $_POST['video'])) {
                        $_POST['video'] = '';
                    }

                    break;

                case 'SOCIAL_SHARE':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['share_url'] = get_url($_POST['share_url']);
                    $_POST['share_facebook'] = (int) isset($_POST['share_facebook']);
                    $_POST['share_twitter'] = (int) isset($_POST['share_twitter']);
                    $_POST['share_linkedin'] = (int) isset($_POST['share_linkedin']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'REVIEWS':

                    /* Clean some posted variables */
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['reviews_count'] = (int) $_POST['reviews_count'] < 1 ? 1 : (int) $_POST['reviews_count'];
                    $_POST['in_between_delay'] = (int) $_POST['in_between_delay'] < 1 ? 0 : (int) $_POST['in_between_delay'];
                    $_POST['order'] = in_array($_POST['order'], ['descending', 'random']) ? $_POST['order'] : 'descending';
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'EMOJI_FEEDBACK':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['show_angry'] = (int) isset($_POST['show_angry']);
                    $_POST['show_sad'] = (int) isset($_POST['show_sad']);
                    $_POST['show_neutral'] = (int) isset($_POST['show_neutral']);
                    $_POST['show_happy'] = (int) isset($_POST['show_happy']);
                    $_POST['show_excited'] = (int) isset($_POST['show_excited']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'COOKIE_NOTIFICATION':

                    /* Clean some posted variables */
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['url_text'] = mb_substr(query_clean($_POST['url_text']), 0, 256);
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'SCORE_FEEDBACK':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'REQUEST_COLLECTOR' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['content_title'] = mb_substr(query_clean($_POST['content_title']), 0, 256);
                    $_POST['content_description'] = mb_substr(query_clean($_POST['content_description']), 0, 512);
                    $_POST['input_placeholder'] = mb_substr(query_clean($_POST['input_placeholder']), 0, 128);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['show_agreement'] = (int) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = mb_substr(query_clean($_POST['agreement_text']), 0, 256);
                    $_POST['agreement_url'] = get_url($_POST['agreement_url']);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_send_is_enabled'] = (int) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = mb_substr(query_clean($_POST['data_send_webhook']), 0, 2048);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? mb_substr(query_clean($_POST['data_send_email']), 0, 320) : '';

                    break;

                case 'COUNTDOWN_COLLECTOR' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['content_title'] = mb_substr(query_clean($_POST['content_title']), 0, 256);
                    $_POST['input_placeholder'] = mb_substr(query_clean($_POST['input_placeholder']), 0, 128);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['end_date'] = (new \DateTime($_POST['end_date'], new \DateTimeZone($this->user->timezone)))->setTimezone(new \DateTimeZone(\Altum\Date::$default_timezone))->format('Y-m-d H:i:s');
                    $_POST['show_agreement'] = (int) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = mb_substr(query_clean($_POST['agreement_text']), 0, 256);
                    $_POST['agreement_url'] = get_url($_POST['agreement_url']);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['data_send_is_enabled'] = (int) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = mb_substr(query_clean($_POST['data_send_webhook']), 0, 2048);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? mb_substr(query_clean($_POST['data_send_email']), 0, 320) : '';

                    break;

                case 'INFORMATIONAL_BAR':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = 'straight';

                    break;

                case 'IMAGE':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['button_url'] = get_url($_POST['button_url']);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'COLLECTOR_BAR' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['input_placeholder'] = mb_substr(query_clean($_POST['input_placeholder']), 0, 128);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['show_agreement'] = (int) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = mb_substr(query_clean($_POST['agreement_text']), 0, 256);
                    $_POST['agreement_url'] = get_url($_POST['agreement_url']);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['border_radius'] = 'straight';

                    $_POST['data_send_is_enabled'] = (int) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = mb_substr(query_clean($_POST['data_send_webhook']), 0, 2048);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? mb_substr(query_clean($_POST['data_send_email']), 0, 320) : '';

                    break;

                case 'COUPON_BAR':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['coupon_code'] = query_clean($_POST['coupon_code']);
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = 'straight';

                    break;

                case 'BUTTON_BAR':

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['url'] = get_url($_POST['url']);
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);
                    $_POST['border_radius'] = 'straight';

                    break;

                case 'COLLECTOR_MODAL' :
                case 'COLLECTOR_TWO_MODAL' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['input_placeholder'] = mb_substr(query_clean($_POST['input_placeholder']), 0, 128);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['show_agreement'] = (int) isset($_POST['show_agreement']);
                    $_POST['agreement_text'] = mb_substr(query_clean($_POST['agreement_text']), 0, 256);
                    $_POST['agreement_url'] = get_url($_POST['agreement_url']);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    $_POST['data_send_is_enabled'] = (int) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = mb_substr(query_clean($_POST['data_send_webhook']), 0, 2048);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? mb_substr(query_clean($_POST['data_send_email']), 0, 320) : '';

                    break;

                case 'BUTTON_MODAL' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['image'] = mb_substr(query_clean($_POST['image']), 0, 2048);
                    $_POST['image_alt'] = mb_substr(query_clean($_POST['image_alt']), 0, 100);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['button_url'] = get_url($_POST['button_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    $_POST['url_new_tab'] = (int) isset($_POST['url_new_tab']);

                    break;

                case 'TEXT_FEEDBACK' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['description'] = mb_substr(query_clean($_POST['description']), 0, 512);
                    $_POST['input_placeholder'] = mb_substr(query_clean($_POST['input_placeholder']), 0, 128);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);
                    $_POST['thank_you_url'] = get_url($_POST['thank_you_url']);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    $_POST['data_send_is_enabled'] = (int) isset($_POST['data_send_is_enabled']);
                    $_POST['data_send_webhook'] = mb_substr(query_clean($_POST['data_send_webhook']), 0, 2048);
                    $_POST['data_send_email'] = filter_var($_POST['data_send_email'], FILTER_VALIDATE_EMAIL) ? mb_substr(query_clean($_POST['data_send_email']), 0, 320) : '';

                    break;

                case 'ENGAGEMENT_LINKS' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);

                    $_POST['categories'] = $_POST['categories'] ? array_map(function($category) {
                        $category['title'] = mb_substr(query_clean($category['title']), 0, 256);
                        $category['description'] = mb_substr(query_clean($category['description']), 0, 512);

                        $category['links'] = array_map(function($category_link) {
                            $category_link['title'] = mb_substr(query_clean($category_link['title']), 0, 256);
                            $category_link['description'] = mb_substr(query_clean($category_link['description']), 0, 512);
                            $category_link['image'] = mb_substr(query_clean($category_link['image']), 0, 2048);
                            $category_link['url'] = get_url($category_link['url']);

                            return $category_link;
                        }, $category['links']);

                        return $category;
                    }, $_POST['categories']) : null;
                    $_POST['categories'] = array_values($_POST['categories'] ?? []);

                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'WHATSAPP_CHAT' :

                    /* Clean some posted variables */
                    $_POST['title'] = mb_substr(query_clean($_POST['title']), 0, 256);
                    $_POST['agent_image'] = mb_substr(query_clean($_POST['agent_image']), 0, 2048);
                    $_POST['agent_image_alt'] = mb_substr(query_clean($_POST['agent_image_alt']), 0, 100);
                    $_POST['agent_name'] = mb_substr(query_clean($_POST['agent_name']), 0, 64);
                    $_POST['agent_description'] = mb_substr(query_clean($_POST['agent_description']), 0, 512);
                    $_POST['agent_message'] = mb_substr(query_clean($_POST['agent_message']), 0, 1024);
                    $_POST['agent_phone_number'] = (int) mb_substr(query_clean($_POST['agent_phone_number']), 0, 32);
                    $_POST['button_text'] = mb_substr(query_clean($_POST['button_text']), 0, 128);

                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

                case 'CUSTOM_HTML':

                    /* Clean some posted variables */
                    $_POST['html'] = mb_substr($_POST['html'], 0, 8192);
                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                        'round',
                    ]) ? $_POST['border_radius'] : 'rounded';
                    break;

                case 'CONTACT_US':

                    /* Clean some posted variables */
                    $_POST['title'] = query_clean($_POST['title'], 256);
                    $_POST['description'] = query_clean($_POST['description'], 512);
                    $_POST['contact_email'] = query_clean($_POST['contact_email'], 320);
                    $_POST['contact_phone_number'] = query_clean($_POST['contact_phone_number'], 64);
                    $_POST['contact_whatsapp'] = query_clean($_POST['contact_whatsapp'], 64);
                    $_POST['contact_telegram'] = query_clean($_POST['contact_telegram'], 32);
                    $_POST['contact_facebook_messenger'] = query_clean($_POST['contact_facebook_messenger'], 64);

                    $_POST['border_radius'] = in_array($_POST['border_radius'], [
                        'straight',
                        'rounded',
                    ]) ? $_POST['border_radius'] : 'rounded';

                    break;

            }

            /* Go over all the possible color inputs and make sure they comply */
            foreach($_POST as $key => $value) {
                if(string_ends_with('_color', $key) && !preg_match('/#([a-f0-9]{3}){1,2}\b/i', $value)) {

                    /* Replace it with a plain black color */
                    $_POST[$key] = '#000';

                }
            }

            /* Get the proper color for the pattern */
            $background_pattern_color_hex = new \OzdemirBurak\Iris\Color\Hex($_POST['background_color']);
            $background_pattern_color = $background_pattern_color_hex->isDark() ? $background_pattern_color_hex->brighten(40) : $background_pattern_color_hex->darken(40);

            /* Process the background pattern if needed */
            $background_patterns = get_notifications_background_patterns($background_pattern_color);
            $_POST['background_pattern'] = array_key_exists($_POST['background_pattern'], $background_patterns) ? $_POST['background_pattern'] : false;
            $_POST['background_pattern_svg'] = $background_patterns[$_POST['background_pattern']] ?? false;

            /* Go over the triggers and clean them */
            foreach($_POST['trigger_type'] as $key => $value) {
                $_POST['trigger_type'][$key] = in_array($value, ['exact', 'not_exact', 'contains', 'not_contains', 'starts_with', 'not_starts_with', 'ends_with', 'not_ends_with', 'page_contains']) ? query_clean($value) : 'exact';
            }

            foreach($_POST['trigger_value'] as $key => $value) {
                $_POST['trigger_value'][$key] = query_clean($value);
            }

            /* Generate the trigger rules var */
            $triggers = [];

            foreach($_POST['trigger_type'] as $key => $value) {
                $triggers[] = [
                    'type' => $value,
                    'value' => $_POST['trigger_value'][$key]
                ];
            }

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Default notification settings */
                $new_notification_settings = [
                    'trigger_all_pages' => $_POST['trigger_all_pages'],
                    'triggers' => $triggers,
                    'display_trigger' => $_POST['display_trigger'],
                    'display_trigger_value' => $_POST['display_trigger_value'],
                    'display_frequency' => $_POST['display_frequency'],
                    'display_mobile' => $_POST['display_mobile'],
                    'display_desktop' => $_POST['display_desktop'],

                    'close_button_color' => $_POST['close_button_color'],
                    'shadow' => $_POST['shadow'],
                    'border_radius' => $_POST['border_radius'],
                    'border_width' => $_POST['border_width'],
                    'border_color' => $_POST['border_color'],
                    'on_animation' => $_POST['on_animation'],
                    'off_animation' => $_POST['off_animation'],
                    'animation' => $_POST['animation'],
                    'animation_interval' => $_POST['animation_interval'],
                    'font' => $_POST['font'],
                    'background_pattern' => $_POST['background_pattern'],
                    'background_pattern_svg' => $_POST['background_pattern_svg'],

                    'direction' => $_POST['direction'],
                    'display_duration' => $_POST['display_duration'],
                    'display_position' => $_POST['display_position'],
                    'display_close_button' => $_POST['display_close_button'],
                    'display_branding' => $_POST['display_branding']
                ];


                /* Prepare the settings json based on the notification type */
                switch($this->notification->type) {

                    case 'INFORMATIONAL' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'COUPON' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'coupon_code' => $_POST['coupon_code'],
                                'button_url' => $_POST['button_url'],
                                'url_new_tab' => $_POST['url_new_tab'],
                                'button_text' => $_POST['button_text'],
                                'footer_text' => $_POST['footer_text'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'LIVE_COUNTER' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'description' => $_POST['description'],
                                'last_activity' => $_POST['last_activity'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'display_minimum_activity' => $_POST['display_minimum_activity'],

                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'number_background_color' => $_POST['number_background_color'],
                                'number_color' => $_POST['number_color'],
                                'pulse_background_color' => $_POST['pulse_background_color'],
                            ]
                        );

                        break;

                    case 'EMAIL_COLLECTOR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'name_placeholder' => $_POST['name_placeholder'],
                                'email_placeholder' => $_POST['email_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'CONVERSIONS' :

                        /* Go over the data triggers auto and clean them */
                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $_POST['data_trigger_auto_type'][$key] = in_array($value, ['exact', 'contains', 'starts_with', 'ends_with']) ? query_clean($value) : 'exact';
                        }

                        foreach($_POST['data_trigger_auto_value'] as $key => $value) {
                            $_POST['data_trigger_auto_value'][$key] = query_clean($value);
                        }

                        /* Generate the trigger rules var */
                        $data_triggers_auto = [];

                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $data_triggers_auto[] = [
                                'type' => $value,
                                'value' => $_POST['data_trigger_auto_value'][$key]
                            ];
                        }

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],
                                'conversions_count' => $_POST['conversions_count'],
                                'in_between_delay' => $_POST['in_between_delay'],
                                'order' => $_POST['order'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'date_color' => $_POST['date_color'],
                                'background_color' => $_POST['background_color'],

                                'data_trigger_auto' => $_POST['data_trigger_auto'],
                                'data_triggers_auto' => $data_triggers_auto
                            ]);

                        break;

                    case 'CONVERSIONS_COUNTER' :

                        /* Go over the data triggers auto and clean them */
                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $_POST['data_trigger_auto_type'][$key] = in_array($value, ['exact', 'contains', 'starts_with', 'ends_with']) ? query_clean($value) : 'exact';
                        }

                        foreach($_POST['data_trigger_auto_value'] as $key => $value) {
                            $_POST['data_trigger_auto_value'][$key] = query_clean($value);
                        }

                        /* Generate the trigger rules var */
                        $data_triggers_auto = [];

                        foreach($_POST['data_trigger_auto_type'] as $key => $value) {
                            $data_triggers_auto[] = [
                                'type' => $value,
                                'value' => $_POST['data_trigger_auto_value'][$key]
                            ];
                        }

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'last_activity' => $_POST['last_activity'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'display_minimum_activity' => $_POST['display_minimum_activity'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'number_background_color' => $_POST['number_background_color'],
                                'number_color' => $_POST['number_color'],

                                'data_trigger_auto' => $_POST['data_trigger_auto'],
                                'data_triggers_auto' => $data_triggers_auto
                            ]
                        );

                        break;

                    case 'VIDEO' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'video' => $_POST['video'],
                                'button_url' => $_POST['button_url'],
                                'button_text' => $_POST['button_text'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'SOCIAL_SHARE' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'share_url' => $_POST['share_url'],
                                'share_facebook' => $_POST['share_facebook'],
                                'share_twitter' => $_POST['share_twitter'],
                                'share_linkedin' => $_POST['share_linkedin'],
                                'share_reddit' => $_POST['share_reddit'],
                                'share_pinterest' => $_POST['share_pinterest'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'REVIEWS' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],
                                'reviews_count' => $_POST['reviews_count'],
                                'in_between_delay' => $_POST['in_between_delay'],
                                'order' => $_POST['order'],

                                /* Keep the following keys to default */
                                'title' => l('notification.reviews.title_default'),
                                'description' => l('notification.reviews.description_default'),
                                'image' => l('notification.reviews.image_default'),
                                'stars' => 5,

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'EMOJI_FEEDBACK' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'show_angry' => $_POST['show_angry'],
                                'show_sad' => $_POST['show_sad'],
                                'show_neutral' => $_POST['show_neutral'],
                                'show_happy' => $_POST['show_happy'],
                                'show_excited' => $_POST['show_excited'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'COOKIE_NOTIFICATION' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'url_text' => $_POST['url_text'],
                                'url' => $_POST['url'],
                                'button_text' => $_POST['button_text'],

                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'SCORE_FEEDBACK' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'REQUEST_COLLECTOR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'content_title' => $_POST['content_title'],
                                'content_description' => $_POST['content_description'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'content_title_color' => $_POST['content_title_color'],
                                'content_description_color' => $_POST['content_description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'COUNTDOWN_COLLECTOR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'content_title' => $_POST['content_title'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'end_date' => $_POST['end_date'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'content_title_color' => $_POST['content_title_color'],
                                'time_color' => $_POST['time_color'],
                                'time_background_color' => $_POST['time_background_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'CUSTOM_HTML' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'html' => $_POST['html'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'INFORMATIONAL_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'IMAGE' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'button_url' => $_POST['button_url'],
                                'button_text' => $_POST['button_text'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'COLLECTOR_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'COUPON_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'coupon_code' => $_POST['coupon_code'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'coupon_code_color' => $_POST['coupon_code_color'],
                                'coupon_code_background_color' => $_POST['coupon_code_background_color'],
                                'coupon_code_border_color' => $_POST['coupon_code_border_color'],

                            ]
                        );

                        break;

                    case 'BUTTON_BAR' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'button_text' => $_POST['button_text'],
                                'url' => $_POST['url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                                'button_color' => $_POST['button_color'],
                                'button_background_color' => $_POST['button_background_color'],

                            ]
                        );

                        break;

                    case 'COLLECTOR_MODAL' :
                    case 'COLLECTOR_TWO_MODAL' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'show_agreement' => $_POST['show_agreement'],
                                'agreement_text' => $_POST['agreement_text'],
                                'agreement_url' => $_POST['agreement_url'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'BUTTON_MODAL' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'image' => $_POST['image'],
                                'image_alt' => $_POST['image_alt'],
                                'button_text' => $_POST['button_text'],
                                'button_url' => $_POST['button_url'],
                                'url_new_tab' => $_POST['url_new_tab'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],
                            ]
                        );

                        break;

                    case 'TEXT_FEEDBACK' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'input_placeholder' => $_POST['input_placeholder'],
                                'button_text' => $_POST['button_text'],
                                'thank_you_url' => $_POST['thank_you_url'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                                'button_background_color' => $_POST['button_background_color'],
                                'button_color' => $_POST['button_color'],

                                'data_send_is_enabled' => $_POST['data_send_is_enabled'],
                                'data_send_webhook' => $_POST['data_send_webhook'],
                                'data_send_email' => $_POST['data_send_email'],
                            ]
                        );

                        break;

                    case 'ENGAGEMENT_LINKS' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'categories' => $_POST['categories'],

                                'title_color' => $_POST['title_color'],
                                'categories_title_color' => $_POST['categories_title_color'],
                                'categories_description_color' => $_POST['categories_description_color'],
                                'categories_links_title_color' => $_POST['categories_links_title_color'],
                                'categories_links_description_color' => $_POST['categories_links_description_color'],
                                'categories_links_background_color' => $_POST['categories_links_background_color'],
                                'categories_links_border_color' => $_POST['categories_links_border_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'WHATSAPP_CHAT' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'agent_image' => $_POST['agent_image'],
                                'agent_image_alt' => $_POST['agent_image_alt'],
                                'agent_name' => $_POST['agent_name'],
                                'agent_description' => $_POST['agent_description'],
                                'agent_message' => $_POST['agent_message'],
                                'agent_phone_number' => $_POST['agent_phone_number'],
                                'button_text' => $_POST['button_text'],
                                'header_agent_name_color' => $_POST['header_agent_name_color'],
                                'header_agent_description_color' => $_POST['header_agent_description_color'],
                                'header_background_color' => $_POST['header_background_color'],
                                'content_background_color' => $_POST['content_background_color'],
                                'content_agent_name_color' => $_POST['content_agent_name_color'],
                                'content_agent_message_color' => $_POST['content_agent_message_color'],
                                'content_agent_message_background_color' => $_POST['content_agent_message_background_color'],
                                'footer_background_color' => $_POST['footer_background_color'],
                                'footer_button_background_color' => $_POST['footer_button_background_color'],
                                'footer_button_color' => $_POST['footer_button_color'],
                                'title_color' => $_POST['title_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;

                    case 'CONTACT_US' :

                        $new_notification_settings = array_merge(
                            $new_notification_settings,
                            [
                                'title' => $_POST['title'],
                                'description' => $_POST['description'],
                                'contact_email' => $_POST['contact_email'],
                                'contact_phone_number' => $_POST['contact_phone_number'],
                                'contact_whatsapp' => $_POST['contact_whatsapp'],
                                'contact_telegram' => $_POST['contact_telegram'],
                                'contact_facebook_messenger' => $_POST['contact_facebook_messenger'],

                                'title_color' => $_POST['title_color'],
                                'description_color' => $_POST['description_color'],
                                'background_color' => $_POST['background_color'],
                            ]
                        );

                        break;
                }

                /* Prepare as json for the database update */
                $new_notification_settings = json_encode($new_notification_settings);

                /* Prepare the statement and execute query */
                db()->where('notification_id', $this->notification_id)->where('user_id', $this->user->user_id)->update('notifications', [
                    'name' => $_POST['name'],
                    'settings' => $new_notification_settings,
                    'last_datetime' => Date::$date,
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('notification/' . $this->notification_id);
            }
        }

    }

    public function duplicate() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.notifications')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        if(empty($_POST)) {
            redirect('dashboard');
        }

        /* Make sure that the user didn't exceed the limit */
        $user_notifications_total = database()->query("SELECT COUNT(*) AS `total` FROM `notifications` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;
        if($this->user->plan_settings->notifications_limit != -1 && $user_notifications_total >= $this->user->plan_settings->notifications_limit) {
            Alerts::add_error(l('global.info_message.plan_feature_limit'));
            redirect('dashboard');
        }

        $notification_id = (int) query_clean($_POST['notification_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('dashboard');
        }

        /* Make sure the notification is created by the logged in user */
        if(!$notification = db()->where('notification_id', $notification_id)->where('user_id', $this->user->user_id)->getOne('notifications')) {
            redirect('dashboard');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Determine the default settings */
            $notification_key = md5($this->user->user_id . $notification->notification_id . $notification->campaign_id . time());
            $name = $notification->name . ' - ' . l('global.duplicated');

            /* Insert to database */
            $notification_id = db()->insert('notifications', [
                'user_id' => $this->user->user_id,
                'campaign_id' => $notification->campaign_id,
                'name' => $name,
                'type' => $notification->type,
                'settings' => $notification->settings,
                'notification_key' => $notification_key,
                'is_enabled' => 0,
                'datetime' => \Altum\Date::$date,
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($name) . '</strong>'));

            /* Redirect */
            redirect('notification/' . $notification_id);

        }

        die();
    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.notifications')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        if(empty($_POST)) {
            redirect('dashboard');
        }

        $notification_id = (int) query_clean($_POST['notification_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('dashboard');
        }

        /* Make sure the notification is created by the logged in user */
        if(!$notification = db()->where('notification_id', $notification_id)->where('user_id', $this->user->user_id)->getOne('notifications', ['notification_id', 'campaign_id', 'name'])) {
            redirect('dashboard');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete from database */
            db()->where('notification_id', $notification->notification_id)->where('user_id', $this->user->user_id)->delete('notifications');

            /* Clear the cache */
            cache()->deleteItem('notifications_total?user_id=' . $this->user->user_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $notification->name . '</strong>'));

            redirect('campaign/' . $notification->campaign_id);

        }

        die();
    }
}
