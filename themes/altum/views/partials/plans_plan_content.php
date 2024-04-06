<?php defined('ALTUMCODE') || die() ?>

<ul class="pricing-feature-list">
    <li class="pricing-feature d-flex justify-content-between align-items-center">
        <div><?= sprintf(l('global.plan_settings.campaigns_limit'), '<strong>' . ($data->plan_settings->campaigns_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->campaigns_limit)) . '</strong>') ?></div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->campaigns_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </li>

    <li class="pricing-feature d-flex justify-content-between align-items-center">
        <div><?= sprintf(l('global.plan_settings.notifications_limit'), '<strong>' . ($data->plan_settings->notifications_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->notifications_limit)) . '</strong>') ?></div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->notifications_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </li>

    <li class="pricing-feature d-flex justify-content-between align-items-center">
        <div><?= sprintf(l('global.plan_settings.notifications_impressions_limit'), '<strong>' . ($data->plan_settings->notifications_impressions_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->notifications_impressions_limit, 0, true)) . '</strong>') ?></div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->notifications_impressions_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </li>

    <?php if(\Altum\Plugin::is_active('teams')): ?>
        <li class="pricing-feature d-flex justify-content-between align-items-center">
            <div><?= sprintf(l('global.plan_settings.teams_limit'), '<strong>' . ($data->plan_settings->teams_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->teams_limit, 0, true)) . '</strong>') ?></div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->teams_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </li>

        <li class="pricing-feature d-flex justify-content-between align-items-center">
            <div><?= sprintf(l('global.plan_settings.team_members_limit'), '<strong>' . ($data->plan_settings->team_members_limit == -1 ? l('global.unlimited') : nr($data->plan_settings->team_members_limit, 0, true)) . '</strong>') ?></div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->team_members_limit ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
    <li class="pricing-feature d-flex justify-content-between align-items-center">
        <div><?= sprintf(l('global.plan_settings.affiliate_commission_percentage'), '<strong>' . nr($data->plan_settings->affiliate_commission_percentage) . '%</strong>') ?></div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->affiliate_commission_percentage ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </li>
    <?php endif ?>

    <li class="pricing-feature d-flex justify-content-between align-items-center">
        <div><?= sprintf(l('global.plan_settings.track_notifications_retention'), '<strong>' . ($data->plan_settings->track_notifications_retention == -1 ? l('global.unlimited') : nr($data->plan_settings->track_notifications_retention, 0, true)) . '</strong>') ?></div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->track_notifications_retention ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </li>

    <li class="pricing-feature d-flex justify-content-between align-items-center">
        <div><?= sprintf(l('global.plan_settings.track_conversions_retention'), '<strong>' . ($data->plan_settings->track_conversions_retention == -1 ? l('global.unlimited') : nr($data->plan_settings->track_conversions_retention, 0, true)) . '</strong>') ?></div>

        <i class="fas fa-fw fa-sm <?= $data->plan_settings->track_conversions_retention ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
    </li>

    <?php $enabled_notifications = array_filter((array) $data->plan_settings->enabled_notifications) ?>
    <?php $enabled_notifications_count = count($enabled_notifications) ?>
    <?php
    $enabled_notifications_string = implode(', ', array_map(function($key) {
        return l('notification.' . mb_strtolower($key) . '.name');
    }, array_keys($enabled_notifications)));
    ?>
    <?php if($enabled_notifications_count == count(\Altum\Notification::get_config())): ?>
        <li class="pricing-feature d-flex justify-content-between align-items-center">
            <div><?= l('global.plan_settings.enabled_notifications_all') ?></div>

            <i class="fas fa-fw fa-sm fa-check text-success"></i>
        </li>
    <?php else: ?>
        <li class="pricing-feature d-flex justify-content-between align-items-center" data-toggle="tooltip" title="<?= $enabled_notifications_string ?>">
            <div><?= sprintf(l('global.plan_settings.enabled_notifications_x'), '<strong>' . nr($enabled_notifications_count) . '</strong>') ?></div>

            <i class="fas fa-fw fa-sm <?= $enabled_notifications_count ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </li>
    <?php endif ?>

    <?php foreach(require APP_PATH . 'includes/simple_user_plan_settings.php' as $plan_setting): ?>
        <li class="pricing-feature d-flex justify-content-between align-items-center">
            <div><?= l('global.plan_settings.' . $plan_setting) ?></div>

            <i class="fas fa-fw fa-sm <?= $data->plan_settings->{$plan_setting} ? 'fa-check text-success' : 'fa-times text-muted' ?>"></i>
        </li>
    <?php endforeach ?>
</ul>

