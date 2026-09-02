<meta property="og:image" content="<?php echo $ct_img; ?>">
<style>
   .sell {
   border: 2px solid #800000;
   background-color: #800000;
   color: black;
   margin: 0px 0px 20px 10px;
   font-size: 12px;
   /* border-radius: 10px; */
   }
   .sell:hover {
   border: 2px solid #800000;
   background-color: #7E7E7E;
   color: black;
   margin: 0px 0px 20px 10px;
   border-radius: 10px;
   }
   .input1 {}
   .sell option {
   color: black;
   }
   .custom-input-group {
   display: flex;
   align-items: stretch;
   }
   .custom-input-group-prepend {
   display: flex;
   align-items: center;
   padding: 0.375rem 0.75rem;
   margin-bottom: 0;
   font-size: 1rem;
   font-weight: 400;
   line-height: 1.5;
   color: #495057;
   text-align: center;
   white-space: nowrap;
   background-color: #e9ecef;
   border: 1px solid #ced4da;
   border-radius: 0.25rem 0 0 0.25rem;
   border: 2px solid #800000;
   }
   .input1 {
   flex-grow: 1;
   margin-right: 20px;
   border-radius: 0 0.25rem 0.25rem 0;
   border: 2px solid #800000;
   padding: 4px 0px;
   text-align: center;
   }
   .bttm {}



   /* Styling for share buttons */
.share-options {
    display: flex;
    flex-direction: column;
}

.share-btn {
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f1f1f1;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-decoration: none;
    color: #333;
}

.share-btn:hover {
    background-color: #e1e1e1;
}

