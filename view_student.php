<?php
session_start();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student</title>
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
        .info-label {
            font-weight: bold;
            color: #495057;
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
        <h1 class="h2">Student Details</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="manage_students.php" class="btn btn-sm btn-outline-secondary me-2">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
            <a href="edit_student.php?serial_no=<?= $student['serial_no'] ?>" class="btn btn-sm btn-primary">
                <i class="bi bi-pencil"></i> Edit
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2 text-center mb-3">
                    <img src="<?= !empty($student['photo']) ? 'uploads/'.$student['photo'] : 'images/default-student.png' ?>" 
                         class="student-photo img-thumbnail" alt="Student Photo">
                </div>
                <div class="col-md-10">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">မြန်မာအမည်:</span> <?= htmlspecialchars($student['student_name_mm']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">English Name:</span> <?= htmlspecialchars($student['student_name_en']) ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">Serial No:</span> <?= htmlspecialchars($student['serial_no']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">Serial Code:</span> <?= htmlspecialchars($student['serial_code']) ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">Class:</span> <?= htmlspecialchars($student['class']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">Specialization:</span> <?= htmlspecialchars($student['specialization']) ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">NRC:</span> <?= htmlspecialchars($student['nrc']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">Phone:</span> <?= htmlspecialchars($student['phone']) ?></p>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">Gender:</span> <?= htmlspecialchars($student['gender']) ?></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><span class="info-label">Date of Birth:</span> <?= htmlspecialchars($student['dob']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Academic Information</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Academic Year:</span> <?= htmlspecialchars($student['academic_year_start']) ?> - <?= htmlspecialchars($student['academic_year_end']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Entry Year:</span> <?= htmlspecialchars($student['entry_year']) ?></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Entrance Exam Seat No:</span> <?= htmlspecialchars($student['entrance_exam_seat_number']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Entrance Exam Year:</span> <?= htmlspecialchars($student['entrance_exam_year']) ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="mb-1"><span class="info-label">Entrance Exam Center:</span> <?= htmlspecialchars($student['entrance_exam_center']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Personal Information</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Nationality:</span> <?= htmlspecialchars($student['nationality']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Religion:</span> <?= htmlspecialchars($student['religion']) ?></p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <p class="mb-1"><span class="info-label">Address:</span> 
                        <?= htmlspecialchars(
                            $student['address_house_no'] . ", " .
                            $student['address_street'] . ", " .
                            $student['address_quarter'] . ", " .
                            $student['address_village'] . ", " .
                            $student['address_township'] . ", " .
                            $student['address_district'] . ", " .
                            $student['address_region'] . ", " .
                            $student['address_state']
                        ) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5>Family Information</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Father's Name (Myanmar):</span> <?= htmlspecialchars($student['father_name_mm']) ?></p>
                    <p class="mb-1"><span class="info-label">Father's Name (English):</span> <?= htmlspecialchars($student['father_name_en']) ?></p>
                    <p class="mb-1"><span class="info-label">Father's NRC:</span> <?= htmlspecialchars($student['father_nrc']) ?></p>
                    <p class="mb-1"><span class="info-label">Father's Phone:</span> <?= htmlspecialchars($student['father_phone']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Mother's Name (Myanmar):</span> <?= htmlspecialchars($student['mother_name_mm']) ?></p>
                    <p class="mb-1"><span class="info-label">Mother's Name (English):</span> <?= htmlspecialchars($student['mother_name_en']) ?></p>
                    <p class="mb-1"><span class="info-label">Mother's NRC:</span> <?= htmlspecialchars($student['mother_nrc']) ?></p>
                    <p class="mb-1"><span class="info-label">Mother's Phone:</span> <?= htmlspecialchars($student['mother_phone']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5>Other Information</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Supporter Name:</span> <?= htmlspecialchars($student['supporter_name']) ?></p>
                    <p class="mb-1"><span class="info-label">Supporter Relation:</span> <?= htmlspecialchars($student['supporter_relation']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1"><span class="info-label">Supporter Phone:</span> <?= htmlspecialchars($student['supporter_phone']) ?></p>
                    <p class="mb-1"><span class="info-label">Grant Support:</span> <?= htmlspecialchars($student['grant_support']) ?></p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <p class="mb-1"><span class="info-label">Remarks:</span> <?= htmlspecialchars($student['remarks']) ?></p>
                </div>
            </div>
        </div>
    </div>
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