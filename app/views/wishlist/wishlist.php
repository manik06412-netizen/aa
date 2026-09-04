<?php 
require('include/header.php');
?>
<style>
/* ══════════════════════════════════════════════════════════════
   KARUDA COMPUTERS — MY WISHLIST CYBER TECH STYLING
══════════════════════════════════════════════════════════════ */
#page {
    background: #F8FAFC !important;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

/* Subheader Banner */
.kc-wish-banner {
    background: linear-gradient(135deg, #001A47 0%, #002566 50%, #003B95 100%) !important;
    padding: 36px 0 32px 0 !important;
    border-bottom: 3px solid #003B95 !important;
    position: relative;
    box-shadow: 0 4px 20px rgba(0, 37, 102, 0.35);
}
.kc-wish-title {
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #ffffff !important;
    margin: 0 !important;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.kc-wish-title span {
    color: #60A5FA;
}

/* Breadcrumb */
.kc-breadcrumb-nav {
    background: transparent !important;
    padding: 12px 0 0 0 !important;
}
.kc-breadcrumb-nav .breadcrumb {
    background: transparent !important;
    padding: 0 !important;
    margin: 0 !important;
    font-size: 13px !important;
}
.kc-breadcrumb-nav .breadcrumb a {
    color: rgba(255, 255, 255, 0.7) !important;
    text-decoration: none;
}
.kc-breadcrumb-nav .breadcrumb .active {
    color: #00BCD4 !important;
    font-weight: 600;
}

/* Wishlist Cards */
.kc-wish-card {
    background: #ffffff !important;
    border: 1.5px solid #E2E8F0 !important;
    border-radius: 14px !important;
    padding: 16px !important;
    display: flex !important;
    flex-direction: column !important;
    height: 100% !important;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03) !important;
    position: relative !important;
}
.kc-wish-card:hover {
    border-color: #0070F3 !important;
    transform: translateY(-4px) !important;
    box-shadow: 0 14px 30px rgba(0, 112, 243, 0.12) !important;
}
.kc-wish-img-box {
    position: relative;
    width: 100%;
    height: 190px;
    background: #F8FAFC;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-bottom: 14px;
    padding: 10px;
}
.kc-wish-img-box img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}
.kc-wish-card:hover .kc-wish-img-box img {
    transform: scale(1.06);
}
.kc-wish-remove-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #FFF1F2;
    border: 1px solid #FECDD3;
    color: #E11D48;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 5;
    transition: all 0.2s ease;
}
.kc-wish-remove-btn:hover {
    background: #E11D48;
    color: #ffffff;
    border-color: #E11D48;
    transform: scale(1.1);
}
.kc-wish-cat-tag {
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    color: #0070F3 !important;
    background: #EFF6FF !important;
    padding: 2px 8px !important;
    border-radius: 4px !important;
    display: inline-block !important;
    margin-bottom: 6px !important;
}
.kc-wish-prod-title {
    font-size: 15px !important;
    font-weight: 700 !important;
    color: #0F172A !important;
    margin: 0 0 8px 0 !important;
    line-height: 1.35 !important;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.kc-wish-prod-title a {
    color: #0F172A !important;
    text-decoration: none !important;
    transition: color 0.2s ease;
}
.kc-wish-prod-title a:hover {
    color: #0070F3 !important;
}
.kc-wish-price-strip {
    display: flex;
    align-items: baseline;
    gap: 8px;
    margin-top: auto;
    margin-bottom: 14px;
}
.kc-wish-price-curr {
    font-size: 20px !important;
    font-weight: 800 !important;
    color: #0D47A1 !important;
}
.kc-wish-price-old {
    font-size: 13px !important;
    color: #94A3B8 !important;
    text-decoration: line-through !important;
}
.kc-wish-cart-btn {
    background: linear-gradient(135deg, #0D47A1, #0070F3) !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 8px !important;
    padding: 10px 16px !important;
    font-size: 13px !important;
    font-weight: 700 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 6px !important;
    text-decoration: none !important;
    transition: all 0.2s ease !important;
    width: 100% !important;
}
.kc-wish-cart-btn:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4) !important;
    color: #ffffff !important;
    text-decoration: none !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 112, 243, 0.3);
}

/* Empty State Card */
.kc-empty-wishlist-box {
    background: #ffffff;
    border: 1.5px dashed #CBD5E1;
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
    margin: 40px auto;
    max-width: 600px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
}
.kc-empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #FFF1F2;
    color: #E11D48;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin-bottom: 20px;
}
.kc-empty-title {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 8px;
}
.kc-empty-sub {
    font-size: 14px;
    color: #64748B;
    margin-bottom: 24px;
}
.kc-empty-cta {
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    color: #ffffff;
    padding: 12px 28px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
}
.kc-empty-cta:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4);
    color: #ffffff;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 112, 243, 0.35);
}
</style>

