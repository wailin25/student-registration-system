<?php

require 'includes/db.php';

$serial_no = (int)$_GET['serial_no'];

// Check if student exists
$stmt = $mysqli->prepare("SELECT student_name_mm FROM students WHERE serial_no = ?");
$stmt->bind_param("i", $serial_no);
$stmt->execute();
$stmt->bind_result($student_name_mm);
$stmt->fetch();
$stmt->close();

if (!$student_name_mm) {
    $_SESSION['error'] = "Student not found";
    header("Location: manage_students.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $mysqli->prepare("DELETE FROM students WHERE serial_no = ?");
    $stmt->bind_param("i", $serial_no);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Student '$student_name_mm' deleted successfully";
    } else {
        $_SESSION['error'] = "Error deleting student: " . $stmt->error;
    }
    
    $stmt->close();
    header("Location: manage_students.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
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
        <h1 class="h2">Delete Student</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="manage_students.php" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-danger text-white">
            <h5 class="mb-0">Confirm Deletion</h5>
        </div>
        <div class="card-body">
            <p>Are you sure you want to delete the following student?</p>
            <div class="alert alert-warning">
                <strong>Name:</strong> <?= htmlspecialchars($student_name_mm) ?><br>
                <strong>Serial No:</strong> <?= htmlspecialchars($serial_no) ?>
            </div>
            <p class="text-danger"><strong>Warning:</strong> This action cannot be undone.</p>
            
            <form method="POST">
                <div class="d-flex justify-content-end">
                    <a href="view_student.php?serial_no=<?= $serial_no ?>" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-x-circle"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Confirm Delete
                    </button>
                </div>
            </form>
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