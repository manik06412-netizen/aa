<?php
session_start();
 
    include("dbconnect.php");
    if(isset($_POST['reg'])){
        $fname=$_POST['fname'];
        $lname=$_POST['lname'];
        $email=$_POST['email'];
        $pwd=$_POST['pwd'];
        $sta='1';
        $mob=$_POST['mobile'];
        $pwd1=md5($pwd);
        $date=date("d/m/Y");
        $usrid=$_SESSION['uid'];
        $chek=mysqli_query($con,"SELECT * FROM user where email='$email' or mobile='$mob'");
        if(mysqli_num_rows($chek)){
            $_SESSION['reg_alert']='EMAIL OR MOBILE ALREADY EXSITS !';
            echo "<script>window.location.href='register.php';</script>";
        }
        else{

            $insert=mysqli_query($con,"UPDATE user set fname='$fname',lname='$lname',email='$email',pwd='$pwd1',mobile='$mob',date='$date',status='$sta' where user_id='$usrid'");
            if($insert){
                echo "<script>window.location.href='login.php';</script>";
            }else{
                $_SESSION['reg_alert']='SORRY!';
                echo "<script>window.location.href='register.php';</script>";
            }
        }
    }
    ?>

<?php
if(isset($_SESSION['uid'])){
    $user_id=$_SESSION['uid'];
}else{
    $user_id='';
}
?>