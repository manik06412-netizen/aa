<?php
error_reporting(0);
ini_set('display_errors', '0');
ob_start();
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';

if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
?>
 <style>
.star-rating {
    display: inline-block;
    font-size: 20px;
    /* Adjust the font size as needed */
}

.fa-star {
    color: #CCCCCC;
    /* Set the default color of stars */
}

.fa-star.colored {
    color: #800000;
    /* Set the color of colored stars */
}
 </style>
 <div id="tab_4th" class="tab-contain review-tab">
     <div class="container">
         <div class="row">
            
             <div class="col-lg-5 col-md-5 col-sm-6 col-xs-12">

                 <div class="rating-info">
                     <p class="index"><strong class="rating"><?php echo $num; ?></strong> out of 5</p>

                     <p class="star-rating">
                         <?php for ($i = 0; $i < 5; $i++) { ?>
                         <i class="fa fa-star <?php echo ($i < $num) ? 'colored' : ''; ?>"></i>
                         <?php } ?>
                     </p>

                 </div>
                 <style>
   .review-tab #comments .commentlist>li {
    list-style: none;
    display: block;
    border-bottom: 1px solid #f1f1f1;
    padding-top: 8px;
    padding-bottom:8px;
   }
   .biolife-panigations-block.version-2 {
    margin: 0;
    border: none;
    padding-top: 10px;
    padding-bottom:1px;
}
</style>
                 <div id="reviews-container">
                     <div id="comments">
                         <ol class="commentlist">
                             <?php
    include "config.php"; 
   $id = $_SESSION['did']; 

    $limit = 5; 
    $page = isset($_GET['page']) ? $_GET['page'] : 1; 

    $start = ($page - 1) * $limit;

    
    $custview = "SELECT * FROM cust_reviews WHERE cid = $id ORDER BY uid DESC LIMIT $start, $limit";

  
    $ress = mysqli_query($con, $custview);

    while ($row1 = mysqli_fetch_array($ress, MYSQLI_ASSOC)) {
        //echo $row1['uratings'];
        ?>
                             <li class="review">
                                 <div class="comment-container">
                                     <div class="row">
                                         <div class="comment-content col-lg-8 col-md-9 col-sm-8 col-xs-12">
                                             <p class="comment-in">
                                                 <span class="post-name"><?php echo $row1['ureview']; ?></span>
                                                 
                                                 <span class="post-date"><?php echo $row1['createdat']; ?></span>
                                                 
                                             </p>
                                            
                                             <p class="star-rating " >
                                                 <?php
    switch ($row1['uratings']) {
        case 1:
            echo '<i class="fas fa-star yellow-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
            break;
        case 2:
            echo '<i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
            break;
        case 3:
            echo '<i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>';
            break;
        case 4:
            echo '<i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="far fa-star"></i>';
            break;
        case 5:
            echo '<i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i><i class="fas fa-star yellow-star"></i>';
            break;
        default:
            echo '<i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i><i class="far fa-star"></i>'; // Default case for unknown rating
    }
    ?>
                                             </p>


                                             <style>
                                             .yellow-star {
                                                 color: #800000;
                                                 /* Specific color code for filled stars */
                                             }
                                             </style>
                                             <p class="author">by: <b><?php echo $row1['uname']; ?></b></p>
                                             <!-- <p class="comment-text"><?php //echo $row1['ureview']; ?></p>  -->
                                         </div>
                                     </div>
                                 </div>
                             </li>
                             <?php } ?>
                         </ol>

                         <div class="biolife-panigations-block version-2">

                             <div class="result-count">
                                 <?php
                                    $countSql = "SELECT COUNT(*) AS total FROM cust_reviews WHERE cid = $id";
                                        $countResult = mysqli_query($con, $countSql);
                                        $countRow = mysqli_fetch_assoc($countResult);
                                        $totalRecords = $countRow['total'];
                                        $totalPages = ceil($totalRecords / $limit);

                                        $prevLink = ($page > 1) ? "<a class='btn btn-bold' style='color: white;' href='details.php?id=".$id."&page=".($page - 1)."'>Previous</a>" : "";
