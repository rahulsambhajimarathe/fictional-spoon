<?php defined('ALTUMCODE') || die() ?>

<?php

$notification_settings_default_html = [];

/* Include the extra content of the notification */
$settings = require THEME_PATH . 'views/notification/settings/settings.' . mb_strtolower($data->notification->type) . '.method.php';
?>


<?php /* Default Triggers Tab */ ?>
<?php ob_start() ?>
<div class="form-group custom-control custom-switch">
    <input
            type="checkbox"
            class="custom-control-input"
            id="trigger_all_pages"
            name="trigger_all_pages"
        <?= $data->notification->settings->trigger_all_pages ? 'checked="checked"' : null ?>
    >
    <label class="custom-control-label clickable" for="trigger_all_pages"><?= l('notification.settings.trigger_all_pages') ?></label>

    <div>
        <small class="form-text text-muted"><?= l('notification.settings.trigger_all_pages_help') ?></small>
    </div>
</div>

<div id="triggers" class="container-disabled">
    <?php if(count($data->notification->settings->triggers)): ?>
        <?php foreach($data->notification->settings->triggers as $trigger): ?>
            <div class="form-row">
                <div class="form-group col-lg-4">
                    <select class="form-control" name="trigger_type[]" data-is-not-custom-select>
                        <option value="exact" data-placeholder="<?= l('notification.settings.trigger_type_exact_placeholder') ?>" <?= $trigger->type == 'exact' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_exact') ?></option>
                        <option value="not_exact" data-placeholder="<?= l('notification.settings.trigger_type_not_exact_placeholder') ?>" <?= $trigger->type == 'not_exact' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_not_exact') ?></option>
                        <option value="contains" data-placeholder="<?= l('notification.settings.trigger_type_contains_placeholder') ?>" <?= $trigger->type == 'contains' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_contains') ?></option>
                        <option value="not_contains" data-placeholder="<?= l('notification.settings.trigger_type_not_contains_placeholder') ?>" <?= $trigger->type == 'not_contains' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_not_contains') ?></option>
                        <option value="starts_with" data-placeholder="<?= l('notification.settings.trigger_type_starts_with_placeholder') ?>" <?= $trigger->type == 'starts_with' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_starts_with') ?></option>
                        <option value="not_starts_with" data-placeholder="<?= l('notification.settings.trigger_type_not_starts_with_placeholder') ?>" <?= $trigger->type == 'not_starts_with' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_not_starts_with') ?></option>
                        <option value="ends_with" data-placeholder="<?= l('notification.settings.trigger_type_ends_with_placeholder') ?>" <?= $trigger->type == 'ends_with' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_ends_with') ?></option>
                        <option value="not_ends_with" data-placeholder="<?= l('notification.settings.trigger_type_not_ends_with_placeholder') ?>" <?= $trigger->type == 'not_ends_with' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_not_ends_with') ?></option>
                        <option value="page_contains" data-placeholder="<?= l('notification.settings.trigger_type_page_contains_placeholder') ?>" <?= $trigger->type == 'page_contains' ? 'selected="selected"' : null ?>><?= l('notification.settings.trigger_type_page_contains') ?></option>
                    </select>
                </div>

                <div class="form-group col-lg-6">
                    <input type="text" name="trigger_value[]" class="form-control" value="<?= $trigger->value ?>">
                </div>

                <div class="form-group col-lg-2">
                    <button type="button" class="trigger-delete btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        <?php endforeach ?>
    <?php endif ?>
</div>

<div class="mb-4">
    <button type="button" id="trigger_add" class="btn btn-outline-success btn-sm"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('notification.settings.trigger_add') ?></button>
</div>

