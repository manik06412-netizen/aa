<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}

error_reporting(0);
require_once('header.php'); 
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Category List';
echo "<script>var sessionTitle = " . json_encode($title) . ";</script>";
?>
<style>
.bg-prim {
    background-color: rgb(146, 187, 211) !important;
}
</style>
<?php
function No_of_products($con, $category, $status){
    $cat_clean = mysqli_real_escape_string($con, trim($category));
    $sql = mysqli_query($con, "SELECT d_id FROM dishes WHERE (TRIM(category) = '$cat_clean' OR category LIKE '%$cat_clean%') AND status = " . intval($status));
    return $sql ? mysqli_num_rows($sql) : 0;
}
function No_of_products_1($con, $category){
    $cat_clean = mysqli_real_escape_string($con, trim($category));
    $sql = mysqli_query($con, "SELECT d_id FROM dishes WHERE TRIM(category) = '$cat_clean' OR category LIKE '%$cat_clean%'");
    return $sql ? mysqli_num_rows($sql) : 0;
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Category List</h1>
    </div>
    <div class="content-header-right">
        <a href="add_category.php" class="btn btn-primary btn-xs new_btn" style="background-color: #FF851B; border-color: #FF851B; color: #ffffff; text-decoration: none;"><i class="fa fa-plus"></i> Add Category</a>
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
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:25%; text-align: left;">Category</th>
                                <th style="width:15%">Total Products</th>
                                <th style="width:15%">Active Products</th>
                                <th style="width:15%">Inactive Products</th>
                                <th style="width:15%">Scheduled Products</th>
                                <th style="width:15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $category_list = mysqli_query($con, "SELECT * FROM res_category ORDER BY c_id DESC");
                            if ($category_list && mysqli_num_rows($category_list) > 0) {
                                while($results_of = mysqli_fetch_array($category_list)){ 
                                    $c_name = htmlspecialchars(trim($results_of['c_name']));
                                    $c_img  = htmlspecialchars($results_of['fpath'] ?? '');
                                    $total  = No_of_products_1($con, $results_of['c_name']);
                                    $act    = No_of_products($con, $results_of['c_name'], 1);
                                    $inact  = No_of_products($con, $results_of['c_name'], 2);
                                    $sched  = No_of_products($con, $results_of['c_name'], 3);
                            ?>
                            <tr>
                                <td style="text-align: left; vertical-align: middle;">
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <img src="<?=$c_img ?>"
                                             onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                             style="width:42px; height:42px; object-fit:cover; border-radius:8px; border:1px solid #e2e8f0; flex-shrink:0;" alt="">
                                        <span style="font-weight:700; color:#1e293b; font-size:13.5px;"><?=$c_name ?></span>
                                    </div>
                                </td>
                                <td style="vertical-align: middle;"><span class="badge-count" style="background:#eff6ff !important;color:#2563eb !important;border-color:#bfdbfe !important; font-size:12px; font-weight:700; padding:4px 10px; border-radius:6px; border:1px solid;"><?=$total ?></span></td>
                                <td style="vertical-align: middle;"><span class="badge-count badge-count-active" style="background:#ecfdf5 !important;color:#059669 !important;border-color:#a7f3d0 !important; font-size:12px; font-weight:700; padding:4px 10px; border-radius:6px; border:1px solid;"><?=$act ?></span></td>
                                <td style="vertical-align: middle;"><span class="badge-count badge-count-inactive" style="background:#fff5f5 !important;color:#e11d48 !important;border-color:#fecaca !important; font-size:12px; font-weight:700; padding:4px 10px; border-radius:6px; border:1px solid;"><?=$inact ?></span></td>
                                <td style="vertical-align: middle;"><span class="badge-count" style="background:#fef3c7 !important;color:#d97706 !important;border-color:#fde68a !important; font-size:12px; font-weight:700; padding:4px 10px; border-radius:6px; border:1px solid;"><?=$sched ?></span></td>
                                <td style="vertical-align: middle;">
                                    <a href="category_of_products_list.php?category_name=<?=urlencode(trim($results_of['c_name'])) ?>"
                                       class="btn-table-action" style="border-radius:8px; font-size:12px; font-weight:600; padding:6px 14px; display:inline-flex; align-items:center; gap:6px; background:#fff; border:1.5px solid #0070F3; color:#0070F3; transition:all 0.15s ease; text-decoration:none;" title="View Products">
                                        <i class="fa fa-eye"></i> View Products
                                    </a>
                                </td>
                            </tr>
                            <?php 
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

<!-- Export functionality scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Print
    const printBtn = document.getElementById('print_table');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            window.print();
        });
    }

    // CSV Export
    const csvBtn = document.getElementById('export_table');
    if (csvBtn) {
        csvBtn.addEventListener('click', function() {
            let csv = [];
            const rows = document.querySelectorAll("#example1 tr");
            for (let i = 0; i < rows.length; i++) {
                let row = [], cols = rows[i].querySelectorAll("td, th");
                for (let j = 0; j < cols.length - 1; j++) {
                    let text = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                    row.push('"' + text.replace(/"/g, '""') + '"');
                }
                csv.push(row.join(","));
            }
            let csvFile = new Blob([csv.join("\n")], {type: "text/csv"});
            let downloadLink = document.createElement("a");
            downloadLink.download = "category_list.csv";
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = "none";
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        });
    }
});
</script>

<?php require_once('footer.php'); ?>