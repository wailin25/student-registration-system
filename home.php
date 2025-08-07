<!-- <?php
// session_start();

// // Database connection
// $host = '127.0.0.1';
// $dbname = 'kowai';
// $username = 'root';
// $password = '';

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     die("Connection failed: " . $e->getMessage());
// }

// // Process login form
// if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
//     $serial_no = $_POST['serial_no'];
//     $entry_year = $_POST['entry_year'];
    
//     // Query to check if student exists
//     $stmt = $pdo->prepare("SELECT * FROM students WHERE serial_no = ? AND entry_year = ?");
//     $stmt->execute([$serial_no, $entry_year]);
//     $student = $stmt->fetch(PDO::FETCH_ASSOC);
    
//     if ($student) {
//         // Set session variables
//         $_SESSION['serial_no'] = $student['serial_no'];
//         $_SESSION['student_name_mm'] = $student['student_name_mm'];
//         $_SESSION['student_name_en'] = $student['student_name_en'];
//         $_SESSION['entry_year'] = $student['entry_year'];
//         $_SESSION['loggedin'] = true;
        
//         // Redirect to dashboard
//       // 
//         exit();
//     } else {
//         // Student not found
//         $error = "Invalid serial number or entry year";
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login - UCSMGY</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@400;600&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #3498db;
            --primary-dark: #2980b9;
            --secondary: #f1c40f;
            --dark: #2c3e50;
            --light: #ecf0f1;
            --danger: #e74c3c;
            --success: #2ecc71;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', 'Noto Sans Myanmar', sans-serif;
            background:url('image/CU4.jpg');
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: var(--dark);
        }
        
        .login-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-header {
            background: var(--primary);
            color: white;
            padding: 25px;
            text-align: center;
            position: relative;
        }
        
        .logo {
            width: 80px;
            margin-bottom: 10px;
        }
        
        .login-header h1 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .login-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .login-body {
            padding: 30px;
        }
        
        .alert {
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }
        
        .alert-danger {
            background-color: #fdecea;
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark);
            font-size: 0.95rem;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        .form-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        
        .input-prefix {
            display: flex;
            align-items: center;
        }
        
        .prefix-text {
            background: #f1f1f1;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-right: none;
            border-radius: 6px 0 0 6px;
            font-size: 1rem;
        }
        
        .prefix-input {
            border-radius: 0 6px 6px 0 !important;
            flex: 1;
        }
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
        }
        
        .btn i {
            margin-right: 8px;
        }
        
        .btn-primary {
            background: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background: #f1f1f1;
            color: var(--dark);
        }
        
        .btn-secondary:hover {
            background: #e1e1e1;
        }
        
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
        }
        
        .footer-text {
            text-align: center;
            margin-top: 20px;
            font-size: 0.85rem;
            color: #777;
        }
        
        @media (max-width: 480px) {
            .login-container {
                border-radius: 0;
            }
            
            .login-header {
                padding: 20px;
            }
            
            .login-body {
                padding: 20px;
            }
            
            .button-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <img src="image/ucsmgy.png" alt="UCSMGY Logo" class="logo">
            <h1>Student Login</h1>
            <p>ကျေးဇူးပြု၍ ခုံအမှတ်နှင့် ခုနှစ်ကိုရိုက်ပါ</p>
        </div>
        
        <div class="login-body">
            <?php ///if (isset($error)): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> <?php //echo htmlspecialchars($error); ?>
                </div>
            <?php //endif; ?>
            
            <form method="post">
                <div class="form-group">
                    <label for="serial_no" class="form-label">ခုံအမှတ် (Serial No)</label>
                    <div class="input-prefix">
                        <span class="prefix-text">UCSMG-</span>
                        <input type="text" id="serial_no" name="serial_no" class="form-input prefix-input" required placeholder="Enter your serial number">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="entry_year" class="form-label">ခုနှစ် (Entry Year)</label>
                    <input type="text" id="entry_year" name="entry_year" class="form-input" required placeholder="Enter your entry year">
                </div>
                
                <div class="button-group">
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                    <button type="submit" name="submit" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i>SEARCH
                    </button>
                </div>
            </form>
            
            <p class="footer-text">Need help? Contact administration</p>
        </div>
    </div>
</body>
</html> -->