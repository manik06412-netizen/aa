<?php require_once('header.php'); ?>

<section class="content-header">
    <h1>
        <i class="fa fa-th-large" style="color: #10b981; font-size: 18px; margin-right: 6px;"></i> Dashboard
        <small>Overview & Operations Summary</small>
    </h1>
</section>

<?php
error_reporting(0);

$total_top_category = 0;
$total_user_count   = 0;
$total_subs         = 0;
$total_product      = 0;
$total_orders       = 0;
$pending_orders     = 0;
$refund_orders      = 0;
$total_vendors      = 0;
$total_restaurants  = 0;

try {
    $s = $pdo->prepare("SELECT COUNT(*) FROM res_category");
    $s->execute(); $total_top_category = (int)$s->fetchColumn();

    $s = $pdo->prepare("SELECT COUNT(*) FROM user WHERE fname != 'Guest'");
    $s->execute(); $total_user_count = (int)$s->fetchColumn();

    $s = $pdo->prepare("SELECT COUNT(*) FROM tbl_subscriber");
    $s->execute(); $total_subs = (int)$s->fetchColumn();

    $s = $pdo->prepare("SELECT COUNT(*) FROM dishes");
    $s->execute(); $total_product = (int)$s->fetchColumn();

    $s = $pdo->prepare("SELECT COUNT(*) FROM tbl_order");
    $s->execute(); $total_orders = (int)$s->fetchColumn();

    $s = $pdo->prepare("SELECT COUNT(*) FROM tbl_order WHERE status = 'Pending'");
    $s->execute(); $pending_orders = (int)$s->fetchColumn();

    $s = $pdo->prepare("SELECT COUNT(*) FROM tbl_order WHERE status = 'Refund'");
    $s->execute(); $refund_orders = (int)$s->fetchColumn();
} catch(Exception $e) { /* ignore */ }

try {
    $r = mysqli_query($con, "SELECT COUNT(*) as cnt FROM vendor");
    if ($r && $row = mysqli_fetch_assoc($r)) { $total_vendors = (int)$row['cnt']; }

    $r = mysqli_query($con, "SELECT COUNT(*) as cnt FROM restraunt");
    if ($r && $row = mysqli_fetch_assoc($r)) { $total_restaurants = (int)$row['cnt']; }
} catch(Exception $e) { /* ignore */ }
?>

