<?php
session_start();
require 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_code = trim($_POST['subject_code']);
    $short_name = trim($_POST['short_name']);
    $subject_name = trim($_POST['subject_name']);
    $sub_subject_name = trim($_POST['sub_subject_name']);
    $academic_year = trim($_POST['academic_year']);
    $credit_unit = (int)$_POST['credit_unit'];
    $prerequisite = trim($_POST['pre_requisite']);
    $faculty = trim($_POST['faculty']);
    $specialization = $_POST['specialization'] ?? []; // multiple values
    $class = trim($_POST['class']);
    $semester = trim($_POST['semester']);
    $type = trim($_POST['type']);

    // Validate required fields
    if (empty($subject_code) || empty($short_name) || empty($subject_name) || empty($academic_year) ||
        empty($credit_unit) || empty($faculty) || empty($specialization) || empty($class) ||
        empty($semester) || empty($type)) {
        header("Location: create_subject.php?error=Please fill in all required fields.");
        exit();
    }

    // Combine multiple specialization values into comma-separated string
    $specialization_str = implode(",", $specialization);

    // Check if subject_code already exists
    $stmt = $mysqli->prepare("SELECT * FROM subjects WHERE subject_code = ?");
    $stmt->bind_param("s", $subject_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        header("Location: create_subject.php?error=Subject code already exists.");
        exit();
    }
    $stmt->close();

    // Insert into database
    $stmt = $mysqli->prepare("INSERT INTO subjects 
        (subject_code, short_name, subject_name, sub_subject_name, academic_year, credit_unit, 
         pre_requisite, faculty, specialization, class, semester, type)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssissssss", 
        $subject_code, $short_name, $subject_name, $sub_subject_name, $academic_year, $credit_unit, 
        $prerequisite, $faculty, $specialization_str, $class, $semester, $type
    );

    if ($stmt->execute()) {
        header("Location: create_subject.php?success=Subject added successfully.");
    } else {
        header("Location: create_subject.php?error=Failed to add subject.");
    }

    $stmt->close();
    $mysqli->close();
} else {
    header("Location: create_subject.php");
    exit();
}
