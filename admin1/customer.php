<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
error_reporting(0);
?>
<?php 
require_once('header.php');
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Customer Details';
echo "<script>var sessionTitle = '$title';</script>";
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>View Customers</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table">CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print</button>
        <button class="btn btn-primary btn-xs" id="download_pdf">PDF</button>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="display:none" width="10">#</th>
                                <th width="200" style="text-align: left;">Name/Email</th>
                                <th width="120">Cust.id</th>
                                <th width="150">Cust.Mobile</th>
                                <th width="120">Current Status</th>
                                <th width="150">Change Status</th>
                                <th width="120">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 0;
                            $statement = $pdo->prepare("SELECT * FROM user where fname !='Guest'  ORDER BY user_id DESC");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);                        
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td style="display:none"><?php echo $i; ?></td>
                                    <td style="text-align: left;"><b><?php echo $row['fname']; ?></b><br><small class="text-muted"><?php echo $row['email']; ?></small></td>
                                    <td><?php echo $row['user_id']; ?></td>
                                    <td><?php echo !empty($row['mobile']) ? htmlspecialchars($row['mobile']) : '-'; ?></td>
                                    <td>
                                        <span class="status-badge <?php echo ($row['status'] == 1) ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo ($row['status'] == 1) ? 'Active' : 'Inactive'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="customer-change-status.php?id=<?php echo $row['id']; ?>" class="btn btn-success btn-xs" style="cursor:pointer;">Change Status</a>
                                    </td>
                                    <td>
                                        <div class="action-btn-group" style="max-width: 120px;">
                                            <!-- FEATURE 6: Purchase History Button -->
                                            <button class="act-btn act-stock" onclick="openHistory(<?php echo (int)$row['user_id']; ?>, '<?php echo htmlspecialchars(addslashes($row['fname'])); ?>')" title="View purchase history">
                                                <i class="fa fa-history"></i>
                                            </button>
                                            <a href="#" class="act-btn act-delete" data-href="customer-delete.php?id=<?php echo $row['id']; ?>" data-toggle="modal" data-target="#confirm-delete" title="Delete customer">
                                                <i class="fa fa-trash-o"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this Customer?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════
     FEATURE 6: CUSTOMER PURCHASE HISTORY MODAL