<div class="form-group">
    <label for="settings_display_trigger"><i class="fas fa-fw fa-bolt fa-sm text-muted mr-1"></i> <?= l('notification.settings.display_trigger') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_trigger == 'delay' ? 'active"' : null?>">
                <input type="radio" name="display_trigger" value="delay" class="custom-control-input" data-title="<?= l('notification.settings.display_trigger_delay_placeholder') ?>" <?= $data->notification->settings->display_trigger == 'delay' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-hourglass-start fa-sm mr-1"></i> <?= l('notification.settings.display_trigger_delay') ?>
            </label>
        </div>

        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_trigger == 'exit_intent' ? 'active"' : null?>">
                <input type="radio" name="display_trigger" value="exit_intent" class="custom-control-input" data-title="<?= l('notification.settings.display_trigger_exit_intent_placeholder') ?>" <?= $data->notification->settings->display_trigger == 'exit_intent' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-door-open fa-sm mr-1"></i> <?= l('notification.settings.display_trigger_exit_intent') ?>
            </label>
        </div>

        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_trigger == 'scroll' ? 'active"' : null?>">
                <input type="radio" name="display_trigger" value="scroll" class="custom-control-input" data-title="<?= l('notification.settings.display_trigger_scroll_placeholder') ?>" <?= $data->notification->settings->display_trigger == 'scroll' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-scroll fa-sm mr-1"></i> <?= l('notification.settings.display_trigger_scroll') ?>
            </label>
        </div>

        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_trigger == 'click' ? 'active"' : null?>">
                <input type="radio" name="display_trigger" value="click" class="custom-control-input" data-title="<?= l('notification.settings.display_trigger_click_placeholder') ?>" <?= $data->notification->settings->display_trigger == 'click' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-mouse fa-sm mr-1"></i> <?= l('notification.settings.display_trigger_click') ?>
            </label>
        </div>

        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_trigger == 'hover' ? 'active"' : null?>">
                <input type="radio" name="display_trigger" value="hover" class="custom-control-input" data-title="<?= l('notification.settings.display_trigger_hover_placeholder') ?>" <?= $data->notification->settings->display_trigger == 'hover' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-mouse-pointer fa-sm mr-1"></i> <?= l('notification.settings.display_trigger_hover') ?>
            </label>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="display_trigger_value"><?= l('notification.settings.display_trigger_' . $data->notification->settings->display_trigger . '_placeholder') ?></label>
    <input id="display_trigger_value" type="text" name="display_trigger_value" class="form-control" value="<?= $data->notification->settings->display_trigger_value ?>" />
</div>

<div class="form-group">
    <label for="settings_display_frequency"><i class="fas fa-fw fa-th fa-sm text-muted mr-1"></i> <?= l('notification.settings.display_frequency') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_frequency == 'all_time' ? 'active"' : null?>">
                <input type="radio" name="display_frequency" value="all_time" class="custom-control-input" <?= $data->notification->settings->display_frequency == 'all_time' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-history fa-sm mr-1"></i> <?= l('notification.settings.display_frequency_all_time') ?>
            </label>
        </div>

        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_frequency == 'once_per_session' ? 'active"' : null?>">
                <input type="radio" name="display_frequency" value="once_per_session" class="custom-control-input" <?= $data->notification->settings->display_frequency == 'once_per_session' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-stopwatch fa-sm mr-1"></i> <?= l('notification.settings.display_frequency_once_per_session') ?>
            </label>
        </div>

        <div class="col-12 col-lg-4">
            <label class="btn btn-gray-200 btn-block text-truncate <?= $data->notification->settings->display_frequency == 'once_per_browser' ? 'active"' : null?>">
                <input type="radio" name="display_frequency" value="once_per_browser" class="custom-control-input" <?= $data->notification->settings->display_frequency == 'once_per_browser' ? 'checked="checked"' : null?> />
                <i class="fas fa-fw fa-window-maximize fa-sm mr-1"></i> <?= l('notification.settings.display_frequency_once_per_browser') ?>
            </label>
        </div>
    </div>
</div>

<div class="form-group custom-control custom-switch">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_mobile"
            name="display_mobile"
        <?= $data->notification->settings->display_mobile ? 'checked="checked"' : null ?>
    >

    <label class="custom-control-label clickable" for="display_mobile"><i class="fas fa-fw fa-sm fa-mobile text-muted mr-1"></i> <?= l('notification.settings.display_mobile') ?></label>

    <div>
        <small class="form-text text-muted"><?= l('notification.settings.display_mobile_help') ?></small>
    </div>
