<?php
session_start();
require 'includes/db.php';
// Check if serial_no is provided
if (!isset($_POST['serial_no'])) {
    $_SESSION['error'] = "Student serial number not provided";
    header("Location: manage_students.php");
    exit();
}

$serial_no = (int)$_POST['serial_no'];

// Validate required fields
$required_fields = [
    'student_name_mm', 
    'student_name_en', 
    'nrc', 
    'class'
];

$errors = [];

foreach ($required_fields as $field) {
    if (empty(trim($_POST[$field]))) {
        $errors[] = ucfirst(str_replace('_', ' ', $field)) . " is required";
    }
}

// Process photo upload if provided
$photo = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/';
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    $file_info = $_FILES['photo'];
    
    // Validate file type
    if (!in_array($file_info['type'], $allowed_types)) {
        $errors[] = "Only JPG, PNG, and GIF images are allowed";
    }
    
    // Validate file size
    if ($file_info['size'] > $max_size) {
        $errors[] = "Image size must be less than 2MB";
    }
    
    if (empty($errors)) {
        // Generate unique filename
        $ext = pathinfo($file_info['name'], PATHINFO_EXTENSION);
        $filename = 'student_' . $serial_no . '_' . time() . '.' . $ext;
        $target_path = $upload_dir . $filename;
        
        if (move_uploaded_file($file_info['tmp_name'], $target_path)) {
            $photo = $filename;
            
            // Delete old photo if exists
            $stmt = $mysqli->prepare("SELECT photo FROM students WHERE serial_no = ?");
            $stmt->bind_param("i", $serial_no);
            $stmt->execute();
            $stmt->bind_result($old_photo);
            $stmt->fetch();
            $stmt->close();
            
            if ($old_photo && file_exists($upload_dir . $old_photo)) {
                unlink($upload_dir . $old_photo);
            }
        } else {
            $errors[] = "Failed to upload photo";
        }
    }
}

if (!empty($errors)) {
    $_SESSION['error'] = implode("<br>", $errors);
    header("Location: edit_student.php?serial_no=" . $serial_no);
    exit();
}

// Prepare the update query
$query = "UPDATE students SET 
    student_name_mm = ?, 
    student_name_en = ?, 
    nrc = ?, 
    phone = ?, 
    class = ?, 
    specialization = ?,
    gender = ?,
    dob = ?,
    entrance_exam_seat_number = ?,
    entrance_exam_year = ?,
    entrance_exam_center = ?,
    nationality = ?,
    religion = ?,
    address_house_no = ?,
    address_street = ?,
    address_quarter = ?,
    address_village = ?,
    address_township = ?,
    address_district = ?,
    address_region = ?,
    address_state = ?,
    father_name_mm = ?,
    father_name_en = ?,
    father_nrc = ?,
    father_phone = ?,
    mother_name_mm = ?,
    mother_name_en = ?,
    mother_nrc = ?,
    mother_phone = ?,
    supporter_name = ?,
    supporter_relation = ?,
    supporter_phone = ?,
    grant_support = ?,
    remarks = ?";

// Add photo to query if uploaded
if ($photo !== null) {
    $query .= ", photo = ?";
}

$query .= " WHERE serial_no = ?";

// Prepare statement
$stmt = $mysqli->prepare($query);

// Bind parameters
$params = [
    $_POST['student_name_mm'],
    $_POST['student_name_en'],
    $_POST['nrc'],
    $_POST['phone'],
    $_POST['class'],
    $_POST['specialization'],
    $_POST['gender'],
    $_POST['dob'],
    $_POST['entrance_exam_seat_number'],
    $_POST['entrance_exam_year'],
    $_POST['entrance_exam_center'],
    $_POST['nationality'],
    $_POST['religion'],
    $_POST['address_house_no'],
    $_POST['address_street'],
    $_POST['address_quarter'],
    $_POST['address_village'],
    $_POST['address_township'],
    $_POST['address_district'],
    $_POST['address_region'],
    $_POST['address_state'],
    $_POST['father_name_mm'],
    $_POST['father_name_en'],
    $_POST['father_nrc'],
    $_POST['father_phone'],
    $_POST['mother_name_mm'],
    $_POST['mother_name_en'],
    $_POST['mother_nrc'],
    $_POST['mother_phone'],
    $_POST['supporter_name'],
    $_POST['supporter_relation'],
    $_POST['supporter_phone'],
    $_POST['grant_support'],
    $_POST['remarks']
];

// Add photo to params if uploaded
if ($photo !== null) {
    $params[] = $photo;
}

$params[] = $serial_no;

// Determine types
$types = str_repeat('s', count($params));

// Bind parameters
$stmt->bind_param($types, ...$params);

// Execute the update
if ($stmt->execute()) {
    $_SESSION['success'] = "Student updated successfully";
} else {
    $_SESSION['error'] = "Error updating student: " . $stmt->error;
}

$stmt->close();

// Redirect back to view student page
header("Location: view_student.php?serial_no=" . $serial_no);
exit();
?>