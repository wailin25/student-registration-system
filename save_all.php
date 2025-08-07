<?php
session_start();
require 'includes/db.php';

// Validate essential session data before proceeding
// FIX: serial_no is nested inside student_data array, so we must check for it there.
if (!isset($_SESSION['user_id']) || !isset($_SESSION['student_data']) || !isset($_SESSION['exam_results']) || !isset($_SESSION['student_data']['serial_no'])) {
    // If any session data is missing, terminate and provide a clear error message.
    die("Error: Invalid session data. Please complete the registration form and payment step properly.");
}

$user_id = $_SESSION['user_id'];
// FIX: Assign serial_no from the correct nested location in the session data.
$studentData = $_SESSION['student_data'];
$serial_no = $studentData['serial_no'];
$examResults = $_SESSION['exam_results'];

// Check if this page was accessed via a POST request from the payment form.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_payment'])) {

    // Validate and sanitize payment inputs from the POST request.
    $pay_date = filter_input(INPUT_POST, 'pay_date', FILTER_SANITIZE_STRING);
    $pay_method = filter_input(INPUT_POST, 'pay_method', FILTER_SANITIZE_STRING);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_VALIDATE_INT);
    
    if (!$pay_date || !$pay_method || $amount === false) {
        die("Error: Missing or invalid payment information.");
    }

    // Begin a database transaction to ensure all queries succeed or fail together.
    $mysqli->begin_transaction();

    try {
        // --- 1. Update the students table with all the registration data, including image_path ---
        $sql_update_student = "
            UPDATE students SET
            academic_year_start=?, academic_year_end=?, class=?, specialization=?, 
            student_name_mm=?, student_name_en=?, nrc=?, citizen_status=?, 
            nationality=?, religion=?, dob=?, birth_place=?, 
            entrance_exam_seat_number=?, entrance_exam_year=?, entrance_exam_center=?, 
            address_house_no=?, address_street=?, address_quarter=?, address_village=?, 
            address_township=?, address_region=?, phone=?, 
            father_name_mm=?, father_name_en=?, father_nrc=?, father_nationality=?, 
            father_religion=?, father_birth_place=?, father_citizen_status=?, 
            father_phone=?, father_job=?, father_address_house_no=?, father_address_street=?, 
            father_address_quarter=?, father_address_village=?, father_address_township=?, 
            father_address_region=?, father_address_state=?, 
            mother_name_mm=?, mother_name_en=?, mother_nrc=?, mother_nationality=?, 
            mother_religion=?, mother_birth_place=?, mother_citizen_status=?, 
            mother_phone=?, mother_job=?, mother_address_house_no=?, mother_address_street=?, 
            mother_address_quarter=?, mother_address_village=?, mother_address_township=?, 
            mother_address_region=?, mother_address_state=?, 
            supporter_name=?, supporter_relation=?, supporter_job=?, supporter_address=?, 
            supporter_phone=?, grant_support=?, signature_status=?, 
            current_home_no=?, current_street=?, current_quarter=?, current_village=?, 
            current_township=?, current_phone=?, registration_status = ?, image_path = ?
            WHERE user_id=?
        ";

        $stmt = $mysqli->prepare($sql_update_student);
        if (!$stmt) {
            throw new Exception("Student update prepare failed: " . $mysqli->error);
        }
        
        $status_pending = 'Pending';
        // The bind_param string is now updated to include the image_path.
        // There are now 66 's' characters and 1 'i' for user_id.
        $stmt->bind_param(
            'sssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssi', // 67 's' and 1 'i'
            $studentData['academic_year_start'], $studentData['academic_year_end'], $studentData['class'], 
            $studentData['specialization'], $studentData['student_name_mm'], $studentData['student_name_en'], 
            $studentData['nrc'], $studentData['citizen_status'], $studentData['nationality'], 
            $studentData['religion'], $studentData['dob'], $studentData['birth_place'], 
            $studentData['entrance_exam_seat_number'], $studentData['entrance_exam_year'], $studentData['entrance_exam_center'], 
            $studentData['address_house_no'], $studentData['address_street'], $studentData['address_quarter'], 
            $studentData['address_village'], $studentData['address_township'], $studentData['address_region'], 
            $studentData['phone'], $studentData['father_name_mm'], $studentData['father_name_en'], 
            $studentData['father_nrc'], $studentData['father_nationality'], $studentData['father_religion'], 
            $studentData['father_birth_place'], $studentData['father_citizen_status'], $studentData['father_phone'], 
            $studentData['father_job'], $studentData['father_address_house_no'], $studentData['father_address_street'], 
            $studentData['father_address_quarter'], $studentData['father_address_village'], $studentData['father_address_township'], 
            $studentData['father_address_region'], $studentData['father_address_state'], 
            $studentData['mother_name_mm'], $studentData['mother_name_en'], $studentData['mother_nrc'], 
            $studentData['mother_nationality'], $studentData['mother_religion'], $studentData['mother_birth_place'], 
            $studentData['mother_citizen_status'], $studentData['mother_phone'], $studentData['mother_job'], 
            $studentData['mother_address_house_no'], $studentData['mother_address_street'], 
            $studentData['mother_address_quarter'], $studentData['mother_address_village'], $studentData['mother_address_township'], 
            $studentData['mother_address_region'], $studentData['mother_address_state'], 
            $studentData['supporter_name'], $studentData['supporter_relation'], $studentData['supporter_job'], 
            $studentData['supporter_address'], $studentData['supporter_phone'], $studentData['grant_support'], 
            $studentData['signature_status'], $studentData['current_home_no'], $studentData['current_street'], 
            $studentData['current_quarter'], $studentData['current_village'], $studentData['current_township'], 
            $studentData['current_phone'], $status_pending, $studentData['image_path'], $user_id
        );

        if (!$stmt->execute()) {
            throw new Exception("Failed to update student data: " . $stmt->error);
        }
        $stmt->close();

        // --- 2. Update answered_exam table ---
        // First, delete any existing records for this student to prevent duplicates.
        $sql_delete_exam = "DELETE FROM answered_exam WHERE serial_no = ?";
        $stmt_delete = $mysqli->prepare($sql_delete_exam);
        $stmt_delete->bind_param("i", $serial_no);
        $stmt_delete->execute();
        $stmt_delete->close();

        // Then, insert the new exam results from the session.
        if (!empty($examResults)) {
            $sql_insert_exam = "
                INSERT INTO answered_exam (serial_no, pass_class, pass_specialization, pass_serial_no, pass_year, pass_fail_status)
                VALUES (?, ?, ?, ?, ?, ?)
            ";
            $stmt_exam = $mysqli->prepare($sql_insert_exam);
            if (!$stmt_exam) {
                throw new Exception("Exam insert prepare failed: " . $mysqli->error);
            }
            foreach ($examResults as $exam) {
                $stmt_exam->bind_param(
                    'isssss',
                    $serial_no,
                    $exam['pass_class'],
                    $exam['pass_specialization'],
                    $exam['pass_serial_no'],
                    $exam['pass_year'],
                    $exam['pass_fail_status']
                );
                if (!$stmt_exam->execute()) {
                    throw new Exception("Failed to insert exam result: " . $stmt_exam->error);
                }
            }
            $stmt_exam->close();
        }

        // --- 3. Save payment info and uploaded payment slip ---
        $paymentSlipPath = null;
        if (isset($_FILES['pay_slip']) && $_FILES['pay_slip']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/payment_slips/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            $filename = basename($_FILES['pay_slip']['name']);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png', 'pdf'];
            if (!in_array($ext, $allowedExts)) {
                throw new Exception("Payment slip file type not allowed.");
            }
            $newFileName = uniqid('pay_') . '.' . $ext;
            $targetFile = $uploadDir . $newFileName;
            if (!move_uploaded_file($_FILES['pay_slip']['tmp_name'], $targetFile)) {
                throw new Exception("Failed to save payment slip.");
            }
            $paymentSlipPath = $targetFile;
        }
        
        $class_from_session = $studentData['class']; 
        $stmt_pay = $mysqli->prepare("
            INSERT INTO payment (user_id, serial_no, class, pay_date, pay_method, amount, pay_slip_path)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        if (!$stmt_pay) {
            throw new Exception("Payment insert prepare failed: " . $mysqli->error);
        }

        $stmt_pay->bind_param(
            'iisssis',
            $user_id,
            $serial_no,
            $class_from_session,
            $pay_date,
            $pay_method,
            $amount,
            $paymentSlipPath
        );

        if (!$stmt_pay->execute()) {
            throw new Exception("Failed to save payment info: " . $stmt_pay->error);
        }
        $stmt_pay->close();

        // Commit all changes if everything was successful.
        $mysqli->commit();

        // Clear session data to prevent accidental resubmission.
        unset($_SESSION['student_data'], $_SESSION['exam_results'], $_SESSION['serial_no']);

        // Redirect to a success page.
        header("Location: thank_you.php");
        exit;

    } catch (Exception $e) {
        // Rollback all changes if an error occurred.
        $mysqli->rollback();
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    // If the page was not accessed via the payment form, show an error.
    die("Invalid access method. This page must be accessed via a POST request from the payment form.");
}
?>
