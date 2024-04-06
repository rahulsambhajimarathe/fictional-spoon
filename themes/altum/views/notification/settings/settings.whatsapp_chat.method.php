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
    <label for="settings_agent_image"><?= l('notification.settings.agent_image') ?></label>
    <input type="url" id="settings_agent_image" name="agent_image" class="form-control" value="<?= $data->notification->settings->agent_image ?>" maxlength="2048" />
    <small class="form-text text-muted"><?= l('notification.settings.image_help') ?></small>
</div>

<div class="form-group">
    <label for="settings_agent_image_alt"><?= l('notification.settings.image_alt') ?></label>
    <input type="text" id="settings_agent_image_alt" name="agent_image_alt" class="form-control" value="<?= $data->notification->settings->agent_image_alt ?>" maxlength="100" />
    <small class="form-text text-muted"><?= l('notification.settings.image_alt_help') ?></small>
</div>

<div class="form-group">
    <label for="settings_agent_name"><?= l('notification.settings.agent_name') ?></label>
    <input type="text" id="settings_agent_name" name="agent_name" class="form-control" value="<?= $data->notification->settings->agent_name ?>" maxlength="64" />
</div>

<div class="form-group">
    <label for="settings_agent_description"><?= l('notification.settings.agent_description') ?></label>
    <input type="text" id="settings_agent_description" name="agent_description" class="form-control" value="<?= $data->notification->settings->agent_description ?>" maxlength="512" />
</div>

<div class="form-group">
    <label for="settings_agent_message"><?= l('notification.settings.agent_message') ?></label>
    <input type="text" id="settings_agent_message" name="agent_message" class="form-control" value="<?= $data->notification->settings->agent_message ?>" maxlength="1024" />
</div>

<div class="form-group">
    <label for="settings_agent_phone_number"><?= l('notification.settings.agent_phone_number') ?></label>
    <input type="number" id="settings_agent_phone_number" name="agent_phone_number" class="form-control" value="<?= $data->notification->settings->agent_phone_number ?>" maxlength="32" />
</div>

<div class="form-group">
    <label for="settings_button_text"><?= l('notification.settings.button_text') ?></label>
    <input type="text" id="settings_button_text" name="button_text" class="form-control" value="<?= $data->notification->settings->button_text ?>" maxlength="128" />
</div>
<?php $html['basic'] = ob_get_clean() ?>


<?php /* Customize Tab */ ?>
<?php ob_start() ?>

<div class="form-group">
    <label for="settings_header_agent_name_color"><?= l('notification.settings.header_agent_name_color') ?></label>
    <input type="hidden" id="settings_header_agent_name_color" name="header_agent_name_color" class="form-control" value="<?= $data->notification->settings->header_agent_name_color ?>" />
    <div id="settings_header_agent_name_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_header_agent_description_color"><?= l('notification.settings.header_agent_description_color') ?></label>
    <input type="hidden" id="settings_header_agent_description_color" name="header_agent_description_color" class="form-control" value="<?= $data->notification->settings->header_agent_description_color ?>" />
    <div id="settings_header_agent_description_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_header_background_color"><?= l('notification.settings.header_background_color') ?></label>
    <input type="hidden" id="settings_header_background_color" name="header_background_color" class="form-control" value="<?= $data->notification->settings->header_background_color ?>" />
    <div id="settings_header_background_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_content_agent_name_color"><?= l('notification.settings.content_agent_name_color') ?></label>
    <input type="hidden" id="settings_content_agent_name_color" name="content_agent_name_color" class="form-control" value="<?= $data->notification->settings->content_agent_name_color ?>" />
    <div id="settings_content_agent_name_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_content_agent_message_color"><?= l('notification.settings.content_agent_message_color') ?></label>
    <input type="hidden" id="settings_content_agent_message_color" name="content_agent_message_color" class="form-control" value="<?= $data->notification->settings->content_agent_message_color ?>" />
    <div id="settings_content_agent_message_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_content_agent_message_background_color"><?= l('notification.settings.content_agent_message_background_color') ?></label>
    <input type="hidden" id="settings_content_agent_message_background_color" name="content_agent_message_background_color" class="form-control" value="<?= $data->notification->settings->content_agent_message_background_color ?>" />
    <div id="settings_content_agent_message_background_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_content_background_color"><?= l('notification.settings.content_background_color') ?></label>
    <input type="hidden" id="settings_content_background_color" name="content_background_color" class="form-control" value="<?= $data->notification->settings->content_background_color ?>" />
    <div id="settings_content_background_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_footer_button_color"><?= l('notification.settings.footer_button_color') ?></label>
    <input type="hidden" id="settings_footer_button_color" name="footer_button_color" class="form-control" value="<?= $data->notification->settings->footer_button_color ?>" />
    <div id="settings_footer_button_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_footer_button_background_color"><?= l('notification.settings.footer_button_background_color') ?></label>
    <input type="hidden" id="settings_footer_button_background_color" name="footer_button_background_color" class="form-control" value="<?= $data->notification->settings->footer_button_background_color ?>" />
    <div id="settings_footer_button_background_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_footer_background_color"><?= l('notification.settings.footer_background_color') ?></label>
    <input type="hidden" id="settings_footer_background_color" name="footer_background_color" class="form-control" value="<?= $data->notification->settings->footer_background_color ?>" />
    <div id="settings_footer_background_color_pickr"></div>
