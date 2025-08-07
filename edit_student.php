<?php

require 'includes/db.php';

$serial_no = (int)$_GET['serial_no'];

// Get student data
$stmt = $mysqli->prepare("SELECT * FROM students WHERE serial_no = ?");
$stmt->bind_param("i", $serial_no);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    $_SESSION['error'] = "Student not found";
    header("Location: manage_students.php");
    exit();
}

// Handle form submission
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $student_name_mm = trim($_POST['student_name_mm']);
    $student_name_en = trim($_POST['student_name_en']);
    $nrc = trim($_POST['nrc']);
    $phone = trim($_POST['phone']);
    $class = trim($_POST['class']);
    $specialization = trim($_POST['specialization']);
    
    // Basic validation
    if (empty($student_name_mm)) {
        $errors[] = "Myanmar name is required";
    }
    if (empty($student_name_en)) {
        $errors[] = "English name is required";
    }
    if (empty($nrc)) {
        $errors[] = "NRC is required";
    }
    if (empty($class)) {
        $errors[] = "Class is required";
    }
    
    if (empty($errors)) {
        // Update student record
        $stmt = $mysqli->prepare("UPDATE students SET 
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
            remarks = ?
            WHERE serial_no = ?");
        
        $stmt->bind_param("ssssssssssssssssssssssssssssssssssi",
            $student_name_mm,
            $student_name_en,
            $nrc,
            $phone,
            $class,
            $specialization,
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
            $_POST['remarks'],
            $serial_no
        );
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Student updated successfully";
            header("Location: view_student.php?serial_no=$serial_no");
            exit();
        } else {
            $errors[] = "Error updating student: " . $stmt->error;
        }
        
        $stmt->close();
    }
}

// Get unique values for dropdowns
$classes = $mysqli->query("SELECT DISTINCT class FROM students ORDER BY class")->fetch_all(MYSQLI_ASSOC);
$specializations = $mysqli->query("SELECT DISTINCT specialization FROM students ORDER BY specialization")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        #sidebar {
            width: 280px;
            height: 100vh;
            position: fixed;
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        #main-content {
            margin-left: 280px;
            transition: margin-left 0.3s ease;
            padding: 20px;
        }
        #main-content.full-width {
            margin-left: 0;
        }
        #sidebar-toggle {
            position: fixed;
            left: 290px;
            top: 70px;
            z-index: 1001;
            transition: left 0.3s ease;
        }
        #sidebar.hide {
            transform: translateX(-100%);
        }
        #sidebar.hide + #main-content {
            margin-left: 0;
        }
        #sidebar.hide ~ #sidebar-toggle {
            left: 10px;
        }
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #main-content {
                margin-left: 0;
            }
            #sidebar-toggle {
                left: 10px;
            }
        }
        .student-photo {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 5px;
        }
        .form-section {
            margin-bottom: 30px;
        }
        .section-title {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
            color: #0d6efd;
        }
    </style>
</head>
<body>
<?php include 'includes/admin_header.php'; ?>
<?php include 'includes/navbar.php'; ?>

<div id="sidebar" class="bg-dark text-white">
    <?php include 'includes/sidebar.php'; ?>
</div>

