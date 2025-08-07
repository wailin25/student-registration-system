<?php
require 'includes/db.php';
require 'includes/admin_header.php';
require 'includes/navbar.php';
?>

<style>
    #wrapper {
        display: flex;
        flex-direction: row;
    }

    #wrapper.toggled .sidebar {
        display: none !important;
    }

    .main-content {
        transition: all 0.3s ease;
        width: 100%;
    }

    @media (min-width: 768px) {
        #wrapper:not(.toggled) .main-content {
            margin-left: 250px; /* Sidebar width */
        }

        #wrapper.toggled .main-content {
            margin-left: 0;
        }
    }
</style>

<div class="d-flex" id="wrapper">
    <?php require 'includes/sidebar.php'; ?>

    <div class="main-content p-4" style="margin-top: 56px;">
        <div class="container-fluid">

            <h4 class="text-center text-purple mb-4">
                Subject Pre-Requisite
            </h4>

            <form method="GET" class="d-flex flex-wrap mb-4">
                <select name="class" class="form-select me-2 mb-2" required>
                    <option value="">Choose Class</option>
                    <?php
                    $classes = ['First Year', 'Second Year', 'Third Year', 'Fourth Year', 'Fifth Year'];
                    foreach ($classes as $c) {
                        echo "<option value='$c'" . (@$_GET['class'] === $c ? " selected" : "") . ">$c</option>";
                    }
                    ?>
                </select>

                <select name="specialization" class="form-select me-2 mb-2" required>
                    <option value="">Choose Specialization</option>
                    <?php
                    $specs = ['CS', 'CT', 'CST'];
                    foreach ($specs as $s) {
                        echo "<option value='$s'" . (@$_GET['specialization'] === $s ? " selected" : "") . ">$s</option>";
                    }
                    ?>
                </select>

                <button class="btn btn-success mb-2">View</button>
            </form>

            <?php
            if (!empty($_GET['class']) && !empty($_GET['specialization'])) {
                $class = $_GET['class'];
                $spec = $_GET['specialization'];

                echo "<h5 class='text-center mb-4 text-primary'>$class ➝ $spec</h5>";
                echo "<div class='row'>";

                for ($sem = 1; $sem <= 2; $sem++) {
                    $semester = ($sem === 1) ? 'I' : 'II';

                    echo "<div class='col-md-6 mb-4'>";
                    echo "<h6 class='text-muted mb-2'>Semester $semester</h6>";

                    $stmt = $mysqli->prepare("SELECT * FROM subjects WHERE class = ? AND FIND_IN_SET(?, specialization) AND semester = ? ORDER BY subject_code ASC");
                    $stmt->bind_param("sss", $class, $spec, $semester);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $hasRow = false;

                    if ($result->num_rows > 0) {
                        echo "<div class='table-responsive'><table class='table table-bordered table-striped'>";
                        echo "<thead style='background:blue;'><tr><th>Subject Code</th><th>Subject Name</th><th>Pre-requisite</th></tr></thead><tbody>";

                        while ($row = $result->fetch_assoc()) {
                            $subjectCode = htmlspecialchars($row['subject_code']);
                            $subjectName = htmlspecialchars($row['subject_name']);
                            $preReq = trim($row['pre_requisite']);

                            if ($preReq === '-' || $preReq === '') continue;

                            $displayPre = '';
                            $check = $mysqli->prepare("SELECT subject_code FROM subjects WHERE subject_code = ?");
                            $check->bind_param("s", $preReq);
                            $check->execute();
                            $checkResult = $check->get_result();

                            if ($checkResult->num_rows > 0) {
                                $displayPre = $preReq;
                            }

                            echo "<tr>
                                    <td>$subjectCode</td>
                                    <td>$subjectName</td>
                                    <td>" . (!empty($displayPre) ? $displayPre : '-') . "</td>
                                  </tr>";
                            $hasRow = true;
                        }
                        echo "</tbody></table></div>";

                        if (!$hasRow) {
                            echo "<p class='text-muted'>No valid pre-requisite subjects found for Semester $semester.</p>";
                        }
                    } else {
                        echo "<p class='text-muted'>No subjects found for Semester $semester.</p>";
                    }

                    echo "</div>"; // col
                }

                echo "</div>"; // row
            } else {
                echo "<div class='alert alert-info text-center'>Please select both <strong>Class</strong> and <strong>Specialization</strong> to analyze.</div>";
            }
            ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.getElementById("sidebarToggle");
        if (toggleButton) {
            toggleButton.addEventListener("click", function () {
                document.getElementById("wrapper").classList.toggle("toggled");
            });
        }
    });
</script>

<?php require 'includes/admin_footer.php'; ?>
