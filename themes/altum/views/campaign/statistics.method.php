<?php defined('ALTUMCODE') || die() ?>

<?php if(!settings()->notifications->analytics_is_enabled): ?>
    <div class="alert alert-warning" role="alert">
        <?= l('campaign.statistics.disabled') ?>
    </div>
<?php endif ?>

<div class="mt-5 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h3 text-truncate mb-0"><?= l('campaign.statistics.header') ?></h2>

        <div class="d-flex align-items-center col-auto p-0">
            <div data-toggle="tooltip" title="<?= l('global.reset') ?>">
                <button
                        type="button"
                        class="btn btn-link text-secondary"
                        data-toggle="modal"
                        data-target="#campaign_statistics_reset_modal"
                        aria-label="<?= l('global.reset') ?>"
                        data-campaign-id="<?= $data->campaign->campaign_id ?>"
                        data-start-date="<?= $data->datetime['start_date'] ?>"
                        data-end-date="<?= $data->datetime['end_date'] ?>"
                >
                    <i class="fas fa-fw fa-sm fa-redo"></i>
                </button>
            </div>

            <div class="">
                <button
                        id="daterangepicker"
                        type="button"
                        class="btn btn-sm btn-light"
                        data-min-date="<?= \Altum\Date::get($data->campaign->datetime, 4) ?>"
                        data-max-date="<?= \Altum\Date::get('', 4) ?>"
                >
                    <i class="fas fa-fw fa-calendar mr-lg-1"></i>
                    <span class="d-none d-lg-inline-block">
                        <?php if($data->datetime['start_date'] == $data->datetime['end_date']): ?>
                            <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) ?>
                        <?php else: ?>
                            <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->datetime['end_date'], 2, \Altum\Date::$default_timezone) ?>
                        <?php endif ?>
                    </span>
                    <i class="fas fa-fw fa-caret-down d-none d-lg-inline-block ml-lg-1"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<?php if(!count($data->logs)): ?>

    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
        'filters_get' => $data->filters->get ?? [],
        'name' => 'campaign.statistics',
        'has_secondary_text' => true,
        'has_wrapper' => false,
    ]); ?>

<?php else: ?>

    <div class="row justify-content-between mb-5">
        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fas fa-fw fa-eye fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['impression']) ?></div>
                        <small class="form-text text-muted"><?= l('campaign.statistics.impressions_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fas fa-fw fa-mouse-pointer fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['hover']) ?></div>
                        <small class="form-text text-muted"><?= l('campaign.statistics.hovers_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fas fa-fw fa-mouse fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['click']) ?></div>
                        <small class="form-text text-muted"><?= l('campaign.statistics.clicks_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fas fa-fw fa-bolt fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['ctr']) . '%' ?></div>
                        <small class="text-muted"><?= l('notification.statistics.ctr_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body d-flex">

                    <div>
                        <div class="card bg-gray-200 text-gray-700 mr-3">
                            <div class="p-3 d-flex align-items-center justify-content-between">
                                <i class="fas fa-fw fa-database fa-lg"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="card-title h4 m-0"><?= nr($data->logs_total['form_submission']) ?></div>
                        <small class="form-text text-muted"><?= l('campaign.statistics.form_submissions_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="chart-container">
                <canvas id="impressions_chart"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="chart-container">
                <canvas id="hovers_chart"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="chart-container">
                <canvas id="clicks_chart"></canvas>
            </div>
        </div>
    </div>

    <div class="card mb-5">
        <div class="card-body">
            <div class="chart-container">
                <canvas id="form_submissions_chart"></canvas>
            </div>
        </div>
    </div>
<?php endif ?>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    /* Daterangepicker */
    $('#daterangepicker').daterangepicker({
        startDate: <?= json_encode($data->datetime['start_date']) ?>,
        endDate: <?= json_encode($data->datetime['end_date']) ?>,
        minDate: $('#daterangepicker').data('min-date'),
        maxDate: $('#daterangepicker').data('max-date'),
        ranges: {
            <?= json_encode(l('global.date.today')) ?>: [moment(), moment()],
            <?= json_encode(l('global.date.yesterday')) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            <?= json_encode(l('global.date.last_7_days')) ?>: [moment().subtract(6, 'days'), moment()],
            <?= json_encode(l('global.date.last_30_days')) ?>: [moment().subtract(29, 'days'), moment()],
            <?= json_encode(l('global.date.this_month')) ?>: [moment().startOf('month'), moment().endOf('month')],
            <?= json_encode(l('global.date.last_month')) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            <?= json_encode(l('global.date.all_time')) ?>: [moment($('#daterangepicker').data('min-date')), moment()]
        },
        alwaysShowCalendars: true,
        linkedCalendars: false,
        singleCalendar: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {

        /* Redirect */
        redirect(`<?= url('campaign/' . $data->campaign->campaign_id . '/statistics') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });

    <?php if(count($data->logs)): ?>
    let impressions_chart = document.getElementById('impressions_chart').getContext('2d');

    let gradient = impressions_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(96, 122, 226, 0.6)');
    gradient.addColorStop(1, 'rgba(96, 122, 226, 0.05)');

    new Chart(impressions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(l('campaign.statistics.impressions_chart')) ?>,
                data: <?= $data->logs_chart['impression'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#607ae2',
                fill: true
            }]
        },
        options: chart_options
    });


    let hovers_chart = document.getElementById('hovers_chart').getContext('2d');
    gradient = hovers_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(213, 96, 226, 0.6)');
    gradient.addColorStop(1, 'rgba(213, 96, 226, 0.05)');

    new Chart(hovers_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(l('campaign.statistics.hovers_chart')) ?>,
                data: <?= $data->logs_chart['hover'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#d560e2',
                fill: true
            }]
        },
        options: chart_options
    });

    let clicks_chart = document.getElementById('clicks_chart').getContext('2d');

    gradient = clicks_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(96, 187, 226, 0.4)');
    gradient.addColorStop(1, 'rgba(96, 187, 226, 0.05)');

    new Chart(clicks_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(l('campaign.statistics.clicks_chart')) ?>,
                data: <?= $data->logs_chart['click'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#60BBE2',
                fill: true
            }]
        },
        options: chart_options
    });

    let form_submissions_chart = document.getElementById('form_submissions_chart').getContext('2d');

    gradient = form_submissions_chart.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(226, 96, 174, 0.4)');
    gradient.addColorStop(1, 'rgba(226, 96, 174, 0.05)');

    new Chart(form_submissions_chart, {
        type: 'line',
        data: {
            labels: <?= $data->logs_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(l('campaign.statistics.form_submissions_chart')) ?>,
                data: <?= $data->logs_chart['form_submission'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#E260AE',
                fill: true
            }]
        },
        options: chart_options
    });
    <?php endif ?>
</script>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/statistics_reset_modal.php', ['modal_id' => 'campaign_statistics_reset_modal', 'resource_id' => 'campaign_id', 'path' => 'campaign/' . $data->campaign->campaign_id . '/statistics/reset']), 'modals'); ?>
