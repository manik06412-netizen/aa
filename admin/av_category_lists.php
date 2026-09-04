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
?>
<?php
session_start();
error_reporting(0);
?>
<?php require_once('header.php'); 
$title = isset($_SESSION['title']) ? $_SESSION['title'] : 'Category List';
echo "<script>var sessionTitle = '$title';</script>";
?>
<style>
.bg-prim {
    background-color: rgb(146, 187, 211) !important;
}
</style>
<?php

function No_of_products($con,$category,$status){
    $sql=mysqli_query($con,"SELECT * FROM dishes where category= '$category' AND status =$status ");
    return mysqli_num_rows($sql);
}
function No_of_products_1($con,$category){
    $sql=mysqli_query($con,"SELECT * FROM dishes where category= '$category' ");
    return mysqli_num_rows($sql);
}
?>


<section class="content-header">
    <div class="content-header-left">
        <h1>Category List</h1>
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
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:10%">Categories</th>
                                <th style="width:10%">Total Products</th>
                                <th style="width:10%">Active Products</th>
                                <th style="width:10%">Inactive Products</th>
                                <th style="width:10%">Scheduled Products</th>
                                <th style="width:10%">View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $category_list = mysqli_query($con,"SELECT * FROM res_category");
                            while($results_of = mysqli_fetch_array($category_list)){ 
                            ?>
                            <tr>
                                <td>
                                    <img src="<?=$results_of['fpath']; ?>"
                                        onerror="this.onerror=null; this.src='Res_img/no_image.png';"
                                        style="width:40px; height:40px;" alt=""><br>
                                    <span class="text-bold "> <?=$results_of['c_name'] ?></span>
                                </td>
                                <td><?=No_of_products_1($con,$results_of['c_name']); ?></td>
                                <td class="bg-g"><?=No_of_products($con,$results_of['c_name'],1); ?></td>
                                <td class="bg-r"><?=No_of_products($con,$results_of['c_name'],2); ?></td>
                                <td class="bg-prim"><?=No_of_products($con,$results_of['c_name'],3); ?></td>
                                <td>
                                    <a href="category_of_products_list.php?category_name=<?=$results_of['c_name'] ?>"
                                        class="btn btn-success btn-sm">View</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
</section>


<?php require_once('footer.php'); ?>