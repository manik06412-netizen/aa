<?php
/**
 * include/new_arrivals.php
 * Karuda Computers — Wireframe Section 7: New Arrivals
 */
?>
<div class="container py-4">
    <!-- Section Header (Centered Title | View All Right) -->
    <div class="kc-sec-heading-block">
        <h2 class="kc-sec-heading-title">
            NEW ARRIVALS
        </h2>
        <a href="allproducts.php?sort=newest" class="kc-sec-heading-viewall">
            View All &rarr;
        </a>
    </div>

    <div class="kc-prod-grid">
        <?php
        if (!isset($con) || !$con) {
            include(__DIR__ . '/../dbconnect.php');
        }

        // Fetch newest products from database
        $new_sql = "SELECT d.*, 
                    COALESCE(MIN(p.pp), 0) AS current_price,
                    COALESCE(MIN(p.oprice), 0) AS old_price,
                    COALESCE(SUM(p.total_stock), 0) AS current_stock
                FROM dishes d
                LEFT JOIN price p ON p.pcode = d.rs_id
                WHERE d.status = 1
                GROUP BY d.d_id
                ORDER BY d.d_id DESC
                LIMIT 5";

        $new_query = mysqli_query($con, $new_sql);

        if ($new_query && mysqli_num_rows($new_query) > 0) {
            while ($p = mysqli_fetch_assoc($new_query)) {
                $p_id = !empty($p['rs_id']) ? (int)$p['rs_id'] : (int)$p['d_id'];
                $p_name = htmlspecialchars($p['dish_name']);
                $raw_img = !empty($p['img']) ? htmlspecialchars($p['img']) : 'img/products/hp_laptop.jpg';
                $p_img = function_exists('kcResolveProdImg') ? kcResolveProdImg($raw_img) : $raw_img;
                $p_price = (float)$p['current_price'];
                $p_old = (float)$p['old_price'];
                $discount = ($p_old > $p_price && $p_old > 0) ? round((($p_old - $p_price) / $p_old) * 100) : 0;
                $detail_link = "details.php?id=" . $p_id;
        ?>
        <div class="uls-compact-card">
            
            <!-- Product Image Box -->
            <div class="uls-img-slide-wrap">
                <?php if ($discount > 0): ?>
                    <span class="uls-bright-discount-badge">-<?php echo $discount; ?>%</span>
                <?php endif; ?>

                <a href="<?php echo $detail_link; ?>" class="uls-img-link">
                    <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" class="uls-slide-img" onerror="this.src='img/products/hp_laptop.jpg'">
                </a>
            </div>

            <!-- Product Title -->
            <h3 class="uls-compact-title">
                <a href="<?php echo $detail_link; ?>" title="<?php echo $p_name; ?>"><?php echo $p_name; ?></a>
            </h3>

            <!-- Price Row -->
            <div class="uls-compact-price-row">
                <span class="uls-price-main">₹<?php echo number_format($p_price, 2); ?></span>
                <?php if ($p_old > $p_price && $p_old > 0): ?>
                    <span class="uls-price-strikethrough">₹<?php echo number_format($p_old, 2); ?></span>
                <?php endif; ?>
            </div>

            <!-- Review Rating & Add to Cart -->
            <div class="uls-compact-bottom-row mt-auto">
                <div class="uls-rating-box">
                    <span class="uls-green-rating-badge">4.9 <i class="fa fa-star" style="font-size:8.5px;"></i></span>
                </div>

                <button type="button" class="uls-white-cart-btn" onclick="quickAddToCart(<?php echo $p_id; ?>, this)" title="Add to Cart">
                    <i class="fa fa-cart-plus"></i>
                </button>
            </div>

        </div>
        <?php
            }
        }
        ?>
    </div>
</div>