</div>

<div class="form-group custom-control custom-switch">
    <input
            type="checkbox"
            class="custom-control-input"
            id="display_desktop"
            name="display_desktop"
        <?= $data->notification->settings->display_desktop ? 'checked="checked"' : null ?>
    >

    <label class="custom-control-label clickable" for="display_desktop"><i class="fas fa-fw fa-sm fa-desktop text-muted mr-1"></i> <?= l('notification.settings.display_desktop') ?></label>

    <div>
        <small class="form-text text-muted"><?= l('notification.settings.display_desktop_help') ?></small>
    </div>
</div>
<?php $notification_settings_default_html['triggers'] = ob_get_clean() ?>


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
<?php $notification_settings_default_html['display'] = ob_get_clean() ?>


<?php /* Standard Customize Tab */ ?>
<?php ob_start() ?>
<div class="row">
    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="settings_on_animation"><i class="fas fa-fw fa-sign-in-alt fa-sm text-muted mr-1"></i> <?= l('notification.settings.on_animation') ?></label>
            <select id="settings_on_animation" class="custom-select" name="on_animation">
                <option value="fadeIn" <?= $data->notification->settings->on_animation == 'fadeIn' ? 'selected="selected"' : null ?>><?= l('notification.settings.on_animation_fadeIn') ?></option>
                <option value="slideInUp" <?= $data->notification->settings->on_animation == 'slideInUp' ? 'selected="selected"' : null ?>><?= l('notification.settings.on_animation_slideInUp') ?></option>
                <option value="slideInDown" <?= $data->notification->settings->on_animation == 'slideInDown' ? 'selected="selected"' : null ?>><?= l('notification.settings.on_animation_slideInDown') ?></option>
                <option value="zoomIn" <?= $data->notification->settings->on_animation == 'zoomIn' ? 'selected="selected"' : null ?>><?= l('notification.settings.on_animation_zoomIn') ?></option>
                <option value="bounceIn" <?= $data->notification->settings->on_animation == 'bounceIn' ? 'selected="selected"' : null ?>><?= l('notification.settings.on_animation_bounceIn') ?></option>
            </select>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="form-group">
            <label for="settings_off_animation"><i class="fas fa-fw fa-sign-out-alt fa-sm text-muted mr-1"></i> <?= l('notification.settings.off_animation') ?></label>
            <select id="settings_off_animation" class="custom-select" name="off_animation">
                <option value="fadeOut" <?= $data->notification->settings->off_animation == 'fadeOut' ? 'selected="selected"' : null ?>><?= l('notification.settings.off_animation_fadeOut') ?></option>
                <option value="slideOutUp" <?= $data->notification->settings->off_animation == 'slideOutUp' ? 'selected="selected"' : null ?>><?= l('notification.settings.off_animation_slideOutUp') ?></option>
                <option value="slideOutDown" <?= $data->notification->settings->off_animation == 'slideOutDown' ? 'selected="selected"' : null ?>><?= l('notification.settings.off_animation_slideOutDown') ?></option>
                <option value="zoomOut" <?= $data->notification->settings->off_animation == 'zoomOut' ? 'selected="selected"' : null ?>><?= l('notification.settings.off_animation_zoomOut') ?></option>
                <option value="bounceOut" <?= $data->notification->settings->off_animation == 'bounceOut' ? 'selected="selected"' : null ?>><?= l('notification.settings.off_animation_bounceOut') ?></option>
            </select>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="settings_animation"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('notification.settings.animation') ?></label>
    <select id="settings_animation" class="custom-select" name="animation">
        <option value="" <?= $data->notification->settings->animation == '' ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
        <option value="heartbeat" <?= $data->notification->settings->animation == 'heartbeat' ? 'selected="selected"' : null ?>><?= l('notification.settings.animation_heartbeat') ?></option>
        <option value="bounce" <?= $data->notification->settings->animation == 'bounce' ? 'selected="selected"' : null ?>><?= l('notification.settings.animation_bounce') ?></option>
        <option value="flash" <?= $data->notification->settings->animation == 'flash' ? 'selected="selected"' : null ?>><?= l('notification.settings.animation_flash') ?></option>
        <option value="pulse" <?= $data->notification->settings->animation == 'pulse' ? 'selected="selected"' : null ?>><?= l('notification.settings.animation_pulse') ?></option>
    </select>
