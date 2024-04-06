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
    <label for="settings_last_activity"><?= sprintf(l('notification.settings.last_activity'), l('global.date.hours')) ?></label>
    <input type="number" id="settings_last_activity" name="last_activity" class="form-control" value="<?= $data->notification->settings->last_activity ?>" required="required" />
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


<?php /* Triggers Tab Extra */ ?>
<?php ob_start() ?>

<div class="form-group">
    <label for="settings_display_minimum_activity"><?= l('notification.settings.display_minimum_activity') ?></label>
    <input type="number" min="0" id="settings_display_minimum_activity" name="display_minimum_activity" class="form-control" value="<?= $data->notification->settings->display_minimum_activity ?>" />
    <small class="form-text text-muted"><?= l('notification.settings.display_minimum_activity_help') ?></small>
</div>

<?php $html['triggers'] = ob_get_clean() ?>


<?php /* Customize Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_number_color"><?= l('notification.settings.number_color') ?></label>
    <input type="hidden" id="settings_number_color" name="number_color" class="form-control" value="<?= $data->notification->settings->number_color ?>" />
    <div id="settings_number_color_pickr"></div>
</div>

<div class="form-group">
    <label for="settings_number_background_color"><?= l('notification.settings.number_background_color') ?></label>
    <input type="hidden" id="settings_number_background_color" name="number_background_color" class="form-control" value="<?= $data->notification->settings->number_background_color ?>" />
    <div id="settings_number_background_color_pickr"></div>
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



<?php /* Data Tab */ ?>
<?php ob_start() ?>
<div class="form-group">
    <label for="settings_data_trigger_input_webhook"><?= l('notification.settings.data_trigger_webhook') ?></label>
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><?= l('notification.settings.data_trigger_type_webhook') ?></span>
        </div>

        <input type="text" id="settings_data_trigger_input_webhook" name="data_trigger_input_webhook" class="form-control" value="<?= url('pixel-webhook/' . $data->notification->notification_key) ?>" placeholder="<?= l('notification.settings.data_trigger_input_webhook') ?>" aria-label="<?= l('notification.settings.data_trigger_input_webhook') ?>" readonly="readonly">
    </div>

    <small class="form-text text-muted"><?= l('notification.settings.data_trigger_webhook_help') ?></small>
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input
                type="checkbox"
                class="custom-control-input"
                id="data_trigger_auto"
                name="data_trigger_auto"
            <?= $data->notification->settings->data_trigger_auto ? 'checked="checked"' : null ?>
        >
        <label class="custom-control-label clickable" for="data_trigger_auto"><?= l('notification.settings.data_trigger_auto') ?></label>

        <div><small class="form-text text-muted"><?= l('notification.settings.data_trigger_auto_help') ?></small></div>
    </div>
</div>

<div id="data_triggers_auto" class="container-disabled">
    <?php if(count($data->notification->settings->data_triggers_auto)): ?>
        <?php foreach($data->notification->settings->data_triggers_auto as $trigger): ?>
            <div class="form-row">
                <div class="form-group col-lg-4">
                    <select class="form-control" name="data_trigger_auto_type[]" data-is-not-custom-select>
                        <option value="exact" data-placeholder="<?= l('notification.settings.trigger_type_exact_placeholder') ?>" <?= $trigger->type == 'exact' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_exact') ?></option>
                        <option value="contains" data-placeholder="<?= l('notification.settings.trigger_type_contains_placeholder') ?>" <?= $trigger->type == 'contains' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_contains') ?></option>
                        <option value="starts_with" data-placeholder="<?= l('notification.settings.trigger_type_starts_with_placeholder') ?>" <?= $trigger->type == 'starts_with' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_starts_with') ?></option>
                        <option value="ends_with" data-placeholder="<?= l('notification.settings.trigger_type_ends_with_placeholder') ?>" <?= $trigger->type == 'ends_with' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_ends_with') ?></option>
                        <option value="page_contains" data-placeholder="<?= l('notification.settings.trigger_type_page_contains_placeholder') ?>" <?= $trigger->type == 'page_contains' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_page_contains') ?></option>
                    </select>
                </div>

                <div class="form-group col-lg-6">
                    <input type="text" name="data_trigger_auto_value[]" class="form-control" value="<?= $trigger->value ?>" placeholder="<?= l('notification.settings.trigger_type_exact_placeholder') ?>" aria-label="<?= l('notification.settings.trigger_type_exact_placeholder') ?>">
                </div>

                <div class="form-group col-lg-2">
                    <button type="button" class="data-trigger-auto-delete btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>

