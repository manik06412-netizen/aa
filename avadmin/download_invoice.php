<?php
   error_reporting(0);
   session_start();
   ?>
<?php require_once('header.php');
   include('../controller/reuse.php');
   ?>
<link rel="stylesheet" href="./css/loader.css">
<style>
.bg-prim {
    background-color: rgb(146, 187, 211) !important;
}

.btn-secondary {
    background-color: rgb(108, 117, 125) !important;
    color: white;
}

.new_btn {
    background-color: #FF851B !important;
    color: white !important;
    border: 1px solid #FF851B !important;
}
.mt-4{
    margin-top: 10px;
}
.p-5{
    padding: 25px;
}
.mr-2{
    margin-right: 10px;
}
.pr-5{
    padding-right:15px ;
}
.pl-5{
    padding-left:15px ;
}
.pb-5{
    padding-bottom:15px ;
}

.pt-2{
    padding-top:10px ;
}
.d-block{
    display: block;
}
</style>
<?php 
// 
$footer_contact = mysqli_query($con,"SELECT * FROM footer_contact");
$address_details =mysqli_fetch_array($footer_contact);
$CON_CONTACT_ADDRESS = $address_details['contact_address'];
$CON_CONTACT_PHONE = $address_details['contact_phone'];
$CON_ALTERNATE_NUMBER = $address_details['alternate_number'];
$CON_CONTACT_EMAIL =  $address_details['contact_email'];

   include("./config.php");
   $order_id = $_GET['order_id'];
   $itemQuery = mysqli_query($con,"SELECT * FROM final WHERE order_id = " . $order_id);
   $item = mysqli_fetch_array($itemQuery);
   ?>
