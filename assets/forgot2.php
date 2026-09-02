<?php 
session_start();
?>
<?php

// include('dbconnect.php');

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;


// require('vendor/autoload.php');

// function sendmail($email,$verify)
// {
// $mail = new PHPMailer(true); 

//     $mail->isSMTP();                                     
//     $mail->Host       = 'smtp.gmail.com';                     
//     $mail->SMTPAuth   = true;                                   
//     $mail->Username   = 'helpatukinfo@gmail.com';                     
//     $mail->Password   = 'gpue jaue nmrb iwbb';                            
//     $mail->SMTPSecure = "TLS";           
//     $mail->Port       = 587;   
//     $mail->Timeout=10;                                 

//     //Recipients
//     $mail->setFrom('helpatukinfo@gmail.com','Kottan');
//     $mail->addAddress($email); 
    
//     //Content
//     $mail->isHTML(true);                                  
//     $mail->Subject = 'Kottan';
//     $content="<div style='text-align:center;height:400px;'>
//     <a href='https://kottan.in/index.php'><img src='https://kottan.in/img/kottan2.jpg' width='150px'></a>
//      <h3 style='font-weight:bold;'>Please take a secound to make sure we've got your email.!</h3>
//      <h6 style='font-size:15px;color:black;'>Please Click & Verify In Your Email</h6>
//      <a href='https://kottan.in/forgot3.php?ge=$verify & gee=$email' style='background:red;color:white;;padding:20px 30px;border-radius:20px;text-decartion:none;text-decoration: none;'><b>Confirom your email</b></a>
//      </div>";
//     $mail->Body=$content ;
  
//     $mail->send();   
//      echo "<script>window.location.href='https://kottan.in/forgot.php'; </script>";

// }

$_SESSION['last']=time();
include('dbconnect.php');

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);

    $query = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
   if(mysqli_num_rows($query)){
    $row = mysqli_fetch_array($query);

    if ($row['email'] != '-') {
        $email = $row['email'];
        $verify = $row['pwd'];
        sendmail($email, $verify);
        $_SESSION['sta'] = "We will send a link on your registered email, check from spam and junk also to reset your password.";
        echo "<script>window.location.href='forgot.php';</script>";
    } else {
        $_SESSION['sta'] = "This E-Mail is Not yet Registered with us.";
        echo "<script>window.location.href='forgot.php';</script>";
    }
 }else{
        $_SESSION['sta'] = "This E-Mail is Not yet Registered with us.";
        echo "<script>window.location.href='forgot.php';</script>";
    }
} else {
    $_SESSION['sta'] = "Columns are empty, please enter your email!";
    echo "<script>window.location.href='forgot.php';</script>";
}

?>