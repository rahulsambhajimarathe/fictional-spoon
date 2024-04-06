<?php defined('ALTUMCODE') || die(); ?>

<div class="mt-5 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2 class="h3 text-truncate mb-0"><?= l('notification.data.header') ?></h2>

        <div class="d-flex align-items-center col-auto p-0">
            <button type="button" data-toggle="modal" data-target="#create_notification_data" class="btn btn-sm btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('notification.data.create') ?></button>

            <?php if(count($data->conversions)): ?>
                <div class="ml-3">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>">
                            <i class="fas fa-fw fa-sm fa-download"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-right d-print-none">
                            <a href="<?= url('notification/' . $data->notification->notification_id . '/data/' . $data->datetime['start_date'] . '/' . $data->datetime['end_date'] . '?page=' . ($_GET['page'] ?? 1) . '&json') ?>" target="_blank" class="dropdown-item">
                                <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <div class="ml-3">
                <button
                        id="daterangepicker"
                        type="button"
                        class="btn btn-sm btn-light"
                        data-min-date="<?= \Altum\Date::get('2000-01-01', 4) ?>"
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


<?php if(!count($data->conversions)): ?>

    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
        'filters_get' => $data->filters->get ?? [],
        'name' => 'global',
        'has_secondary_text' => false,
        'has_wrapper' => false,
    ]); ?>

<?php else: ?>

    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('notification.data.data') ?></th>
                <th><?= l('notification.data.type') ?></th>
                <th><?= l('notification.data.date') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody class="accordion" id="accordion">

            <?php foreach($data->conversions as $row): ?>
                <tr class="clickable" data-toggle="collapse" data-target="#<?= 'data_collapse_' . $row->id ?>" aria-expanded="true" aria-controls="<?= 'data_collapse_' . $row->id ?>">
                    <td class="text-nowrap">
                        <strong><?= l('notification.data.expand_data') ?></strong>
                    </td>
                    <td class="text-nowrap"><?= l('notification.data.type_' . $row->type) ?></td>
                    <td class="text-nowrap"><span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get($row->datetime, 1) ?></span></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                    <i class="fas fa-fw fa-ellipsis-v"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="#" class="dropdown-item" data-delete-notification-data="<?= l('delete_modal.subheader2') ?>" data-row-id="<?= $row->id ?>"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>

                <tr id="<?= 'data_collapse_' . $row->id ?>" data-id="<?= $row->id ?>" data-notification-id="<?= $row->notification_id ?>" class="collapse" data-parent="#accordion">
                    <td colspan="4">
                        <div class="row">
                            <div class="d-flex justify-content-center">
                                <div class="spinner-grow"></div>
                            </div>
                        </div>
                    </td>
                </tr>

            <?php endforeach ?>

            </tbody>
        </table>
    </div>

    <div class="mt-3"><?= $data->pagination ?></div>
<?php endif ?>


<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

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
        redirect(`<?= url('notification/' . $data->notification->notification_id . '/data') ?>?start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });


    /* Handle the opening and closing of the details for conversions */
    $('[id^="data_collapse_"]').on('show.bs.collapse', event => {
        let id = $(event.currentTarget).data('id');
        let notification_id = $(event.currentTarget).data('notification-id');
        let request_type = 'read_data_conversion';

        $.ajax({
            type: 'GET',
            url: `${url}notifications-ajax?id=${id}&notification_id=${notification_id}&global_token=${global_token}&request_type=${request_type}`,
            success: (result) => {

                $(event.currentTarget).find('.row').html(result.details.html);

                /* Refresh tooltips */
                $('[data-toggle="tooltip"]').tooltip();

            },
            dataType: 'json'
        });


    });

    /* Delete handler for the conversion */
    $('[data-delete-notification-data]').on('click', event => {
        let message = $(event.currentTarget).attr('data-delete-notification-data');

        if(!confirm(message)) return false;

        /* Continue with the deletion */
        ajax_call_helper(event, 'notification-data-ajax', 'delete', () => {

            /* On success delete the actual row from the DOM */
            let current_tr = $(event.currentTarget).closest('tr');
            let next_tr = current_tr.next();

            current_tr.remove();
            next_tr.remove();

        });
    });
</script>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