<section class="content">

    <!-- ── Key Business Metrics ── -->
    <div class="row">
        <!-- Products -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="products_list.php" style="text-decoration:none;">
                <div class="stat-card emerald">
                    <div class="stat-info">
                        <h3><?php echo number_format($total_product); ?></h3>
                        <p>Total Products</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-leaf"></i></div>
                </div>
            </a>
        </div>

        <!-- Orders -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="my_orders.php" style="text-decoration:none;">
                <div class="stat-card blue">
                    <div class="stat-info">
                        <h3><?php echo number_format($total_orders); ?></h3>
                        <p>Total Orders</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-shopping-bag"></i></div>
                </div>
            </a>
        </div>

        <!-- Customers -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="customer.php" style="text-decoration:none;">
                <div class="stat-card purple">
                    <div class="stat-info">
                        <h3><?php echo number_format($total_user_count); ?></h3>
                        <p>Customers</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-users"></i></div>
                </div>
            </a>
        </div>

        <!-- Categories -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="category_lists.php" style="text-decoration:none;">
                <div class="stat-card teal">
                    <div class="stat-info">
                        <h3><?php echo number_format($total_top_category); ?></h3>
                        <p>Categories</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-tags"></i></div>
                </div>
            </a>
        </div>
    </div>

    <!-- ── Operational Metrics ── -->
    <div class="row">
        <!-- Pending Orders -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="my_orders.php" style="text-decoration:none;">
                <div class="stat-card amber">
                    <div class="stat-info">
                        <h3><?php echo number_format($pending_orders); ?></h3>
                        <p>Pending Orders</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-clock-o"></i></div>
                </div>
            </a>
        </div>

        <!-- Refunds -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="refund_orders.php" style="text-decoration:none;">
                <div class="stat-card rose">
                    <div class="stat-info">
                        <h3><?php echo number_format($refund_orders); ?></h3>
                        <p>Refund Orders</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-undo"></i></div>
                </div>
            </a>
        </div>

        <!-- Vendors -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="vendor_list.php" style="text-decoration:none;">
                <div class="stat-card indigo">
                    <div class="stat-info">
                        <h3><?php echo number_format($total_vendors); ?></h3>
                        <p>Vendors</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-building-o"></i></div>
                </div>
            </a>
        </div>

        <!-- Subscribers -->
        <div class="col-lg-3 col-sm-6 col-xs-12">
            <a href="subscriber.php" style="text-decoration:none;">
                <div class="stat-card blue">
                    <div class="stat-info">
                        <h3><?php echo number_format($total_subs); ?></h3>
                        <p>Subscribers</p>
                    </div>
                    <div class="stat-icon"><i class="fa fa-envelope-o"></i></div>
                </div>
            </a>
        </div>
    </div>

    <!-- ── Quick Management Hub ── -->
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-bolt" style="color: #f59e0b; margin-right: 6px;"></i> Quick Actions
                    </h3>
                </div>
                <div class="box-body" style="padding: 20px 24px;">
                    <div class="row">
                        <div class="col-md-2 col-sm-4 col-xs-6 mb-3 text-center">
                            <a href="add_products.php" class="btn btn-default btn-block" style="padding: 16px 10px; display: block; border: 1px solid #e2e8f0; border-radius: 12px; background: #fafafa;">
                                <i class="fa fa-plus-circle fa-2x" style="color: #10b981;"></i><br>
                                <span style="font-size: 12.5px; font-weight: 600; color: #1e293b; margin-top: 8px; display: inline-block;">Add Product</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 mb-3 text-center">
                            <a href="products_list.php" class="btn btn-default btn-block" style="padding: 16px 10px; display: block; border: 1px solid #e2e8f0; border-radius: 12px; background: #fafafa;">
                                <i class="fa fa-list-ul fa-2x" style="color: #3b82f6;"></i><br>
                                <span style="font-size: 12.5px; font-weight: 600; color: #1e293b; margin-top: 8px; display: inline-block;">Product List</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 mb-3 text-center">
                            <a href="my_orders.php" class="btn btn-default btn-block" style="padding: 16px 10px; display: block; border: 1px solid #e2e8f0; border-radius: 12px; background: #fafafa;">
                                <i class="fa fa-shopping-bag fa-2x" style="color: #8b5cf6;"></i><br>
                                <span style="font-size: 12.5px; font-weight: 600; color: #1e293b; margin-top: 8px; display: inline-block;">All Orders</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 mb-3 text-center">
                            <a href="stock.php" class="btn btn-default btn-block" style="padding: 16px 10px; display: block; border: 1px solid #e2e8f0; border-radius: 12px; background: #fafafa;">
                                <i class="fa fa-cubes fa-2x" style="color: #f59e0b;"></i><br>
                                <span style="font-size: 12.5px; font-weight: 600; color: #1e293b; margin-top: 8px; display: inline-block;">Adjust Stock</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 mb-3 text-center">
                            <a href="reports_list.php" class="btn btn-default btn-block" style="padding: 16px 10px; display: block; border: 1px solid #e2e8f0; border-radius: 12px; background: #fafafa;">
                                <i class="fa fa-bar-chart fa-2x" style="color: #06b6d4;"></i><br>
                                <span style="font-size: 12.5px; font-weight: 600; color: #1e293b; margin-top: 8px; display: inline-block;">Reports</span>
                            </a>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 mb-3 text-center">
                            <a href="web_settings.php" class="btn btn-default btn-block" style="padding: 16px 10px; display: block; border: 1px solid #e2e8f0; border-radius: 12px; background: #fafafa;">
                                <i class="fa fa-sliders fa-2x" style="color: #64748b;"></i><br>
                                <span style="font-size: 12.5px; font-weight: 600; color: #1e293b; margin-top: 8px; display: inline-block;">Settings</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════════════════════════════
         FEATURE 1: REVENUE + ORDERS TREND CHART (Last 7 Days)
    ══════════════════════════════════════════════════════════ -->
    <div class="row" style="margin-top:8px;">

        <!-- Revenue Chart -->
        <div class="col-md-8">
            <div class="box" style="border-radius:14px;">
                <div class="box-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h3 class="box-title">
                        <i class="fa fa-line-chart" style="color:#22c55e;margin-right:7px;"></i>
                        Revenue &amp; Orders — Last 7 Days
                    </h3>
                    <span style="font-size:11px;color:#94a3b8;background:#f8fafc;padding:4px 10px;border-radius:20px;border:1px solid #e2e8f0;">
                        <?php echo date('d M'); ?> week
                    </span>
                </div>
                <div class="box-body" style="padding:16px 20px;">
                    <canvas id="avRevenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- Order Status Doughnut -->
        <div class="col-md-4">
            <div class="box" style="border-radius:14px;height:100%;">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-pie-chart" style="color:#6366f1;margin-right:7px;"></i>
                        Order Status Split
                    </h3>
                </div>
                <div class="box-body" style="padding:10px 20px;display:flex;flex-direction:column;align-items:center;">
                    <canvas id="avOrderDonut" height="180" width="180"></canvas>
                    <div id="avDonutLegend" style="margin-top:12px;width:100%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         FEATURE 2: LOW STOCK ALERT PANEL
    ══════════════════════════════════════════════════════════ -->
    <div class="row" style="margin-top:4px;">
        <div class="col-md-12">
            <div class="box" style="border-radius:14px;border-left:4px solid #ef4444;">
                <div class="box-header" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:10px;">
                    <h3 class="box-title">
                        <i class="fa fa-exclamation-triangle" style="color:#ef4444;margin-right:7px;"></i>
                        Low Stock Alert
                        <span id="lowStockBadge" style="display:inline-block;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:2px 8px;border-radius:20px;margin-left:8px;vertical-align:middle;"></span>
                    </h3>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <label style="font-size:12px;color:#64748b;margin:0;">Threshold:</label>
                        <select id="stockThreshold" onchange="loadLowStock()" style="border:1px solid #e2e8f0;border-radius:8px;padding:4px 10px;font-size:12px;color:#374151;">
                            <option value="5">≤ 5 units</option>
                            <option value="10" selected>≤ 10 units</option>
                            <option value="20">≤ 20 units</option>
                            <option value="50">≤ 50 units</option>
                        </select>
                        <a href="stock.php" class="btn btn-danger btn-xs" style="border-radius:8px;font-size:11px;">
                            <i class="fa fa-cubes"></i> Manage Stock
                        </a>
                    </div>
                </div>
                <div class="box-body" style="padding:0;">
                    <div id="lowStockContainer" style="max-height:280px;overflow-y:auto;">
                        <div style="text-align:center;padding:30px;color:#94a3b8;">
                            <i class="fa fa-spinner fa-spin fa-2x"></i>
                            <p style="margin-top:10px;font-size:13px;">Loading stock data...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ══════════════════════════════════════════════════════════
         FEATURE 3: ORDER STATUS PIPELINE
    ══════════════════════════════════════════════════════════ -->
    <div class="row" style="margin-top:4px;">
        <div class="col-md-12">
            <div class="box" style="border-radius:14px;">
                <div class="box-header">
                    <h3 class="box-title">
                        <i class="fa fa-road" style="color:#6366f1;margin-right:7px;"></i>
                        Order Status Pipeline
                        <small style="font-size:11px;color:#94a3b8;margin-left:6px;">— Real-time count per stage</small>
                    </h3>
                </div>
                <div class="box-body" style="padding:20px 24px;">
                    <?php
                    // Order status pipeline data
                    $pipeline = [
                        ['label'=>'Placed',    'status'=>'Pending',   'icon'=>'fa-shopping-cart', 'color'=>'#6366f1','bg'=>'#eef2ff'],
                        ['label'=>'Confirmed', 'status'=>'Confirm',   'icon'=>'fa-check-circle',  'color'=>'#0891b2','bg'=>'#ecfeff'],
                        ['label'=>'Packing',   'status'=>'Packing',   'icon'=>'fa-archive',       'color'=>'#d97706','bg'=>'#fffbeb'],
                        ['label'=>'Shipped',   'status'=>'Shipped',   'icon'=>'fa-truck',         'color'=>'#7c3aed','bg'=>'#faf5ff'],
                        ['label'=>'Delivered', 'status'=>'Delivered', 'icon'=>'fa-check',         'color'=>'#16a34a','bg'=>'#f0fdf4'],
                        ['label'=>'Cancelled', 'status'=>'Cancelled', 'icon'=>'fa-times-circle',  'color'=>'#dc2626','bg'=>'#fef2f2'],
                        ['label'=>'Refund',    'status'=>'Refund',    'icon'=>'fa-undo',          'color'=>'#9333ea','bg'=>'#fdf4ff'],
                    ];
                    $pipelineTotal = 0;
                    $pipelineCounts = [];
                    foreach ($pipeline as $p) {
                        $cnt = 0;
                        try {
                            $ps = $pdo->prepare("SELECT COUNT(*) FROM tbl_order WHERE status = ?");
                            $ps->execute([$p['status']]);
                            $cnt = (int)$ps->fetchColumn();
                        } catch(Exception $e) {}
                        $pipelineCounts[] = $cnt;
                        $pipelineTotal += $cnt;
                    }
                    ?>
                    <!-- Pipeline Steps -->
                    <div style="display:flex;align-items:stretch;gap:0;overflow-x:auto;padding-bottom:8px;">
                        <?php foreach($pipeline as $i => $p):
                            $cnt   = $pipelineCounts[$i];
                            $pct   = $pipelineTotal > 0 ? round($cnt / $pipelineTotal * 100) : 0;
                            $isLast = ($i === count($pipeline)-1);
                        ?>
                        <div style="flex:1;min-width:110px;display:flex;flex-direction:column;align-items:center;position:relative;">
                            <!-- Step circle -->
                            <div style="width:52px;height:52px;border-radius:50%;background:<?php echo $p['bg']; ?>;border:2px solid <?php echo $p['color']; ?>;display:flex;align-items:center;justify-content:center;z-index:2;position:relative;">
                                <i class="fa <?php echo $p['icon']; ?>" style="color:<?php echo $p['color']; ?>;font-size:18px;"></i>
                            </div>
                            <!-- Connector line -->
                            <?php if (!$isLast): ?>
                            <div style="position:absolute;top:25px;left:calc(50% + 26px);right:calc(-50% + 26px);height:2px;background:<?php echo $cnt>0 ? $p['color'] : '#e2e8f0'; ?>;z-index:1;"></div>
                            <?php endif; ?>
                            <!-- Count -->
                            <div style="font-size:22px;font-weight:800;color:<?php echo $p['color']; ?>;margin-top:10px;line-height:1;"><?php echo number_format($cnt); ?></div>
                            <!-- Label -->
                            <div style="font-size:11px;font-weight:600;color:#64748b;margin-top:3px;text-align:center;"><?php echo $p['label']; ?></div>
                            <!-- Percent badge -->
                            <div style="font-size:10px;background:<?php echo $p['bg']; ?>;color:<?php echo $p['color']; ?>;padding:2px 7px;border-radius:10px;margin-top:4px;font-weight:600;"><?php echo $pct; ?>%</div>
                            <!-- Bar -->
                            <div style="width:80%;height:4px;background:#f1f5f9;border-radius:4px;margin-top:8px;overflow:hidden;">
                                <div style="width:<?php echo $pct; ?>%;height:100%;background:<?php echo $p['color']; ?>;border-radius:4px;transition:width 1s ease;"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Total -->
                    <div style="text-align:right;margin-top:14px;font-size:12px;color:#94a3b8;">
                        Total Orders: <strong style="color:#0f172a;"><?php echo number_format($pipelineTotal); ?></strong>
                        &nbsp;·&nbsp;
                        <a href="my_orders.php" style="color:#22c55e;font-weight:600;text-decoration:none;">View All Orders →</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>

