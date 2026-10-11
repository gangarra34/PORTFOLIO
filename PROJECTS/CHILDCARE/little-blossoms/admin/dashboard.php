<?php require __DIR__.'/../includes/functions.php';require_role('admin');$title='Admin dashboard';require __DIR__.'/../includes/header.php';?>
<section class="section">
    <div class="container">
        <p class="eyebrow">
            Staff portal
        </p>
        <h1>
            Administration dashboard
        </h1>
        <p>
            Manage childcare information and parent communication.
        </p>
        <div class="row g-3">
            <?php foreach([['Homepage features','index_edit.php'],['Fees','registration_edit.php'],['Daily child records','day_details_edit.php'],['Testimonials','testimonial_manage.php'],['Contact messages','contact_us_manage.php'],['Services','service_manage.php'],['Parent view','../day_details.php']] as $link):?>
                <div class="col-md-4">
                    <a class="card feature-card p-4 text-decoration-none text-success fw-bold" href="<?=e($link[1])?>
                        ">
                        <?=e($link[0])?>
                        →
                    </a>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php require __DIR__.'/../includes/footer.php';?>
