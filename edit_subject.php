<?php
session_start();
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';
require 'includes/sidebar.php';

// Validate subject_id
if (!isset($_GET['subject_id']) || !is_numeric($_GET['subject_id'])) {
    die("Invalid subject ID.");
}

$subject_id = $_GET['subject_id'];
$success = $error = "";

// Fetch subject data
$stmt = $mysqli->prepare("SELECT * FROM subjects WHERE subject_id = ?");
$stmt->bind_param("i", $subject_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Subject not found.");
}

$subject = $result->fetch_assoc();

// Handle update form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $subject_code = trim($_POST['subject_code']);
    $subject_name = trim($_POST['subject_name']);
    $class = trim($_POST['class']);
    $semester = trim($_POST['semester']);

    if (empty($subject_code) || empty($subject_name) || empty($class) || empty($semester)) {
        $error = "All fields are required.";
    } else {
        $update_stmt = $mysqli->prepare("UPDATE subjects SET subject_code = ?, subject_name = ?, class = ?, semester = ? WHERE id = ?");
        $update_stmt->bind_param("ssssi", $subject_code, $subject_name, $class, $semester, $subject_id);

        if ($update_stmt->execute()) {
            $success = "Subject updated successfully.";
            // Refresh subject data
            $subject = ['subject_code' => $subject_code, 'subject_name' => $subject_name, 'class' => $class, 'semester' => $semester];
        } else {
            $error = "Failed to update subject.";
        }
    }
}
?>

<div class="main-content p-4" id="mainContent" style="min-height: 100vh;margin-top: 56px;">
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Edit Subject</h4>
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
                        <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo htmlspecialchars($subject['subject_code']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo htmlspecialchars($subject['subject_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" name="class" id="class" required>
                            <option value="">-- Select Class --</option>
                            <?php
                            $classes = ['First Year', 'Second Year', 'Third Year', 'Fourth Year', 'Fifth Year'];
                            foreach ($classes as $cls) {
                                $selected = ($subject['class'] === $cls) ? 'selected' : '';
                                echo "<option value=\"$cls\" $selected>$cls</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="semester" class="form-label">Semester</label>
                        <select class="form-select" name="semester" id="semester" required>
                            <option value="">-- Select Semester --</option>
                            <option value="I" <?php if ($subject['semester'] === 'I') echo 'selected'; ?>>I</option>
                            <option value="II" <?php if ($subject['semester'] === 'II') echo 'selected'; ?>>II</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Update Subject</button>
                    <a href="manage_subjects.php" class="btn btn-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require 'includes/admin_footer.php'; ?>
