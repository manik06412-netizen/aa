<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);
include "include/header.php";

$rawStatus = (string)($order['status'] ?? '0');
$statusStep = 1; // 1 = Placed, 2 = Processing, 3 = Packed, 4 = Dispatched, 5 = Delivered, 0 = Cancelled

if ($rawStatus === '0' || $rawStatus === 'Processing' || $rawStatus === '') {
    $statusStep = 2;
    $statusTitle = 'Order in Processing';
    $statusDesc = 'Your hardware order has been received and verified. Preparing components for assembly and testing.';
    $statusColor = '#00BCD4';
} elseif ($rawStatus === '1' || $rawStatus === 'Dispatched') {
    $statusStep = 4;
    $statusTitle = 'Dispatched / In Transit';
    $statusDesc = 'Your package has been securely packed in anti-static materials and handed over to BlueDart Express Courier.';
    $statusColor = '#0070F3';
} elseif ($rawStatus === '2' || $rawStatus === 'Completed' || $rawStatus === 'Delivered') {
    $statusStep = 5;
    $statusTitle = 'Delivered Successfully';
    $statusDesc = 'Hardware package delivered safely to the customer destination.';
    $statusColor = '#10B981';
} elseif ($rawStatus === '4' || $rawStatus === 'Cancelled') {
    $statusStep = 0;
    $statusTitle = 'Order Cancelled';
    $statusDesc = 'This order was cancelled. If you need any assistance or refund clarification, please contact our support desk.';
    $statusColor = '#E11D48';
}

$pId = !empty($order['product_id']) ? $order['product_id'] : ($order['pr_id'] ?? 1001);
$pName = !empty($order['dish_name']) ? htmlspecialchars($order['dish_name']) : (!empty($order['p_name']) ? htmlspecialchars($order['p_name']) : 'High-Performance Hardware');
$pImg = !empty($order['resolved_img']) ? htmlspecialchars($order['resolved_img']) : 'img/products/hp_laptop.jpg';
$catName = !empty($order['category']) ? htmlspecialchars($order['category']) : 'Components';
$unitPrice = (float)($order['sel_price'] ?? ($order['ct_py'] ?? 0));
$totalAmt = (float)($order['final_amt'] ?? ($order['total_amt'] ?? ($order['total'] ?? 0)));
if ($totalAmt <= 0) $totalAmt = $unitPrice;
$orderDate = !empty($order['order_date']) ? $order['order_date'] : (!empty($order['date']) ? $order['date'] : date('d-m-Y'));
$qty = (int)($order['qty'] ?? 1);
?>
<style>
/* ══════════════════════════════════════════════════════════════
   KARUDA COMPUTERS — CYBER TECH LIVE ORDER TRACKER
══════════════════════════════════════════════════════════════ */
#page {
    background: #F8FAFC !important;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

/* Banner */
.kc-track-banner {
    background: linear-gradient(135deg, #001A47 0%, #002566 50%, #003B95 100%) !important;
    padding: 36px 0 32px 0 !important;
    border-bottom: 3px solid #003B95 !important;
    box-shadow: 0 4px 20px rgba(0, 37, 102, 0.35);
}
.kc-track-title {
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #ffffff !important;
    margin: 0 !important;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}
.kc-track-title span {
    color: #60A5FA;
}

.kc-breadcrumb-nav {
    background: transparent !important;
    padding: 8px 0 0 0 !important;
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

/* Search Bar in Tracker */
.kc-track-search-form {
    display: flex;
    align-items: center;
    background: rgba(255, 255, 255, 0.08);
    border: 1.5px solid rgba(0, 188, 212, 0.4);
    border-radius: 12px;
    padding: 4px 6px;
    max-width: 400px;
    width: 100%;
    transition: all 0.3s ease;
}
.kc-track-search-form:focus-within {
    background: rgba(255, 255, 255, 0.15);
    border-color: #00BCD4;
    box-shadow: 0 0 15px rgba(0, 188, 212, 0.35);
}
.kc-track-search-input {
    background: transparent;
    border: none;
    outline: none;
    color: #ffffff;
    font-size: 14px;
    padding: 8px 12px;
    flex: 1;
}
.kc-track-search-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}
.kc-track-search-btn {
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    border: none;
    color: #ffffff;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}
.kc-track-search-btn:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4);
}

