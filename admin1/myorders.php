<!DOCTYPE html>
<html class="no-js" lang="en">

<?php 

include "includes/header.php"; 
include "includes/navbar.php";


?>
<script>
$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})
</script>

<body class="biolife-body">

    <!--Hero Section-->
    <div class="hero-section hero-background">
        <h1 class="page-title">MY ORDERS</h1>
    </div>

    <!--Navigation section-->
    <div class="container">
        <nav class="biolife-nav nav-86px">
            <ul>
                <li class="nav-item"><a href="index-2.html" class="permal-link">Home</a></li>
                <li class="nav-item"><span class="current-page">Myorders</span></li>
            </ul>
        </nav>
    </div>

    <div class="page-contain contact-us">

        <!-- Main content -->
        <div id="main-content" class="main-content">

            <div class="container">

                <div class="row">

                    <div id="main-content" class="main-content">
                        <div class="container">

                            <!--Cart Table-->
                            <div class="shopping-cart-container">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <h3 class="box-title">Your cart items</h3>
                                        <?php
         include 'config.php';
         $user_id=$_SESSION['uid'];
         // Fetch orders
         $query = "SELECT * FROM final INNER JOIN chekout ON final.refid = chekout.ref_id  where final.status='0'and  final.user_id='$user_id' group by order_id ";
         $sql = mysqli_query($con, $query);
         
         if (mysqli_num_rows($sql) > 0) {
            while ($ree = mysqli_fetch_array($sql)) {
         ?>
                                        <style>
                                        .shop_table.cart-form {
                                            border-bottom: 1px solid black;
                                        }
                                        </style>

                                        <table class="shop_table cart-form">

                                            <thead style="background-color: #f2f2f2;">
                                                <tr>
                                                    <th class="product-name">Your Order

                                                        <p class="val"> <?php echo  $ree['date']; ?></p>
                                                    </th>
                                                    <th class="product-price">Ship to
                                                        <p class="h5"> <?php echo $ree['fname']; ?></p>
                                                        <p style="margin:30px;">
                                                        <?php
                                                        $addressQuery = "SELECT * FROM address WHERE id = " . $ree['address_id'];
    $addressResult = mysqli_query($con, $addressQuery);

    while ($address = mysqli_fetch_array($addressResult)) {
        
    
    ?>
                                                            <a target="_blank"
                                                                href="https://www.quackit.com/bootstrap/bootstrap_4/tutorial/"
                                                                data-toggle="tooltip" data-placement="top"
                                                                title=" <?php echo $ree['flat'] .   "," .$ree['District']."," . $ree['state'] . "," . $ree['country']  ." Mobile No - " . $ree['mobile'] .",pin code:" . $ree['pin'] ;?>">
                                                                <?php echo  $ree['fname']; ?>
                                                            </a>
                                                            <?php
                                                            }
                                                            ?>
                                                        </p>



                                                    </th>
                                                    <th class="product-quantity">Placed by
                                                        
                                                        <?php
                                                        $s = $ree['user_id'];
                                                        $ff = mysqli_query($con, "select * from user where user_id=$s");
                                                        if ($lee1 = mysqli_fetch_array($ff)) {
                                                            echo '  <p class="val">  '. $lee1['fname'] . $lee1['lname'];
                                                        }
                                                        ?>
                                                    </th>
                                                    <th class="product-subtotal">Total Amount
                                                        <p class="text-danger it"><?php echo $ree['total']; ?></p>
                                                    </th>
                                                    <th class="product-subtotal">Order Id
                                                        <p class="val"> #<?php echo $ree['order_id']; ?></p>
                                                        <a href="invoice.php?orid=<?php echo $ree['order_id']; ?>"
                                                            class="btn btn-info"> Invoice</a>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="cart_item">
                                                    <?php
                                                        if ($ree['status'] == 0) {
                                                            echo "ORDERED  " . $ree['date'] ."<br/><br/>" ;
                                                        } elseif ($ree['status'] == 1) {
                                                            echo "SHIPPED  " . $ree['date'];
                                                        } else {
                                                            echo "DELIVERED  " . $ree['date'];
                                                        }
                                                       
                  
                                                        $itemQuery = "SELECT * FROM chekout WHERE ref_id = " . $ree['refid'];
                                                        $itemSql = mysqli_query($con, $itemQuery);
                                                        
                                                        while ($item = mysqli_fetch_array($itemSql)) {
                                                        ?>
                                                    <td class="product-thumbnail" data-title="Product Name">
                                                        <?php
                                                $lee2 = mysqli_query($con, "SELECT * FROM dishes WHERE rs_id = " . $item['pr_id']);
                                                
                                                if ($lle3 = mysqli_fetch_array($lee2)) {
                                                    // echo '<img src="./admin/' . $lle3['img'] . '" alt="' . $item['p_name'] . '" width="200px" height="200px"class="logodi">';
                                                    echo '<a class="prd-thumb" href="#">
                                                    <figure><img width="113" height="113"
                                                               src="./admin/' . $lle3['img'] . '"
                                                               alt="shipping cart"></figure>
                                                   </a>';
                                                
                                                }
                                                ?>

                                                        <b><a class="prd-name"
                                                                href="#"><?php echo strtoupper($item['p_name']); ?></a>
                                                        </b>
                                                        <p></p>
                                                        <div class="action">
                                                            <a href="#" class="edit"><i class="fa fa-pencil"
                                                                    aria-hidden="true"></i></a>
                                                            <a href="#" class="remove"><i class="fa fa-trash-o"
                                                                    aria-hidden="true"></i></a>
                                                        </div>
                                                    </td>
                                                    <td class="product-price" data-title="Price">
                                                        <div class="price price-contain">
                                                            <ins><span class="price-amount"><span
                                                                        class="currencySymbol">₹</span><?php echo $item['ct_py']; ?></span></ins>
                                                            <!-- <del><span class="price-amount"><span
                                                                        class="currencySymbol">₹</span><?php echo  $lle3['oprice']; ?></span></del>  -->
                                                        </div>
                                                    </td>
                                                    <td></td>
                                                    <td class="product-quantity" data-title="Quantity">
                                                        <div class="quantity-box type1">
                                                            <div class="qty-input">

                                                                <a href="details.php?id=<?php echo $item['pr_id'] ?>"
                                                                    class="btn btn-warning mr-2">Buy Again</a>

                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="product-quantity" data-title="Quantity">
                                                        <div class="quantity-box type1">
                                                            <div class="qty-input">


                                                                <a href="rev.php?id=<?php echo $item['pr_id']; ?>&user=<?php echo $lee1['fname'] . $lee1['lname']; ?>"
                                                                    class="btn btn-info mr-2 mt-2">Write a product
                                                                    review</a>
                                                            </div>
                                                        </div>
                                                    </td>

                                                </tr>
                                                <?php
                                                }
                                                ?>


                                            </tbody>
                                        </table>
                                        <?php
                                            }
                                        }
                                            else {

                                                ?>
                                           <div class="row">
                     <div class="col-lg-2"></div>
                     <div class="col-lg-8">
                        <div class="contain-product deal-layout contain-product__deal-layout ">
                           <div class="product-thumb text-center">
                              <a href="index.php" class="link-to-product">
                              <img src="img/error.png" width="80px" height="80px">
                              </a>
                           </div>
                           <div class="info">
                              <div class="slide-down-box">
                                 <p class="message">Back to Shop and order to enjoy.
                                 </p>
                                 <h3 class="text-center">
                                    NO ORDERS FOUND
                                 </h3>
                                 <div class="buttons">
                                    <a href="index.php" class="btn add-to-cart-btn">Back To Home</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-2"></div>
                  </div>
                  <?php

                                                 }
                                            ?>
                                    </div>

                                </div>
                            </div>

                            <!--Related Product-->
                            <div class="product-related-box single-layout">
                                <div class="biolife-title-box lg-margin-bottom-26px-im">
                                    <span class="biolife-icon icon-organic"></span>
                                    <span class="subtitle">All the best item for You</span>
                                    <h3 class="main-title">Recently Added Products</h3>
                                </div>
                                <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile"
                                    data-slick='{"rows":1,"arrows":true,"dots":false,"infinite":true,"speed":400,"slidesMargin":0,"slidesToShow":5, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 4}},{"breakpoint":992, "settings":{ "slidesToShow": 3, "slidesMargin":20}},{"breakpoint":768, "settings":{ "slidesToShow": 2, "slidesMargin":10}}]}'>
                                    <?php 
                        include('dbconnect.php');
                        $fetcate = mysqli_query($con, "SELECT DISTINCT d.* FROM dishes d JOIN price p ON d.rs_id = p.pcode where d.status='0' LIMIT 15");
                        while ($rocat = mysqli_fetch_array($fetcate)) {
                            $ct_img = $rocat['img'];
                            $ct_name = $rocat['dish_name'];
                            $ct_nid = $rocat['rs_id'];
                            $ct_category = $rocat['category'];
                        
                        ?>
                                    <li class="product-item">
                                        <div class="contain-product layout-default">
                                            <form action="shopping_related_add.php"
                                                id="addwishlist_product<?php echo $ct_nid; ?>" method="POST">

                                                <div class="product-thumb">
                                                    <a href="#" class="link-to-product">
                                                        <img src="./admin/<?php echo $ct_img; ?>" alt="dd"  width="270" height="200" style="height: 240px !important" class="product-thumnail">
                                                    </a>
                                                </div>
                                                <div class="info">
                                                    <b class="categories"><?php echo $ct_category; ?></b>
                                                    <h4 class="product-title"><a href="#"
                                                            class="pr-name"><?php echo $ct_name; ?></a>
                                                    </h4>
                                                    <div class="price ">
                                                        <?php
                                       include("dbconnect.php");
                                       $gbb = mysqli_query($con, "SELECT * FROM price where pcode='$ct_nid'");
                                       if ($gb13 = mysqli_fetch_array($gbb)) {
                                          $pc=$gb13['id'];
                                          $currentp = $gb13['pp'];
                                          $oldp = $gb13['oprice'];
                                       
                                       } ?>
                                                        <input type="hidden" name="qty" value="1">
                                                        <input type="hidden" name="prdid"
                                                            value="<?php echo $ct_nid; ?>">
                                                        <input type="hidden" name="pid" value="<?php echo $pc; ?>">
                                                        <ins><span class="price-amount"><span
                                                                    class="currencySymbol" style="font-size: smaller;">₹</span><?php echo $currentp; ?></span></ins>
                                                        <del><span class="price-amount"><span
                                                                    class="currencySymbol" style="font-size: smaller;">₹</span><?php echo $oldp; ?></span></del>
                                                    </div>
                                                    <div class="slide-down-box">
                                                        
                                                        <div class="buttons">
                                                            <?php
                                          include("dbconnect.php");
                                          $syl = 0;
                                          
                                           $zqswl = mysqli_query($con, "SELECT * FROM watch_list where userid='$uid' and pr_id='$ct_nid'");
                                            if (mysqli_num_rows($zqswl)) {
                                             $syl = 1;
                                              } ?>
                                                            <a href="javascript:void(0);"
                                                                onclick="toggleWatch(<?php echo  $ct_nid; ?>)"
                                                                class="btn wishlist-btn">
                                                                <i <?php if ($syl == 1) { ?>style='color:green;'
                                                                    <?php } ?> class="fa fa-heart" aria-hidden="true"
                                                                    id="heart-icon1-<?php echo $ct_nid; ?>"></i>
                                                            </a>
                                                            <a href="#" class="btn add-to-cart-btn"
                                                                id="addwishlist<?php echo $ct_nid; ?>"><i
                                                                    class="fa fa-cart-arrow-down"
                                                                    aria-hidden="true"></i>add to
                                                                cart</a>
                                                            <a href="#" class="btn compare-btn"><i class="fa fa-random"
                                                                    aria-hidden="true"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                            <script>
                                            document.getElementById('addwishlist<?php echo $ct_nid; ?>')
                                                .addEventListener('click', function(event) {
                                                    event.preventDefault();
                                                    document.getElementById(
                                                        'addwishlist_product<?php echo $ct_nid; ?>').submit();
                                                });
                                            </script>
                                        </div>
                                    </li>
                                    <?php } ?>
                                </ul>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOOTER -->
    <?php include "includes/footer.php"; ?>



    <!-- Scroll Top Button -->
    <a class="btn-scroll-top"><i class="biolife-icon icon-left-arrow"></i></a>

    <script src="assets/js/jquery-3.4.1.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.countdown.min.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/jquery.nicescroll.min.js"></script>
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/biolife.framework.js"></script>
    <script src="assets/js/functions.js"></script>
</body>

</html>