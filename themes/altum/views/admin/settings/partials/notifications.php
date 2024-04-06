<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="branding"><?= l('admin_settings.notifications.branding') ?></label>
        <textarea id="branding" name="branding" class="form-control"><?= settings()->notifications->branding ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.notifications.branding_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="analytics_is_enabled" name="analytics_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->notifications->analytics_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="analytics_is_enabled"><?= l('admin_settings.notifications.analytics_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.notifications.analytics_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="pixel_cache"><?= l('admin_settings.notifications.pixel_cache') ?></label>
        <input id="pixel_cache" type="number" min="0" name="pixel_cache" class="form-control" value="<?= settings()->notifications->pixel_cache ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.notifications.pixel_cache_help') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
