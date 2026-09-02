<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
   ?>
<!DOCTYPE html>
<html lang="zxx">
   <?php
      include('dbconnect.php');
      include('includes/header.php');
      require APP_ROOT . '/views/layouts/navbar.php';
      
      
      ?>
 <style>
  .cusstom {
    background-color: white !important;
    border: 1px solid black;
    border-radius: 10px;
    color: black !important;
    background-color: #800000 !important;
  }
  .cusstom option {
    color: black !important;
  }
  ul.unique {
    display: flex;
    text-align: left !important;
    list-style-type: none;
    flex-wrap: wrap;
    justify-content: left;
    /* background-color: blue; */
    width: 120%;
    margin-right: 30px !important;
    padding-left: 10px;
  }
  li {
    text-align: left;
    list-style-type: none;
    margin: 5px 7px !important;
  }
.imgset{
   height: 240px !important;
   width: 200px !important;
}
    
    
  /* Media query for devices with a width of less than 768px */
  @media (max-width: 768px) {
   
   ul.unique {
     display: flex; /* Stack list items vertically on smaller screens */
     /* width: 100%; Adjust width to fit smaller screens */
     margin-right: 0 !important; /* Remove right margin */
     margin-left: -20px !important; /* Remove right margin */

/* background-color: blue; */
     justify-content: space-around;
     align-items: center;
     width: 100vw;
   }
   li {
     margin: 10px 5px !important; /* Increase margin for better readability */
     width: 45% !important;
   }
   .imgset{
  height: 200px !important;
  width: 100% !important;
}

small{
  margin-top: 50px !important;
  left: 0;
}
 }
