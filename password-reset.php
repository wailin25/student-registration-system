<?php
session_start();
$page_title="Password Reset";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - UCS Magway</title>
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
            background: url('https://t3.ftcdn.net/jpg/06/99/54/86/360_F_699548683_PFzyOp06BDjQU3MTCpAaQTMF8jGvvXZE.jpg') no-repeat center center fixed;
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
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .reset-info {
            background-color: #fff8e6;
            border-left: 4px solid #ffcc00;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }
        
        .reset-info h5 {
            color: #0c2340;
            margin-bottom: 10px;
        }
        
        .password-strength {
            height: 6px;
            background: #e0e0e0;
            border-radius: 3px;
            margin-top: 10px;
            overflow: hidden;
        }
        
        .strength-meter {
            height: 100%;
            width: 0;
            background: #ffcc00;
            transition: width 0.3s ease;
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
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <img src="uploads/image/ucsmgy.png" style="width: 100px;">
            <h1>UCS Magway</h1>
            <p>University of Computer Studies, Magway</p>
            <h3>Reset Your Password</h3>
        </div>

        <div class="card-body">
            <?php
            if (isset($_SESSION['status'])) {
                echo "<div class='alert alert-success'><h5>" . $_SESSION['status'] . "</h5></div>";
                unset($_SESSION['status']);
            }
            ?>
            
            <div class="reset-info">
                <h5><i class="fas fa-key me-2"></i> Password Reset Instructions</h5>
                <p>Enter your email address below. We'll send you a link to reset your password. This link will expire in 1 hour for security reasons.</p>
            </div>
            
            <form action="password-reset-code.php" method="POST">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control" id="email" placeholder="Enter your registered email address" required>
                </div>
                
                <button type="submit" name="password_reset_link" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-2"></i> Send Password Reset Link
                </button>
            </form>
        </div>

        <div class="card-footer">
            <p>Remembered your password? <a href="login.php">Login to your account</a></p>
            <p>Need to create an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</div>

<script>
    // Animation for the form elements
    document.addEventListener('DOMContentLoaded', function() {
        const formElements = document.querySelectorAll('.form-group, .btn-primary, .reset-info');
        formElements.forEach((el, index) => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            
            setTimeout(() => {
                el.style.opacity = '1';
                el.style.transform = 'translateY(0)';
            }, 150 * (index + 1));
        });
    });
</script>
</body>
</html>
