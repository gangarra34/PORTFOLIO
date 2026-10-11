<?php $title='Home'; require __DIR__.'/includes/header.php'; $features=$pdo->query('SELECT * FROM feature ORDER BY id LIMIT 2')->fetchAll(); ?>
<section class="hero">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <p class="eyebrow">
                    A happy place to learn and grow
                </p>
                <h1>
                    Big dreams start with little blossoms.
                </h1>
                <p class="lead my-4">
                    A warm, playful childcare community where every child feels safe, seen and inspired to explore.
                </p>
                <a class="btn btn-success btn-lg me-2" href="registration.php">
                    Join our family
                </a>
                <a class="btn btn-outline-success btn-lg" href="services.php">
                    Explore our programmes
                </a>
            </div>
            <div class="col-lg-6">
                <img class="hero-illustration" src="assets/img/hero.svg" alt="Colourful illustration of flowers and a cheerful sun">
            </div>
        </div>
    </div>
</section>
<section class="section">
    <div class="container">
        <div class="text-center mb-5">
            <p class="eyebrow">
                What's happening
            </p>
            <h2 class="display-6 fw-bold">
                Discover something wonderful
            </h2>
            <p>
                Events and activities at Little Blossoms.
            </p>
        </div>
        <div class="row g-4">
            <?php foreach($features as $f): ?>
                <div class="col-md-6">
                    <article class="card feature-card">
                        <img src="<?=e($f['image_path'])?>
                        " alt="
                        <?=e($f['title'])?>
                        ">
                        <div class="card-body">
                            <h3 class="h4">
                                <?=e($f['title'])?>
                            </h3>
                            <p>
                                <?=nl2br(e($f['detail']))?>
                            </p>
                            <?php if($f['link_path']): ?>
                                <a href="<?=e($f['link_path'])?>
                                    " class="btn btn-outline-success">Learn more →
                                </a>
                            <?php endif; ?>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="section bg-white">
    <div class="container text-center">
        <p class="eyebrow">
            Our promise
        </p>
        <h2>
            Every child belongs here
        </h2>
        <p class="lead mx-auto" style="max-width:720px">
            From first steps to school readiness, we nurture confidence, kindness and curiosity through play-based learning.
        </p>
        <div class="row g-4 mt-3">
            <div class="col-md-4">
                <div class="pill">
                    🌱 Caring educators
                </div>
            </div>
            <div class="col-md-4">
                <div class="pill">
                    🎨 Creative play
                </div>
            </div>
            <div class="col-md-4">
                <div class="pill">
                    💚 Family partnership
                </div>
            </div>
        </div>
    </div>
</section>
<?php require __DIR__.'/includes/footer.php'; ?>