<!-- ══ Chart.js CDN ══ -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
/* ── FEATURE 1: Revenue & Orders Line Chart ── */
(function() {
    <?php
    // Build last 7 days labels + order counts
    $labels = []; $orderCounts = []; $revData = [];
    for ($d = 6; $d >= 0; $d--) {
        $date_label = date('d M', strtotime("-$d days"));
        $db_date    = date('d-m-Y', strtotime("-$d days"));
        $labels[]   = $date_label;
        // Order count per day
        $cnt = 0; $rev = 0;
        try {
            $st = $pdo->prepare("SELECT COUNT(*) FROM tbl_order WHERE order_date = ?");
            $st->execute([$db_date]);
            $cnt = (int)$st->fetchColumn();
            // Revenue: sum of total_amount per day
            $st2 = $pdo->prepare("SELECT COALESCE(SUM(total_amount),0) FROM tbl_order WHERE order_date = ?");
            $st2->execute([$db_date]);
            $rev = (float)$st2->fetchColumn();
        } catch(Exception $e) {}
        $orderCounts[] = $cnt;
        $revData[]     = $rev;
    }
    ?>
    const labels    = <?php echo json_encode($labels); ?>;
    const orderData = <?php echo json_encode($orderCounts); ?>;
    const revData   = <?php echo json_encode($revData); ?>;

    const ctx = document.getElementById('avRevenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Revenue (₹)',
                        data: revData,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.08)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#22c55e',
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        yAxisID: 'yRev',
                    },
                    {
                        label: 'Orders',
                        data: orderData,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,0.06)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#6366f1',
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        yAxisID: 'yOrd',
                    }
                ]
            },
            options: {
                responsive: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { font: { family: 'Inter', size: 12 }, usePointStyle: true, pointStyleWidth: 8 } },
                    tooltip: {
                        callbacks: {
                            label: (ctx) => {
                                if (ctx.dataset.yAxisID === 'yRev') return ' ₹' + ctx.parsed.y.toLocaleString('en-IN');
                                return ' ' + ctx.parsed.y + ' orders';
                            }
                        }
                    }
                },
                scales: {
                    yRev: { type:'linear', position:'left',  ticks:{ callback: v => '₹'+v.toLocaleString('en-IN'), font:{size:11} }, grid:{ color:'#f1f5f9' } },
                    yOrd: { type:'linear', position:'right', ticks:{ stepSize:1, font:{size:11} }, grid:{ drawOnChartArea:false } },
                    x:    { ticks:{ font:{size:11} }, grid:{ color:'#f8fafc' } }
                }
            }
        });
    }

    /* ── Doughnut: Order Status Split ── */
    <?php
    $statusLabels  = ['Pending','Confirm','Packing','Shipped','Delivered','Cancelled','Refund'];
    $statusColors  = ['#6366f1','#0891b2','#d97706','#7c3aed','#16a34a','#dc2626','#9333ea'];
    $statusCounts2 = [];
    foreach ($statusLabels as $sl) {
        try {
            $st = $pdo->prepare("SELECT COUNT(*) FROM tbl_order WHERE status = ?");
            $st->execute([$sl]);
            $statusCounts2[] = (int)$st->fetchColumn();
        } catch(Exception $e) { $statusCounts2[] = 0; }
    }
    ?>
    const donutLabels = <?php echo json_encode($statusLabels); ?>;
    const donutData   = <?php echo json_encode($statusCounts2); ?>;
    const donutColors = <?php echo json_encode($statusColors); ?>;

    const donutCtx = document.getElementById('avOrderDonut');
    if (donutCtx) {
        new Chart(donutCtx, {
            type: 'doughnut',
            data: {
                labels: donutLabels,
                datasets: [{ data: donutData, backgroundColor: donutColors, borderWidth: 2, borderColor: '#fff', hoverOffset: 6 }]
            },
            options: {
                cutout: '68%',
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: c => ' ' + c.label + ': ' + c.parsed } }
                }
            }
        });
        // Custom legend
        const legend = document.getElementById('avDonutLegend');
        if (legend) {
            legend.innerHTML = donutLabels.map((l,i) =>
                `<div style="display:flex;align-items:center;gap:7px;margin:4px 0;font-size:11.5px;">
                    <span style="width:10px;height:10px;border-radius:50%;background:${donutColors[i]};display:inline-block;flex-shrink:0;"></span>
                    <span style="color:#374151;font-weight:500;">${l}</span>
                    <span style="margin-left:auto;font-weight:700;color:#0f172a;">${donutData[i]}</span>
                </div>`
            ).join('');
        }
    }
})();

