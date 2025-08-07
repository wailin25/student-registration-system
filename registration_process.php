<?php
session_start();
require 'includes/header.php';
require 'includes/student_navbar.php';
require 'includes/db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect all form data
    $studentData = [
        'serial_no' => $_POST['serial_no'],
        'academic_year_start' => $_POST['academic_year_start'],
        'academic_year_end' => $_POST['academic_year_end'],
        'class' => $_POST['class'],
        'specialization' => $_POST['specialization'],
        'entry_year' => $_POST['entry_year'],
        'student_name_mm' => $_POST['student_name_my'],
        'student_name_en' => $_POST['student_name_en'],
        'gender' => $_POST['gender'],
        'dob' => $_POST['dob'],
        'birth_place' => $_POST['birth_place'],
        'entrance_exam_seat_number' => $_POST['entrance_exam_seat_number'],
        'entrance_exam_year' => $_POST['entrance_exam_year'],
        'entrance_exam_center' => $_POST['entrance_exam_center'],
        'nrc' => $_POST['nrc'],
        'citizen_status' => $_POST['citizen_status'],
        'nationality' => $_POST['nationality'],
        'religion' => $_POST['religion'],
        'address_house_no' => $_POST['address_house_no'],
        'address_street' => $_POST['address_street'],
        'address_quarter' => $_POST['address_quarter'],
        'address_village' => $_POST['address_village'],
        'address_township' => $_POST['address_township'],
        'address_region' => $_POST['address_region'],
        'father_name_mm' => $_POST['father_name_my'],
        'father_name_en' => $_POST['father_name_en'],
        'father_nationality' => $_POST['father_ethnicity'],
        'father_religion' => $_POST['father_religion'],
        'father_nrc' => $_POST['father_nrc'],
        'father_birth_place' => $_POST['father_birth_place'],
        'father_citizen_status' => $_POST['father_citizen_status'],
        'father_phone' => $_POST['father_phone'],
        'father_job' => $_POST['father_job'],
        'father_address_house_no' => $_POST['father_address_house_no'],
        'father_address_street' => $_POST['father_address_street'],
        'father_address_quarter' => $_POST['father_address_quarter'],
        'father_address_village' => $_POST['father_address_village'],
        'father_address_township' => $_POST['father_address_township'],
        'father_address_region' => $_POST['father_region'],
        'mother_name_mm' => $_POST['mother_name_my'],
        'mother_name_en' => $_POST['mother_name_en'],
        'mother_nationality' => $_POST['mother_ethnicity'],
        'mother_religion' => $_POST['mother_religion'],
        'mother_nrc' => $_POST['mother_nrc'],
        'mother_birth_place' => $_POST['mother_birth_place'],
        'mother_citizen_status' => $_POST['mother_citizen_status'],
        'mother_phone' => $_POST['mother_phone'],
        'mother_job' => $_POST['mother_job'],
        'mother_address_house_no' => $_POST['mother_address_house_no'],
        'mother_address_street' => $_POST['mother_address_street'],
        'mother_address_quarter' => $_POST['mother_address_quarter'],
        'mother_address_village' => $_POST['mother_address_village'],
        'mother_address_township' => $_POST['mother_address_township'],
        'mother_address_region' => $_POST['mother_region'],
        'phone' => $_POST['phone'],
        'supporter_name' => $_POST['supporter_name'],
        'supporter_relation' => $_POST['supporter_relation'],
        'supporter_job' => $_POST['supporter_job'],
        'supporter_address' => $_POST['supporter_address'],
        'supporter_phone' => $_POST['supporter_phone'],
        'grant_support' => $_POST['grant_support'],
        'signature_status' => $_POST['signature_status'],
        'current_home_no' => $_POST['house_no'],
        'current_street' => $_POST['street_no'],
        'current_quarter' => $_POST['quarter'],
        'current_village' => $_POST['village'],
        'current_township' => $_POST['current_township'],
        'current_phone' => $_POST['current_phone'],
        
    ];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/students/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileTmp = $_FILES['image']['tmp_name'];
        $fileName = basename($_FILES['image']['name']);
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png'];

        if (in_array($fileExt, $allowedExt)) {
            $newFileName = uniqid('student_') . '.' . $fileExt;
            $targetPath = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmp, $targetPath)) {
                $studentData['image_path'] = $targetPath;
            }
        }
    }

    // Save exam results
    $examResults = [];
    for ($i = 1; $i <= 5; $i++) {
        if (!empty($_POST["pass_class$i"])) {
            $examResults[] = [
                'pass_class' => $_POST["pass_class$i"],
                'pass_specialization' => $_POST["pass_specialization$i"],
                'pass_serial_no' => $_POST["pass_serial_no$i"],
                'pass_year' => $_POST["pass_year$i"],
                'pass_fail_status' => $_POST["pass_fail_status$i"]
            ];
        }
    }

    // Store data in session for payment page
    $_SESSION['student_data'] = $studentData;
    $_SESSION['exam_results'] = $examResults;

    // Redirect to payment page
    header("Location: payment.php");
    exit();
}
?>