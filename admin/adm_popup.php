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
   $title = mysqli_real_escape_string($con, $_POST['title']);
   $image = $_FILES['image'];
   $currentDate = date('Y-m-d'); 
   
   
   $target_dir = "./Res_img/pop_image/"; 
   $target_file = $target_dir . basename($_FILES["image"]["name"]);
   $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
   $uploadOk = 1;
   
   
   
   if ($uploadOk == 0) {
    //    echo "Sorry, your file was not uploaded.";
   } else {
       if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

        //    echo "The file ". htmlspecialchars( basename( $_FILES["image"]["name"])) . " has been uploaded.";
           $imageName = basename($_FILES["image"]["name"]);
   
        
           $sql = "INSERT INTO popup (title, description, link, image_name, date) VALUES (?, ?, ?, ?, ?)";
   
           $stmt = $con->prepare($sql);
           if ($stmt === false) {
               echo "Error preparing statement: " . $con->error;
           } else {
               $stmt->bind_param("sssss", $title ,$description, $link, $imageName, $currentDate);
               if ($stmt->execute()) {

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


<style>
    .toast {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 9999;
      background-color: rgb(92, 74, 199);
      padding: 10px 40px;
      color: white;
      border-radius: 10px
    }
    .toast-header {
      background-color: rgb(92, 74, 199);
      color: #fff;
    }
  </style>
<body class="fix-header">
   <div id="main-wrapper">
      <?php include "navbar.php"; ?>
      <?php include "sidebar1.php"; ?>
      <div class="page-wrapper" style="height:1200px;">

         <div class="container-fluid">
            <div class="col-lg-12">
               <div class="card card-outline-primary">
                  <div class="card-header">
                     <h4 class="m-b-0 text-white">Add Popup</h4>
                  </div>
                  <div class="card-body">
                     <form action='' method='post' enctype="multipart/form-data" id="myForm">
                        <div class="form-body">
                           <hr>
                           <div class="row p-t-20">
                           <div class="col-md-6">
                           <div class="form-group">
                                    <label class="control-label">Enter Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Enter Title for popup"
                                       required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Enter link</label>
                                    <input type="text" name="link"  class="form-control" placeholder="Enter link"
                                       required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <label class="control-label">Enter Description</label>
                                 <div class="form-group">
                                    <textarea type="text" id="myTextarea"  maxlength="200" minlength="50" name="description" placeholder="Enter Description" rows="5"
                                       cols="50" required></textarea>
                                       <div id="errorMessage" class="error-message"></div>

                                 </div>
                              </div>

                           

                              <div class="col-md-6">
                              <div class="form-group has-danger">
                                 <label for="imageUpload">Upload Images:</label>
                                 <div class="form-group has-danger">
                                    <input class='m-2' type="file" class="form-control-file" id="imageUpload"
                                       name="image" required>
                                 </div>
                              </div>
                           </div>
                           </div>

                        </div>

                        <div class="row">
                          
                        </div>
                  </div>
               </div>

               <div class="form-actions ml-3">
                  <input type="submit" name="submit" class="btn btn-success" value="Save">
                  <a href="#" class="btn btn-inverse">Cancel</a>
               </div>
            </div>
         </div>
      </div>
      </form>












      
 


<body class="fix-header" >
   <div id="main-wrapper" style="margin-top: -650px">
     
      <div class="page-wrapper" style="height:1200px;">

         <div class="container-fluid">
            <div class="col-lg-12">
               <div class="card card-outline-primary">
                  <div class="card-header">
                     <h4 class="m-b-0 text-white">view Popup</h4>
                  </div>
            
                  <?php

$query = "SELECT *  FROM popup"; 
$result = $con->query($query);
?>



            <table class="table table-bordered table-hover">
                <thead class="">
                    <tr class="text-white">
                        <th>Description</th>
                        <th>Link</th>
                        <th>Image</th>
                        <th>Date</th>
                        <th>Actions</th>
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
                                <td>
                                    <a href="" onclick="confirmDelete('.$rows['id'].')" class="btn btn-danger btn-sm">
                                        <i class="fa fa-trash-o"></i>
                                    </a>

                                    <a href="popup_update.php?cat_upd=' . $rows['id'] . '" class="btn btn-info  btn-sm ">
                                    <i class="ti-settings"></i>
                                </a>
                                </td>
                            </tr>';
                           //  echo $rows['id'];
                    }
                    ?>
                </tbody>
            </table>



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
   </div>
   </div>
   </div>
   </div>

   </div>

   </div>
















   <script>
                        function confirmDelete(Id) {
                            
                            var confirmDelete = confirm("Are you sure you want to delete this popup?");
                            console.log(confirmDelete);
                            if (confirmDelete) {
                                window.location.href = 'delete_popup.php?popup_del=' + Id;
                            } else {
                                
                            }
                        }
                     </script>












   <div class="toast" id="myToast" style="display: none;">
    <div class="toast-header">
      <strong class="mr-auto">Success</strong>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" style="color: white;">&times;</button>
    </div>
    <div class="toast-body">
      popup successfully added
    </div>
  </div>

  <?php
include("../dbconnect.php");

if(isset($_GET['popup_del'])){

   // sending query
   mysqli_query($con,"DELETE FROM popup WHERE id = '".$_GET['cat_del']."'");
   echo "<script>window.location.href='deals_view.php';</script>";
}


?>

<!-- Bootstrap and jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
   <script src="js/lib/jquery/jquery.min.js"></script>

   <script src="js/lib/bootstrap/js/popper.min.js"></script>
   <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>

   <script src="js/jquery.slimscroll.js"></script>

   <script src="js/sidebarmenu.js"></script>

   <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>

   <script src="js/custom.min.js"></script>
   <script>
  $(document).ready(function() {
  $('#myForm').submit(function(event) {
    // Prevent the default form submission to delay it
    // event.preventDefault();

    // Reference to the form to submit it later
    var form = this;

    // Show toast notification
    $('#myToast').fadeIn();
    setTimeout(function() {
      $('#myToast').fadeOut(function() {
        // After fadeout, submit the form traditionally
        form.submit();
      });
    }, 500); // Show the toast for 1 second before fading out
  });
});

</script>
 <script>
        const textarea = document.getElementById('myTextarea');
        const errorMessage = document.getElementById('errorMessage');

        const minLength = 50;
        const maxLength = 200;

        // Update error message and disable/enable textarea based on length
        function updateValidation() {
            const currentLength = textarea.value.length;

            if (currentLength < minLength) {
                const remainingChars = minLength - currentLength;
                errorMessage.textContent = `Minimum ${minLength} characters required and Maximum 200 characters`;
                textarea.disabled = false; // Enable textarea
            } else if (currentLength > maxLength) {
                const excessChars = currentLength - maxLength;
                errorMessage.textContent = `Maximum ${maxLength} characters exceeded. Remove ${excessChars} characters.`;
                textarea.disabled = false; // Enable textarea
            } else {
                errorMessage.textContent = '';
                textarea.disabled = false; // Enable textarea
            }
        }

        // Listen for input events
        textarea.addEventListener('input', updateValidation);
    </script>
</body>

</html>