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
// session_start();
error_reporting(0);

if (isset($_POST['report_name'])) {
    $_SESSION['active_report'] = $_POST['report_name'];
    header("Location: " . $_SESSION['active_report']); 
    exit(); 
}

require_once('header.php');
?>
<style>
.reports_img {
    width: 20%;
    margin:5px 0 ;
}

.btn_blocks {
    display: inline-block;
}

.card {
    margin: 16px;
    border: 0.1px solid rgb(216, 216, 216);
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    box-shadow: 0.2px 0.2px 10px rgb(216, 216, 216);
}
.card:hover {
    margin: 16px;
    border: 0.1px solid rgb(216, 216, 216);
    border-radius: 10px;
    padding: 15px;
    text-align: center;
    cursor: pointer;
    box-shadow: none;
}
.report_title{
    font-weight: bold;
}
</style>

<section class="content-header">
    <div class="content-header-left">
        <h1>Reports Center</h1>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <div class="row">
                        <div class="col-6 col-lg-6">
                            <div class="card" onclick="redirectToPage('report_customer.php')">
                                <h4 class="report_title">Customers</h4>
                                <img src="./Res_img/service.png" class="reports_img" alt="Customers Report">
                                <div>
                                    <a href="javascript:void(0);" class="btn btn_blocks btn-primary">View Reports</a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6 col-lg-6">
                            <div class="card" onclick="redirectToPage('report_products.php')">
                                <h4 class="report_title">Products</h4>
                                <img src="./Res_img/product-chain.png" class="reports_img" alt="Products Report">
                                <div>
                                    <a href="javascript:void(0);" class="btn btn_blocks btn-primary">View Reports</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-6">
                            <div class="card" onclick="redirectToPage('report_order_placed.php')">
                                <h4 class="report_title">Orders</h4>
                                <img src="./Res_img/project.png" class="reports_img" alt="Orders Report">
                                <div>
                                    <a href="javascript:void(0);" class="btn btn_blocks btn-primary">View Reports</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 col-lg-6">
                            <div class="card" onclick="redirectToPage('product_inventory.php')">
                                <h4 class="report_title">Inventory</h4>
                                <img src="./Res_img/material-management.png" class="reports_img" alt="Inventory Report">
                                <div>
                                    <a href="javascript:void(0);" class="btn btn_blocks btn-primary">View Reports</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
function redirectToPage(reportName) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = window.location.href;

    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'report_name';
    input.value = reportName;

    form.appendChild(input);
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php require_once('footer.php'); ?>
