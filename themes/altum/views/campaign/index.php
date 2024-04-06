<?php defined('ALTUMCODE') || die() ?>

<header class="header pb-0">
    <div class="container">

        <?php if(settings()->main->breadcrumbs_is_enabled): ?>
            <nav aria-label="breadcrumb">
                <ol class="custom-breadcrumbs small">
                    <li>
                        <a href="<?= url('campaigns') ?>"><?= l('campaigns.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page"><?= l('campaign.breadcrumb') ?></li>
                </ol>
            </nav>
        <?php endif ?>

        <div class="row">
            <div class="col text-truncate">
                <h1 class="h2 text-truncate"><span><?= $data->campaign->name ?></span></h1>

                <div class="d-flex align-items-center text-muted">
                    <img src="<?= get_favicon_url_from_domain($data->campaign->domain) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />
                    <div class="d-inline-block text-truncate"><?= $data->campaign->domain ?></div>
                </div>
            </div>

            <div class="col-auto">
                <div class="d-flex align-items-center">
                    <div class="custom-control custom-switch mr-3" data-toggle="tooltip" title="<?= l('campaigns.table.is_enabled_tooltip') ?>">
                        <input
                                type="checkbox"
                                class="custom-control-input"
                                id="campaign_is_enabled_<?= $data->campaign->campaign_id ?>"
                                data-row-id="<?= $data->campaign->campaign_id ?>"
                                onchange="ajax_call_helper(event, 'campaigns-ajax', 'is_enabled_toggle')"
                            <?= $data->campaign->is_enabled ? 'checked="checked"' : null ?>
                        >
                        <label class="custom-control-label clickable" for="campaign_is_enabled_<?= $data->campaign->campaign_id ?>"></label>
                    </div>

                    <div class="dropdown">
                        <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                            <i class="fas fa-fw fa-ellipsis-v"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="<?= url('campaign/' . $data->campaign->campaign_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-server mr-2"></i> <?= l('global.view') ?></a>
                            <a href="<?= url('campaign/' . $data->campaign->campaign_id . '/statistics') ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('campaign.statistics.link') ?></a>

                            <a href="#" data-toggle="modal" data-target="#update_campaign" data-campaign-id="<?= $data->campaign->campaign_id ?>" data-name="<?= $data->campaign->name ?>" data-domain="<?= $data->campaign->domain ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?></a>

                            <a
                                    href="#"
                                    data-toggle="modal"
                                    data-target="#campaign_pixel_key"
                                    data-pixel-key="<?= $data->campaign->pixel_key ?>"
                                    class="dropdown-item"
                            ><i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('campaign.pixel_key') ?></a>

                            <div <?= $this->user->plan_settings->custom_branding ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                <a
                                        href="#"
                                    <?php if($this->user->plan_settings->custom_branding): ?>
                                        data-toggle="modal"
                                        data-target="#custom_branding_campaign_modal"
                                        data-campaign-id="<?= $data->campaign->campaign_id ?>"
                                        data-branding-name="<?= $data->campaign->branding->name ?? '' ?>"
                                        data-branding-url="<?= $data->campaign->branding->url ?? '' ?>"
                                        class="dropdown-item"
                                    <?php else: ?>
                                        class="dropdown-item container-disabled"
                                    <?php endif ?>
                                >
                                    <i class="fas fa-fw fa-sm fa-random mr-2"></i> <?= l('campaign.custom_branding') ?>
                                </a>
                            </div>

                            <a href="#" data-toggle="modal" data-target="#campaign_duplicate_modal" data-campaign-id="<?= $data->campaign->campaign_id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-clone mr-2"></i> <?= l('global.duplicate') ?></a>

                            <a href="#" data-toggle="modal" data-target="#campaign_delete_modal" data-campaign-id="<?= $data->campaign->campaign_id ?>" data-resource-name="<?= $data->campaign->name ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <ul class="mt-5 nav nav-custom">
            <li class="nav-item">
                <a href="<?= url('campaign/' . $data->campaign->campaign_id) ?>" class="nav-link <?= $data->method == 'settings' ? 'active' : null ?>">
                    <i class="fas fa-fw fa-sm fa-bell mr-1"></i> <?= l('campaign.notifications.link') ?>
                </a>
            </li>

            <li class="nav-item">
                <a href="<?= url('campaign/' . $data->campaign->campaign_id . '/statistics') ?>" class="nav-link <?= $data->method == 'statistics' ? 'active' : null ?>">
                    <i class="fas fa-fw fa-sm fa-chart-bar mr-1"></i> <?= l('campaign.statistics.link') ?>
                </a>
            </li>
        </ul>
    </div>
</header>

<section class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['method'] ?>
</section>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/campaign/campaign_pixel_key_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/campaign/update_campaign_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/campaign/custom_branding_campaign_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'notification_duplicate_modal', 'resource_id' => 'notification_id', 'path' => 'notification/duplicate']), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'campaign_duplicate_modal', 'resource_id' => 'campaign_id', 'path' => 'campaign/duplicate']), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'campaign',
    'resource_id' => 'campaign_id',
    'has_dynamic_resource_name' => true,
    'path' => 'campaign/delete'
]), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'notification',
    'resource_id' => 'notification_id',
    'has_dynamic_resource_name' => true,
    'path' => 'notification/delete'
]), 'modals'); ?>

<?php ob_start() ?>
<script>
    <?php if(isset($_GET['pixel_key_modal'])): ?>
    /* Open the pixel key modal */
    $('[data-pixel-key]').trigger('click');
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
