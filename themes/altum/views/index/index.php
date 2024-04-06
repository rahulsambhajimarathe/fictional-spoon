<?php defined('ALTUMCODE') || die() ?>

<div class="index-background-container d-none d-lg-block">
    <div class="index-background-image"></div>
</div>

<div class="index-cover-container d-none d-lg-block">
    <div class="container container-disabled-simple">
        <div class="index-cover">
            <div class="row mb-3">
                <div class="col-4">
                    <?php $notification = \Altum\Notification::get('INFORMATIONAL', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Altum\Notification::get('CONVERSIONS', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Altum\Notification::get('REVIEWS', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <?php $notification = \Altum\Notification::get('EMAIL_COLLECTOR', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <div>
                        <?php $notification = \Altum\Notification::get('SCORE_FEEDBACK', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>

                    <div class="mt-3">
                        <?php $notification = \Altum\Notification::get('CONVERSIONS_COUNTER', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>
                </div>

                <div class="col-4">
                    <div>
                        <?php $notification = \Altum\Notification::get('EMOJI_FEEDBACK', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>

                    <div class="mt-3">
                        <?php $notification = \Altum\Notification::get('COOKIE_NOTIFICATION', null, null, false) ?>
                        <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-4">
                    <?php $notification = \Altum\Notification::get('SOCIAL_SHARE', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Altum\Notification::get('REQUEST_COLLECTOR', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

                <div class="col-4">
                    <?php $notification = \Altum\Notification::get('LIVE_COUNTER', null, null, false) ?>
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="index-container">
    <div class="container">
        <?= \Altum\Alerts::output_alerts() ?>

        <div class="row mt-6">
            <div class="col">
                <div class="text-left">
                    <h1 class="index-header mb-4"><?= l('index.header') ?></h1>
                    <p class="index-subheader text-muted mb-5"><?= sprintf(l('index.subheader'), $data->total_notifications) ?></p>

                    <div>
                        <a href="<?= url('register') ?>" class="btn btn-primary index-button">
                            <?= l('index.sign_up') ?> <i class="fas fa-fw fa-sm fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container pt-5 mt-10">
    <div class="row">
        <div class="col-12 col-md-4">
            <div class="d-flex align-items-start mb-5 mb-md-0 icon-zoom-animation" data-aos="fade-up" data-aos-delay="100">
                <div class="index-icon-container rounded mr-4">
                    <i class="fas fa-fw fa-plug"></i>
                </div>

                <div>
                    <h2 class="h5"><?= l('index.steps.one') ?></h2>
                    <p class="text-muted m-0"><?= l('index.steps.one_text') ?></p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex align-items-start mb-5 mb-md-0 icon-zoom-animation" data-aos="fade-up" data-aos-delay="200">
                <div class="index-icon-container rounded mr-4">
                    <i class="fas fa-fw fa-plus"></i>
                </div>

                <div>
                    <h2 class="h5"><?= l('index.steps.two') ?></h2>
                    <p class="text-muted m-0"><?= l('index.steps.two_text') ?></p>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="d-flex align-items-start mb-5 mb-md-0 icon-zoom-animation" data-aos="fade-up" data-aos-delay="300">
                <div class="index-icon-container rounded mr-4">
                    <i class="fas fa-fw fa-money-bill-wave"></i>
                </div>

                <div>
                    <h2 class="h5"><?= l('index.steps.three') ?></h2>
                    <p class="text-muted m-0"><?= l('index.steps.three_text') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="py-5 bg-gray-100 mt-10">
    <div class="container">
        <div class="text-center">
            <h2 class="h4"><?= l('index.setup.header') ?></h2>
        </div>

        <div class="row mt-5 d-flex align-items-center">
            <div class="col-12 col-md-3 mb-5 mb-md-0 text-center" >
                <img src="<?= ASSETS_FULL_URL . 'images/shopify_logo.svg' ?>" class="img-fluid zoomer" alt="<?= l('index.setup.shopify') ?>">
            </div>
            <div class="col-12 col-md-3 mb-5 mb-md-0 text-center">
                <img src="<?= ASSETS_FULL_URL . 'images/worpress_logo.svg' ?>" class="img-fluid zoomer" alt="<?= l('index.setup.wordpress') ?>">
            </div>
            <div class="col-12 col-md-3 mb-5 mb-md-0 text-center">
                <img src="<?= ASSETS_FULL_URL . 'images/zapier_logo.svg' ?>" class="img-fluid zoomer" alt="<?= l('index.setup.zapier') ?>">
            </div>
            <div class="col-12 col-md-3 mb-5 mb-md-0 text-center">
                <img src="<?= ASSETS_FULL_URL . 'images/squarespace_logo.svg' ?>" class="img-fluid zoomer" alt="<?= l('index.setup.squarespace') ?>">
            </div>
        </div>
    </div>
</div>

<div class="container mt-10">
    <div class="mb-3 d-flex justify-content-between align-items-center flex-column flex-md-row">
        <div>
            <h2><span><?= l('index.tools.preview') ?></span></h2>
            <p class="text-muted"><?= l('index.tools.preview_description') ?></p>
        </div>

        <div id="notification_preview" class="container-disabled-simple"></div>
    </div>

    <div id="notifications" class="mt-8 row d-flex align-items-center">
        <?php foreach($data->notifications as $notification_type => $notification_config): ?>
            <?php $notification = \Altum\Notification::get($notification_type) ?>

            <label class="col-12 col-md-6 col-lg-4 mb-md-4 custom-radio-box mb-3">
                <input type="radio" name="type" value="<?= $notification_type ?>" class="custom-control-input" required="required">

                <div class="card zoomer h-100" data-aos="fade-up">
                    <div class="card-body">

                        <div class="mb-3 text-center">
                            <span class="custom-radio-box-main-icon"><i class="<?= l('notification.' . mb_strtolower($notification_type) . '.icon') ?>"></i></span>
                        </div>

                        <div class="card-title font-weight-bold text-center"><?= l('notification.' . mb_strtolower($notification_type) . '.name') ?></div>

                        <p class="text-muted text-center"><?= l('notification.' . mb_strtolower($notification_type) . '.description') ?></p>

                    </div>
                </div>

                <div class="preview" style="display: none">
                    <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>
            </label>
        <?php endforeach ?>
    </div>
</div>

<?php ob_start() ?>
<script>
    $('#notifications .altumcode-hidden').removeClass('altumcode-hidden').addClass('altumcode-shown');
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<div class="container mt-8">
    <div class="card bg-gray-100 py-5">
        <div class="card-body text-center">
            <h2 class="h3"><?= sprintf(l('index.tools.header'), '<span class="text-primary">' . nr($data->total_track_notifications, 0, true, true) . '</span>') ?></h2>

            <p class="text-muted"><?= l('index.tools.subheader') ?></p>
        </div>
    </div>
</div>

<?php if(settings()->main->display_index_testimonials): ?>
<div class="mt-9 py-7 bg-primary-100">
    <div class="container">
        <div class="text-center">
            <h2><?= l('index.testimonials.header') ?> <i class="fas fa-fw fa-xs fa-check-circle text-primary"></i></h2>
        </div>

        <div class="row mt-8">
            <?php foreach(['one', 'two', 'three'] as $key => $value): ?>
                    <div class="col-12 col-lg-4 mb-6 mb-lg-0" data-aos="fade-up" data-aos-delay="<?= $key * 100 ?>">
                        <div class="card border-0 zoom-animation">
                            <div class="card-body">
                                <img src="<?= ASSETS_FULL_URL . 'images/index/testimonial-' . $value . '.jpeg' ?>" class="img-fluid index-testimonial-avatar" alt="<?= l('index.testimonials.' . $value . '.name') . ', ' . l('index.testimonials.' . $value . '.attribute') ?>" loading="lazy" />

                                <p class="mt-5">
                                    <span class="text-gray-800 font-weight-bold text-muted h5">“</span>
                                    <span><?= l('index.testimonials.' . $value . '.text') ?></span>
                                    <span class="text-gray-800 font-weight-bold text-muted h5">”</span>
                                </p>

                                <div class="blockquote-footer mt-4">
                                    <span class="font-weight-bold"><?= l('index.testimonials.' . $value . '.name') ?></span>, <span class="text-muted"><?= l('index.testimonials.' . $value . '.attribute') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
        </div>
    </div>
</div>
<?php endif ?>

<?php if(settings()->main->display_index_plans): ?>
<div class="container mt-10">
    <div class="text-center mb-5">
        <h2><?= l('index.pricing.header') ?></h2>
        <p class="text-muted"><?= l('index.pricing.subheader') ?></p>
    </div>

    <?= $this->views['plans'] ?>
</div>
<?php endif ?>

<?php if(settings()->main->display_index_faq): ?>
    <div class="container mt-9">
        <div class="text-center mb-5">
            <h2><?= sprintf(l('index.faq.header'), '<span class="text-primary">', '</span>') ?></h2>
        </div>

        <div class="accordion index-faq" id="faq_accordion">
            <?php foreach(['one', 'two', 'three', 'four'] as $key): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="" id="<?= 'faq_accordion_' . $key ?>">
                            <h3 class="mb-0">
                                <button class="btn btn-lg font-weight-bold btn-block d-flex justify-content-between text-gray-800 px-0 icon-zoom-animation" type="button" data-toggle="collapse" data-target="<?= '#faq_accordion_answer_' . $key ?>" aria-expanded="true" aria-controls="<?= 'faq_accordion_answer_' . $key ?>">
                                    <span><?= l('index.faq.' . $key . '.question') ?></span>

                                    <span data-icon>
                                        <i class="fas fa-fw fa-circle-chevron-down"></i>
                                    </span>
                                </button>
                            </h3>
                        </div>

                        <div id="<?= 'faq_accordion_answer_' . $key ?>" class="collapse text-muted mt-2" aria-labelledby="<?= 'faq_accordion_' . $key ?>" data-parent="#faq_accordion">
                            <?= l('index.faq.' . $key . '.answer') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        'use strict';

        $('#faq_accordion').on('show.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.transform = 'rotate(180deg)';
            svg.style.color = 'var(--primary)';
        })

        $('#faq_accordion').on('hide.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.color = 'var(--primary-800)';
            svg.style.removeProperty('transform');
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

<?php if(settings()->users->register_is_enabled): ?>
    <div class="index-register-container mt-9">
        <div class="container">
            <div class="py-4">
                <div class="row align-items-center justify-content-center" data-aos="fade-up">
                    <div class="col-12 col-lg-5">
                        <div class="text-center text-lg-left mb-4 mb-lg-0">
                            <h2><?= l('index.cta.header') ?></h2>
                            <p class="h6"><?= l('index.cta.subheader') ?></p>
                        </div>
                    </div>

                    <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                        <div class="text-center text-lg-right">
                            <?php if(\Altum\Authentication::check()): ?>
                                <a href="<?= url('dashboard') ?>" class="btn btn-outline-light index-button">
                                    <?= l('dashboard.menu') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                </a>
                            <?php else: ?>
                                <a href="<?= url('register') ?>" class="btn btn-outline-light index-button">
                                    <?= l('index.cta.register') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                </a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif ?>

<?php if(count($data->blog_posts)): ?>
    <div class="my-5">&nbsp;</div>

    <div class="container">
        <div class="text-center mb-5">
            <h2><?= sprintf(l('index.blog.header'), '<span class="text-primary">', '</span>') ?></h2>
        </div>

        <div class="row">
            <?php foreach($data->blog_posts as $blog_post): ?>
            <div class="col-12 col-lg-4 mb-5">
                <div class="card h-100 zoom-animation-subtle">
                    <div class="card-body">
                        <?php if($blog_post->image): ?>
                                <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>" aria-label="<?= $blog_post->title ?>">
                                    <img src="<?= \Altum\Uploads::get_full_url('blog') . $blog_post->image ?>" class="blog-post-image-small img-fluid w-100 rounded mb-4" loading="lazy" />
                                </a>
                            <?php endif ?>

                        <a href="<?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?>">
                            <h3 class="h5 card-title mb-2"><?= $blog_post->title ?></h3>
                        </a>

                        <p class="text-muted mb-0"><?= $blog_post->description ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>


<?php ob_start() ?>
<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/libraries/aos.min.css' ?>">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>


<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/aos.min.js' ?>"></script>

<script>
    AOS.init({
        delay: 100,
        duration: 600
    });

    /* Preview handler */
    $('input[name="type"]').on('change', (event, first_trigger = false) => {

        let preview_html = $(event.currentTarget).closest('label').find('.preview').html();

        $('#notification_preview').hide().html(preview_html).fadeIn();

        /* Make sure its not the first check */
        if(!first_trigger) {
            document.querySelector('#notification_preview').scrollIntoView();
        }

    });

    /* Select a default option */
    $('input[name="type"]:first').attr('checked', true).trigger('change', true);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

