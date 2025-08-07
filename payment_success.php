<?php
session_start();

// Check if payment was successful
if (!isset($_SESSION['payment_success'])) {
    header('Location: payment.php');
    exit();
}

// Clear the success flag
unset($_SESSION['payment_success']);

require 'includes/student_navbar.php';
?>
<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ငွေပေးချေမှုအောင်မြင်ပါသည်</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .success-icon {
            font-size: 5rem;
            color: #28a745;
        }
        .card {
            max-width: 600px;
            margin: 2rem auto;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            font-weight: bold;
            font-size: 1.5rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0069d9);
            border: none;
        }
        .btn-outline-secondary {
            border-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="card">
            <div class="card-header text-center py-4">
                <i class="bi bi-check-circle-fill me-2"></i> ငွေပေးချေမှုအောင်မြင်ပါသည်
            </div>
            <div class="card-body text-center py-5">
                <div class="success-icon mb-4">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h3 class="card-title mb-3">မှတ်ပုံတင်ပြီးမြောက်ပါသည်!</h3>
                <p class="card-text fs-5">
                    သင့်ငွေပေးချေမှုအောင်မြင်စွာ ပြီးမြောက်ပြီး မှတ်ပုံတင်မှုလုပ်ငန်းစဉ် ပြည့်စုံသွားပါပြီ။
                </p>
                
                <div class="mt-5 d-flex justify-content-center gap-3">
                    <a href="student_dashboard.php" class="btn btn-primary btn-lg px-4 py-2">
                        <i class="bi bi-speedometer2 me-2"></i> ကျောင်းသားဒက်ရှ်ဘုတ်
                    </a>
                    <a href="print_receipt.php" class="btn btn-outline-secondary btn-lg px-4 py-2">
                        <i class="bi bi-printer me-2"></i> ပြေစာရိုက်ထုတ်ရန်
                    </a>
                </div>
            </div>
            <div class="card-footer text-center text-muted py-3">
                ကျွန်ုပ်တို့၏ တက္ကသိုလ်တွင် ပညာသင်ယူခွင့်ရသည့်အတွက် ကျေးဇူးတင်ပါသည်။
            </div>
        </div>
    </div>
</body>
</html>