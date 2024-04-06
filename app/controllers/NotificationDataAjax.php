<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Response;

class NotificationDataAjax extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!empty($_POST) && (\Altum\Csrf::check('token') || \Altum\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Create */
                case 'create': $this->create(); break;

                /* Delete */
                case 'delete': $this->delete(); break;

            }

        }

        die();
    }

    private function create() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.notifications')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['notification_id'] = (int) $_POST['notification_id'];
        $type = 'imported';

        /* Check for possible errors */
        if(!db()->where('notification_id', $_POST['notification_id'])->where('user_id', $this->user->user_id)->getValue('notifications', 'notification_id')) {
            die();
        }

        /* CSV or Data */
        $csv = !empty($_FILES['csv']['name']);

        if(!$csv && empty($_POST['key']) && empty($_POST['value'])) {
            die();
        }

        if($csv) {
            $file_name = $_FILES['csv']['name'];
            $file_extension = explode('.', $file_name);
            $file_extension = mb_strtolower(end($file_extension));
            $file_temp = $_FILES['csv']['tmp_name'];

            if($_FILES['csv']['error'] == UPLOAD_ERR_INI_SIZE) {
                Response::json(sprintf(l('global.error_message.file_size_limit'), get_max_upload()), 'error');
            }

            if($_FILES['csv']['error'] && $_FILES['csv']['error'] != UPLOAD_ERR_INI_SIZE) {
                Response::json(l('global.error_message.file_upload'), 'error');
            }

            if(!in_array($file_extension, ['csv'])) {
                Response::json(l('global.error_message.invalid_file_type'), 'error');
            }

            $csv_array = array_map('str_getcsv', file($file_temp));

            if(!$csv_array || !is_array($csv_array)) {
                Response::json(l('global.error_message.invalid_file_type'), 'error');
            }

            $headers_array = $csv_array[0];
            unset($csv_array[0]);
            reset($csv_array);

            /* Go over each row */
            foreach($csv_array as $key => $value) {
                if(count($headers_array) != count($value)) {
                    continue;
                }

                /* Date for insertion */
                $datetime = \Altum\Date::$date;

                $data = [];
                foreach($headers_array as $header_key => $header_value) {
                    $data[$header_value] = $value[$header_key];

                    /* Check for date type of column */
                    if(in_array($header_value, ['date', 'datetime'])) {
                        try {
                            $datetime = (new \DateTime($value[$header_key]))->format('Y-m-d H:i:s');
                        } catch (\Exception $exception) {
                            // :)
                        }
                    }
                }
                $data = json_encode($data);

                /* Insert in the database */
                db()->insert('track_conversions', [
                    'notification_id' => $_POST['notification_id'],
                    'type' => $type,
                    'data' => $data,
                    'datetime' => $datetime,
                ]);
            }
        }

        else {
            /* Parse the keys and values */
            $data = [];
            foreach ($_POST['key'] as $key => $value) {
                if(!empty($_POST['key'][$key]) && isset($_POST['value'][$key])) {
                    $cleaned_value = query_clean($value);

                    $data[$cleaned_value] = query_clean($_POST['value'][$key]);
                }
            }

            $data = json_encode($data);

            /* Insert in the database */
            db()->insert('track_conversions', [
                'notification_id' => $_POST['notification_id'],
                'type' => $type,
                'data' => $data,
                'datetime' => \Altum\Date::$date
            ]);
        }

        Response::json(l('global.success_message.create2'), 'success');
    }

    private function delete() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.notifications')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['id'] = (int) $_POST['id'];

        /* Check for possible errors */
        if(!$notification_id = db()->where('id', $_POST['id'])->getValue('track_conversions', 'notification_id')) {
            die();
        }

        if(!db()->where('notification_id', $notification_id)->where('user_id', $this->user->user_id)->getValue('notifications', 'notification_id')) {
            die();
        }

        /* Delete from database */
        db()->where('id', $_POST['id'])->delete('track_conversions');

        Response::json(l('global.success_message.delete2'), 'success');
    }

}
