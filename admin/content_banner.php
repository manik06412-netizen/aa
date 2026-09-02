<!DOCTYPE html>
<html lang="en">
<head>
 
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        .nav {
            display: flex;
            list-style-type: none;
            padding: 0;
        }

        .nav li {
            margin-right: 15px;
        }

        .nav li a {
            text-decoration: none;
            color: #007bff;
        }

        .nav li a.active {
            font-weight: bold;
            color: #0056b3;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .card .img {
            max-height: 100px;
            max-width: 150px;
        }

        .image-card {
            position: relative;
            width: 100%;
            max-width: 300px;
            margin: auto;
        }

        .delete-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            z-index: 10;
        }

        .delete-btn:hover {
            background-color: rgba(255, 2, 0, 0.9);
        }
    </style>
    <?php
    include("../config.php");
    error_reporting(0);
    session_start();

    $query1 = "SELECT image FROM logo ORDER BY id DESC LIMIT 1";
    $result1 = mysqli_query($con, $query1);
    $logo = mysqli_fetch_assoc($result1)['image'];

    $query2 = "SELECT * FROM fevicon ORDER BY id DESC LIMIT 1";
    $result2 = mysqli_query($con, $query2);
    $fevicon = mysqli_fetch_assoc($result2)['fevicon'];
    $fevicon1 = mysqli_fetch_assoc($result2)['fname'];

    $query3 = "SELECT * FROM footer_contact ORDER BY id DESC LIMIT 1";
    $result3 = mysqli_query($con, $query3);
    $contact = mysqli_fetch_assoc($result3);

    $query4 = "SELECT * FROM about_us ";
    $result4 = mysqli_query($con, $query4);
    $about = mysqli_fetch_assoc($result4);

  
    ?>
    <?php include "head.php"; ?>
</head>
<body class="fix-header fix-sidebar">
    <div id="main-wrapper">
        <?php include "navbar.php"; ?>
        <?php include "sidebar1.php"; ?>
        <div class="page-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Banners Settings</h4>
                             <div id="banner-section" class="section">
                                    <form action='add_websettings.php' method='post' enctype='multipart/form-data'>
                                        <div class="form-body">
                                            <hr>
                                            <div class="row p-t-20">
                                                <div class="col-md-12">
                                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($slider['id']); ?>">
                                                    <div class="form-group">
                                                        <label class="control-label">Add Banner</label>
                                                        <input type="file" name="banner" class="form-control" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions">
                                                <input type="submit" name="banner" class="btn btn-success" value="Update">
                                                <a href="dashboard.php" class="btn btn-inverse">Cancel</a>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="card">
                                        <div class="row">
                                        <?php
                                         $query5= "SELECT * FROM slider ";
                                        $result5 = mysqli_query($con, $query5);
                                        while($slider=mysqli_fetch_array($result5)){
                                        ?>
                                          <div class="col-lg-4 image-card">
    <img src="<?php echo $slider['banner_img']; ?>" width="310px" height="150px" style="border: 1px solid black; margin-top: 2px;">
    <a href="delete_image.php?bid=<?php echo $slider['id']; ?>">
        <button type="submit" name="delete" class="delete-btn">
            <i class="fa fa-trash"></i>
        </button>
    </a>
</div>
                                            <?php
  }
  ?>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
<?php 
$tap_part = $_GET['tap_'];
?>
<script>
   function phoneNumberValidation(input) {
    const phoneNumberPattern = /^[7896][0-9]{0,9}$/; // Updated to allow partial valid numbers
    const errorMessage = document.getElementById('contactPhoneError');

    // Remove any non-digit characters
    input.value = input.value.replace(/\D/g, '');

    // Validate the input against the pattern
    if (!phoneNumberPattern.test(input.value) || input.value.length > 10) {
        errorMessage.style.display = 'block';
        input.classList.add('is-invalid');
    } else {
        errorMessage.style.display = 'none';
        input.classList.remove('is-invalid');
    }
}
    function modify_tap(){
        let tap_value = "<?= $tap_part; ?>";
        let get_the_part = document.getElementById(tap_value);
        get_the_part.style.display = 'block';
    }
    setTimeout(()=>{
        modify_tap();
    },2000);
 
</script>
    <script>
        $(document).ready(function () {
            $(document).ready(function () {
    $('.nav-link').on('click', function (e) {
        e.preventDefault();

        // Hide all sections
        $('.section').hide();

        // Show the target section
        var target = $(this).data('target');
        $('#' + target).show();
    });

    // Initial setup: show the first section only
    $('.section').hide(); // Hide all sections
    $('.section').first().show(); // Show the first section by default
});

            $('#deleteModal').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var imageId = button.data('id');
                var modal = $(this);
                modal.find('#confirmDelete').data('id', imageId);
            });

            $('#confirmDelete').on('click', function () {
                var imageId = $(this).data('id');
                $.ajax({
                    url: 'delete_image.php',
                    method: 'POST',
                    data: { id: imageIds },
                    success: function (response) {
                        location.reload();
                    }
                });
            });
        });
    </script>
      <footer class="footer"> © All rights reserved. </footer>
    <!-- End footer -->
    </div>
    <!-- End Page wrapper  -->
    </div>
    <!-- End Wrapper -->
    <!-- All Jquery -->
    <script src="js/lib/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="js/lib/bootstrap/js/popper.min.js"></script>
    <script src="js/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Menu sidebar -->
    <script src="js/sidebarmenu.js"></script>
    <!--stickey kit -->
    <script src="js/lib/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <!--Custom JavaScript -->
    <script src="js/custom.min.js"></script>
</body>
</html>