<div>
    <button type="button" id="data_trigger_auto_add" class="btn btn-outline-success btn-sm"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('notification.settings.data_trigger_auto_add') ?></button>
</div>
<?php $html['data'] = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Notification Preview Handlers */
    $('#settings_title').on('change paste keyup', event => {
        $('#notification_preview .altumcode-conversions-counter-title').text($(event.currentTarget).val());
    });

    $('#settings_description').on('change paste keyup', event => {
        $('#notification_preview .altumcode-conversions-counter-description').text($(event.currentTarget).val());
    });

    $('#settings_image').on('change paste keyup', event => {
        $('#notification_preview .altumcode-conversions-counter-image').attr('src', $(event.currentTarget).val());
    });

    /* Number Color Handler */
    let settings_number_color_pickr = Pickr.create({
        el: '#settings_number_color_pickr',
        default: $('#settings_number_color').val(),
        ...pickr_options
    });

    settings_number_color_pickr.on('change', hsva => {
        $('#settings_number_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-conversions-counter-number').css('color', hsva.toHEXA().toString());
    });

    /* Number Background Color Handler */
    let settings_number_background_color_pickr = Pickr.create({
        el: '#settings_number_background_color_pickr',
        default: $('#settings_number_background_color').val(),
        ...pickr_options
    });

    settings_number_background_color_pickr.on('change', hsva => {
        $('#settings_number_background_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-conversions-counter-number').css('background', hsva.toHEXA().toString());
    });


    /* Description Color Handler */
    let settings_title_color_pickr = Pickr.create({
        el: '#settings_title_color_pickr',
        default: $('#settings_title_color').val(),
        ...pickr_options
    });

    settings_title_color_pickr.on('change', hsva => {
        $('#settings_title_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-conversions-counter-title').css('color', hsva.toHEXA().toString());
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

    /* Data Triggers Auto Handler */
    let data_trigger_auto_status_handler = () => {

        if(!$('#data_trigger_auto:checked').length) {

            /* Disable the container visually */
            $('#data_triggers_auto').addClass('container-disabled');

            /* Remove the new trigger add button */
            $('#data_trigger_auto_add').hide();

        } else {

            /* Remove disabled container if depending on the status of the trigger checkbox */
            $('#data_triggers_auto').removeClass('container-disabled');

            /* Bring back the new trigger add button */
            $('#data_trigger_auto_add').show();

        }

        $('select[name="data_trigger_auto_type[]"]').off().on('change', event => {

            let input = $(event.currentTarget).closest('.form-row').find('input');
            let placeholder = $(event.currentTarget).find(':checked').data('placeholder');

            /* Add the proper placeholder */
            input.attr('placeholder', placeholder);

        }).trigger('change');

    };

    /* Trigger on status change live of the checkbox */
    $('#data_trigger_auto').on('change', data_trigger_auto_status_handler);

    /* Delete trigger handler */
    let data_triggers_auto_delete_handler = () => {

        /* Delete button handler */
        $('.data-trigger-auto-delete').off().on('click', event => {

            let element = $(event.currentTarget).closest('.form-row');

            element.remove();

            data_triggers_auto_count_handler();

        });

    };

    let data_trigger_auto_add_sample = () => {
        let rule_sample = $('#data_trigger_auto_rule_sample').html();

        $('#data_triggers_auto').append(rule_sample);
    };

    let data_triggers_auto_count_handler = () => {

        let total_triggers = $('#data_triggers_auto > .form-row').length;

        /* Make sure we at least have two input groups to show the delete button */
        if(total_triggers > 1) {
            $('#data_triggers_auto .data-trigger-auto-delete').removeAttr('disabled');

            /* Make sure to set a limit to these triggers */
            if(total_triggers > 10) {
                $('#data_trigger_auto_add').hide();
            } else {
                $('#data_trigger_auto_add').show();
            }

        } else {

            if(total_triggers == 0) {
                data_trigger_auto_add_sample();
            }

            $('#data_triggers_auto .data-trigger-auto-delete').attr('disabled', 'disabled');
        }
    };

    /* Add new trigger rule handler */
    $('#data_trigger_auto_add').on('click', () => {
        data_trigger_auto_add_sample();
        data_triggers_auto_delete_handler();
        data_triggers_auto_count_handler();
        data_trigger_auto_status_handler();
    });

    /* Trigger functions for the first initial load */
    data_trigger_auto_status_handler();
    data_triggers_auto_delete_handler();
    data_triggers_auto_count_handler();
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
