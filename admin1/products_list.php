<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) { header("Location: login.php"); exit; }
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) { $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1; }
?>
<?php error_reporting(0); ?>
<?php require_once('header.php'); ?>
<style>
.bg-prim { background-color: rgb(146, 187, 211) !important; }
/* ── Bulk toolbar ── */
#bulkToolbar {
    display: none;
    align-items: center;
    gap: 10px;
    background: linear-gradient(135deg,#0b192c,#1e293b);
    border-radius: 12px;
    padding: 12px 20px;
    margin-bottom: 14px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    animation: slideDown 0.25s ease;
}
@keyframes slideDown { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:translateY(0)} }
#bulkToolbar .bulk-count {
    background: #00BCD4; color: #fff;
    font-size: 12px; font-weight: 700;
    padding: 3px 10px; border-radius: 20px;
    min-width: 28px; text-align: center;
}
#bulkToolbar span.label-txt { color: #94a3b8; font-size: 13px; font-weight: 500; }
#bulkToolbar .bulk-sep { width: 1px; height: 20px; background: rgba(255,255,255,0.1); }
.bulk-btn {
    border: none; border-radius: 8px;
    font-size: 12px; font-weight: 600;
    padding: 7px 14px; cursor: pointer;
    display: inline-flex; align-items: center; gap: 6px;
    transition: all 0.15s ease;
}
.bulk-btn:hover { transform: translateY(-1px); }
.bulk-btn.green  { background:#00BCD4; color:#fff; }
.bulk-btn.amber  { background:#f59e0b; color:#fff; }
.bulk-btn.red    { background:#ef4444; color:#fff; }
.bulk-btn.ghost  { background:rgba(255,255,255,0.08); color:#94a3b8; }
.bulk-btn.ghost:hover { background:rgba(255,255,255,0.15); color:#fff; }
/* Select checkbox column */
.cb-col { width: 36px !important; text-align: center !important; }
.prod-checkbox { width: 16px; height: 16px; accent-color: #00BCD4; cursor: pointer; }
/* ── Quick Edit inline ── */
.qe-btn {
    background: #eff6ff; color: #0070F3;
    border: 1px solid #bfdbfe;
    font-size: 10.5px; font-weight: 600;
    padding: 3px 9px; border-radius: 6px;
    cursor: pointer; transition: all 0.15s;
    display: inline-flex; align-items: center; gap: 4px;
    text-decoration: none;
}
.qe-btn:hover { background: #dbeafe; color: #0056b3; }

/* Remove up/down spinner arrows */
.qe-input::-webkit-outer-spin-button,
.qe-input::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button,
input[type=number]::-webkit-inner-spin-button {
    -webkit-appearance: none !important;
    margin: 0 !important;
}
.qe-input,
input[type=number] {
    -moz-appearance: textfield !important;
}

.qe-input {
    border: 1.5px solid #00BCD4 !important;
    border-radius: 6px !important;
    padding: 4px 6px !important;
    font-size: 12.5px !important;
    font-weight: 700 !important;
    width: 78px !important;
    max-width: 82px !important;
    text-align: center !important;
    outline: none !important;
    background: #ffffff !important;
    color: #0f172a !important;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.06);
    transition: all 0.15s ease;
}
.qe-input:focus {
    border-color: #008ba3 !important;
    box-shadow: 0 0 0 2px rgba(0, 188, 212, 0.25) !important;
}
.qe-save {
    background: #00BCD4; color: #fff;
    border: none; border-radius: 6px;
    padding: 4px 10px; font-size: 11px;
    font-weight: 600; cursor: pointer;
    transition: all 0.15s;
}
.qe-save:hover { background: #0097a7; }
.qe-cancel {
    background: #f1f5f9; color: #64748b;
    border: none; border-radius: 6px;
    padding: 4px 8px; font-size: 11px;
    cursor: pointer;
}

/* ── Premium Actions Column ── */
.action-btn-group {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    flex-wrap: wrap;
    max-width: 240px;
    margin: 0 auto;
}
.act-btn {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 13px;
    color: #4a5568;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.18s cubic-bezier(0.2, 0.8, 0.2, 1);
    text-decoration: none !important;
}
.act-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.06);
}
.act-edit:hover { background: #eff6ff; color: #0070F3; border-color: #bfdbfe; }
.act-price:hover { background: #fffbeb; color: #d97706; border-color: #fde68a; }
.act-stock:hover { background: #ecfeff; color: #0891b2; border-color: #c5f6fa; }
.act-desc:hover { background: #faf5ff; color: #7c3aed; border-color: #e9d5ff; }
.act-status:hover { background: #f0fdf4; color: #16a34a; border-color: #bbf7d0; }
.act-delete:hover { background: #fef2f2; color: #dc2626; border-color: #fca5a5; }

/* ── Status Badges ── */
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-active {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}
.status-inactive {
    background: #fff5f5;
    color: #e11d48;
    border: 1px solid #fecaca;
}
</style>

<section class="content-header">
    <div class="content-header-left">
        <h1>Product List</h1>
    </div>
    <div class="content-header-right"></div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <!-- ══ FEATURE 4: BULK ACTION TOOLBAR ══ -->
            <div id="bulkToolbar">
                <span class="bulk-count" id="bulkCount">0</span>
                <span class="label-txt">products selected</span>
                <div class="bulk-sep"></div>
                <button class="bulk-btn green"  onclick="doBulkAction('activate')">
                    <i class="fa fa-check-circle"></i> Activate
                </button>
                <button class="bulk-btn amber"  onclick="doBulkAction('deactivate')">
                    <i class="fa fa-pause-circle"></i> Deactivate
                </button>
                <button class="bulk-btn red"    onclick="doBulkAction('delete')">
                    <i class="fa fa-trash"></i> Delete
                </button>
                <div class="bulk-sep"></div>
                <button class="bulk-btn ghost"  onclick="clearSelection()">
                    <i class="fa fa-times"></i> Clear
                </button>
            </div>

            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <!-- Checkbox column header -->
                                <th class="cb-col">
                                    <input type="checkbox" id="selectAll" class="prod-checkbox" title="Select All">
                                </th>
                                <th style="width:25%; text-align: left;">Product Name</th>
                                <th style="width:15%">Category Name</th>
                                <th style="width:12%">Date Added</th>
                                <!-- ══ FEATURE 5: Quick Edit columns ══ -->
                                <th style="width:12%">Price <small style="color:#e2e8f0;font-weight:400;">(Quick Edit)</small></th>
                                <th style="width:10%">Stock <small style="color:#e2e8f0;font-weight:400;">(Quick Edit)</small></th>
                                <th style="width:10%">Status</th>
                                <th style="width:16%">Quick Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 0;
                        $sql = "SELECT d.*, 
                                    COALESCE(MIN(p.pp), 0) AS current_price,
                                    COALESCE(SUM(p.total_stock), 0) AS current_stock
                                FROM dishes d
                                LEFT JOIN price p ON p.pcode = d.rs_id
                                GROUP BY d.d_id
                                ORDER BY d.d_id DESC";
                        $query = mysqli_query($con, $sql);

                        if ($query && mysqli_num_rows($query) > 0) {
                            while ($rows = mysqli_fetch_array($query)) {
                                $i++;
                                $statusClass = ($rows['status'] == 1) ? 'bg-g' : (($rows['status'] == 2) ? 'bg-r' : 'bg-prim');
                                $statusText  = ($rows['status'] == 1) ? 'Active' : (($rows['status'] == 2) ? 'Inactive' : 'Scheduled');
                                $did         = (int)$rows['d_id'];
                                $rsid        = htmlspecialchars($rows['rs_id']);
                                $priceLink   = 'add_price.php?prd_id=' . $rsid;
                                $stockLink   = 'upd_stock.php?prd_id=' . $rsid;
                                $updateDescLink   = 'update_desc.php?prd_id=' . $rsid;
                                $statusChangeLink = 'products_status.php?status_id=' . $did;
                                $rawPrice    = (float)$rows['current_price'];
                                $curPrice    = number_format($rawPrice, 2);
                                $curStock    = (int)$rows['current_stock'];
                                echo '<tr class="' . $statusClass . '" data-id="' . $did . '">
                                    <td class="cb-col" style="vertical-align: middle;">
                                        <input type="checkbox" class="prod-checkbox prod-cb" value="' . $did . '" onchange="updateBulkBar()">
                                    </td>
                                    <td style="text-align: left; vertical-align: middle;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <img src="' . htmlspecialchars($rows['img']) . '" class="radius" style="height:46px;width:46px;object-fit:cover;border-radius:10px;border: 1px solid #e2e8f0;flex-shrink:0;" onerror="this.src=\'no_image.png\'"/>
                                            <span style="font-weight:700;color:#1e293b;font-size:13.5px;">' . htmlspecialchars($rows['dish_name']) . '</span>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle;">' . htmlspecialchars($rows['category']) . '</td>
                                    <td style="vertical-align: middle;">' . htmlspecialchars($rows['date_of_adding']) . '</td>

                                    <!-- ══ FEATURE 5: Quick Edit Price ══ -->
                                    <td id="price-cell-' . $did . '" style="vertical-align: middle;">
                                        <span id="price-display-' . $did . '" style="font-weight:800;color:#0f172a;font-size:13.5px;">₹' . $curPrice . '</span>
                                        <br>
                                        <button class="qe-btn" style="margin-top:5px;" onclick="startEditPrice(' . $did . ', ' . $rawPrice . ')">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>
                                    </td>

                                    <!-- ══ FEATURE 5: Quick Edit Stock ══ -->
                                    <td id="stock-cell-' . $did . '" style="vertical-align: middle;">
                                        <span id="stock-display-' . $did . '" style="font-weight:800;color:' . ($curStock <= 10 ? '#dc2626' : '#16a34a') . ';font-size:13.5px;">' . $curStock . '</span>
                                        <br>
                                        <button class="qe-btn" style="margin-top:5px;" onclick="startEditStock(' . $did . ', ' . $curStock . ')">
                                            <i class="fa fa-pencil"></i> Edit
                                        </button>
                                    </td>

                                    <td style="vertical-align: middle;">
                                        <span class="status-badge ' . ($rows['status'] == 1 ? 'status-active' : 'status-inactive') . '">
                                            ' . $statusText . '
                                        </span>
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <div class="action-btn-group">
                                            <a href="product_update.php?menu_upd=' . $did . '" class="act-btn act-edit" title="Edit details"><i class="fa fa-pencil"></i></a>
                                            <a href="' . $priceLink . '" class="act-btn act-price" title="Price update"><i class="fa fa-tag"></i></a>
                                            <a href="' . $stockLink . '" class="act-btn act-stock" title="Stock update"><i class="fa fa-cubes"></i></a>
                                            <a href="' . $updateDescLink . '" class="act-btn act-desc" title="Description"><i class="fa fa-file-text-o"></i></a>
                                            <a href="' . $statusChangeLink . '" class="act-btn act-status" title="Toggle status"><i class="fa fa-power-off"></i></a>
                                            <a href="#" onclick="Products_delete(' . $did . ')" class="act-btn act-delete" data-toggle="modal" data-target="#confirm-delete" title="Delete product"><i class="fa fa-trash-o"></i></a>
                                        </div>
                                    </td>
                                </tr>';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ══ Quick Edit Save API ══ -->
<?php
// Handle quick-edit AJAX save (price/stock)
// These are separate endpoints quick_edit_price.php & quick_edit_stock.php
?>

<!-- Existing Delete Modal — untouched -->
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">Are you sure want to delete this Product?</div>
            <div class="modal-footer">
                <input type="hidden" id="delete_id">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a onclick="Submit_delete()" class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- ══ Bulk Result Toast ══ -->
<div id="bulkToast" style="display:none;position:fixed;bottom:28px;right:28px;z-index:9999;
    background:#0b192c;color:#fff;padding:14px 22px;border-radius:12px;
    font-size:13px;font-weight:600;box-shadow:0 8px 30px rgba(0,0,0,0.25);
    align-items:center;gap:10px;min-width:260px;animation:slideDown 0.3s ease;border-left:4px solid #00BCD4;">
</div>

<?php require_once('footer.php'); ?>

<script>
/* ═══════════════════════════════════════════
   Existing delete handlers — UNTOUCHED
═══════════════════════════════════════════ */
let url_id = 0;
function Products_delete(id_name) {
    document.getElementById("delete_id").value = id_name;
}
function Submit_delete() {
    let id_name = document.getElementById("delete_id").value;
    location.href = `products_status.php?del_id=${id_name}`;
}

/* ═══════════════════════════════════════════
   FEATURE 4: Bulk Action
═══════════════════════════════════════════ */
function updateBulkBar() {
    const checked = document.querySelectorAll('.prod-cb:checked');
    const toolbar = document.getElementById('bulkToolbar');
    const cnt     = document.getElementById('bulkCount');
    if (checked.length > 0) {
        toolbar.style.display = 'flex';
        cnt.textContent = checked.length;
    } else {
        toolbar.style.display = 'none';
    }
}

document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.prod-cb').forEach(cb => {
        cb.checked = this.checked;
    });
    updateBulkBar();
});

function getSelectedIds() {
    return Array.from(document.querySelectorAll('.prod-cb:checked')).map(cb => cb.value).join(',');
}

function clearSelection() {
    document.querySelectorAll('.prod-cb').forEach(cb => cb.checked = false);
    document.getElementById('selectAll').checked = false;
    updateBulkBar();
}

function showToast(msg, type) {
    const t = document.getElementById('bulkToast');
    const icon = type === 'success' ? '✅' : '❌';
    t.innerHTML = `${icon} &nbsp;${msg}`;
    t.style.display = 'flex';
    t.style.borderLeft = `4px solid ${type === 'success' ? '#22c55e' : '#ef4444'}`;
    setTimeout(() => { t.style.display = 'none'; }, 3500);
}

function doBulkAction(action) {
    const ids = getSelectedIds();
    if (!ids) return;
    const labels = { activate:'Activate', deactivate:'Deactivate', delete:'Delete' };
    if (action === 'delete' && !confirm(`Delete selected products? This cannot be undone.`)) return;

    fetch('bulk_product_action.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `action=${action}&ids=${ids}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showToast(data.message || 'Error occurred', 'error');
        }
    })
    .catch(() => showToast('Network error. Try again.', 'error'));
}

/* ═══════════════════════════════════════════
   FEATURE 5: Quick Edit Price & Stock
═══════════════════════════════════════════ */
function formatPriceINR(val) {
    return parseFloat(val || 0).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function startEditPrice(did, currentVal) {
    const rawVal = parseFloat(String(currentVal).replace(/[^0-9.-]/g, '')) || 0;
    const cell = document.getElementById('price-cell-' + did);
    cell.innerHTML = `
        <div style="display:flex;align-items:center;gap:3px;justify-content:center;">
            <span style="color:#64748b;font-size:12px;font-weight:700;">₹</span>
            <input type="number" id="pe-inp-${did}" class="qe-input" value="${rawVal > 0 ? rawVal.toFixed(2) : '0.00'}" step="any" min="0"
                onkeydown="if(event.key==='Enter') savePrice(${did}); if(event.key==='Escape') cancelPrice(${did}, ${rawVal});">
        </div>
        <div style="display:flex;gap:4px;justify-content:center;margin-top:5px;">
            <button class="qe-save" onclick="savePrice(${did})" title="Save"><i class="fa fa-check"></i></button>
            <button class="qe-cancel" onclick="cancelPrice(${did}, ${rawVal})" title="Cancel"><i class="fa fa-times"></i></button>
        </div>`;
    const inp = document.getElementById('pe-inp-' + did);
    if (inp) {
        inp.focus();
        inp.select();
    }
}

function cancelPrice(did, oldVal) {
    const raw = parseFloat(String(oldVal).replace(/[^0-9.-]/g, '')) || 0;
    const cell = document.getElementById('price-cell-' + did);
    cell.innerHTML = `
        <span id="price-display-${did}" style="font-weight:800;color:#0f172a;font-size:13.5px;">₹${formatPriceINR(raw)}</span><br>
        <button class="qe-btn" style="margin-top:5px;" onclick="startEditPrice(${did}, ${raw})">
            <i class="fa fa-pencil"></i> Edit
        </button>`;
}

function savePrice(did) {
    const inp = document.getElementById('pe-inp-' + did);
    const val = parseFloat(inp?.value || 0);
    if (isNaN(val) || val < 0) { showToast('Enter a valid price', 'error'); return; }
    fetch('quick_edit_price.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `d_id=${did}&price=${val}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Price updated to ₹' + formatPriceINR(val), 'success');
            cancelPrice(did, val);
        } else {
            showToast(data.message || 'Save failed', 'error');
        }
    })
    .catch(() => showToast('Network error', 'error'));
}

function startEditStock(did, currentVal) {
    const rawVal = parseInt(currentVal, 10) || 0;
    const cell = document.getElementById('stock-cell-' + did);
    cell.innerHTML = `
        <div style="display:flex;align-items:center;gap:3px;justify-content:center;">
            <input type="number" id="se-inp-${did}" class="qe-input" value="${rawVal}" step="1" min="0" style="width:65px !important;"
                onkeydown="if(event.key==='Enter') saveStock(${did}); if(event.key==='Escape') cancelStock(${did}, ${rawVal});">
        </div>
        <div style="display:flex;gap:4px;justify-content:center;margin-top:5px;">
            <button class="qe-save" onclick="saveStock(${did})" title="Save"><i class="fa fa-check"></i></button>
            <button class="qe-cancel" onclick="cancelStock(${did}, ${rawVal})" title="Cancel"><i class="fa fa-times"></i></button>
        </div>`;
    const inp = document.getElementById('se-inp-' + did);
    if (inp) {
        inp.focus();
        inp.select();
    }
}

function cancelStock(did, oldVal) {
    const raw = parseInt(oldVal, 10) || 0;
    const cell  = document.getElementById('stock-cell-' + did);
    const color = raw <= 10 ? '#dc2626' : '#16a34a';
    cell.innerHTML = `
        <span id="stock-display-${did}" style="font-weight:800;color:${color};font-size:13.5px;">${raw}</span><br>
        <button class="qe-btn" style="margin-top:5px;" onclick="startEditStock(${did}, ${raw})">
            <i class="fa fa-pencil"></i> Edit
        </button>`;
}

function saveStock(did) {
    const inp = document.getElementById('se-inp-' + did);
    const val = parseInt(inp?.value || 0, 10);
    if (isNaN(val) || val < 0) { showToast('Enter a valid quantity', 'error'); return; }
    fetch('quick_edit_stock.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `d_id=${did}&qty=${val}`
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('Stock updated to ' + val + ' units', 'success');
            cancelStock(did, val);
        } else {
            showToast(data.message || 'Save failed', 'error');
        }
    })
    .catch(() => showToast('Network error', 'error'));
}

/* DataTable init — keeping existing behaviour */
$('#example1').DataTable({
    "order": [],
    "columnDefs": [{ "orderable": false, "targets": [0, 4, 5, 6, 7] }]
});
</script>