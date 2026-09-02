<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); }
   error_reporting(0);
   require('include/header.php');
   ?>
<style>
.margin_60 {
    padding-top: 0px !important;
    background-color: white !important;
}

#page {
    background-color: white !important;
}

body {
    background: white !important;
}

/*  */
.box_shadow {
    box-shadow: 0.4px 0.4px 3px rgb(206, 206, 206);
    border-radius: 3px;
}

.quantity_edit {
    /* border:1px solid rgb(221, 221, 221);
    background-color: white; */
    display: flex;
    position: relative;
}

.text-underline:hover {
    color: rgb(51, 144, 252);
}

.decrement {
    border-top: 1px solid rgb(212, 212, 212);
    border-bottom: 1px solid rgb(212, 212, 212);
    border-right: none;
    border-left: 1px solid rgb(212, 212, 212);
    padding: 2px 10px;
    border-radius: 4px 0 0 4px;
    cursor: pointer;
}

.decrement:hover {
    border-top: 1px solid rgb(212, 212, 212);
    border-bottom: 1px solid rgb(212, 212, 212);
    border-right: none;
    border-left: 1px solid rgb(212, 212, 212);
    padding: 2px 10px;
    border-radius: 4px 0 0 4px;
    cursor: pointer;
    background-color: rgb(230, 230, 230);
}

.quantity_display_input {
    width: 50px;
    height: 30px;
    border-right: none;
    border-left: none;
    text-align: center;
    background-color: white !important;
}

.increment {
    border-right: 1px solid rgb(212, 212, 212);
    border-top: 1px solid rgb(212, 212, 212);
    border-bottom: 1px solid rgb(212, 212, 212);
    padding: 2px 10px;
    border-radius: 4px;
    border-radius: 0 4px 4px 0;
    cursor: pointer;
}

.increment:hover {
    border-right: 1px solid rgb(212, 212, 212);
    border-top: 1px solid rgb(212, 212, 212);
    border-bottom: 1px solid rgb(212, 212, 212);
    padding: 2px 10px;
    border-radius: 4px;
    border-radius: 0 4px 4px 0;
    background-color: rgb(230, 230, 230);
    cursor: pointer;
}

._save_latter {
    position: absolute;
    bottom: 0px;
}

.discount_off {
    position: absolute;
    top: 0px;
    display: inline-block;
    padding: 1px 10px;
    background: rgb(204, 12, 57);
    color: "white";
    cursor: pointer;
    font-size: small;
    box-shadow: 0px 0px rgb(218, 218, 218);

}

.discount_off:hover {
    position: absolute;
    top: 0px;
    display: inline-block;
    padding: 1px 10px;
    background: rgb(204, 12, 57);
    box-shadow: 2px 2px rgb(218, 218, 218);
    color: "white";
    cursor: pointer;
    font-size: small;
}
</style>
<script>
function ToasterCheck() {
    let Shopping_remove = "<?=$_SESSION['shopping_remove'];?>";

    if (Shopping_remove == 1) {
        toastr.success('The product has been successfully removed ', 'Success');
    }
}

setTimeout(ToasterCheck, 500);
</script>
<?php
unset($_SESSION['shopping_remove']);
?>

