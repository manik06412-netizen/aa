<style>
/* ── Product Cards Grid (Related Products) ── */
.kc-prod-grid-item {
    margin-bottom: 24px !important;
}
.kc-product-card {
    background: #ffffff !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 14px !important;
    overflow: hidden !important;
    height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    position: relative !important;
}
.kc-product-card:hover {
    transform: translateY(-5px) !important;
    border-color: #0070F3 !important;
    box-shadow: 0 14px 30px rgba(0, 112, 243, 0.14) !important;
}
.kc-prod-img-wrap {
    position: relative !important;
    background: #F8FAFC !important;
    padding: 16px !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    height: 200px !important;
    overflow: hidden !important;
}
.kc-prod-img-wrap img {
    max-height: 100% !important;
    max-width: 100% !important;
    object-fit: contain !important;
    transition: transform 0.4s ease !important;
}
.kc-product-card:hover .kc-prod-img-wrap img {
    transform: scale(1.06) !important;
}
.kc-discount-badge {
    position: absolute !important;
    top: 10px !important;
    left: 10px !important;
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    font-size: 10.5px !important;
    font-weight: 800 !important;
    padding: 3px 8px !important;
    border-radius: 6px !important;
    z-index: 2 !important;
    letter-spacing: 0.3px !important;
    box-shadow: 0 2px 8px rgba(0, 188, 212, 0.3) !important;
}
.kc-prod-wish-btn {
    position: absolute !important;
    top: 10px !important;
    right: 10px !important;
    width: 32px !important;
    height: 32px !important;
    border-radius: 50% !important;
    background: #ffffff !important;
    border: 1px solid #E2E8F0 !important;
    color: #94A3B8 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    z-index: 2 !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
}
.kc-prod-wish-btn:hover, .kc-prod-wish-btn.active {
    background: #FFF1F2 !important;
    border-color: #FECDD3 !important;
    color: #E11D48 !important;
    transform: scale(1.1) !important;
}

/* Card Body */
.kc-prod-body {
    padding: 16px !important;
    display: flex !important;
    flex-direction: column !important;
    flex-grow: 1 !important;
}
.kc-prod-cat {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    color: #00BCD4 !important;
    margin-bottom: 4px !important;
    letter-spacing: 0.5px !important;
}
.kc-prod-title {
    font-size: 14px !important;
    font-weight: 700 !important;
    line-height: 1.4 !important;
    color: #0F172A !important;
    margin: 0 0 8px 0 !important;
    display: -webkit-box !important;
    -webkit-line-clamp: 2 !important;
    -webkit-box-orient: vertical !important;
    overflow: hidden !important;
    min-height: 38px !important;
    transition: color 0.2s ease !important;
}
.kc-prod-title a {
    color: inherit !important;
    text-decoration: none !important;
}
.kc-prod-title a:hover {
    color: #0070F3 !important;
}

/* Ratings */
.kc-prod-stars {
    color: #F59E0B !important;
    font-size: 12px !important;
    margin-bottom: 10px !important;
    display: flex !important;
    align-items: center !important;
    gap: 2px !important;
}
.kc-prod-stars span {
    font-size: 11px !important;
    font-weight: 600 !important;
    color: #94A3B8 !important;
    margin-left: 4px !important;
}

/* Price Box */
.kc-prod-price-box {
    margin-top: auto !important;
    display: flex !important;
    align-items: baseline !important;
    gap: 8px !important;
    margin-bottom: 12px !important;
}
.kc-curr-price {
    font-size: 19px !important;
    font-weight: 800 !important;
    color: #0D47A1 !important;
    font-family: 'Outfit', sans-serif !important;
}
.kc-old-price {
    font-size: 13px !important;
    color: #94A3B8 !important;
    text-decoration: line-through !important;
}

/* CTA Button */
.kc-prod-btn {
    background: linear-gradient(135deg, #0D47A1, #0070F3) !important;
    color: #ffffff !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    border-radius: 8px !important;
    padding: 9px 0 !important;
    text-align: center !important;
    display: block !important;
    text-decoration: none !important;
    transition: all 0.3s ease !important;
    border: none !important;
    width: 100% !important;
}
.kc-prod-btn:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    box-shadow: 0 4px 14px rgba(0, 112, 243, 0.35) !important;
    transform: translateY(-1px) !important;
    text-decoration: none !important;
}

