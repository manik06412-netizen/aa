<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/inc/config.php';
if (!isset($_SESSION["admin1_user"])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION["adm_id"])) {
    $_SESSION["adm_id"] = $_SESSION["admin1_user"]["id"] ?? 1;
}
if (!isset($_SESSION["user"])) {
    $_SESSION["user"] = [
        "id"        => $_SESSION["admin1_user"]["id"] ?? 1,
        "full_name" => $_SESSION["admin1_user"]["full_name"] ?? "Admin",
        "email"     => $_SESSION["admin1_user"]["email"] ?? "",
        "photo"     => $_SESSION["admin1_user"]["photo"] ?? "no_image.png",
    ];
}
?>
<?php
session_start();
include("../dbconnect.php");
//error_reporting(0);
?>
<!DOCTYPE html>
<html lang="en">
<?php include "head.php"; ?>

<?php
if(isset($_POST['submit'])){

   include 'dbconnect.php'; 
   
  
   $description = mysqli_real_escape_string($con, $_POST['description']);
   $link = mysqli_real_escape_string($con, $_POST['link']);
   $image = $_FILES['image'];
   $currentDate = date('Y-m-d'); 
   
   
   $target_dir = "./Res_img/pop_image/"; 
   $target_file = $target_dir . basename($_FILES["image"]["name"]);
   $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
   $uploadOk = 1;
   
   
   
   if ($uploadOk == 0) {
       echo "Sorry, your file was not uploaded.";
   } else {
       if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
           echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])) . " has been uploaded.";
           $imageName = basename($_FILES["image"]["name"]);
   
        
           $sql = "INSERT INTO popup (description, link, image_name, date) VALUES (?, ?, ?, ?)";
   
           $stmt = $con->prepare($sql);
           if ($stmt === false) {
               echo "Error preparing statement: " . $con->error;
           } else {
               $stmt->bind_param("ssss", $description, $link, $imageName, $currentDate);
               if ($stmt->execute()) {
                  echo "<script>alert('popup added');</script>";

               } else {
                   echo "Error: " . $stmt->error;
               }
               $stmt->close();
           }
       } else {
           echo "Sorry, there was an error uploading your file.";
       }
   }
   
   $con->close();

}

?>




<body class="fix-header">
   <div id="main-wrapper">
      <?php include "navbar.php"; ?>
      <?php include "sidebar1.php"; ?>
      <div class="page-wrapper" style="height:1200px;">

         <div class="container-fluid">
            <div class="col-lg-12">
               <div class="card card-outline-primary">
                  <div class="card-header">
                     <h4 class="m-b-0 text-white">view Popup</h4>
                  </div>
            
                  <?php

$query = " select * FROM popup ORDER BY id DESC limit 1" ; 
$result = $con->query($query);
?>



            <table class="table table-bordered table-hover">
                <thead class="">
                    <tr class="text-white">
                        <th>Description</th>
                        <th>Link</th>
                        <th>Image</th>
                        <th>Date</th>
                     
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while($rows = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$rows['description'].'</td>
                                <td><a href="'.$rows['link'].'" target="_blank">Link</a></td>
                                <td>
                                    <div class="text-center">
                                        <img src="Res_img/pop_image/'.$rows['image_name'].'" class="img-fluid rounded" style="max-height:100px; max-width:150px;" />
                                    </div>
                                </td>
                                <td>'.$rows['date'].'</td>
                              
                            </tr>';
                    }
                    ?>
                </tbody>
            </table>


<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>



                  


               </div>

               
               
            </div>
         </div>
      </div>
      </form>
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
</body>

</html>