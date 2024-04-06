<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Date;
use Altum\Response;

class CampaignsAjax extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!empty($_POST) && (\Altum\Csrf::check('token') || \Altum\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Custom Branding Set */
                case 'custom_branding': $this->custom_branding(); break;

                /* Create */
                case 'create': $this->create(); break;

                /* Update */
                case 'update': $this->update(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.campaigns')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['campaign_id'] = (int) $_POST['campaign_id'];

        /* Get the current status */
        $is_enabled = db()->where('campaign_id', $_POST['campaign_id'])->getValue('campaigns', 'is_enabled');

        /* Update data in database */
        db()->where('campaign_id', $_POST['campaign_id'])->where('user_id', $this->user->user_id)->update('campaigns', [
            'is_enabled' => (int) !$is_enabled,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

        Response::json('', 'success');
    }

    private function custom_branding() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.campaigns')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['campaign_id'] = (int) $_POST['campaign_id'];
        $_POST['name'] = mb_substr(trim(query_clean($_POST['name'])), 0, 128);
        $_POST['url'] = get_url($_POST['url']);

        /* Make sure the user has access to the custom branding method */
        if(!$this->user->plan_settings->custom_branding) {
            die();
        }

        /* Check for possible errors */
        if(!isset($_POST['name'], $_POST['url'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        $campaign_branding = json_encode([
            'name' => $_POST['name'],
            'url'   => $_POST['url']
        ]);

        /* Update data in database */
        db()->where('campaign_id', $_POST['campaign_id'])->where('user_id', $this->user->user_id)->update('campaigns', [
            'branding' => $campaign_branding,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

        Response::json(l('global.success_message.update2'), 'success');
    }

    private function create() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.campaigns')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['name'] = trim(query_clean($_POST['name']));
        $is_enabled = 1;

        /* Domain checking */
        $_POST['domain'] = mb_strtolower(input_clean($_POST['domain']));

        if(string_starts_with('http://', $_POST['domain']) || string_starts_with('https://', $_POST['domain'])) {
            if(function_exists('idn_to_utf8')) {
                $_POST['domain'] = parse_url(idn_to_utf8($_POST['domain']), PHP_URL_HOST);
            }
        } else {
            if(function_exists('idn_to_utf8')) {
                $_POST['domain'] = parse_url(idn_to_utf8('https://' . $_POST['domain']), PHP_URL_HOST);
            }
        }

        if(function_exists('idn_to_ascii')) {
            $_POST['domain'] = idn_to_ascii($_POST['domain']);
        }

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['domain'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        /* Make sure that the user didn't exceed the limit */
        $account_total_campaigns = database()->query("SELECT COUNT(*) AS `total` FROM `campaigns` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total;
        if($this->user->plan_settings->campaigns_limit != -1 && $account_total_campaigns >= $this->user->plan_settings->campaigns_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Generate a unique pixel key for the website */
        $pixel_key = string_generate(32);
        while(db()->where('pixel_key', $pixel_key)->getValue('campaigns', 'pixel_key')) {
            $pixel_key = string_generate(32);
        }

        /* Insert to database */
        $campaign_id = db()->insert('campaigns', [
            'user_id' => $this->user->user_id,
            'pixel_key' => $pixel_key,
            'name' => $_POST['name'],
            'domain' => $_POST['domain'],
            'branding' => json_encode([
                'name' => '',
                'url' => '',
            ]),
            'is_enabled' => $is_enabled,
            'datetime' => Date::$date,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('campaign_id=' . $campaign_id);
        cache()->deleteItem('campaigns_total?user_id=' . $this->user->user_id);
        cache()->deleteItem('notifications_total?user_id=' . $this->user->user_id);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . filter_var($_POST['name']) . '</strong>'), 'success', ['campaign_id' => $campaign_id]);

    }

    private function update() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.campaigns')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['campaign_id'] = (int) $_POST['campaign_id'];
        $_POST['name'] = trim(query_clean($_POST['name']));

        /* Domain checking */
        $_POST['domain'] = mb_strtolower(input_clean($_POST['domain']));

        if(string_starts_with('http://', $_POST['domain']) || string_starts_with('https://', $_POST['domain'])) {
            if(function_exists('idn_to_utf8')) {
                $_POST['domain'] = parse_url(idn_to_utf8($_POST['domain']), PHP_URL_HOST);
            }
        } else {
            if(function_exists('idn_to_utf8')) {
                $_POST['domain'] = parse_url(idn_to_utf8('https://' . $_POST['domain']), PHP_URL_HOST);
            }
        }

        if(function_exists('idn_to_ascii')) {
            $_POST['domain'] = idn_to_ascii($_POST['domain']);
        }

        /* Check for possible errors */
        if(empty($_POST['name']) || empty($_POST['domain'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        /* Insert to database */
        db()->where('campaign_id', $_POST['campaign_id'])->where('user_id', $this->user->user_id)->update('campaigns', [
            'name' => $_POST['name'],
            'domain' => $_POST['domain'],
            'last_datetime' => Date::$date,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.update1'), '<strong>' . filter_var($_POST['name']) . '</strong>'), 'success', ['campaign_id' => $_POST['campaign_id']]);
    }

}