<body>
    <div id="page">
        <header class="kc-header-container">
            <?php  require APP_ROOT . '/views/layouts/navbar.php'; ?>
        </header>
        <div class="sub_header_in sticky_header">
            <div class="container">
                <h1>ShoppingCart</h1>
            </div>

        </div>
        <main>
            <?php 
            include("dbconnect.php");
            ?>
            <div class="container-fluid margin_60">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb" style=" background-color: white !important;">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ShoppingCart</li>
                    </ol>
                </nav>
                <!-- right -->
                <div class="shopping-cart-container ">
                    <form id="form_of">
                        <div class="row">
                            <?php 
                        $user_id = $_SESSION['uid'];
                        include("dbconnect.php");
                        $fetch=mysqli_query($con,"SELECT * FROM card where userid='$user_id' and status='0'");
                        if(mysqli_num_rows($fetch)){ 
                        $count=mysqli_query($con,"SELECT sum(qty) as qty_count FROM card where userid='$user_id' and status='0'");
                        $value_of = mysqli_fetch_array($count);
                        ?>
                            <!-- left start -->
                            <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 ">
                                <h3 class="box-title mb-5">Your Cart Items (<span
                                        class="qty_of_2"><?=$value_of['qty_count'] ?></span>)</h3>

                                <!-- card start -->
                                <?php
                                  $subTotal=0;
                                  $totaquntity=0;
                              
                                  $discounted_price = 0;
                                  $total_discount = 0;
                                   $check_delivery_option =0;
                                   $uniq_id = 0;
                                   $stock_status = 0;
                                  while($row8=mysqli_fetch_array($fetch)){ 
                                    $uniq_id ++;
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
                                      $discount_per_1 =0;
                                      $discount_amount = 0;
                                      $total_price_of =0;
                                      $get_amount = 0;
                                    //   $total_of_get =0;
                                    //   $final_price = 0;
                                      $product_dis_amt = 0;
                                   
                                      $stock_color= '';
                                    //   $psel=mysqli_query($con,"SELECT * FROM dishes inner join price on dishes.rs_id=price.pcode where dishes.rs_id='$product_id' and price.id='$cpr_id' and dishes.status='1'");

                                    $psel = mysqli_query($con, " SELECT  dishes.*,  prd_stock.*, 
                                      price.*  FROM dishes INNER JOIN  prd_stock ON dishes.rs_id = prd_stock.pd_code 
                                     INNER JOIN  price ON prd_stock.price_id = price.id INNER JOIN ( SELECT  price_id, 
                                     MAX(id) AS max_id FROM  prd_stock GROUP BY price_id) latest_price ON prd_stock.id = latest_price.max_id  where price.pcode='$product_id' AND price.id='$cpr_id' AND  dishes.status='1'");




                                      if($prow=mysqli_fetch_array($psel)){ 
                                          $img=$prow['img'];
                                          $id=$prow['id'];
                                          $cprice=$prow['pp'];
                                          $oprice=$prow['oprice'];
                                          $cprqn=$prow['qn'];
                                          $cprwg=$prow['wg'];
                                          $title=$prow['dish_name'];
                                          $s_stock = $prow['s_status'];
                                          $cprid=$prow['rs_id'];
                                          $img=$prow['img'];
                                          $category = $prow['category'];
                                          $brand_name = $prow['brand_name'];
                                          $refund=$prow['refund'];
                                          $del_opt = $prow['deliv_opt'];
                                           $discount_per=$prow['discount'];
                                        $total_stock=  $prow['total'];
                                          $discount_per_1=$prow['discount'] ? $prow['discount'] : 0;
                                          $discount_amount = $camt - ($camt * $discount_per_1 / 100);
                                           $product_dis_amt = $camt - $discount_amount;
                                          $total_discount += $discount_amount;
                                        $get_amount = $discount_amount * ($cgst / 100);
                                        $total_of_get =  $discount_amount +  $get_amount;
                                        $final_price += $total_of_get;

                                           $total_price_of = $subTotal - $total_discount;
                                          if ($refund == 'Refundable') {
                                            $reinfo = "100% Refundable Product";
                                            $textColor = "green"; 
                                        } else {
                                            $reinfo = "Non Refundable Procedure";
                                            $textColor = "red"; 
                                        }
                                        if($del_opt == "Delivery Charge"){ 
                                            $check_delivery_option = 1;
                                        }
                                        if($s_stock == 'Currently Unavailable'){
                                            $stock_status = 1;
                                            $stock_color = 'text-danger';
                                            $stocK_status = 'Currently Unavailable';
                                        }else if($total_stock == 0){
                                            $stock_status = 1;
                                            $stock_color = 'text-danger';
                                            $stocK_status = 'Currently Unavailable';
                                        }else{
                                            $stock_color = 'text-success';
                                            $stocK_status = 'Instock';
                                        }


                                          ?>
                                <input type="hidden" name="prd_id[]" value="<?php echo $product_id; ?>">
                                <input type="hidden" name="price_id[]" value="<?php echo $id; ?>">
                                <input type="hidden" name="imgs[]" value="<?php echo $img; ?>">
                                <input type="hidden" name="get_per[]" value="<?=$cgst; ?>" id="get_per<?=$uniq_id; ?>">
                                <input type="hidden" name="pro_name[]" value="<?php echo $title; ?>">
                                <input type="hidden" name="old_Price[]" value="<?=$oprice; ?>">
                                <div class="card border mt-3">
                                    <div class="card-body pt-3 pb-3 ml-2 mr-2 mt-2 mb-2">
                                        <div class="row">

                                            <div class="col-lg-3  col-12">
                                                <img class="w-100 img-thumbnail" style="height:150px;"
                                                    src="./avadmin/<?php echo $img; ?>" alt="shipping cart">
                                            </div>
                                            <div class="col-lg-6 col-12 ">
                                                <div class="pb-2">

                                                    <div class="row">
                                                        <div class="col-12">
                                                            <h5><?=$title; ?></h5>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <small class="font-weight-bold">Product code:</small>
                                                            <span>
                                                                #<?=$cprid; ?></span><br>
                                                        </div>
                                                        <div class="col-md-6 col-12">
                                                            <small class="font-weight-bold">Size:</small> <span>
                                                                <?=$cprqn; ?>
                                                                <?=$cprwg; ?>.</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col-md-8 col-12">
                                                        <span class="d-block"><i class="fa fa-leaf text-success"
                                                                aria-hidden="true"></i>
                                                            </ul> <?= $category ?></span>
                                                        <span class="d-block mt-1"><i class="fa fa-tags text-success"
                                                                aria-hidden="true"></i>
                                                            </ul><?=$brand_name; ?></span>
                                                        <ul class="bullets mt-1">
                                                            <li style="color: <?php echo $textColor; ?>;"
                                                                class="<?= $refund == 'Refundable' ? 'bullet' : 'bullets-red'; ?>">
                                                                <?php echo $reinfo; ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="col-12 col-md-4 mt-2">
                                                        <?php if($del_opt == "Free delivery"){ ?>
                                                        <small class="_save_latter "><i class="fa-solid fa-truck"></i>
                                                            <?=$del_opt; ?>!.</small>
                                                        <?php }else{ ?>
                                                        <small class="_save_latter "><i class="fa-solid fa-truck"></i>
                                                            Shipping Change extra!.</small>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-12">
                                                <?php
                                        if ($discount_per != 0) {
                                        ?>
                                                <span class="discount_off text-white"><?=$discount_per; ?> %
                                                    off</span>
                                                <?php } ?>
                                                <div class="text-right">
                                                    <span class="w-100 h5" onclick="RemoveCard(<?=$cart_id; ?>)"
                                                        style="cursor:pointer;"><i class="icon-trash border text-danger"
                                                            aria-hidden="true"></i></span>
                                                </div>
                                                <div class="d-flex justify-content-center align-items-center mt-4">
                                                    <h5 class="price-amount font-weight-bold" id="atss">
                                                        <small><?= $_SESSION['selectedCurrency']; ?></small>
                                                        <span
                                                            id="modify_price<?=$uniq_id; ?>"><?php echo  $camt; ?></span>
                                                    </h5>
                                                </div>
                                                <div class="d-flex justify-content-center align-items-center mt-4">
                                                    <!--  -->
                                                    <input type='hidden' class="price-amount1"
                                                        id="current_price<?=$uniq_id; ?>" name="corrent_price[]"
                                                        value="<?= $cprice; ?>">

                                                    <input type="hidden" id="discount_per<?=$uniq_id ?>"
                                                        name="discount_per[]" value="<?=$discount_per_1; ?>">
                                                    <input type="hidden" id="total_stock<?=$uniq_id; ?>"
                                                        value="<?=$total_stock; ?>">

                                                    <input type="hidden" id="modify_input_price<?=$uniq_id; ?>"
                                                        value="<?=$camt; ?>" name="modify_input_price[]"
                                                        class="modify_input_price_cls">

                                                    <input type="hidden" class="discount_amt_cls"
                                                        name="discount_amt_cls[]" id="discount_amt<?=$uniq_id; ?>"
                                                        value="<?=$product_dis_amt; ?>">

                                                    <input type="hidden" name="gst_price[]" class="get_price"
                                                        value="<?=$get_amount; ?>" id="get_price<?=$uniq_id; ?>"><br>
                                                    <input type="hidden" value="<?=$discount_amount; ?>"
                                                        class="gst_total_add" id="get_added<?=$uniq_id; ?>">
                                                    <!--  -->
                                                    <span class="quantity_edit">

                                                        <span class="decrement text-center"
                                                            onclick="Decrement('<?=$uniq_id; ?>')">-</span>
                                                        <input type="text" readonly id="product_qty<?=$uniq_id; ?>"
                                                            value="<?=$qty; ?>" name="pro_qty[]" min="1"
                                                            class="quantity_display_input form-control">
                                                        <span class="increment"
                                                            onclick="Increment('<?=$uniq_id; ?>')">+</span>
                                                    </span>

                                                    <!--  -->
                                                </div>
                                                <div class="text-center mt-1">
                                                    <small class="d-block <?=$stock_color; ?>"><i
                                                            class="fa-solid fa-circle"></i>
                                                        <?= $stocK_status; ?></small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } } ?>
                                <!-- card end -->
                            </div>
                            <!-- right -->
                            <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 mt-4">
                                <div class="card box_shadow mt-5 position-sticky " style="top:7rem">
                                    <div class="card-body ">
                                        <div class="table-responsive">
                                            <table class="table table-borderless">
                                                <tr style="height:40px">
                                                    <?php $final_subtotal = $subTotal - $total_price_of; ?>
                                                    <td>
                                                        <span class="font-weight-bold">Subtotal </span>(<span
                                                            class="qty_of_2"><?=$value_of['qty_count'] ?></span>
                                                        Item)
                                                        <input type="hidden" name="items" id="items_qty"
                                                            value="<?=$value_of['qty_count']; ?>">
                                                    </td>
                                                    <td class="text-right">
                                                        <span><?= $_SESSION['selectedCurrency']; ?>
                                                            <span id="subtotal_span"><?= $subTotal; ?></span></span>
                                                        <input type="hidden" name="subtotal" value="<?= $subTotal; ?>"
                                                            id="subtotal_input">
                                                    </td>

                                                </tr>
                                                <tr class="border-bottom" style="height:40px">
                                                    <td>
                                                        <span class="font-weight-bold">Discount price </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span><?= $_SESSION['selectedCurrency']; ?>
                                                            <span
                                                                id="discount_price_upd"><?=$total_price_of; ?></span></span>
                                                        <input type="hidden" id="discount_price_input"
                                                            name="discount_price" value="<?=$total_price_of; ?>">
                                                    </td>
                                                </tr>
                                                <tr class="border-bottom">
                                                    <td>
                                                        <span class="font-weight-bold mt-5">Taxes+shipping charges
                                                        </span>
                                                    </td>
                                                    <td class="text-right">
                                                        <span class="mt-5">Calculated at checkout</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <span class="font-weight-bold  mt-5" id="delivery_condition"
                                                            style="margin-top:100px;">
                                                            <?php
                                                        if($final_subtotal >= 499){
                                                            echo "<small class='text-success'>
                                                           * This Products Eligible for Free delivery !
                                                            </small>";
                                                        }else if($check_delivery_option == 0){
                                                            echo "<small class='text-success'>
                                                            * This Products Eligible for Free delivery !
                                                             </small>";
                                                        }else{
                                                            echo "<small class='text-danger'>
                                                           * Shipping Change extra !
                                                            </small>";
                                                        }
                                                        ?>
                                                        </span>
                                                        <input type="hidden" name="delivery_option"
                                                            value="<?=$check_delivery_option;?>">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h4 class="font-weight-bold mt-3">Subtotal </h4>
                                                    </td>

                                                    <td class="text-right">
                                                        <h4 class="mt-3"><?= $_SESSION['selectedCurrency']; ?>
                                                            <span id="final_subtotal"><?=$final_subtotal; ?></span>
                                                            <input type="hidden" id="final_subtotal_input"
                                                                name="final_sub_total" value="<?=$final_subtotal; ?>">
                                                            <input type="hidden" name="with_gst_total"
                                                                value="<?= $final_price; ?>" id="final_of_get">
                                                        </h4>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="input-group  ml-4">
                                                    <input type="checkbox" class="form-check-input" name="agree"
                                                        value="1" required id="agree">
                                                    <label for="agree">I agree the <a href="terms_conditions.php">terms
                                                            & conditions</a></label>
                                                </div>
                                                <div class="btn-checkout mt-2 text-center">
                                                    <button type="submit" id="checkout"
                                                        class="btn checkout btn_1 w-100">PROCEED TO
                                                        CHECKOUT</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- right end -->
                            <?php }else{ ?>
                            <div class="col-12 text-center">
                                <img src="./img/no_datas.png" class="img-fluid h-50" alt="">
                            </div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
                <div class="loading" id="loading_spinner" style="display:none;">Loading&#8230;</div>
        </main>
        <?php include('include/footer.php'); ?>
    </div>
    <?php include('include/sign_footer.php'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form_of');
        const loadingSpinner = document.getElementById('loading_spinner');

        form.addEventListener('submit', (event) => {
            event.preventDefault();
            let stock_status = "<?=$stock_status; ?>";
            console.log(stock_status);
            if (stock_status == 0) {
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'shopping_insert.php', true);

                loadingSpinner.style.display = 'block';

                xhr.onload = () => {
                    loadingSpinner.style.display = 'none';
                    let response;
                    try {
                        response = JSON.parse(xhr.responseText);
                    } catch (e) {
                        alert('Failed to parse response.');
                        return;
                    }
                    if (response.status === 1) {
                        window.location.href = response.redirect || `checkout.php?refid=${encodeURIComponent(response.ref_id)}`;
                    } else if (response.require_login && response.redirect) {
                        alert('Please login to complete your hardware purchase.');
                        window.location.href = response.redirect;
                    } else {
                        alert(response.error || 'Error processing checkout.');
                    }
                };
                xhr.onerror = () => {
                    loadingSpinner.style.display = 'none';
                    alert('Network error');
                };

                xhr.send(formData);
            } else {
                alert("stock is Currently Unavailable! ")
            }

        });
    });



    function RemoveCard(id_name) {
        var confirmDelete = confirm("Are you sure you want to Remove this product?");
        if (confirmDelete) {
            window.location.href = 'shopping_remove.php?card_id=' + id_name;
        }
    }


    function Final_calculateFunction() {
        let check_delivery_option = "<?=$check_delivery_option ?>";
        let discount_amt_cls = document.getElementsByClassName("discount_amt_cls");
        let discount_price_upd = document.getElementById("discount_price_upd");
        let delivery_condition = document.getElementById("delivery_condition");
        let total_discount = 0;
        let subtotal = 0;
        for (let element of discount_amt_cls) {
            total_discount += parseFloat(element.value) || 0;
        }
        discount_price_upd.textContent = total_discount;
        document.getElementById("discount_price_input").value = total_discount;

        let modify_input_price = document.getElementsByClassName("modify_input_price_cls");
        for (let element_of of modify_input_price) {
            subtotal += parseFloat(element_of.value) || 0;
        }
        document.getElementById("subtotal_span").textContent = subtotal;
        document.getElementById("subtotal_input").value = subtotal;

        let final_subtotal = subtotal - total_discount;
        document.getElementById("final_subtotal").textContent = final_subtotal;
        document.getElementById("final_subtotal_input").value = final_subtotal;

        // document.getElementById("with_gst_final").value = '0';


        if (final_subtotal >= 499) {
            delivery_condition.innerHTML =
                '<small class="text-success"> * This Products Eligible for Free delivery !</small>';
        } else if (check_delivery_option == 0) {
            delivery_condition.innerHTML =
                '<small class="text-success"> * This Products Eligible for Free delivery !</small>';
        } else {
            delivery_condition.innerHTML = '<small class="text-danger"> * Shipping Change extra !</small>';
        }
    }


    function updatePrice(uniq_id) {
        let product_qty = parseInt(document.getElementById("product_qty" + uniq_id).value);
        let current_price = parseFloat(document.getElementById("current_price" + uniq_id).value);
        let modify_price = document.getElementById("modify_price" + uniq_id);
        let modify_input_price = document.getElementById("modify_input_price" + uniq_id);
        let discount_per = parseInt(document.getElementById("discount_per" + uniq_id).value);
        let discount_amt = document.getElementById("discount_amt" + uniq_id);
        let get_per = document.getElementById("get_per" + uniq_id).value;
        let get_added = document.getElementById("get_added" + uniq_id);
        let gst_total_add = document.getElementsByClassName("gst_total_add");
        let gst_get_price = document.getElementsByClassName("get_price");
        let final_of_get = document.getElementById("final_of_get");

        let get_price = document.getElementById("get_price" + uniq_id);

        let result = current_price * product_qty;

        let discount = (result - (result * discount_per / 100)).toFixed(2);

        discount_amt.value = (result - discount).toFixed(2);
        let discount_balance = result - (result - discount);

        get_added.value = discount_balance;

        let get_amount = discount_balance * (get_per / 100);

        get_price.value = parseFloat(get_amount.toFixed(2));
        modify_price.textContent = result.toFixed(2);
        modify_input_price.value = result.toFixed(2);

        // 
        let quantity_display_input = document.getElementsByClassName("quantity_display_input");
        let total_qty = 0;
        for (let element of quantity_display_input) {
            total_qty += parseFloat(element.value) || 0;
        }
        let qty_update = document.getElementsByClassName("qty_of_2");
        for (let element of qty_update) {
            element.textContent = total_qty;
        }
        document.getElementById("items_qty").value = total_qty;
        Final_calculateFunction();
        let total_with_gst = 0;
        for (let i = 0; i < gst_total_add.length; i++) {
            console.log(parseFloat(gst_get_price[i].value));
            total_with_gst += parseFloat(gst_total_add[i].value) + parseFloat(gst_get_price[i].value);
        }

        final_of_get.value = total_with_gst.toFixed(2);

    }

    function Decrement(uniq_id) {
        let qtyElem = document.getElementById("product_qty" + uniq_id);
        let product_qty = parseInt(qtyElem.value);
        if (product_qty > 1) {
            qtyElem.value = product_qty - 1;
            updatePrice(uniq_id);
        }
    }

    function Increment(uniq_id) {
        let qtyElem = document.getElementById("product_qty" + uniq_id);
        let total_stock = parseInt(document.getElementById("total_stock" + uniq_id).value);
        let product_qty = parseInt(qtyElem.value);
        if (product_qty < total_stock) {
            qtyElem.value = product_qty + 1;
            updatePrice(uniq_id);
        }
    }
    </script>



</body>

</html>