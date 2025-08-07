<?php
session_start(); // Session ကို စတင်ပါ။ မက်ဆေ့ချ်များ သိမ်းဆည်းရန်။
require 'includes/db.php'; // Database ချိတ်ဆက်မှုအတွက် db.php ဖိုင်ကို ထည့်သွင်းပါ။

$serialNo = ''; // serialNo variable ကို ကြိုတင်သတ်မှတ်ပါ။
$student_data = null; // ကျောင်းသားအချက်အလက်များကို သိမ်းဆည်းရန် variable ကို ကြိုတင်သတ်မှတ်ပါ။
$error_message = ''; // အမှားမက်ဆေ့ချ်များကို သိမ်းဆည်းရန်။

// POST method ဖြင့် ဖောင်တင်သွင်းခြင်း ရှိမရှိ စစ်ဆေးပါ။ (အတည်ပြုရန် နှိပ်သောအခါ)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // POST request ဖြစ်ပါက ကျောင်းသားကို ပြန်ပို့သည့် လုပ်ငန်းစဉ်ကို လုပ်ဆောင်ပါ။
    if (isset($_POST['serial_no']) && !empty($_POST['serial_no'])) {
        $serialNo = $_POST['serial_no']; // POST မှ serial_no ကို ရယူပါ။

        // Database transaction ကို စတင်ပါ။ (အချက်အလက်များ မှန်ကန်စေရန်)
        $mysqli->begin_transaction();

        try {
            // 1. 'transfered_students' table မှ ကျောင်းသားအချက်အလက်များကို ရယူပါ။
            $select_query = "SELECT * FROM transfered_students WHERE serial_no = ?";
            $stmt_select = $mysqli->prepare($select_query);
            if ($stmt_select === false) {
                throw new Exception("Transfer လုပ်ပြီးသား ကျောင်းသားအချက်အလက် ရယူရန် query ပြင်ဆင်ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $mysqli->error);
            }
            $stmt_select->bind_param("s", $serialNo);
            $stmt_select->execute();
            $result_select = $stmt_select->get_result();
            $student_data_to_move = $result_select->fetch_assoc();
            $stmt_select->close();

            if (!$student_data_to_move) {
                throw new Exception("Serial No. '$serialNo' ဖြင့် transfer လုပ်ပြီးသား ကျောင်းသားကို ရှာမတွေ့ပါ။");
            }

            // 'id' column ကို ဖယ်ရှားပါ။ (အကယ်၍ 'id' သည် auto-increment primary key ဖြစ်ပြီး students table တွင် အသစ် ထပ်ထည့်လိုပါက)
            // ဤနေရာတွင် 'id' ကို ဖယ်ရှားခြင်းသည် students table တွင် သီးခြား id အသစ်တစ်ခု ထုတ်ပေးရန် ရည်ရွယ်ပါသည်။
            if (isset($student_data_to_move['id'])) {
                unset($student_data_to_move['id']);
            }

            // 2. 'students' table ထဲသို့ ကျောင်းသားအချက်အလက်များကို ထည့်သွင်းပါ။
            // Column နာမည်များကို comma ဖြင့် ခြား၍ string အဖြစ် ပေါင်းစပ်ပါ။
            $insert_columns = implode(", ", array_keys($student_data_to_move));
            // Placeholder များ (ဥပမာ: ?, ?, ?) ကို အချက်အလက်အရေအတွက်အလိုက် ဖန်တီးပါ။
            $placeholders = implode(", ", array_fill(0, count($student_data_to_move), "?"));
            $insert_query = "INSERT INTO students ($insert_columns) VALUES ($placeholders)";

            $stmt_insert = $mysqli->prepare($insert_query);
            if ($stmt_insert === false) {
                throw new Exception("students table သို့ ပြန်ထည့်ရန် query ပြင်ဆင်ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $mysqli->error);
            }

            // Insert အတွက် parameters များကို dynamic နည်းဖြင့် bind လုပ်ပါ။
            $bind_types = str_repeat("s", count($student_data_to_move));
            $bind_params = [];
            foreach ($student_data_to_move as $key => &$value) {
                $bind_params[] = &$value;
            }
            array_unshift($bind_params, $bind_types);

            call_user_func_array([$stmt_insert, 'bind_param'], $bind_params);

            if (!$stmt_insert->execute()) {
                throw new Exception("ကျောင်းသားကို students table သို့ ပြန်ထည့်ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $stmt_insert->error);
            }
            $stmt_insert->close();

            // 3. 'transfered_students' table မှ ကျောင်းသားအချက်အလက်များကို ဖျက်ပါ။
            $delete_query = "DELETE FROM transfered_students WHERE serial_no = ?";
            $stmt_delete = $mysqli->prepare($delete_query);
            if ($stmt_delete === false) {
                throw new Exception("transfered_students table မှ ဖျက်ရန် query ပြင်ဆင်ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $mysqli->error);
            }
            $stmt_delete->bind_param("s", $serialNo);
            if (!$stmt_delete->execute()) {
                throw new Exception("ကျောင်းသားကို transfered_students table မှ ဖျက်ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $stmt_delete->error);
            }
            $stmt_delete->close();

            // လုပ်ငန်းစဉ်အားလုံး အောင်မြင်ပါက transaction ကို commit လုပ်ပါ။
            $mysqli->commit();
            $_SESSION['success'] = "Serial No. '$serialNo' ဖြင့် ကျောင်းသားကို students table သို့ အောင်မြင်စွာ ပြန်ပို့ပြီးပါပြီ။";

        } catch (Exception $e) {
            // အမှားအယွင်း တစ်ခုခု ဖြစ်ပွားပါက transaction ကို rollback လုပ်ပါ။
            $mysqli->rollback();
            $_SESSION['error'] = "ကျောင်းသားကို ပြန်ပို့ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $e->getMessage();
        } finally {
            // Database connection ကို ပိတ်ပါ။
            if ($mysqli) {
                $mysqli->close();
            }
            // manage_transfer_student.php သို့ ပြန်ပို့ပါ။
            header("Location: manage_transfer_students.php");
            exit(); // Script ကို ချက်ချင်းရပ်ပါ။
        }
    } else {
        $_SESSION['error'] = "ကျောင်းသားကို ပြန်ပို့ရန် Serial No. မတွေ့ပါ။";
        header("Location: manage_transfer_students.php");
        exit();
    }
} else {
    // GET request ဖြစ်ပါက ကျောင်းသားအချက်အလက်များကို ပြသရန် ရယူပါ။
    if (isset($_GET['serial_no']) && !empty($_GET['serial_no'])) {
        $serialNo = $_GET['serial_no'];

        $select_query = "SELECT * FROM transfered_students WHERE serial_no = ?";
        $stmt_select = $mysqli->prepare($select_query);
        if ($stmt_select === false) {
            $error_message = "ကျောင်းသားအချက်အလက် ရယူရန် query ပြင်ဆင်ရာတွင် အမှားအယွင်း ဖြစ်ပွားခဲ့သည်: " . $mysqli->error;
        } else {
            $stmt_select->bind_param("s", $serialNo);
            $stmt_select->execute();
            $result_select = $stmt_select->get_result();
            $student_data = $result_select->fetch_assoc();
            $stmt_select->close();

            if (!$student_data) {
                $error_message = "Serial No. '$serialNo' ဖြင့် transfer လုပ်ပြီးသား ကျောင်းသားကို ရှာမတွေ့ပါ။";
            }
        }
    } else {
        $error_message = "ကျောင်းသား၏ Serial No. မတွေ့ပါ။";
    }
    // Database connection ကို ပိတ်ပါ။ (GET request အတွက်)
    if ($mysqli) {
        $mysqli->close();
    }
}
?>

<!DOCTYPE html>
<html lang="my">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ကျောင်းသားကို ပြန်ပို့ရန် အတည်ပြုပါ</title>
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
        }
    </style>
</head>
<body>
 <?php require 'includes/admin_header.php'; ?>
<?php require 'includes/navbar.php'; ?>

<div id="sidebar" class="bg-dark text-white">
    <?php include 'includes/sidebar.php'; ?>
</div>
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header">
                ကျောင်းသားကို ပြန်ပို့ရန် အတည်ပြုပါ
            </div>
            <div class="card-body">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= htmlspecialchars($error_message) ?>
                    </div>
                    <a href="manage_transfer_students.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> နောက်သို့ ပြန်သွားမည်
                    </a>
                <?php elseif ($student_data): ?>
                    <p class="lead">အောက်ပါကျောင်းသားအချက်အလက်များကို students table သို့ ပြန်ပို့ရန် သေချာပါသလား။</p>
                    <form action="move_back_student.php" method="POST">
                        <input type="hidden" name="serial_no" value="<?= htmlspecialchars($student_data['serial_no']) ?>">

                        <div class="mb-3 row">
                            <label for="student_name_mm" class="col-sm-4 col-form-label">မြန်မာအမည်:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['student_name_mm'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="student_name_en" class="col-sm-4 col-form-label">အင်္ဂလိပ်အမည်:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['student_name_en'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="serial_code" class="col-sm-4 col-form-label">Serial Code:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['serial_code'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="serial_no_display" class="col-sm-4 col-form-label">Serial No:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['serial_no'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="class" class="col-sm-4 col-form-label">Class:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['class'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="specialization" class="col-sm-4 col-form-label">Specialization:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['specialization'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="nrc" class="col-sm-4 col-form-label">NRC:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['nrc'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="phone" class="col-sm-4 col-form-label">Phone:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['phone'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="remarks" class="col-sm-4 col-form-label">Remarks:</label>
                            <div class="col-sm-8">
                                <p class="form-control-plaintext"><?= htmlspecialchars($student_data['remarks'] ?? 'N/A') ?></p>
                            </div>
                        </div>
                        <!-- လိုအပ်ပါက အခြား fields များကို ဤနေရာတွင် ထပ်ထည့်နိုင်ပါသည်။ -->

                        <div class="d-flex justify-content-end mt-4">
                            <a href="manage_transfer_students.php" class="btn btn-secondary me-2">
                                <i class="bi bi-x-circle"></i> ဖျက်သိမ်းရန်
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-arrow-left-circle"></i> ကျောင်းသားများသို့ ပြန်ပို့ရန် အတည်ပြုပါ
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="alert alert-info" role="alert">
                        ကျောင်းသားအချက်အလက်ကို ပြသနိုင်ခြင်း မရှိပါ။
                    </div>
                    <a href="manage_transfer_students.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> နောက်သို့ ပြန်သွားမည်
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
