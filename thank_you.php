<?php
session_start();

// (Optional) Clear session data related to registration/payment if needed
// unset($_SESSION['student_data']);
// unset($_SESSION['exam_results']);
?>
<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>ကျေးဇူးတင်ပါတယ် - ငွေပေးချေမှုအတည်ပြုခြင်း</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        .thank-you-container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .thank-you-container h1 {
            font-weight: bold;
            color: #198754;
        }
        .thank-you-container p {
            font-size: 1.15rem;
            margin-top: 20px;
            color: #333;
        }
        .btn-home {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <h1>ငွေပေးချေမှု အောင်မြင်ပါသည်။</h1>
        <p>ကျေးဇူးတင်ပါသည်၊ သင့်စာရင်းသွင်းမှုကို အောင်မြင်စွာ လက်ခံရရှိပါသည်။</p>
        <p>သင့်အလားအလာများသည် ကျောင်း၏စည်းမျဉ်းစည်းကမ်းများအတိုင်း ဆက်လက်ဆောင်ရွက်ထားမှုဖြစ်ပါသည်။</p>
        <a href="student_dashboard.php" class="btn btn-success btn-lg btn-home">ပင်မစာမျက်နှာသို့ ပြန်သွားရန်</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