/* Main Container Card */
.kc-track-card {
    background: #ffffff;
    border: 1.5px solid #E2E8F0;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
    padding: 32px;
    margin-bottom: 30px;
}

/* Status Header Highlight */
.kc-status-hero {
    background: linear-gradient(135deg, #F8FAFC 0%, #EFF6FF 100%);
    border: 1.5px solid #DBEAFE;
    border-radius: 16px;
    padding: 24px;
    margin-bottom: 35px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}
.kc-status-hero-text h3 {
    font-size: 22px;
    font-weight: 800;
    color: #0F172A;
    margin: 0 0 6px 0;
    display: flex;
    align-items: center;
    gap: 10px;
}
.kc-status-hero-text p {
    font-size: 14px;
    color: #475569;
    margin: 0;
}
.kc-status-pill-lg {
    padding: 8px 20px;
    border-radius: 30px;
    font-size: 14px;
    font-weight: 800;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #00BCD4;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(0, 188, 212, 0.3);
}

/* Horizontal Timeline Stepper */
.kc-stepper-wrap {
    position: relative;
    padding: 30px 10px 40px 10px;
    margin-bottom: 30px;
}
.kc-stepper-progress-bar {
    position: absolute;
    top: 52px;
    left: 8%;
    right: 8%;
    height: 4px;
    background: #E2E8F0;
    z-index: 1;
    border-radius: 2px;
}
.kc-stepper-progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #10B981, #0070F3, #00BCD4);
    border-radius: 2px;
    transition: width 0.6s cubic-bezier(0.2, 0.8, 0.2, 1);
}
.kc-stepper-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    position: relative;
    z-index: 2;
    text-align: center;
}
.kc-step-node {
    display: flex;
    flex-direction: column;
    align-items: center;
}
.kc-step-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: #ffffff;
    border: 3px solid #CBD5E1;
    color: #94A3B8;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    margin-bottom: 12px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}
.kc-step-node.completed .kc-step-icon {
    background: #10B981;
    border-color: #10B981;
    color: #ffffff;
    box-shadow: 0 0 16px rgba(16, 185, 129, 0.4);
}
.kc-step-node.active .kc-step-icon {
    background: #0070F3;
    border-color: #00BCD4;
    color: #ffffff;
    box-shadow: 0 0 20px rgba(0, 112, 243, 0.5);
    transform: scale(1.15);
    animation: pulseStep 2s infinite;
}
@keyframes pulseStep {
    0%, 100% { box-shadow: 0 0 0 0 rgba(0, 188, 212, 0.4); }
    50% { box-shadow: 0 0 0 10px rgba(0, 188, 212, 0); }
}
.kc-step-label {
    font-size: 14px;
    font-weight: 700;
    color: #475569;
    margin-bottom: 4px;
}
.kc-step-node.completed .kc-step-label,
.kc-step-node.active .kc-step-label {
    color: #0F172A;
}
.kc-step-time {
    font-size: 12px;
    color: #94A3B8;
}

/* Two-Column Info Layout */
.kc-track-grid {
    display: grid;
    grid-template-columns: 1.3fr 1fr;
    gap: 25px;
}
@media (max-width: 991px) {
    .kc-track-grid { grid-template-columns: 1fr; }
    .kc-stepper-grid { grid-template-columns: repeat(3, 1fr); gap: 20px 0; }
    .kc-stepper-progress-bar { display: none; }
}

/* Product Info Box */
.kc-track-info-card {
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    padding: 20px;
    height: 100%;
}
.kc-track-card-title {
    font-size: 16px;
    font-weight: 800;
    color: #0F172A;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.kc-track-prod-strip {
    display: flex;
    align-items: center;
    gap: 16px;
    background: #ffffff;
    border: 1px solid #E2E8F0;
    border-radius: 12px;
    padding: 14px;
    margin-bottom: 16px;
}
.kc-track-prod-img {
    width: 75px;
    height: 75px;
    object-fit: contain;
    background: #F8FAFC;
    border-radius: 8px;
    padding: 4px;
}
.kc-track-prod-details h4 {
    font-size: 15px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 4px 0;
}
.kc-track-prod-details h4 a {
    color: #0F172A;
    text-decoration: none;
}
.kc-track-prod-details h4 a:hover {
    color: #0070F3;
}
.kc-meta-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    border-bottom: 1px dashed #E2E8F0;
    font-size: 13px;
    color: #475569;
}
.kc-meta-row:last-child {
    border-bottom: none;
}
.kc-meta-row b {
    color: #0F172A;
}