</style>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<div class="product-tab z-index-20 sm-margin-top-193px xs-margin-top-30px" id="scrollhere">
   <div class="container">
      <div class="biolife-title-box">
         <span class="subtitle">The Authentic Flavour of Chettinad</span>
         <h3 class="main-title"> PRODUCTS</h3>
      </div>
      <div class="biolife-tab biolife-tab-contain sm-margin-top-34px">
         <div class="tab-head tab-head__icon-top-layout icon-top-layout"
            style="display:flex; justify-content: center;">
            <ul class="tabs md-margin-bottom-35-im xs-margin-bottom-40-im"
               style="list-style: none; display: flex; flex-direction: row; ">
               <li class="<?php //echo $selectedCategory === $row['c_name'] ? 'active' : ''; ?>"
                  onclick="window.location.href = '?category=All'"
                  style="margin-bottom: 10px; margin-right: 10px;">
                  <a href="#tab01_2nd" class="tab-link mx-8">
                  <img src="img/all.png" alt="Vegetables" width="60" height="60" class="product-thumnail"><br>
                  All </a>
               </li>
               <?php
                  include 'dbconnect.php';
                  $myvar = true;
                  $getcat = mysqli_query($con, "SELECT * FROM res_category order by orderr asc");
                  while ($catft = mysqli_fetch_array($getcat)) {
                      $cat_name = $catft['c_name'];
                      $cat_img = $catft['icon'];
                      $cat_id = $catft['c_id'];
                      
                      ?>
               <li class="<?php //echo $selectedCategory === $row['c_name'] ? 'active' : ''; ?>"
                  onclick="window.location.href = '?category=<?php echo $cat_name; ?>'"
                  style="margin-bottom: 10px; margin-right: 15px;">
                  <a href="#tab01_2nd" class="tab-link mx-12">
                  <img src="../admin/<?php echo $cat_img; ?>" alt="Vegetables" width="60" height="60"
                     class="product-thumnail"><br>
                  <?php echo $cat_name; ?>
                  </a>
               </li>
               <?php
                  } ?>
            </ul>
            <small style="position:absolute ; margin-top: 80px; margin-left: 10px;">
            <span id="productCount">
            </span> items found out of <?php echo $_SESSION['total_dishes'] ?> items
            </small>
         </div>
         <div class="tab-content">
            <div id="tab01_1st" class="tab-contain active">
               <ul class="products-list biolife-carousel nav-center-02 nav-none-on-mobile eq-height-contain"
                  data-slick='{"rows":2 ,"arrows":true,"dots":false,"infinite":true,"speed":400,"slidesMargin":10,"slidesToShow":5, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 5}},{"breakpoint":992, "settings":{ "slidesToShow": 4, "slidesMargin":25 }},{"breakpoint":768, "settings":{ "slidesToShow": 1, "slidesMargin":15}}]}'>
                  <?php
                     include('dbconnect.php');
                     if (isset($_GET['category'])) {
                        $catt = $_GET['category'];
                        if ($catt == 'All') {
                          $fetcate = mysqli_query($con, "SELECT DISTINCT d.* FROM dishes d JOIN price p ON d.rs_id = p.pcode LIMIT 10");
                     
                        } else {
                          $fetcate = mysqli_query($con, "SELECT DISTINCT d.* FROM dishes d JOIN price p ON d.rs_id = p.pcode where d.category='$catt' LIMIT 10");
                     
                     
                        }
                     } else {
                        $fetcate = mysqli_query($con, "SELECT DISTINCT d.* FROM dishes d JOIN price p ON d.rs_id = p.pcode LIMIT 10");
                     }
                     $productCount = 0;
                     
                     while ($rocat = mysqli_fetch_array($fetcate)) {
                      $productCount++;
                     
                        $ct_img = $rocat['img'];
                        $ct_name = $rocat['dish_name'];
                        $ct_nid = $rocat['rs_id'];
                        $ct_category = $rocat['category'];
                     
                      $myvar = false;
                     
                     
                     ?>
                  <!-- <style>
                     .product-item {
                     display: inline-block;
                     vertical-align: top; /* Align items to the top */
                     margin-right: 20px; /* Adjust as needed */
                     border: 2px solid #800000;
                     margin-bottom: 20px;
                     }
                     </style> -->
                  <form action="add1.php" method="post">
                     <input type="hidden" name="prdid" value="<?php echo $ct_nid; ?>">
                     <li class="product-item" style="border: 2px solid #800000; margin-bottom: 20px;">
                        <div class="contain-product layout-default">
                           <div class="product-thumb">
                              <a href="details.php?id=<?php echo $ct_nid ?>" class="link-to-product">
                              <img src="./admin/<?php echo $ct_img; ?>" alt="" width="270" height="200"
                                 class="product-thumnail" style="height: 240px !important"
                                 data-toggle="tooltip" title="view details">
                              </a>
                              <!-- <a class="lookup btn_call_quickview" href="#"
                                 onclick="setVariableValue('<?php echo $ct_nid; ?>');">
                                 <i class="biolife-icon icon-search"></i>
                                 </a> -->
                              <script>
                                 function setVariableValue(variableValue) {
                                     var quickviewElement = document.querySelector('.biolife-quickview-block');
                                     quickviewElement.setAttribute('data-variable-value', variableValue);
                                 }
                              </script>
                           </div>
                           <div class="info">
                              <b class="categories">
                              <?php echo $ct_category; ?>
                              </b>
                              <h4 class="product-title">
                                 <a href="details.php?id=<?php echo $ct_nid; ?>" class="pr-name">
                                 <?php echo "<b>".strtoupper($ct_name)."</b>"; ?>
                                 </a>
                              </h4>
                              <?php
                                 include("dbconnect.php");
                                 $gbb = mysqli_query($con, "SELECT * FROM price where pcode='$ct_nid'");
                                 if ($gb13 = mysqli_fetch_array($gbb)) {
                                    $pc=$gb13['id'];
                                    $currentp = $gb13['pp'];
                                    $oldp = $gb13['oprice'];
                                 
                                 } ?>
                              <div class="price">
                                 <ins>
                                 <span style="font-size: 25px !important;" class="price-amount"
                                    id="<?php echo $ct_nid; ?>_cunt">
                                 <span class="currencySymbol">₹</span><?php echo $currentp; ?>
                                 </span>
                                 </ins>
                                 <input type="hidden" name="qty" value="1">
                                 <input type="hidden" name="pid" value="<?php echo $pc; ?>">
                                 <input type="hidden" name="prdid" value="<?php echo $ct_nid; ?>">
                                 <input type="hidden" name="price" value="<?php echo $currentp; ?>">
                                 <style>
                                    .currencySymbol {
                                    font-size: smaller;
                                    }
                                    .currencySymbol {
                                    margin-right: -3px;
                                    }
                                    /* Adjust this value to move the symbol closer to the number */
                                 </style>
                                 <span class="price-amount"
                                    style="color: red; font-size: large; text-decoration: line-through black;"
                                    id="<?php echo $ct_nid; ?>_cop">
                                 <span class="currencySymbol">₹</span>
                                 <?php echo $oldp; ?>
                                 </span>
                              </div>
                              <input type="hidden" id="<?php echo $ct_nid; ?>_cunt1"
                                 value="<?php echo $currentp; ?>">
                              <input type="hidden" id="<?php echo $ct_nid; ?>_cop1"
                                 value="<?php echo $oldp; ?>">
                              <div class="slide-down-box">
                                 <!-- <div class="row">
                                    <div class="col-lg-6 ">
                                    </div>
                                    </div> -->
                                 <?php
                                    include("dbconnect.php");
                                    $syl = 0;
                                    
                                    $ud1 = $_SESSION['uid'];
                                    $zqswl = mysqli_query($con, "SELECT * FROM watch_list where userid='$ud1' and pr_id='$ct_nid'");
                                    if (mysqli_num_rows($zqswl)) {
                                       $syl = 1;
                                    } ?>
                                 <div class="buttons">
                                    <a data-toggle="tooltip" title="add to wishlist"
                                       href="javascript:void(0);"
                                       onclick="toggleWatchlist1(<?php echo $ct_nid; ?>)"
                                       class="btn wishlist-btn">
                                    <i class="fa fa-heart" aria-hidden="true"
                                       id="heart-icon1-<?php echo $ct_nid; ?>"
                                       <?php if ($syl == 1) { ?> style='color:green;' <?php } ?>></i>
                                    </a>
                                    <div class='text-center'>
                                       <button input="submit" name="add" href="#"
                                          class="btn add-to-cart-btn" data-toggle="tooltip"
                                          title="Buy now">
                                       <i class="fa fa-cart-arrow-down" aria-hidden="true">
                                       </i>buy
                                       </button>
                                    </div>
                                
<?php 
$img='img/kottanf.jpg';
?>
<!-- Share button with onclick event -->
<a href="#" class="btn compare-btn" onclick="shareProduct('<?php echo $ct_name; ?>', '<?php echo $currentp; ?>', '<?php echo 'details.php?id=' . $ct_nid; ?>', '<?php echo '/admin/' . $ct_img; ?>')" >
    <i class="fa fa-share" aria-hidden="true"></i> Share
</a>

 

                                 </div>
                              </div>
                  </form>
                  </div>
                  </div>
                  </li>
                  <?php } ?>
               </ul>
               <?php if ($myvar == true): ?>
               <center>
                  <img src="./assets/images/no-product.png" alt="">
               </center>
               <?php endif; ?>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<div id="shareOptions" class="share-options"></div>
