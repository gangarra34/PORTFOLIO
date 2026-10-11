<?php $title='Parent stories';require __DIR__.'/includes/header.php';$q=$pdo->query('SELECT t.*,s.name AS service_name,u.first_name FROM testimonial t JOIN service s ON s.id=t.service_id JOIN user u ON u.id=t.parent_id WHERE t.approved=1 ORDER BY t.created_at DESC');?>
<section class="section">
    <div class="container">
        <h1>
            What families say
        </h1>
        <p class="lead">
            Kind words from our childcare community.
        </p>
        <?php if(role()==='parent'):?>
            <a class="btn btn-success mb-4" href="testimonial_add.php">
                Write a testimonial
            </a>
        <?php endif;?>
        <div class="row g-4">
            <?php foreach($q as $t):?>
                <div class="col-md-6">
                    <blockquote class="form-panel h-100">
                        <p class="fs-5">
                            “
                            <?=e($t['comment'])?>
                            ”
                        </p>
                        <footer class="small-note">
                            <?=e($t['first_name'])?>
                            ·
                            <?=e($t['service_name'])?>
                            ·
                            <?=e(substr($t['created_at'],0,10))?>
                        </footer>
                    </blockquote>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