<!-- end  -->
<section class="content-header">
    <div class="content-header-left">
        <h1>Download Invoice</h1>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12 ">
            <div class="box box-info" style="padding:20px;">

                <div class="row ">
                    <div class="col-md-12 mt-4">
                        <div class="card mt-4" style="margin: 40px;">
                            <div class="card-body">
                                <div class="invoice_card">
                                    <div class="row p-5 ">
                                        <div class="col-4 col-lg-4 d-flex align-items-center justify-content-center">
                                            <img src="../img/av.PNG" width="150" height="40" alt=""
                                                class="logo_normal">
                                        </div>
                                        <div class="col-4 col-lg-4"></div>
                                        <div class="col-4 col-lg-4 text-right">
                                            <h5>KARUDA COMPUTERS</h5>
                                            <span class="d-block" >
                                                <?php echo $CON_CONTACT_ADDRESS; ?>,
                                            </span>
                                            <span class="d-block" >
                                                <strong class="mr-2">Phone no: </strong><?=$CON_CONTACT_PHONE;  ?>,
                                                <?= $CON_ALTERNATE_NUMBER; ?>,
                                            </span>
                                            <span class="d-block" >
                                                <strong class="mr-2">Email address: </strong><?=$CON_CONTACT_EMAIL;  ?>
                                            </span>
                                        </div>
                                        <div class="w-100 border-bottom mt-4"></div>
                                    </div>
                                    <div class="row pt-2 pr-5 pl-5 pb-5">
                                        <div class="col-12 text-center mb-2" style="margin-bottom:30px;">
                                            <h5>Tax Invoice</h5>
                                        </div>
                                        <div class="col-1"></div>
                                        <div class="col-6">
                                            <strong>Bill No:</strong><span><?=$item['refid'] ?></span>
                                            <strong class="d-block mt-2" >Invoice To *</strong>
                                            <?php
                                            $addressQuery = mysqli_query($con, "SELECT * FROM address WHERE id = " . $item['address_id']);
                                            while ($address = mysqli_fetch_array($addressQuery)) {
                                            echo "<strong>Name: </strong>" . $address['fname'] . ",<br/>";
                                            echo  $address['flat'] . ",<br/>" . $address['District'] . ", " . $address['state'] . ", " . $address['country'] . "-" . $address['pincode'] . "<br/>";
                                            echo "<strong class='mr-3'>Phone no:</strong>".$address['mobile'] .".";
                                            }
                        
                                               ?>
                                        </div>
                                        <div class="col-5">
                                            <strong class="d-block">Invoice Details *</strong>
                                            <span class="mt-2 mb-3 d-block">Invoice No :
                                                <span><?=$item['order_id']; ?></span></span>
                                            <span class="mt-2 mb-3 d-block">Date :
                                                <span><?=$item['order_date']; ?></span></span>
                                        </div>
                                        <div class="w-100 border-bottom mt-4"></div>
                                        <div class="col-12 text-center mb-2">
                                            <h5 class="mt-4">Items List</h5>
                                        </div>
                                        <!--  -->
                                        <table class="table" style="margin-top: 30px;">
                                            <tr class="bg-primary">
                                                <th>#</th>
                                                <th>Item Name</th>
                                                <th>Quantity</th>
                                                <th>Price/Unit</th>
                                                <th>Gst</th>
                                                <th>Discount</th>
                                                <th>Amount</th>
                                            </tr>
                                            <?php 
                                                $s_no =0;
                                                $single_prod_price = 0;
                                                $total_off =0;
                                                $transaction_id =0;
                                                $itemQuery1 = mysqli_query($con,"SELECT * FROM final  WHERE order_id = " . $order_id);
                                                while($item_of_products = mysqli_fetch_array($itemQuery1)){ 
                                                    $dish_category = mysqli_query($con,"SELECT * FROM dishes where rs_id=". $item_of_products['product_id']);
                                                    $dish_details = mysqli_fetch_array($dish_category);
                                                    $s_no ++;
                                                    if($transaction_id ==0){
                                                        $transaction_id = $item_of_products['trans_id'];
                                                    }
                                                    $single_prod_price = $item_of_products['sel_price'] / $item_of_products['qty'];
                                                $total_off += $item_of_products['sel_price'];
                                                $total_amt=$single_prod_price+$item_of_products['gst_per']-$item_of_products['discount_amt'];
                                                ?>
                                            <tr>
                                                <td>
                                                    <?=$s_no; ?>
                                                </td>
                                                <td style="width:25%">
                                                    <span class="text-dark"><?=$dish_details['dish_name']; ?></span>
                                                </td>
                                                <td style="width:15%">
                                                    <span>Qty: <?=$item_of_products['qty']; ?></span>
                                                </td>
                                                <td style="width:15%">
                                                    <span>Rs. <?=$single_prod_price; ?></span>
                                                </td>
                                                <td style="width:15%" class="text-left">
                                                    <span>Rs. <?=$item_of_products['gst_per']; ?> </span>
                                                </td>
                                                <td style="width:15%" class="text-left">
                                                    <span>Rs. <?=$item_of_products['discount_amt']; ?> </span>
                                                </td>
                                                <td style="width:15%" class="text-left">
                                                    <span>Rs. <?= $total_amt; ?> </span>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                          
                                        </table>
                                        <div class="w-100 border-bottom mt-4"></div>
                                        <div class="col-6 col-lg-6  col-md-6 col-sm-6 col-xl-6">
                                            <h6 class="mt-4">Payment Details:</h6>
                                            <table class="table table-borderless">
                                                <tr>
                                                    <th>Transaction Id:</th>
                                                    <td><?= $transaction_id; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Transaction Method:</th>
                                                    <td>UPI</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-6 col-lg-6  col-md-6 col-sm-6 col-xl-6 ">
                                            <table class="table mt-4 ">
                                                <tr class="bg-primary">
                                                    <th colspan="2">Amounts</th>
                                                </tr>
                                              
                                                <tr>
                                                    <?php 
                                                    $include_tax_of = $item['total_amt'] - $item['shipping_amt'] ?? 0;
                                                        ?>
                                                    <th>Subtotal (disc & tax incl.)</th>
                                                    <td>Rs. <?=$include_tax_of; ?></td>
                                                </tr>
                                                <tr>
                                                    <th>Shipping Price</th>
                                                    <td>Rs. <?=$item['shipping_amt']; ?></td>
                                                </tr>
                                                <tr>
    <th>Promo Amount (disc)</th>
    <?php $promo_price = $item['promo_amt'] ? number_format($item['promo_amt'], 2) : number_format(0, 2); ?>
    <td>Rs. <?= $promo_price; ?></td>
</tr>

                                                <tr>
                                                    <th>Pay Amount</th>
                                                    <th>Rs. <?=$item['final_amt']; ?></th>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 text-right mt-4">
                        <button onclick="printInvoice()" class="btn btn-warning"><i class="fa fa-download" aria-hidden="true"></i> Download
                            Invoice</button>
                    </div>
                </div>
            </div>
        </div>
</section>
<div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
<script>
$('#example1').DataTable({
    "order": [],
    "columnDefs": [{
        "orderable": false,
        "targets": "_all"
    }]
});
</script>

<?php require_once('footer.php'); ?>
<script>
    $('.wish_bt.liked').on('click', function(c) {
        $(this).parent().parent().parent().fadeOut('slow', function(c) {});
    });
    </script>
    <script>
    function printInvoice() {
        var invoiceContent = document.querySelector('.invoice_card').innerHTML;

        var printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write('<html><head><title>Invoice</title>');
        var styles = document.querySelectorAll('link[rel="stylesheet"], style');
        styles.forEach(function(style) {
            printWindow.document.write(style.outerHTML);
        });
        printWindow.document.write('</head><body>');
        printWindow.document.write(invoiceContent);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.onload = function() {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    }
    </script>