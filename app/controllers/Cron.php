<?php
/*
 * @copyright Copyright (c) 2023 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Logger;
use Altum\Models\User;

class Cron extends Controller {

    private function initiate() {
        /* Initiation */
        set_time_limit(0);

        /* Make sure the key is correct */
        if(!isset($_GET['key']) || (isset($_GET['key']) && $_GET['key'] != settings()->cron->key)) {
            die();
        }

        /* Send webhook notification if needed */
        if(settings()->webhooks->cron_start) {
            $backtrace = debug_backtrace();
            \Unirest\Request::post(settings()->webhooks->cron_start, [], [
                'type' => $backtrace[1]['function'] ?? null,
            ]);
        }
    }

    private function close() {
        /* Send webhook notification if needed */
        if(settings()->webhooks->cron_end) {
            $backtrace = debug_backtrace();
            \Unirest\Request::post(settings()->webhooks->cron_end, [], [
                'type' => $backtrace[1]['function'] ?? null,
            ]);
        }
    }

    private function update_cron_execution_datetimes($key) {
        $date = \Altum\Date::$date;

        /* Database query */
        database()->query("UPDATE `settings` SET `value` = JSON_SET(`value`, '$.{$key}', '{$date}') WHERE `key` = 'cron'");
    }

    public function index() {

        $this->initiate();

        $this->users_plan_expiry_checker();

        $this->users_deletion_reminder();

        $this->auto_delete_inactive_users();

        $this->auto_delete_unconfirmed_users();

        $this->users_plan_expiry_reminder();

        $this->update_cron_execution_datetimes('cron_datetime');

        /* Make sure the reset date month is different than the current one to avoid double resetting */
        $reset_date = settings()->cron->reset_date ? (new \DateTime(settings()->cron->reset_date))->format('m') : null;
        $current_date = (new \DateTime())->format('m');

        if($reset_date != $current_date) {
            $this->logs_cleanup();

            $this->users_logs_cleanup();

            $this->internal_notifications_cleanup();

            $this->users_impressions_reset();

            $this->notifications_logs_cleanup();

            $this->update_cron_execution_datetimes('reset_date');

            /* Clear the cache */
            cache()->deleteItem('settings');
        }

        $this->close();
    }

    private function users_plan_expiry_checker() {
        if(!settings()->payment->user_plan_expiry_checker_is_enabled) {
            return;
        }

        $date = \Altum\Date::$date;

        /* Get potential monitors from users that have almost all the conditions to get an email report right now */
        $result = database()->query("
            SELECT `user_id`
            FROM `users`
            WHERE 
                `plan_id` <> 'free'
				AND `plan_expiration_date` < '{$date}' 
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Switch the user to the default plan */
            db()->where('user_id', $user->user_id)->update('users', [
                'plan_id' => 'free',
                'plan_settings' => json_encode(settings()->plan_free->settings),
                'payment_subscription_id' => ''
            ]);

            /* Clear the cache */
            cache()->deleteItemsByTag('user_id=' .  \Altum\Authentication::$user_id);

            if(DEBUG) {
                echo sprintf('Plan expired for user_id %s', $user->user_id);
            }
        }

    }

    private function users_deletion_reminder() {
        if(!settings()->users->auto_delete_inactive_users) {
            return;
        }

        /* Determine when to send the email reminder */
        $days_until_deletion = settings()->users->user_deletion_reminder;
        $days = settings()->users->auto_delete_inactive_users - $days_until_deletion;
        $past_date = (new \DateTime())->modify('-' . $days . ' days')->format('Y-m-d H:i:s');

        /* Get the users that need to be reminded */
        $result = database()->query("
            SELECT `user_id`, `name`, `email`, `language`, `anti_phishing_code` FROM `users` WHERE `plan_id` = 'free' AND `last_activity` < '{$past_date}' AND `user_deletion_reminder` = 0 AND `type` = 0 LIMIT 25
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Prepare the email */
            $email_template = get_email_template(
                [
                    '{{DAYS_UNTIL_DELETION}}' => $days_until_deletion,
                ],
                l('global.emails.user_deletion_reminder.subject', $user->language),
                [
                    '{{DAYS_UNTIL_DELETION}}' => $days_until_deletion,
                    '{{LOGIN_LINK}}' => url('login'),
                    '{{NAME}}' => $user->name,
                ],
                l('global.emails.user_deletion_reminder.body', $user->language)
            );

            if(settings()->users->user_deletion_reminder) {
                send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);
            }

            /* Update user */
            db()->where('user_id', $user->user_id)->update('users', ['user_deletion_reminder' => 1]);

            if(DEBUG) {
                if(settings()->users->user_deletion_reminder) echo sprintf('User deletion reminder email sent for user_id %s', $user->user_id);
            }
        }

    }

    private function auto_delete_inactive_users() {
        if(!settings()->users->auto_delete_inactive_users) {
            return;
        }

        /* Determine what users to delete */
        $days = settings()->users->auto_delete_inactive_users;
        $past_date = (new \DateTime())->modify('-' . $days . ' days')->format('Y-m-d H:i:s');

        /* Get the users that need to be reminded */
        $result = database()->query("
            SELECT `user_id`, `name`, `email`, `language`, `anti_phishing_code` FROM `users` WHERE `plan_id` = 'free' AND `last_activity` < '{$past_date}' AND `user_deletion_reminder` = 1 AND `type` = 0 LIMIT 25
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Prepare the email */
            $email_template = get_email_template(
                [],
                l('global.emails.auto_delete_inactive_users.subject', $user->language),
                [
                    '{{INACTIVITY_DAYS}}' => settings()->users->auto_delete_inactive_users,
                    '{{REGISTER_LINK}}' => url('register'),
                    '{{NAME}}' => $user->name,
                ],
                l('global.emails.auto_delete_inactive_users.body', $user->language)
            );

            send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

            /* Delete user */
            (new User())->delete($user->user_id);

            if(DEBUG) {
                echo sprintf('User deletion for inactivity user_id %s', $user->user_id);
            }
        }

    }

    private function auto_delete_unconfirmed_users() {
        if(!settings()->users->auto_delete_unconfirmed_users) {
            return;
        }

        /* Determine what users to delete */
        $days = settings()->users->auto_delete_unconfirmed_users;
        $past_date = (new \DateTime())->modify('-' . $days . ' days')->format('Y-m-d H:i:s');

        /* Get the users that need to be reminded */
        $result = database()->query("SELECT `user_id` FROM `users` WHERE `status` = '0' AND `datetime` < '{$past_date}' LIMIT 100");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Delete user */
            (new User())->delete($user->user_id);

            if(DEBUG) {
                echo sprintf('User deleted for unconfirmed account user_id %s', $user->user_id);
            }
        }
    }

    private function logs_cleanup() {
        /* Clear files caches */
        clearstatcache();

        $current_month = (new \DateTime())->format('m');

        $deleted_count = 0;

        /* Get the data */
        foreach(glob(UPLOADS_PATH . 'logs/' . '*.log') as $file_path) {
            $file_last_modified = filemtime($file_path);

            if((new \DateTime())->setTimestamp($file_last_modified)->format('m') != $current_month) {
                unlink($file_path);
                $deleted_count++;
            }
        }

        if(DEBUG) {
            echo sprintf('logs_cleanup: Deleted %s file logs.', $deleted_count);
        }
    }

    private function users_logs_cleanup() {
        /* Delete old users logs */
        $ninety_days_ago_datetime = (new \DateTime())->modify('-90 days')->format('Y-m-d H:i:s');
        db()->where('datetime', $ninety_days_ago_datetime, '<')->delete('users_logs');
    }

    private function internal_notifications_cleanup() {
        /* Delete old users notifications */
        $ninety_days_ago_datetime = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
        db()->where('datetime', $ninety_days_ago_datetime, '<')->delete('internal_notifications');
    }

    private function users_impressions_reset() {
        db()->update('users', ['current_month_notifications_impressions' => 0]);
    }

    private function notifications_logs_cleanup() {

        /* Clean the track_logs table */
        $activity_date = (new \DateTime())->modify('-30 day')->format('Y-m-d H:i:s');
        database()->query("DELETE FROM `track_logs` WHERE `datetime` < '{$activity_date}'");

        /* Clean the track notifications table based on the users plan */
        $result = database()->query("SELECT `user_id`, `plan_settings` FROM `users` WHERE `status` = 1");

        /* Go through each result */
        while($user = $result->fetch_object()) {
            $user->plan_settings = json_decode($user->plan_settings);

            if($user->plan_settings->track_notifications_retention !== -1) {

                /* Clear out old notification statistics logs */
                $x_days_ago_datetime = (new \DateTime())->modify('-' . ($row->plan_settings->track_notifications_retention ?? 90) . ' days')->format('Y-m-d H:i:s');
                database()->query("DELETE FROM `track_notifications` WHERE `datetime` < '{$x_days_ago_datetime}'");

                if(DEBUG) {
                    echo sprintf('Track notifications cleanup done for user_id %s', $user->user_id);
                }

            }

            if($user->plan_settings->track_conversions_retention !== -1) {

                /* Clear out old notification statistics logs */
                $x_days_ago_datetime = (new \DateTime())->modify('-' . ($row->plan_settings->track_conversions_retention ?? 90) . ' days')->format('Y-m-d H:i:s');
                database()->query("DELETE FROM `track_conversions` WHERE `datetime` < '{$x_days_ago_datetime}'");

                if(DEBUG) {
                    echo sprintf('Track conversions cleanup done for user_id %s', $user->user_id);
                }

            }
        }

    }

    private function users_plan_expiry_reminder() {
        if(!settings()->payment->user_plan_expiry_reminder) {
            return;
        }

        /* Determine when to send the email reminder */
        $days = settings()->payment->user_plan_expiry_reminder;
        $future_date = (new \DateTime())->modify('+' . $days . ' days')->format('Y-m-d H:i:s');

        $result = database()->query("
            SELECT
                `user_id`,
                `name`,
                `email`,
                `plan_id`,
                `plan_expiration_date`,
                `language`,
                `anti_phishing_code`
            FROM 
                `users`
            WHERE 
                `status` = 1
                AND `plan_id` <> 'free'
                AND `plan_expiry_reminder` = '0'
                AND (`payment_subscription_id` IS NULL OR `payment_subscription_id` = '')
				AND '{$future_date}' > `plan_expiration_date`
            LIMIT 25
        ");

        /* Go through each result */
        while($user = $result->fetch_object()) {

            /* Determine the exact days until expiration */
            $days_until_expiration = (new \DateTime($user->plan_expiration_date))->diff((new \DateTime()))->days;

            /* Prepare the email */
            $email_template = get_email_template(
                [
                    '{{DAYS_UNTIL_EXPIRATION}}' => $days_until_expiration,
                ],
                l('global.emails.user_plan_expiry_reminder.subject', $user->language),
                [
                    '{{DAYS_UNTIL_EXPIRATION}}' => $days_until_expiration,
                    '{{USER_PLAN_RENEW_LINK}}' => url('pay/' . $user->plan_id),
                    '{{NAME}}' => $user->name,
                    '{{PLAN_NAME}}' => (new \Altum\Models\Plan())->get_plan_by_id($user->plan_id)->name,
                ],
                l('global.emails.user_plan_expiry_reminder.body', $user->language)
            );

            send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

            /* Update user */
            db()->where('user_id', $user->user_id)->update('users', ['plan_expiry_reminder' => 1]);

            if(DEBUG) {
                echo sprintf('Email sent for user_id %s', $user->user_id);
            }
        }

    }

    public function broadcasts() {

        $this->initiate();

        /* Update cron job last run date */
        $this->update_cron_execution_datetimes('broadcasts_datetime');

        /* Process a maximum of 30 emails per cron job run */
        $i = 1;
        while(($broadcast = db()->where('status', 'processing')->getOne('broadcasts')) && $i <= 30) {
            $broadcast->users_ids = json_decode($broadcast->users_ids ?? '[]');
            $broadcast->sent_users_ids = json_decode($broadcast->sent_users_ids ?? '[]');
            $broadcast->settings = json_decode($broadcast->settings ?? '[]');

            $users_ids_to_be_processed = array_diff($broadcast->users_ids, $broadcast->sent_users_ids);

            /* Get first user that needs to be processed */
            if(count($users_ids_to_be_processed)) {
                $user_id = reset($users_ids_to_be_processed);
                $user = db()->where('user_id', $user_id)->getOne('users', ['user_id', 'name', 'email', 'language', 'anti_phishing_code']);

                /* Prepare the email */
                $email_template = get_email_template(
                    [
                        '{{NAME}}' => $user->name,
                        '{{EMAIL}}' => $user->email,
                    ],
                    $broadcast->subject,
                    [
                        '{{NAME}}' => $user->name,
                        '{{EMAIL}}' => $user->email,
                    ],
                    convert_editorjs_json_to_html($broadcast->content)
                );

                $broadcast->sent_users_ids[] = $user_id;

                /* Add the tracking pixel */
                if(settings()->main->broadcasts_statistics_is_enabled) {
                    $tracking_id = base64_encode('broadcast_id=' . $broadcast->broadcast_id . '&user_id=' . $user->user_id);
                    $email_template->body .= '<img src="' . SITE_URL . 'broadcast?id=' . $tracking_id . '" style="display: none;" />';
                }

                /* Replace all links with trackable links */
                $email_template->body = preg_replace('/<a href=\"(.+)\"/', '<a href="' . SITE_URL . 'broadcast?id=' . $tracking_id . '&url=$1"', $email_template->body);

                /* Send the email */
                send_mail($user->email, $email_template->subject, $email_template->body, ['is_broadcast' => true, 'is_system_email' => $broadcast->settings->is_system_email, 'anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

                /* Update the broadcast */
                db()->where('broadcast_id', $broadcast->broadcast_id)->update('broadcasts', [
                    'sent_emails' => db()->inc(),
                    'sent_users_ids' => json_encode($broadcast->sent_users_ids),
                    'status' => count($users_ids_to_be_processed) == 1 ? 'sent' : 'processing',
                    'last_sent_email_datetime' => \Altum\Date::$date,
                ]);

                Logger::users($user->user_id, 'broadcast.' . $broadcast->broadcast_id . '.sent');

                if(DEBUG) {
                    echo '<br />' . "broadcast_id - {$broadcast->broadcast_id} | user_id - {$user_id} sent email." . '<br />';
                }
            }

            /* If there are no users to be processed, mark as sent */
            else {
                db()->where('broadcast_id', $broadcast->broadcast_id)->update('broadcasts', [
                    'status' => 'sent'
                ]);
            }

            $i++;
        }

        $this->close();
    }

    public function push_notifications() {
        if(\Altum\Plugin::is_active('push-notifications')) {

            $this->initiate();

            /* Update cron job last run date */
            $this->update_cron_execution_datetimes('push_notifications_datetime');

            require_once \Altum\Plugin::get('push-notifications')->path . 'controllers/Cron.php';

            $this->close();
        }
    }

}
