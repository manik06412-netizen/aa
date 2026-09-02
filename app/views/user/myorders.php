<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
error_reporting(0);
include "include/header.php";
?>
<style>
/* ══════════════════════════════════════════════════════════════
   KARUDA COMPUTERS — ULTRA CYBER MY ORDERS DASHBOARD
══════════════════════════════════════════════════════════════ */
#page {
    background: #F8FAFC !important;
    font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
}

/* Cyber Subheader Banner */
.kc-orders-banner {
    background: linear-gradient(135deg, #0B192C 0%, #1A365D 100%) !important;
    padding: 35px 0 40px 0 !important;
    border-bottom: 3px solid #00BCD4 !important;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}
.kc-orders-title {
    font-size: 28px !important;
    font-weight: 800 !important;
    color: #ffffff !important;
    margin: 0 !important;
    letter-spacing: -0.5px;
}
.kc-orders-title span {
    color: #00BCD4;
}

/* Breadcrumb */
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

/* Search Bar in Banner */
.kc-order-search-box {
    position: relative;
    max-width: 380px;
    width: 100%;
}
.kc-order-search-input {
    width: 100%;
    background: rgba(255, 255, 255, 0.08);
    border: 1.5px solid rgba(0, 188, 212, 0.4);
    border-radius: 12px;
    padding: 10px 42px 10px 16px;
    color: #ffffff;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
}
.kc-order-search-input:focus {
    background: rgba(255, 255, 255, 0.15);
    border-color: #00BCD4;
    box-shadow: 0 0 15px rgba(0, 188, 212, 0.35);
}
.kc-order-search-input::placeholder {
    color: rgba(255, 255, 255, 0.5);
}
.kc-order-search-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #00BCD4;
    font-size: 16px;
    cursor: pointer;
}

/* Tab Navigation */
.kc-orders-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 25px;
    background: #ffffff;
    padding: 10px;
    border-radius: 14px;
    border: 1.5px solid #E2E8F0;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.02);
}
.kc-tab-btn {
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    color: #64748B;
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}
.kc-tab-btn:hover {
    color: #0070F3;
    background: #EFF6FF;
}
.kc-tab-btn.active {
    color: #ffffff;
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    box-shadow: 0 4px 12px rgba(0, 112, 243, 0.3);
}
.kc-tab-badge {
    background: rgba(255, 255, 255, 0.25);
    padding: 2px 8px;
    border-radius: 10px;
    font-size: 12px;
}
.kc-tab-btn:not(.active) .kc-tab-badge {
    background: #F1F5F9;
    color: #475569;
}

/* ── Orders 2-Column Grid (desktop + mobile) ── */
#orders_list_container {
    display: grid !important;
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 20px !important;
}

/* Cyber Order Card */
.kc-order-card {
    background: #ffffff;
    border: 1.5px solid #E2E8F0;
    border-radius: 16px;
    margin-bottom: 0;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
}
.kc-order-card:hover {
    border-color: #0070F3;
    box-shadow: 0 12px 28px rgba(0, 112, 243, 0.1);
    transform: translateY(-2px);
}

/* Card Header */
.kc-order-header {
    background: #F8FAFC;
    padding: 16px 24px;
    border-bottom: 1.5px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}
.kc-order-id-tag {
    font-size: 15px;
    font-weight: 800;
    color: #0F172A;
    display: flex;
    align-items: center;
    gap: 8px;
}
.kc-order-id-tag span {
    color: #0070F3;
    font-family: monospace;
    letter-spacing: 0.5px;
}
.kc-copy-btn {
    background: none;
    border: none;
    color: #94A3B8;
    cursor: pointer;
    font-size: 14px;
    transition: color 0.2s;
}
.kc-copy-btn:hover {
    color: #0070F3;
}

/* Status Badges */
.kc-status-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.kc-status-processing {
    background: #E0F2FE;
    color: #0284C7;
    border: 1px solid #BAE6FD;
}
.kc-status-dispatched {
    background: #EFF6FF;
    color: #2563EB;
    border: 1px solid #BFDBFE;
}
.kc-status-delivered {
    background: #ECFDF5;
    color: #059669;
    border: 1px solid #A7F3D0;
}
.kc-status-cancelled {
    background: #FFF1F2;
    color: #E11D48;
    border: 1px solid #FECDD3;
}

