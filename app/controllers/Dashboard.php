<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class Dashboard extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Get some stats */
        $total_campaigns = \Altum\Cache::cache_function_result('campaigns_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('campaigns', 'count(*)');
        });

        $total_notifications = \Altum\Cache::cache_function_result('notifications_total?user_id=' . $this->user->user_id, null, function() {
            return db()->where('user_id', $this->user->user_id)->getValue('notifications', 'count(*)');
        });

        /* Get the latest campaigns */
        $campaigns = [];
        $campaigns_result = database()->query("SELECT * FROM `campaigns` WHERE `user_id` = {$this->user->user_id} ORDER BY `campaign_id` DESC LIMIT 5");
        while($row = $campaigns_result->fetch_object()) $campaigns[] = $row;

        /* Get the latest notifications */
        $notifications = [];
        $notifications_result = database()->query("SELECT * FROM `notifications` WHERE `user_id` = {$this->user->user_id} ORDER BY `notification_id` DESC LIMIT 5");
        while($row = $notifications_result->fetch_object()) $notifications[] = $row;

        /* Prepare the View */
        $data = [
            'total_campaigns' => $total_campaigns,
            'total_notifications' => $total_notifications,
            'campaigns' => $campaigns,
            'notifications' => $notifications,
        ];

        $view = new \Altum\View('dashboard/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
