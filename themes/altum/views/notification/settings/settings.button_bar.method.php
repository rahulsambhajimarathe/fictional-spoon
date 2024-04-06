<?php
defined('ALTUMCODE') || die();

/* Create the content for each tab */
$html = [];

/* Extra Javascript needed */
$javascript = '';
?>

<?php /* Basic Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_name"><?= l('notification.settings.name') ?></label>
        <input type="text" id="settings_name" name="name" class="form-control" value="<?= $data->notification->name ?>" maxlength="256" required="required" />
    </div>

    <div class="form-group">
        <label for="settings_title"><?= l('notification.settings.title') ?></label>
        <input type="text" id="settings_title" name="title" class="form-control" value="<?= $data->notification->settings->title ?>" maxlength="256" />
    </div>

    <div class="form-group">
        <label for="settings_button_text"><?= l('notification.settings.button_text') ?></label>
        <input type="text" id="settings_button_text" name="button_text" class="form-control" value="<?= $data->notification->settings->button_text ?>" maxlength="128" />
    </div>

    <div class="form-group">
        <label for="settings_url"><?= l('notification.settings.url') ?></label>
        <input type="url" id="settings_url" name="url" class="form-control" value="<?= $data->notification->settings->url ?>" maxlength="2048" />
        <small class="form-text text-muted"><?= l('notification.settings.url_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="settings_url_new_tab"
                name="url_new_tab"
            <?= $data->notification->settings->url_new_tab ? 'checked="checked"' : null ?>
        >

        <label class="custom-control-label clickable" for="settings_url_new_tab"><?= l('notification.settings.url_new_tab') ?></label>

        <div>
            <small class="form-text text-muted"><?= l('notification.settings.url_new_tab_help') ?></small>
        </div>
    </div>
<?php $html['basic'] = ob_get_clean() ?>

<?php /* Default Display Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_direction"><i class="fas fa-fw fa-map-signs fa-sm text-muted mr-1"></i> <?= l('notification.settings.direction') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-6">
            <label class="btn btn-gray-200 btn-block text-truncate <?= ($data->notification->settings->direction  ?? null) == 'ltr' ? 'active"' : null?>">
                <input type="radio" name="direction" value="ltr" class="custom-control-input" <?= ($data->notification->settings->direction  ?? null) == 'ltr' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-long-arrow-alt-right fa-sm mr-1"></i> <?= l('notification.settings.direction_ltr') ?>
            </label>
        </div>
        <div class="col-6">
            <label class="btn btn-gray-200 btn-block text-truncate <?= ($data->notification->settings->direction  ?? null) == 'rtl' ? 'active' : null?>">
                <input type="radio" name="direction" value="rtl" class="custom-control-input" <?= ($data->notification->settings->direction  ?? null) == 'rtl' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-long-arrow-alt-left fa-sm mr-1"></i> <?= l('notification.settings.direction_rtl') ?>
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="settings_display_position"><i class="fas fa-fw fa-th fa-sm text-muted mr-1"></i> <?= l('notification.settings.display_position') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-6">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'top' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="top" class="custom-control-input" <?= $data->notification->settings->display_position == 'top' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_top') ?>
            </label>
        </div>

        <div class="col-6">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'top_floating' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="top_floating" class="custom-control-input" <?= $data->notification->settings->display_position == 'top_floating' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_top_floating') ?>
            </label>
        </div>

        <div class="col-6">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'bottom' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="bottom" class="custom-control-input" <?= $data->notification->settings->display_position == 'bottom' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_bottom') ?>
            </label>
        </div>

        <div class="col-6">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'bottom_floating' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="bottom_floating" class="custom-control-input" <?= $data->notification->settings->display_position == 'bottom_floating' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_bottom_floating') ?>
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="settings_display_duration"><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('notification.settings.display_duration') ?></label>
    <div class="input-group">
        <input type="number" min="-1" id="settings_display_duration" name="display_duration" class="form-control" value="<?= $data->notification->settings->display_duration ?>" required="required" />
        <div class="input-group-append">
            <span class="input-group-text"><?= l('global.date.seconds') ?></span>
        </div>
    </div>
    <small class="form-text text-muted"><?= l('notification.settings.display_duration_help') ?></small>
</div>

<div class="form-group custom-control custom-switch">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_close_button"
            name="display_close_button"
            <?= $data->notification->settings->display_close_button ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="display_close_button"><?= l('notification.settings.display_close_button') ?></label>
</div>

<div class="form-group custom-control custom-switch <?= !$this->user->plan_settings->removable_branding ? 'container-disabled': null ?>">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_branding"
            name="display_branding"
            <?= $data->notification->settings->display_branding ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="display_branding"><?= l('notification.settings.display_branding') ?></label>
</div>
<?php $html['display'] = ob_get_clean() ?>

<?php /* Customize Tab */ ?>
<?php ob_start() ?>
    <div class="form-group">
        <label for="settings_title_color"><?= l('notification.settings.title_color') ?></label>
        <input type="hidden" id="settings_title_color" name="title_color" class="form-control" value="<?= $data->notification->settings->title_color ?>" />
        <div id="settings_title_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="settings_background_color"><?= l('notification.settings.background_color') ?></label>
        <input type="hidden" id="settings_background_color" name="background_color" class="form-control" value="<?= $data->notification->settings->background_color ?>" />
        <div id="settings_background_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="settings_background_pattern"><?= l('notification.settings.background_pattern') ?></label>
        <div class="row btn-group-toggle" data-toggle="buttons">
            <div class="col-4">
                <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->background_pattern == '' ? 'active"' : null?>">
                    <input type="radio" name="background_pattern" value="" class="custom-control-input" <?= $data->notification->settings->background_pattern == '' ? 'checked="checked"' : null?> />
                    <?= l('global.none') ?>
                </label>
            </div>

            <?php foreach(get_notifications_background_patterns() as $key => $value): ?>
            <div class="col-4">
                <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->background_pattern == $key ? 'active' : null?>" style="background-image: url(<?= $value ?>);">
                    <input type="radio" name="background_pattern" value="<?= $key ?>" class="custom-control-input" <?= $data->notification->settings->background_pattern == $key ? 'checked="checked"' : null?> data-value="<?= $value ?>" />
                    <?= l('notification.settings.background_pattern_' . $key) ?>
                </label>
            </div>
            <?php endforeach ?>
        </div>
    </div>

    <div class="form-group">
        <label for="settings_button_color"><?= l('notification.settings.button_color') ?></label>
        <input type="hidden" id="settings_button_color" name="button_color" class="form-control" value="<?= $data->notification->settings->button_color ?>" />
        <div id="settings_button_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="settings_button_background_color"><?= l('notification.settings.button_background_color') ?></label>
        <input type="hidden" id="settings_button_background_color" name="button_background_color" class="form-control" value="<?= $data->notification->settings->button_background_color ?>" />
        <div id="settings_button_background_color_pickr"></div>
    </div>

    <div class="form-group">
        <label for="settings_close_button_color"><?= l('notification.settings.close_button_color') ?></label>
        <input type="hidden" id="settings_close_button_color" name="close_button_color" class="form-control" value="<?= $data->notification->settings->close_button_color ?>" />
        <div id="settings_close_button_color_pickr"></div>
    </div>

    <div class="form-group" data-range-counter data-range-counter-suffix="px">
        <label for="settings_border_width"><?= l('notification.settings.border_width') ?></label>
        <input type="range" min="0" max="5" id="settings_border_width" name="border_width" class="form-control-range" value="<?= $data->notification->settings->border_width ?>" />
    </div>

    <div class="form-group">
        <label for="settings_border_color"><?= l('notification.settings.border_color') ?></label>
        <input type="hidden" id="settings_border_color" name="border_color" class="form-control border-left-0" value="<?= $data->notification->settings->border_color ?>" />
        <div id="settings_border_color_pickr"></div>
    </div>
