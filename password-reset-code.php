<?php
session_start();
include 'includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function send_password_reset($get_name, $get_email, $token){
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'aungwai592@gmail.com';
    $mail->Password   = 'jftrpyfupjjdtwul'; // App password
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('aungwai592@gmail.com',$get_name);
    $mail->addAddress($get_email);
    $mail->isHTML(true);
    $mail->Subject = "Reset Password Notification";

    $email_template = "
    <h2>You have Registered with UCSMGY</h2>
    <h5>You are receiving this email because we received a password  reset for your account.</h5>
    <br><br>
    <a href='http://localhost/UCSMGYSRMS/password-change.php?token=$token&email=$get_email'>Click Me</a>
    ";
    
    $mail->Body = $email_template;
    $mail->send();
}

if(isset($_POST['password_reset_link']))
{
	$email=mysqli_real_escape_string($conn,$_POST['email']);
	$token=md5(rand());

	$check_email= "SELECT email FROM users WHERE email='$email' LIMIT 1";
	$check_email_run= mysqli_query($conn,$check_email);

	if(mysqli_num_rows($check_email_run)>0){

		$row=mysqli_fetch_array($check_email_run);
		$get_name= $row['name'];
		$get_email= $row['email'];
		$update_token="UPDATE users SET verify_token='$token' WHERE email='$get_email' LIMIT 1";
		$update_token_run= mysqli_query($conn,$update_token);

		if($update_token_run)
		{
			send_password_reset($get_name,$get_email,$token);
			$_SESSION['status']="We e-mailed you password reset link";
			header("Location:password-reset.php");
			exit(0);
		}
		else
		{
			$_SESSION['status']="Something went wrong. #1";
			header("Location:password-reset.php");
			exit(0);
		}

	}else{
		$_SESSION['status']="No Email Found";
		header("Location:password-reset.php");
		exit(0);
	}
}


if(isset($_POST['password_update'])){
	$email=mysqli_real_escape_string($conn,$_POST['email']);
	$new_password=mysqli_real_escape_string($conn,$_POST['new_password']);
	$confirm_password=mysqli_real_escape_string($conn,$_POST['confirm_password']);
	$token=mysqli_real_escape_string($conn,$_POST['password_token']);

	if(!empty($token))
	{
		if(!empty($email) && !empty($new_password) && !empty($confirm_password))
		{
			$check_token ="SELECT verify_token FROM users WHERE verify_token='$token' LIMIT 1";
			$check_token_run =mysqli_query($conn,$check_token);
			if(mysqli_num_rows($check_token_run)>0){
				if($new_password == $confirm_password)
				{
						$update_password="UPDATE users SET password='$new_password' WHERE verify_token='$token' LIMIT 1";
						$update_password_run=mysqli_query($conn,$update_password);

						if($update_password_run)
							
						{
								$new_token=md5(rand())."ucsmgy";
								$update_to_new_token="UPDATE users SET verify_token='$new_token' WHERE verify_token='$token' LIMIT 1";
								$update_to_new_token_run=mysqli_query($conn,$update_to_new_token);
								$_SESSION['status']="New Password Successfully Update.!";
								header("Location:login.php");
								exit(0);
						}
						else
						{
							$_SESSION['status']="Did not Update Password.Something went wrong.!";
								header("Location:password-change.php?token=$token&email=$email");
								exit(0);
						}
				}
				else
				{
					$_SESSION['status']="Password and Confirm Pasword does not match";
					header("Location:password-change.php?token=$token&email=$email");
					exit(0);
				}
			}
			else
			{
				$_SESSION['status']="Invalid Token";
				header("Location:password-change.php?token=$token&email=$email");
				exit(0);
			}
		}
		else
		{
			$_SESSION['status']="All fields are mendatory";
			header("Location:password-change.php?token=$token&email=$email");
			exit(0);
		}
	}
	else
	{
		$_SESSION['status']="No Token Available";
		header("Location:password-change.php");
		exit(0);
	}
}
?>
