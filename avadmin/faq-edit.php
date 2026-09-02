<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

	if(empty($_POST['faq_title'])) {
		$valid = 0;
		$error_message .= 'Please Fill the Title <br>';
	}

	if(empty($_POST['faq_content'])) {
		$valid = 0;
		$error_message .= 'Please Fill the Content<br>';
	}

	if($valid == 1) {

		$statement = $pdo->prepare("UPDATE tbl_faq SET faq_title=?, faq_content=? WHERE faq_id=?");
		$statement->execute(array($_POST['faq_title'],$_POST['faq_content'],$_REQUEST['id']));
		   

	    $success_message = 'FAQ is updated successfully!';
	}
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_faq WHERE faq_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Edit FAQ</h1>
	</div>
	<div class="content-header-right">
		<a href="faq.php" class="btn  btn-sm"style="background-color:#FF851B; color:white; border:1px solid #FF851B;">View All</a>
	</div>
</section>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_faq WHERE faq_id=?");
$statement->execute(array($_REQUEST['id']));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
	$faq_title = $row['faq_title'];
	$faq_content = $row['faq_content'];
}
?>
<style>
textarea.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: none; 
    color: #dc3545;
}
 
</style>
<section class="content">

	<div class="row">
		<div class="col-md-12">

			<?php if($error_message): ?>
			<div class="callout callout-danger">
				<p>
				<?php echo $error_message; ?>
				</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
				<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>
			<form class="form-horizontal" action="" method="post" id="myForm">
    <div class="box box-info">
        <div class="box-body">
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Title <span>*</span></label>
                <div class="col-sm-6">
                    <input type="text" autocomplete="off" class="form-control" name="faq_title" value="<?php echo $faq_title; ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="" class="col-sm-2 control-label">Content <span>*</span></label>
                <div class="col-sm-9">
                    <textarea class="form-control" name="faq_content" id="editor1" style="height:140px;"><?php echo $faq_content; ?></textarea>
                    <div class="invalid-feedback" id="error_message" style="display:none;">This field is required.</div>
                </div>
            </div>  
            <div class="form-group">
                <label for="" class="col-sm-2 control-label"></label>
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-success pull-left" id="submit_btn" name="form1">Submit</button>
                </div>
            </div>
        </div>
    </div>
</form>

		</div>
	</div>

</section>
<script>
    function Form_check(){
     let btn =  document.getElementById("submit_btn");
    //  let mForm = document.getElementById("mForm");
     let editor1 = document.getElementById("editor1").value;
     if(editor1){
        btn.type="submit";
     }else{
        document.getElementById("error_message").style.display ="block";
     }
     
    }
	$(document).ready(function() {
    // Initialize Summernote editor
    $('#editor1').summernote({
        height: 300
    });

    $('#myForm').on('submit', function(event) {
        var valid = true;

        // Check Summernote editor content
        if ($('#editor1').summernote('code').trim() === '') {
            $('#error_message').show();
            $('#editor1').addClass('is-invalid');
            valid = false;
        } else {
            $('#error_message').hide();
            $('#editor1').removeClass('is-invalid');
        }

        if (!valid) {
            event.preventDefault(); // Prevent form submission if validation fails
            $('html, body').animate({scrollTop: $('.is-invalid').first().offset().top - 20}, 500); // Scroll to the first invalid editor
        }
    });
});

</script>

<?php require_once('footer.php'); ?>