<div id="main-content" style="margin-top:50px;">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Student</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="view_student.php?serial_no=<?= $student['serial_no'] ?>" class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Cancel
            </a>
        </div>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="section-title">Basic Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 text-center mb-3">
                        <img src="<?= !empty($student['photo']) ? 'uploads/'.$student['photo'] : 'images/default-student.png' ?>" 
                             class="student-photo img-thumbnail mb-2" alt="Student Photo">
                        <input type="file" class="form-control" name="photo" accept="image/*">
                    </div>
                    <div class="col-md-10">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="student_name_mm" class="form-label">မြန်မာအမည်</label>
                                <input type="text" class="form-control" id="student_name_mm" name="student_name_mm" 
                                       value="<?= htmlspecialchars($student['student_name_mm']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="student_name_en" class="form-label">English Name</label>
                                <input type="text" class="form-control" id="student_name_en" name="student_name_en" 
                                       value="<?= htmlspecialchars($student['student_name_en']) ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nrc" class="form-label">NRC</label>
                                <input type="text" class="form-control" id="nrc" name="nrc" 
                                       value="<?= htmlspecialchars($student['nrc']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="phone" name="phone" 
                                       value="<?= htmlspecialchars($student['phone']) ?>">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="class" class="form-label">Class</label>
                                <select class="form-select" id="class" name="class" required>
                                    <option value="">Select Class</option>
                                    <?php foreach ($classes as $c): ?>
                                        <option value="<?= $c['class'] ?>" <?= $student['class'] === $c['class'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($c['class']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="specialization" class="form-label">Specialization</label>
                                <select class="form-select" id="specialization" name="specialization">
                                    <option value="">Select Specialization</option>
                                    <?php foreach ($specializations as $s): ?>
                                        <option value="<?= $s['specialization'] ?>" <?= $student['specialization'] === $s['specialization'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($s['specialization']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="Male" <?= $student['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" 
                                       value="<?= htmlspecialchars($student['dob']) ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="section-title">Academic Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="entrance_exam_seat_number" class="form-label">Entrance Exam Seat No</label>
                        <input type="text" class="form-control" id="entrance_exam_seat_number" name="entrance_exam_seat_number" 
                               value="<?= htmlspecialchars($student['entrance_exam_seat_number']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="entrance_exam_year" class="form-label">Entrance Exam Year</label>
                        <input type="text" class="form-control" id="entrance_exam_year" name="entrance_exam_year" 
                               value="<?= htmlspecialchars($student['entrance_exam_year']) ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="entrance_exam_center" class="form-label">Entrance Exam Center</label>
                        <input type="text" class="form-control" id="entrance_exam_center" name="entrance_exam_center" 
                               value="<?= htmlspecialchars($student['entrance_exam_center']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="section-title">Personal Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="nationality" class="form-label">Nationality</label>
                        <input type="text" class="form-control" id="nationality" name="nationality" 
                               value="<?= htmlspecialchars($student['nationality']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="religion" class="form-label">Religion</label>
                        <input type="text" class="form-control" id="religion" name="religion" 
                               value="<?= htmlspecialchars($student['religion']) ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <h6>Address</h6>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="address_house_no" class="form-label">House No</label>
                        <input type="text" class="form-control" id="address_house_no" name="address_house_no" 
                               value="<?= htmlspecialchars($student['address_house_no']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="address_street" class="form-label">Street</label>
                        <input type="text" class="form-control" id="address_street" name="address_street" 
                               value="<?= htmlspecialchars($student['address_street']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="address_quarter" class="form-label">Quarter</label>
                        <input type="text" class="form-control" id="address_quarter" name="address_quarter" 
                               value="<?= htmlspecialchars($student['address_quarter']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="address_village" class="form-label">Village</label>
                        <input type="text" class="form-control" id="address_village" name="address_village" 
                               value="<?= htmlspecialchars($student['address_village']) ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="address_township" class="form-label">Township</label>
                        <input type="text" class="form-control" id="address_township" name="address_township" 
                               value="<?= htmlspecialchars($student['address_township']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="address_district" class="form-label">District</label>
                        <input type="text" class="form-control" id="address_district" name="address_district" 
                               value="<?= htmlspecialchars($student['address_district']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="address_region" class="form-label">Region</label>
                        <input type="text" class="form-control" id="address_region" name="address_region" 
                               value="<?= htmlspecialchars($student['address_region']) ?>">
                    </div>
                    <div class="col-md-3">
                        <label for="address_state" class="form-label">State</label>
                        <input type="text" class="form-control" id="address_state" name="address_state" 
                               value="<?= htmlspecialchars($student['address_state']) ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="section-title">Family Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6>Father's Information</h6>
                        <div class="mb-3">
                            <label for="father_name_mm" class="form-label">မြန်မာအမည်</label>
                            <input type="text" class="form-control" id="father_name_mm" name="father_name_mm" 
                                   value="<?= htmlspecialchars($student['father_name_mm']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="father_name_en" class="form-label">English Name</label>
                            <input type="text" class="form-control" id="father_name_en" name="father_name_en" 
                                   value="<?= htmlspecialchars($student['father_name_en']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="father_nrc" class="form-label">NRC</label>
                            <input type="text" class="form-control" id="father_nrc" name="father_nrc" 
                                   value="<?= htmlspecialchars($student['father_nrc']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="father_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="father_phone" name="father_phone" 
                                   value="<?= htmlspecialchars($student['father_phone']) ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6>Mother's Information</h6>
                        <div class="mb-3">
                            <label for="mother_name_mm" class="form-label">မြန်မာအမည်</label>
                            <input type="text" class="form-control" id="mother_name_mm" name="mother_name_mm" 
                                   value="<?= htmlspecialchars($student['mother_name_mm']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="mother_name_en" class="form-label">English Name</label>
                            <input type="text" class="form-control" id="mother_name_en" name="mother_name_en" 
                                   value="<?= htmlspecialchars($student['mother_name_en']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="mother_nrc" class="form-label">NRC</label>
                            <input type="text" class="form-control" id="mother_nrc" name="mother_nrc" 
                                   value="<?= htmlspecialchars($student['mother_nrc']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="mother_phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="mother_phone" name="mother_phone" 
                                   value="<?= htmlspecialchars($student['mother_phone']) ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="section-title">Other Information</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="supporter_name" class="form-label">Supporter Name</label>
                        <input type="text" class="form-control" id="supporter_name" name="supporter_name" 
                               value="<?= htmlspecialchars($student['supporter_name']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="supporter_relation" class="form-label">Supporter Relation</label>
                        <input type="text" class="form-control" id="supporter_relation" name="supporter_relation" 
                               value="<?= htmlspecialchars($student['supporter_relation']) ?>">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="supporter_phone" class="form-label">Supporter Phone</label>
                        <input type="text" class="form-control" id="supporter_phone" name="supporter_phone" 
                               value="<?= htmlspecialchars($student['supporter_phone']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label for="grant_support" class="form-label">Grant Support</label>
                        <select class="form-select" id="grant_support" name="grant_support">
                            <option value="Yes" <?= $student['grant_support'] === 'Yes' ? 'selected' : '' ?>>Yes</option>
                            <option value="No" <?= $student['grant_support'] === 'No' ? 'selected' : '' ?>>No</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks" name="remarks" rows="3"><?= htmlspecialchars($student['remarks']) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <button type="submit" class="btn btn-primary me-2">
                <i class="bi bi-save"></i> Save Changes
            </button>
            <a href="view_student.php?serial_no=<?= $student['serial_no'] ?>" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const toggleBtn = document.getElementById('sidebar-toggle');
    
    // Toggle sidebar
    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('hide');
        mainContent.classList.toggle('full-width');
    });
    
    // For mobile view
    const mobileMediaQuery = window.matchMedia('(max-width: 768px)');
    
    function handleMobileView(e) {
        if (e.matches) {
            sidebar.classList.add('hide');
            mainContent.classList.add('full-width');
        } else {
            sidebar.classList.remove('hide');
            mainContent.classList.remove('full-width');
        }
    }
    
    // Initial check
    handleMobileView(mobileMediaQuery);
    
    // Listen for changes
    mobileMediaQuery.addListener(handleMobileView);
});
</script>
</body>
</html>