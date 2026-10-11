
<?php
require_once __DIR__ . '/functions.php';

// Website title
$title = $title ?? 'Little Blossoms Childcare';

// Correct project location inside XAMPP
$base = '/PORTFOLIO/PROJECTS/CHILDCARE/little-blossoms';

// Get navigation links based on user role
$nav = $pdo->prepare(
    "SELECT label, path
     FROM page
     WHERE access_level IN ('public', ?)
     AND show_nav = 1
     ORDER BY sort_order, id"
);

$nav->execute([role()]);
$navItems = $nav->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= e($title) ?> | Little Blossoms</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Custom CSS -->
    <link
        href="<?= e($base) ?>/assets/css/style.css"
        rel="stylesheet"
    >
</head>

<body>

<header>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container">

            <!-- Logo -->
            <a class="navbar-brand fw-bold"
               href="<?= e($base) ?>/index.php">
                🌼 Little Blossoms
            </a>

            <!-- Mobile navigation button -->
            <button
                class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navLinks"
                aria-controls="navLinks"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navLinks">

                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">

                    <!-- Dynamic navigation -->
                    <?php foreach ($navItems as $item): ?>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?= e($base . '/' . ltrim($item['path'], '/')) ?>">
                                <?= e($item['label']) ?>
                            </a>
                        </li>

                    <?php endforeach; ?>

                    <!-- Logged-in user -->
                    <?php if (user()): ?>

                        <li class="nav-item">
                            <span class="nav-link text-success">
                                Hello, <?= e(user()['first_name']) ?>
                            </span>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-outline-success btn-sm"
                               href="<?= e($base) ?>/logout.php">
                                Log out
                            </a>
                        </li>

                    <?php else: ?>

                        <!-- Login button -->
                        <li class="nav-item">
                            <a class="btn btn-success btn-sm"
                               href="<?= e($base) ?>/login.php">
                                Parent / Admin Login
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
</header>

<main>

    <!-- Flash messages -->
    <?php if ($message = flash()): ?>

        <div class="container mt-3">
            <div class="alert alert-info" role="status">
                <?= e($message) ?>
            </div>
        </div>

    <?php endif; ?>