/* ── FEATURE 2: Low Stock Alert ── */
function loadLowStock() {
    const threshold = document.getElementById('stockThreshold')?.value || 10;
    const container = document.getElementById('lowStockContainer');
    const badge     = document.getElementById('lowStockBadge');
    if (!container) return;

    container.innerHTML = '<div style="text-align:center;padding:24px;color:#94a3b8;"><i class="fa fa-spinner fa-spin fa-2x"></i></div>';

    fetch('low_stock_alert.php?threshold=' + threshold)
        .then(r => r.json())
        .then(data => {
            if (!data.success || !data.items?.length) {
                badge.textContent = '0';
                badge.style.background = '#22c55e';
                container.innerHTML = `
                    <div style="text-align:center;padding:32px;color:#22c55e;">
                        <i class="fa fa-check-circle fa-3x"></i>
                        <p style="margin-top:12px;font-size:14px;font-weight:600;color:#374151;">All products have sufficient stock!</p>
                        <p style="font-size:12px;color:#94a3b8;">No items below ${threshold} units threshold.</p>
                    </div>`;
                return;
            }
            badge.textContent = data.count;
            badge.style.background = '#ef4444';

            const rows = data.items.map((item, i) => {
                const isOut = item.level === 'out';
                const stockColor = isOut ? '#ef4444' : '#d97706';
                const stockBg    = isOut ? '#fef2f2' : '#fffbeb';
                const levelLabel = isOut
                    ? '<span style="background:#fef2f2;color:#ef4444;font-size:10px;font-weight:700;padding:2px 8px;border-radius:10px;border:1px solid #fecaca;">OUT OF STOCK</span>'
                    : '<span style="background:#fffbeb;color:#d97706;font-size:10px;font-weight:700;padding:2px 8px;border-radius:10px;border:1px solid #fde68a;">LOW STOCK</span>';
                return `
                    <tr style="${i%2===0?'background:#fafafa;':''}">
                        <td style="padding:10px 16px;font-size:13px;font-weight:600;color:#0f172a;">${item.product_name}</td>
                        <td style="padding:10px 16px;font-size:12px;color:#64748b;">${item.category}</td>
                        <td style="padding:10px 16px;text-align:center;">
                            <span style="background:${stockBg};color:${stockColor};font-size:16px;font-weight:800;padding:3px 12px;border-radius:8px;display:inline-block;">${item.stock}</span>
                        </td>
                        <td style="padding:10px 16px;text-align:center;">${levelLabel}</td>
                        <td style="padding:10px 16px;text-align:center;">
                            <a href="stock.php" style="background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0;font-size:11px;font-weight:600;padding:5px 12px;border-radius:8px;text-decoration:none;">
                                <i class="fa fa-plus"></i> Add Stock
                            </a>
                        </td>
                    </tr>`;
            }).join('');

            container.innerHTML = `
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:2px solid #f0f4f8;">
                            <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;background:#f8fafc;">Product</th>
                            <th style="padding:10px 16px;text-align:left;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;background:#f8fafc;">Category</th>
                            <th style="padding:10px 16px;text-align:center;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;background:#f8fafc;">Stock Qty</th>
                            <th style="padding:10px 16px;text-align:center;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;background:#f8fafc;">Status</th>
                            <th style="padding:10px 16px;text-align:center;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:#64748b;background:#f8fafc;">Action</th>
                        </tr>
                    </thead>
                    <tbody>${rows}</tbody>
                </table>`;
        })
        .catch(() => {
            container.innerHTML = '<div style="text-align:center;padding:24px;color:#94a3b8;font-size:13px;"><i class="fa fa-wifi" style="margin-right:6px;"></i>Could not load stock data.</div>';
        });
}

// Auto-load on page ready
document.addEventListener('DOMContentLoaded', loadLowStock);
</script>

<?php require_once('footer.php'); ?>
