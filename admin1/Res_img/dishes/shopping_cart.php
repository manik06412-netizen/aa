<?php 
   session_start();
//    error_reporting(0);
   require('include/header.php');
   ?>
<style>
.shpcart-subtotal-block {
    padding: 10px;
}
</style>

<body>
    <div id="page">
        <header class="header_in is_sticky menu_fixed">
            <?php include('include/navbar.php'); ?>
        </header>
        <div class="sub_header_in sticky_header">
            <div class="container">
                <h1>ShoppingCart</h1>
            </div>
            <!-- /container -->
        </div>
        <main>
            <?php 
            include("dbconnect.php");
            ?>
            <div class="container-fluid margin_60">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style="background:#F8F8F8 ! important;">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ShoppingCart</li>
                    </ol>
                </nav>
                <!-- start -->
                <div class="shopping-cart-container m-5">
                    <div class="row">
                        <?php 
                     include("dbconnect.php");
                      $fetch=mysqli_query($con,"SELECT * FROM card where userid='$user_id'and status='0'");
                     if(mysqli_num_rows($fetch)){ 
                      ?>
                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 ">
                            <h3 class="box-title">Your Cart Items</h3>
                            <form class="shopping-cart-form" id="shopping_to_check" action="Shopping_insert.php"
                                method="POST">
                                <div class="card">
                                    <div class="card-body">
                                        <input type="hidden" name="userid" value="<?php echo $user_id; ?>">
                                        <table class="shop_table table text-center table-bordered cart-form">
                                            <thead>
                                                <tr>
                                                    <th class="product-name" style="width:30%">Product Name</th>
                                                    <th class="product-price">Product Price(₹)</th>
                                                    <th class="product-quantity">Quantity</th>
                                                    <th class="product-quantity">gst(%)</th>
                                                    <th class="product-subtotal">Total</th>
                                                </tr>
                                            </thead>
                                            <tbody class="align-middle">
                                                <?php 
                                       $subTotal=0;
                                       $totaquntity=0;
                                       while($row8=mysqli_fetch_array($fetch)){
                                           $product_id=$row8['p_id'];
                                           $cart_id=$row8['id'];
                                           $qty=$row8['qty'];
                                           $totaquntity +=$qty;
                                           $camt=$row8['amt'];
                                           $cgst=$row8['gst'];
                                           $cpr_id=$row8['pric_id'];
                                           $ctot=$row8['tot'];
                                           $subTotal += $camt;
                                           //price
                                           $psel=mysqli_query($con,"SELECT * FROM dishes  where rs_id='$product_id' and status='0'");
                                           if($prow=mysqli_fetch_array($psel)){ 
                                               $img=$prow['img'];
                                               $id=$prow['id'];
                                               $cprice=$prow['pp'];
                                               $oprice=$prow['oprice'];
                                               $cprqn=$prow['qn'];
                                               $cprwg=$prow['wg'];
                                               $title=$prow['dish_name'];
                                               $cprid=$prow['rs_id'];
                                               $img=$prow['img'];
                                       
                                               ?>
                                                <input type="hidden" name="prd_id[]" value="<?php echo $product_id; ?>">
                                                <input type="hidden" name="imgs[]" value="<?php echo $img; ?>">
                                                <input type="hidden" name="get_per[]" value ="5">
                                                <input type="hidden" name="pro_name[]" value="<?php echo $title; ?>">
                                                <tr class="cart_item">
                                                    <td class="product-thumbnail align-middle"
                                                        data-title="Product Name">
                                                        <a class="prd-thumb" href="#">
                                                            <figure><img width="113" height="113"
                                                                    src="./admin/<?php echo $img; ?>"
                                                                    alt="shipping cart">
                                                            </figure>
                                                        </a>
                                                        <a class="prd-name" href="#"><?php echo $title; ?></a>
                                                        <div class="action main-part">
                                                            <input type="hidden" class="cart_id" name="cart_id[]"
                                                                value="<?php echo $cart_id; ?>">
                                                            <input type="hidden" class="current_price"
                                                                name="current_price[]" value="<?php echo $cprice; ?>">
                                                            <a href="javascript:void(0);"
                                                                class="edit"><?php echo $cprqn."-".$cprwg; ?></a>
                                                            <a href="#"
                                                                onclick="confirmDelete(<?php echo $product_id; ?>,<?php echo $id; ?>)"
                                                                class="remove h4 text-danger"><i class="icon-trash"
                                                                    aria-hidden="true"></i></a>
                                                        </div>
                                                    </td>
                                                    <td class="product-price align-middle " data-title="Price">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="price price-contain">
                                                                    <span class="price-amount" id="atss"
                                                                        style="font-size:25px;color:#6F666F;"><?php echo $cprice; ?>
                                                                    </span>
                                                                    <del><span class="old_p1 price-amount" id="atss1"
                                                                            style="font-size:20px;color:red;"><?php echo $oprice; ?>
                                                                        </span></del>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="product-quantity align-middle" data-title="Quantity">
                                                        <div class="quantity-box type1">
                                                            <div class="qty-input">
                                                                <input type="number" name="pro_qty[]"
                                                                    onkeydown="return false" value="<?php echo $qty; ?>"
                                                                    onchange="updateTotal(this)" min="1" max="20"
                                                                    class="quantity-input form-control">
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="product-quantity1 align-middle" data-title="gst">
                                                        <div class="quantity-box type1">
                                                            <div class="price price-contain">
                                                                <span class="price-amount"
                                                                    style="font-size:20px;color:#6F666F;"><?php echo $cgst; ?>
                                                                    (%)</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="product-subtotal align-middle total-column"
                                                        data-title="Total">
                                                        <div class="price price-contain">
                                                            <span class="currencySymbol"></span><span
                                                                class="price-amount"
                                                                style="font-size:25px;color:#6F666F;"
                                                                id="atss"><?php echo $camt; ?></span>
                                                        </div>
                                                        <input type='hidden' class="price-amount1"
                                                            name="corrent_price[]" value="<?php echo $ctot; ?>">
                                                        <input type='hidden' class="price-amount1_old"
                                                            name="old_price_modify[]" value="<?php echo $oprice; ?>">
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                                <tr class="cart_item wrap-buttons">
                                                    <td class="wrap-btn-control" colspan="4">
                                                        <a class="btn btn_add" href="index.php">Back to Shop</a>
                                                    </td>
                                                    <td>
                                                        <a onclick="Clear_all(<?php echo $user_id ?>)"
                                                            href="javascript:void(0);" class="btn btn-info"
                                                            type="reset">Clear all</a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                        </div>
                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12">
                            <div class="shpcart-subtotal-block card">
                                <div class="card-body">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                <b class="stt-name">Subtotal <span class="sub">(<sapn class="totalqty">
                                                            <?php echo $totaquntity; ?></span> Items)</span>
                                                </b>
                                            </td>
                                            <td>
                                                <span class="stt-price subtotal">₹ <?php echo $subTotal; ?></span>
                                                <input type="hidden" name="subtot" class="subtotalpr"
                                                    value="<?php echo $subTotal; ?>">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <b class="stt-name " style="font-size:17px;">Total (tax incl.)</b>
                                            </td>
                                            <td>
                                                <span class="stt-price subtotal" style="font-size:20px;"> ₹
                                                    <?php echo $subTotal; ?></span>
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="btn-checkout mt-2 text-center">
                                        <a href="#" id="checkout" class="btn checkout btn_1">PROCEED TO CHECKOUT</a>
                                    </div>
                                    <script>
                                    document.getElementById('checkout').addEventListener('click', function(event) {
                                        event.preventDefault();
                                        document.getElementById('shopping_to_check').submit();
                                    });
                                    </script>
                                    <div class="biolife-progress-bar text-center mt-3">
                                        <p class="pickup-info" style="font-size:13px;"><b>
                                                <i class="fa fa-credit-card" aria-hidden="true"></i></b> 100% Payment
                                            Protection</p>
                                        <p class="pickup-info" style="font-size:13px;"><b>
                                                <i class="fa fa-lock" aria-hidden="true"></i></b> Safe and Secure
                                            Payments
                                        </p>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                        <?php }else{ ?> <div class="col-12 d-flex justify-content-center">
                            <img src="./img/no-product.png">
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
                <script src="https:/ / ajax.googleapis.com / ajax / libs / jquery / 3.5 .1 / jquery.min.js "></script>
                <script>
                function Clear_all(id_name) {
                    var confirmDelete = confirm("Are you sure you want to delete this all products?");
                    if (confirmDelete) {
                        window.location.href = "removeallitems.php?userid=" + id_name;
                    } else {

                    }
                }

                function updateTotal(input) {
                    var row = $(input).closest('tr');
                    var price = parseFloat(row.find('.product-price .price-amount').text());
                    var price1 = parseFloat($('.product-price .old_p1.price-amount').text());
                    var quantity = parseInt($(input).val());
                    //var gst = parseInt($(input).val());
                    var total1 = price1 * quantity;
                    var gtot1 = total1;
                    var total = price * quantity;
                    var gtot = total;
                    row.find('.product-subtotal .price-amount').text(gtot.toFixed(2));
                    row.find('.product-subtotal .price-amount1').val(gtot.toFixed(2));
                    row.find('.product-subtotal .price-amount1_old').val(gtot1.toFixed(2));
                    updateCartSummary();
                }

                function updateCartSummary() {
                    var subtotal = 0;
                    $('.product-subtotal .price-amount').each(function() {
                        subtotal += parseFloat($(this).text());
                    });
                    $('.subtotal').text('₹' + subtotal.toFixed(2));
                    $('.subtotalpr').val(subtotal.toFixed(2));
                    var quantity = 0;
                    $('.product-quantity .quantity-input').each(function() {
                        quantity += parseFloat($(this).val());
                    });
                    $('.totalqty').text(parseInt(quantity));
                }
                </script>
            </div>
        </main>
        <?php include('include/footer.php'); ?>
    </div>
    <?php include('include/sign_footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function confirmDelete(categoryId, price_id) {
        var confirmDelete = confirm("Are you sure you want to delete this product?");
        if (confirmDelete) {
            window.location.href = 'shopping_remove.php?id=' + categoryId + '&price=' + price_id;
        } else {}
    }
    </script>
    <script>
    function deleteTemp(id) {
        $.ajax({
            url: 'del_tmp.php',
            type: 'GET',
            data: {
                id: id
            },
            success: function(response) {
                console.log('Server response:',
                    response);
                if (response === 'success') {
                    location.reload();
                } else if (response === 'error') {
                    location.reload();

                } else if (response === 'invalid_id') {
                    location.reload();

                } else {
                    location.reload();

                }
            },
            error: function() {
                alert('Error in AJAX call.');
            }
        });
    }
    </script>
</body>

</html>