</div>

<div class="form-group">
    <label for="settings_animation_interval"><i class="fas fa-fw fa-history fa-sm text-muted mr-1"></i> <?= l('notification.settings.animation_interval') ?></label>
    <div class="input-group">
        <input type="number" min="3" id="settings_animation_interval" name="animation_interval" class="form-control" value="<?= $data->notification->settings->animation_interval ?? 3 ?>" />
        <div class="input-group-append">
            <span class="input-group-text"><?= l('global.date.seconds') ?></span>
        </div>
    </div>
</div>

<div class="form-group">
    <label for="settings_font"><i class="fas fa-fw fa-pen-nib fa-sm text-muted mr-1"></i> <?= l('notification.settings.font') ?></label>
    <div class="row btn-group-toggle" data-toggle="buttons">
        <div class="col-6 col-lg-4 h-100">
            <label class="btn btn-gray-200 btn-block text-truncate text-truncate <?= ($data->notification->settings->font ?? 'inherit') == 'inherit' ? 'active"' : null?>">
                <input type="radio" name="font" value="inherit" class="custom-control-input" <?= ($data->notification->settings->font ?? 'inherit') == 'inherit' ? 'checked="checked"' : null?> required="required" />
                <?= l('notification.settings.font_inherit') ?>
            </label>
        </div>

        <?php foreach(['Arial', 'Verdana', 'Helvetica', 'Tahoma', 'Trebuchet MS', 'Times New Roman', 'Georgia', 'Garamond', 'Courier New', 'Monaco', 'Comic Sans MS', 'Courier', 'Lucida Console', 'Impact'] as $font): ?>
            <div class="col-6 col-lg-4 h-100">
                <label class="btn btn-gray-200 btn-block text-truncate text-truncate <?= ($data->notification->settings->font ?? 'inherit') == $font ? 'active"' : null?>" style="font-family: <?= $font ?> !important;">
                    <input type="radio" name="font" value="<?= $font ?>" class="custom-control-input" <?= ($data->notification->settings->font ?? 'inherit') == $font ? 'checked="checked"' : null?> required="required" />
                    <?= $font ?>
                </label>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php $notification_settings_default_html['customize'] = ob_get_clean() ?>