@media (max-width: 767px) {
    .kc-prod-grid-item {
        flex: 0 0 50% !important;
        max-width: 50% !important;
        width: 50% !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
        margin-bottom: 12px !important;
    }
    .kc-product-card {
        border-radius: 12px !important;
    }
    .kc-prod-img-wrap {
        height: 130px !important;
        padding: 10px 6px !important;
    }
    .kc-prod-body {
        padding: 10px 8px !important;
    }
    .kc-prod-cat {
        font-size: 9px !important;
        margin-bottom: 2px !important;
    }
    .kc-prod-title {
        font-size: 12px !important;
        min-height: 32px !important;
        margin-bottom: 4px !important;
        line-height: 1.3 !important;
    }
    .kc-prod-stars {
        font-size: 9.5px !important;
        margin-bottom: 6px !important;
        gap: 1px !important;
    }
    .kc-prod-price-box {
        margin-bottom: 8px !important;
        gap: 4px !important;
        flex-wrap: wrap !important;
    }
    .kc-curr-price {
        font-size: 14px !important;
    }
    .kc-old-price {
        font-size: 10.5px !important;
    }
    .kc-prod-btn {
        font-size: 11px !important;
        padding: 6px 4px !important;
        border-radius: 6px !important;
    }
    .kc-discount-badge {
        font-size: 9px !important;
        padding: 2px 5px !important;
        top: 6px !important;
        left: 6px !important;
    }
    .kc-prod-wish-btn {
        width: 26px !important;
        height: 26px !important;
        font-size: 11px !important;
        top: 6px !important;
        right: 6px !important;
    }
}
@media (max-width: 420px) {
    .kc-prod-grid-item {
        padding-left: 3px !important;
        padding-right: 3px !important;
    }
    .kc-prod-img-wrap {
        height: 115px !important;
    }
    .kc-prod-title {
        font-size: 11px !important;
        min-height: 28px !important;
    }
    .kc-curr-price {
        font-size: 12.5px !important;
    }
}
</style>

