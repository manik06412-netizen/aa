<!-- ══════════════════════════════════════════════════════════
     KARUDA COMPUTERS — CLEAN FEATURED PRODUCTS
══════════════════════════════════════════════════════════ -->
<div class="container">
    
    <!-- Section Header (Sub-text removed) -->
    <div class="kc-section-header text-center mb-4 mt-5">
        <span class="kc-badge-pill">
            <i class="fa fa-fire text-danger"></i> Best Selling Hardware
        </span>
        <h2 class="kc-modern-heading">
            Featured <span class="kc-heading-gradient">Products</span>
        </h2>
    </div>

    <div class="kc-prod-grid">
        <?php
        include(__DIR__ . '/../dbconnect.php');

        // Helper to resolve product image paths accurately
        if (!function_exists('kcResolveProdImg')) {
            function kcResolveProdImg($imgPath) {
                if (empty($imgPath)) return 'img/products/hp_laptop.jpg';
                if (file_exists(__DIR__ . '/../' . $imgPath)) return $imgPath;
                if (file_exists(__DIR__ . '/../avadmin/' . $imgPath)) return 'avadmin/' . $imgPath;
                if (file_exists(__DIR__ . '/../admin1/' . $imgPath)) return 'admin1/' . $imgPath;
                if (file_exists(__DIR__ . '/../admin/' . $imgPath)) return 'admin/' . $imgPath;
                return $imgPath;
            }
        }

        // Fetch products from database
        $sql = "SELECT d.*, 
                    COALESCE(MIN(p.pp), 0) AS current_price,
                    COALESCE(MIN(p.oprice), 0) AS old_price,
                    COALESCE(SUM(p.total_stock), 0) AS current_stock
                FROM dishes d
                LEFT JOIN price p ON p.pcode = d.rs_id
                WHERE d.status = 1
                GROUP BY d.d_id
                ORDER BY d.d_id DESC
                LIMIT 8";

        $prod_query = mysqli_query($con, $sql);

        if ($prod_query && mysqli_num_rows($prod_query) > 0) {
            while ($p = mysqli_fetch_assoc($prod_query)) {
                $p_id = !empty($p['rs_id']) ? (int)$p['rs_id'] : (int)$p['d_id'];
                $p_name = htmlspecialchars($p['dish_name']);
                $raw_img = !empty($p['img']) ? htmlspecialchars($p['img']) : 'img/products/hp_laptop.jpg';
                $p_img = kcResolveProdImg($raw_img);
                $p_price = (float)$p['current_price'];
                $p_old = (float)$p['old_price'];
                $discount = ($p_old > $p_price && $p_old > 0) ? round((($p_old - $p_price) / $p_old) * 100) : 0;
                $detail_link = "details.php?id=" . $p_id;
        ?>
        <div class="uls-compact-card">
            
            <!-- 1. Product Image Box (Full Container Fit & Hover Slide Animation) -->
            <div class="uls-img-slide-wrap">
                <?php if ($discount > 0): ?>
                    <span class="uls-bright-discount-badge">-<?php echo $discount; ?>%</span>
                <?php endif; ?>

                <a href="<?php echo $detail_link; ?>" class="uls-img-link">
                    <img src="<?php echo $p_img; ?>" alt="<?php echo $p_name; ?>" class="uls-slide-img" onerror="this.src='img/products/hp_laptop.jpg'">
                </a>
            </div>

            <!-- 2. Product Name / Title -->
            <h3 class="uls-compact-title">
                <a href="<?php echo $detail_link; ?>" title="<?php echo $p_name; ?>"><?php echo $p_name; ?></a>
            </h3>

            <!-- 3. Price Row -->
            <div class="uls-compact-price-row">
                <span class="uls-price-main">₹<?php echo number_format($p_price, 2); ?></span>
                <?php if ($p_old > $p_price && $p_old > 0): ?>
                    <span class="uls-price-strikethrough">₹<?php echo number_format($p_old, 2); ?></span>
                <?php endif; ?>
            </div>

            <!-- 4. Review Rating & Side Add to Cart Icon Button -->
            <div class="uls-compact-bottom-row mt-auto">
                <div class="uls-rating-box">
                    <span class="uls-green-rating-badge">4.9 <i class="fa fa-star" style="font-size:8.5px;"></i></span>
                </div>

                <!-- Default White Add to Cart Button (Hover -> Theme Blue) -->
                <button type="button" class="uls-white-cart-btn" onclick="quickAddToCart(<?php echo $p_id; ?>, this)" title="Add to Cart">
                    <i class="fa fa-cart-plus"></i>
                </button>
            </div>

        </div>
        <?php
            }
        } else {
        ?>
        <div class="col-12 text-center py-5">
            <i class="fa fa-box-open" style="font-size:48px; color:#CBD5E1; margin-bottom:12px;"></i>
            <p style="color:#94A3B8; font-size:16px; font-weight:600;">No products available right now. Check back soon!</p>
        </div>
        <?php
        }
        ?>
    </div>

    <!-- View All Products Action Button -->
    <div class="text-center mt-2 mb-5">
        <a href="allproducts.php" class="uls-view-all-products-btn">
            <span>View All Products</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>

    <style>
    /* ══════════════════════════════════════════════════════════
       COMPACT PRODUCT CARDS STYLING
    ══════════════════════════════════════════════════════════ */
    .kc-prod-grid {
        display: grid !important;
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr)) !important;
        gap: 14px !important;
        margin-bottom: 30px !important;
    }

    /* View All Products Button */
    .uls-view-all-products-btn {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 10px !important;
        background: linear-gradient(135deg, #0070F3 0%, #0056B3 100%) !important;
        color: #ffffff !important;
        font-size: 14.5px !important;
        font-weight: 700 !important;
        padding: 12px 34px !important;
        border-radius: 10px !important;
        text-decoration: none !important;
        letter-spacing: 0.3px !important;
        box-shadow: 0 4px 18px rgba(0, 112, 243, 0.35) !important;
        transition: all 0.25s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    }
    .uls-view-all-products-btn:hover {
        background: linear-gradient(135deg, #0080FF 0%, #0070F3 100%) !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 8px 26px rgba(0, 188, 212, 0.45) !important;
        color: #ffffff !important;
    }
    .uls-view-all-products-btn i {
        transition: transform 0.25s ease !important;
    }
    .uls-view-all-products-btn:hover i {
        transform: translateX(4px) !important;
    }
    
    /* Card Container */
    .uls-compact-card {
        background: #ffffff !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 12px !important;
        padding: 10px !important;
        position: relative !important;
        transition: transform 0.2s ease, box-shadow 0.2s ease !important;
        display: flex !important;
        flex-direction: column !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03) !important;
    }
    .uls-compact-card:hover {
        border-color: #E2E8F0 !important;
        transform: translateY(-3px) !important;
        box-shadow: 0 8px 18px rgba(0, 0, 0, 0.07) !important;
    }

    /* 1. Image Box & Image Slide Animation */
    .uls-img-slide-wrap {
        width: 100% !important;
        height: 160px !important;
        border-radius: 8px !important;
        background: #FFFFFF !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        margin-bottom: 8px !important;
        overflow: hidden !important;
        position: relative !important;
        padding: 0 !important;
    }
    .uls-img-link {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 100% !important;
        height: 100% !important;
    }
    .uls-slide-img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
        transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    }
    .uls-compact-card:hover .uls-slide-img {
        transform: scale(1.08) translateX(4px) !important;
    }

    /* Bright Red Discount Badge */
    .uls-bright-discount-badge {
        position: absolute !important;
        top: 6px !important;
        left: 6px !important;
        background: #EF4444 !important;
        color: #ffffff !important;
        font-size: 10px !important;
        font-weight: 800 !important;
        padding: 2px 6px !important;
        border-radius: 5px !important;
        z-index: 5 !important;
        letter-spacing: 0.3px !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3) !important;
    }

    /* 2. Product Name / Title */
    .uls-compact-title {
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #1E293B !important;
        margin: 0 0 6px 0 !important;
        line-height: 1.3 !important;
        height: 34px !important;
        overflow: hidden !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
    }
    .uls-compact-title a {
        color: #1E293B !important;
        text-decoration: none !important;
    }
    .uls-compact-title a:hover {
        color: #0070F3 !important;
    }

    /* 3. Price Row */
    .uls-compact-price-row {
        display: flex !important;
        align-items: baseline !important;
        gap: 6px !important;
        margin-bottom: 8px !important;
        flex-wrap: wrap !important;
    }
    .uls-price-main {
        font-size: 15px !important;
        font-weight: 800 !important;
        color: #0F172A !important;
    }
    .uls-price-strikethrough {
        font-size: 11.5px !important;
        color: #94A3B8 !important;
        text-decoration: line-through !important;
    }

    /* 4. Bottom Row */
    .uls-compact-bottom-row {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 6px !important;
        padding-top: 2px !important;
    }
    .uls-rating-box {
        display: flex !important;
        align-items: center !important;
    }
    .uls-green-rating-badge {
        background: #388E3C !important;
        color: #ffffff !important;
        font-size: 11px !important;
        font-weight: 800 !important;
        padding: 2px 6px !important;
        border-radius: 4px !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 3px !important;
    }

    /* Add to Cart Icon Button */
    .uls-white-cart-btn {
        width: 32px !important;
        height: 32px !important;
        border-radius: 8px !important;
        background: #FFFFFF !important;
        border: 1.5px solid #CBD5E1 !important;
        color: #0070F3 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-size: 14px !important;
        cursor: pointer !important;
        flex-shrink: 0 !important;
        transition: all 0.22s ease !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04) !important;
    }
    .uls-white-cart-btn:hover {
        background: #0070F3 !important;
        border-color: #0070F3 !important;
        color: #FFFFFF !important;
        transform: scale(1.08) !important;
        box-shadow: 0 4px 12px rgba(0, 112, 243, 0.35) !important;
    }

    /* ══════════════════════════════════════════════════════════
       MOBILE RESPONSIVE: 2 CARDS PER ROW (max-width: 576px)
    ══════════════════════════════════════════════════════════ */
    @media (max-width: 576px) {
        .kc-prod-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }
        .uls-compact-card {
            padding: 8px !important;
            border-radius: 10px !important;
        }
        .uls-img-slide-wrap {
            height: 130px !important;
        }
        .uls-compact-title {
            font-size: 11.5px !important;
            height: 30px !important;
            margin-bottom: 4px !important;
        }
        .uls-price-main {
            font-size: 13.5px !important;
        }
        .uls-price-strikethrough {
            font-size: 10px !important;
        }
        .uls-white-cart-btn {
            width: 28px !important;
            height: 28px !important;
            font-size: 12px !important;
        }
    }
    </style>
