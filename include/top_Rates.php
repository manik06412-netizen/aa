<!DOCTYPE html>
<html lang="en">

<?php 
include "include/header.php";
?>
<?php
function getLatestProducts() {
    include('dbconnect.php');

    // Fetch product IDs and review counts
    $lastsel_reviews = mysqli_query($con, "SELECT cid, COUNT(uratings) as review_count FROM cust_reviews GROUP BY cid ORDER BY cid DESC");

    if (!$lastsel_reviews) {
        die("Query failed: " . mysqli_error($con));
    }

    $result = array();

    while ($sel1 = mysqli_fetch_array($lastsel_reviews)) {
        $sidi = $sel1['cid'];
        // Fetch the latest dishes related to the current product ID
        $lastsel = mysqli_query($con, "SELECT * FROM dishes WHERE rs_id = $sidi ORDER BY d_id DESC LIMIT 6");

        while ($lastsel1 = mysqli_fetch_array($lastsel)) {
            $sid = $lastsel1['rs_id'];
            $categorys = $lastsel1['category'];
            $csubcate = $lastsel1['subcate'];
            $s = mysqli_query($con, "SELECT * FROM price WHERE pcode = '$sid' ");
            $r = mysqli_fetch_array($s);
            $result[] = array(
                'id' => $lastsel1['rs_id'],
                'img' => $lastsel1['img'],
                'title' => $lastsel1['dish_name'],
                'price' => $r['pp'],
                'pprice' => $r['oprice'],
                'category'=>$categorys,
                'scate'=>$csubcate
            );
        }
    }

    return $result;
}

$latestProducts = getLatestProducts();
?>
 
 <?php
function star_ratings($con, $productId, $defaultRating) {
    // Fetch average rating
    $averageRatingQuery = "SELECT AVG(uratings) AS overall_avg_rating FROM cust_reviews WHERE cid = $productId";
    $averageRatingResult = mysqli_query($con, $averageRatingQuery);
    $averageRating = $defaultRating;
    // $averageRating1 = $defaultRating;

    if ($averageRatingRow = mysqli_fetch_array($averageRatingResult)) {
        // $averageRating = $averageRatingRow['overall_avg_rating'];
        $averageRating = round($averageRatingRow['overall_avg_rating'], 1);

    }

    // If no user rating is found, use the default rating from the dishes table
    if ($averageRating == 0) {
        $averageRating = $defaultRating;
    }

    // Fetch total number of reviews
    $totalRevSql = "SELECT COUNT(*) AS total_reviews FROM cust_reviews WHERE cid = $productId";
    $totalRevRes = mysqli_query($con, $totalRevSql);
    $totalReviews = 0;
    if ($totalRevRow = mysqli_fetch_array($totalRevRes)) {
        $totalReviews = $totalRevRow['total_reviews'];
    }

    // Output star ratings
    $num = number_format($averageRating, 2);
    if ($num >= 1 && $num <= 5) {
        echo '<div class="star-rating text-center mt-2">';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $num) {
                echo '<i class="fas fa-star colored"></i>';
            } else {
                echo '<i class="fas fa-star"></i>';
            }
        }
        echo " ($totalReviews)</div>"; 
    }
}
?>