</style>

   <?php
      include 'dbconnect.php';
      
      
      
      $cat = $_GET['cat'];
      
      
      if ($cat == 'all') {
          $key = "or";
      } else {
          $key = "and";
      }
      
      
      
      $sort_option = isset($_GET['sort']) ? $_GET['sort'] : (isset($_SESSION['sort']) ? $_SESSION['sort'] : 'name_asc');
      $_SESSION['sort'] = $sort_option; 
      $search_term = isset($_GET['searchquery']) ? $_GET['searchquery'] : '';
      
      
      $between_option = isset($_GET['price']) ? $_GET['price'] : (isset($_SESSION['price']) ? $_SESSION['price'] : 'class-4');
      
      
      
      $sql = "SELECT d.*, p.pp, p.qn, p.oprice ,p.id
      FROM dishes d 
      JOIN price p ON d.dish_name = p.pname
      WHERE 
      (
          d.dish_name LIKE '$search_term%' 
          or d.category like '$search_term%' 
          OR d.k2 LIKE '$search_term' 
          OR d.k3 LIKE '$search_term' 
          OR d.k4 LIKE '$search_term' 
          OR d.k5 LIKE '$search_term' 
          or d.subcate like '$search_term'
          $key d.category = '$cat'
      )
      group by d.dish_name
      ";
      
      
      if(isset($_GET['sort']))
      switch ($sort_option) {
          case 'date':
              $sql .= " ORDER BY d.date_of_adding ASC ";
              break;
          case 'price':
              $sql .= " ORDER BY p.pp ASC";
              break;
          case 'price-desc':
              $sql .= " ORDER BY p.pp DESC ";
              break;
          case 'rating':
            $sql .= " ORDER BY d.dish_name ASC ";
            //   $sql .= " ORDER BY cr.uratings ASC ";
              break;
          default:
              $sql .= " ORDER BY d.dish_name ASC ";
      }
      
      
      
      
      
      if (isset($_GET['price'])) {
      
          $sql = "SELECT d.*, p.pp, p.qn, p.oprice 
      FROM dishes d 
      JOIN price p ON d.dish_name = p.pname
      WHERE 
      (
          status = '1' and
         d.dish_name LIKE '$search_term%' 
          or d.category like '$search_term%' 
          OR d.k2 LIKE '$search_term%' 
          OR d.k3 LIKE '$search_term%' 
          OR d.k4 LIKE '$search_term%' 
          OR d.k5 LIKE '$search_term%' 
          or d.subcate like '%$search_term'
          $key d.category = '$cat'
      )
      
      
          ";
      
      
          switch ($between_option) {
              case 'class-1':
                  $sql .= "AND p.pp BETWEEN 0 AND 5 group by d.dish_name";
                  break;
              case 'class-2':
                  $sql .= " AND p.pp BETWEEN 5 AND 20 group by d.dish_name";
                  break;
              case 'class-3':
                  $sql .= "AND p.pp BETWEEN 20 AND 100 group by d.dish_name";
                  break;
              case 'class-4':
                  $sql .= "AND p.pp BETWEEN 100 AND 500 group by d.dish_name ";
                  break;
              case 'class-5':
                  $sql .= "AND p.pp BETWEEN 500 AND 2000 group by d.dish_name";
                  break;
              case 'class-6':
                  $sql .= "AND p.pp > 2000 group by d.dish_name";
                  break;
              default:
                  $sql .= " ORDER BY name ASC";
          }
      
          
      }
      
      
    
      
      $result = mysqli_query($con, $sql);
      
      // echo $sql;
      $products = [];
      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
              $products[] = $row;
          }
      } else {
          $products = []; 
      }
      ?>
   <body>
      <div class="hero-section hero-background" style="margin-bottom: 40px;">
         <h2 class="text-center page-title">Search results for :
            <?php echo $search_term ?>
         </h2>
      </div>
      <div class="container mt-4 bg-sucess d-flex flex-row-reverse" style="background-color: ble !important">
         <div class="row" style="
            display: flex;
            justify-content: right;
            ">
            <div class="col-1 ">
            </div>
         </div>
      </div>
      <div class="container">
         <div id="top-functions-area" class="top-functions-area">
            <div class="flt-item to-left group-on-mobile">
               <span class="flt-title">Filter</span>
               <a href="#" class="icon-for-mobile">
               <span></span>
               <span></span>
               <span></span>
               </a>
               <div class="wrap-selectors">
                  <form action="#" name="frm-refine" method="get">
                     <span class="title-for-mobile">Filter Products By</span>
                     <div data-title="Price:" class="selector-item">
                  <form action="" method="get">
                  <select name="price" class="selector" onchange="this.form.submit()">
                  <option value="all">Price</option>
                  <option value="class-1">₹0 - ₹5</option>
                  <option value="class-2">₹5 - ₹20</option>
                  <option value="class-3">₹20 - ₹100</option>
                  <option value="class-4">₹100 - ₹500</option>
                  <option value="class-5">₹500 - ₹2000</option>
                  <option value="class-6">More than ₹2000</option>
                  </select>
                  <input type="hidden" name="submit2" value="between">
                  <input type="hidden" name="cat" value="<?php echo $cat; ?>">
                  <input type="hidden" name="searchquery" value="<?php echo $search_term; ?>">
                  </form>
                  </div>
                  <p class="btn-for-mobile"><button type="submit" class="btn-submit">Go</button></p>
                  </form>
               </div>
            </div>
            <div class="flt-item to-right">
               <span class="flt-title">Sort</span>
               <div class="wrap-selectors">
                  <div class="selector-item orderby-selector">
                     <form action="">
                        <select name="sort" class="orderby" aria-label="Shop order" onchange="this.form.submit()">
                           <option value="menu_order" selected="selected">Default sorting</option>
                           <option value="rating">average rating</option>
                           <option value="date">Recently added</option>
                           <option value="price">price: low to high</option>
                           <option value="price-desc">price: high to low</option>
                        </select>
                        <input type="hidden" name="searchquery" value="<?php echo $search_term; ?>">
                        <input type="hidden" name="cat" value="<?php echo $cat; ?>">
                     </form>
                  </div>
               </div>
            </div>
            <small  style="position:absolute ; margin-top: 5px; margin-left: 10px;" >
           <span id="productCount">

           </span>    items found out of <?php echo $_SESSION['total_dishes'] ?> items
        </small>
         </div>
      </div>
      <div class="container">
   
      <div class="">
            
      <ul class="products-list  nav-none-on-mobile eq-height-contain"
               data-slick='{"rows":2 ,"arrows":true,"dots":false,"infinite":false,"speed":400,"slidesMargin":10,"slidesToShow":6, "responsive":[{"breakpoint":1200, "settings":{ "slidesToShow": 5}},{"breakpoint":992, "settings":{ "slidesToShow": 4, "slidesMargin":25 }},{"breakpoint":768, "settings":{ "slidesToShow": 1, "slidesMargin":15}}]}'>
                  <?php 
                                      $productCount = 0;

                  foreach ($products as $product) : 
                     $productCount++;

                  
                  ?>
                    <style>
 .product-item {
    display: inline-block;
    vertical-align: top; /* Align items to the top */
    margin-right: 20px; /* Adjust as needed */
    border: 2px solid #800000;
    margin-bottom: 20px;
}
 </style>
                    <li class="product-item" style="border: 2px solid #800000; margin-bottom: 20px;">
                     <div class="contain-product layout-default">
                        <div class="product-thumb">
                        <a href="details.php?id=<?php echo $product['rs_id']?>" class="link-to-product">
                           <img src="./admin/<?php echo htmlspecialchars($product['img']); ?>" alt=""
                              width="270" height="200" class="product-thumnail"
                              style="height: 240px !important; width: 200px !important;" data-toggle="tooltip" title="
                              view details">
                           </a>
                          
                        </div>
                        <div class="info">
                           <b class="categories">
                           <?php
                              echo $product['category']
                              ?>
                           </b>
                           <h4 class="product-title"><a href="details.php?id=<?php echo $product['rs_id'] ?>"
                              class="pr-name">
                              <?php
                                 echo $product['dish_name'];
                                 
                                 ?>
                              </a>
                           </h4>
                           <div class="price ">
                              <ins><span class="price-amount" style="font-size: 25px !important;" ><span class="currencySymbol">₹</span>
                              <?php
                                 echo $product['pp'];
                                 ?>
                              </span></ins>
                              <del><span class="price-amount" style="color: red; font-size: large; text-decoration: line-through black;"><span class="currencySymbol">₹</span>
                              <?php
                                 echo $product['oprice'];
                                    ?>
                              </span></del>
                           </div>
                           <div class="slide-down-box">
                                                           <?php
                                 include("dbconnect.php");
                                 $syl = 0;
                                 
                                 $ud1 = $_SESSION['uid'];
                                 $productId = $product['rs_id'];
                                 $zqswl = mysqli_query($con, "SELECT * FROM watch_list where userid='$ud1' and pr_id= $productId");
                                 if (mysqli_num_rows($zqswl)) {
                                    $syl = 1;
                                 } ?>
                              <div class="buttons">
                                 <a href="javascript:void(0);"
                                    onclick="toggleWatchlist1(<?php echo $productId; ?>)"
                                    class="btn wishlist-btn" data-toggle="tooltip" title="Add to wishlist">
                                 <i class="fa fa-heart" aria-hidden="true"
                                    id="heart-icon1-<?php echo $productId; ?>" <?php if ($syl == 1) { ?>
                                    style='color:red;' <?php } ?>></i>
                                 </a>
                                 <form action="add1.php" method="post">
                                    <input type="hidden" name="qty" value="<?php echo $product['qn']; ?>">
                                    <input type="hidden" name="pid" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="prdid"
                                       value="<?php echo   $product['rs_id']; ?>">
                                    <input type="hidden" name="price" value="<?php echo $product['id']; ?>">
                            
                                    <div class="text-center">

                                       <button type="submit" name="searchResult" href="#"
                                       class="btn add-to-cart-btn" data-toggle="tooltip" title="Buy now">
                                       <i class="fa fa-cart-arrow-down" aria-hidden="true">
                                          </i>Buy
                                       </button>
                                    </div>



                                 </form>
                                 <a data-toggle="tooltip" title="Share" href="#" class="btn compare-btn"><i class="fa fa-share"
                                    aria-hidden="true"></i></a>
                              </div>
                           </div>
                        </div>
                     </div>
                  </li>
                  <?php endforeach; ?>
               </ul>
               <h1 id="phpProductCount" style="visibility: hidden;"><?php echo $productCount;?>
    </h1>
            </div>
         </div>
         <?php if (empty($products)): ?>
         <center>
         <div style="text-align: center;">
    <img src="./assets/images/no-product.png" alt="">
</div>

         </center>
         <?php endif; ?>
      </div>
      </div>
      </div>
     
      <?php
         include('includes/footer.php');
         include('includes/mobmenu.php');
         include('includes/popup.php');
         ?>
      <script src="assets/js/jquery-3.4.1.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/jquery.countdown.min.js"></script>
      <script src="assets/js/jquery.nice-select.min.js"></script>
      <script src="assets/js/jquery.nicescroll.min.js"></script>
      <script src="assets/js/slick.min.js"></script>
      <script src="assets/js/biolife.framework.js"></script>
      <script src="assets/js/functions.js"></script>
   </body>



   <script>
console.log(document.getElementById("phpProductCount").innerHTML)
let totalProductCount = document.getElementById("phpProductCount").innerHTML;

document.getElementById('productCount').innerHTML = totalProductCount;

    </script>
   <script>
      async function toggleWatchlist1(p_id) {
          try {
              const response = await fetch(`watc.php?p_id=${p_id}`);
              const data = await response.json();
      
              if (data.status === 'success') {
                  const heartIcon1 = document.getElementById(`heart-icon1-${p_id}`);
                  heartIcon1.style.color = (heartIcon1.style.color === 'red') ? '' : 'red';
      
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
</html>