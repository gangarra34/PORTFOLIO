<?php $title='Services & Facilities';require __DIR__.'/includes/header.php';$items=$pdo->query('SELECT * FROM service ORDER BY id')->fetchAll();?>
<section class="section">
    <div class="container">
        <p class="eyebrow">
            Learning together
        </p>
        <h1>
            Our services and facilities
        </h1>
        <p class="lead">
            Age-appropriate care in welcoming spaces.
        </p>
        <div class="row g-4 mt-3">
            <?php foreach($items as $s):?>
                <div class="col-md-6 col-lg-4">
                    <article class="card feature-card">
                        <img src="<?=e($s['image_path'])?>
                        " alt="
                        <?=e($s['name'])?>
                        ">
                        <div class="card-body">
                            <h2 class="h4">
                                <?=e($s['name'])?>
                            </h2>
                            <p>
                                <?=e($s['summary'])?>
                            </p>
                            <a class="btn btn-outline-success" href="<?=e($s['link_path'])?>
                                ">Learn more
                            </a>
                        </div>
                    </article>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php';?>
