<?php
session_start(); // Session ကို စတင်ပါ။ မက်ဆေ့ချ်များ (ဥပမာ- အမှားမက်ဆေ့ချ်) သိမ်းဆည်းရန်။
require 'includes/db.php'; // Database ချိတ်ဆက်မှုအတွက် db.php ဖိုင်ကို ထည့်သွင်းပါ။

$student_data = null; // ကျောင်းသားအချက်အလက်များကို သိမ်းဆည်းရန် variable ကို ကြိုတင်သတ်မှတ်ပါ။
$error_message = ''; // အမှားမက်ဆေ့ချ်များကို သိမ်းဆည်းရန်။

// serial_no ကို GET method မှ ရယူပါ။
if (isset($_GET['serial_no']) && !empty($_GET['serial_no'])) {
    $serialNo = $_GET['serial_no']; // ရယူထားသော serial_no ကို variable ထဲ ထည့်ပါ။

    // 'transfered_students' table မှ ကျောင်းသားအချက်အလက်များကို ရယူပါ။
    $select_query = "SELECT * FROM transfered_students WHERE serial_no = ?";
    $stmt_select = $mysqli->prepare($select_query); // Prepared statement ပြင်ဆင်ပါ။

    if ($stmt_select === false) {
        $error_message = "ကျောင်းသားအချက်အလက် ရယူရန် query ပြင်ဆင်ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $mysqli->error;
    } else {
        $stmt_select->bind_param("s", $serialNo); // serial_no ကို string (s) အဖြစ် bind လုပ်ပါ။
        $stmt_select->execute(); // Query ကို လုပ်ဆောင်ပါ။
        $result_select = $stmt_select->get_result(); // ရလဒ်များကို ရယူပါ။
        $student_data = $result_select->fetch_assoc(); // အချက်အလက်များကို associative array အဖြစ် ရယူပါ။
        $stmt_select->close(); // Statement ကို ပိတ်ပါ။

        // ကျောင်းသားကို ရှာမတွေ့ပါက အမှားမက်ဆေ့ချ် သတ်မှတ်ပါ။
        if (!$student_data) {
            $error_message = "Serial No. '$serialNo' ဖြင့် transfer လုပ်ပြီးသား ကျောင်းသားကို ရှာမတွေ့ပါ။";
        }
    }
} else {
    // serial_no မပါဝင်ပါက အမှားမက်ဆေ့ချ် သတ်မှတ်ပါ။
    $error_message = "ကျောင်းသား၏ Serial No. မတွေ့ပါ။";
}

// Database connection ကို ပိတ်ပါ။
if ($mysqli) {
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfer လုပ်ပြီးသား ကျောင်းသားအချက်အလက် ကြည့်ရန်</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 700px;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            font-weight: bold;
        }
        .form-label {
            font-weight: 500;
        }
        .form-control-plaintext {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            border-bottom: 1px solid #dee2e6; /* အချက်အလက်များကို ပိုမိုရှင်းလင်းစေရန် */
            padding-bottom: 0.25rem;
        }
    </style>
</head>
<body>   <?php require 'includes/admin_header.php'; ?>
<?php require 'includes/navbar.php'; ?>

<div id="sidebar" class="bg-dark text-white">
    <?php include 'includes/sidebar.php'; ?>
</div>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header">
                Transfer လုပ်ပြီးသား ကျောင်းသားအချက်အလက်
            </div>
            <div class="card-body">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                    <a href="manage_transfer_students.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Transfer လုပ်ပြီးသား ကျောင်းသားများစာရင်းသို့ ပြန်သွားမည်
                    </a>
                <?php elseif ($student_data): ?>
                    <h5 class="mb-4">ကျောင်းသားအသေးစိတ်အချက်အလက်များ</h5>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">မြန်မာအမည်:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['student_name_mm'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">အင်္ဂလိပ်အမည်:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['student_name_en'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Serial Code:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['serial_code'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Serial No:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['serial_no'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Class:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['class'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Specialization:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['specialization'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">NRC:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['nrc'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Phone:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['phone'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-4 col-form-label">Remarks:</label>
                        <div class="col-sm-8">
                            <p class="form-control-plaintext"><?= htmlspecialchars($student_data['remarks'] ?? 'N/A') ?></p>
                        </div>
                    </div>
                    <!-- လိုအပ်ပါက အခြား fields များကို ဤနေရာတွင် ထပ်ထည့်နိုင်ပါသည်။ -->

                    <div class="d-flex justify-content-end mt-4">
                        <a href="manage_transfer_students.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Transfer လုပ်ပြီးသား ကျောင်းသားများစာရင်းသို့ ပြန်သွားမည်
                        </a>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        ပြသရန် ကျောင်းသားအချက်အလက် မတွေ့ပါ။
                    </div>
                    <a href="manage_transfer_students.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Transfer လုပ်ပြီးသား ကျောင်းသားများစာရင်းသို့ ပြန်သွားမည်
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