<?php $html['customize'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Notification Preview Handlers */
    $('#settings_title').on('change paste keyup', event => {
        $('#notification_preview .altumcode-button-bar-title').text($(event.currentTarget).val());
    });

    $('#settings_button').on('change paste keyup', event => {
        $('#notification_preview .altumcode-button-bar-coupon-code').text($(event.currentTarget).val());
    });

    /* Title Color Handler */
    let settings_title_color_pickr = Pickr.create({
        el: '#settings_title_color_pickr',
        default: $('#settings_title_color').val(),
        ...pickr_options
    });

    settings_title_color_pickr.on('change', hsva => {
        $('#settings_title_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-button-bar-title').css('color', hsva.toHEXA().toString());
    });

    /* Background Color Handler */
    let settings_background_color_pickr = Pickr.create({
        el: '#settings_background_color_pickr',
        default: $('#settings_background_color').val(),
        ...pickr_options
    });

    settings_background_color_pickr.on('change', hsva => {
        $('#settings_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-wrapper').css('background-color', hsva.toHEXA().toString());
    });

    /* Button Color Handler */
    let settings_button_color_pickr = Pickr.create({
        el: '#settings_button_color_pickr',
        default: $('#settings_button_color').val(),
        ...pickr_options
    });

    settings_button_color_pickr.on('change', hsva => {
        $('#settings_button_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-button-bar-button').css('color', hsva.toHEXA().toString());
    });

    /* Button background Color Handler */
    let settings_button_background_color_pickr = Pickr.create({
        el: '#settings_button_background_color_pickr',
        default: $('#settings_button_background_color').val(),
        ...pickr_options
    });

    settings_button_background_color_pickr.on('change', hsva => {
        $('#settings_button_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-button-bar-button').css('background', hsva.toHEXA().toString());
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
