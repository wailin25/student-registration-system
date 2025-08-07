<?php
session_start();
require 'includes/db.php';
$page_title = "Registration Successful";

// Check if registration was successful
if (!isset($_SESSION['payment_success'])) {
    header("Location: payment.php");
    exit();
}

// Get student information
$student_id = $_SESSION['serial_no'] ?? '';
$student_name = $_SESSION['student_name_en'] ?? 'Student';
$student_class = $_SESSION['current_class'] ?? '';

// Get payment details
$payment_details = $_SESSION['payment_data'] ?? [
    'pay_date' => date('Y-m-d'),
    'amount' => 0,
    'pay_method' => 'N/A',
    'academic_year' => date('Y') . '-' . (date('Y') + 1)
];

// Clear success flag
unset($_SESSION['payment_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --primary-dark: #3a0ca3;
            --secondary: #f8b739;
            --success: #2ecc71;
            --success-dark: #27ae60;
            --white: #ffffff;
            --light-gray: #f5f5f5;
            --dark-gray: #333333;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e2e6f0 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .success-container {
            max-width: 800px;
            width: 100%;
            padding: 20px;
        }
        
        .success-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
            text-align: center;
            position: relative;
            z-index: 10;
        }
        
        .success-header {
            background: linear-gradient(120deg, var(--success), var(--success-dark));
            color: white;
            padding: 40px 20px;
            position: relative;
            overflow: hidden;
        }
        
        .success-header::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .success-header::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .check-icon {
            font-size: 80px;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
            animation: bounce 1s ease;
        }
        
        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
        }
        
        .success-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }
        
        .success-body {
            padding: 40px;
        }
        
        .success-info {
            max-width: 600px;
            margin: 0 auto 30px;
            font-size: 1.1rem;
            color: #555;
            line-height: 1.6;
        }
        
        .receipt-card {
            background: var(--light-gray);
            border-radius: 15px;
            padding: 30px;
            margin: 30px auto;
            max-width: 500px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            text-align: left;
        }
        
        .receipt-title {
            text-align: center;
            margin-bottom: 25px;
            color: var(--primary-dark);
            font-weight: 600;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .receipt-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px dashed #ddd;
        }
        
        .receipt-label {
            font-weight: 500;
            color: #666;
        }
        
        .receipt-value {
            font-weight: 600;
            color: var(--dark-gray);
        }
        
        .receipt-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--success-dark);
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--success);
            display: flex;
            justify-content: space-between;
        }
        
        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-dashboard {
            background: linear-gradient(120deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .btn-dashboard:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(67, 97, 238, 0.4);
            color: white;
        }
        
        .btn-print {
            background: linear-gradient(120deg, #6c757d, #495057);
            color: white;
            padding: 12px 30px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(108, 117, 125, 0.3);
        }
        
        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(108, 117, 125, 0.4);
            color: white;
        }
        
        .university-info {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 0.9rem;
        }
        
        .contact-info {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 15px;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        /* Animations */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {transform: translateY(0);}
            40% {transform: translateY(-30px);}
            60% {transform: translateY(-15px);}
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .success-card {
            animation: fadeIn 0.8s ease;
        }
        
        /* Responsive design */
        @media (max-width: 768px) {
            .success-header {
                padding: 30px 15px;
            }
            
            .success-title {
                font-size: 2rem;
            }
            
            .success-body {
                padding: 25px;
            }
            
            .receipt-card {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
            
            .btn-dashboard, .btn-print {
                width: 100%;
                max-width: 300px;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-card">
            <div class="success-header">
                <div class="check-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h1 class="success-title">Registration Successful!</h1>
                <p class="success-subtitle">Thank you for completing your registration</p>
            </div>
            
            <div class="success-body">
                <p class="success-info">
                    Your registration and payment have been successfully processed. 
                    Below is your payment receipt. You can save or print this page for your records.
                </p>
                
                <div class="receipt-card">
                    <h3 class="receipt-title">
                        <i class="fas fa-receipt"></i> Payment Receipt
                    </h3>
                    
                    <div class="receipt-item">
                        <span class="receipt-label">Student ID:</span>
                        <span class="receipt-value"><?= $student_id ?></span>
                    </div>
                    
                    <div class="receipt-item">
                        <span class="receipt-label">Student Name:</span>
                        <span class="receipt-value"><?= $student_name ?></span>
                    </div>
                    
                    <div class="receipt-item">
                        <span class="receipt-label">Class:</span>
                        <span class="receipt-value"><?= $student_class ?></span>
                    </div>
                    
                    <div class="receipt-item">
                        <span class="receipt-label">Academic Year:</span>
                        <span class="receipt-value"><?= $payment_details['academic_year'] ?></span>
                    </div>
                    
                    <div class="receipt-item">
                        <span class="receipt-label">Payment Date:</span>
                        <span class="receipt-value"><?= date('F j, Y', strtotime($payment_details['pay_date'])) ?></span>
                    </div>
                    
                    <div class="receipt-item">
                        <span class="receipt-label">Payment Method:</span>
                        <span class="receipt-value"><?= $payment_details['pay_method'] ?></span>
                    </div>
                    
                    <div class="receipt-total">
                        <span class="receipt-label">Total Amount:</span>
                        <span class="receipt-value"><?= number_format($payment_details['amount']) ?> MMK</span>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="student_dashboard.php" class="btn-dashboard">
                        <i class="fas fa-tachometer-alt"></i> Go to Dashboard
                    </a>
                    <a href="#" class="btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i> Print Receipt
                    </a>
                </div>
                
                <div class="university-info">
                    <p>Computer University (Magway)</p>
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <span>+95 9 123 456 789</span>
                        </div>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <span>info@ucsmgy.edu.mm</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add animation to receipt card
        document.addEventListener('DOMContentLoaded', function() {
            const receiptCard = document.querySelector('.receipt-card');
            setTimeout(() => {
                receiptCard.style.transform = 'translateY(0)';
                receiptCard.style.opacity = '1';
            }, 300);
        });
    </script>
</body>
</html>