<?php
defined('ALTUMCODE') || die();

/* Include the extra content of the notification */
$statistics = require THEME_PATH . 'views/notification/statistics/statistics.' . mb_strtolower($data->notification->type) . '.method.php';

?>

<?php if(!settings()->notifications->analytics_is_enabled): ?>
    <div class="alert alert-warning" role="alert">
        <?= l('notification.statistics.disabled') ?>
    </div>
<?php endif ?>

<div class="mt-5 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h3 text-truncate mb-0"><?= l('notification.statistics.header') ?></h2>

        <div class="d-flex align-items-center col-auto p-0">
            <div data-toggle="tooltip" title="<?= l('global.reset') ?>">
                <button
                        type="button"
                        class="btn btn-link text-secondary"
                        data-toggle="modal"
                        data-target="#notification_statistics_reset_modal"
                        aria-label="<?= l('global.reset') ?>"
                        data-notification-id="<?= $data->notification->notification_id ?>"
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
                        data-min-date="<?= \Altum\Date::get($data->notification->datetime, 4) ?>"
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
        <div class="col-12 col-md-6 col-lg-3 mb-3">
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
                        <small class="text-muted"><?= l('notification.statistics.impressions_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 mb-3">
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
                        <small class="text-muted"><?= l('notification.statistics.hovers_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 mb-3">
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
                        <small class="text-muted"><?= l('notification.statistics.clicks_chart') ?></small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-3 mb-3">
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

    <?php foreach($statistics->html as $html): echo $html; endforeach ?>

    <?php if($data->top_pages_result->num_rows): ?>

        <h2 class="h3 mt-5"><?= l('notification.statistics.header_top_pages') ?></h2>
        <p class="text-muted"><?= l('notification.statistics.subheader_top_pages') ?></p>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th></th>
                    <th>
                        <?= l('notification.statistics.pageviews') ?>
                    </th>
                </tr>
                </thead>
                <tbody>

                <?php while($row = $data->top_pages_result->fetch_object()): ?>

                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex flex-column">
                                <?= l('notification.statistics.' . $row->type) ?>
                                <span class="text-muted"><?= $row->url ?></span>
                            </div>
                        </td>
                        <td class="text-nowrap"><?= nr($row->pageviews) ?></td>
                    </tr>

                <?php endwhile ?>

                </tbody>
            </table>
        </div>

    <?php endif ?>

<?php endif ?>

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
        redirect(`<?= url('notification/' . $data->notification->notification_id . '/statistics') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

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
                label: <?= json_encode(l('notification.statistics.impressions_chart')) ?>,
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
                label: <?= json_encode(l('notification.statistics.hovers_chart')) ?>,
                data: <?= $data->logs_chart['hover'] ?? '[]' ?>,
                backgroundColor: gradient,
                borderColor: '#d560e2',
                fill: true
            }]
        },
        options: chart_options
    });
    <?php endif ?>

</script>

<?= $statistics->javascript ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/statistics_reset_modal.php', ['modal_id' => 'notification_statistics_reset_modal', 'resource_id' => 'notification_id', 'path' => 'notification/' . $data->notification->notification_id . '/statistics/reset']), 'modals'); ?>
