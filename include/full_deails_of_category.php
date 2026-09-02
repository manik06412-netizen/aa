<div class="main_title_2">
    <span></span>
</div>
<div class="container">
    <?php
      function limit_words($string, $word_limit) {
        $words = explode(" ", $string);
        if (count($words) > $word_limit) {
            $limited_string = implode(" ", array_slice($words, 0, $word_limit));
            $limited_string .= ' <span id="more">... <a href="#" onclick="showFullDescription()">more</a></span>';
        } else {
            $limited_string = $string;
        }
        return $limited_string;
    }
      include('dbconnect.php');
      $categoryQuery = "SELECT DISTINCT res_category.c_name , res_category.fpath FROM res_category INNER JOIN dishes on res_category.c_name = dishes.category";
      $categoryResult = mysqli_query($con, $categoryQuery);
      while ($categoryRow = mysqli_fetch_assoc($categoryResult)) {
          $categoryName = $categoryRow['c_name'];
          $categoryImage = $categoryRow['fpath'];

          $productQuery = "SELECT DISTINCT d.*, p.pp, p.oprice, p.qn, p.wg 
          FROM dishes d 
          JOIN price p ON d.rs_id = p.pcode 
          WHERE d.category = ? AND d.status = '0' 
          LIMIT 4";

      $stmt = $con->prepare($productQuery);
      $stmt->bind_param('s', $categoryName);
      $stmt->execute();
      $productResult = $stmt->get_result();
      if(mysqli_num_rows($productResult)>0){
          ?>
    <div class="row mb-4 pb-2 bg-white"
        style="border:0.7px solid rgb(196, 196, 196);border-top:2px solid rgb(27,171,5);">
        <div class="col-6 mt-2">
            <h4 style="font-wight:bold;" class="mt-1"><?php echo htmlspecialchars($categoryName); ?></h4>

        </div>
        <div class="col-6 mt-2">
            <a class="btn_1 float-right" href="allcategories.php?cat=<?= htmlspecialchars($categoryName);?>">View
                All</a>
        </div>
        <div class="col-md-4 ">
            <div class="category-box">
                <img src="<?php echo htmlspecialchars(resolve_image_url($categoryImage)); ?>" class="img-fluid" style="height:350px;"
                    alt="<?php echo htmlspecialchars($categoryName); ?>">
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <?php
                while ($productRow = $productResult->fetch_assoc()) {
                   $ct_img = $productRow['img'];
                   $ct_name = $productRow['dish_name'];
                   $ct_nid = $productRow['rs_id'];
                   $ct_description = $productRow['description'];
                   $currentp = $productRow['pp'];
                   $oldp = $productRow['oprice'];
                   $qn_list = $productRow['qn'];
                   $wg_list = $productRow['wg'];
               
                   // Fetch offers
                   $display_offers = '';
                   $countings = 0;
                   $discounted_amount = 0;
                   $discount_percentage = 0;
                   $qtys_List = 0;
                                 

                 ?>
                <div class="col-md-6 mt-2">
                    <div class="card border sub_styled_borders">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">
                                    <a href="details.php?id=<?php echo $ct_nid; ?>"><img
                                            src="./admin/<?php echo $ct_img; ?>" class="w-100" height="130px"
                                            alt=""></a>
                                </div>
                                <div class="col-6">
                                    <a href="details.php?id=<?php echo $ct_nid; ?>">
                                        <h6><?php echo htmlspecialchars($ct_name); ?></h6>
                                    </a>
                                    <div class="price">
                                    <?php star_ratings($con,$ct_nid); ?>
                                        <ins><span class="price-amount"><span
                                                    class="currencySymbol"><?php echo $_SESSION['selectedCurrency']; ?>
                                                </span><?php echo $currentp; ?></span></ins>
                                        <del><span class="price-amount text-danger"><span
                                                    class="currencySymbol"><?php echo $_SESSION['selectedCurrency']; ?>
                                                </span><?php echo  $oldp; ?></span></del>
                                    </div>
                                    <small><?php echo $limited_description; ?></small>
                                    <span id="full_description"
                                        style="display:none;"><?php echo $ct_description; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } // End product loop ?>
            </div>
        </div>
    </div>
    <?php } } // End category loop ?>
    <!-- /row -->
</div>
<script>
// function showFullDescription() {
//     var moreText = document.getElementById("more");
//     var fullText = document.getElementById("full_description");

//     moreText.style.display = "none";
//     fullText.style.display = "inline";
// }
</script>