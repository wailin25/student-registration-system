<?php
session_start();
 include ('includes/db.php');
 if(isset($_POST['login_now_btn'])){

     if($email === 'admin@gmail.com' && $password === 'admin@123') {
        $_SESSION['authenticated'] = true;
        $_SESSION['user_role'] = 'admin';
        $_SESSION['status'] = "Welcome Admin!";
        header("Location: admin_dashboard.php");
        exit(0);
    }
    if(!empty(trim($_POST['email'])) && !empty(trim($_POST['password']))){

        $email=mysqli_real_escape_string($mysqli,$_POST['email']);

        $password=mysqli_real_escape_string($mysqli,$_POST['password']);

        $login_query="SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";

        $login_query_run=mysqli_query($mysqli,$login_query);

        if(mysqli_num_rows($login_query_run)>0){

            $row=mysqli_fetch_array($login_query_run);
            
             if($row['verify_status'] =="1"){
                   $_SESSION['authenticated']=TRUE;
                    $_SESSION['user_id'] = $row['user_id'];
                     $_SESSION['user_role'] = $row['role'];
                   $_SESSION['auth_user']=[
                    'username'=>$row['name'],
                    'email'=>$row['email'],
                    'phone'=>$row['phone'],
                   ];
                    $_SESSION['status']="You are Logged in Successfully.";
                     if ($row['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: student_dashboard.php");
                }
                exit(0);
             }
             else
             {
                 $_SESSION['status']="Please Verify your Email Address to Login.";

                header("Location:login.php");
                exit(0);

             }
        }else{
             $_SESSION['status']="Invalid Email or password";

            header("Location:login.php");
            exit(0);
        }
    }
    else{
         $_SESSION['status']="All fields are mendatory";

        header("Location:login.php");
        exit(0);
    }
 }
 
?>