<h1 id="phpProductCount" style="visibility: hidden;"><?php echo $productCount;?></h1>
<center>
   <a href="allproduct.php" class="my-2 btn btn-bold"
      style="background-color: #800000 !important; color: white; z-index: 10;">
   view all
   </a>
</center>
<!--fav script start -->






<script>
    
 function shareProduct(productName, productPrice, productUrl, imageUrl) {
    var shareOptions = document.getElementById('shareOptions');
    shareOptions.innerHTML = '';

    var encodedProductName = encodeURIComponent(productName);
    var encodedProductPrice = encodeURIComponent('₹' + productPrice);
    var encodedProductUrl = encodeURIComponent(productUrl);
    var encodedImageUrl = encodeURIComponent(imageUrl);

    var facebookUrl = 'https://www.facebook.com/sharer/sharer.php?u=' + encodedProductUrl + '&quote=' + encodedProductName + ' - ' + encodedProductPrice + '&picture=' + encodedImageUrl;
    var twitterUrl = 'https://twitter.com/intent/tweet?url=' + encodedProductUrl + '&text=' + encodedProductName + ' - ' + encodedProductPrice;
    var linkedInUrl = 'https://www.linkedin.com/sharing/share-offsite/?url=' + encodedProductUrl + '&title=' + encodedProductName + ' - ' + encodedProductPrice;
    var whatsappMessage = encodeURIComponent(productName + ' - ₹' + productPrice + ' - ' + productUrl + ' - Image: ' + imageUrl);
    var whatsappUrl = 'https://wa.me/?text=' + whatsappMessage;

    var facebookBtn = createShareButton('Share on Facebook', facebookUrl);
    var twitterBtn = createShareButton('Share on Twitter', twitterUrl);
    var linkedInBtn = createShareButton('Share on LinkedIn', linkedInUrl);
    var whatsappBtn = createShareButton('Share on WhatsApp', whatsappUrl);

    shareOptions.appendChild(facebookBtn);
    shareOptions.appendChild(twitterBtn);
    shareOptions.appendChild(linkedInBtn);
    shareOptions.appendChild(whatsappBtn);
}

// Helper function to create share button element
function createShareButton(text, url) {
    var shareBtn = document.createElement('a');
    shareBtn.setAttribute('href', url);
    shareBtn.setAttribute('target', '_blank');
    shareBtn.classList.add('share-btn');
    shareBtn.innerText = text;
    return shareBtn;
}

</script>

</script>



<script>
   async function toggleWatchlist1(p_id) {
       try {
           const response = await fetch(`watc.php?p_id=${p_id}`);
           const data = await response.json();
   
           if (data.status === 'success') {
               const heartIcon1 = document.getElementById(`heart-icon1-${p_id}`);
               heartIcon1.style.color = (heartIcon1.style.color === 'green') ? '' : 'green';
   
               // Fetch the updated watchlist count
               updateWatchlistCount();
           } else {
               console.error('Error in watchlist operation');
           }
       } catch (error) {
           console.error('Error in AJAX request', error);
       }
   }
   
   // Function to update the watchlist count
   async function updateWatchlistCount() {
       try {
           const response = await fetch('fav.php'); // Assuming fav.php returns the count
           const data = await response.json();
           document.querySelector('.icon-qty-combine .qty').textContent = data.count;
       } catch (error) {
           console.error('Error fetching watchlist count', error);
       }
   }
</script>
<script>
   console.log(document.getElementById("phpProductCount").innerHTML)
   let totalProductCount = document.getElementById("phpProductCount").innerHTML;
   
   document.getElementById('productCount').innerHTML = totalProductCount;
</script>
<script>
   window.onload = function() {
       // Get the element you want to scroll to
       var element = document.getElementById("scrollhere");
   
       // Scroll the element into view
       element.scrollIntoView({
           behavior: "smooth",
           block: "start"
       });
   };
</script>