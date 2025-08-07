```php
<?php
session_start();
require_once 'includes/db.php'; // Make sure this path is correct for your database connection

/**
 * This function receives the database connection ($mysqli) and saves student, exam,
 * and registration/payment data to the database.
 * It uses transactions to ensure data consistency.
 *
 * @param mysqli $mysqli The database connection object.
 * @return array An associative array with 'status' (success/error) and 'message'.
 */
function saveRegistrationDataPermanently($mysqli) {
    try {
        // Check if student data exists in session
        if (!isset($_SESSION['student_data'])) {
            return ['status' => 'error', 'message' => 'No student data found in session. Please start from registration form.'];
        }

        $studentData = $_SESSION['student_data'];
        $examResults = $_SESSION['exam_results'] ?? []; // Exam results from registration_form.php
        $paymentData = $_SESSION['payment_data'] ?? []; // Payment data from payment.php
        $serial_no = $studentData['serial_no'] ?? null;

        if (empty($serial_no)) {
            return ['status' => 'error', 'message' => 'Student Serial Number is missing.'];
        }

        // Start a database transaction
        $mysqli->begin_transaction();

        // 1. Save/Update Student Data in 'students' table
        // Check if student already exists
        $checkStmt = $mysqli->prepare("SELECT serial_no FROM students WHERE serial_no = ?");
        if (!$checkStmt) {
            throw new Exception("Prepare statement failed for student check: " . $mysqli->error);
        }
        $checkStmt->bind_param("s", $serial_no);
        $checkStmt->execute();
        $checkStmt->store_result();
        $studentExists = $checkStmt->num_rows > 0;
        $checkStmt->close();

        // Dynamically build the field list for student data
        $studentFields = [
            'academic_year_start', 'academic_year_end', 'class', 'specialization',
            'entry_year', 'student_name_mm', 'student_name_en', 'nrc', 'citizen_status',
            'nationality', 'religion', 'dob', 'birth_place', 'entrance_exam_seat_number',
            'entrance_exam_year', 'entrance_exam_center', 'address_house_no', 'address_street',
            'address_quarter', 'address_village', 'address_township', 'address_region', 'phone',
            'father_name_mm', 'father_name_en', 'father_nrc', 'father_ethnicity', 'father_religion',
            'father_birth_place', 'father_citizen_status', 'father_phone', 'father_job',
            'father_address_home', 'father_address_street', 'father_address_quarter',
            'father_address_village', 'father_address_township', 'father_address_region',
            'mother_name_mm', 'mother_name_en', 'mother_nrc', 'mother_ethnicity', 'mother_religion',
            'mother_birth_place', 'mother_citizen_status', 'mother_phone', 'mother_job',
            'mother_address_house_no', 'mother_address_street', 'mother_address_quarter',
            'mother_address_village', 'mother_address_township', 'mother_address_region',
            'supporter_name', 'supporter_relation', 'supporter_job', 'supporter_address',
            'supporter_phone', 'grant_support', 'signature_status', 'current_house_no',
            'current_street', 'current_quarter', 'current_village', 'current_township',
            'current_phone', 'image_path' // image_path from registration_form.php
        ];

        // Prepare values for binding, ensuring all fields are present (even if empty)
        $studentValues = [];
        $types = '';
        foreach ($studentFields as $field) {
            $studentValues[] = $studentData[$field] ?? null; // Use null for missing fields
            $types .= 's'; // Assume all are strings for simplicity; adjust as per your DB schema (i, d, b for int, float, blob)
        }

        if ($studentExists) {
            // Build UPDATE query
            $setClauses = [];
            foreach ($studentFields as $field) {
                $setClauses[] = "`{$field}` = ?";
            }
            $updateSql = "UPDATE students SET " . implode(', ', $setClauses) . " WHERE serial_no = ?";
            $updateStmt = $mysqli->prepare($updateSql);
            if (!$updateStmt) {
                throw new Exception("Prepare statement failed for student update: " . $mysqli->error);
            }
            // Bind values: all student values + serial_no at the end
            // ၁. parameter type string
            $types_with_serial = $types . 's';

            // ၂. parameter values တွေကို array နဲ့ တစ်နေရာထဲ စုပြီး
            $allParams = array_merge([$types_with_serial], $studentValues, [$serial_no]);

            // ၃. reference ဖြစ်အောင် ပြောင်းပေးတဲ့ helper function
            function refValues(array $arr): array {
                $refs = [];
                foreach ($arr as $key => $value) {
                    $refs[$key] = &$arr[$key];  // reference ပြောင်း
                }
                return $refs;
            }

            // ၄. call_user_func_array နဲ့ bind_param ခေါ်မယ်
            call_user_func_array([$updateStmt, 'bind_param'], refValues($allParams));

            $updateStmt->execute();
            $updateStmt->close();
        } else {
            // Build INSERT query
            $columns = implode(', ', array_map(function($field) { return "`{$field}`"; }, $studentFields));
            $placeholders = implode(', ', array_fill(0, count($studentFields), '?'));
            $insertSql = "INSERT INTO students (`serial_no`, {$columns}) VALUES (?, {$placeholders})";
            $insertStmt = $mysqli->prepare($insertSql);
            if (!$insertStmt) {
                throw new Exception("Prepare statement failed for student insert: " . $mysqli->error);
            }
            // Bind values: serial_no first, then all student values
            $insertStmt->bind_param('s' . $types, $serial_no, ...$studentValues);
            $insertStmt->execute();
            $insertStmt->close();
        }

        // 2. Save/Update Exam Results in 'answered_exam' table
        // Clear existing exam results for this student to prevent duplicates
        $delStmt = $mysqli->prepare("DELETE FROM answered_exam WHERE serial_no = ?");
        if (!$delStmt) {
            throw new Exception("Prepare statement failed for exam delete: " . $mysqli->error);
        }
        $delStmt->bind_param("s", $serial_no);
        $delStmt->execute();
        $delStmt->close();

        // Insert new exam results
        foreach ($examResults as $res) {
            // Ensure necessary fields exist before inserting
            if (!empty($res['pass_class']) && !empty($res['pass_specialization']) && !empty($res['pass_year'])) {
                $insStmt = $mysqli->prepare("INSERT INTO answered_exam (serial_no, pass_class, pass_specialization, pass_serial_no, pass_year, pass_fail_status) VALUES (?, ?, ?, ?, ?, ?)");
                if (!$insStmt) {
                    throw new Exception("Prepare statement failed for exam insert: " . $mysqli->error);
                }
                $pass_class = $res['pass_class'] ?? null;
                $pass_specialization = $res['pass_specialization'] ?? null;
                $pass_serial_no = $res['pass_serial_no'] ?? null;
                $pass_year = $res['pass_year'] ?? null;
                $pass_fail_status = $res['pass_fail_status'] ?? null;

                $insStmt->bind_param("ssssss", $serial_no, $pass_class, $pass_specialization, $pass_serial_no, $pass_year, $pass_fail_status);
                $insStmt->execute();
                $insStmt->close();
            }
        }

        // 3. Save Payment Data in 'payments' table
        // Assuming payment data is always new for a successful payment submission
        if (!empty($paymentData['academic_year']) && !empty($paymentData['amount']) && !empty($paymentData['pay_method']) && !empty($paymentData['pay_date'])) {
            $insPaymentStmt = $mysqli->prepare("INSERT INTO payments (
                student_serial_no, academic_year, amount, payment_method, payment_date, receipt_path, payment_status, created_at
            ) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            if (!$insPaymentStmt) {
                throw new Exception("Prepare statement failed for payment insert: " . $mysqli->error);
            }

            $academic_year = $paymentData['academic_year'];
            $amount = $paymentData['amount'];
            $pay_method = $paymentData['pay_method'];
            $pay_date = $paymentData['pay_date'];
            $receipt_path = $paymentData['pay_slip_path'] ?? null; // This should be the final, moved path from payment.php
            $payment_status = 'pending'; // Or 'completed' if payment is auto-approved

            $insPaymentStmt->bind_param("sissss",
                $serial_no, $academic_year, $amount, $pay_method, $pay_date, $receipt_path, $payment_status
            );
            $insPaymentStmt->execute();
            $insPaymentStmt->close();

            // Optionally, update student's current academic year and payment status in 'students' table
            $updateStudentPaymentStatusStmt = $mysqli->prepare("UPDATE students SET current_academic_year = ?, payment_status = ? WHERE serial_no = ?");
            if (!$updateStudentPaymentStatusStmt) {
                throw new Exception("Prepare statement failed for student payment status update: " . $mysqli->error);
            }
            $payment_status_for_student = 'paid'; // Set to 'paid' after payment record is inserted
            $updateStudentPaymentStatusStmt->bind_param("iss", $academic_year, $payment_status_for_student, $serial_no);
            $updateStudentPaymentStatusStmt->execute();
            $updateStudentPaymentStatusStmt->close();

        } else {
            // This might happen if payment.php did not correctly save payment_data to session
            // or if this function is called without full payment data.
            error_log("Payment data missing in session for serial_no: " . $serial_no);
        }

        // 4. Update 'registration' table status
        $regCheck = $mysqli->prepare("SELECT serial_no FROM registration WHERE serial_no = ?");
        if (!$regCheck) {
            throw new Exception("Prepare statement failed for registration check: " . $mysqli->error);
        }
        $regCheck->bind_param("s", $serial_no);
        $regCheck->execute();
        $regCheck->store_result();
        $regExists = $regCheck->num_rows > 0;
        $regCheck->close();

        if ($regExists) {
            $regStmt = $mysqli->prepare("UPDATE registration SET reg_date=CURDATE(), status='completed' WHERE serial_no=?");
            if (!$regStmt) {
                throw new Exception("Prepare statement failed for registration update: " . $mysqli->error);
            }
            $regStmt->bind_param("s", $serial_no);
        } else {
            $regStmt = $mysqli->prepare("INSERT INTO registration (serial_no, reg_date, status) VALUES (?, CURDATE(), 'completed')");
            if (!$regStmt) {
                throw new Exception("Prepare statement failed for registration insert: " . $mysqli->error);
            }
            $regStmt->bind_param("s", $serial_no);
        }
        $regStmt->execute();
        $regStmt->close();

        // If all operations are successful, commit the transaction
        $mysqli->commit();

        // Clear session data after successful save
        unset($_SESSION['student_data']);
        unset($_SESSION['exam_results']);
        unset($_SESSION['payment_data']);
        // Optionally unset CSRF token if it's no longer needed for subsequent pages
        unset($_SESSION['csrf_token']);

        return ['status' => 'success', 'message' => 'Registration and Payment data saved successfully!'];

    } catch (Exception $e) {
        // Rollback transaction on any error
        $mysqli->rollback();
        // Log the error for debugging purposes (e.g., to your server's error log)
        error_log("saveRegistrationDataPermanently Error for serial_no " . ($serial_no ?? 'N/A') . ": " . $e->getMessage());
        return ['status' => 'error', 'message' => 'An error occurred during registration: ' . $e->getMessage()];
    } finally {
        // Close database connection if it's still open (though it's better handled at the end of the script)
        // This is typically handled by the 'db.php' or by the script that calls this function.
    }
}

// How to use this function (Example in save_registration.php itself or called from payment.php)
// This file would typically be called after a successful payment processing in payment.php
// Or as a dedicated endpoint for final data saving.

// Example of how it might be called and handle the response:
// Ensure that this script is only callable after payment has been verified and all session data is ready.
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'GET') { // Adjust method as per your flow
    $result = saveRegistrationDataPermanently($mysqli);

    if ($result['status'] === 'success') {
        // Redirect to a success page or dashboard
        header('Location: student_dashboard.php?status=success');
        exit();
    } else {
        // Redirect back to payment page with an error or to a dedicated error page
        $_SESSION['error_message'] = $result['message'];
        header('Location: payment.php?status=error'); // Or confirmation.php
        exit();
    }
} else {
    // Handle direct access or invalid request method
    header('Location: index.php'); // Redirect to home or login page
    exit();
}

?>