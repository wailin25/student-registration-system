<?php
// process_transfer.php
require 'includes/db.php';
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $serialNo = $_POST['serial_no'] ?? '';
    $status = $_POST['status'] ?? '';
    $transferDate = $_POST['transfer_date'] ?? '';
    $remark = $_POST['remark'] ?? ''; // remark ကိုလက်ခံရယူခြင်း
    
    if (empty($serialNo) || empty($status) || empty($transferDate)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: transfer_student.php?serial_no=" . urlencode($serialNo));
        exit();
    }
    
    $stmt = $mysqli->prepare("UPDATE students SET status = ?, transfer_date = ?, remark = ? WHERE serial_no = ?");
    $stmt->bind_param("ssss", $status, $transferDate, $remark, $serialNo);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Student status updated successfully to '{$status}'.";
    } else {
        $_SESSION['error'] = "Error updating student status: " . $stmt->error;
    }
    
    $stmt->close();
    $mysqli->close();

    header("Location: manage_students.php");
    exit();
}
header("Location: manage_students.php");
exit();
?>