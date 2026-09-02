<?php 
session_start();
// error_reporting(0);
?>
<?php
require('../config.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


require '../vendor/autoload.php';

function sendmail($email)
{
$mail = new PHPMailer(true); 

    $mail->isSMTP();                                     
    $mail->Host       = 'smtp.gmail.com';                     
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = 'helpatukinfo@gmail.com';                     
    $mail->Password   = 'cdkb yvhj cpsg bmga';                            
    $mail->SMTPSecure = "TLS";           
    $mail->Port       = 587;   
    $mail->Timeout=10;          
    
        $main="";
   
    $mail->setFrom('helpatukinfo@gmail.com','Karuda Computers');
    $mail->addAddress($email); 
    
    //Content
    $mail->isHTML(true);                                  
    $mail->Subject = 'Karuda Computers';
    $content="<div style='text-align:center;height:400px; box-shadow: 5px 5px 5px rgba(176, 176, 176, 0.5);'>
    <a href='http://avherbals.in/index.php'></a>
     <h3 style='font-weight:bold;'>Please take a second to make sure we've got your Email.!</h3>
     <h6 style='font-size:15px;color:black;'>Please Click & Verify In Your Email</h6>
     <a href='http://avherbals.in/forgot_2.php?gee=$email' style='background:red;color:white;padding:20px 30px;border-radius:20px;text-decartion:none;text-decoration: none;'><b>Confirom your Email.</b></a>
     </div>";
    $mail->Body=$content ;
    $mail->send();   
}

$_SESSION['last']=time();

if (isset($_POST['forgot_pass'])) {
    $email = trim($_POST['email']);

    $query = mysqli_query($con, "SELECT * FROM user WHERE email='$email'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_array($query);
        if ($row['status'] != '1') {
                echo 'inactive';
        } else {
            $email = $row['email'];
            sendmail($email);
            echo '1';
        }
    }else{
        echo "inv_email";
    }
}

// 
if(isset($_POST['rest_pass'])){
    $pwd=trim($_POST['pwd']);
    $pwd1=trim($_POST['pwd1']);
    $email=trim($_POST['email']);
    if($pwd !=$pwd1){
        echo'not_match';
    }else{
        $enc_pass = md5($pwd1);
        $up=mysqli_query($con,"UPDATE user set pwd='$enc_pass' where email='$email'");
        if($up){
            echo'success';
        }else{
           echo 'not';
        }
    }
}

?>