<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === 'admin@gmail.com' && $password === 'admin@123') {
        $_SESSION['authenticated'] = true;
        $_SESSION['user_role'] = 'admin';
        $_SESSION['status'] = "Welcome Admin!";
        header("Location: admin_dashboard.php");
        exit(0);
    }

    if (!empty($email) && !empty($password)) {
        // Normally: Check from database
        // This is simulation only
        $_SESSION['authenticated'] = true;
        $_SESSION['user_role'] = 'student';
        $_SESSION['status'] = "Welcome Student!";
        header("Location: student_dashboard.php");
        exit(0);
    } else {
        $_SESSION['status'] = "Invalid email or password";
        header("Location: login.php");
        exit(0);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UCSMGY Student Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: url('uploads/image/images.jpg') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: #333;
        }
        
        .container {
            width: 50%;
            max-width: 650px;
        }
        
        .ucs-header {
            text-align: center;
            margin-bottom: 30px;
            color: white;
        }
        
        .ucs-logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0c2340, #1e3a5f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 3px solid #ffcc00;
            box-shadow: 0 0 20px rgba(255, 204, 0, 0.3);
        }
        
        .ucs-logo i {
            font-size: 40px;
            color: #ffcc00;
        }
        
        .ucs-header h1 {
            font-size: 28px;
            margin-bottom: 5px;
            color: #ffcc00;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }
        
        .ucs-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 100px 300px rgba(0, 0, 0, 0.4);
            border: none;
        }
        
        .card-header {
            background: #0c2340;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 15px 15px 0 0 !important;
            position: relative;
        }
        
        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 24px;
        }
        
        .card-body {
            padding: 30px;
            background: white;
        }
        
        .form-group {
            margin-bottom: 25px;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        
        .form-control {
            padding: 14px 15px 14px 45px;
            border: 2px solid #e1e1e1;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #0c2340;
            outline: none;
            box-shadow: 0 0 0 3px rgba(12, 35, 64, 0.2);
        }
        
        .input-icon {
            position: absolute;
            left: 15px;
            top: 43px;
            color: #777;
            font-size: 18px;
        }
        
        .btn-primary {
            background: linear-gradient(to right, #0c2340, #1e3a5f);
            color: white;
            border: none;
            padding: 14px;
            width: 100%;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
            box-shadow: 0 5px 15px rgba(12, 35, 64, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(12, 35, 64, 0.6);
        }
        
        .card-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 15px 15px;
            border-top: 1px solid #eee;
        }
        
        .card-footer p {
            margin: 0;
            color: #666;
        }
        
        .card-footer a {
            color: #0c2340;
            text-decoration: none;
            font-weight: 600;
        }
        
        .card-footer a:hover {
            color: #ffcc00;
            text-decoration: underline;
        }
        
        .alert {
            border-radius: 10px;
        }
        
        .password-info {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        
        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 14px;
        }
        
        .login-links a {
            color: #0c2340;
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-links a:hover {
            color: #ffcc00;
            text-decoration: underline;
        }
        
        .resend-verification {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        @media (max-width: 576px) {
            .card-body {
                padding: 20px;
            }
            
            .ucs-header h1 {
                font-size: 24px;
            }
            
            .container {
                width: 95%;
            }
            
            .login-links {
                flex-direction: column;
                gap: 10px;
                align-items: center;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <img src="uploads/image/ucsmgy.png" style="width: 100px;">
            <p>University of Computer Studies (Magway)</p>
            <h3> LOGIN INTO YOUR ACCOUNT</h3>
        </div>

        <div class="card-body">
            <?php
            if (isset($_SESSION['status'])) {
                echo "<div class='alert alert-success'><h5>" . $_SESSION['status'] . "</h5></div>";
                unset($_SESSION['status']);
            }
            ?>
            
            <form action="logincode.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="student@gmail.com">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Enter your password">
                    <i class="fas fa-eye-slash toggle-password" style="position:absolute; top:49px; right:15px; cursor:pointer;"></i>
                </div>

                <button type="submit" name="login_now_btn" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt me-2"></i> Login Now
                </button>
                <div class="" align="center">
                    <a href="index.php" class="btn btn-outline-success mt-3">
                        <i class="fa fa-house"></i>Back to Home
                    </a>
                </div>
                <div class="login-links">
                    <a href="password-reset.php">Forgot your Password?</a>
                    <a href="register.php">Create New Account</a>
                </div>
                
                <div class="resend-verification">
                    <h5>Did not receive your Verification Email?
                        <a href="resend-email-verification.php">Resend</a>
                    </h5>
                </div>
            </form>
        </div>

        <!-- <div class="card-footer">
            <p>© 2025 UCS Magway. All rights reserved.</p>
        </div> -->
    </div>
</div>

<script>
    // Password visibility toggle
    document.querySelector('.toggle-password').addEventListener('click', function () {
        const pwd = document.getElementById('password');
        if (pwd.type === "password") {
            pwd.type = "text";
            this.classList.remove('fa-eye-slash');
            this.classList.add('fa-eye');
        } else {
            pwd.type = "password";
            this.classList.remove('fa-eye');
            this.classList.add('fa-eye-slash');
        }
    });

    // Smooth animation for form elements
    document.addEventListener('DOMContentLoaded', function() {
        const formElements = document.querySelectorAll('.form-group, .btn-primary, .login-links');
        formElements.forEach((el, index) => {
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, 100 * index);
        });
    });
</script>
</body>
</html>