<?php defined('ALTUMCODE') || die() ?>

<header class="header">
    <div class="container">
        <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= url('dashboard') ?>"><?= l('dashboard.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?= url('campaign/' . $data->campaign->campaign_id) ?>"><?= l('campaign.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= l('notification_create.breadcrumb') ?></li>
            </ol>
        </nav>
<?php endif ?>

        <h1 class="h2 mr-3"><i class="fas fa-fw fa-xs fa-bell mr-1"></i> <?= l('notification_create.header') ?></h1>

        <div class="d-flex align-items-center text-muted mr-3">
            <img src="<?= get_favicon_url_from_domain($data->campaign->domain) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />
             <?= $data->campaign->domain ?>
        </div>
    </div>
</header>

<section class="container">

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="my-5 mb-lg-0 d-flex flex-column flex-md-row justify-content-center align-items-center">
        <div id="notification_preview"></div>
    </div>

    <form name="create_notification" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
        <input type="hidden" name="campaign_id" value="<?= $data->campaign->campaign_id ?>" />

        <div id="notifications" class="mt-5 row d-flex align-items-stretch">
            <?php foreach($data->notifications as $notification_type => $notification_config): ?>

                <?php

                /* Check for permission of usage of the notification */
                if(!$this->user->plan_settings->enabled_notifications->{$notification_type}) {
                    continue;
                }

                ?>

                <?php $notification = \Altum\Notification::get($notification_type, null, null, true, $data->campaign->branding) ?>

                <label class="col-12 col-md-6 col-lg-4 mb-3 mb-md-4 custom-radio-box mb-3">

                    <input type="radio" name="type" value="<?= $notification_type ?>" class="custom-control-input" required="required">

                    <div class="card zoomer h-100">
                        <div class="card-body">
                            <div class="mb-3 text-center">
                                <span class="custom-radio-box-main-icon"><i class="<?= l('notification.' . mb_strtolower($notification_type) . '.icon') ?>"></i></span>
                            </div>

                            <div class="card-title font-weight-bold text-center"><?= l('notification.' . mb_strtolower($notification_type) . '.name') ?></div>

                            <p class="text-muted text-center"><?= l('notification.' . mb_strtolower($notification_type) . '.description') ?></p>
                        </div>
                    </div>

                    <div class="preview" style="display: none">
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>

                </label>
            <?php endforeach ?>
        </div>

        <div class="mt-4">
            <button type="submit" name="submit" class="btn btn-block btn-lg btn-primary"><?= l('global.create') ?></button>
        </div>
    </form>
</section>

<?php ob_start() ?>
<script>
    $('#notifications .altumcode-hidden').removeClass('altumcode-hidden').addClass('altumcode-shown');
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>


<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script>
    /* Preview handler */
    $('input[name="type"]').on('change', event => {

        let preview_html = $(event.currentTarget).closest('label').find('.preview').html();
        let type = $(event.currentTarget).val();

        $('#notification_preview').hide().html(preview_html).fadeIn();

        if(type.includes('_BAR')) {
            $('#notification_preview').removeClass().addClass('notification-create-preview-bar');
        } else {
            $('#notification_preview').removeClass().addClass('notification-create-preview-normal');
        }
    });

    /* Select a default option */
    $('input[name="type"]:first').attr('checked', true).trigger('change');
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
