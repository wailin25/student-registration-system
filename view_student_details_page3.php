<?php

require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';

// Admin Login စစ်ဆေးခြင်း
// if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
//     header("Location: admin_login.php");
//     exit();
// }

$page_title = "Student Details - Page 3";

// URL ကနေ serial_no ကို ရယူပါ
$serial_no = $_GET['serial_no'] ?? null;

if (!$serial_no) {
    die("Error: No student serial number provided.");
}

// Students table မှ အချက်အလက်များကို ဆွဲထုတ်ပါ (header အတွက် လိုအပ်နိုင်သည်)
$stmt = $mysqli->prepare("SELECT student_name_en FROM students WHERE serial_no = ?");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$studentData = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$studentData) {
    die("Error: Student not found with serial number " . htmlspecialchars($serial_no));
}

// Payment table မှ ငွေပေးချေမှု အချက်အလက်များကို ဆွဲထုတ်ပါ
$stmt = $mysqli->prepare("SELECT * FROM payment WHERE serial_no = ? ORDER BY pay_date DESC");
$stmt->bind_param("s", $serial_no);
$stmt->execute();
$paymentInfo = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
<body>
    <div class="container-fluid" style="margin-top:70px;">
        <div class="row">
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar text-white">
                <?php include 'includes/sidebar.php'; ?>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Student Details (Page 3)</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <a href="view_student_details_page2.php?serial_no=<?= htmlspecialchars($serial_no) ?>" class="btn btn-secondary me-2"><i class="fas fa-arrow-left me-2"></i>Previous Page</a>
                        <a href="manage_registrations.php" class="btn btn-info">Done</a>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">Payment Information</h4>
                    </div>
                    <div class="card-body">
                        <?php if ($paymentInfo): ?>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Payment Date:</strong> <?= htmlspecialchars($paymentInfo['pay_date'] ?? '') ?></li>
                                <li class="list-group-item"><strong>Payment Method:</strong> <?= htmlspecialchars($paymentInfo['pay_method'] ?? '') ?></li>
                                <li class="list-group-item"><strong>Amount:</strong> <?= number_format($paymentInfo['amount'] ?? 0) ?> MMK</li>
                                <li class="list-group-item">
                                    <strong>Payment Slip:</strong>
                                    <?php 
                                        $slip_path = $paymentInfo['pay_slip_path'] ?? null;
                                        if ($slip_path && file_exists($slip_path)):
                                    ?>
                                        <a href="<?= htmlspecialchars($slip_path) ?>" target="_blank" class="btn btn-outline-primary btn-sm ms-3">
                                            View Slip <i class="fas fa-external-link-alt"></i>
                                        </a>
                                        <div class="mt-2 text-center">
                                            <?php if (strtolower(pathinfo($slip_path, PATHINFO_EXTENSION)) !== 'pdf'): ?>
                                                <img src="<?= htmlspecialchars($slip_path) ?>" class="img-fluid rounded border shadow-sm" style="max-height: 400px;">
                                            <?php else: ?>
                                                <p class="text-muted mt-3">This is a PDF file. Click "View Slip" to open.</p>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-danger ms-3">Not found.</span>
                                    <?php endif; ?>
                                </li>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted text-center">No payment information found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>