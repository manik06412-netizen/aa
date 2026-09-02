<?php require_once('header.php'); ?>
<?php
$prd_id=$_GET['prd_id'];
$error_message = '';
$success_message = '';

// Insert form logic
if (isset($_POST['form_about'])) {
    $valid = 1;

    if (empty($_POST['about_title'])) {
        $valid = 0;
        $_SESSION['error_message'] .= 'Please Fill the Title<br>';
    }

    if (empty($_POST['about_content'])) {
        $valid = 0;
        $_SESSION['error_message'] .= 'Please Fill the Content<br>';
    }

    if ($valid == 1) {
        try {
            $statement = $pdo->prepare("INSERT INTO prd_description (pcode, title, desp) VALUES (?, ?, ?)");
            $statement->execute(array($_POST['prd_id'], $_POST['about_title'], $_POST['about_content']));

            $_SESSION['success_message'] = 'Information is added successfully.';
            echo "<script>window.location.href='$_SERVER[PHP_SELF]?prd_id=$prd_id';</script>";
            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] .= 'Error occurred while inserting data: ' . $e->getMessage() . '<br>';
        }
    }
}

// Fetch descriptions
$statement = $pdo->prepare("SELECT * FROM prd_description WHERE pcode = ? order by id desc");
$statement->execute(array($prd_id));
$descriptions = $statement->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Add Description</h1>
    </div>
    <div class="content-header-right">
        <h5 class="text-bold">Step 3/3</h5>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-lg-8" style="padding-top: 20px;">
            <!-- Add Description Form -->
            <div class="box box-info mb-5" style="padding: 25px 0px 25px 25px; margin:0px 30px 25px 25px;">
                <h4>Add Description</h4>
                <div class="card-body">
                    <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                        <div class="">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Title *</label>
                                <div class="col-sm-9">
                                    <input class="form-control" type="hidden" name="prd_id"
                                        value="<?php echo $prd_id; ?>" required>
                                    <input class="form-control" type="text" name="about_title" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Content *</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="about_content" id="editor1"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="form-actions text-right" style="padding-right:25px;">
                                <button type="submit" class="btn btn-success" name="form_about">Add</button>
                                <?php
                                $sql2 = "SELECT * FROM prd_description WHERE pcode=$prd_id";
                                $query2 = mysqli_query($con, $sql2);
                                if (mysqli_num_rows($query2) > 0) {
                                    echo '<a href="#" class="btn btn-warning" id="nextButton">Skip and Save</a>';
                                }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-4" style="padding-top: 20px;">
            <!-- Fetch Descriptions Section -->
            <div class="box box-info" style="padding: 25px;">
                <h4>Existing Descriptions</h4>
                <div class="card-body">
                    <?php if (!empty($descriptions)): ?>
                    <table id="example3" class="table table-bordered table-hover  nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <!-- <th>ID</th> -->
                                <th>Title</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($descriptions as $desc): ?>
                            <tr>
                                <!-- <td><?php echo htmlspecialchars($desc['id']); ?></td> -->
                                <td><?php echo htmlspecialchars($desc['title']); ?></td>
                                <td class="text-center">
                                    <a href="update_desc.php?desc_id=<?php echo $desc['id']; ?>&prd_id=<?php echo $prd_id; ?>"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil" style="font-size:16px"></i>
                                    </a>
                                    <a href="delete_description.php?desc_id=<?php echo $desc['id']; ?>&prd_id=<?php echo $prd_id; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this description?');">
                                        <i class="fa fa-trash-o" style="font-size:16px"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <p>No descriptions found for this product.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</section>
<div id="confirmation-modal">
        <div class="modal-content">
            <p>Are you sure you want to Finish?</p>
            <button class="btn btn-danger btn-sm" id="cancelButton">No</button>
            <a href="#" id="confirmNext" class="btn btn-success btn-lg">Yes</a>
        </div>
    </div>


<style>
/* Custom modal styles */
#confirmation-modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    display: none;
    /* Hide modal by default */
}

.modal-content {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    text-align: center;
    width: 300px;
    /* Adjust as needed */
}

.modal-content p {
    margin-bottom: 20px;
}

.modal-content button {
    padding: 10px 20px;
    margin: 0 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 16px;
}

.btn-success {
    background-color: #28a745;
    color: white;
}
.btn-danger {
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px;
        border: 1px solid #dc3545;
    }

   
.btn-success:hover {
    background-color: #218838;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            var nextButton = document.getElementById('nextButton');
            var cancelButton = document.getElementById('cancelButton');
            var confirmationModal = document.getElementById('confirmation-modal');
            var confirmNext = document.getElementById('confirmNext');

            // Show modal
            nextButton.addEventListener('click', function (event) {
                event.preventDefault(); // Prevent the default action of the link
                var prdId = <?php echo json_encode($prd_id); ?>;
                var nextUrl = 'products_list.php'; // Replace with the actual URL if needed
                confirmNext.href = nextUrl;
                confirmationModal.style.display = 'flex'; // Show the modal
            });

            // Hide modal on cancel
            cancelButton.addEventListener('click', function () {
                confirmationModal.style.display = 'none'; // Hide the modal
            });

            // Close modal when clicking outside of the modal content
            confirmationModal.addEventListener('click', function (event) {
                if (event.target === confirmationModal) {
                    confirmationModal.style.display = 'none';
                }
            });
        });
    </script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>


<?php require_once('footer.php'); ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    CKEDITOR.replace('editor1');
});
$(document).ready(function() {
    $('#example3').DataTable({
        "paging": true,
        "pageLength": 5,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "scrollX": true,
        "language": {
            "paginate": {
                "previous": "&lt;",
                "next": "&gt;"
            }
        },
        "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
            '<"row"<"col-sm-12"tr>>' +
            '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
    });
});
</script>