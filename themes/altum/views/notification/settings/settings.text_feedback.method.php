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
    <label for="settings_description"><?= l('notification.settings.description') ?></label>
    <input type="text" id="settings_description" name="description" class="form-control" value="<?= $data->notification->settings->description ?>" maxlength="512" />
</div>

<div class="form-group">
    <label for="settings_input_placeholder"><?= l('notification.settings.input_placeholder') ?></label>
    <input type="text" id="settings_input_placeholder" name="input_placeholder" class="form-control" value="<?= $data->notification->settings->input_placeholder ?>" maxlength="128" />
</div>

<div class="form-group">
    <label for="settings_button_text"><?= l('notification.settings.button_text') ?></label>
    <input type="text" id="settings_button_text" name="button_text" class="form-control" value="<?= $data->notification->settings->button_text ?>" maxlength="128" />
</div>

<div class="form-group">
    <label for="settings_thank_you_url"><?= l('notification.settings.thank_you_url') ?></label>
    <input type="text" id="settings_thank_you_url" name="thank_you_url" class="form-control" value="<?= $data->notification->settings->thank_you_url ?>" maxlength="2048" />
    <small class="form-text text-muted"><?= l('notification.settings.thank_you_url_help') ?></small>
</div>
<?php $html['basic'] = ob_get_clean() ?>


<?php /* Customize Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_title_color"><?= l('notification.settings.title_color') ?></label>
    <input type="hidden" id="settings_title_color" name="title_color" class="form-control" value="<?= $data->notification->settings->title_color ?>" />
    <div id="settings_title_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_description_color"><?= l('notification.settings.description_color') ?></label>
    <input type="hidden" id="settings_description_color" name="description_color" class="form-control" value="<?= $data->notification->settings->description_color ?>" />
    <div id="settings_description_color_pickr"></div>
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
    <label for="settings_button_background_color"><?= l('notification.settings.button_background_color') ?></label>
    <input type="hidden" id="settings_button_background_color" name="button_background_color" class="form-control" value="<?= $data->notification->settings->button_background_color ?>" />
    <div id="settings_button_background_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_button_color"><?= l('notification.settings.button_color') ?></label>
    <input type="hidden" id="settings_button_color" name="button_color" class="form-control" value="<?= $data->notification->settings->button_color ?>" />
    <div id="settings_button_color_pickr"></div>
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


<?php /* Data Tab */ ?>
<?php ob_start() ?>
<div class="form-group custom-control custom-switch">
    <input
            type="checkbox"
            class="custom-control-input"
            id="data_send_is_enabled"
            name="data_send_is_enabled"
        <?= $data->notification->settings->data_send_is_enabled ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="data_send_is_enabled"><?= l('notification.settings.data_send_is_enabled') ?></label>
</div>

<div id="data_send" class="container-disabled">
    <div class="form-group">
        <label for="settings_data_send_webhook"><?= l('notification.settings.data_send_webhook') ?></label>
        <input type="text" id="settings_data_send_webhook" name="data_send_webhook" class="form-control" value="<?= $data->notification->settings->data_send_webhook ?>" maxlength="2048" placeholder="<?= l('notification.settings.data_send_webhook_placeholder') ?>" aria-label="<?= l('notification.settings.data_send_webhook_placeholder') ?>" />
        <small class="form-text text-muted"><?= l('notification.settings.data_send_webhook_help') ?></small>
    </div>

    <div class="form-group">
        <label for="settings_data_send_email"><?= l('notification.settings.data_send_email') ?></label>
        <input type="text" id="settings_data_send_email" name="data_send_email" class="form-control" value="<?= $data->notification->settings->data_send_email ?>" maxlength="320"  placeholder="<?= l('notification.settings.data_send_email_placeholder') ?>" aria-label="<?= l('notification.settings.data_send_email_placeholder') ?>" />
        <small class="form-text text-muted"><?= l('notification.settings.data_send_email_help') ?></small>
    </div>
</div>
<?php $html['data'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Cancel the submit button form of the email collector */
    $('#altumcode-text-feedback-form').on('submit', event => event.preventDefault());

    $('#notification_preview .altumcode-hidden').removeClass('altumcode-hidden').addClass('altumcode-shown');

    /* Notification Preview Handlers */
    $('#settings_title').on('change paste keyup', event => {
        $('#notification_preview .altumcode-text-feedback-title').text($(event.currentTarget).val());
    });

    $('#settings_description').on('change paste keyup', event => {
        $('#notification_preview .altumcode-text-feedback-description').text($(event.currentTarget).val());
    });

    $('#settings_input_placeholder').on('change paste keyup', event => {
        $('#notification_preview [name="input"]').attr('placeholder', $(event.currentTarget).val());
    });

    $('#settings_button_text').on('change paste keyup', event => {
        $('#notification_preview [name="button"]').text($(event.currentTarget).val());
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
        $('#notification_preview .altumcode-text-feedback-title').css('color', hsva.toHEXA().toString());
    });


    /* Description Color Handler */
    let settings_description_color_pickr = Pickr.create({
        el: '#settings_description_color_pickr',
        default: $('#settings_description_color').val(),
        ...pickr_options
    });

    settings_description_color_pickr.on('change', hsva => {
        $('#settings_description_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-text-feedback-description').css('color', hsva.toHEXA().toString());
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

    /* Submit Background Color Handler */
    let settings_button_background_color_pickr = Pickr.create({
        el: '#settings_button_background_color_pickr',
        default: $('#settings_button_background_color').val(),
        ...pickr_options
    });

    settings_button_background_color_pickr.on('change', hsva => {
        $('#settings_button_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview [name="button"]').css('background', hsva.toHEXA().toString());
    });

    /* Submit Background Color Handler */
    let settings_button_color_pickr = Pickr.create({
        el: '#settings_button_color_pickr',
        default: $('#settings_button_color').val(),
        ...pickr_options
    });

    settings_button_color_pickr.on('change', hsva => {
        $('#settings_button_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview [name="button"]').css('color', hsva.toHEXA().toString());
    });

    /* Data Send Handler */
    let data_send_status_handler = () => {

        if($('#data_send_is_enabled:checked').length > 0) {

            /* Remove disabled container if depending on the status of the trigger checkbox */
            $('#data_send').removeClass('container-disabled');

        } else {

            /* Disable the container visually */
            $('#data_send').addClass('container-disabled');

        }
    };

    /* Trigger it for the first initial load */
    data_send_status_handler();

    /* Trigger on status change live of the checkbox */
    $('#data_send_is_enabled').on('change', data_send_status_handler);
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
