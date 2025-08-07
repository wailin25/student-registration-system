<?php
session_start();
require 'includes/db.php';

$user_id = $_SESSION['user_id'] ?? '';
$new_class = $_POST['class'] ?? '';

// validate
$valid_classes = ['ပထမနှစ်', 'ဒုတိယနှစ်', 'တတိယနှစ်', 'စတုတ္ထနှစ်', 'ပဉ္စမနှစ်'];
if (!$user_id || !in_array($new_class, $valid_classes)) {
    header("Location: student_dashboard.php?error=1");
    exit();
}

// update
$stmt = $mysqli->prepare("UPDATE students SET class = ? WHERE user_id = ?");
$stmt->bind_param("si", $new_class, $user_id);

if ($stmt->execute()) {
    header("Location: student_dashboard.php?success=1");
} else {
    header("Location: student_dashboard.php?error=2");
}
$stmt->close();
?>