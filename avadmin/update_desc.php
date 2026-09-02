<?php require_once('header.php'); ?>
<?php
session_start();
error_reporting();
$prd_id = $_GET['prd_id'];
$error_message = '';
$success_message = '';

// Check if the form is submitted to update a specific description
if (isset($_POST['form_update'])) {
    $desc_id = $_POST['desc_id']; // Retrieve the description ID
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
            // Update the specific description based on ID
            $statement = $pdo->prepare("UPDATE prd_description SET title = ?, desp = ? WHERE id = ?");
            $statement->execute(array($_POST['about_title'], $_POST['about_content'], $desc_id));

            $_SESSION['success_message'] = 'Information updated successfully.';

            // Redirect to the same page but with the updated description ID
            // header("Location: update_desc.php?desc_id=" . $desc_id . "&prd_id=" . $prd_id);
            header("Location: " . $_SERVER['PHP_SELF'] . "?prd_id=" . $prd_id . "&desc_id=" . $desc_id);


            exit;
        } catch (Exception $e) {
            $_SESSION['error_message'] .= 'Error occurred while updating data: ' . $e->getMessage() . '<br>';
        }
    }
}

if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

$descri = isset($_GET['desc_id']) ? $_GET['desc_id'] : null;

// Base parameters
$params = array($prd_id);

// Base SQL query to fetch all descriptions for listing
$sql1 = "SELECT * FROM prd_description WHERE pcode = ?";

// Prepare and execute the first query (fetch all descriptions for listing)
$statement1 = $pdo->prepare($sql1);
$statement1->execute($params);
$descriptions1 = $statement1->fetchAll(PDO::FETCH_ASSOC);

// Fetch the specific description if desc_id is set
if (isset($_GET['desc_id'])) {
    $sql2 = "SELECT * FROM prd_description WHERE pcode = ? AND id = ?";
    $params[] = $_GET['desc_id'];
    
    // Prepare and execute the second query
    $statement2 = $pdo->prepare($sql2);
    $statement2->execute($params);
    $descriptions2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Fallback to fetch all descriptions (optional, depends on your logic)
    $descriptions2 = $descriptions1; // Assuming you want the same results if no desc_id
}
?>

<section class="content-header">
    <div class="content-header-left">
        <h1>Update Descriptions</h1>
    </div>
    <div class="content-header-right">
        <a href="p_description.php?prd_id=<?php echo $prd_id; ?>" class="btn btn-sm" style="background-color:#FF851B; color:white; border:1px solid #FF851B;">Add More</a>
    </div>
</section>

<section class="content">
    <div class="row">
        <div class="col-12">
            <?php if($error_message): ?>
            <div class="callout callout-danger">
                <p><?php echo $error_message; ?></p>
            </div>
            <?php endif; ?>

            <?php if($success_message): ?>
            <div class="callout callout-success">
                <p><?php echo $success_message; ?></p>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-md-9" style="padding-top: 20px;">
            <div class="box box-info" style="padding: 25px 0px 25px 25px; margin:0px 30px 25px 25px;">
                <div class="card-body">
                    <!-- Loop through only the specific description (if desc_id is set) -->
                    <?php if ($descriptions2): ?>
                        <?php foreach ($descriptions2 as $key => $desc): ?>
                            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Page Title * </label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="about_title" value="<?php echo htmlspecialchars($desc['title']); ?>" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="" class="col-sm-2 control-label">Page Content * </label>
                                        <div class="col-sm-9">
                                            <!-- Assign unique ID using $key for each textarea -->
                                            <textarea class="form-control" name="about_content" id="editor<?php echo $key + 1; ?>"><?php echo htmlspecialchars($desc['desp']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-actions text-right">
                                        <input type="hidden" name="desc_id" value="<?php echo $desc['id']; ?>">
                                        <button type="submit" class="btn btn-success" name="form_update">Update</button>
                                    </div>
                                </div>
                            </form>
                            <hr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-3" style="padding-top: 20px;">
            <!-- Fetch Descriptions Section -->
            <div class="box box-info" style="padding: 25px;">
                <h4> Descriptions List</h4>
                <div class="card-body">
                    <table id="example3" class="table table-bordered table-hover nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($descriptions1)): ?>
                                <?php foreach ($descriptions1 as $desc): ?>
                                    <tr>
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
                            <?php else: ?>
                                <tr>
                                    <td colspan="2" class="text-center">No descriptions found for this product.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>

<!-- Add this script at the end of the page -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize CKEditor for all textareas
        document.querySelectorAll('textarea[id^="editor"]').forEach(function(textarea) {
            CKEDITOR.replace(textarea.id);  // Initialize CKEditor by id
        });
    });
</script>
