<!DOCTYPE html>
<html lang="en">
<?php
      include("../config.php");
      error_reporting(0);
      session_start();
      ?>
<style>
.totals_table tr {
    height: 35px;
}

.total-price {
    color: rgb(213, 22, 22);
    font-weight: bold;
}
</style>
<?php include "head.php"; ?>
<?php 
include '../dbconnect.php';

// Define the number of records per page
$records_per_page = 5;

// Get the current page number from the query string, defaulting to 1 if not set
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting record for the current page
$start_from = ($page - 1) * $records_per_page;

// Query to fetch the latest 5 records with pagination
$query = "SELECT * FROM final 
          INNER JOIN chekout ON final.refid = chekout.ref_id  
          WHERE final.status='0' 
          GROUP BY order_id 
          ORDER BY final.id DESC 
          LIMIT $start_from, $records_per_page";

$sql = mysqli_query($con, $query);
?>

<body class="fix-header fix-sidebar">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper">
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <?php while ($ree = mysqli_fetch_array($sql)) { ?>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <button class="btn btn-info">Order Id: <?=$ree['order_id']; ?></button>
                                <a href="c_order.php?id=<?=$ree['order_id']; ?>"
                                    class="btn btn-outline-warning float-right">Confirm Order</a>
                            </div>
                            <div class="card-body">
                                <table class="table">
                                    <?php 
                            $itemQuery = mysqli_query($con,"SELECT * FROM final WHERE refid = " . $ree['refid']);
                            while($item = mysqli_fetch_array($itemQuery)){  
                               $dish_category = mysqli_query($con,"SELECT * FROM dishes WHERE rs_id=". $item['product_id']);
                               $dish_details = mysqli_fetch_array($dish_category);
                               $product_img_path = $dish_details['img']; 
                               $product_without_pro = str_replace('../admin/', '', $product_img_path);
                            ?>
                                    <tr>
                                        <td class="text-center" style="width:15%">
                                            <img src="<?=$product_without_pro;?>" width="50" height="50" alt="">
                                        </td>
                                        <td style="width:25%">
                                            <span class="text-dark"><?=$dish_details['dish_name']; ?></span>
                                            <span class="d-block"><?=$dish_details['category'] ?></span>
                                        </td>
                                        <td style="width:15%" class="text-left">
                                            <span>Rs. <?=$item['sel_price']; ?></span>
                                        </td>
                                        <td style="width:15%">
                                            <span>Qty: <?=$item['qty']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span>Ordered On: <?=$item['order_date']; ?> </span>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </table>
                                <div class="row">
                                    <div class="col-7">
                                        <div class="card1 mt-3 ml-3">
                                            <h6 class="text-success">Delivery Address :</h6>
                                            <?php 
                                    $addressQuery = mysqli_query($con, "SELECT * FROM address WHERE id = " . $ree['address_id']);
                                    while ($address = mysqli_fetch_array($addressQuery)) {
                                        echo "<strong>Name: </strong>" . $address['fname'] . ",<br/>";
                                        echo  $address['flat'] . ",<br/>" . $address['District'] . ",<br/>" . $address['state'] . ",<br/>" . $address['country'] . "-" . $address['pincode'] . "<br/>";
                                    }
                                    ?>
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <table style="width:100%" class="totals_table table-borderless">
                                            <tr>
                                                <td>Sub Total:</td>
                                                <td>Rs.<?=$ree['total_amt']; ?></td>
                                            </tr>
                                            <tr>
                                                <td>Discount Price:</td>
                                                <td>Rs.<?=$ree['discount_amt']; ?>.00</td>
                                            </tr>
                                            <tr>
                                                <td>Shipping Charge:</td>
                                                <td>Rs.<?=$ree['shipping_amt']; ?>.00</td>
                                            </tr>
                                            <tr>
                                                <td>Promo Code Price:</td>
                                                <?php 
                                        $promo_price = $ree['shipping_amt'] ? $ree['shipping_amt'] : 0.00;
                                        ?>
                                                <td>Rs.<?=$promo_price; ?></td>
                                            </tr>
                                            <tr>
                                                <td class="total-price">Order Total:</td>
                                                <td class="total-price">Rs.<?=$ree['final_amt']; ?>.00</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>

                <!-- Pagination Controls -->
                <?php 
        // Query to get the total number of records
        $total_query = "SELECT COUNT(DISTINCT order_id) AS total FROM final WHERE final.status='0'";
        $total_result = mysqli_query($con, $total_query);
        $total_row = mysqli_fetch_array($total_result);
        $total_records = $total_row['total'];

        // Calculate total pages
        $total_pages = ceil($total_records / $records_per_page);
        ?>

                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php if ($page > 1) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?=($page - 1); ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php } ?>
                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?=($i == $page) ? 'active' : '';?>">
                            <a class="page-link" href="?page=<?=$i;?>"><?=$i;?></a>
                        </li>
                        <?php } ?>
                        <?php if ($page < $total_pages) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?=($page + 1); ?>" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    </div>
    <footer class="footer"> © 2018 All rights reserved. </footer>
    </div>
    </div>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="js/lib/datatables/datatables.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="js/lib/datatables/cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="js/lib/datatables/cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="js/lib/datatables/cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <script src="js/lib/datatables/datatables-init.js"></script>
</body>

</html>