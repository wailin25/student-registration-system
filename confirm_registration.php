<?php
// Start the session and include database connection
session_start();
require 'includes/db.php';

// --- SECURITY CHECK ---
// Ensure the user is logged in and is an admin.
// if (!isset($_SESSION['authenticated']) ||
//     $_SESSION['authenticated'] !== true ||
//     !isset($_SESSION['user_role']) ||
//     $_SESSION['user_role'] !== 'admin') {
//     $_SESSION['status'] = "Access Denied! Please login as an admin.";
//     header('Location: login.php');
//     exit;
// }

// Check if the serial number is provided in the URL
if (!isset($_GET['serial_no']) || empty($_GET['serial_no'])) {
    $_SESSION['status'] = "Error: Student serial number not provided.";
    header('Location: manage_registrations.php');
    exit;
}

$serial_no = $_GET['serial_no'];

// Use a prepared statement to update the student's registration status
// SQL injection ကိုကာကွယ်ရန် Prepared Statements ကိုအသုံးပြုပါ
$update_student_sql = "UPDATE students SET registration_status = 'Confirmed' WHERE serial_no = ?";
$stmt_student = $mysqli->prepare($update_student_sql);

if ($stmt_student) {
    // Bind the serial number to the query
    $stmt_student->bind_param("s", $serial_no);

    // Execute the query
    if ($stmt_student->execute()) {
        // Also update the payment status to 'Paid' for consistency
        $update_payment_sql = "UPDATE payment SET pay_status = 'Paid' WHERE serial_no = ?";
        $stmt_payment = $mysqli->prepare($update_payment_sql);
        
        if ($stmt_payment) {
            $stmt_payment->bind_param("s", $serial_no);
            $stmt_payment->execute();
            $stmt_payment->close();
        }

        // Set a success message and redirect
        $_SESSION['status'] = "Registration for student ID " . htmlspecialchars($serial_no) . " has been successfully confirmed. ✅";
    } else {
        // Handle database execution error
        $_SESSION['status'] = "Error confirming registration: " . $stmt_student->error;
    }

    $stmt_student->close();
} else {
    // Handle database preparation error
    $_SESSION['status'] = "Error preparing statement: " . $mysqli->error;
}

// Redirect back to the registrations page
header('Location: manage_registrations.php');
exit;
?>