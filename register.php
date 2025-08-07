
<?php
session_start();
include 'includes/db.php';
if (isset($_SESSION['user_id'])) {
   // header('Location: index.php');
   // exit;
}
$page_title = "Account Register Form";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UCSMGY Student Registration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* (Same CSS as in your original, keep it unchanged) */
        /* You can paste your original CSS block here */
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
        
        @media (max-width: 576px) {
            .card-body {
                padding: 20px;
            }
            
            .ucs-header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <img src="uploads/image/ucsmgy.png" style="width: 100px;">
            
            <p>University of Computer Studies(Magway)</p>
            <h3>CREATE  ACCOUNT</h3>
        </div>

        <div class="card-body">
            <?php if (isset($_SESSION['status'])): ?>
                <div class="alert alert-warning"><?= $_SESSION['status']; unset($_SESSION['status']); ?></div>
            <?php endif; ?>
            <form id="registrationForm" method="POST" action="code.php">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="Enter your full name">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <i class="fas fa-phone input-icon"></i>
                    <input type="tel" class="form-control" id="phone" name="phone" required placeholder="09xxxxxxxxx">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" class="form-control" id="email" name="email" required placeholder="student@gmail.com">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" class="form-control" id="password" name="password" required placeholder="Create a strong password">
                    <i class="fas fa-eye-slash toggle-password" style="position:absolute; top:49px; right:15px; cursor:pointer;"></i>
                    <div class="password-info">At least 8 characters with numbers and symbols</div>
                </div>

                <button type="submit"name="register_btn" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Register Account
                </button>
            </form>
        </div>

        <div class="card-footer">
            <p>Already have an account? <a href="login.php">Sign In</a></p>
        </div>
    </div>
</div>

<script>
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
</script>
</body>
</html>