<body>
    <div id="page">
        
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <!-- Subheader Banner -->
        <div class="kc-wish-banner">
            <div class="container-fluid px-lg-5 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div>
                        <h1 class="kc-wish-title">My <span>Wishlist</span></h1>
                        <nav class="kc-breadcrumb-nav" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                            </ol>
                        </nav>
                    </div>
                    <div>
                        <span class="badge" style="background:rgba(0,188,212,0.15); border:1px solid #00BCD4; color:#00E5FF; font-size:13px; padding:8px 16px; border-radius:20px;">
                            <i class="fa fa-heart mr-1" style="color:#E11D48;"></i> <?= count($items); ?> Saved Hardware Item(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <main>
            <div class="container-fluid px-lg-5 px-3 py-5">
                
                <?php if (!empty($items)): ?>
                <div class="row g-4" id="wishlist_grid">
                    <?php foreach ($items as $item): 
                        $p_id = !empty($item['rs_id']) ? (int)$item['rs_id'] : (!empty($item['d_id']) ? (int)$item['d_id'] : (int)$item['pr_id']);
                        $p_name = htmlspecialchars($item['dish_name'] ?? 'Hardware Component');
                        $p_img = htmlspecialchars($item['resolved_img'] ?? 'img/products/hp_laptop.jpg');
                        $cat_name = htmlspecialchars($item['category'] ?? 'Hardware');
                        $price = (float)($item['pp'] ?? 0);
                        $oprice = (float)($item['oprice'] ?? 0);
                        if ($price <= 0) $price = 1500;
                        if ($oprice <= $price) $oprice = $price * 1.2;
                        $detailUrl = "details.php?id=" . $p_id;
                    ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-4" id="wish_item_<?= $p_id; ?>">
                        <div class="kc-wish-card">
                            
                            <!-- Image Box with Remove Button -->
                            <div class="kc-wish-img-box">
                                <button type="button" class="kc-wish-remove-btn" onclick="Remove_Product(<?= $p_id; ?>)" title="Remove from Wishlist">
                                    <i class="fa fa-trash-can"></i>
                                </button>
                                <a href="<?= $detailUrl; ?>" class="w-100 h-100 d-flex align-items-center justify-content-center">
                                    <img src="<?= $p_img; ?>" alt="<?= $p_name; ?>" onerror="this.src='img/products/hp_laptop.jpg'">
                                </a>
                            </div>

                            <!-- Body -->
                            <div>
                                <span class="kc-wish-cat-tag"><?= $cat_name; ?></span>
                                <h3 class="kc-wish-prod-title">
                                    <a href="<?= $detailUrl; ?>"><?= $p_name; ?></a>
                                </h3>
                            </div>

                            <!-- Pricing -->
                            <div class="kc-wish-price-strip">
                                <span class="kc-wish-price-curr">₹<?= number_format($price, 2); ?></span>
                                <?php if ($oprice > $price): ?>
                                <span class="kc-wish-price-old">₹<?= number_format($oprice, 2); ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Action Buttons: View Details & Buy -->
                            <div class="d-flex gap-2 mt-3">
                                <a href="<?= $detailUrl; ?>" class="kc-wish-cart-btn w-100 text-center" style="text-decoration:none;">
                                    <i class="fa fa-eye mr-1"></i> View Details & Buy
                                </a>
                            </div>

                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php else: ?>
                <!-- Empty State -->
                <div class="kc-empty-wishlist-box">
                    <div class="kc-empty-icon">
                        <i class="fa fa-heart-crack"></i>
                    </div>
                    <h2 class="kc-empty-title">Your Wishlist is Empty</h2>
                    <p class="kc-empty-sub">
                        Explore our catalogue of laptops, custom prebuilt gaming PCs, and workstation hardware to add your favorites!
                    </p>
                    <a href="allproducts.php" class="kc-empty-cta">
                        <i class="fa fa-layer-group"></i> Explore All Hardware
                    </a>
                </div>
                <?php endif; ?>

            </div>
        </main>

        <script>
        async function Remove_Product(id_name) {
            if (!confirm('Are you sure you want to remove this item from your Wishlist?')) return;

            try {
                let formData = new FormData();
                formData.append('p_id', id_name);
                let response = await fetch('remove_wishlist.php', {
                    method: 'POST',
                    body: formData
                });
                let result = await response.json();
                if (result.status === 1) {
                    let card = document.getElementById('wish_item_' + id_name);
                    if (card) {
                        card.style.transition = 'all 0.3s ease';
                        card.style.transform = 'scale(0.8)';
                        card.style.opacity = '0';
                        setTimeout(() => { card.remove(); window.location.reload(); }, 300);
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert('Error removing item from wishlist');
                }
            } catch (err) {
                console.error(err);
                window.location.reload();
            }
        }
        </script>

        <!-- Standalone Trust Banner & Footer -->
        <?php include('include/trust_banner.php'); ?>
        <?php include('include/footer.php'); ?>
    </div>
    
    <?php include('include/sign_footer.php'); ?>
</body>
</html>