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
        <label for="settings_html"><?= l('notification.settings.html') ?></label>
        <textarea id="settings_html" name="html" class="form-control" maxlength="8192" required="required"><?= $data->notification->settings->html ?></textarea>
        <small class="form-text text-muted"><?= l('notification.settings.html_help') ?></small>
    </div>
<?php $html['basic'] = ob_get_clean() ?>

<?php /* Default Display Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_display_position"><i class="fas fa-fw fa-th fa-sm text-muted mr-1"></i> <?= l('notification.settings.display_position') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'top_left' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="top_left" class="custom-control-input" <?= $data->notification->settings->display_position == 'top_left' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_top_left') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'top_center' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="top_center" class="custom-control-input" <?= $data->notification->settings->display_position == 'top_center' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_top_center') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'top_right' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="top_right" class="custom-control-input" <?= $data->notification->settings->display_position == 'top_right' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_top_right') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'middle_left' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="middle_left" class="custom-control-input" <?= $data->notification->settings->display_position == 'middle_left' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_middle_left') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'middle_center' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="middle_center" class="custom-control-input" <?= $data->notification->settings->display_position == 'middle_center' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_middle_center') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'middle_right' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="middle_right" class="custom-control-input" <?= $data->notification->settings->display_position == 'middle_right' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_middle_right') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'bottom_left' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="bottom_left" class="custom-control-input" <?= $data->notification->settings->display_position == 'bottom_left' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_bottom_left') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'bottom_center' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="bottom_center" class="custom-control-input" <?= $data->notification->settings->display_position == 'bottom_center' ? 'checked="checked"' : null?> />
                <?= l('notification.settings.display_position_bottom_center') ?>
            </label>
        </div>

        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_position == 'bottom_right' ? 'active"' : null?>">
                <input type="radio" name="display_position" value="bottom_right" class="custom-control-input" <?= $data->notification->settings->display_position == 'bottom_right' ? 'checked="checked"' : null?> />
                 <?= l('notification.settings.display_position_bottom_right') ?>
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

<div class="form-group">
    <label for="settings_border_radius"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('notification.settings.border_radius') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= ($data->notification->settings->border_radius  ?? null) == 'straight' ? 'active"' : null?>">
                <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($data->notification->settings->border_radius  ?? null) == 'straight' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('notification.settings.border_radius_straight') ?>
            </label>
        </div>
        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= ($data->notification->settings->border_radius  ?? null) == 'round' ? 'active' : null?>">
                <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($data->notification->settings->border_radius  ?? null) == 'round' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('notification.settings.border_radius_round') ?>
            </label>
        </div>
        <div class="col-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= ($data->notification->settings->border_radius  ?? null) == 'rounded' ? 'active' : null?>">
                <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($data->notification->settings->border_radius  ?? null) == 'rounded' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('notification.settings.border_radius_rounded') ?>
            </label>
        </div>
    </div>
</div>

    <div class="form-group custom-control custom-switch">
        <input
            type="checkbox"
            class="custom-control-input"
            id="settings_shadow"
            name="shadow"
            <?= $data->notification->settings->shadow ? 'checked="checked"' : null ?>
        >

        <label class="custom-control-label clickable" for="settings_shadow"><?= l('notification.settings.shadow') ?></label>

        <div>
            <small class="form-text text-muted"><?= l('notification.settings.shadow_help') ?></small>
        </div>
    </div>
<?php $html['customize'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Notification Preview Handlers */
    $('#settings_html').on('change paste keyup', event => {
        $('#notification_preview .altumcode-custom-html-html').html($(event.currentTarget).val());
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


</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
