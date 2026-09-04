<?php
   session_start();
   function fav(){
       include('./dbconnect.php');
       if(isset($_SESSION['uid'])){
           $user_id=$_SESSION['uid'];
       $fov=mysqli_query($con,"SELECT * FROM watch_list where userid='$user_id'");
       $row=mysqli_num_rows($fov);
       }else{
           $row=0;
       }
       return $row;
   }
   ?>
<?php
   function addCard(){
       include('./dbconnect.php');
       if(isset($_SESSION['uid'])){
       $user_id=$_SESSION['uid'];
       $card=mysqli_query($con,"SELECT * FROM card where userid='$user_id' and status='0'");
       $row1=mysqli_num_rows($card);
   }else{
       $row1=0;
   }
       return $row1;
   }
   ?>
<style>
   .biolife-cart-info .minicart-block .btn-control .btn{
   display: inline-block;
   width: calc( 50% - 8px );
   padding: 16px 10px 17px;
   float: left;
   font-size: 14px;
   color: #eeeeee;
   line-height: 1;
   font-weight: 700;
   text-transform: uppercase;
   background-color: #800000;
   border: none;
   border-radius: 99999999px;
   margin-top: 20px;
   }
   .biolife-cart-info .minicart-block .btn-control .btn:hover{
   background-color: #388306 !important;
   color: #ffffff !important;
   }
