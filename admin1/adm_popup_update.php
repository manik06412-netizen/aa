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
include("../dbconnect.php"); // Adjust the path as needed

// Handle the form submission for updating the popup details
if (isset($_POST['update'])) {
    // Sanitize and prepare variables from form submission
    $id = $_POST['id'];
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = mysqli_real_escape_string($con, $_POST['description']);
    $link = mysqli_real_escape_string($con, $_POST['link']);
    $currentDate = date('Y-m-d'); // Assuming you want to update the date as well

    // Image handling logic
    $imageName = ""; // Initialize to empty, indicating no new image by default
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $target_dir = "./Res_img/pop_image/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $imageName = basename($_FILES["image"]["name"]);
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Prepare the UPDATE SQL, conditional on whether a new image was uploaded
    $sql = "UPDATE popup SET title = ?, description = ?, link = ?, date = ?";
    $sql .= ($imageName != "") ? ", image_name = ?" : ""; // Append image_name in SQL if new image
    $sql .= " WHERE id = ?";

    $stmt = $con->prepare($sql);
    if ($imageName != "") {
        // Bind parameters including the new image name
        $stmt->bind_param("sssssi", $title, $description, $link, $currentDate, $imageName, $id);
    } else {
        // Bind parameters without the new image name
        $stmt->bind_param("ssssi", $title, $description, $link, $currentDate, $id);
    }

    if ($stmt->execute()) {
        echo "Popup updated successfully.";
    } else {
        echo "Error updating record: " . $stmt->error;
    }
    $stmt->close();
    // Optionally, redirect or re-fetch the updated data here
}

// Check if the 'cat_upd' GET parameter is set for fetching and displaying popup details
if (isset($_GET['cat_upd'])) {
    $popupId = $_GET['cat_upd'];
    $query = "SELECT * FROM popup WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $popupId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    echo $row['id'];
    $stmt->close();
} else {
    // Handle case where no popup ID is specified for editing
    echo "No popup ID specified for editing.";
    exit; // Exit if no specific popup is intended for update
}
?>


<!DOCTYPE html>
<html lang="en">;
<?php include "head.php"; ?>
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
                     <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <div class="form-body">
                           <hr>
                           <div class="row p-t-20">
                           <div class="col-md-6">
                           <div class="form-group">
                                    <label class="control-label">Enter Title</label>
                                    <input type="text" name="title" class="form-control" placeholder="Enter Title for popup" value="<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>"
                                       required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">Enter link</label>
                                    <input type="text" name="link"  class="form-control" placeholder="Enter link" value="<?php echo htmlspecialchars($row['link'], ENT_QUOTES); ?>"
                                       required>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <label class="control-label">Enter Description</label>
                                 <div class="form-group">
                                    <textarea type="text" id="myTextarea"  maxlength="200" minlength="50" name="description" placeholder="Enter Description" rows="5"
                                       cols="50" required>
                                       <?php echo htmlspecialchars($row['description'], ENT_QUOTES); ?>
                                    </textarea>
                                       <div id="errorMessage" class="error-message"></div>

                                 </div>
                              </div>

                           

                              <div class="col-md-6">
                              <div class="form-group has-danger">
                                 <label for="imageUpload">Upload Images:</label>
                                 <div class="form-group has-danger">
                                    <input class='m-2' type="file" class="form-control-file" id="imageUpload"
                                       name="image" >
                                       <?php if (!empty($row['image_name'])): ?>
                <p>Current Image: <?php echo htmlspecialchars($row['image_name'], ENT_QUOTES); ?></p>
            <?php endif; ?>
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
                  <input type="submit" name="update" class="btn btn-success" value="Save">
                  <a href="popup.php" class="btn btn-inverse">Back</a>

               </div>
            </div>
         </div>
      </div>
     
      </form>

      <div class="toast" id="myToast" style="display: none;">
    <div class="toast-header">
      <strong class="mr-auto">Success</strong>
      <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" style="color: white;">&times;</button>
    </div>
    <div class="toast-body">
      popup updated successfully 
    </div>
  </div>


</body>
</html>
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
      // event.preventDefault(); // Prevent form submission

      // Show toast notification
      $('#myToast').fadeIn();
      setTimeout(function() {
        $('#myToast').fadeOut(function() {
          // After fadeout, refresh the page
          location.reload();
        });
      }, 1000); // Fade out after 3 seconds
    });
  });
</script>