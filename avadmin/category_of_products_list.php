<?php
error_reporting(0);
$category_name = trim($_GET['category_name']);
?>
<?php require_once('header.php'); ?>
<style>
.bg-prim {
    background-color: rgb(146, 187, 211) !important;
}
</style>

<section class="content-header">
    <div class="content-header-left">
        <h1 style="text-transform:capitalize"><?=$category_name; ?> Category</h1>
    </div>
    <div class="content-header-right">
        <button class="btn btn-primary btn-xs" id="export_table">Export to CSV</button>
        <button class="btn btn-primary btn-xs" id="print_table">Print Table</button>
    </div>
</section>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table text-center table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th style="width:17%">Product Name</th>
                                <th style="width:15%">Date of Added</th>
                                <th style="width:25%">Update</th>
                                <th style="width:12%">Current Status</th>
                                <th style="width:12%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
            $i = 0;
            $sql = "SELECT * FROM dishes where category= '$category_name' order by d_id desc";
            $query = mysqli_query($con, $sql);

            if (mysqli_num_rows($query) > 0) {
                while ($rows = mysqli_fetch_array($query)) {
                    $statusClass = ($rows['status'] == 1) ? 'bg-g' : (($rows['status'] == 2) ? 'bg-r' : 'bg-prim');
                    $statusText = ($rows['status'] == 1) ? 'Active' : (($rows['status'] == 2) ? 'Inactive' : 'Scheduled');
                    $descriptionLink = htmlspecialchars($rows['rs_id']);
                    $statusLink = htmlspecialchars($rows['d_id']);
                    $deleteLink = htmlspecialchars($rows['d_id']);
                    $editLink = $rows['d_id'];
                    $priceLink = 'add_price.php?prd_id=' . htmlspecialchars($rows['rs_id']);
                    $stockLink = 'add_stock.php?prd_id=' . htmlspecialchars($rows['rs_id']);
                    $updateDescLink = 'update_desc.php?prd_id=' . htmlspecialchars($rows['rs_id']);
                    $statusChangeLink = 'product_status1.php?status_id=' . htmlspecialchars($rows['d_id']);
                    $deleteConfirmLink = 'product_status1.php?del_id=' . htmlspecialchars($rows['d_id']);

                    echo '<tr class="' . $statusClass . '">

                        <td>
                            <center><img src="' . htmlspecialchars($rows['img']) . '" class="img-responsive radius" style="height:40px;width:40px;" /></center><br/>
                            <b>' . htmlspecialchars($rows['dish_name']) . '</b>
                        </td>
                        <td>' . htmlspecialchars($rows['date_of_adding']) . '</td>
                        <td >
                        <div class="row">
                        <div class="col-md-6">
                         <a href="' . $priceLink . '" class="btn btn-warning btn-xs btn-flat btn-addon btn-sm m-b-10 m-l-5" style="width:100%; margin-top:5px;" >Price Update</a>
                        </div>
                        <div class="col-md-6">
                           <a href="' . $stockLink . '" class="btn btn-info btn-xs btn-flat btn-addon btn-sm m-b-10 m-l-5" style="width:100%; margin-top:5px;" >Stock Update</a>
                        </div>
                        <div class="col-md-6">
                           <a class="btn btn-primary btn-xs" href="' . $updateDescLink . '"  style="width:100%; margin-top:5px;">Description Update</a>
                        </div>
                        <div class="col-md-6">
                           <a href="' . $statusChangeLink . '" class="btn btn-success btn-flat btn-addon btn-xs btn-sm m-b-10 m-l-5" style="width:100%; margin-top:5px;">Status Update</a>
                        </div>
                        </div>
                        </td>
                        <td><h6>' . $statusText . '</h6></td>
                        <td>
                            <a href="#" onclick="Products_delete(' . htmlspecialchars($rows['d_id']) . ')" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#confirm-delete">
                                <i class="fa fa-trash-o" style="font-size:16px"></i>
                            </a>
                            <a href="product_update.php?menu_upd=' . $editLink . '" class="btn btn-info btn-xs btn-flat btn-addon btn-sm m-b-10 m-l-5">Edit</a>
                        </td>
                    </tr>';
                }
                    } else {
                    
                    }
                    ?>
                        </tbody>
                    </table>

                </div>
            </div>
</section>

<script>
$('#example1').DataTable({
    "order": [],
    "columnDefs": [{
        "orderable": false,
        "targets": "_all"
    }]
});
</script>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Delete Confirmation</h4>
            </div>
            <div class="modal-body">
                Are you sure want to delete this Product?
            </div>
            <div class="modal-footer">
                <input type="hidden" id="delete_id">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a onclick="Submit_delete()" class="btn btn-danger btn-ok">Delete</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>


<script>
let url_id = 0;

function Products_delete(id_name) {
    console.log(id_name);
    document.getElementById("delete_id").value = id_name;
}

function Submit_delete() {
    let id_name = document.getElementById("delete_id").value;
    location.href = `product_delete.php?id=${id_name}`;
}
</script>