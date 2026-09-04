
<?php
include("../dbconnect.php");
error_reporting(0);
session_start();
$sql = "SELECT * FROM cust_reviews   JOIN dishes ON cust_reviews.cid = dishes.rs_id";
$query = mysqli_query($con, $sql);
?>
 <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <?php include "head.php"; ?>

<body class="fix-header">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper" style="height:1200px;">
            <div class="container-fluid">
                <div class="card card-outline-primary">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Products with Reviews</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive m-t-40">
                            <table class="display nowrap table table-hover table-striped table-bordered"style="text-align:center;" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th style="text-align:center;">Si.no</th>
                                        <th>Product Name</th>
                                        <th style="text-align:center;">View Reviews</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!mysqli_num_rows($query) > 0) {
                                        echo '<tr><td colspan="3"><center>No products with reviews!</center></td></tr>';
                                    } else {
                                        $ik = 1;
                                        while ($rows = mysqli_fetch_array($query)) {
                                            echo '<tr>
                                                    <td>' . $ik++ . '</td>
                                                    <td>' . htmlspecialchars($rows['dish_name']) . '</td>
                                                                       <td style="text-align:center;">
                                                                               
                                                                       <button type="button" data-id="' . $rows['cid'] . '" class="btn btn-info view-reviews" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  View Reviews
</button>
                      
                    </td>

                                                  </tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/lib/jquery/jquery.min.js"></script>
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/jquery.slimscroll.js"></script>
    <script src="js/sidebarmenu.js"></script>
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="js/custom.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<!-- Popper.js (required for Bootstrap components like tooltips and popovers) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.8/umd/popper.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<div class="modal fade " id="staticBackdrop"  tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Customer Reviews and Ratings</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <div id="reviewsContent"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function() {
    $('.view-reviews').click(function() {
        var productId = $(this).data('id');
        
      
        $.ajax({
            url: 'get_reviews.php',
            type: 'GET',
            data: { cid: productId },
            success: function(data) {
              
                $('#reviewsContent').html(data);
            },
            error: function() {
                alert('Failed to retrieve reviews. Please try again.');
            }
        });
    });
});
</script>

</body>
</html>

