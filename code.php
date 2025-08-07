<?php
session_start();
include 'includes/db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

function sendemail_verify($name, $email, $verify_token){
    $mail = new PHPMailer(true);
    try {
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
        $mail->Subject = "Email Verification from UCSMGY";

        $email_template = "
        <h2>You have Registered with UCSMGY</h2>
        <h5>Verify your email address to Login with the below link</h5>
        <br><br>
        <a href='http://localhost/UCSMGYSRMS/verify-email.php?token=$verify_token'>Click to Verify</a>
        ";

        $mail->Body = $email_template;
        $mail->send();
    } catch (Exception $e) {
        // Email sending failed - optionally handle errors here
    }
}

if (isset($_POST['register_btn'])) {
    $name = mysqli_real_escape_string($mysqli, $_POST['name']);
    $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $password = $_POST['password'];

    $verify_token = md5(rand());
     // sendemail_verify($name, $email, $verify_token);
     // echo "sent or not?";

    $check_email_query = "SELECT email FROM users WHERE email = '$email' LIMIT 1";
    $check_email_query_run = mysqli_query($mysqli, $check_email_query);

    if (mysqli_num_rows($check_email_query_run) > 0) {
        $_SESSION['status'] = "Email ID already exists.";
        header("Location: register.php");
        exit;
    }
    else{
    $query = "INSERT INTO users (name, phone, email, password, verify_token) VALUES ('$name','$phone' ,'$email' ,'$password' ,'$verify_token')";

    $query_run = mysqli_query($mysqli,$query);

        if ($query_run) {
            sendemail_verify($name, $email, $verify_token);
            $_SESSION['status'] = "Registration successful! Please check your email to verify.";
            header("Location: register.php");
            exit;
        
        } else {
            $_SESSION['status'] = "Registration failed.";
            header("Location: register.php");
            exit;
        }
    }
}

?>
