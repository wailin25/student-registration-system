<?php
// Start the session
session_start();

// Database connection
$mysqli = mysqli_connect("localhost", "root", "", "kowai");

// Receive serial_no from GET request
$serial_no = isset($_GET['serial_no']) ? $_GET['serial_no'] : '';

// Initialize values
$student_name_mm = "";
$student_name_en = "";

// Only query if serial_no is not empty
if (!empty($serial_no)) {
    // Use prepared statements for security
    $stmt = $mysqli->prepare("SELECT student_name_mm, student_name_en FROM students WHERE serial_no = ?");
    $stmt->bind_param("s", $serial_no);
    $stmt->execute();
    $stmt->bind_result($student_name_mm, $student_name_en);
    $stmt->fetch();
    $stmt->close();
}

// Return JSON response
echo json_encode([$student_name_mm, $student_name_en]);
exit;
?>
