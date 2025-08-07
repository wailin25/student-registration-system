<?php
session_start();
require 'includes/db.php';

// Security headers
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Strict-Transport-Security: max-age=63072000; includeSubDomains; preload");

// Configuration
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 300); // 5 minutes in seconds

// Initialize login attempts if not set
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt_time'] = time();
}

// Check if account is locked
if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
    if (time() - $_SESSION['last_attempt_time'] < LOGIN_LOCKOUT_TIME) {
        $_SESSION['error'] = 'Account locked. Try again in ' . ceil((LOGIN_LOCKOUT_TIME - (time() - $_SESSION['last_attempt_time'])) / 60) . ' minutes';
        header("Location: login.php");
        exit();
    } else {
        // Lockout period has expired
        $_SESSION['login_attempts'] = 0;
    }
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    // Basic validation
    if (empty($email) || empty($password)) {
        incrementLoginAttempts();
        $_SESSION['error'] = 'Email and password are required';
        header("Location: login.php");
        exit();
    }

    try {
        // Database query for users
        $stmt = $mysqli->prepare("SELECT user_id, name, email, password, role, is_active FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Check if account is active
            if (!$user['is_active']) {
                $_SESSION['error'] = 'Account is inactive. Please contact administrator';
                header("Location: login.php");
                exit();
            }

            // Verify password
            if (password_verify($password, $user['password'])) {
                handleSuccessfulLogin($user['user_id'], $user['name'], $user['email'], $user['role']);
                
                $redirect = match($user['role']) {
                    'student' => 'student_dashboard.php',
                    'staff' => 'staff_dashboard.php',
                    'admin' => 'admin_dashboard.php',
                    default => 'index.php'
                };
                header("Location: $redirect");
                exit();
            }
        }
        
        // If we reach here, login failed
        incrementLoginAttempts();
        $_SESSION['error'] = 'Invalid email or password';
        header("Location: login.php");
        exit();
        
    } catch (Exception $e) {
        error_log("Database error during login: " . $e->getMessage());
        $_SESSION['error'] = 'System error. Please try again later';
        header("Location: login.php");
        exit();
    }
}

/**
 * Increments login attempts counter
 */
function incrementLoginAttempts() {
    $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
    $_SESSION['last_attempt_time'] = time();
}

/**
 * Handles successful login (session setup)
 */
