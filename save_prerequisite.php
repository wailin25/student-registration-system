<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_id = $_POST['subject_id'];
    $prereq_id = $_POST['prerequisite_id'];

    if ($subject_id == $prereq_id) {
        header("Location: pre_requisite.php?error=Subject cannot be its own pre-requisite.");
        exit;
    }

    $stmt = $mysqli->prepare("UPDATE subjects SET prerequisite = ? WHERE id = ?");
    $stmt->bind_param("ii", $prereq_id, $subject_id);

    if ($stmt->execute()) {
        header("Location: pre_requisite.php?success=Pre-requisite assigned successfully.");
    } else {
        header("Location: pre_requisite.php?error=Failed to assign pre-requisite.");
    }

    $stmt->close();
    $mysqli->close();
}
