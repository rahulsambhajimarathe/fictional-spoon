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

class NotificationsAjax extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!empty($_POST) && (\Altum\Csrf::check('token') || \Altum\Csrf::check('global_token')) && isset($_POST['request_type'])) {

            switch($_POST['request_type']) {

                /* Status toggle */
                case 'is_enabled_toggle': $this->is_enabled_toggle(); break;

                /* Get conversion data */
                case 'read_data_conversion': $this->read_data_conversion(); break;

            }

        }

        if(!empty($_GET) && (\Altum\Csrf::check('token') || \Altum\Csrf::check('global_token')) && isset($_GET['request_type'])) {

            switch($_GET['request_type']) {

                /* Get conversion data */
                case 'read_data_conversion': $this->read_data_conversion(); break;

            }

        }

        die();
    }

    private function is_enabled_toggle() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.notifications')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['notification_id'] = (int) $_POST['notification_id'];

        /* Get the current status */
        $is_enabled = db()->where('notification_id', $_POST['notification_id'])->getValue('notifications', 'is_enabled');

        /* Update data in database */
        db()->where('notification_id', $_POST['notification_id'])->where('user_id', $this->user->user_id)->update('notifications', [
            'is_enabled' => (int) !$is_enabled,
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('campaign_id=' . $_POST['campaign_id']);

        Response::json('', 'success');
    }

    private function read_data_conversion() {
        $_GET['notification_id'] = (int)$_GET['notification_id'];
        $_GET['id'] = (int)$_GET['id'];

        /* Get the current status */
        $user_id = db()->where('notification_id', $_GET['notification_id'])->getValue('notifications', 'user_id');

        if($user_id && $user_id == $this->user->user_id) {

            /* Get the data from the conversions table */
            $conversion = db()->where('id', $_GET['id'])->where('notification_id', $_GET['notification_id'])->getOne('track_conversions', ['type', 'data', 'location', 'url']);

            if($conversion) {

                $conversion->data = json_decode($conversion->data);
                $conversion->location = !empty($conversion->location) ? json_decode($conversion->location) : null;

                /* Generate the view */
                $data = [
                    'conversion' => $conversion,
                ];
                $view = new \Altum\View('notification/data/data.read_conversion.method', (array) $this);

                Response::json('', 'success', ['html' => $view->run($data)]);
            }

        }
    }

}