/* Logistics & Delivery Address Box */
.kc-logistics-box {
    background: linear-gradient(135deg, #0B192C, #1E293B);
    color: #ffffff;
    border-radius: 16px;
    padding: 24px;
    height: 100%;
    position: relative;
    overflow: hidden;
}
.kc-logistics-box::after {
    content: "\f48b";
    font-family: "Font Awesome 6 Free";
    font-weight: 900;
    position: absolute;
    right: -10px;
    bottom: -15px;
    font-size: 110px;
    color: rgba(255, 255, 255, 0.04);
    pointer-events: none;
}
.kc-logistics-title {
    font-size: 16px;
    font-weight: 800;
    color: #00BCD4;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Action Buttons Bar */
.kc-track-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1.5px solid #F1F5F9;
}
.kc-track-btn {
    padding: 10px 22px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none !important;
    transition: all 0.25s ease;
    cursor: pointer;
    border: none;
}
.kc-track-btn-primary {
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    color: #ffffff !important;
}
.kc-track-btn-primary:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4);
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0, 112, 243, 0.3);
}
.kc-track-btn-outline {
    background: #ffffff;
    color: #475569 !important;
    border: 1.5px solid #CBD5E1;
}
.kc-track-btn-outline:hover {
    border-color: #0070F3;
    color: #0070F3 !important;
    background: #EFF6FF;
}