</div>

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
    $('#notification_preview .altumcode-hidden').removeClass('altumcode-hidden').addClass('altumcode-shown');

    /* Notification Preview Handlers */
    $('#settings_title').on('change paste keyup', event => {
        $('#notification_preview .altumcode-whatsapp-chat-title').text($(event.currentTarget).val());
    });

    $('#settings_agent_image').on('change paste keyup', event => {
        $('#notification_preview .altumcode-whatsapp-chat-window-header-image').attr('src', $(event.currentTarget).val());
    });

    $('#settings_agent_name').on('change paste keyup', event => {
        $('#notification_preview .altumcode-whatsapp-chat-window-header-title').text($(event.currentTarget).val());
        $('#notification_preview .altumcode-whatsapp-chat-window-content-reply-author').text($(event.currentTarget).val());
    });

    $('#settings_agent_description').on('change paste keyup', event => {
        $('#notification_preview .altumcode-whatsapp-chat-window-header-description').text($(event.currentTarget).val());
    });

    $('#settings_agent_message').on('change paste keyup', event => {
        $('#notification_preview .altumcode-whatsapp-chat-window-content-reply-text').text($(event.currentTarget).val());
    });

    $('#settings_button_text').on('change paste keyup', event => {
        $('#notification_preview .altumcode-whatsapp-chat-window-footer-button').text($(event.currentTarget).val());
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
        $('#notification_preview .altumcode-whatsapp-chat-title').css('color', hsva.toHEXA().toString());
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


    /* Header Agent Name Color Handler */
    let settings_header_agent_name_color_pickr = Pickr.create({
        el: '#settings_header_agent_name_color_pickr',
        default: $('#settings_header_agent_name_color').val(),
        ...pickr_options
    });

    settings_header_agent_name_color_pickr.on('change', hsva => {
        $('#settings_header_agent_name_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-header-title').css('color', hsva.toHEXA().toString());
    });

    /* Header Agent Description Color Handler */
    let settings_header_agent_description_color_pickr = Pickr.create({
        el: '#settings_header_agent_description_color_pickr',
        default: $('#settings_header_agent_description_color').val(),
        ...pickr_options
    });

    settings_header_agent_description_color_pickr.on('change', hsva => {
        $('#settings_header_agent_description_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-header-description').css('color', hsva.toHEXA().toString());
    });

    /* Header Agent Description Color Handler */
    let settings_header_background_color_pickr = Pickr.create({
        el: '#settings_header_background_color_pickr',
        default: $('#settings_header_background_color').val(),
        ...pickr_options
    });

    settings_header_background_color_pickr.on('change', hsva => {
        $('#settings_header_background_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-header').css('background', hsva.toHEXA().toString());
    });

    /* Content Agent Name Color Handler */
    let settings_content_agent_name_color_pickr = Pickr.create({
        el: '#settings_content_agent_name_color_pickr',
        default: $('#settings_content_agent_name_color').val(),
        ...pickr_options
    });

    settings_content_agent_name_color_pickr.on('change', hsva => {
        $('#settings_content_agent_name_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-content-reply-author').css('color', hsva.toHEXA().toString());
    });

    /* Content Agent Reply Color Handler */
    let settings_content_agent_message_color_pickr = Pickr.create({
        el: '#settings_content_agent_message_color_pickr',
        default: $('#settings_content_agent_message_color').val(),
        ...pickr_options
    });

    settings_content_agent_message_color_pickr.on('change', hsva => {
        $('#settings_content_agent_message_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-content-reply-text').css('color', hsva.toHEXA().toString());
    });

    /* Content Agent Reply Background Color Handler */
    let settings_content_agent_message_background_color_pickr = Pickr.create({
        el: '#settings_content_agent_message_background_color_pickr',
        default: $('#settings_content_agent_message_background_color').val(),
        ...pickr_options
    });

    settings_content_agent_message_background_color_pickr.on('change', hsva => {
        $('#settings_content_agent_message_background_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-content-reply').css('background', hsva.toHEXA().toString());
    });

    /* Content Agent Reply Background Color Handler */
    let settings_content_background_color_pickr = Pickr.create({
        el: '#settings_content_background_color_pickr',
        default: $('#settings_content_background_color').val(),
        ...pickr_options
    });

    settings_content_background_color_pickr.on('change', hsva => {
        $('#settings_content_background_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-content').css('background', hsva.toHEXA().toString());
    });

    /* Footer Background Color Handler */
    let settings_footer_background_color_pickr = Pickr.create({
        el: '#settings_footer_background_color_pickr',
        default: $('#settings_footer_background_color').val(),
        ...pickr_options
    });

    settings_footer_background_color_pickr.on('change', hsva => {
        $('#settings_footer_background_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-footer').css('background', hsva.toHEXA().toString());
    });

    /* Footer Background Color Handler */
    let settings_footer_button_background_color_pickr = Pickr.create({
        el: '#settings_footer_button_background_color_pickr',
        default: $('#settings_footer_button_background_color').val(),
        ...pickr_options
    });

    settings_footer_button_background_color_pickr.on('change', hsva => {
        $('#settings_footer_button_background_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-footer-button').css('background', hsva.toHEXA().toString());
    });

    /* Footer Background Color Handler */
    let settings_footer_button_color_pickr = Pickr.create({
        el: '#settings_footer_button_color_pickr',
        default: $('#settings_footer_button_color').val(),
        ...pickr_options
    });

    settings_footer_button_color_pickr.on('change', hsva => {
        $('#settings_footer_button_color').val(hsva.toHEXA().toString());
        $('#notification_preview .altumcode-whatsapp-chat-window-footer-button').css('color', hsva.toHEXA().toString());
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
