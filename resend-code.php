<?php 
session_start();
include('db.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function resend_email_verify($name,$email,$verify_token){
	$mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'aungwai592@gmail.com';
    $mail->Password   = 'jftrpyfupjjdtwul'; // App password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('aungwai592@gmail.com',$name);
    $mail->addAddress($email);
    $mail->isHTML(true);
    $mail->Subject = "Resend- Email Verification from UCSMGY";

    $email_template = "
    <h2>You have Registered with UCSMGY</h2>
    <h5>Verify your email address to Login with the below link</h5>
    <br><br>
    <a href='http://localhost/UCSMGYSRMS/verify-email.php?token=$verify_token'>Click Resend</a>
    ";
    
    $mail->Body = $email_template;
    $mail->send();
}
if(isset($_POST['resend_email_verify_btn'])){
	if(!empty(trim($_POST['email'])))
	{
		$email=mysqli_real_escape_string($conn,$_POST['email']);
		$check_email_query="SELECT * FROM users WHERE email='$email' LIMIT 1";
		$check_email_query_run=mysqli_query($conn,$check_email_query);
		if(mysqli_num_rows($check_email_query_run) > 0)
		{
			$row =mysqli_fetch_array($check_email_query_run);
			if($row['verify_status'] =="0"){
				$name=$row['name'];
				$email=$row['email'];
				$verify_query=$row['verify_token'];
				resend_email_verify($name,$email,$verify_token);
				$_SESSION['status']="Verification Email Link has been sent to your email address.!";
				header("Location:login.php");
				exit(0);
			}else{
				$_SESSION['status']="Email Already Exists.Please Login";
				header("Location:resend-email-verification.php");
				exit(0);
			}
		}else
		{
			$_SESSION['status']="Email is not registered. Please Reister Now.!";
			header("Location:register.php");
			exit(0);
		}
	}
	else
	{
		$_SESSION['status']="Please enter the email field";
		header("Location:resend-email-verification.php");
		exit(0);
	}
}
?>