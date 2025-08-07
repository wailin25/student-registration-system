<?php
session_start();
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';
require 'includes/sidebar.php';

// ✅ Admin-only access check
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$success = $error = "";

// ✅ Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);
    $class = trim($_POST['class']);
    $semester = trim($_POST['semester']);

    // ✅ Validate inputs
    if (empty($subject_code) || empty($subject_name) || empty($class) || empty($semester)) {
        $error = "All fields are required.";
    } else {
        // ✅ Check for duplicate subject_code
        $stmt_check = $mysqli->prepare("SELECT id FROM subjects WHERE subject_code = ?");
        $stmt_check->bind_param("s", $subject_code);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Subject code already exists.";
        } else {
            // ✅ Insert subject
            $stmt = $mysqli->prepare("INSERT INTO subjects (subject_code, subject_name, class, semester) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $subject_code, $subject_name, $class, $semester);

            if ($stmt->execute()) {
                $success = "Subject created successfully.";
                // Clear the form
                $subject_code = $subject_name = $class = $semester = '';
            } else {
                $error = "Failed to create subject.";
            }
        }
    }
}
?>

<div class="main-content p-4" id="mainContent">
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0">Create New Subject</h4>
            </div>
            <div class="card-body">
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php elseif ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="subject_code" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subject_code" name="subject_code"
                               value="<?php echo isset($subject_code) ? htmlspecialchars($subject_code) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name"
                               value="<?php echo isset($subject_name) ? htmlspecialchars($subject_name) : ''; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="class" name="class" required>
                            <option value="">-- Select Class --</option>
                            <?php
                            $classes = ['First Year', 'Second Year', 'Third Year', 'Fourth Year', 'Fifth Year'];
                            foreach ($classes as $cls) {
                                $selected = (isset($class) && $class === $cls) ? 'selected' : '';
                                echo "<option value=\"$cls\" $selected>$cls</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" id="semester" name="semester" required>
                            <option value="">-- Select Semester --</option>
                            <option value="I" <?php echo (isset($semester) && $semester === 'I') ? 'selected' : ''; ?>>I</option>
                            <option value="II" <?php echo (isset($semester) && $semester === 'II') ? 'selected' : ''; ?>>II</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Create Subject</button>
                    <a href="manage_subjects.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/admin_footer.php'; ?>