</style>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<div class="header-middle biolife-sticky-object ">
   <div class="container">
      <div class="row">
         <div class="col-lg-3 col-md-4 col-xs-6">
            <a href="./index.php" class="logo"><img src="img/kottanf.jpg" alt="logo" width="255px"
               height="150px"></a>
         </div>
         <div class="col-lg-6 col-md-6 hidden-sm hidden-xs">
            <div class="primary-menu">
               <ul class="menu biolife-menu clone-main-menu clone-primary-menu" id="primary-menu"
                  data-menuname="main menu">
                  <li class="menu-item"><a href="./index.php">Home</a></li>
                  <li class="menu-item menu-item-has-children has-megamenu">
                     <a href="#" class="menu-name" data-title="Shop">Category</a>
                     <div class="wrap-megamenu lg-width-900 md-width-750">
                        <div class="mega-content">
                           <?php
                              include('dbconnect.php');
                              $sql = "SELECT * FROM `res_category` ORDER BY `c_name` ASC";
                              $result = $con->query($sql);
                              
                              if ($result->num_rows > 0) {
                                  while ($row = $result->fetch_assoc()) {
                                      ?>
                           <div class="col-lg-3 col-md-3 col-xs-12 md-margin-bottom-0 xs-margin-bottom-25">
                              <div class="wrap-custom-menu vertical-menu">
                                 <h4 class="menu-title"><?php echo $row['c_name']; ?></h4>
                                 <ul class="menu">
                                    <?php
                                       $c_name = $row['c_name'];
                                       
                                       $sql2 = "SELECT DISTINCT subcate FROM dishes WHERE category = '$c_name'";
                                       
                                       $result2 = $con->query($sql2);
                                       
                                       if ($result2->num_rows > 0) {
                                           while ($row2 = $result2->fetch_assoc()) {
                                               ?>
                                    <li><a
                                       href="allcategories.php?cat=<?php echo $row['c_name']; ?>&subcate=<?php echo $row2['subcate']; ?>"><?php echo $row2['subcate']; ?></a>
                                    </li>
                                    <?php
                                       }
                                       } else {
                                       echo "<p>No results found</p>";
                                       }
                                       ?>
                                 </ul>
                              </div>
                           </div>
                           <?php
                              }
                              }
                              ?>
                        </div>
                     </div>
                  </li>
                  <li class="menu-item"><a href="about.php">About Us</a></li>
                  <li class="menu-item"><a href="contact.php">Contact</a></li>
               </ul>
            </div>
         </div>
         <div class="col-lg-3 col-md-2 col-md-6 col-xs-6">
            <div class="biolife-cart-info">
               <div class="mobile-search">
                  <a href="javascript:void(0)" class="open-searchbox"><i class="biolife-icon icon-search"></i></a>
                  <div class="mobile-search-content">
                     <form action="#" class="form-search" name="mobile-seacrh" method="get">
                        <a href="#" class="btn-close"><span class="biolife-icon icon-close-menu"></span></a>
                        <input type="text" name="s" class="input-text" value="" placeholder="Search here...">
                        <select name="category">
                           <option value="-1" selected>All Categories</option>
                           <option value="vegetables">Vegetables</option>
                           <option value="fresh_berries">Fresh Berries</option>
                           <option value="ocean_foods">Ocean Foods</option>
                           <option value="butter_eggs">Butter & Eggs</option>
                           <option value="fastfood">Fastfood</option>
                           <option value="fresh_meat">Fresh Meat</option>
                           <option value="fresh_onion">Fresh Onion</option>
                           <option value="papaya_crisps">Papaya & Crisps</option>
                           <option value="oatmeal">Oatmeal</option>
                        </select>
                        <button type="submit" class="btn-submit">go</button>
                     </form>
                  </div>
               </div>
               <div class="wishlist-block hidden-sm hidden-xs">
                  <a href="wishlist.php" class="link-to">
                  <span class="icon-qty-combine">
                  <i class="icon-heart-bold biolife-icon"></i>
                  <span class="qty"><?php echo fav(); ?></span>
                  </span>
                  </a>
               </div>
               <div class="minicart-block">
                  <div class="minicart-contain">
                     <a href="javascript:void(0)" class="link-to">
                     <span class="icon-qty-combine">
                     <i class="icon-cart-mini biolife-icon"></i>
                     <span class="qty"><?php echo addCard() ?></span>
                     </span>
                     <span class="title"> </span>
                     <span class="sub-total">₹ 0.00</span>
                     </a>
                     <?php if(addCard() !=0){ ?>
                     <div class="cart-content">
                        <div class="cart-inner">
                           <form action="Shopping_insert.php" id="menubar_Checkout_form"
                              enctype="multipart/form-data" method="POST">
                              <input type="hidden" name="userid" value="<?php echo $user_id; ?>">
                              <ul class="products">
                                 <?php 
                                    include('dbconnect.php');
                                    $fetch=mysqli_query($con,"SELECT * FROM card where userid='$user_id'and status='0'");
                                    
                                    $subTotal=0;
                                    $totaquntity=0;
                                    while($row_price=mysqli_fetch_array($fetch)){
                                        $product_id=$row_price['p_id'];
                                        $qty=$row_price['qty'];
                                        $totaquntity +=$qty;
                                        $camt=$row_price['amt'];
                                        $cart_id=$row_price['id'];
                                        $cgst=$row_price['gst'];
                                        $cpr_id=$row_price['pric_id'];
                                        $ctot=$row_price['tot'];
                                        $subTotal += $camt;
                                        //price table
                                        $PSEL=mysqli_query($con,"SELECT dishes.img, dishes.dish_name, dishes.rs_id, price.id, price.pp, price.oprice, price.gst
                                        FROM dishes
                                        INNER JOIN price ON dishes.rs_id = price.pcode
                                        WHERE price.pcode = '$product_id'
                                        AND price.id = '$cpr_id'");
                                        if($PROW=mysqli_fetch_array($PSEL)){ 
                                            $img=$PROW['img'];
                                            $gstt=$PROW['gst'];
                                            $priceid=$PROW['id'];
                                            $cprice=$PROW['pp'];
                                            $oprice=$PROW['oprice'];
                                            $title=$PROW['dish_name'];
                                            $cprid=$PROW['rs_id'];
                                            ?>
                                 <li>
                                    <div class="minicart-item">
                                       <!--corrent amounts-->
                                       <input type="hidden" class="cart_id" name="cart_id[]" value="<?php echo $cart_id ?>" id="<?php echo $cprid; ?>_cartid">
                                       <input type="hidden" class="current_price" name="current_price[]" value="<?php echo $cprice; ?>" id="<?php echo $cprid; ?>_current">
                                       <input type="hidden" name="prd_id[]" value="<?php echo $cprid ; ?>">
                                       <input type="hidden" name="price_id[]"
                                          value="<?php echo $priceid ; ?>">
                                       <input type="hidden" name="pro_name[]" value="<?php echo $title ; ?>">
                                       <input type="hidden" name="corrent_price[]"
                                          value="<?php echo $ctot; ?>"
                                          id="<?php echo $cprid; ?>curr_modify">
                                       <input type="hidden" name="old_price_modify[]" value="<?php echo  $oprice; ?>" id="<?php echo $cprid; ?>old_modify">
                                       <input type="hidden" id="<?php echo $cprid; ?>_old"
                                          name="old_price[]" value="<?php echo $oprice; ?>">
                                       <input type="hidden" id="<?php echo $cprid; ?>_gst"
                                          name="gst_price[]" value="<?php echo $gstt; ?>">
                                       <input type="hidden" value="<?php echo $img; ?>" name="imgs[]">
                                       <!--corrent amounts-->
                                       <div class="thumb">
                                          <a href="#"><img src="./admin/<?php echo $img; ?>" width="90"
                                             height="90" alt="National Fresh"></a>
                                       </div>
                                       <div class="left-info">
                                          <div class="product-title"><a href="#"
                                             class="product-name"><?php echo $title; ?></a></div>
                                          <div class="price">
                                             <ins><span class="price-amount product_price"
                                                id="<?php echo $cprid; ?>current1"><span
                                                class="currencySymbol">₹ </span><?php echo $camt; ?></span></ins>
                                             <del><span class="price-amount"
                                                id="<?php echo $cprid; ?>old1"><span
                                                class="currencySymbol">₹ </span><?php echo  $oprice; ?></span></del><br>
                                             <b class="price-amount"> Incl:GST ( +<?php echo $gstt; ?>
                                             </span>%
                                             )</b>
                                          </div>
                                          <div class="qty">
                                             <label >Qty:</label>
                                             <input type="number" min="1" max="20" onkeydown="return false" class="input-qty" name="pro_qty[]" id="<?php echo $cprid; ?>_quantity" value="<?php echo $qty; ?>">
                                          </div>
                                       </div>
                                       <div class="action">
                                          <a
                                             href="#" onclick="confirmDeleteproduct(<?php echo $product_id; ?>,<?php echo $cpr_id; ?>)"><i
                                             class="fa fa-trash-o" aria-hidden="true"></i></a>
                                       </div>
                                    </div>
                                    <!--amount values increes script start-->
                                    <script>
                                       $(document).ready(function() {
                                           $("#<?php echo $cprid; ?>_current,#<?php echo $cprid; ?>_gst, #<?php echo $cprid; ?>_quantity ,#<?php echo $cprid; ?>_old ")
                                               .on("input", function() {
                                                   var camount = parseFloat($(
                                                           "#<?php echo $cprid; ?>_current")
                                                       .val()
                                                       .replace("₹ ", "") || 0);
                                                   var camountgst = parseFloat($(
                                                           "#<?php echo $cprid; ?>_gst")
                                                       .val()
                                                       .replace("₹ ", "") || 0);
                                                   var oamount1 = parseFloat($(
                                                           "#<?php echo $cprid; ?>_old")
                                                       .val()
                                                       .replace("₹ ", "") || 0);
                                                   var tquantity = parseInt($(
                                                           "#<?php echo $cprid; ?>_quantity")
                                                       .val()) || 0;
                                                   var ctotal = camount * tquantity;
                                                   var ctotal1 = oamount1 * tquantity;
                                                   $("#<?php echo $cprid; ?>current1").html("₹ " +
                                                       ctotal);
                                                   $("#<?php echo $cprid; ?>curr_modify").val(ctotal);
                                                   $("#<?php echo $cprid; ?>old1").html("₹ " +
                                                       ctotal1);
                                                       $("#<?php echo $cprid; ?>old_modify").val(ctotal1);
                                       
                                               });
                                       });
                                    </script>
                                    <!--amount values increes script start-->
                                 </li>
                                 <!--deals of day-->
                                 <?php }
                                    }
                                    $fetch1=mysqli_query($con,"SELECT * FROM card where userid='$user_id'and status='0'");
                                    
                                    $subTotal1=0;
                                    $totaquntity1=0;
                                    while($row9=mysqli_fetch_array($fetch1)){
                                        $product_id1=$row9['p_id'];
                                        $qty1=$row9['qty'];
                                        $totaquntity1 +=$qty;
                                        $camt1=$row9['amt'];
                                        $cgst1=$row9['gst'];
                                        $cpr_id1=$row9['pric_id'];
                                        $ctot1=$row9['tot'];
                                        $cart_id=$row9['id'];
                                        $subTotal1 += $camt1;      
                                    $delas_pd=mysqli_query($con,"SELECT * FROM dishes inner join deals_prot on dishes.rs_id=deals_prot.d_id where dishes.rs_id='$product_id1' and deals_prot.deals_id='$cpr_id1' and dishes.status='1'");
                                    if($delas_row=mysqli_fetch_array($delas_pd)){ 
                                    $dimg=$delas_row['img'];
                                    $did=$delas_row['deals_id'];
                                    $dcprice=$delas_row['dis_pri'];
                                    $dcprs_id=$delas_row['rs_id'];
                                    $doprice=$delas_row['actual_pri'];
                                    $dtitle=$delas_row['dish_name'];
                                    $dtgst=$delas_row['gst'];
                                    
                                    
                                    ?>
                                 <li>
                                    <div class="minicart-item">
                                       <input type="hidden" class="cart_id" name="cart_id[]" value="<?php echo $cart_id ?>" id="<?php echo $did; ?>_cartid">
                                       <input type="hidden" id="<?php echo $did; ?>_current"
                                          name="current_price[]" class="current_price" value="<?php echo $dcprice; ?>">
                                       <input type="hidden" name="old_price_modify[]" value="<?php echo  $doprice; ?>" id="<?php echo $did; ?>old_modify">
                                       <input type="hidden" name="prd_id[]"
                                          value="<?php echo $dcprs_id ; ?>">
                                       <input type="hidden" name="price_id[]" value="<?php echo $did ; ?>">
                                       <input type="hidden" name="pro_name[]"
                                          value="<?php echo $dtitle ; ?>">
                                       <input type="hidden" name="corrent_price[]"
                                          value="<?php echo $ctot1; ?>"
                                          id="<?php echo $did; ?>current1modify">
                                       <input type="hidden" value="<?php echo $dimg; ?>" name="imgs[]">
                                       <!--corrent amounts-->
                                       <input type="hidden" id="<?php echo $did; ?>_old" name="old_price[]"
                                          value="<?php echo $doprice; ?>">
                                       <input type="hidden" id="<?php echo $did; ?>_gst" name="gst_price[]"
                                          value="<?php echo $dtgst; ?>">
                                       <!--corrent amounts-->
                                       <div class="thumb">
                                          <a href="#"><img src="./admin/<?php echo $dimg; ?>" width="90"
                                             height="90" alt="National Fresh"></a>
                                       </div>
                                       <div class="left-info">
                                          <div class="product-title"><a href="#"
                                             class="product-name"><?php echo $dtitle; ?></a></div>
                                          <div class="price">
                                             <ins><span class="price-amount product_price"
                                                id="<?php echo $did; ?>current1"><span
                                                class="currencySymbol">₹ </span><?php echo $dcprice; ?></span></ins>
                                             <del><span class="price-amount"
                                                id="<?php echo $did; ?>old1"><span
                                                class="currencySymbol">₹ </span><?php echo  $doprice; ?></span></del><br>
                                             <b class="price-amount">Incl:GST ( +<?php echo $dtgst; ?>
                                             </span>%
                                             )</b>
                                          </div>
                                          <div class="qty mycart_qty">
                                             <label>Qty:</label>
                                             <input type="number" MIN='1' onkeydown="return false"
                                                MAX='20' class="input-qty" name="pro_qty[]"
                                                id="<?php echo $did; ?>_quantity"
                                                value="<?php echo $qty1; ?>" >
                                          </div>
                                       </div>
                                       <div class="action">
                                          <a
                                             href="#"onclick="confirmDeleteproduct(<?php echo $product_id; ?>,<?php echo $did; ?>)"><i
                                             class="fa fa-trash-o" aria-hidden="true"></i></a>
                                       </div>
                                    </div>
                                    <!--amount values increes script start-->
                                    <script>
                                       $(document).ready(function() {
                                           $("#<?php echo $did; ?>_current,#<?php echo $did; ?>_gst, #<?php echo $did; ?>_quantity ,#<?php echo $did; ?>_old ")
                                               .on("input", function() {
                                                   var camount = parseFloat($(
                                                           "#<?php echo $did; ?>_current")
                                                       .val()
                                                       .replace("₹ ", "") || 0);
                                                   var oamount1 = parseFloat($(
                                                           "#<?php echo $did; ?>_old")
                                                       .val()
                                                       .replace("₹ ", "") || 0);
                                                   var camountgst1 = parseFloat($(
                                                           "#<?php echo $did; ?>_gst")
                                                       .val()
                                                       .replace("₹ ", "") || 0);
                                                   var tquantity = parseInt($(
                                                           "#<?php echo $did; ?>_quantity")
                                                       .val()) || 0;
                                                   var ctotal = camount * tquantity;
                                                
                                                   var ctotal1 = oamount1 * tquantity;
                                                   
                                       
                                                   $("#<?php echo $did; ?>current1").html("₹ " +
                                                       ctotal);
                                                   $("#<?php echo $did; ?>current1modify").val(ctotal);
                                                       
                                                   $("#<?php echo $did; ?>old1").html("₹ " +
                                                       ctotal1);
                                                       $("#<?php echo $did; ?>old_modify").val(ctotal1);
                                       
                                               });
                                       });
                                    </script>
                                    <!--amount values increes script start-->
                                 </li>
                                 <?php  
                                    }  
                                         
                                         }?>
                              </ul>
                              <p class="btn-control">
                                 <a href="shopping_cart.php" class="btn view-cart">view cart</a>
                                 <a href="#" id="menubar_Checkout" class="btn ">checkout</a>
                              </p>
                        </div>
                        <input type="text" name="subtot" class="sub-totall" value="<?php echo $subTotal; ?>">
                        </form>
                        <script>
                           $(document).ready(function() {
                               calculatemycart();
                           
                               function calculatemycart() {
                                   var subtotal = 0;
                                   $(".product_price").each(function() {
                                       var price = parseFloat($(this).text().replace("₹", ""));
                                       subtotal += price;
                                   });
                                   $(".sub-total").text("₹ " + subtotal.toFixed(2));
                                   $(".sub-totall").val(subtotal.toFixed(2));
                               }
                           
                               $(document).on('change', '.mycart_qty input[type="number"]', function() {
                                   calculatemycart();
                               });
                        
                               $(document).on('DOMNodeInserted', '.product_price', function() {
                                   calculatemycart();
                               });
                           });
                        </script>
                        <!--wishlist part-->
                        <script>
                           document.getElementById('menubar_Checkout').addEventListener('click', function(event) {
                               event.preventDefault();
                               document.getElementById('menubar_Checkout_form').submit();
                           });
                        </script>
                     </div>
                     <?php } ?>
                  </div>
               </div>
               <div class="mobile-menu-toggle">
                  <a class="btn-toggle" data-object="open-mobile-menu" href="javascript:void(0)">
                  <span></span>
                  <span></span>
                  <span></span>
                  </a>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script>
   $(document).ready(function(){
       $('.input-qty').change(function(){
           var qty = $(this).val();
           var current_price = $(this).closest('.minicart-item').find('.current_price').val();
           var cart_id = $(this).closest('.minicart-item').find('.cart_id').val();
           console.log("Qty:", qty, "Current Price:", current_price, "Cart ID:", cart_id);
           
           $.ajax({
               url: 'add_qty_cart.php',
               type: 'GET',
               data: {
                   qty: qty,
                   current_price: current_price,
                   cart_id: cart_id
               },
               success: function(response) {
                   console.log(response); 
               },
               error: function(xhr, status, error) {
                   console.error(xhr.responseText); 
               }
           });
       });
   });
</script>
<script>
    function confirmDeleteproduct(categoryId,price_id) {
        var confirmDelete = confirm("Are you sure you want to delete this product?");
        if (confirmDelete) {
            window.location.href = 'cart_value_remove.php?id=' + categoryId + '&price=' +price_id ;
        } else {
            // Do nothing or handle cancellation
        }
    }
    </script>