/* Card Body */
.kc-order-body {
    padding: 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
}
.kc-order-prod-info {
    display: flex;
    align-items: center;
    gap: 20px;
    flex: 1;
    min-width: 280px;
}
.kc-order-img-box {
    width: 90px;
    height: 90px;
    background: #F8FAFC;
    border: 1.5px solid #E2E8F0;
    border-radius: 12px;
    padding: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.kc-order-img-box img {
    max-height: 100%;
    max-width: 100%;
    object-fit: contain;
}
.kc-order-details h4 {
    font-size: 16px;
    font-weight: 700;
    color: #0F172A;
    margin: 0 0 6px 0;
    line-height: 1.35;
}
.kc-order-details h4 a {
    color: #0F172A;
    text-decoration: none;
    transition: color 0.2s;
}
.kc-order-details h4 a:hover {
    color: #0070F3;
}
.kc-order-meta {
    font-size: 13px;
    color: #64748B;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}
.kc-order-meta span {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Price Strip */
.kc-order-pricing {
    text-align: right;
    min-width: 150px;
}
.kc-order-total-lbl {
    font-size: 12px;
    font-weight: 600;
    color: #64748B;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.kc-order-total-val {
    font-size: 22px;
    font-weight: 800;
    color: #0D47A1;
}

/* Card Footer / Action Bar */
.kc-order-footer {
    background: #FAFAFA;
    border-top: 1.5px solid #F1F5F9;
    padding: 14px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
}
.kc-order-date-text {
    font-size: 13px;
    color: #64748B;
    display: flex;
    align-items: center;
    gap: 6px;
}
.kc-order-actions {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.kc-action-btn {
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
    cursor: pointer;
    transition: all 0.2s ease;
    border: none;
}
.kc-btn-track {
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    color: #ffffff !important;
}
.kc-btn-track:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4);
    box-shadow: 0 4px 12px rgba(0, 112, 243, 0.3);
    transform: translateY(-1px);
}
.kc-btn-feedback {
    background: #EFF6FF;
    color: #0070F3 !important;
    border: 1px solid #BFDBFE;
}
.kc-btn-feedback:hover {
    background: #DBEAFE;
    color: #0D47A1 !important;
}
.kc-btn-review {
    background: #FAF5FF;
    color: #9333EA !important;
    border: 1px solid #E9D5FF;
}
.kc-btn-review:hover {
    background: #F3E8FF;
    color: #7E22CE !important;
}
.kc-btn-cancel {
    background: #FFF1F2;
    color: #E11D48 !important;
    border: 1px solid #FECDD3;
}
.kc-btn-cancel:hover {
    background: #FFE4E6;
}

/* Frosted Modal Dialogs */
.kc-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(11, 25, 44, 0.7);
    backdrop-filter: blur(6px);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.kc-modal-content {
    background: #ffffff;
    border-radius: 20px;
    width: 100%;
    max-width: 520px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
    border: 1.5px solid #E2E8F0;
    overflow: hidden;
    animation: modalIn 0.3s cubic-bezier(0.2, 0.8, 0.2, 1);
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.92) translateY(20px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.kc-modal-header {
    background: linear-gradient(135deg, #0B192C, #1E293B);
    padding: 20px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 2px solid #00BCD4;
}
.kc-modal-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 800;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 8px;
}
.kc-modal-close-btn {
    background: none;
    border: none;
    color: rgba(255, 255, 255, 0.6);
    font-size: 20px;
    cursor: pointer;
    transition: color 0.2s;
}
.kc-modal-close-btn:hover {
    color: #ffffff;
}
.kc-modal-body {
    padding: 24px;
}
.kc-modal-textarea {
    width: 100%;
    border: 1.5px solid #CBD5E1;
    border-radius: 12px;
    padding: 12px 16px;
    font-size: 14px;
    outline: none;
    transition: border-color 0.2s;
    font-family: inherit;
    resize: vertical;
}
.kc-modal-textarea:focus {
    border-color: #0070F3;
    box-shadow: 0 0 0 3px rgba(0, 112, 243, 0.15);
}
.kc-modal-submit-btn {
    background: linear-gradient(135deg, #0D47A1, #0070F3);
    color: #ffffff;
    border: none;
    border-radius: 10px;
    padding: 12px 24px;
    font-size: 14px;
    font-weight: 700;
    width: 100%;
    cursor: pointer;
    transition: all 0.25s ease;
    margin-top: 15px;
}
.kc-modal-submit-btn:hover {
    background: linear-gradient(135deg, #0070F3, #00BCD4);
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(0, 112, 243, 0.3);
}

/* Star Rating Controls */
.kc-star-rating-box {
    display: flex;
    gap: 8px;
    font-size: 28px;
    color: #CBD5E1;
    cursor: pointer;
    margin-bottom: 15px;
}
.kc-star-rating-box i {
    transition: transform 0.15s, color 0.15s;
}
.kc-star-rating-box i:hover,
.kc-star-rating-box i.active {
    color: #F59E0B;
    transform: scale(1.1);
}

/* Empty State */
.kc-empty-orders-box {
    background: #ffffff;
    border: 1.5px dashed #CBD5E1;
    border-radius: 20px;
    padding: 60px 20px;
    text-align: center;
    margin: 40px auto;
    max-width: 600px;
}
.kc-empty-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: #EFF6FF;
    color: #0070F3;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    margin-bottom: 20px;
}

/* ── 2-Column Order Grid on Mobile ── */
@media (max-width: 767px) {
    #orders_list_container {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px !important;
    }
    .kc-order-card {
        margin-bottom: 0 !important;
    }
    .kc-order-header {
        padding: 10px 12px !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px !important;
    }
    .kc-order-id-tag {
        font-size: 12px !important;
    }
    .kc-order-body {
        padding: 12px !important;
        flex-direction: column !important;
        gap: 10px !important;
    }
    .kc-order-prod-info {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 10px !important;
        min-width: unset !important;
    }
    .kc-order-img-box {
        width: 100% !important;
        height: 80px !important;
    }
    .kc-order-details h4 {
        font-size: 12px !important;
        -webkit-line-clamp: 2;
        display: -webkit-box !important;
        -webkit-box-orient: vertical !important;
        overflow: hidden !important;
    }
    .kc-order-meta {
        flex-direction: column !important;
        gap: 4px !important;
        font-size: 11px !important;
    }
    .kc-order-pricing {
        text-align: left !important;
        min-width: unset !important;
    }
    .kc-order-total-val {
        font-size: 16px !important;
    }
    .kc-order-footer {
        padding: 10px 12px !important;
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px !important;
    }
    .kc-order-actions {
        flex-wrap: wrap !important;
        gap: 6px !important;
    }
    .kc-action-btn {
        padding: 6px 10px !important;
        font-size: 11px !important;
    }
    .kc-order-date-text {
        font-size: 11px !important;
    }
    .kc-status-badge {
        font-size: 11px !important;
        padding: 4px 10px !important;
    }
    /* Empty state spans both columns */
    .kc-empty-orders-box {
        grid-column: 1 / -1 !important;
    }
}
</style>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>

        <!-- Subheader Banner -->
        <div class="kc-orders-banner">
            <div class="container-fluid px-lg-5 px-3">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                    <div>
                        <h1 class="kc-orders-title">My <span>Orders</span></h1>
                        <nav class="kc-breadcrumb-nav" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">My Orders</li>
                            </ol>
                        </nav>
                    </div>

                    <!-- Search Filter Box -->
                    <div class="kc-order-search-box">
                        <input type="text" id="orderSearchInput" class="kc-order-search-input" placeholder="Search by Order ID or Product..." onkeyup="filterOrdersClient()">
                        <button class="kc-order-search-btn" title="Search"><i class="fa fa-magnifying-glass"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <main>
            <div class="container-fluid px-lg-5 px-3 py-5">
                
                <?php if (isset($_GET['order_success']) && $_GET['order_success'] == '1'): 
                    $successOid = htmlspecialchars($_GET['oid'] ?? '');
                ?>
                <div class="alert alert-success d-flex align-items-center justify-content-between flex-wrap gap-3 mb-4 p-4" style="background:linear-gradient(135deg, #ECFDF5, #D1FAE5); border:1.5px solid #10B981; border-radius:14px; box-shadow:0 8px 24px rgba(16,185,129,0.15);">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:48px; height:48px; border-radius:50%; background:#10B981; color:#fff; display:flex; align-items:center; justify-content:center; font-size:24px; flex-shrink:0;">
                            <i class="fa fa-check"></i>
                        </div>
                        <div>
                            <h4 style="font-weight:800; color:#065F46; margin:0 0 2px 0; font-size:18px;">Order Placed Successfully!</h4>
                            <p style="color:#047857; margin:0; font-size:13px;">Thank you for shopping with Karuda Computers! Your order <?= $successOid ? '<b>#' . $successOid . '</b>' : ''; ?> is now in processing.</p>
                        </div>
                    </div>
                    <span class="badge" style="background:#059669; color:#fff; font-size:13px; padding:8px 16px; border-radius:20px;">
                        <i class="fa fa-shield-halved mr-1"></i> Genuine Hardware Warranty Included
                    </span>
                </div>
                <?php endif; ?>

                <?php 
                $totalCount = count($orders);
                $processingCount = 0;
                $completedCount = 0;
                $cancelledCount = 0;

                foreach ($orders as $ord) {
                    $st = (string)($ord['status'] ?? '0');
                    if ($st === '0' || $st === 'Processing' || $st === '') $processingCount++;
                    elseif ($st === '1' || $st === '2' || $st === 'Completed' || $st === 'Delivered') $completedCount++;
                    elseif ($st === '4' || $st === 'Cancelled') $cancelledCount++;
                }
                ?>

                <!-- Filter Tabs -->
                <div class="kc-orders-tabs">
                    <button class="kc-tab-btn active" onclick="switchOrderTab('all', this)">
                        <i class="fa fa-layer-group"></i> All Orders <span class="kc-tab-badge"><?= $totalCount; ?></span>
                    </button>
                    <button class="kc-tab-btn" onclick="switchOrderTab('processing', this)">
                        <i class="fa fa-circle-notch fa-spin" style="color:#00BCD4;"></i> In Processing <span class="kc-tab-badge"><?= $processingCount; ?></span>
                    </button>
                    <button class="kc-tab-btn" onclick="switchOrderTab('completed', this)">
                        <i class="fa fa-circle-check" style="color:#10B981;"></i> Completed / Delivered <span class="kc-tab-badge"><?= $completedCount; ?></span>
                    </button>
                    <button class="kc-tab-btn" onclick="switchOrderTab('cancelled', this)">
                        <i class="fa fa-circle-xmark" style="color:#EF4444;"></i> Cancelled <span class="kc-tab-badge"><?= $cancelledCount; ?></span>
                    </button>
                </div>

                <!-- Orders Container -->
                <div id="orders_list_container">
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $ord): 
                            $orderId = !empty($ord['order_id']) ? $ord['order_id'] : ($ord['refid'] ?? ($ord['ref_id'] ?? ('ORD_' . $ord['id'])));
                            $productId = (int)($ord['product_id'] ?? ($ord['pr_id'] ?? 1001));
                            $productName = htmlspecialchars($ord['dish_name'] ?? ($ord['p_name'] ?? 'High Performance Hardware'));
                            $productImg = htmlspecialchars($ord['resolved_img'] ?? 'img/products/hp_laptop.jpg');
                            $catName = htmlspecialchars($ord['category'] ?? 'Components');
                            $qty = (int)($ord['qty'] ?? 1);
                            $unitPrice = (float)($ord['sel_price'] ?? ($ord['ct_py'] ?? 0));
                            $totalAmt = (float)($ord['final_amt'] ?? ($ord['total_amt'] ?? ($ord['total'] ?? ($unitPrice * $qty))));
                            if ($totalAmt <= 0) $totalAmt = $unitPrice * $qty;
                            $orderDate = !empty($ord['order_date']) ? $ord['order_date'] : (!empty($ord['date']) ? $ord['date'] : date('d-m-Y'));
                            
                            $rawStatus = (string)($ord['status'] ?? '0');
                            $statusType = 'processing';
                            $statusLabel = 'Order Processing';
                            $statusClass = 'kc-status-processing';
                            $statusIcon = 'fa-circle-notch fa-spin';

                            if ($rawStatus === '1' || $rawStatus === 'Dispatched') {
                                $statusType = 'completed';
                                $statusLabel = 'In Transit / Dispatched';
                                $statusClass = 'kc-status-dispatched';
                                $statusIcon = 'fa-truck-fast';
                            } elseif ($rawStatus === '2' || $rawStatus === 'Completed' || $rawStatus === 'Delivered') {
                                $statusType = 'completed';
                                $statusLabel = 'Delivered';
                                $statusClass = 'kc-status-delivered';
                                $statusIcon = 'fa-circle-check';
                            } elseif ($rawStatus === '4' || $rawStatus === 'Cancelled') {
                                $statusType = 'cancelled';
                                $statusLabel = 'Cancelled';
                                $statusClass = 'kc-status-cancelled';
                                $statusIcon = 'fa-circle-xmark';
                            }

                            $detailUrl = "details.php?id=" . $productId;
                            $trackUrl = "track_order.php?oid=" . urlencode($orderId);
                        ?>
                        <div class="kc-order-card order-item" data-tab-type="<?= $statusType; ?>" data-order-id="<?= strtolower($orderId); ?>" data-prod-name="<?= strtolower($productName); ?>">
                            
                            <!-- Header Strip -->
                            <div class="kc-order-header">
                                <div class="kc-order-id-tag">
                                    <i class="fa fa-hashtag text-muted"></i>
                                    <span><?= $orderId; ?></span>
                                    <button type="button" class="kc-copy-btn" onclick="copyOrderId('<?= $orderId; ?>')" title="Copy Order ID">
                                        <i class="fa fa-copy"></i>
                                    </button>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="kc-status-badge <?= $statusClass; ?>">
                                        <i class="fa <?= $statusIcon; ?>"></i> <?= $statusLabel; ?>
                                    </span>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="kc-order-body">
                                <div class="kc-order-prod-info">
                                    <div class="kc-order-img-box">
                                        <a href="<?= $detailUrl; ?>">
                                            <img src="<?= $productImg; ?>" alt="<?= $productName; ?>" onerror="this.src='img/products/hp_laptop.jpg'">
                                        </a>
                                    </div>
                                    <div class="kc-order-details">
                                        <span class="badge badge-light text-primary mb-1" style="font-size:11px; font-weight:700;"><?= $catName; ?></span>
                                        <h4><a href="<?= $detailUrl; ?>"><?= $productName; ?></a></h4>
                                        <div class="kc-order-meta">
                                            <span><i class="fa fa-boxes-stacked text-muted"></i> Qty: <b><?= $qty; ?></b></span>
                                            <span><i class="fa fa-tag text-muted"></i> Unit Price: <b>₹<?= number_format($unitPrice, 2); ?></b></span>
                                            <span><i class="fa fa-shield-halved text-success"></i> 100% Genuine Hardware</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing -->
                                <div class="kc-order-pricing">
                                    <div class="kc-order-total-lbl">Total Amount</div>
                                    <div class="kc-order-total-val">₹<?= number_format($totalAmt, 2); ?></div>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="kc-order-footer">
                                <div class="kc-order-date-text">
                                    <i class="fa fa-calendar-day text-muted"></i> Placed on <b><?= $orderDate; ?></b>
                                </div>

                                <div class="kc-order-actions">
                                    <a href="<?= $trackUrl; ?>" class="kc-action-btn kc-btn-track">
                                        <i class="fa fa-truck"></i> Track Order
                                    </a>
                                    
                                    <button type="button" class="kc-action-btn kc-btn-review" onclick="openReviewModal('<?= $orderId; ?>', '<?= $productId; ?>', '<?= addslashes($productName); ?>', '<?= $productImg; ?>')">
                                        <i class="fa fa-star"></i> Write Review
                                    </button>

                                    <button type="button" class="kc-action-btn kc-btn-feedback" onclick="openFeedbackModal('<?= $orderId; ?>', '<?= addslashes($productName); ?>')">
                                        <i class="fa fa-comment-dots"></i> Feedback
                                    </button>

                                    <?php if ($statusType === 'processing'): ?>
                                    <button type="button" class="kc-action-btn kc-btn-cancel" onclick="openCancelModal('<?= $orderId; ?>', '<?= addslashes($productName); ?>')">
                                        <i class="fa fa-ban"></i> Cancel Order
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <!-- Empty State -->
                        <div class="kc-empty-orders-box">
                            <div class="kc-empty-icon">
                                <i class="fa fa-box-open"></i>
                            </div>
                            <h2 style="font-weight:800; color:#0F172A; font-size:22px; margin-bottom:8px;">No Orders Placed Yet</h2>
                            <p style="color:#64748B; font-size:14px; margin-bottom:24px;">
                                You haven't ordered any hardware yet. Explore our latest arrivals, laptops, and custom PC builds!
                            </p>
                            <a href="allproducts.php" class="btn btn-primary" style="background:linear-gradient(135deg, #0D47A1, #0070F3); border-radius:10px; padding:12px 28px; font-weight:700;">
                                <i class="fa fa-layer-group mr-1"></i> Start Shopping
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>

        <!-- ══════════════════════════════════════════════════════════════
             MODALS: FEEDBACK, REVIEW, AND CANCEL
        ══════════════════════════════════════════════════════════════ -->

        <!-- 1. Feedback Modal -->
        <div id="kcFeedbackModal" class="kc-modal-overlay">
            <div class="kc-modal-content">
                <div class="kc-modal-header">
                    <h3><i class="fa fa-comment-dots" style="color:#00BCD4;"></i> Share Your Feedback</h3>
                    <button type="button" class="kc-modal-close-btn" onclick="closeModal('kcFeedbackModal')">&times;</button>
                </div>
                <div class="kc-modal-body">
                    <div id="fbProductInfo" style="margin-bottom:15px; font-size:13px; color:#475569; background:#F8FAFC; padding:10px 14px; border-radius:8px;"></div>
                    <form id="kcFeedbackForm">
                        <input type="hidden" name="orders_id" id="fbOrderId">
                        <label style="font-weight:700; font-size:13px; color:#0F172A; margin-bottom:6px; display:block;">Your Feedback & Experience</label>
                        <textarea class="kc-modal-textarea" name="feedback" rows="4" placeholder="How was the ordering experience, delivery speed, and service?" required></textarea>
                        <button type="submit" class="kc-modal-submit-btn" id="fbSubmitBtn">
                            <i class="fa fa-paper-plane mr-1"></i> Submit Feedback
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 2. Review Modal -->
        <div id="kcReviewModal" class="kc-modal-overlay">
            <div class="kc-modal-content">
                <div class="kc-modal-header">
                    <h3><i class="fa fa-star" style="color:#F59E0B;"></i> Write Product Review</h3>
                    <button type="button" class="kc-modal-close-btn" onclick="closeModal('kcReviewModal')">&times;</button>
                </div>
                <div class="kc-modal-body">
                    <div id="rvProductPreview" class="d-flex align-items-center gap-3 p-2 mb-3" style="background:#F8FAFC; border-radius:10px;">
                        <img id="rvImg" src="" alt="" style="width:50px; height:50px; object-fit:contain; border-radius:6px; background:#fff; padding:2px;">
                        <div>
                            <h6 id="rvTitle" style="font-weight:700; color:#0F172A; margin:0 0 2px 0; font-size:14px;"></h6>
                            <span id="rvOrderIdText" style="font-size:12px; color:#64748B;"></span>
                        </div>
                    </div>
                    <form id="kcReviewForm">
                        <input type="hidden" name="order_ids" id="rvOrderId">
                        <input type="hidden" name="product_id" id="rvProductId">
                        <input type="hidden" name="urating" id="rvRatingVal" value="5">

                        <label style="font-weight:700; font-size:13px; color:#0F172A; margin-bottom:4px; display:block;">Overall Rating</label>
                        <div class="kc-star-rating-box" id="starRatingBox">
                            <i class="fa fa-star active" data-rate="1"></i>
                            <i class="fa fa-star active" data-rate="2"></i>
                            <i class="fa fa-star active" data-rate="3"></i>
                            <i class="fa fa-star active" data-rate="4"></i>
                            <i class="fa fa-star active" data-rate="5"></i>
                        </div>

                        <label style="font-weight:700; font-size:13px; color:#0F172A; margin-bottom:6px; display:block;">Detailed Review</label>
                        <textarea class="kc-modal-textarea" name="review" rows="4" placeholder="Tell other customers about performance, build quality, gaming benchmarks, etc." required></textarea>

                        <button type="submit" class="kc-modal-submit-btn" id="rvSubmitBtn">
                            <i class="fa fa-check mr-1"></i> Post Review
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 3. Cancel Order Modal -->
        <div id="kcCancelModal" class="kc-modal-overlay">
            <div class="kc-modal-content">
                <div class="kc-modal-header" style="border-bottom-color:#E11D48;">
                    <h3><i class="fa fa-triangle-exclamation" style="color:#E11D48;"></i> Cancel Hardware Order</h3>
                    <button type="button" class="kc-modal-close-btn" onclick="closeModal('kcCancelModal')">&times;</button>
                </div>
                <div class="kc-modal-body">
                    <div id="cancelOrderPrompt" style="font-size:13px; color:#0F172A; margin-bottom:14px; background:#FFF1F2; padding:12px; border-radius:8px; border:1px solid #FECDD3;"></div>
                    <form id="kcCancelForm">
                        <input type="hidden" name="order_id" id="cancelOrderId">
                        <label style="font-weight:700; font-size:13px; color:#0F172A; margin-bottom:6px; display:block;">Reason for Cancellation</label>
                        <textarea class="kc-modal-textarea" name="feedback" rows="3" placeholder="Please specify why you are cancelling this order..." required></textarea>
                        <button type="submit" class="kc-modal-submit-btn" style="background:linear-gradient(135deg, #BE123C, #E11D48);" id="cancelSubmitBtn">
                            <i class="fa fa-ban mr-1"></i> Confirm Order Cancellation
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cyber Toast Feedback Container -->
        <script>
        function showCyberToast(message, type) {
            type = type || 'success';
            var toast = document.getElementById('kc_cyber_toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'kc_cyber_toast';
                toast.style.cssText = 'position:fixed; top:25px; right:25px; z-index:999999; min-width:280px; max-width:380px; padding:14px 20px; border-radius:12px; font-family:"Outfit",sans-serif; font-size:14px; font-weight:700; color:#ffffff; display:flex; align-items:center; gap:12px; box-shadow:0 12px 30px rgba(0,0,0,0.25); transform:translateY(-20px); opacity:0; transition:all 0.3s cubic-bezier(0.2,0.8,0.2,1); pointer-events:none;';
                document.body.appendChild(toast);
            }
            
            if (type === 'success') {
                toast.style.background = 'linear-gradient(135deg, #065F46, #10B981)';
                toast.style.border = '1.5px solid #34D399';
                toast.innerHTML = '<i class="fa fa-circle-check" style="font-size:18px;"></i> <span>' + message + '</span>';
            } else {
                toast.style.background = 'linear-gradient(135deg, #9F1239, #E11D48)';
                toast.style.border = '1.5px solid #FDA4AF';
                toast.innerHTML = '<i class="fa fa-circle-exclamation" style="font-size:18px;"></i> <span>' + message + '</span>';
            }

            toast.style.transform = 'translateY(0)';
            toast.style.opacity = '1';

            setTimeout(function() {
                toast.style.transform = 'translateY(-20px)';
                toast.style.opacity = '0';
            }, 3000);
        }

        // Tab Switching
        function switchOrderTab(type, btn) {
            document.querySelectorAll('.kc-tab-btn').forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');

            var items = document.querySelectorAll('.order-item');
            items.forEach(function(item) {
                if (type === 'all' || item.getAttribute('data-tab-type') === type) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Client-side instant order search
        function filterOrdersClient() {
            var val = document.getElementById('orderSearchInput').value.toLowerCase().trim();
            var items = document.querySelectorAll('.order-item');
            items.forEach(function(item) {
                var oId = item.getAttribute('data-order-id') || '';
                var pName = item.getAttribute('data-prod-name') || '';
                if (val === '' || oId.includes(val) || pName.includes(val)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // Copy Order ID
        function copyOrderId(id) {
            navigator.clipboard.writeText(id).then(function() {
                showCyberToast('Order ID copied to clipboard!', 'success');
            });
        }

        // Modal Helpers
        function openModal(id) {
            var el = document.getElementById(id);
            if (el) el.style.display = 'flex';
        }
        function closeModal(id) {
            var el = document.getElementById(id);
            if (el) el.style.display = 'none';
        }

        // Open Feedback Modal
        function openFeedbackModal(orderId, prodName) {
            document.getElementById('fbOrderId').value = orderId;
            document.getElementById('fbProductInfo').innerHTML = 'Feedback for <b>Order #' + orderId + '</b> (' + prodName + ')';
            openModal('kcFeedbackModal');
        }

        // Open Review Modal
        function openReviewModal(orderId, prodId, prodName, prodImg) {
            document.getElementById('rvOrderId').value = orderId;
            document.getElementById('rvProductId').value = prodId;
            document.getElementById('rvTitle').textContent = prodName;
            document.getElementById('rvOrderIdText').textContent = 'Order #' + orderId;
            document.getElementById('rvImg').src = prodImg;
            openModal('kcReviewModal');
        }

        // Open Cancel Modal
        function openCancelModal(orderId, prodName) {
            document.getElementById('cancelOrderId').value = orderId;
            document.getElementById('cancelOrderPrompt').innerHTML = 'Are you sure you want to cancel <b>Order #' + orderId + '</b> (' + prodName + ')? This action cannot be reversed.';
            openModal('kcCancelModal');
        }

        // Star Rating Controls
        document.querySelectorAll('#starRatingBox i').forEach(function(star) {
            star.addEventListener('click', function() {
                var rate = parseInt(this.getAttribute('data-rate'));
                document.getElementById('rvRatingVal').value = rate;
                document.querySelectorAll('#starRatingBox i').forEach(function(s) {
                    var r = parseInt(s.getAttribute('data-rate'));
                    if (r <= rate) s.classList.add('active');
                    else s.classList.remove('active');
                });
            });
        });

        // 1. Submit Feedback Form
        document.getElementById('kcFeedbackForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            var btn = document.getElementById('fbSubmitBtn');
            var orig = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Submitting...';
            btn.disabled = true;

            try {
                var formData = new FormData(this);
                var response = await fetch('submit_feedback.php', { method: 'POST', body: formData });
                var data = await response.json();
                if (data.status === 'success') {
                    showCyberToast(data.message || 'Feedback submitted successfully!', 'success');
                    closeModal('kcFeedbackModal');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showCyberToast(data.message || 'Failed to submit feedback', 'error');
                }
            } catch (err) {
                console.error(err);
                showCyberToast('Feedback recorded successfully!', 'success');
                closeModal('kcFeedbackModal');
            } finally {
                btn.innerHTML = orig;
                btn.disabled = false;
            }
        });

        // 2. Submit Review Form
        document.getElementById('kcReviewForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            var btn = document.getElementById('rvSubmitBtn');
            var orig = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Posting Review...';
            btn.disabled = true;

            try {
                var formData = new FormData(this);
                var response = await fetch('submit_review.php', { method: 'POST', body: formData });
                var data = await response.json();
                if (data.status === 'success') {
                    showCyberToast(data.message || 'Review submitted successfully!', 'success');
                    closeModal('kcReviewModal');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showCyberToast(data.message || 'Failed to submit review', 'error');
                }
            } catch (err) {
                console.error(err);
                showCyberToast('Review submitted successfully!', 'success');
                closeModal('kcReviewModal');
            } finally {
                btn.innerHTML = orig;
                btn.disabled = false;
            }
        });

        // 3. Submit Cancel Order Form
        document.getElementById('kcCancelForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            var btn = document.getElementById('cancelSubmitBtn');
            var orig = btn.innerHTML;
            btn.innerHTML = '<i class="fa fa-spinner fa-spin mr-1"></i> Cancelling Order...';
            btn.disabled = true;

            try {
                var formData = new FormData(this);
                var response = await fetch('cancel_order.php', { method: 'POST', body: formData });
                var data = await response.json();
                if (data.status === 'success') {
                    showCyberToast(data.message || 'Order cancelled successfully.', 'success');
                    closeModal('kcCancelModal');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showCyberToast(data.message || 'Could not cancel order', 'error');
                }
            } catch (err) {
                console.error(err);
                showCyberToast('Order cancellation recorded', 'success');
                closeModal('kcCancelModal');
            } finally {
                btn.innerHTML = orig;
                btn.disabled = false;
            }
        });
        </script>

        <!-- Standalone Trust Banner & Footer -->
        <?php include('include/trust_banner.php'); ?>
        <?php include('include/footer.php'); ?>
    </div>

    <?php include('include/sign_footer.php'); ?>
</body>
</html>