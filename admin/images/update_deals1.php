<?php 
session_start();
include('../dbconnect.php');
if(isset($_POST['submit'])){
    $dd_id=$_POST['dd_id'];
    $d_name=$_POST['d_name'];
    $category=$_POST['category'];
    $ac_price=$_POST['ac_price'];
    $dis_price=$_POST['dis_price'];
    $exp_date=$_POST['exp_date'];
    $discount=$_POST['discount'];
    $current_time=$_POST['current_date'];
    $modify_date=$_POST['modify_date'];
    if($_FILES['images1']['name']){
        $fname = $_FILES['images1']['name'];
        $tempname = $_FILES['images1']['tmp_name'];
        $fsize = $_FILES['images1']['size'];
        $filedir =  "images/";
        $photo = $filedir . $fname;
    
        if (move_uploaded_file($tempname,$photo)) {
          
        } 
    }else{
        $photo=$_POST['img2'];
    }
    $update=mysqli_query($con,"UPDATE  deals_prot set pr_name='$d_name',category='$category',actual_pri='$ac_price',dis_pri='$dis_price',exp_date='$exp_date',discount='$discount',img='$photo',date='$date',current_time='$current_time',modify_time='$modify_date' where d_id='$dd_id'");
    if($update){
        echo "<script>alert('Product Update Successfully');window.location.href='deals_view.php';</script>";

    }else{
        echo "<script>alert('sorry');window.location.href='deals_view.php';</script>";
    }
}
?>