/* Toast */
.kc-copy-toast {
    position: fixed;
    top: 25px;
    right: 25px;
    z-index: 999999;
    padding: 12px 20px;
    background: #065F46;
    border: 1.5px solid #34D399;
    color: #fff;
    font-weight: 700;
    border-radius: 10px;
    display: none;
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
</style>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <!-- Subheader Banner -->
        <div class="kc-track-banner">
            <div class="container-fluid px-lg-5 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h1 class="kc-track-title">Live Order <span>Tracking</span></h1>
                        <nav class="kc-breadcrumb-nav" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item"><a href="myorders.php">My Orders</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Track Order</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Search another order -->
                    <form class="kc-track-search-form" action="track_order.php" method="GET">
                        <input type="text" name="oid" class="kc-track-search-input" placeholder="Enter Order ID to Track..." value="<?= htmlspecialchars($orderId); ?>" required>
                        <button type="submit" class="kc-track-search-btn">
                            <i class="fa fa-truck-fast mr-1"></i> Track
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <main>
            <div class="container-fluid px-lg-5 px-3 py-5">
                
                <?php if ($order): ?>
                
                <!-- Main Tracking Card -->
                <div class="kc-track-card">
                    
                    <!-- Hero Status Strip -->
                    <div class="kc-status-hero">
                        <div class="kc-status-hero-text">
                            <h3>
                                <i class="fa <?= ($statusStep === 5 ? 'fa-circle-check text-success' : ($statusStep === 0 ? 'fa-circle-xmark text-danger' : 'fa-circle-notch fa-spin text-info')); ?>"></i>
                                <?= $statusTitle; ?>
                            </h3>
                            <p><?= $statusDesc; ?></p>
                        </div>
                        <div>
                            <span class="kc-status-pill-lg" style="background:<?= $statusColor; ?>;">
                                <i class="fa fa-hashtag"></i> <?= htmlspecialchars($orderId); ?>
                            </span>
                        </div>
                    </div>

                    <!-- 5-Step Visual Stepper -->
                    <div class="kc-stepper-wrap">
                        <div class="kc-stepper-progress-bar">
                            <?php 
                            $fillPct = 0;
                            if ($statusStep === 1) $fillPct = 10;
                            elseif ($statusStep === 2) $fillPct = 35;
                            elseif ($statusStep === 3) $fillPct = 60;
                            elseif ($statusStep === 4) $fillPct = 85;
                            elseif ($statusStep === 5) $fillPct = 100;
                            ?>
                            <div class="kc-stepper-progress-fill" style="width: <?= $fillPct; ?>%;"></div>
                        </div>

                        <div class="kc-stepper-grid">
                            <!-- Step 1: Order Placed -->
                            <div class="kc-step-node <?= ($statusStep >= 1 ? ($statusStep == 1 ? 'active' : 'completed') : ''); ?>">
                                <div class="kc-step-icon"><i class="fa fa-clipboard-check"></i></div>
                                <span class="kc-step-label">Order Placed</span>
                                <span class="kc-step-time"><?= $orderDate; ?></span>
                            </div>

                            <!-- Step 2: In Processing -->
                            <div class="kc-step-node <?= ($statusStep >= 2 ? ($statusStep == 2 ? 'active' : 'completed') : ''); ?>">
                                <div class="kc-step-icon"><i class="fa fa-microchip"></i></div>
                                <span class="kc-step-label">Processing</span>
                                <span class="kc-step-time">Component Check</span>
                            </div>

                            <!-- Step 3: Packed & Tested -->
                            <div class="kc-step-node <?= ($statusStep >= 3 ? ($statusStep == 3 ? 'active' : 'completed') : ''); ?>">
                                <div class="kc-step-icon"><i class="fa fa-box-open"></i></div>
                                <span class="kc-step-label">Quality Packing</span>
                                <span class="kc-step-time">Anti-Static Sealed</span>
                            </div>

                            <!-- Step 4: Dispatched -->
                            <div class="kc-step-node <?= ($statusStep >= 4 ? ($statusStep == 4 ? 'active' : 'completed') : ''); ?>">
                                <div class="kc-step-icon"><i class="fa fa-truck-fast"></i></div>
                                <span class="kc-step-label">In Transit</span>
                                <span class="kc-step-time">Express Delivery</span>
                            </div>

                            <!-- Step 5: Delivered -->
                            <div class="kc-step-node <?= ($statusStep >= 5 ? 'completed' : ''); ?>">
                                <div class="kc-step-icon"><i class="fa fa-house-chimney-check"></i></div>
                                <span class="kc-step-label">Delivered</span>
                                <span class="kc-step-time">Customer Handover</span>
                            </div>
                        </div>
                    </div>

                    <!-- Details 2-Column Grid -->
                    <div class="kc-track-grid">
                        
                        <!-- Left: Hardware Order Summary -->
                        <div class="kc-track-info-card">
                            <h4 class="kc-track-card-title">
                                <i class="fa fa-desktop text-primary"></i> Ordered Hardware Item
                            </h4>

                            <div class="kc-track-prod-strip">
                                <img src="<?= $pImg; ?>" alt="<?= $pName; ?>" class="kc-track-prod-img" onerror="this.src='img/products/hp_laptop.jpg'">
                                <div class="kc-track-prod-details">
                                    <span class="badge badge-light text-primary mb-1" style="font-size:11px; font-weight:700;"><?= $catName; ?></span>
                                    <h4><a href="details.php?id=<?= $pId; ?>"><?= $pName; ?></a></h4>
                                    <span style="font-size:13px; color:#64748B;">Quantity: <b><?= $qty; ?> Unit(s)</b></span>
                                </div>
                            </div>

                            <div class="kc-meta-row">
                                <span>Order Reference ID:</span>
                                <span><b>#<?= htmlspecialchars($orderId); ?></b> <i class="fa fa-copy text-muted" style="cursor:pointer;" onclick="copyTrackId('<?= $orderId; ?>')"></i></span>
                            </div>
                            <div class="kc-meta-row">
                                <span>Order Date:</span>
                                <span><b><?= $orderDate; ?></b></span>
                            </div>
                            <div class="kc-meta-row">
                                <span>Payment Status:</span>
                                <span class="text-success"><b><i class="fa fa-shield-check mr-1"></i> Paid Online / Confirmed</b></span>
                            </div>
                            <div class="kc-meta-row">
                                <span>Total Amount:</span>
                                <span style="font-size:16px; font-weight:800; color:#0D47A1;">₹<?= number_format($totalAmt, 2); ?></span>
                            </div>
                        </div>

                        <!-- Right: Logistics & Delivery Dispatch Info -->
                        <div class="kc-logistics-box">
                            <h4 class="kc-logistics-title">
                                <i class="fa fa-truck-ramp-box"></i> Logistics & Courier Partner
                            </h4>

                            <div style="font-size:14px; margin-bottom:14px;">
                                <div style="color:rgba(255,255,255,0.7); font-size:12px;">Courier Partner</div>
                                <div style="font-weight:700; font-size:16px; color:#ffffff;">BlueDart / DTDC Express Cargo</div>
                            </div>

                            <div style="font-size:14px; margin-bottom:14px;">
                                <div style="color:rgba(255,255,255,0.7); font-size:12px;">Estimated Delivery Window</div>
                                <div style="font-weight:700; font-size:15px; color:#38BDF8;">
                                    <i class="fa fa-calendar-check mr-1"></i> 3 - 5 Business Days
                                </div>
                            </div>

                            <div style="font-size:14px; border-top:1px solid rgba(255,255,255,0.15); padding-top:14px;">
                                <div style="color:rgba(255,255,255,0.7); font-size:12px;">Delivery Destination</div>
                                <div style="font-weight:600; color:#ffffff; font-size:13px; margin-top:2px;">
                                    <?php if ($address): ?>
                                        <?= htmlspecialchars($address['name'] ?? 'Customer'); ?><br>
                                        <?= htmlspecialchars($address['address'] ?? ''); ?>, <?= htmlspecialchars($address['city'] ?? ''); ?> - <?= htmlspecialchars($address['pincode'] ?? ''); ?>
                                    <?php else: ?>
                                        Customer Registered Address (Verified at Checkout)
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Bottom Action Buttons -->
                    <div class="kc-track-actions">
                        <a href="myorders.php" class="kc-track-btn kc-track-btn-primary">
                            <i class="fa fa-arrow-left"></i> Back to My Orders
                        </a>
                        <a href="allproducts.php" class="kc-track-btn kc-track-btn-outline">
                            <i class="fa fa-store"></i> Continue Shopping
                        </a>
                        <a href="contact.php" class="kc-track-btn kc-track-btn-outline">
                            <i class="fa fa-headset"></i> Contact Tech Support
                        </a>
                    </div>

                </div>

                <?php else: ?>

                <!-- Order Not Found State -->
                <div class="kc-track-card text-center py-5">
                    <div style="width:80px; height:80px; border-radius:50%; background:#EFF6FF; color:#0070F3; display:inline-flex; align-items:center; justify-content:center; font-size:36px; margin-bottom:20px;">
                        <i class="fa fa-magnifying-glass"></i>
                    </div>
                    <h2 style="font-weight:800; color:#0F172A; font-size:22px; margin-bottom:8px;">No Order Found for Tracking</h2>
                    <p style="color:#64748B; font-size:14px; max-width:500px; margin:0 auto 24px auto;">
                        Please check your Order ID from your confirmation email or order history and try searching again.
                    </p>
                    <a href="myorders.php" class="kc-track-btn kc-track-btn-primary">
                        <i class="fa fa-list-check"></i> View My Orders History
                    </a>
                </div>

                <?php endif; ?>

            </div>
        </main>

        <div id="kcCopyToast" class="kc-copy-toast">
            <i class="fa fa-circle-check mr-1"></i> Order ID copied to clipboard!
        </div>

        <script>
        function copyTrackId(id) {
            navigator.clipboard.writeText(id).then(function() {
                var toast = document.getElementById('kcCopyToast');
                toast.style.display = 'block';
                setTimeout(function() { toast.style.display = 'none'; }, 2500);
            });
        }
        </script>

        <!-- Standalone Trust Propositions Strip & Footer -->
        <?php include('include/trust_banner.php'); ?>
        <?php include('include/footer.php'); ?>
    </div>

    <?php include('include/sign_footer.php'); ?>
</body>
</html>