</div>

<!-- AJAX Add to Cart Function with Toastr Notifications -->
<script>
function quickAddToCart(productId, btn) {
    if (!productId) return;

    // Toastr default options
    if (typeof toastr !== 'undefined') {
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
    }

    // Check if user is logged in
    if (typeof window.IS_USER_LOGGED_IN !== 'undefined' && !window.IS_USER_LOGGED_IN) {
        if (typeof toastr !== 'undefined') {
            toastr.warning('Please log in to add products to your cart!', 'Login Required');
        } else {
            alert('Please log in to add products to your cart!');
        }
        setTimeout(function() {
            window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.pathname + window.location.search);
        }, 1200);
        return;
    }

    var $btn = $(btn);
    var origHtml = $btn.html();
    $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

    $.ajax({
        url: 'cart.php?action=add',
        type: 'POST',
        data: { prdid: productId, pid: 0, qty: 1 },
        dataType: 'json',
        success: function(resp) {
            $btn.prop('disabled', false).html(origHtml);
            if (resp && resp.status == 3) {
                if (typeof toastr !== 'undefined') {
                    toastr.info('🛒 Item is already in your cart! Quantity updated.', 'Cart Updated');
                } else {
                    alert('🛒 Item is already in your cart! Quantity updated.');
                }
            } else if (resp && resp.status == 2) {
                if (typeof toastr !== 'undefined') {
                    toastr.success('✅ Item added to your cart!', 'Success');
                } else {
                    alert('✅ Item added to your cart!');
                }
            } else {
                if (typeof toastr !== 'undefined') {
                    toastr.success('✅ Item added to your cart!', 'Success');
                } else {
                    alert('✅ Item added to your cart!');
                }
            }
            if (resp && typeof resp.number_of_cart !== 'undefined') {
                $('.uls-badge, #cartCountBadge, #ulsCartBadge, .kc-cart-badge').text(resp.number_of_cart).show();
            }
        },
        error: function() {
            $btn.prop('disabled', false).html(origHtml);
            if (typeof toastr !== 'undefined') {
                toastr.error('Failed to update cart. Please try again.', 'Error');
            } else {
                alert('Failed to update cart. Please try again.');
            }
        }
    });
}
</script>