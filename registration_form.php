<?php
session_start();
require 'includes/db.php';
require 'includes/student_navbar.php';

// Generate CSRF token if it doesn't already exist in the session.
// This helps protect against Cross-Site Request Forgery attacks.
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$user_id = $_SESSION['user_id'] ?? null;

// User login check. If no user is logged in, redirect them to the login page.
if (!$user_id) {
    header("Location: login.php");
    exit();
}

// Fetch existing student data from the database to pre-populate the form.
$stmt = $mysqli->prepare("SELECT * FROM students WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$studentData = $result->fetch_assoc();
$stmt->close();

if (!$studentData) {
    die("Student data not found.");
}
$serial_no = $studentData['serial_no'];

// Fetch answered exam results from the database to pre-populate the form.
$stmt = $mysqli->prepare("SELECT * FROM answered_exam WHERE serial_no = ?");
if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}
$stmt->bind_param("i", $serial_no);
$stmt->execute();
$result = $stmt->get_result();
$examResults = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$entry_year = null;
foreach ($examResults as $exam) {
    if ($exam['pass_class'] === 'ပထမနှစ်') {
        $entry_year = $exam['pass_year'];
        break;
    }
}
$uploadDir = 'uploads/';

// --- Form submission logic starts here ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_form'])) {
    // Validate CSRF token to ensure the request came from our form.
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed");
    }

    // Sanitize and collect all POST data into a new array.
    $newStudentData = [
        'user_id' => $user_id,
        'serial_no' => $serial_no,
        'entry_year' => $entry_year,
        'academic_year_start' => filter_input(INPUT_POST, 'academic_year_start', FILTER_SANITIZE_STRING),
        'academic_year_end' => filter_input(INPUT_POST, 'academic_year_end', FILTER_SANITIZE_STRING),
        'class' => filter_input(INPUT_POST, 'class', FILTER_SANITIZE_STRING),
        'specialization' => filter_input(INPUT_POST, 'specialization', FILTER_SANITIZE_STRING),
        'student_name_mm' => filter_input(INPUT_POST, 'student_name_mm', FILTER_SANITIZE_STRING),
        'student_name_en' => filter_input(INPUT_POST, 'student_name_en', FILTER_SANITIZE_STRING),
        'nrc' => filter_input(INPUT_POST, 'nrc', FILTER_SANITIZE_STRING),
        'citizen_status' => filter_input(INPUT_POST, 'citizen_status', FILTER_SANITIZE_STRING),
        'nationality' => filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_STRING),
        'religion' => filter_input(INPUT_POST, 'religion', FILTER_SANITIZE_STRING),
        'dob' => filter_input(INPUT_POST, 'dob', FILTER_SANITIZE_STRING),
        'birth_place' => filter_input(INPUT_POST, 'birth_place', FILTER_SANITIZE_STRING),
        'entrance_exam_seat_number' => filter_input(INPUT_POST, 'entrance_exam_seat_number', FILTER_SANITIZE_STRING),
        'entrance_exam_year' => filter_input(INPUT_POST, 'entrance_exam_year', FILTER_SANITIZE_STRING),
        'entrance_exam_center' => filter_input(INPUT_POST, 'entrance_exam_center', FILTER_SANITIZE_STRING),
        'address_house_no' => filter_input(INPUT_POST, 'address_house_no', FILTER_SANITIZE_STRING),
        'address_street' => filter_input(INPUT_POST, 'address_street', FILTER_SANITIZE_STRING),
        'address_quarter' => filter_input(INPUT_POST, 'address_quarter', FILTER_SANITIZE_STRING),
        'address_village' => filter_input(INPUT_POST, 'address_village', FILTER_SANITIZE_STRING),
        'address_township' => filter_input(INPUT_POST, 'address_township', FILTER_SANITIZE_STRING),
        'address_region' => filter_input(INPUT_POST, 'address_region', FILTER_SANITIZE_STRING),
        'phone' => filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING),
        'father_name_mm' => filter_input(INPUT_POST, 'father_name_mm', FILTER_SANITIZE_STRING),
        'father_name_en' => filter_input(INPUT_POST, 'father_name_en', FILTER_SANITIZE_STRING),
        'father_nrc' => filter_input(INPUT_POST, 'father_nrc', FILTER_SANITIZE_STRING),
        'father_nationality' => filter_input(INPUT_POST, 'father_nationality', FILTER_SANITIZE_STRING),
        'father_religion' => filter_input(INPUT_POST, 'father_religion', FILTER_SANITIZE_STRING),
        'father_birth_place' => filter_input(INPUT_POST, 'father_birth_place', FILTER_SANITIZE_STRING),
        'father_citizen_status' => filter_input(INPUT_POST, 'father_citizen_status', FILTER_SANITIZE_STRING),
        'father_phone' => filter_input(INPUT_POST, 'father_phone', FILTER_SANITIZE_STRING),
        'father_job' => filter_input(INPUT_POST, 'father_job', FILTER_SANITIZE_STRING),
        'father_address_house_no' => filter_input(INPUT_POST, 'father_address_house_no', FILTER_SANITIZE_STRING),
        'father_address_street' => filter_input(INPUT_POST, 'father_address_street', FILTER_SANITIZE_STRING),
        'father_address_quarter' => filter_input(INPUT_POST, 'father_address_quarter', FILTER_SANITIZE_STRING),
        'father_address_village' => filter_input(INPUT_POST, 'father_address_village', FILTER_SANITIZE_STRING),
        'father_address_township' => filter_input(INPUT_POST, 'father_address_township', FILTER_SANITIZE_STRING),
        'father_address_region' => filter_input(INPUT_POST, 'father_address_region', FILTER_SANITIZE_STRING),
        'father_address_state'=> filter_input(INPUT_POST, 'father_address_state', FILTER_SANITIZE_STRING),
        'mother_name_mm' => filter_input(INPUT_POST, 'mother_name_mm', FILTER_SANITIZE_STRING),
        'mother_name_en' => filter_input(INPUT_POST, 'mother_name_en', FILTER_SANITIZE_STRING),
        'mother_nrc' => filter_input(INPUT_POST, 'mother_nrc', FILTER_SANITIZE_STRING),
        'mother_nationality' =>filter_input(INPUT_POST, 'mother_nationality', FILTER_SANITIZE_STRING),
        'mother_religion' => filter_input(INPUT_POST, 'mother_religion', FILTER_SANITIZE_STRING),
        'mother_birth_place' => filter_input(INPUT_POST, 'mother_birth_place', FILTER_SANITIZE_STRING),
        'mother_citizen_status' => filter_input(INPUT_POST, 'mother_citizen_status', FILTER_SANITIZE_STRING),
        'mother_phone' => filter_input(INPUT_POST, 'mother_phone', FILTER_SANITIZE_STRING),
        'mother_job' => filter_input(INPUT_POST, 'mother_job', FILTER_SANITIZE_STRING),
        'mother_address_house_no' => filter_input(INPUT_POST, 'mother_address_house_no', FILTER_SANITIZE_STRING),
        'mother_address_street' => filter_input(INPUT_POST, 'mother_address_street', FILTER_SANITIZE_STRING),
        'mother_address_quarter' => filter_input(INPUT_POST, 'mother_address_quarter', FILTER_SANITIZE_STRING),
        'mother_address_village' => filter_input(INPUT_POST, 'mother_address_village', FILTER_SANITIZE_STRING),
        'mother_address_township' => filter_input(INPUT_POST, 'mother_address_township', FILTER_SANITIZE_STRING),
        'mother_address_region' => filter_input(INPUT_POST, 'mother_address_region', FILTER_SANITIZE_STRING),
        'mother_address_state' =>filter_input(INPUT_POST, 'mother_address_state', FILTER_SANITIZE_STRING),
        'supporter_name' => filter_input(INPUT_POST, 'supporter_name', FILTER_SANITIZE_STRING),
        'supporter_relation' => filter_input(INPUT_POST, 'supporter_relation', FILTER_SANITIZE_STRING),
        'supporter_job' => filter_input(INPUT_POST, 'supporter_job', FILTER_SANITIZE_STRING),
        'supporter_address' => filter_input(INPUT_POST, 'supporter_address', FILTER_SANITIZE_STRING),
        'supporter_phone' => filter_input(INPUT_POST, 'supporter_phone', FILTER_SANITIZE_STRING),
        'grant_support' => filter_input(INPUT_POST, 'grant_support', FILTER_SANITIZE_STRING),
        'signature_status' => filter_input(INPUT_POST, 'signature_status', FILTER_SANITIZE_STRING),
        'current_home_no' => filter_input(INPUT_POST, 'current_home_no', FILTER_SANITIZE_STRING),
        'current_street' => filter_input(INPUT_POST, 'current_street', FILTER_SANITIZE_STRING),
        'current_quarter' => filter_input(INPUT_POST, 'current_quarter', FILTER_SANITIZE_STRING),
        'current_village' => filter_input(INPUT_POST, 'current_village', FILTER_SANITIZE_STRING),
        'current_township' => filter_input(INPUT_POST, 'current_township', FILTER_SANITIZE_STRING),
        'current_phone' => filter_input(INPUT_POST, 'current_phone', FILTER_SANITIZE_STRING),
        'image_path' => $studentData['image_path'] ?? null // Use existing image path as default.
    ];
    
    // Process the uploaded student image.
    $uploadDir = 'uploads/image/';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = basename($_FILES['image']['name']);
        $fileTmp = $_FILES['image']['tmp_name'];
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExt, $allowedExt)) {
            $newFileName = uniqid('student_') . '.' . $fileExt;
            $targetPath = $uploadDir . $newFileName;
            if (move_uploaded_file($fileTmp, $targetPath)) {
                $newStudentData['image_path'] = $targetPath;
            }
        }
    }
    
    // Sanitize and collect all exam results into a new array.
    $newExamResults = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($_POST["pass_class$i"])) {
            $newExamResults[] = [
                'pass_class' => filter_input(INPUT_POST, "pass_class$i", FILTER_SANITIZE_STRING),
                'pass_specialization' => filter_input(INPUT_POST, "pass_specialization$i", FILTER_SANITIZE_STRING),
                'pass_serial_no' => filter_input(INPUT_POST, "pass_serial_no$i", FILTER_SANITIZE_STRING),
                'pass_year' => filter_input(INPUT_POST, "pass_year$i", FILTER_SANITIZE_STRING),
                'pass_fail_status' => filter_input(INPUT_POST, "pass_fail_status$i", FILTER_SANITIZE_STRING),
            ];
        }
    }
    
    // Store all the collected data into the session.
    // This is the crucial step that allows 'payment.php' and 'save_all.php' to access the data.
    $_SESSION['student_data'] = $newStudentData;
    $_SESSION['exam_results'] = $newExamResults;
    $_SESSION['serial_no'] = $serial_no;

    // After setting the session variables, redirect the user to the payment page.
    header('Location: payment.php');
    exit();
}
// --- End of form submission logic ---

