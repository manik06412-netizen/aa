<?php

include "header.php"; 

$product_id = $_GET['id'];


$sql = "SELECT * FROM cust_reviews WHERE cid='$product_id' ORDER BY createdat DESC";
$query = mysqli_query($con, $sql);
?>
<section class="content-header">
    <div class="content-header-left">
        <h1>Product Reviews</h1>
    </div>
    <div class="content-header-right">

        <a href="reviews.php" class="btn btn-sm"
            style="background-color:#FF851B; color:white; border:1px solid #FF851B;">Go Back</a>
    </div>
</section>


<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <?php
  
if (mysqli_num_rows($query) > 0) {
    $ik = 1;
    
    echo '<table id="example1" class="table table-hover table-striped table-bordered" style="text-align:center;">';
    echo '<thead>
            <tr>
                <th style="display: none;">Id</th>
                <th style="text-align:center;">Customer Name</th>
                <th style="text-align:center;">Rating</th>
                <th style="text-align:center;">Review</th>
                <th style="text-align:center;">Date</th>
                <th style="text-align:center;">Action</th>
            </tr>
          </thead>
          <tbody>';
    while ($rows = mysqli_fetch_array($query)) {
        $date = DateTime::createFromFormat('Y-m-d', $rows['createdat']);
        $formattedDate = $date->format('d-m-Y');
        echo '<tr>
        
               <td style="display: none;">'. htmlspecialchars($rows['uid']) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($rows['uname']) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($rows['uratings']) . '</td>
                <td style="text-align:center;">' . htmlspecialchars($rows['ureview']) . '</td>
                <td style="text-align:center;">' .   $formattedDate  . '</td>
                <td style="text-align:center;"><a href="#" data-href="delete_review.php?id='.$rows['uid'] .'" data-toggle="modal" data-target="#confirm-delete" class="btn btn-danger btn-flat btn-addon btn-xs m-b-10"><i class="fa fa-trash-o" style="font-size:16px"></i></a></td>
                            </tr>';
    }
    echo '</tbody></table>';
} 

mysqli_close($con);
include "footer.php";
?>
                </div>
            </div>


</section>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this Information?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>