$nextLink = ($page < $totalPages) ? "<a class='btn btn-bold' style='color: white;' href='details.php?id=".$id."&page=".($page + 1)."'>Next</a>" : "";

                                        

                                        echo "<div class='pagination'>";
                                        // echo $id2;
                                        echo $prevLink;
                                        echo $nextLink;
                                        echo "</div>";

                                        ?>
                                 <!-- <p class="txt-count"><b>1-5</b> of <b>126</b> reviews</p>
                                        <a href="#" class="link-to">See all<i class="fa fa-caret-right"
                                                aria-hidden="true"></i></a> -->
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="col-lg-7 col-md-7 col-sm-6 col-xs-12">
                 <div class="review-form-wrapper">
                     <span class="title">Submit your Valuable Review</span>
                     <form id="ratingform">

                         <div class="text-primary" style=" font-size: 40px; padding-bottom:10px; text-align: center;">
                             <!-- Add 'required' attribute to make rating required -->
                             <input type="hidden" name="urating" id="urating" value="0" required>
                             <i class="far fa-star star-icon" data-rating="1" onclick="setRating(1)"></i>
                             <i class="far fa-star star-icon" data-rating="2" onclick="setRating(2)"></i>
                             <i class="far fa-star star-icon" data-rating="3" onclick="setRating(3)"></i>
                             <i class="far fa-star star-icon" data-rating="4" onclick="setRating(4)"></i>
                             <i class="far fa-star star-icon" data-rating="5" onclick="setRating(5)"></i>
                         </div>
                         <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                         <script>
                         $(document).ready(function() {
                             $('.star-icon').click(function() {
                                 var clickedRating = parseInt($(this).data('rating'));
                                 $('input[name="urating"]').val(clickedRating);

                                 $('.star-icon').removeClass('fas').addClass('far');

                                 for (var i = 1; i <= clickedRating; i++) {
                                     $('.star-icon[data-rating="' + i + '"]').removeClass('far')
                                         .addClass('fas');
                                 }
                             });
                         });
                         </script>
                         <script>
                         $(document).ready(function() {
                             $('.star-icon').click(function() {
                                 var clickedRating = parseInt($(this).data('rating'));
                                 $('input[name="urating"]').val(clickedRating);

                                 // Reset all stars to default color
                                 $('.star-icon').css('color',
                                     '#000'); // Change '#000' to your default color

                                 // Set selected stars to yellow
                                 for (var i = 1; i <= clickedRating; i++) {
                                     $('.star-icon[data-rating="' + i + '"]').css('color',
                                         '#800000'); // Yellow color
                                 }
                             });
                         });
                         </script>

                         <p class="form-row ">
                             <input type="text" name="uname" value="" placeholder="Your Name">
                         </p>
                         <p class="form-row ">
                             <input type="email" name="uemail" value="" placeholder="Email Address">
                         </p>
                         <p class="form-row">
                             <textarea name="ureview" id="txt-comment" cols="30"
                                 placeholder="Write Your Review Here..."></textarea>
                         </p>
                         <p class="form-row">
                             <button type="submit" name="save">submit review</button>
                         </p>
                     </form>
                 </div>
             </div>
         </div>

     </div>
 </div>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
$(document).ready(function() {
    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();

        var page = $(this).attr('href');

        $('#reviews-container').load(page + ' #reviews-container');
    });
});

 </script>
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script>
    $(document).ready(function() {
        // Get the current active tab
        var activeTab = $('.nav-tabs .active').attr('href');

        $('#ratingform').submit(function(event) {
            event.preventDefault(); // Prevent default form submission
            var formData = $(this).serialize(); // Serialize form data

            $.ajax({
                type: 'POST',
                url: 'reviewsave.php',
                data: formData,
                
            });
        });

    });
</script>