<body>
		
	<div id="page">
    <?php include('include/scroller_navbar.php') ?>
    <header class="header">
        <?php include('include/navbar.php'); ?>
    </header>
	
	<main>
		<div class="container-fluid margin_80_55 mt-5 pt-5">
			<div class="main_title_2">
				<span><em></em></span>
				<h2>Most Selling Products</h2>
				<p>"Discover our highest-rated items curated for excellence."</p>
			</div>
			
			<div id="reccomended" class="owl-carousel owl-theme">
            <?php foreach ($latestProducts as $product) {
                $productId = $product['id'];
                $isInWatchlist = 0;
                $userId = $_SESSION['uid'];

                // Check if product is in the user's watchlist
                $zqswl = mysqli_query($con, "SELECT * FROM watch_list WHERE userid='$userId' AND pr_id='$productId'");
                if (mysqli_num_rows($zqswl)) {
                    $isInWatchlist = 1;
                }
            ?>
				<div class="item">
					<div class="strip grid">
                        <figure>
                            <a href="details.php?id=<?php echo $productId; ?>">
                                <img src="./avadmin/<?php echo $product['img']; ?>" class="img-fluid" alt="">
                            </a>
                            <small><?php echo $product['category']; ?></small>
                        </figure>
						<div class="wrapper text-center">
                            <a title="add to wishlist" href="javascript:void(0);" onclick="toggleWatchlist1(<?php echo $productId; ?>)"
                               id="heart-icon1-<?php echo $productId; ?>"
                               style="<?php echo $isInWatchlist ? 'background-color:orange;' : ''; ?>" class="wish_bt"></a>
                            <h3 class="text-center"><a href="details.php?id=<?php echo $productId; ?>"><?php echo $product['title']; ?></a></h3>
                            <p class="text-center"><?php echo $product['scate']; ?></p>
                            <?php if (isset($product['price'])): ?>
                                    <ins class="text-center">
                                        <span class="price-amount text-center">
                                            <span class="currencySymbol text-center"><?php echo $_SESSION['selectedCurrency']; ?> </span>
                                            <?php echo $product['price']; ?>
                                        </span>
                                    </ins>
                                    <?php endif; ?>
                                    <?php if (isset($product['pprice'])): ?>
                                   
                                    <del><span class="price-amount text-danger"><span
                                                    class="currencySymbol"><?php echo $_SESSION['selectedCurrency']; ?>
                                                </span>  <?php echo $product['pprice']; ?></span></del>
                                    <?php endif; ?>
						</div>
                        <?php
                                // Fetch average rating and total reviews
                                $averageRatingQuery = "SELECT AVG(uratings) AS overall_avg_rating FROM cust_reviews WHERE cid = $productId";
                                $averageRatingResult = mysqli_query($con, $averageRatingQuery);
                                if ($averageRatingResult && $averageRatingRow = mysqli_fetch_array($averageRatingResult)) {
                                    $averageRating = round($averageRatingRow['overall_avg_rating'], 1);

                                    $totalReviewsQuery = "SELECT COUNT(*) AS total_reviews FROM cust_reviews WHERE cid = $productId";
                                    $totalReviewsResult = mysqli_query($con, $totalReviewsQuery);
                                    if ($totalReviewsResult && $totalReviewsRow = mysqli_fetch_array($totalReviewsResult)) {
                                        $totalReviews = $totalReviewsRow['total_reviews'];
                                        $rating = max(min($averageRating, 5), 1);
                                        $percentage = ($rating / 5) * 100;
                                ?>
                               
                                <style>
                                    .fa-star.colored {
                                        color: #305724;
                                    }
                                    .listed{
                                        list-style-type: none;
                                       
                                        margin-left:0px !important;
                                    }

                                </style>
                                 
                              
                                    <?php
                    
                    
                    star_ratings($con, $productId, $defaultRating);
                ?>
                                    
                                  
                              
                                <?php
                                    }
                                }
                                ?>
                               
						<ul>
                     
                               
							<li><div class="score"><span>Superb<em><?php  echo  $totalReviews; ?> Reviews</em></span><strong><?php echo $averageRating ; ?></strong></div></li>
						</ul>
					</div>
                </div>
            <?php } ?>
			</div>
		</div>
	</main>
	
	<div id="toTop"></div>

	<!-- COMMON SCRIPTS -->
    <script src="js/common_scripts.js"></script>
	<script src="js/functions.js"></script>
	<script src="assets/validate.js"></script>
	<script src="js/switcher.js"></script>
	<?php include "include/footer.php"; ?>
</body>

</html>
