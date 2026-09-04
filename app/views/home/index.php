<?php
// MVC Home View
require_once APP_ROOT . '/views/layouts/header.php';
?>
<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <main>
            <!-- 1. CATEGORY SECTION (Circular Badges) -->
            <?php include(__DIR__ . '/../../../include/cat.php'); ?>

            <!-- 2. PROMINENT HERO SEARCH SECTION -->
            <?php include(__DIR__ . '/../../../include/search_section.php'); ?>

            <!-- 3. FEATURED PRODUCTS SECTION -->
            <?php include(__DIR__ . '/../../../include/categories.php'); ?>

            <!-- 6. PROMO BANNER -->
            <?php include(__DIR__ . '/../../../include/promo_banner.php'); ?>

            <!-- 7. NEW ARRIVALS -->
            <?php include(__DIR__ . '/../../../include/new_arrivals.php'); ?>

            <!-- 8. TRUST / FEATURES -->
            <?php include(__DIR__ . '/../../../include/trust_banner.php'); ?>

            <!-- 9. TESTIMONIALS -->
            <?php include(__DIR__ . '/../../../include/testimonial_section.php'); ?>

            <!-- 10. FAQ SECTION -->
            <?php include(__DIR__ . '/../../../include/index_qa.php'); ?>
        </main>

        <?php require APP_ROOT . '/views/layouts/footer.php'; ?>
    </div>

    <?php require APP_ROOT . '/views/layouts/sign_footer.php'; ?>
</body>
</html>
