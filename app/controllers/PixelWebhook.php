<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class PixelWebhook extends Controller {

    public function index() {

        $notification_key = isset($this->params[0]) ? query_clean($this->params[0]) : false;
        $date = \Altum\Date::$date;

        /* Make sure the api key exists */
        if(!$notification = db()->where('notification_key', $notification_key)->where('is_enabled', 1)->getOne('notifications')) {
            die();
        }

        /* Make sure the $notification_key belongs to an active user */
        if(!db()->where('user_id', $notification->user_id)->where('status', 1)->getOne('users')) {
            die();
        }

        /* Check for JSON submitted payload */
        $payload = @json_decode(@file_get_contents('php://input'), true);

        if($payload) {

            $_POST = (array) $payload;

        }

        /* Flatten everything recursively */
        $_POST = array_flatten($_POST);

        /* Location */
        $location = [];

        /* Clean all the received variables */
        foreach($_POST as $key => $value) {
            $_POST[$key] = query_clean($value);

            if($key == 'city') {
                $location['city'] = $_POST[$key];
                unset($_POST[$key]);
            }

            if($key == 'country') {
                $location['country'] = $_POST[$key];
                unset($_POST[$key]);
            }

            if($key == 'country_code') {
                $location['country_code'] = $_POST[$key];
                unset($_POST[$key]);
            }
        }

        /* Data for the conversion */
        $data = json_encode($_POST);
        $type = 'webhook';
        $url = '';
        $location = !empty($location) ? json_encode($location) : null;

        /* Make sure that the data is not already submitted and exists for this notification */
        $result = database()->query("SELECT `id` FROM `track_conversions` WHERE `notification_id` = {$notification->notification_id} AND `data` = '{$data}'");

        if($result->num_rows) {
            die();
        }

        /* Insert the conversion log */
        db()->insert('track_conversions', [
            'notification_id' => $notification->notification_id,
            'type' => $type,
            'data' => $data,
            'url' => $url,
            'location' => $location,
            'datetime' => $date,
        ]);

    }

}