function handleSuccessfulLogin($user_id, $name, $email, $role) {
    // Reset login attempts
    $_SESSION['login_attempts'] = 0;
    
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);

    // Set session variables
    $_SESSION['user_id'] = $user_id;
    $_SESSION['user_name'] = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
    $_SESSION['user_email'] = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
    $_SESSION['user_role'] = $role;
    $_SESSION['logged_in'] = true;
    $_SESSION['last_activity'] = time();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Login System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #1a3a6c;
            --primary-light: #2a5a9c;
            --primary-dark: #0d2342;
            --secondary: #f8b739;
            --accent: #34a853;
            --error: #e74c3c;
            --success: #2ecc71;
            --white: #ffffff;
            --light-gray: #f5f7fa;
            --medium-gray: #e0e6ed;
            --dark-gray: #333333;
            --text: #2d3748;
            --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #1a3a6c 0%, #2a5a9c 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
            position: relative;
            overflow-x: hidden;
        }
        
        .background-shapes {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }
        
        .shape {
            position: absolute;
            border-radius: 50%;
            opacity: 0.1;
        }
        
        .shape-1 {
            width: 300px;
            height: 300px;
            background: var(--secondary);
            top: -150px;
            right: -150px;
        }
        
        .shape-2 {
            width: 200px;
            height: 200px;
            background: var(--accent);
            bottom: -100px;
            left: -100px;
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            margin: 0 auto;
            z-index: 10;
            animation: fadeIn 0.8s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
        }
        
        .logo-container {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        
        .logo {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            font-size: 1.8rem;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: var(--transition);
        }
        
        .logo:hover {
            transform: rotate(10deg);
        }
        
        .logo i {
            font-size: 2.5rem;
        }
        
        .login-box h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--dark-gray);
            position: relative;
            font-weight: 600;
        }
        
        .login-box h2::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: var(--secondary);
            border-radius: 3px;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }
        
        .input-with-icon {
            position: relative;
        }
        
        .input-with-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        .form-group input {
            width: 100%;
            padding: 0.8rem 1rem 0.8rem 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-group.password-group .input-with-icon input {
            padding-right: 45px; /* Add space for the eye icon */
        }
        
        .form-group input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 58, 108, 0.2);
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            background: none;
            border: none;
            padding: 0;
            font-size: 1.1rem;
            transition: color 0.2s;
            z-index: 2; /* Ensure it stays above the input */
        }
        
        .password-toggle:hover {
            color: var(--primary);
        }
        
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            font-size: 0.95rem;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
        }
        
        .remember-me input {
            margin-right: 8px;
            accent-color: var(--primary);
        }
        
        .forgot-password {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }
        
        .forgot-password::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary);
            transition: var(--transition);
        }
        
        .forgot-password:hover::after {
            width: 100%;
        }
        
        .submit-btn {
            width: 100%;
            padding: 0.9rem;
            background: linear-gradient(to right, var(--primary), var(--primary-light));
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            box-shadow: 0 4px 10px rgba(26, 58, 108, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: 0.5s;
        }
        
        .submit-btn:hover::before {
            left: 100%;
        }
        
        .submit-btn:hover {
            background: linear-gradient(to right, var(--primary-dark), var(--primary));
            box-shadow: 0 6px 15px rgba(26, 58, 108, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(2px);
        }
        
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.95rem;
            color: var(--dark-gray);
        }
        
        .register-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            position: relative;
        }
        
        .register-link a::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: var(--primary);
            transition: var(--transition);
        }
        
        .register-link a:hover::after {
            width: 100%;
        }
        
        .error {
            color: var(--error);
            background-color: rgba(231, 76, 60, 0.1);
            padding: 0.8rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            animation: shake 0.5s;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .university-tag {
            display: block;
            color: var(--primary);
            padding: 3px 0;
            font-size: 0.9rem;
            text-align: center;
            font-weight: 500;
            margin-top: 5px;
        }
        
        .security-info {
            display: flex;
            justify-content: space-around;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--medium-gray);
        }
        
        .security-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            color: var(--dark-gray);
            font-size: 0.85rem;
        }
        
        .security-item i {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: var(--primary);
        }
        
        .footer {
            text-align: center;
            margin-top: 2rem;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        
        .footer a {
            color: white;
            text-decoration: none;
            font-weight: 500;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        .lockout-info {
            background: rgba(231, 76, 60, 0.1);
            padding: 10px;
            border-radius: 8px;
            margin-top: 10px;
            font-size: 0.85rem;
            color: var(--error);
            display: none;
        }
        
        @media (max-width: 768px) {
            .login-box {
                padding: 1.8rem;
            }
            
            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.8rem;
            }
            
            .security-info {
                flex-direction: column;
                gap: 15px;
            }
        }
        
        @media (max-width: 480px) {
            body {
                padding: 1rem;
            }
            
            .login-box {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="background-shapes">
        <div class="shape shape-1"></div>
        <div class="shape shape-2"></div>
    </div>
    
    <div class="login-container">
        <div class="login-box">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2>University Login</h2>
                <div class="university-tag">University of Computer Studies (Magway)</div>
            </div>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> 
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">University Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" 
                               placeholder="Enter your university email" 
                               required
                               value="<?php echo isset($_SESSION['login_email']) ? $_SESSION['login_email'] : ''; unset($_SESSION['login_email']); ?>">
                    </div>
                </div>
                
                <div class="form-group password-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" 
                               placeholder="Enter your password" 
                               required>
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
                
                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-password">Forgot password?</a>
                </div>
                
                <div class="lockout-info" id="lockoutInfo">
                    <i class="fas fa-lock"></i>
                    Account locked due to too many failed attempts. Try again later.
                </div>
                
                <button type="submit" class="submit-btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="security-info">
                <div class="security-item">
                    <i class="fas fa-shield-alt"></i>
                    <span>256-bit Encryption</span>
                </div>
                <div class="security-item">
                    <i class="fas fa-lock"></i>
                    <span>Secure Login</span>
                </div>
                <div class="security-item">
                    <i class="fas fa-user-shield"></i>
                    <span>Privacy Protected</span>
                </div>
            </div>
            
            <div class="register-link">
                Don't have an account? <a href="#">Contact administration</a>
            </div>
        </div>
        
        <div class="footer">
            <p>© 2025 University Management System | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            const password = document.getElementById('password');
            const icon = togglePassword.querySelector('i');
            
            togglePassword.addEventListener('click', function() {
                if (password.type === 'password') {
                    password.type = 'text';
                    icon.classList.replace('fa-eye-slash', 'fa-eye');
                } else {
                    password.type = 'password';
                    icon.classList.replace('fa-eye', 'fa-eye-slash');
                }
            });
            
            // Check for lockout message
            const errorDiv = document.querySelector('.error');
            if (errorDiv && errorDiv.textContent.includes('Account locked')) {
                const lockoutInfo = document.getElementById('lockoutInfo');
                lockoutInfo.style.display = 'block';
            }
            
            // Auto-focus email field
            document.getElementById('email').focus();
        });
    </script>
</body>
</html>