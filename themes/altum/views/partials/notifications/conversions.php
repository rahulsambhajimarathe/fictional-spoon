<?php defined('ALTUMCODE') || die() ?>


<?php ob_start() ?>
<div role="dialog" class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> <?= ($notification->settings->direction ?? 'ltr') == 'rtl' ? 'altumcode-rtl' : null ?> altumcode-conversions-wrapper" style='font-family: <?= $notification->settings->font ?? 'inherit' ?>!important;background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <div class="altumcode-conversions-content">
        <?php $notification->image = isset($notification->image) && $notification->image ? $notification->image : $notification->settings->image; ?>
        <?php if(!empty($notification->image)): ?>
            <img src="<?= $notification->image ?>" class="altumcode-conversions-image" alt="<?= $notification->image_alt ?? null ?>" loading="lazy" />
        <?php endif ?>

        <div style="width: 100%!important;">
            <div class="altumcode-conversions-header">
                <p class="altumcode-conversions-title" style="color: <?= $notification->settings->title_color ?>"><?= isset($notification->title) && $notification->title ? html_entity_decode($notification->title) : $notification->settings->title ?></p>

                <button class="altumcode-close" style="color: <?= $notification->settings->close_button_color ?>;">×</button>
            </div>

            <p class="altumcode-conversions-description" style="color: <?= $notification->settings->description_color ?>"><?= isset($notification->description) && $notification->description ? html_entity_decode($notification->description) : $notification->settings->description ?></p>

            <div class="altumcode-conversions-time" style="color: <?= $notification->settings->date_color ?>">
                <?php if(isset($notification->last_action_date) && $notification->last_action_date): ?>
                    <?= \Altum\Date::get_timeago($notification->last_action_date) ?>
                <?php else: ?>
                    <?= l('notification.conversions.time_ago_default') ?>
                <?php endif ?>
            </div>

            <?php if($notification->settings->display_branding): ?>
                <?php if(isset($notification->branding, $notification->branding->name, $notification->branding->url) && !empty($notification->branding->name) && !empty($notification->branding->url)): ?>
                    <a href="<?= $notification->branding->url ?>" class="altumcode-site"><?= $notification->branding->name ?></a>
                <?php else: ?>
                    <a href="<?= url() ?>" class="altumcode-site"><?= settings()->notifications->branding ?></a>
                <?php endif ?>
            <?php endif ?>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
new AltumCodeManager({
    should_show: true,
    content: <?= json_encode($html) ?>,
    display_mobile: <?= json_encode($notification->settings->display_mobile) ?>,
    display_desktop: <?= json_encode($notification->settings->display_desktop) ?>,
    display_trigger: <?= json_encode($notification->settings->display_trigger) ?>,
    display_trigger_value: <?= json_encode($notification->settings->display_trigger_value) ?>,
    duration: <?= $notification->settings->display_duration === -1 ? -1 : $notification->settings->display_duration * 1000 ?>,
    url: <?= json_encode(isset($notification->url) && $notification->url ? $notification->url : $notification->settings->url) ?>,
    url_new_tab: <?= json_encode($notification->settings->url_new_tab) ?>,
    close: <?= json_encode($notification->settings->display_close_button) ?>,
    display_frequency: <?= json_encode($notification->settings->display_frequency) ?>,
    position: <?= json_encode($notification->settings->display_position) ?>,
    trigger_all_pages: <?= json_encode($notification->settings->trigger_all_pages) ?>,
    triggers: <?= json_encode($notification->settings->triggers) ?>,
    data_trigger_auto: <?= json_encode($notification->settings->data_trigger_auto) ?>,
    data_triggers_auto: <?= json_encode($notification->settings->data_triggers_auto) ?>,
    on_animation: <?= json_encode($notification->settings->on_animation) ?>,
    off_animation: <?= json_encode($notification->settings->off_animation) ?>,
    animation: <?= json_encode($notification->settings->animation) ?>,
    animation_interval: <?= (int) $notification->settings->animation_interval ?>,

    notification_id: <?= $notification->notification_id ?>
}).initiate();
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
