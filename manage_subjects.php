<?php
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';
?>

<div class="d-flex">
    <?php require 'includes/sidebar.php'; ?>

    <div class="main-content p-4" id="mainContent" style="margin-top: 56px;">
        <div class="container-fluid">

            <!-- Dynamic Title -->
            <h4 class="text-center text-purple mb-4">
                <?= (isset($_GET['class']) && isset($_GET['specialization']) && $_GET['class'] !== '' && $_GET['specialization'] !== '') 
                    ? htmlspecialchars($_GET['class']) . ' ➩ ' . htmlspecialchars($_GET['specialization']) . ' ➩ Subjects List' 
                    : 'Manage Subjects by Class & Specialization' ?>
            </h4>

            <!-- Top Controls -->
            <div class="d-flex justify-content-between mb-4 flex-wrap">
                <!-- Dropdown -->
                <div class="mb-2">
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="manageSubjectsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Manage Subjects
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="manageSubjectsDropdown">
                            <li><a class="dropdown-item" href="create_subject.php">Create</a></li>
                            <li><a class="dropdown-item" href="manage_subjects.php">Manage</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Filter Form -->
                <form method="GET" class="d-flex flex-wrap">
                    <select name="class" class="form-select me-2 mb-2" required>
                        <option value="">Choose class</option>
                        <option value="First Year" <?= (@$_GET['class'] === 'First Year') ? 'selected' : '' ?>>First Year</option>
                        <option value="Second Year" <?= (@$_GET['class'] === 'Second Year') ? 'selected' : '' ?>>Second Year</option>
                        <option value="Third Year" <?= (@$_GET['class'] === 'Third Year') ? 'selected' : '' ?>>Third Year</option>
                        <option value="Fourth Year" <?= (@$_GET['class'] === 'Fourth Year') ? 'selected' : '' ?>>Fourth Year</option>
                        <option value="Fifth Year" <?= (@$_GET['class'] === 'Fifth Year') ? 'selected' : '' ?>>Fifth Year</option>
                    </select>

                    <select name="specialization" class="form-select me-2 mb-2" required>
                        <option value="">Choose specialization</option>
                        <option value="CS" <?= (@$_GET['specialization'] === 'CS') ? 'selected' : '' ?>>CS</option>
                        <option value="CT" <?= (@$_GET['specialization'] === 'CT') ? 'selected' : '' ?>>CT</option>
                        <option value="CST" <?= (@$_GET['specialization'] === 'CST') ? 'selected' : '' ?>>CST</option>
                    </select>

                    <button class="btn btn-success mb-2">View</button>
                </form>
            </div>

            <?php
            if (!empty($_GET['class']) && !empty($_GET['specialization'])) {
                $selectedClass = trim($_GET['class']);
                $selectedSpecialization = trim($_GET['specialization']);
            ?>

            <div class="row">
                <?php
                for ($sem = 1; $sem <= 2; $sem++) {
                    $semesterStr = ($sem === 1) ? 'I' : 'II';

                    echo "<div class='col-md-6 mb-4'>";
                    echo "<div class='card h-100'>";
                    echo "<div class='card-header fw-bold text-primary fs-5'>" . htmlspecialchars($selectedClass) . " / " . htmlspecialchars($selectedSpecialization) . " / Semester {$semesterStr}</div>";
                    echo "<div class='card-body table-responsive'>";
                    echo "<table class='table table-bordered table-hover'>";
                    echo "<thead class='table-dark'>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Credit Units</th>
                                <th style='width: 120px;'>Action</th>
                            </tr>
                          </thead><tbody>";

                    // Use FIND_IN_SET to match multi-valued specialization field
                    $stmt = $mysqli->prepare("SELECT * FROM subjects WHERE class = ? AND FIND_IN_SET(?, specialization) AND semester = ? ORDER BY subject_code ASC");
                    $stmt->bind_param("sss", $selectedClass, $selectedSpecialization, $semesterStr);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        while ($subject = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($subject['subject_code']) . "</td>
                                    <td>" . htmlspecialchars($subject['subject_name']) . "</td>
                                    <td>" . htmlspecialchars($subject['credit_unit']) . "</td>
                                    <td>
                                        <a href='edit_subject.php?subject_id=" . urlencode($subject['subject_id']) . "' class='btn btn-sm btn-primary'>Edit</a>
                                        <a href='delete_subject.php?subject_id=" . urlencode($subject['subject_id']) . "' class='btn btn-sm btn-danger ms-1'
                                           onclick='return confirm(\"Are you sure you want to delete this subject?\");'>Delete</a>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No subjects found for this semester.</td></tr>";
                    }

                    echo "</tbody></table>";
                    echo "</div></div></div>";
                }
                ?>
            </div>

            <?php } else { ?>
                <div class="alert alert-info text-center">
                    Please select both <strong>Class</strong> and <strong>Specialization</strong> to view subjects.
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<?php require 'includes/admin_footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll to main content
        document.querySelector('.main-content').scrollIntoView({ behavior: 'smooth' });
    }); 
</script>
</body>         
</html>