<div class="row" id="response_for_the_table">
    <?php
    $rel_cat = isset($cat) ? $cat : '';
    $current_pr_id = isset($pr_id) ? $pr_id : 0;
    
    $fetcate = mysqli_query($con, "SELECT DISTINCT d.* FROM dishes d JOIN price p ON d.rs_id = p.pcode WHERE d.status='1' AND d.category='$rel_cat' AND d.rs_id != '$current_pr_id' LIMIT 4");
    if (!$fetcate || mysqli_num_rows($fetcate) == 0) {
        // Fallback to any products if no related category found
        $fetcate = mysqli_query($con, "SELECT DISTINCT d.* FROM dishes d JOIN price p ON d.rs_id = p.pcode WHERE d.status='1' AND d.rs_id != '$current_pr_id' LIMIT 4");
    }

    if ($fetcate && mysqli_num_rows($fetcate) > 0) {
        while ($rocat = mysqli_fetch_array($fetcate)) {
            $ct_img = $rocat['img'];
            $ct_name = $rocat['dish_name'];
            $ct_nid = $rocat['rs_id'];
            $ct_category = $rocat['category'];
            $ct_catid = $rocat['cateid'];
            $defaultRating = !empty($rocat['ratings']) ? (int)$rocat['ratings'] : 5;

            $cat_name = !empty($ct_category) ? $ct_category : 'Hardware';
            if (!empty($ct_catid)) {
                $ctt = mysqli_query($con, "SELECT c_name FROM res_category WHERE c_id='$ct_catid'");
                if ($ctt && $crow = mysqli_fetch_assoc($ctt)) {
                    $cat_name = $crow['c_name'];
                }
            }

            $gbb = mysqli_query($con, "SELECT * FROM price WHERE pcode='$ct_nid' ORDER BY pp ASC LIMIT 1");
            if ($gbb && $gb13 = mysqli_fetch_array($gbb)) {
                $currentp = $gb13['pp'];
                $oldp = $gb13['oprice'];
                $discount_per = !empty($gb13['discount']) ? $gb13['discount'] : (($oldp > $currentp && $oldp > 0) ? round((($oldp - $currentp) / $oldp) * 100) : 0);
            } else {
                $currentp = 0;
                $oldp = 0;
                $discount_per = 0;
            }

            $img_src = '';
            if (empty($ct_img)) {
                $img_src = 'img/karuda_logo.png';
            } else {
                $clean = preg_replace('#/+#', '/', ltrim($ct_img, './'));
                if (file_exists($clean)) $img_src = $clean;
                elseif (file_exists('avadmin/' . $clean)) $img_src = 'avadmin/' . $clean;
                elseif (file_exists('admin/' . $clean)) $img_src = 'admin/' . $clean;
                else $img_src = $clean;
            }

            $syl = 0;
            if (!empty($_SESSION['uid'])) {
                $ud1 = $_SESSION['uid'];
                $zqswl = mysqli_query($con, "SELECT * FROM watch_list WHERE userid='$ud1' AND pr_id='$ct_nid'");
                if ($zqswl && mysqli_num_rows($zqswl) > 0) $syl = 1;
            }
    ?>
    <div class="col-xl-3 col-lg-4 col-md-6 col-6 kc-prod-grid-item">
        <div class="kc-product-card">
            
            <!-- Image & Badges -->
            <div class="kc-prod-img-wrap">
                <?php if ($discount_per > 0) { ?>
                <span class="kc-discount-badge"><?= $discount_per; ?>% OFF</span>
                <?php } ?>
                
                <button type="button" class="kc-prod-wish-btn <?= ($syl == 1) ? 'active' : ''; ?>" 
                        onclick="toggle_Wishlist(<?= $ct_nid; ?>, '<?= htmlspecialchars($ct_img); ?>', '<?= htmlspecialchars($ct_name); ?>')" 
                        id="heart-icon1-<?= $ct_nid; ?>" title="Add to Wishlist">
                    <i class="fa <?= ($syl == 1) ? 'fa-heart' : 'fa-heart-o'; ?>"></i>
                </button>

                <a href="details.php?id=<?= htmlspecialchars($ct_nid); ?>" class="w-100 h-100 d-flex align-items-center justify-content-center">
                    <img src="<?= htmlspecialchars($img_src); ?>" alt="<?= htmlspecialchars($ct_name); ?>" onerror="this.src='img/karuda_logo.png'">
                </a>
            </div>

            <!-- Body -->
            <div class="kc-prod-body">
                <span class="kc-prod-cat"><?= htmlspecialchars($cat_name); ?></span>
                <h4 class="kc-prod-title">
                    <a href="details.php?id=<?= htmlspecialchars($ct_nid); ?>"><?= htmlspecialchars($ct_name); ?></a>
                </h4>

                <!-- Rating -->
                <div class="kc-prod-stars">
                    <?php
                    for ($st = 1; $st <= 5; $st++) {
                        if ($st <= $defaultRating) {
                            echo '<i class="fa fa-star"></i>';
                        } else {
                            echo '<i class="fa fa-star-o" style="color:#CBD5E1;"></i>';
                        }
                    }
                    ?>
                    <span>(<?= $defaultRating; ?>.0)</span>
                </div>

                <!-- Pricing -->
                <div class="kc-prod-price-box">
                    <span class="kc-curr-price">₹ <?= number_format($currentp); ?></span>
                    <?php if ($oldp > $currentp) { ?>
                    <span class="kc-old-price">₹ <?= number_format($oldp); ?></span>
                    <?php } ?>
                </div>

                <!-- CTA -->
                <a href="details.php?id=<?= htmlspecialchars($ct_nid); ?>" class="kc-prod-btn">
                    View Details
                </a>
            </div>

        </div>
    </div>
    <?php 
        }
    } else { 
    ?>
    <div class="col-12 text-center py-5">
        <i class="fa fa-box-open" style="font-size:48px; color:#CBD5E1; margin-bottom:12px;"></i>
        <h5 style="color:#64748B;">No related products found.</h5>
    </div>
    <?php } ?>
</div>