?>

<!-- ... (Your HTML and JavaScript code for the form) ... -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UCS(MGY) Registration Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Your existing CSS styles here */
            nav.navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #fff;
        }
        .form-container {
            margin-top: 20px;
            width: 950px;
        }
        .student-select-container {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .student-select-container label {
            font-weight: bold;
            margin-right: 10px;
        }
        .student-select-container select {

            width: 200px;
            padding: 5px;
        }
        .student-select-container button {
            margin-left: 10px;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .main-table td, .main-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .exam-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .exam-table td, .exam-table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .form-section {
            display: none;
        }
        .form-section.active {
            display: block;
        }
        .image-preview-container {
            width: 150px;
            height: 180px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .image-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .navigation {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
        }
        .declaration {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
        }
        .declaration-item {
            margin-bottom: 10px;
        }
        .required:after {
            content: " *";
            color: red;
        }
        .year-input {
            width: 50px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container form-container">
        <form id="studentForm" method="POST" action="payment.php" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

            <!-- Your existing form HTML here -->
             <div class="form-header text-center mb-4">
                <img src="uploads/image/ucsmgy.png" alt="တက္ကသိုလ်လိုဂို" class="university-logo" style="height: 80px;" align="left">
                <h5>ကွန်ပျူတာတက္ကသိုလ် (မကွေး)</h5>
                <span>(</span>
                <input type="text" name="academic_year_start" class="year-input" maxlength="4" value="<?php echo isset($studentData['academic_year_start']) ? $studentData['academic_year_start'] : '၂၀၂၄'; ?>" oninput="allowOnlyMyanmarDigits(this)">
                <span>-</span>
                <input type="text" name="academic_year_end" class="year-input" maxlength="4" value="<?php echo isset($studentData['academic_year_end']) ? $studentData['academic_year_end'] : '၂၀၂၅'; ?>" oninput="allowOnlyMyanmarDigits(this)">
                <span>) ပညာသင်နှစ်</span>
                <h5>ကျောင်းသား/ကျောင်းသူများ ပညာသင်ခွင့်လျှောက်လွှာ</h5>
            </div>

            <!-- Page 1: Student Information -->
            <div class="form-section active" id="page1">
                <table class="main-table">
                    <tr>
                        <?php
                            $imagePath = $studentData['image_path'] ?? 'uploads/image/default.png';
                        ?>

                        <td class="photo-cell" rowspan="5" style="width: 160px; vertical-align: top;">
                            <label for="fileupload" class="d-block">
                                <div class="image-preview-container mb-2">
                                    <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="Student Photo" class="image-preview" style="width: 140px; height: auto; border: 1px solid #ccc;" />
                                </div>
                                <input class="form-control form-control-sm" type="file" id="fileupload" name="image" onchange="previewImage(this)">
                            </label>
                        </td>

                        <td>သင်တန်းနှစ်</td>
                        <td>
                            <select name="class" class="form-control" required>
                                <option value="">--ရွေးပါ--</option>
                                <option value="ပထမနှစ်" <?php echo (isset($studentData['class']) && $studentData['class'] == 'ပထမနှစ်') ? 'selected' : ''; ?>>ပထမနှစ်</option>
                                <option value="ဒုတိယနှစ်" <?php echo (isset($studentData['class']) && $studentData['class'] == 'ဒုတိယနှစ်') ? 'selected' : ''; ?>>ဒုတိယနှစ်</option>
                                <option value="တတိယနှစ်" <?php echo (isset($studentData['class']) && $studentData['class'] == 'တတိယနှစ်') ? 'selected' : ''; ?>>တတိယနှစ်</option>
                                <option value="စတုတ္ထနှစ်" <?php echo (isset($studentData['class']) && $studentData['class'] == 'စတုတ္ထနှစ်') ? 'selected' : ''; ?>>စတုတ္ထနှစ်</option>
                                <option value="ပဉ္စမနှစ်" <?php echo (isset($studentData['class']) && $studentData['class'] == 'ပဉ္စမနှစ်') ? 'selected' : ''; ?>>ပဉ္စမနှစ်</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>အထူးပြုဘာသာ</td>
                        <td>
                            <select name="specialization" class="form-control" required>
                                <option value="">--ရွေးပါ--</option>
                                <option value="CST" <?php echo (isset($studentData['specialization']) && $studentData['specialization'] == 'CST') ? 'selected' : ''; ?>>CST</option>
                                <option value="CS" <?php echo (isset($studentData['specialization']) && $studentData['specialization'] == 'CS') ? 'selected' : ''; ?>>CS</option>
                                <option value="CT" <?php echo (isset($studentData['specialization']) && $studentData['specialization'] == 'CT') ? 'selected' : ''; ?>>CT</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>ခုံအမှတ်</td>
                        <td style="display: flex; gap: 5px; align-items: center;">
                            <!-- UCSMG- Prefix (always readonly) -->
                            <input type="text" name="serial_code" value="UCSMG-" readonly
                                style="flex: 0 0 80px; background-color: #f1f1f1; border: 1px solid #ccc; text-align: center;">

                            <!-- Serial No (always readonly, no edit button) -->
                            <input type="text" name="serial_no" id="serial_no" pattern="\d{5}" maxlength="5" placeholder="e.g., 24001"
                                value="<?= isset($studentData['serial_no']) ? htmlspecialchars($studentData['serial_no']) : ''; ?>"
                                class="form-control" readonly>
                        </td>
                    </tr>

                    <tr>
                        <td>တက္ကသိုလ်ဝင်ရောက်သည့်ခုနှစ်</td>
                        <td>
                            <input type="text" name="entry_year" pattern="20\d{2}" maxlength="4" value="<?php echo isset($studentData['entry_year']) ? $studentData['entry_year'] : ''; ?>" placeholder="ဥပမာ - 2024" class="form-control" readonly>
                        </td>
                    </tr>
                </table>

                <!-- Personal Information Table -->
                <table class="main-table">
                    <tr>
                        <td colspan="2"><label class="required">၁။ ပညာဆက်လက်သင်ခွင့်တောင်းသူ</label></td>
                        <td><label>ကျောင်းသား/ကျောင်းသူ</label></td>
                        <td style="width:190px;text-align:center;"><label>အဘ</label></td>
                        <td style="width:190px;text-align:center;"><label>အမိ</label></td>
                    </tr>
                    <tr>
                        <td rowspan="2"><label class="required">အမည်</label></td>
                        <td><label>မြန်မာစာဖြင့်</label></td>
                        <td><input type="text" name="student_name_mm" value="<?php echo isset($studentData['student_name_mm']) ? $studentData['student_name_mm'] : ''; ?>" placeholder="မောင်ကျော်ကျော် / မလှလှ" class="form-control" readonly></td>
                        <td><input type="text" name="father_name_mm" value="<?php echo isset($studentData['father_name_mm']) ? $studentData['father_name_mm'] : ''; ?>" placeholder="ဦးမောင်မောင်" class="form-control" readonly></td>
                        <td><input type="text" name="mother_name_mm" value="<?php echo isset($studentData['mother_name_mm']) ? $studentData['mother_name_mm'] : ''; ?>" placeholder="ဒေါ်အေးအေး" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td><label>အင်္ဂလိပ်စာဖြင့်</label></td>
                        <td><input type="text" name="student_name_en" value="<?php echo isset($studentData['student_name_en']) ? $studentData['student_name_en'] : ''; ?>" placeholder="Mg Kyaw Kyaw / Ma Hla Hla" class="form-control" readonly></td>
                        <td><input type="text" name="father_name_en" value="<?php echo isset($studentData['father_name_en']) ? $studentData['father_name_en'] : ''; ?>" placeholder="U Maung Maung" class="form-control" readonly></td>
                        <td><input type="text" name="mother_name_en" value="<?php echo isset($studentData['mother_name_en']) ? $studentData['mother_name_en'] : ''; ?>" placeholder="Daw Aye Aye" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label class="required">လူမျိုး</label></td>
                        <td><input type="text" name="nationality" value="<?php echo isset($studentData['nationality']) ? $studentData['nationality'] : ''; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="father_nationality" value="<?php echo isset($studentData['father_nationality']) ? $studentData['father_nationality'] : ''; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="mother_nationality" value="<?php echo isset($studentData['mother_nationality']) ? $studentData['mother_nationality'] : ''; ?>" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label class="required">ကိုးကွယ်သည့်ဘာသာ</label></td>
                        <td><input type="text" name="religion" value="<?php echo isset($studentData['religion']) ? $studentData['religion'] : ''; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="father_religion" value="<?php echo isset($studentData['father_religion']) ? $studentData['father_religion'] : ''; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="mother_religion" value="<?php echo isset($studentData['mother_religion']) ? $studentData['mother_religion'] : ''; ?>" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label class="required">မွေးဖွားရာဇာတိ</label></td>
                        <td><input type="text" name="birth_place" value="<?php echo isset($studentData['birth_place']) ? $studentData['birth_place'] : ''; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="father_birth_place" value="<?php echo isset($studentData['father_birth_place']) ? $studentData['father_birth_place'] : ''; ?>" class="form-control" required></td>
                        <td><input type="text" name="mother_birth_place" value="<?php echo isset($studentData['mother_birth_place']) ? $studentData['mother_birth_place'] : ''; ?>" class="form-control" required></td>
                    </tr>
                    <tr>
                        <td colspan="2"><label class="required">မြို့နယ်/ပြည်နယ်/တိုင်း</label></td>
                        <td>
                            <input type="text" name="address_township" value="<?php echo isset($studentData['address_township']) ? $studentData['address_township'] : ''; ?>" class="form-control" placeholder="မြို့နယ်" required>
                            <input type="text" name="address_region" value="<?php echo isset($studentData['address_region']) ? $studentData['address_region'] : ''; ?>" class="form-control mt-1" placeholder="တိုင်းဒေသကြီး" >
                            <input type="text" name="address_state" value="<?php echo isset($studentData['address_state']) ? $studentData['address_state'] : ''; ?>" class="form-control mt-1" placeholder="ပြည်နယ်" >
  
                        </td>
                        <td>
                            <input type="text" name="father_township" value="<?php echo isset($studentData['father_address_township']) ? $studentData['father_address_township'] : ''; ?>" class="form-control" placeholder="မြို့နယ်" required>
                            <input type="text" name="father_address_region" value="<?php echo isset($studentData['father_address_region']) ? $studentData['father_address_region'] : ''; ?>" class="form-control mt-1" placeholder="တိုင်းဒေသကြီး" >
                            <input type="text" name="father_address_state" value="<?php echo isset($studentData['father_address_state']) ? $studentData['father_address_state'] : ''; ?>" class="form-control mt-1" placeholder="ပြည်နယ်" >
 
                        </td>
                        <td>
                            <input type="text" name="mother_township" value="<?php echo isset($studentData['mother_address_township']) ? $studentData['mother_address_township'] : ''; ?>" class="form-control" placeholder="မြို့နယ်" required>
                            <input type="text" name="mother_address_region" value="<?php echo isset($studentData['mother_address_region']) ? $studentData['mother_address_region'] : ''; ?>" class="form-control mt-1" placeholder="တိုင်းဒေသကြီး">
                            <input type="text" name="mother_address_state" value="<?php echo isset($studentData['mother_address_state']) ? $studentData['mother_address_state'] : ''; ?>" class="form-control mt-1" placeholder="ပြည်နယ်">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><label class="required">မှတ်ပုံတင်အမှတ်</label></td>
                        <td><input type="text" name="nrc" value="<?php echo isset($studentData['nrc']) ? $studentData['nrc'] : ''; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="father_nrc" value="<?php echo isset($studentData['father_nrc']) ? $studentData['father_nrc'] : ''; ?>" class="form-control" readonly></td>
                        <td><input type="text" name="mother_nrc" value="<?php echo isset($studentData['mother_nrc']) ? $studentData['mother_nrc'] : ''; ?>" class="form-control" readonly></td>
                    </tr>
                    <tr>
                    <td colspan="2">
                        <label class="required">နိုင်ငံခြားသား</label>
                    </td>

                    <!-- Student -->
                    <td>
                        <select name="citizen_status" class="form-select form-select-sm" readonly>
                        <option value="" <?= (!isset($studentData['citizen_status'])) ? 'selected' : '' ?>>တိုင်းရင်းသား/နိုင်ငံခြားသား</option>
                        <option value="တိုင်းရင်းသား" <?= (isset($studentData['citizen_status']) && $studentData['citizen_status'] == 'တိုင်းရင်းသား') ? 'selected' : '' ?>>တိုင်းရင်းသား</option>
                        <option value="နိုင်ငံခြားသား" <?= (isset($studentData['citizen_status']) && $studentData['citizen_status'] == 'နိုင်ငံခြားသား') ? 'selected' : '' ?>>နိုင်ငံခြားသား</option>
                        </select>
                    </td>

                    <!-- Father -->
                    <td>
                        <select name="father_citizen_status" class="form-select form-select-sm" readonly>
                        <option value=""  <?= (!isset($studentData['father_citizen_status'])) ? 'selected' : '' ?>>တိုင်းရင်းသား/နိုင်ငံခြားသား</option>
                        <option value="တိုင်းရင်းသား" <?= (isset($studentData['father_citizen_status']) && $studentData['father_citizen_status'] == 'တိုင်းရင်းသား') ? 'selected' : '' ?>>တိုင်းရင်းသား</option>
                        <option value="နိုင်ငံခြားသား" <?= (isset($studentData['father_citizen_status']) && $studentData['father_citizen_status'] == 'နိုင်ငံခြားသား') ? 'selected' : '' ?>>နိုင်ငံခြားသား</option>
                        </select>
                    </td>

                    <!-- Mother -->
                    <td>
                        <select name="mother_citizen_status" class="form-select form-select-sm" readonly>
                        <option value=""  <?= (!isset($studentData['mother_citizen_status'])) ? 'selected' : '' ?>>တိုင်းရင်းသား/နိုင်ငံခြားသား</option>
                        <option value="တိုင်းရင်းသား" <?= (isset($studentData['mother_citizen_status']) && $studentData['mother_citizen_status'] == 'တိုင်းရင်းသား') ? 'selected' : '' ?>>တိုင်းရင်းသား</option>
                        <option value="နိုင်ငံခြားသား" <?= (isset($studentData['mother_citizen_status']) && $studentData['mother_citizen_status'] == 'နိုင်ငံခြားသား') ? 'selected' : '' ?>>နိုင်ငံခြားသား</option>
                        </select>
                    </td>
                    </tr>

                    <tr>
                        <td colspan="2"><label class="required">မွေးသက္ကရာဇ်</label></td>
                        <td><input type="date" name="dob" value="<?php echo isset($studentData['dob']) ? $studentData['dob'] : ''; ?>" class="form-control" readonly></td>
                        <td colspan="2">အဘအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</td>
                    </tr>
                    <tr>
                        <td rowspan="3">တက္ကသိုလ်ဝင်တန်းစာမေးပွဲအောင်မြင်သည့်</td>
                        <td>ခုံအမှတ် - </td>
                        <td><input type="text" name="entrance_exam_seat_number" value="<?php echo isset($studentData['entrance_exam_seat_number']) ? $studentData['entrance_exam_seat_number'] : ''; ?>" class="form-control" readonly></td>
                        <td class="text-center" colspan="2" rowspan="3" style="padding: 8px;">
                            <div class="container-fluid">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="father_address_house_no" value="<?php echo isset($studentData['father_house_no']) ? $studentData['father_address_house_no'] : ''; ?>" placeholder="အိမ်">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="father_address_quarter" value="<?php echo isset($studentData['father_address_quarter']) ? $studentData['father_address_quarter'] : ''; ?>" placeholder="ရပ်ကွက်">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="father_address_street" value="<?php echo isset($studentData['father_address_street']) ? $studentData['father_address_street'] : ''; ?>" placeholder="လမ်းအမှတ်">
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="father_address_village" value="<?php echo isset($studentData['father_address_village']) ? $studentData['father_address_village'] : ''; ?>" placeholder="ကျေးရွာ">
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="father_address_township" value="<?php echo isset($studentData['father_address_township']) ? $studentData['father_address_township'] : ''; ?>" placeholder="မြို့နယ်" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="father_phone" value="<?php echo isset($studentData['father_phone']) ? $studentData['father_phone'] : ''; ?>" placeholder="09-xxxxxxxxx" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="father_job" value="<?php echo isset($studentData['father_job']) ? $studentData['father_job'] : ''; ?>" placeholder="အလုပ်အကိုင်" required>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>ခုနှစ် - </td>
                        <td><input type="text" name="entrance_exam_year" value="<?php echo isset($studentData['entrance_exam_year']) ? $studentData['entrance_exam_year'] : ''; ?>" class="form-control" maxlength="4" readonly></td>
                    </tr>
                    <tr>
                        <td>စာစစ်ဌာန - </td>
                        <td><input type="text" name="entrance_exam_center" value="<?php echo isset($studentData['entrance_exam_center']) ? $studentData['entrance_exam_center'] : ''; ?>" class="form-control" readonly></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="center"><label class="required">အမြဲတမ်းနေထိုင်သည့်လိပ်စာ(အပြည့်အစုံ)</label></td>
                        <td colspan="2"><label class="required">အမိအုပ်ထိန်းသူ၏ အလုပ်အကိုင်၊ရာထူး၊ဌာန၊လိပ်စာအပြည့်အစုံ</label></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding: 8px;">
                            <div class="container-fluid">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="address_house_no" value="<?php echo isset($studentData['address_house_no']) ? $studentData['address_house_no'] : ''; ?>" placeholder="အိမ်အမှတ်">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="address_street" value="<?php echo isset($studentData['address_street']) ? $studentData['address_street'] : ''; ?>" placeholder="လမ်း" >
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="address_quarter" value="<?php echo isset($studentData['address_quarter']) ? $studentData['address_quarter'] : ''; ?>" placeholder="ရပ်ကွက်" >
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="address_village" value="<?php echo isset($studentData['address_village']) ? $studentData['address_village'] : ''; ?>" placeholder="ကျေးရွာ">
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="address_township" value="<?php echo isset($studentData['address_township']) ? $studentData['address_township'] : ''; ?>" placeholder="မြို့နယ်" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="phone" value="<?php echo isset($studentData['phone']) ? $studentData['phone'] : ''; ?>" placeholder="ဖုန်းနံပါတ်" required>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td colspan="2" style="padding: 8px;">
                            <div class="container-fluid">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="mother_address_house_no" value="<?php echo isset($studentData['mother_address_house_no']) ? $studentData['mother_address_house_no'] : ''; ?>" placeholder="အိမ်">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="mother_address_quarter" value="<?php echo isset($studentData['mother_address_quarter']) ? $studentData['mother_address_quarter'] : ''; ?>" placeholder="ရပ်ကွက်">
                                    </div>
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" name="mother_address_street" value="<?php echo isset($studentData['mother_address_street']) ? $studentData['mother_address_street'] : ''; ?>" placeholder="လမ်းအမှတ်" >
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="mother_address_village" value="<?php echo isset($studentData['mother_address_village']) ? $studentData['mother_address_village'] : ''; ?>" placeholder="ကျေးရွာ" >
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="mother_address_township" value="<?php echo isset($studentData['mother_address_township']) ? $studentData['mother_address_township'] : ''; ?>" placeholder="မြို့နယ်" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="mother_phone" value="<?php echo isset($studentData['mother_phone']) ? $studentData['mother_phone'] : ''; ?>" placeholder="09-xxxxxxxxx" required>
                                    </div>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="mother_job" value="<?php echo isset($studentData['mother_job']) ? $studentData['mother_job'] : ''; ?>" placeholder="အလုပ်အကိုင်" required>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="exam-table">
                    <tr>
                        <th>ဖြေဆိုခဲ့သည့်စာမေးပွဲများ</th>
                        <th>အဓိကဘာသာ</th>
                        <th>ခုံအမှတ်</th>
                        <th>ခုနှစ်</th>
                        <th>အောင်/ရှုံး</th>
                    </tr>
                    <?php for ($i = 1; $i <= 5; $i++):
                        $result = isset($examResults[$i-1]) ? $examResults[$i-1] : [];
                    ?>
                    <tr>
                        <td>
                            <select name="pass_class<?php echo $i; ?>" class="form-select form-select-sm">
                                <option value="">ရွေးချယ်ပါ</option>
                                <option value="ပထမနှစ်" <?php echo (isset($result['pass_class']) && $result['pass_class'] == 'ပထမနှစ်') ? 'selected' : ''; ?>>ပထမနှစ်</option>
                                <option value="ဒုတိယနှစ်" <?php echo (isset($result['pass_class']) && $result['pass_class'] == 'ဒုတိယနှစ်') ? 'selected' : ''; ?>>ဒုတိယနှစ်</option>
                                <option value="တတိယနှစ်" <?php echo (isset($result['pass_class']) && $result['pass_class'] == 'တတိယနှစ်') ? 'selected' : ''; ?>>တတိယနှစ်</option>
                                <option value="စတုတ္ထနှစ်" <?php echo (isset($result['pass_class']) && $result['pass_class'] == 'စတုတ္ထနှစ်') ? 'selected' : ''; ?>>စတုတ္ထနှစ်</option>
                                <option value="ပဉ္စမနှစ်" <?php echo (isset($result['pass_class']) && $result['pass_class'] == 'ပဉ္စမနှစ်') ? 'selected' : ''; ?>>ပဉ္စမနှစ်</option>
                            </select>
                        </td>
                        <td>
                            <select name="pass_specialization<?php echo $i; ?>" class="form-select form-select-sm">
                                <option value="">ရွေးချယ်ပါ</option>
                                <option value="CST" <?php echo (isset($result['pass_specialization']) && $result['pass_specialization'] == 'CST') ? 'selected' : ''; ?>>CST</option>
                                <option value="CS" <?php echo (isset($result['pass_specialization']) && $result['pass_specialization'] == 'CS') ? 'selected' : ''; ?>>CS</option>
                                <option value="CT" <?php echo (isset($result['pass_specialization']) && $result['pass_specialization'] == 'CT') ? 'selected' : ''; ?>>CT</option>
                            </select>
                        </td>
                        <td>
                            <input type="text" name="pass_serial_no<?php echo $i; ?>" value="<?php echo isset($result['pass_serial_no']) ? $result['pass_serial_no'] : ''; ?>" class="form-control form-control-sm" style="max-width: 200px;">
                        </td>
                        <td>
                            <input type="text" name="pass_year<?php echo $i; ?>" value="<?php echo isset($result['pass_year']) ? $result['pass_year'] : ''; ?>" maxlength="4" class="form-control form-control-sm" style="max-width: 200px;">
                        </td>
                        <td>
                            <select name="pass_fail_status<?php echo $i; ?>" class="form-select form-select-sm">
                                <option value="">ရွေးချယ်ပါ</option>
                                <option value="အောင်" <?php echo (isset($result['pass_fail_status']) && $result['pass_fail_status'] == 'အောင်') ? 'selected' : ''; ?>>အောင်</option>
                                <option value="ရှုံး" <?php echo (isset($result['pass_fail_status']) && $result['pass_fail_status'] == 'ရှုံး') ? 'selected' : ''; ?>>ရှုံး</option>
                                <option value="ရပ်နား" <?php echo (isset($result['pass_fail_status']) && $result['pass_fail_status'] == 'ရပ်နား') ? 'selected' : ''; ?>>ရပ်နား</option>
                            </select>
                        </td>
                    </tr>
                    <?php endfor; ?>
                </table>

                <div class="navigation">
                    <!-- <button type="button" class="btn btn-secondary" onclick="prevPage(1, 2)">
                        <i class="fas fa-arrow-left"></i> နောက်သို့
                    </button> -->
                    <button type="button" class="btn btn-primary" onclick="nextPage(1, 2)" >
                        ရှေ့သို့ <i class="fas fa-arrow-right"></i>
                    </button>
                </div>

            </div>

            <!-- Page 2: Supporter Information -->
            <div class="form-section" id="page2">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="form-section-title">၃။ ကျောင်းနေရန်အထောက်အပံ့ပြုမည့်ပုဂ္ဂိုလ်</h5>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-bordered">
                            <tr>
                                <td>(က) အမည်</td>
                                <td><input type="text" name="supporter_name" value="<?php echo isset($studentData['supporter_name']) ? $studentData['supporter_name'] : ''; ?>" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td>(ခ) ဆွေမျိုးတော်စပ်ပုံ</td>
                                <td><input type="text" name="supporter_relation" value="<?php echo isset($studentData['supporter_relation']) ? $studentData['supporter_relation'] : ''; ?>" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td>(ဂ) အလုပ်အကိုင်</td>
                                <td><input type="text" name="supporter_job" value="<?php echo isset($studentData['supporter_job']) ? $studentData['supporter_job'] : ''; ?>" class="form-control" required></td>
                            </tr>
                            <tr>
                                <td>(ဃ) ဆက်သွယ်ရန်လိပ်စာ</td>
                                <td><input type="text" name="supporter_address" value="<?php echo isset($studentData['supporter_address']) ? $studentData['supporter_address'] : ''; ?>" class="form-control" placeholder="ကျေးရွာ/ရပ်ကွက်/မြို့" required></td>
                            </tr>
                            <tr>
                                <td>နှင့်ဖုန်းနံပါတ်</td>
                                <td><input type="text" name="supporter_phone" value="<?php echo isset($studentData['supporter_phone']) ? $studentData['supporter_phone'] : ''; ?>" class="form-control" placeholder="09-xxxxxxxxx" required></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5 class="form-section-title">၄။ ပညာသင်ထောက်ပံ့ကြေးပေးရန် ပြု/မပြု</h5>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="grant_support" id="support_yes" value="ပြု" <?php echo (isset($studentData['grant_support']) && $studentData['grant_support'] == 'ပြု') ? 'checked' : ''; ?> required>
                            <label class="form-check-label" for="support_yes">ပြု</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="grant_support" id="support_no" value="မပြု" <?php echo (isset($studentData['grant_support']) && $studentData['grant_support'] == 'မပြု') ? 'checked' : ''; ?>>
                            <label class="form-check-label" for="support_no">မပြု</label>
                        </div>
                    </div>
                </div>

                <h5 class="form-section-title text-center mt-4">ကိုယ်တိုင်ဝန်ခံချက်</h5>
                <div class="declaration">
                    <div class="declaration-item">၁။ အထက်ဖော်ပြပါအချက်အားလုံးမှန်ကန်ပါသည်။</div>
                    <div class="declaration-item">၂။ ဤတက္ကသိုလ်၌ ဆက်လက်ပညာသင်ခွင့်တောင်းသည်ကို မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။</div>
                    <div class="declaration-item">၃။ ကျောင်းလခများမှန်မှန်ပေးရန် မိဘ(သို့မဟုတ်)အုပ်ထိန်းသူက သဘောတူပြီးဖြစ်ပါသည်။</div>
                    <div class="declaration-item">၄။ တက္ကသိုလ်ကျောင်းသားကောင်းတစ်ယောက်ပီသစွာ တက္ကသိုလ်ကချမှတ်သည့်စည်းမျဉ်းစည်းကမ်းနှင့်အညီ လိုက်နာကျင့်သုံးနေထိုင်ပါမည်။</div>
                    <div class="declaration-item">၅။ ကျွန်တော်/ကျွန်မသည် မည်သည့်နိုင်ငံရေးပါတီတွင်မျှပါဝင်မည်မဟုတ်ပါ။ မည်သည့်နိုင်ငံရေးလှုပ်ရှားမှုမျှ ပါဝင်မည်မဟုတ်ကြောင်း ဝန်ခံကတိပြုပါသည်။</div>
                </div>

                <div class="row mt-4">
                  <div class="col-md-6">
                      <div class="date-input-group">
                          <label class="form-label fw-bold">နေ့စွဲ၊၂၀</label>
                          <input type="text" name="day"
                              value="<?php echo date('d'); ?>"
                              class="form-control d-inline-block"
                              style="width: 70px;" maxlength="2" disabled>
                          <span>ရက်၊</span>
                          <input type="text" name="month"
                              value="<?php echo date('m'); ?>"
                              class="form-control d-inline-block"
                              style="width: 70px;" maxlength="2" disabled>
                          <span>လ၊</span>
                          <input type="text" name="year"
                              value="<?php echo date('Y'); ?>"
                              class="form-control d-inline-block"
                              style="width: 70px;" maxlength="4" disabled>
                          <span>ခုနှစ်</span>
                      </div>
                  </div>
              </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">ယခုဆက်သွယ်ရန်လိပ်စာ</h6>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>အိမ်အမှတ်</label>
                                        <input type="text" name="current_home_no" value="<?php echo isset($studentData['current_home_no']) ? $studentData['current_home_no'] : ''; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>လမ်းအမှတ်</label>
                                        <input type="text" name="current_street_no" value="<?php echo isset($studentData['current_street']) ? $studentData['current_street'] : ''; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>ရပ်ကွက်</label>
                                        <input type="text" name="current_quarter" value="<?php echo isset($studentData['current_quarter']) ? $studentData['current_quarter'] : ''; ?>" class="form-control">
                                    </div>
                                    <div class="col-md-6">
                                        <label>ကျေးရွာ</label>
                                        <input type="text" name="current_village" value="<?php echo isset($studentData['current_village']) ? $studentData['current_village'] : ''; ?>" class="form-control">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-6">
                                        <label>မြို့နယ်</label>
                                        <input type="text" name="current_township" value="<?php echo isset($studentData['current_township']) ? $studentData['current_township'] : ''; ?>" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label>ဖုန်းနံပါတ်</label>
                                        <input type="text" name="current_phone" value="<?php echo isset($studentData['current_phone']) ? $studentData['current_phone'] : ''; ?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center mb-4">
                            <input type="text" name="signature_status" value="<?php echo isset($studentData['signature_status']) ? $studentData['signature_status'] : ''; ?>" class="form-control mb-2" style="width: 60%; margin: 0 auto;" placeholder="လက်မှတ်" required>
                            <div>ပညာသင်ခွင့်လျှောက်ထားသူလက်မှတ်</div>
                        </div>
                        <div class="text-center mt-5">
                            <div class="mb-2">---------------------</div>
                            <div>တက္ကသိုလ်ရုံးမှစစ်ဆေးပြီး</div>
                        </div>
                    </div>
                </div>
                <div class="text-center mb-4">
                    <h5>(မကွေးကွန်ပျုတာ)တက္ကသိုလ်ရုံးအတွက်</h5>
                    <p>ဖော်ပြပါဘာသာရပ်များဖြင့်ပညာသင်ခွင့်ပြုသည်။</p>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="specialization" class="form-label">အဓိကသာမာန်ဘာသာတွဲများ</label>
                        <select name="specialization" class="form-control" required>
                            <option value="">--ရွေးချယ်ပါ--</option>
                            <option value="CST" <?php echo (isset($studentData['specialization']) && $studentData['specialization'] == 'CST') ? 'selected' : ''; ?>>CST</option>
                            <option value="CS" <?php echo (isset($studentData['specialization']) && $studentData['specialization'] == 'CS') ? 'selected' : ''; ?>>CS</option>
                            <option value="CT" <?php echo (isset($studentData['specialization']) && $studentData['specialization'] == 'CT') ? 'selected' : ''; ?>>CT</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <div class="mb-2">------------------</div>
                            <div>ပါမောက္ခချုပ်</div>
                            <div>ကွန်ပျုတာတက္ကသိုလ်(မကွေး)</div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h5 class="form-section-title">ငွေလက်ခံသည့်ဌာန။</h5>
                        <p>ငွေသွင်းရန်လိုအပ်သည့် ငွေကြေးများကို လက်ခံရရှိပြီးဖြစ်ပါသည်။</p>

                        <div class="date-input-group">
                          <label class="form-label">နေ့စွဲ</label>
                          <input type="text" name="payment_day"
                              value="<?php echo date('d'); ?>"
                              class="form-control d-inline-block"
                              style="width: 70px;" maxlength="2" disabled>
                          <span>ရက်၊</span>
                          <input type="text" name="payment_month"
                              value="<?php echo date('m'); ?>"
                              class="form-control d-inline-block"
                              style="width: 70px;" maxlength="2" disabled>
                          <span>လ၊</span>
                          <input type="text" name="payment_year"
                              value="<?php echo date('Y'); ?>"
                              class="form-control d-inline-block"
                              style="width: 70px;" maxlength="4" disabled>
                          <span>ခုနှစ်</span>
                      </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center mt-4">
                            <div>-----------------</div>
                            <div>ငွေလက်ခံသူ</div>
                        </div>
                    </div>
                </div>

           <div class="navigation">
                <button type="button" class="btn btn-primary" onclick="prevPage(2, 1)">
                    <i class="fas fa-arrow-left"></i> နောက်သို့
                </button>
                <button type="submit" name="submit_form" class="btn btn-primary">Submit Registration</button>

            </div>
        </form>
    </div>
    <!-- Photo Alert Modal -->
    <div class="modal fade" id="photoAlertModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">သတိပေးချက်</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ကျေးဇူးပြု၍ ကျောင်းသား၏ဓာတ်ပုံကို တင်ပေးပါ
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">အိုကေ</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Your existing JavaScript here -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
   <script>
        // Enable serial number editing
        function enableSerialEdit() {
            const serialInput = document.getElementById('serial_no');
            serialInput.removeAttribute('readonly');
            serialInput.focus();
            document.querySelector('.edit-serial-btn').style.display = 'none';
        }

        // Function to fetch student data when serial number changes
        function fetchStudentData(serialNo) {
            if (serialNo.length === 5) {
                if(confirm('ဤအမှတ်နှင့်သက်ဆိုင်သော ကျောင်းသားအချက်အလက်များကို လာမည်။ ဆက်လုပ်ပါမည်လား?')) {
                    window.location.href = '?serial_no=' + serialNo;
                } else {
                    document.getElementById('serial_no').value = '';
                }
            }
        }

     
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.image-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}




        // Page navigation
        function nextPage(current, next) {
                document.getElementById('page' + current).classList.remove('active');
                document.getElementById('page' + next).classList.add('active');
            }

            function prevPage(current, prev) {
                document.getElementById('page' + current).classList.remove('active');
                document.getElementById('page' + prev).classList.add('active');
            }

        // Form validation
        function validateForm() {
            let isValid = true;

            // Check required fields
            $('#studentForm [required]').each(function() {
                if ($(this).val() === '') {
                    $(this).addClass('is-invalid');
                    isValid = false;
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            // Special validation for serial number
            const serialNo = $('#serial_no').val();
            if (serialNo.length !== 5 || !/^\d+$/.test(serialNo)) {
                $('#serial_no').addClass('is-invalid');
                isValid = false;
            }

            // Check if image is uploaded
            if ($('#fileupload').val() === '') {
                $('#photoAlertModal').modal('show');
                isValid = false;
            }

            return isValid;
        }

        // Function to go to payment
        

        // Handle form submission
        $('#studentForm').on('submit_form', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            return true;
        });

        // Myanmar digits validation
        function allowOnlyMyanmarDigits(input) {
            var myanmarDigits = ['၀', '၁', '၂', '၃', '၄', '၅', '၆', '၇', '၈', '၉'];
            var value = input.value;
            var newValue = '';

            for (var i = 0; i < value.length; i++) {
                if (myanmarDigits.includes(value[i])) {
                    newValue += value[i];
                }
            }

            input.value = newValue;
        }
    </script>
</body>
</html>