══════════════════════════════════════════════════════ -->
<style>
#historyModal .modal-dialog { max-width: 820px; width: 95%; }
#historyModal .modal-content { border-radius: 16px; border: none; box-shadow: 0 24px 60px rgba(0,0,0,0.18); }
#historyModal .modal-header {
    background: linear-gradient(135deg, #0f172a, #1e293b);
    border-radius: 16px 16px 0 0;
    padding: 18px 24px;
    border: none;
}
#historyModal .modal-header h4 { color: #fff; font-size: 16px; margin: 0; }
#historyModal .modal-header .close { color: #94a3b8; opacity: 1; font-size: 20px; }
#historyModal .modal-body { padding: 0; max-height: 70vh; overflow-y: auto; }
.hist-stat-row { display: flex; gap: 12px; padding: 18px 20px; background: #f8fafc; border-bottom: 1px solid #e8edf2; flex-wrap: wrap; }
.hist-stat { flex: 1; min-width: 120px; background: #fff; border-radius: 12px; padding: 14px 16px; text-align: center; border: 1px solid #e2e8f0; }
.hist-stat .val { font-size: 22px; font-weight: 800; line-height: 1; }
.hist-stat .lbl { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #94a3b8; margin-top: 4px; }
.hist-order-table { width: 100%; border-collapse: collapse; }
.hist-order-table th { background: #f8fafc; padding: 10px 14px; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; border-bottom: 2px solid #e8edf2; white-space: nowrap; }
.hist-order-table td { padding: 10px 14px; font-size: 12.5px; border-bottom: 1px solid #f0f4f8; vertical-align: middle; }
.hist-order-table tr:hover td { background: #f8fafc; }
.hist-status { font-size: 10px; font-weight: 700; padding: 3px 8px; border-radius: 10px; display: inline-block; }
</style>

<div class="modal fade" id="historyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">
                    <i class="fa fa-history" style="color:#22c55e;margin-right:8px;"></i>
                    <span id="histCustomerName">Customer</span> — Purchase History
                </h4>
            </div>
            <div class="modal-body">
                <div id="histLoader" style="text-align:center;padding:48px;color:#94a3b8;">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                    <p style="margin-top:14px;font-size:13px;">Loading order history...</p>
                </div>
                <div id="histContent" style="display:none;">
                    <!-- Stats row -->
                    <div class="hist-stat-row" id="histStats"></div>
                    <!-- Orders table -->
                    <div style="padding:0 20px 20px;">
                        <table class="hist-order-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Order ID</th>
                                    <th>Date</th>
                                    <th>Items</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="histOrderRows"></tbody>
                        </table>
                        <div id="histEmpty" style="display:none;text-align:center;padding:32px;color:#94a3b8;">
                            <i class="fa fa-shopping-bag fa-3x"></i>
                            <p style="margin-top:12px;font-size:14px;font-weight:600;">No orders yet</p>
                        </div>
                    </div>
                </div>
            </div>

    'Pending':   { bg:'#eef2ff', color:'#6366f1' },
    'Confirm':   { bg:'#ecfeff', color:'#0891b2' },
    'Packing':   { bg:'#fffbeb', color:'#d97706' },
    'Shipped':   { bg:'#faf5ff', color:'#7c3aed' },
    'Delivered': { bg:'#f0fdf4', color:'#16a34a' },
    'Cancelled': { bg:'#fef2f2', color:'#dc2626' },
    'Refund':    { bg:'#fdf4ff', color:'#9333ea' },
};

function openHistory(userId, custName) {
    document.getElementById('histCustomerName').textContent = custName;
    document.getElementById('histLoader').style.display = 'block';
    document.getElementById('histContent').style.display = 'none';
    $('#historyModal').modal('show');

    fetch('customer_history_api.php?user_id=' + userId)
        .then(r => r.json())
        .then(data => {
            document.getElementById('histLoader').style.display = 'none';
            if (!data.success) {
                document.getElementById('histLoader').style.display = 'block';
                document.getElementById('histLoader').innerHTML = '<p style="color:#ef4444;">Could not load history.</p>';
                return;
            }
            document.getElementById('histContent').style.display = 'block';

            // Stats
            const s = data.stats;
            document.getElementById('histStats').innerHTML = `
                <div class="hist-stat">
                    <div class="val" style="color:#6366f1;">${s.total_orders}</div>
                    <div class="lbl">Total Orders</div>
                </div>
                <div class="hist-stat">
                    <div class="val" style="color:#16a34a;">₹${parseFloat(s.total_spend).toLocaleString('en-IN', {minimumFractionDigits:2})}</div>
                    <div class="lbl">Total Spend</div>
                </div>
                <div class="hist-stat">
                    <div class="val" style="color:#22c55e;">${s.delivered}</div>
                    <div class="lbl">Delivered</div>
                </div>
                <div class="hist-stat">
                    <div class="val" style="color:#f59e0b;">${s.pending}</div>
                    <div class="lbl">Pending</div>
                </div>`;

            // Orders
            const tbody = document.getElementById('histOrderRows');
            const empty = document.getElementById('histEmpty');
            if (!data.orders?.length) {
                tbody.innerHTML = '';
                empty.style.display = 'block';
            } else {
                empty.style.display = 'none';
                tbody.innerHTML = data.orders.map((o, i) => {
                    const st = statusStyles[o.status] || {bg:'#f1f5f9', color:'#64748b'};
                    const amt = parseFloat(o.total_amount || 0).toLocaleString('en-IN', {minimumFractionDigits:2});
                    return `
                    <tr>
                        <td style="color:#94a3b8;">${i+1}</td>
                        <td><b style="color:#6366f1;">${o.order_id || '—'}</b></td>
                        <td>${o.order_date || '—'}</td>
                        <td><span style="background:#f0f4f8;color:#374151;padding:2px 8px;border-radius:6px;font-weight:600;">${o.item_count} items</span></td>
                        <td><b>₹${amt}</b></td>
                        <td><span class="hist-status" style="background:${st.bg};color:${st.color};">${o.status}</span></td>
                    </tr>`;
                }).join('');
            }
        })
        .catch(() => {
            document.getElementById('histLoader').style.display = 'block';
            document.getElementById('histLoader').innerHTML = '<p style="color:#ef4444;padding:20px;"><i class="fa fa-wifi"></i> Network error. Try again.</p>';
        });
}
</script>
