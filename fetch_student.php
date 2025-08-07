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
$academic_year_start = "";
$academic_year_end = "";
$image_path = "";
$class="";
$specialization = "";
$serial_code = "";
$entry_year = "";
$gender = "";
$dob = "";
$birth_place = "";
$entrance_exam_year = "";
$entrance_exam_seat_number = "";
$entrance_exam_center = "";
$nrc="";
$citizen_status = "";
$nationality = "";
$religion = "";
$address_house_no = "";
$address_street = "";
$address_township = "";
$address_state = "";
$address_quarter= "";
$address_village = "";
$address_district = "";
$father_name_mm = "";
$father_name_en = "";
$father_nrc = "";
$father_nationality = "";
$father_religion = "";
$father_birth_place = "";
$father_citizen_status = "";
$father_phone = "";
$father_job = "";
$father_address_house_no = "";
$father_address_street = "";
$father_address_quarter = "";
$father_address_village = "";
$father_address_township = "";
$father_address_region = "";
$father_address_state = "";
$mother_name_mm = "";
$mother_name_mother_name_en = "";
$mother_nrc = "";
$mother_nationality = "";
$mother_religion = "";
$mother_birth_place = "";
$mother_citizen_status = "";
$mother_phone = "";
$mother_job = "";
$mother_address_house_no = "";
$mother_address_street = "";
$mother_address_quarter = "";
$mother_address_village = "";
$mother_address_township = "";
$mother_address_region = "";
$mother_address_state = "";
$phone="";
$supporter_name="";
$supporter_relation="";
$supporter_job="";
$supporter_address="";
$supporter_phone = "";
$grant_support = "";
$signature_status= "";
$remarks="";
$current_home_no = "";
$current_street = "";
$current_quarter="";
$current_village = "";
$current_township = "";
$current_phone = "";
$current_year = "";
$current_month = "";
$current_day = "";


// Only query if serial_no is not empty
if (!empty($serial_no)) {
    // Use prepared statements for security
    $stmt = $mysqli->prepare("SELECT * FROM students WHERE serial_no = ?");
    $stmt->bind_param("s", $serial_no);
    $stmt->execute();
    $stmt->bind_result($student_name_mm, $student_name_en,
    $academic_year_start, $academic_year_end,
     $image_path, $class, $specialization,
      $serial_code, $entry_year, $gender, $dob,
       $birth_place, $entrance_exam_year,
        $entrance_exam_seat_number, $entrance_exam_center,
         $nrc, $citizen_status, $nationality, $religion,
          $address_house_no, $address_street, $address_township, 
          $address_state, $address_quarter,$address_village,
          $address_district,$father_name_mm,$father_name_en,
          $father_nrc,$father_nationality,$father_religion,
          $father_birth_place,$father_citizen_status,$father_phone,$father_job,
          $father_address_house_no,$father_address_street,$father_address_quarter,
          $father_address_village,$father_address_township,$father_address_region,
          $father_address_state,$mother_name_mm,$mother_name_en,$mother_nrc,$mother_nationality,
          $mother_religion,$mother_birth_place,$mother_citizen_status,$mother_phone,$mother_job,
          $mother_address_house_no,
          $mother_address_street,$mother_address_quarter,
          $mother_address_village,$mother_address_township,$mother_address_region,
          $mother_address_state,$phone, $supporter_name, $supporter_relation, $supporter_job, 
          $supporter_address, $supporter_phone, $grant_support, $signature_status, $remarks, 
          $current_home_no, $current_street, $current_quarter, $current_village, $current_township,
           $current_phone, $current_year, $current_month, $current_day);

    $stmt->fetch();
    $stmt->close();
}

// Return JSON response
echo json_encode([$student_name_mm, $student_name_en,
$academic_year_start, $academic_year_end,
$image_path, $class, $specialization,
    $serial_code, $entry_year, $gender, $dob,
$birth_place, $entrance_exam_year,
$entrance_exam_seat_number, $entrance_exam_center,
$nrc, $citizen_status, $nationality, $religion,
$address_house_no, $address_street, $address_township,
$address_state, $address_quarter, $address_village,
$address_district, $father_name_mm, $father_name_en,
$father_nrc
, $father_nationality, $father_religion,
$father_birth_place, $father_citizen_status, $father_phone, $father_job
, $father_address_house_no,
$father_address_street, $father_address_quarter,
$father_address_village, $father_address_township, $father_address_region,
$father_address_state, $mother_name_mm, $mother_name_en, $mother_nrc, $mother,
$mother_nationality, $mother_religion, $mother_birth_place, $mother_citizen_status, $mother_phone, $mother_job,
$mother_address_house_no,
$mother_address_street, $mother_address_quarter,
$mother_address_village, $mother_address_township, $mother_address_region,
$mother_address_state, $phone, $supporter_name, $supporter_relation, $supporter_job,
$supporter_address, $supporter_phone, $grant_support, $signature_status, $remarks,
$current_home_no, $current_street, $current_quarter, $current_village, $current_township,
$current_phone, $current_year, $current_month, $current_day
]);
exit;
?>
