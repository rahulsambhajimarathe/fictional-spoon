<?php defined('ALTUMCODE') || die() ?>

<?php foreach((array) $data->conversion->data as $key => $value): ?>
    <div class="col-4 font-weight-bold"><?= $key ?></div>
    <div class="col-8"><?= $value ?></div>
<?php endforeach ?>

<?php if(!empty($data->conversion->url)): ?>
    <div class="col-4 font-weight-bold"><?= l('notification.data.url') ?></div>
    <div class="col-8"><?= $data->conversion->url ?></div>
<?php endif ?>

<?php if(!empty($data->conversion->ip)): ?>
    <div class="col-4 font-weight-bold"><?= l('global.ip') ?></div>
    <div class="col-8"><?= $data->conversion->ip ?></div>
<?php endif ?>

<?php if($data->conversion->location && isset($data->conversion->location->country)): ?>
    <div class="col-4 font-weight-bold">
        <?= l('global.country') ?>
        <span data-toggle="tooltip" title="<?= sprintf(l('notification.data.variable'), 'country') ?>"><i class="fas fa-fw fa-sm fa-circle-question ml-1 text-muted"></i></span>
    </div>
    <div class="col-8">
        <?php if(isset($data->conversion->location->country_code)): ?>
            <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->conversion->location->country_code) . '.svg' ?>" class="img-fluid icon-favicon mr-1" alt="<?= l('global.country') ?>" />
        <?php endif ?>
        <span class="align-middle"><?= $data->conversion->location->country ?></span>
    </div>
<?php endif ?>

<?php if($data->conversion->location && isset($data->conversion->location->country_code)): ?>
    <div class="col-4 font-weight-bold">
        <?= l('notification.data.country_code') ?>
        <span data-toggle="tooltip" title="<?= sprintf(l('notification.data.variable'), 'country_code') ?>"><i class="fas fa-fw fa-sm fa-circle-question ml-1 text-muted"></i></span>
    </div>
    <div class="col-8"><span class="align-middle"><?= $data->conversion->location->country_code ?></span></div>
<?php endif ?>

<?php if($data->conversion->location && isset($data->conversion->location->city)): ?>
    <div class="col-4 font-weight-bold">
        <?= l('global.city') ?>
        <span data-toggle="tooltip" title="<?= sprintf(l('notification.data.variable'), 'city') ?>"><i class="fas fa-fw fa-sm fa-circle-question ml-1 text-muted"></i></span>
    </div>
    <div class="col-8"><span class="align-middle"><?= $data->conversion->location->city ?></span></div>
<?php endif ?>
