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
            <!-- Hero Search & Live Auto-Suggest Discovery Section -->
            <?php include(__DIR__ . '/../../../include/slidderfull.php'); ?>

            <!-- Shop by Category Carousel -->
            <?php include(__DIR__ . '/../../../include/cat.php'); ?>

            <!-- Latest Products Grid -->
            <?php include(__DIR__ . '/../../../include/categories.php'); ?>

            <!-- FAQ Section -->
            <?php include(__DIR__ . '/../../../include/index_qa.php'); ?>

            <!-- Standalone Trust & Value Propositions Banner -->
            <?php include(__DIR__ . '/../../../include/trust_banner.php'); ?>
        </main>

        <?php require APP_ROOT . '/views/layouts/footer.php'; ?>
    </div>

    <?php require APP_ROOT . '/views/layouts/sign_footer.php'; ?>
</body>
</html>
