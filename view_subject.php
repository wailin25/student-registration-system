<?php
session_start();
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid subject ID.");
}

$subject_id = $_GET['id'];
$stmt = $mysqli->prepare("SELECT * FROM subjects WHERE id = ?");
$stmt->bind_param("s", $subject_id);
$stmt->execute();
$result = $stmt->get_result();
$subject = $result->fetch_assoc();

if (!$subject) {
    die("Subject not found.");
}
?>

<div class="d-flex">
    <?php require 'includes/sidebar.php'; ?>
    <div class="main-content p-4">
        <div class="container">
            <h4 class="mb-4 text-primary">Subject Details</h4>
            <table class="table table-bordered">
                <tr><th>Subject Code</th><td><?= htmlspecialchars($subject['subject_code']) ?></td></tr>
                <tr><th>Subject Name</th><td><?= htmlspecialchars($subject['subject_name']) ?></td></tr>
                <tr><th>Class</th><td><?= htmlspecialchars($subject['class']) ?></td></tr>
                <tr><th>Semester</th><td><?= $subject['semester'] == 1 ? 'Semester I' : 'Semester II' ?></td></tr>
            </table>
            <a href="manage_subjects.php" class="btn btn-secondary mt-3">Back</a>
        </div>
    </div>
</div>

<?php require 'includes/admin_footer.php'; ?>
