<?php
require_once __DIR__.'/functions.php';
$title = $title ?? 'Little Blossoms Childcare';
$base = '/little-blossoms';
// Navigation is populated from the database, filtered by the current role.
$nav = $pdo->prepare('SELECT label, path FROM page WHERE access_level IN (\'public\', ?) AND show_nav=1 ORDER BY sort_order, id');
$nav->execute([role()]);
$navItems=$nav->fetchAll();
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <meta name="description" content="Little Blossoms Childcare: caring, learning and growing together.">
        <title>
            <?=e($title)?>
            | Little Blossoms
        </title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?=$base?>
        /assets/css/style.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <nav class="navbar navbar-expand-lg bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand fw-bold" href="<?=$base?>
                        /index.php">🌼 Little Blossoms
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navLinks" aria-controls="navLinks" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                        </span>
                    </button>
                    <div class="collapse navbar-collapse" id="navLinks">
                        <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
                            <?php foreach($navItems as $item): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?=$base.'/'.e($item['path'])?>
                                        ">
                                        <?=e($item['label'])?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <?php if(user()): ?>
                                <li class="nav-item">
                                    <span class="nav-link text-success">
                                        Hello,
                                        <?=e(user()['first_name'])?>
                                    </span>
                                </li>
                                <li class="nav-item">
                                    <a class="btn btn-outline-success btn-sm" href="<?=$base?>
                                        /logout.php">Log out
                                    </a>
                                </li>
                                <?php else: ?>
                                <li class="nav-item">
                                    <a class="btn btn-success btn-sm" href="<?=$base?>
                                        /login.php">Parent / Admin login
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </nav>
        </header>
        <main>
            <?php if($message=flash()): ?>
                <div class="container mt-3">
                    <div class="alert alert-info" role="status">
                        <?=e($message)?>
                    </div>
                </div>
            <?php endif; ?>