<div class="mt-5 mb-3 row">
    <div class="col-12 mb-5 col-md-7 mb-md-0">
        <ul class="nav nav-pills nav-custom2 flex-column flex-lg-row justify-content-between mb-5" id="pills-tab" role="tablist">

            <?php if(in_array('basic', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link active" id="tab_basic_link" data-toggle="pill" href="#tab_basic" role="tab" aria-controls="tab_basic" aria-selected="true">
                        <i class="fas fa-fw fa-sm fa-cog mr-1"></i> <?= l('notification.settings.tab_basic') ?>
                    </a>
                </li>
            <?php endif ?>

            <?php if(in_array('triggers', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_triggers_link" data-toggle="pill" href="#tab_triggers" role="tab" aria-controls="tab_triggers" aria-selected="false">
                        <i class="fas fa-fw fa-sm fa-angle-up mr-1"></i> <?= l('notification.settings.tab_triggers') ?>
                    </a>
                </li>
            <?php endif ?>

            <?php if(in_array('display', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_display_link" data-toggle="pill" href="#tab_display" role="tab" aria-controls="tab_display" aria-selected="false">
                        <i class="fas fa-fw fa-sm fa-sliders-h mr-1"></i> <?= l('notification.settings.tab_display') ?>
                    </a>
                </li>
            <?php endif ?>

            <?php if(in_array('customize', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_customize_link" data-toggle="pill" href="#tab_customize" role="tab" aria-controls="tab_customize" aria-selected="false">
                        <i class="fas fa-fw fa-sm fa-paint-brush mr-1"></i> <?= l('notification.settings.tab_customize') ?>
                    </a>
                </li>
            <?php endif ?>


            <?php if(in_array('data', $data->notification->settings->enabled_settings_tabs)): ?>
                <li class="nav-item">
                    <a class="nav-link" id="tab_data_link" data-toggle="pill" href="#tab_data" role="tab" aria-controls="tab_data" aria-selected="false">
                        <i class="fas fa-fw fa-sm fa-database mr-1"></i> <?= l('notification.settings.tab_data') ?>
                    </a>
                </li>
            <?php endif ?>
        </ul>

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="tab-content">
                <div class="tab-pane fade show active" id="tab_basic" role="tabpanel" aria-labelledby="tab_basic_link">
                    <?= $settings->html['basic'] ?>
                </div>

                <div class="tab-pane fade" id="tab_triggers" role="tabpanel" aria-labelledby="tab_triggers_link">
                    <?= $notification_settings_default_html['triggers'] ?>

                    <?= isset($settings->html['triggers']) ? $settings->html['triggers'] : null ?>
                </div>

                <div class="tab-pane fade" id="tab_display" role="tabpanel" aria-labelledby="tab_display_link">
                    <?= isset($settings->html['display']) ? $settings->html['display'] : $notification_settings_default_html['display'] ?>
                </div>

                <div class="tab-pane fade" id="tab_customize" role="tabpanel" aria-labelledby="tab_customize_link">
                    <?= $settings->html['customize'] ?>

                    <?= $notification_settings_default_html['customize'] ?>
                </div>

                <div class="tab-pane fade" id="tab_data" role="tabpanel" aria-labelledby="tab_data_link">
                    <?= $settings->html['data'] ?? null ?>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary"><?= l('global.update') ?></button>
            </div>

        </form>
    </div>

    <div class="col-12 d-flex justify-content-center col-md-4 offset-md-1">
        <div id="notification_preview" class="mt-3 notification-preview notification-preview-<?= mb_strtolower($data->notification->type) ?>">
            <?= \Altum\Notification::get($data->notification->type, $data->notification, $this->user)->html ?>
        </div>
    </div>
</div>


<div style="display:none" id="trigger_rule_sample">
    <div class="form-row">
        <div class="form-group col-lg-4">
            <select class="form-control" name="trigger_type[]" data-is-not-custom-select>
                <option value="exact" data-placeholder="<?= l('notification.settings.trigger_type_exact_placeholder') ?>"><?= l('notification.settings.trigger_type_exact') ?></option>
                <option value="not_exact" data-placeholder="<?= l('notification.settings.trigger_type_not_exact_placeholder') ?>"><?= l('notification.settings.trigger_type_not_exact') ?></option>
                <option value="contains" data-placeholder="<?= l('notification.settings.trigger_type_contains_placeholder') ?>"><?= l('notification.settings.trigger_type_contains') ?></option>
                <option value="not_contains" data-placeholder="<?= l('notification.settings.trigger_type_not_contains_placeholder') ?>"><?= l('notification.settings.trigger_type_not_contains') ?></option>
                <option value="starts_with" data-placeholder="<?= l('notification.settings.trigger_type_starts_with_placeholder') ?>"><?= l('notification.settings.trigger_type_starts_with') ?></option>
                <option value="not_starts_with" data-placeholder="<?= l('notification.settings.trigger_type_not_starts_with_placeholder') ?>"><?= l('notification.settings.trigger_type_not_starts_with') ?></option>
                <option value="ends_with" data-placeholder="<?= l('notification.settings.trigger_type_ends_with_placeholder') ?>"><?= l('notification.settings.trigger_type_ends_with') ?></option>
                <option value="not_ends_with" data-placeholder="<?= l('notification.settings.trigger_type_not_ends_with_placeholder') ?>"><?= l('notification.settings.trigger_type_not_ends_with') ?></option>
                <option value="page_contains" data-placeholder="<?= l('notification.settings.trigger_type_page_contains_placeholder') ?>"><?= l('notification.settings.trigger_type_page_contains') ?></option>
            </select>
        </div>

        <div class="form-group col-lg-6">
            <input type="text" name="trigger_value[]" class="form-control" value="">
        </div>

        <div class="form-group col-lg-2">
            <button type="button" class="trigger-delete btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</div>

<div style="display:none" id="data_trigger_auto_rule_sample">
    <div class="form-row">
        <div class="form-group col-lg-4">
            <select class="form-control" name="data_trigger_auto_type[]" data-is-not-custom-select>
                <option value="exact" data-placeholder="<?= l('notification.settings.trigger_type_exact_placeholder') ?>"><?= l('notification.settings.trigger_type_exact') ?></option>
                <option value="contains" data-placeholder="<?= l('notification.settings.trigger_type_contains_placeholder') ?>"><?= l('notification.settings.trigger_type_contains') ?></option>
                <option value="starts_with" data-placeholder="<?= l('notification.settings.trigger_type_starts_with_placeholder') ?>"><?= l('notification.settings.trigger_type_starts_with') ?></option>
                <option value="ends_with" data-placeholder="<?= l('notification.settings.trigger_type_ends_with_placeholder') ?>"><?= l('notification.settings.trigger_type_ends_with') ?></option>
                <option value="page_contains" data-placeholder="<?= l('notification.settings.trigger_type_page_contains_placeholder') ?>"><?= l('notification.settings.trigger_type_page_contains') ?></option>
            </select>
        </div>

        <div class="form-group col-lg-6">
            <input type="text" name="data_trigger_auto_value[]" class="form-control" placeholder="<?= l('notification.settings.trigger_type_exact_placeholder') ?>" aria-label="<?= l('notification.settings.trigger_type_exact_placeholder') ?>">
        </div>

        <div class="form-group col-lg-2">
            <button type="button" class="data-trigger-auto-delete btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/pickr.min.js' ?>"></script>

<script>
    /* Initiate the color picker */
    let pickr_options = {
        comparison: false,

        components: {
            preview: true,
            opacity: false,
            hue: true,
            comparison: false,
            interaction: {
                hex: true,
                rgba: false,
                hsla: false,
                hsva: false,
                cmyk: false,
                input: true,
                clear: false,
                save: false,
            }
        }
    };


    /* Display Trigger Handler */
    let display_trigger_status_handler = () => {
        let display_trigger = document.querySelector('input[name="display_trigger"]:checked');

        switch(display_trigger.value) {
            case 'delay':
            case 'scroll':
            case 'click':
            case 'hover':

                /* Make sure to show the input field */
                $('input[name="display_trigger_value"]').parent().show();

                /* Add the proper placeholder */
                $('label[for="display_trigger_value"]').text(display_trigger.getAttribute('data-title'));

                break;

            case 'exit_intent':

                /* Hide the display trigger value for this option */
                $('input[name="display_trigger_value"]').parent().hide();

                break;
        }
    };

    /* Trigger it for the first initial load */
    display_trigger_status_handler();

    /* Trigger on select change */
    $('input[name="display_trigger"]').on('change', () => {
        display_trigger_status_handler();

        /* Clear the input from previous values */
        $('input[name="display_trigger_value"]').val('');
    });



    /* Triggers Handler */
    let triggers_status_handler = () => {

        if($('#trigger_all_pages').is(':checked')) {

            /* Disable the container visually */
            $('#triggers').addClass('container-disabled');

            /* Remove the new trigger add button */
            $('#trigger_add').hide();

        } else {

            /* Remove disabled container if depending on the status of the trigger checkbox */
            $('#triggers').removeClass('container-disabled');

            /* Bring back the new trigger add button */
            $('#trigger_add').show();

        }

        $('select[name="trigger_type[]"]').off().on('change', event => {

            let input = $(event.currentTarget).closest('.form-row').find('input');
            let placeholder = $(event.currentTarget).find(':checked').data('placeholder');

            /* Add the proper placeholder */
            input.attr('placeholder', placeholder);

        }).trigger('change');

    };

    /* Trigger on status change live of the checkbox */
    $('#trigger_all_pages').on('change', triggers_status_handler);

    /* Delete trigger handler */
    let triggers_delete_handler = () => {

        /* Delete button handler */
        $('.trigger-delete').off().on('click', event => {

            let trigger = $(event.currentTarget).closest('.form-row');

            trigger.remove();

            triggers_count_handler();
        });

    };

    let triggers_add_sample = () => {
        let trigger_rule_sample = $('#trigger_rule_sample').html();

        $('#triggers').append(trigger_rule_sample);
    };

    let triggers_count_handler = () => {
        let total_triggers = $('#triggers > .form-row').length;

        /* Make sure we at least have two input groups to show the delete button */
        if(total_triggers > 1) {
            $('#triggers .trigger-delete').removeAttr('disabled');

            /* Make sure to set a limit to these triggers */
            if(total_triggers > 10) {
                $('#trigger_add').hide();
            } else {
                $('#trigger_add').show();
            }

        } else {

            if(total_triggers == 0) {
                triggers_add_sample();
            }

            $('#triggers .trigger-delete').attr('disabled', 'disabled');
        }
    };

    /* Add new trigger rule handler */
    $('#trigger_add').on('click', () => {
        triggers_add_sample();
        triggers_delete_handler();
        triggers_count_handler();
        triggers_status_handler();
    });

    /* Trigger functions for the first initial load */
    triggers_status_handler();
    triggers_delete_handler();
    triggers_count_handler();

    /* Background Pattern Handler */
    $('input[name="background_pattern"]').on('change paste keyup', event => {
        let value = event.currentTarget.getAttribute('data-value');
        $('#notification_preview .altumcode-wrapper').css('background-image', value ? `url(${value})` : '');
    });

    /* Close Button Color Handler */
    let settings_close_button_color_pickr = Pickr.create({
        el: '#settings_close_button_color_pickr',
        default: $('#settings_close_button_color').val(),
        ...pickr_options
    });

    settings_close_button_color_pickr.on('change', hsva => {
        $('#settings_close_button_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-close').css('color', hsva.toHEXA().toString());
    });

    /* Border radius preview */
    $('input[name="border_radius"]').on('change', event => {
        let border_radius = event.currentTarget.value;
        let notification_preview_wrapper = $('#notification_preview .altumcode-wrapper');
        notification_preview_wrapper.removeClass('altumcode-wrapper-round altumcode-wrapper-straight altumcode-wrapper-rounded').addClass(`altumcode-wrapper-${border_radius}`);
    });

    /* Font preview */
    $('input[name="font"]').on('change', event => {
        let font = event.currentTarget.value;
        document.querySelector('#notification_preview .altumcode-wrapper').style.fontFamily = font;
    });

    /* Border Color Handler */
    let settings_border_color_pickr = Pickr.create({
        el: '#settings_border_color_pickr',
        default: $('#settings_border_color').val(),
        ...pickr_options
    });

    settings_border_color_pickr.on('change', hsva => {
        $('#settings_border_color').val(hsva.toHEXA().toString());

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-wrapper').css('border-color', hsva.toHEXA().toString());
    });

    /* Border Width Handler */
    $('#settings_border_width').on('change', event => {

        /* Notification Preview Handler */
        $('#notification_preview .altumcode-wrapper').css('border-width', $(event.currentTarget).val());

    });

    /* Shadow handler */
    $('#settings_shadow').on('change', event => {

        /* Notification Preview Handler */
        if($(event.currentTarget).is(':checked')) {
            $('#notification_preview .altumcode-wrapper').addClass('altumcode-wrapper-shadow');
        } else {
            $('#notification_preview .altumcode-wrapper').removeClass('altumcode-wrapper-shadow');
        }

    });

    /* Failsafe on _color fields being empty */
    $('input[name$="_color"]').on('change paste keyup', event => {
        if($(event.currentTarget).val().trim() == '') {
            $(event.currentTarget).val('#000');
        }
    });
</script>

<?= $settings->javascript ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

